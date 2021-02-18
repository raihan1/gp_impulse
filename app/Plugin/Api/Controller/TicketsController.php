<?php
App::uses( 'ApiAppController', 'Api.Controller' );

/**
 * Tickets Controller
 */
class TicketsController extends ApiAppController {
    
    public $uses = array( 'Ticket', 'TrService', 'TrClass', 'Supplier', 'Service', 'SubCenter', 'SubCenterBudget' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Ticket List
     */
    public function tr_list() {
        try {
            if( empty( $_REQUEST['last_sync_time'] ) ) {
                throw new Exception( 'Please provide last sync time.', STATUS_INPUT_UNACCEPTABLE );
            }
            
            $conditions = array(
                'OR'                => array(
                    'Ticket.created >= '  => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                    'Ticket.modified >= ' => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                ),
                'Ticket.is_deleted' => array( YES, NO ),
            );
            if( $this->loginUser['User']['role'] == SUPPLIER ) {
                $conditions['Ticket.supplier'] = $this->loginUser['Supplier']['name'];
            }
            else {
                $conditions['Ticket.sub_center'] = $this->loginUser['SubCenter']['sub_center_name'];
            }
            
            $data = $this->Ticket->find( 'all', array( 'conditions' => $conditions, 'contain' => FALSE, 'noStatus' => TRUE ) );
            if( !empty( $data ) ) {
                $i = 0;
                foreach( $data as $d ) {
                    if( $d['Ticket']['is_deleted'] == YES || $d['Ticket']['invoice_id'] != 0 ) {
                        $d['Ticket']['status'] = INACTIVE;
                    }
                    $this->output['result'][ $i ]['Ticket'] = $d['Ticket'];
                    
                    //<editor-fold desc="TrService" defaultstate="collapsed">
                    $this->output['result'][ $i ]['Ticket']['TrService'] = array();
                    $trService = $this->TrService->find( 'all', array(
                        'conditions' => array(
                            'TrService.ticket_id'  => $d['Ticket']['id'],
                            'TrService.is_deleted' => array( YES, NO ),
                        ),
                        'contain'    => FALSE,
                        'noStatus'   => TRUE,
                    ) );
                    if( !empty( $trService ) ) {
                        foreach( $trService as $trs ) {
                            if( $trs['TrService']['is_deleted'] == YES ) {
                                $trs['TrService']['status'] = INACTIVE;
                            }
                            $this->output['result'][ $i ]['Ticket']['TrService'][] = $trs['TrService'];
                        }
                    }
                    //</editor-fold>
                    
                    $i++;
                }
            }
            
            $this->output['last_sync_time'] = date( 'Y-m-d H:i:s' );
            $this->output['message'] = count( $this->output['result'] ) . ' Ticket found.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Ticket Create
     */
    public function tr_create() {
        try {
            //<editor-fold desc="Budget Validation" defaultstate="collapsed">
            $mainTypes = Configure::read( 'mainTypes' );
            $mainType = $this->WarrantyLookup->getMainType( $this->input['data']['tr_class'] );
            
            if( in_array( $mainType, $mainTypes ) && ( !isset( $this->input['data']['confirm'] ) || $this->input['data']['confirm'] != YES ) ) {
                $min_cost = $this->SubCenter->find( 'first', array( 'conditions' => array( 'SubCenter.id' => $this->loginUser['User']['sub_center_id'] ), 'contain' => FALSE ) );
                
                $budget_data = $this->SubCenterBudget->find( 'first', array(
                    'contain'    => 'SubCenter',
                    'conditions' => array(
                        'SubCenter.id'          => $this->loginUser['User']['sub_center_id'],
                        'SubCenterBudget.month' => date( 'm' ),
                        'SubCenterBudget.year'  => date( 'Y' ),
                    ),
                ));
                
                $total_budget = $budget_data['SubCenterBudget'][ $mainType . '_initial_budget' ];
                $current_budget = $budget_data['SubCenterBudget'][ $mainType . '_current_budget' ];
                $total_use = ( $total_budget - $current_budget ) + $min_cost['SubCenter'][ $mainType . '_min_budget' ];
                
                $get_cost_percent = ( round( ( $total_use / $total_budget ), 5 ) ) * 100;
                
                if( $get_cost_percent >= 80 && $get_cost_percent < 90 ) {
                    if( $budget_data['SubCenter']['eighty_percent_action'] == YES ) {
                        throw new Exception( 'You can not exceed 80% of Subcenter Budget. Please contact Administrator.', STATUS_FORBIDDEN );
                    }
                    else {
                        throw new Exception( 'You are exceeding 80% of Subcenter Budget. Are sure to continue?', STATUS_UNAUTHORIZED );
                    }
                }
                else if( $get_cost_percent >= 90 && $get_cost_percent < 100 ) {
                    if( $budget_data['SubCenter']['ninety_percent_action'] == YES ) {
                        throw new Exception( 'You can not exceed 90% of Subcenter Budget. Please contact Administrator.', STATUS_FORBIDDEN );
                    }
                    else {
                        throw new Exception( 'You are exceeding 90% of Subcenter Budget. Are sure to continue?', STATUS_UNAUTHORIZED );
                    }
                }
                else if( $get_cost_percent >= 100 ) {
                    if( $budget_data['SubCenter']['hundred_percent_action'] == YES ) {
                        throw new Exception( 'You can not exceed 100% of Subcenter Budget. Please contact Administrator.', STATUS_FORBIDDEN );
                    }
                    else {
                        throw new Exception( 'You are exceeding 100% of Subcenter Budget. Are sure to continue?', STATUS_UNAUTHORIZED );
                    }
                }
            }
            //</editor-fold>
            
            //<editor-fold desc="Save Ticket" defaultstate="collapsed">
            $ticketData = array( 'Ticket' => array(
                'user_id'              => $this->loginUser['User']['id'],
                'department'           => $this->loginUser['User']['department'],
                'region'               => $this->loginUser['Region']['region_name'],
                'sub_center'           => $this->loginUser['SubCenter']['sub_center_name'],
                'site'                 => $this->input['data']['site'],
                'project'              => $this->input['data']['project'],
                'asset_group'          => $this->input['data']['asset_group'],
                'asset_number'         => $this->input['data']['asset_number'],
                'tr_class'             => $this->input['data']['tr_class'],
                'supplier'             => $this->input['data']['supplier'],
                'supplier_category'    => $this->input['data']['supplier_category'],
                'comment'              => $this->input['data']['comment'],
                'received_at_supplier' => date( 'Y-m-d H:i:s', strtotime( $this->input['data']['received_at_supplier'] ) ),
                'complete_date'        => date( 'Y-m-d H:i:s', strtotime( $this->input['data']['complete_date'] ) ),
                'created_by'           => $this->loginUser['User']['id'],
                'stage'                => SUPPLIER_STAGE,
            ) );
            if( !empty( $this->input['data']['id'] ) ) {
                $ticketData['Ticket']['id'] = $this->input['data']['id'];
            }
            
            $this->Ticket->create();
            if( !$this->Ticket->save( $ticketData ) ) {
                throw new Exception( 'Failed to save ticket data', STATUS_SERVER_ERROR );
            }
            $data = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => $this->Ticket->id ), 'contain' => FALSE ) );
            //</editor-fold>
            
            //<editor-fold desc="Send Email" defaultstate="collapsed">
            $supplierEmail = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.name' => $this->input['data']['supplier'] ), 'contain' => FALSE ) );
            $subCenterUsers = $this->User->find( 'all', array(
                'conditions' => array(
                    'User.id !='         => $this->loginUser['User']['id'],
                    'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
                    'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                ),
                'contain'    => FALSE,
                'fields'     => array( 'User.email' ),
            ) );
            $ccEmails = $this->loginUser['User']['email'];
            foreach( $subCenterUsers as $u ) {
                $ccEmails .= ", {$u['User']['email']}";
            }
            $message = '<div>
                            <p style="color: #5B5861; line-height: 22px;">
                                Dear ' . $data['Ticket']['supplier'] . ',
                                <br /><br />SITE NAME: ' . $data['Ticket']['site'] . '
                                <br />SUBCENTER: ' . $data['Ticket']['sub_center'] . '
                                <br />REGION: ' . $data['Ticket']['region'] . '
                                <br />TR NUMBER: ' . $data['Ticket']['id'] . '
                                <br />ASSET GROUP: ' . $data['Ticket']['asset_group'] . '
                                <br />SUPPLIER NAME: ' . $data['Ticket']['supplier'] . '
                                <br />TR CREATION DATE: ' . date( 'j-M-y G:i:s A', strtotime( $data['Ticket']['created'] ) ) . '
                                <br />RECEIVED AT SUPPLIER SITE: ' . date( 'j-M-y G:i:s A', strtotime( $data['Ticket']['received_at_supplier'] ) ) . '
                                <br />TR CLASS: ' . $data['Ticket']['tr_class'] . '
                                <br />TR ISSUER: ' . $this->loginUser['User']['name'] . '
                                <br />PROPOSED COMPLETION DATE &amp; TIME: ' . ( !empty( $data['Ticket']['complete_date'] ) ? date( 'j-M-y G:i:s A', strtotime( $data['Ticket']['complete_date'] ) ) : '' ) . '
                                <br />TR COMMENT: ' . $data['Ticket']['comment'] . '
                            </p>
                        </div>';
            $emailResult = 1;
            if( !$this->sendEmailGP( $supplierEmail['Supplier']['email'], $ccEmails, "{$data['Ticket']['id']}_{$data['Ticket']['site']}_{$data['Ticket']['tr_class']}", $message ) ) {
                $emailResult = 0;
            }
            
            $this->loadModel( 'Notification' );
            $this->Notification->create();
            $this->Notification->save( array( 'Notification' => array(
                'ticket_id' => $this->Ticket->id,
                'type'      => 0,
                'receiver'  => $supplierEmail['Supplier']['email'],
                'result'    => $emailResult,
            ) ) );
            //</editor-fold>
            
            //<editor-fold desc="Send SMS" defaultstate="collapsed">
            $users = $this->User->find( 'all', array(
                'conditions' => array(
                    'OR'          => array(
                        array(
                            'User.role'     => SUPPLIER,
                            'Supplier.name' => $data['Ticket']['supplier'],
                        ),
                        array(
                            'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
                            'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                        ),
                    ),
                    'User.status' => ACTIVE,
                ),
                'contain'    => array( 'Supplier.name' ),
                'fields'     => array( 'User.phone' ),
            ) );
            if( !empty( $users ) ) {
                $message = "TR: {$data['Ticket']['id']}"
                    . "\nSITE: {$data['Ticket']['site']}"
                    . "\nSC: {$data['Ticket']['sub_center']}"
                    . "\nCLASS: {$data['Ticket']['tr_class']}"
                    . "\nCreator: " . $this->loginUser['User']['name']
                    . "\nRcv Date: {$data['Ticket']['received_at_supplier']}"
                    . "\nComment: " . substr( $data['Ticket']['comment'], 0, 30 );
                if( strlen( $message ) > 160 ) {
                    $message = substr( $message, 0, 160 );
                }
                
                foreach( $users as $u ) {
                    $smsResult = $this->sendSMS( $u['User']['phone'], $message );
                    if( !$smsResult['result'] ) {
                        $errors[] = 'SMS send unsuccessful for TR ID: ' . $this->Ticket->id;
                    }
                    
                    $this->Notification->create();
                    $this->Notification->save( array( 'Notification' => array(
                        'ticket_id' => $this->Ticket->id,
                        'type'      => 1,
                        'receiver'  => $u['User']['phone'],
                        'result'    => $smsResult['result'] ? 1 : 0,
                        'msg_id'    => $smsResult['msgId'],
                    ) ) );
                }
            }
            //</editor-fold>
            
            $this->output['result']['id'] = $this->Ticket->id;
            $this->output['message'] = 'Ticket saved successfully';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Delete ticket
     */
    public function delete_tr() {
        try {
            //<editor-fold defaultstate="collapsed" desc="validation">
            if( empty( $this->input['data']['id'] ) ) {
                throw new Exception( 'Please provide Ticket ID.', STATUS_INPUT_UNACCEPTABLE );
            }
            
            $this->loadModel( 'Ticket' );
            $tr = $this->Ticket->find( 'first', array(
                'conditions' => array( 'Ticket.id' => intval( $this->input['data']['id'] ), 'Ticket.lock_status' => NULL ),
                'contain'    => FALSE,
            ) );
            if( empty( $tr ) ) {
                throw new Exception( 'Invalid Ticket ID.', STATUS_NOT_FOUND );
            }
            //</editor-fold>
            
            $this->Ticket->id = $tr['Ticket']['id'];
            if( !$this->Ticket->saveField( 'is_deleted', YES ) ) {
                throw new Exception( 'Failed to delete ticket', STATUS_SERVER_ERROR );
            }
            
            $this->output['result']['id'] = $this->input['data']['id'];
            $this->output['message'] = 'Ticket deleted successfully';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Lock/unlock ticket
     */
    public function status_change() {
        try {
            //<editor-fold defaultstate="collapsed" desc="validation">
            if( empty( $this->input['data']['id'] ) ) {
                throw new Exception( 'Please provide Ticket ID.', STATUS_INPUT_UNACCEPTABLE );
            }
            
            $this->loadModel( 'Ticket' );
            $tr = $this->Ticket->find( 'first', array(
                'conditions' => array( 'Ticket.id' => intval( $this->input['data']['id'] ), 'Ticket.lock_status' => NULL ),
                'contain'    => FALSE,
            ) );
            if( empty( $tr ) ) {
                throw new Exception( 'Invalid Ticket ID.', STATUS_NOT_FOUND );
            }
            //</editor-fold>
            
            $lockStatus = NULL;
            if( $this->input['data']['lock_status'] == LOCK ) {
                $lockStatus = LOCK;
            }
            
            $this->Ticket->id = $tr['Ticket']['id'];
            if( !$this->Ticket->saveField( 'lock_status', $lockStatus ) ) {
                throw new Exception( 'Failed to update ticket status', STATUS_SERVER_ERROR );
            }
            
            $this->output['result']['id'] = $tr['Ticket']['id'];
            $this->output['message'] = 'Ticket status updated successfully';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Ticket list for validation
     */
    public function tr_list_for_validation() {
        try {
            //<editor-fold desc="Validation" defaultstate="collapsed">
            if( empty( $_REQUEST['last_sync_time'] ) ) {
                throw new Exception( 'Please provide last sync time', STATUS_INPUT_UNACCEPTABLE );
            }
            //</editor-fold>
            
            //<editor-fold desc="Get ticket list" defaultstate="collapsed">
            $conditions = array(
                'OR'                => array(
                    'Ticket.created >= '  => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                    'Ticket.modified >= ' => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                ),
                'Ticket.is_deleted' => array( YES, NO ),
            );
            if( $this->loginUser['User']['role'] == SUPPLIER ) {
                $conditions['Ticket.supplier'] = $this->loginUser['Supplier']['name'];
            }
            else {
                $conditions['Ticket.sub_center'] = $this->loginUser['SubCenter']['sub_center_name'];
            }
            $data = $this->Ticket->find( 'all', array( 'conditions' => $conditions, 'contain' => FALSE, 'noStatus' => TRUE ) );
            //</editor-fold>
            
            if( !empty( $data ) ) {
                $i = 0;
                foreach( $data as $d ) {
                    if( $d['Ticket']['is_deleted'] == YES || $d['Ticket']['invoice_id'] != 0 ) {
                        $d['Ticket']['status'] = INACTIVE;
                    }
                    $this->output['result'][ $i ]['Ticket'] = $d['Ticket'];
                    
                    //<editor-fold desc="Services" defaultstate="collapsed">
                    $this->output['result'][ $i ]['Ticket']['TrService'] = array();
                    $trServices = $this->TrService->find( 'all', array(
                        'conditions' => array( 'TrService.ticket_id' => $d['Ticket']['id'], 'TrService.is_deleted' => array( YES, NO ) ),
                        'contain'    => array( 'LastService' ),
                        'noStatus'   => TRUE,
                    ) );
                    if( !empty( $trServices ) ) {
                        $j = 0;
                        foreach( $trServices as $trs ) {
                            if( $trs['TrService']['is_deleted'] == YES ) {
                                $trs['TrService']['status'] = INACTIVE;
                            }
                            $this->output['result'][ $i ]['Ticket']['TrService'][ $j ] = $trs['TrService'];
                            
                            $this->output['result'][ $i ]['Ticket']['TrService'][ $j ]['last_delivery_date'] = !empty( $trs['LastService']['delivery_date'] ) ? $trs['LastService']['delivery_date'] : '';
                            $this->output['result'][ $i ]['Ticket']['TrService'][ $j ]['last_quantity'] = !empty( $trs['LastService']['quantity'] ) ? $trs['LastService']['quantity'] : '';
                            
                            $j++;
                        }
                    }
                    //</editor-fold>
                    
                    $i++;
                }
            }
            
            $this->output['last_sync_time'] = date( 'Y-m-d H:i:s' );
            $this->output['message'] = count( $this->output['result'] ) . ' tickets found';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Validate ticket by TR Validator (approve/reject)
     */
    public function validate_tr() {
        try {
            $this->loadModel( 'Ticket' );
            $this->loadModel( 'SubCenterBudget' );
            $this->loadModel( 'TrService' );
            
            //<editor-fold desc="Validation" defaultstate="collapsed">
            if( empty( $this->input['data']['id'] ) ) {
                throw new Exception( 'Please provide Ticket ID', STATUS_INPUT_UNACCEPTABLE );
            }
    
            $tr = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => $this->input['data']['id'] ), 'contain' => FALSE ) );
            if( empty( $tr ) ) {
                throw new Exception( 'Invalid Ticket ID', STATUS_NOT_FOUND );
            }
            //</editor-fold>
            
            //<editor-fold desc="Save ticket" defaultstate="collapsed">
            $saveData = array( 'Ticket' => array(
                'id'               => $this->input['data']['id'],
                'approval_status'  => $this->input['data']['approval_status'],
                'validation_date'  => date( 'Y-m-d H:i:s' ),
                'tr_validation_by' => $this->loginUser['User']['id'],
            ) );
            if( $this->input['data']['approval_status'] == YES ) {
                $saveData['Ticket']['stage'] = INVOICE_CREATION_STAGE;
            }
            if( !$this->Ticket->save( $saveData ) ) {
                throw new Exception( 'Failed to save ticket data', STATUS_SERVER_ERROR );
            }
            //</editor-fold>
            
            if( $this->input['data']['approval_status'] == YES ) {
                //<editor-fold desc="Update budget" defaultstate="collapsed">
                $mainTypes = Configure::read( 'mainTypes' );
                $mainType = $this->WarrantyLookup->getMainType( $tr['Ticket']['tr_class'] );
                
                if( in_array( $mainType, $mainTypes ) ) {
                    $column = "{$mainType}_consumed_budget";
                    $this->SubCenterBudget->updateAll(
                        array( $column => $column . ' + ' . $tr['Ticket']['total_with_vat'] ),
                        array(
                            'sub_center_id' => $this->loginUser['User']['sub_center_id'],
                            'month'         => date( 'm' ),
                            'year'          => date( 'Y' ),
                        )
                    );
                }
                //</editor-fold>
            }
            else {
                //<editor-fold desc="Update TrService comment" defaultstate="collapsed">
                foreach( $this->input['data']['TrService'] as $trs ) {
                    if( !empty( $trs['comments'] ) ) {
                        $trService = $this->TrService->find( 'first', array( 'conditions' => array( 'TrService.id' => $trs['id'] ), 'contain' => FALSE ) );
                        
                        $latestComment = '<b>' . $this->loginUser['User']['name'] . "</b>: {$trs['comments']} (" . date( 'Y-m-d H:i:s' ) . ')';
                        $comments = ( !empty( $trService['TrService']['comments'] ) ? "{$trService['TrService']['comments']}<br />" : '' ) . $latestComment;
    
                        $this->TrService->id = $trs['id'];
                        if( !$this->TrService->saveField( 'comments', $comments ) ) {
                            throw new Exception( 'Failed to save service data', STATUS_SERVER_ERROR );
                        }
                    }
                }
                //</editor-fold>
            }
            
            $this->output['result']['id'] = $this->input['data']['id'];
            $this->output['message'] = 'Ticket status updated successfully.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
}