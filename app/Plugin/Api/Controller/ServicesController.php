<?php
App::uses( 'ApiAppController', 'Api.Controller' );

/**
 * Services Controller
 */
class ServicesController extends ApiAppController {
    
    public $uses = array( 'Service', 'Ticket', 'TrService' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Add/edit services (locked->pending and rejected->pending)
     */
    public function add_service() {
        try {
            //<editor-fold desc="Validation" defaultstate="collapsed">
            $tr = $this->Ticket->find( 'first', array(
                'conditions' => array( 'Ticket.id' => $this->input['data']['TrService'][0]['ticket_id'] ),
                'contain'    => FALSE,
            ) );
            if( empty( $tr ) ) {
                throw new Exception( 'Invalid Ticket ID: ' . $this->input['data']['TrService'][0]['ticket_id'], STATUS_NOT_FOUND );
            }
            
            $services = array();
            foreach( $this->input['data']['TrService'] as $id => $trs ) {
                $service = $this->Service->find( 'first', array(
                    'conditions' => array( 'Service.supplier_id' => $this->loginUser['User']['supplier_id'], 'Service.service_name' => $trs['service'] ),
                    'contain'    => FALSE,
                ) );
                if( empty( $service ) ) {
                    throw new Exception( 'Invalid Service: ' . $trs['service'], STATUS_NOT_FOUND );
                }
                $this->input['data']['TrService'][ $id ]['Service'] = $service['Service'];
                
                if( $trs['status'] == ACTIVE ) {
                    $quantity = floatval( $trs['quantity'] );
                    if( $quantity == 0 ) {
                        throw new Exception( 'Quantity cannot be zero', STATUS_INPUT_UNACCEPTABLE );
                    }
                    if( in_array( $trs['service'], $services ) ) {
                        throw new Exception( 'Duplicate item is not allowed', STATUS_INPUT_UNACCEPTABLE );
                    }
                    $services[] = $trs['service'];
                }
            }
            if( count( $services ) == 0 ) {
                throw new Exception( 'Please provide at least one valid service', STATUS_INPUT_UNACCEPTABLE );
            }
            //</editor-fold>
            
            $total = $vat_total = $total_with_vat = 0;
            
            //<editor-fold desc="Save services" defaultstate="collapsed">
            foreach( $this->input['data']['TrService'] as $trs ) {
                $quantity = floatval( $trs['quantity'] );
                $saveData = array( 'TrService' => array(
                    'ticket_id'           => $tr['Ticket']['id'],
                    'service'             => $trs['Service']['service_name'],
                    'service_desc'        => $trs['Service']['service_desc'],
                    'supplier'            => $tr['Ticket']['supplier'],
                    'quantity'            => $quantity,
                    'unit_price'          => $trs['Service']['service_unit_price'],
                    'vat'                 => $trs['Service']['vat'],
                    'unit_price_with_vat' => $trs['Service']['vat_plus_price'],
                    'total'               => $trs['Service']['service_unit_price'] * $quantity,
                    'vat_total'           => $trs['Service']['service_unit_price'] * $quantity * $trs['Service']['vat'] / 100,
                    'total_with_vat'      => $trs['Service']['vat_plus_price'] * $quantity,
                    'delivery_date'       => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                    'warranty_status'     => NO,
                    'status'              => $trs['status'],
                ) );
    
                $exist = $this->TrService->find( 'first', array(
                    'conditions' => array(
                        'TrService.ticket_id' => $tr['Ticket']['id'],
                        'TrService.service'   => $trs['Service']['service_name'],
                        'TrService.status'    => $trs['status'],
                    ),
                    'contain'    => FALSE,
                ) );
                if( $exist ) {
                    $saveData['TrService']['id'] = $exist['TrService']['id'];
                }
                
                $latestComment = empty( $trs['comments'] ) ? '' : ( '<b>' . $this->loginUser['User']['name'] . "</b>: {$trs['comments']} (" . date( 'Y-m-d H:i:s' ) . ')' );
                if( !empty( $trs['id'] ) ) {
                    $saveData['TrService']['id'] = $trs['id'];
                    $trService = $this->TrService->find( 'first', array( 'conditions' => array( 'TrService.id' => $trs['id'] ), 'contain' => FALSE ) );
                }
                $saveData['TrService']['comments'] = ( !empty( $trService['TrService']['comments'] ) ? "{$trService['TrService']['comments']}<br />" : '' ) . $latestComment;
                
                if( $trs['status'] == ACTIVE ) {
                    $conditions = array(
                        'Ticket.site'                 => $tr['Ticket']['site'],
                        'Ticket.supplier'             => $tr['Ticket']['supplier'],
                        'TrService.service'           => $trs['Service']['service_name'],
                        'TrService.delivery_date <= ' => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                        'TrService.status'            => ACTIVE,
                        'TrService.is_deleted'        => NO,
                    );
                    if( !empty( $trs['id'] ) ) {
                        $conditions['TrService.id !='] = $trs['id'];
                    }
                    $lastService = $this->TrService->find( 'first', array( 'conditions' => $conditions, 'contain' => array( 'Ticket.site' ) ) );
                    if( !empty( $lastService ) ) {
                        $saveData['TrService']['last_service'] = $lastService['TrService']['id'];
                        
                        //<editor-fold desc="Check Warranty" defaultstate="collapsed">
                        if( $trs['Service']['warranty_days'] > 0 || $trs['Service']['warranty_hours'] > 0 ) {
                            $warrantyHours = 24 * $trs['Service']['warranty_days'] + $trs['Service']['warranty_hours'];
                            
                            if( $lastService['TrService']['warranty_status'] == NO && strtotime( $lastService['TrService']['delivery_date'] ) >= strtotime( "-{$warrantyHours} hours", strtotime( $trs['delivery_date'] ) ) ) {
                                $saveData['TrService']['warranty_status'] = YES;
                                $saveData['TrService']['total'] = 0;
                                $saveData['TrService']['vat_total'] = 0;
                                $saveData['TrService']['total_with_vat'] = 0;
                            }
                        }
                        //</editor-fold>
                    }
                }
                
                $this->TrService->create();
                if( !$this->TrService->save( $saveData ) ) {
                    throw new Exception( 'Failed to save service data', STATUS_SERVER_ERROR );
                }
                
                $total += $saveData['TrService']['total'];
                $vat_total += $saveData['TrService']['vat_total'];
                $total_with_vat += $saveData['TrService']['total_with_vat'];
                
                $this->output['result']['id'][] = $this->TrService->id;
            }
            //</editor-fold>
            
            //<editor-fold desc="Update ticket" defaultstate="collapsed">
            $trData = array( 'Ticket' => array(
                'id'             => $tr['Ticket']['id'],
                'total'          => $total,
                'vat_total'      => $vat_total,
                'total_with_vat' => $total_with_vat,
                'pending_status' => PENDING,
                'stage'          => TR_VALIDATION_STAGE,
                'closing_date'   => date( 'Y-m-d H:i:s' ),
                'tr_closed_by'   => $this->loginUser['User']['id'],
            ) );
            $this->Ticket->create();
            if( !$this->Ticket->save( $trData, array( 'fieldList' => array_keys( $trData['Ticket'] ) ) ) ) {
                throw new Exception( 'Failed to save ticket data' );
            }
            //</editor-fold>
            
            $this->output['message'] = 'Services added successfully';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
}