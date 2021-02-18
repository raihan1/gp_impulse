<?php
App::uses( 'SupplierAppController', 'Supplier.Controller' );

/**
 * Tickets Controller
 *
 * @property WarrantyLookupComponent WarrantyLookup
 */
class TicketsController extends SupplierAppController {
    
    public $uses = array( 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Service', 'TrService', 'User' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return parent::isAuthorized( $user );
    }
    
    /**
     * Ticket List
     */
    public function index() {
        $sub_centers = $this->WarrantyLookup->getSubCenterList();
        $subCenterList = array();
        foreach( $sub_centers as $id => $name ) {
            $subCenterList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $sub_center = $this->Session->read( 'sub_center' );
        
        $this->set( array(
            'subCenterList'    => $subCenterList,
            'sub_center'       => $sub_center,
            'title_for_layout' => 'Ticket List',
        ) );
    }
    
    /**
     * Assigned tr actions via ajax datatable
     */
    public function assign_tr_data() {
        $result = array();
        
        //<editor-fold desc="Lock ticket" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'lock' ) {
            $ticket = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => intval( $this->request->data['customActionName'] ) ), 'contain' => FALSE ) );
            if( !empty( $ticket ) ) {
                $this->Ticket->id = intval( $this->request->data['customActionName'] );
                if( $this->Ticket->saveField( 'lock_status', LOCK ) ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The ticket has been locked.';
                }
                else {
                    $errors = '';
                    foreach( $this->Ticket->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = $errors;
                }
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Invalid Ticket ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.supplier'    => $this->loginUser['Supplier']['name'],
            'Ticket.lock_status' => NULL,
        );
        
        $sub_center = $this->Session->read( 'sub_center' );
        if( !empty( $this->request->data['sub_center'] ) ) {
            $this->Session->write( 'sub_center', $this->request->data['sub_center'] );
            $conditions['Ticket.sub_center'] = $this->request->data['sub_center'];
        }
        else if( !empty( $sub_center ) ) {
            $conditions['Ticket.sub_center'] = $sub_center;
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            2 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            8 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'date' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'date' ) {
                    $conditions["DATE({$col['model']})"] = date( 'Y-m-d', strtotime( $this->request->data[ $col['field'] ] ) );
                }
                else if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[ $col['field'] ] . '%';
                }
                else {
                    $conditions["{$col['model']}"] = $this->request->data[ $col['field'] ];
                }
            }
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'User.name' ),
            'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
            'offset'     => intval( $this->request->data['start'] ),
            'order'      => $order,
        ) );
        
        $this->set( array(
            'request' => $this->request->data,
            'result'  => $result,
            'data'    => $data,
            'total'   => $total,
        ) );
    }
    
    /**
     * Locked tr actions via ajax datatable
     */
    public function locked_tr_data() {
        $result = array();
        
        //<editor-fold desc="Unlock ticket" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'unlock' ) {
            $ticket = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => intval( $this->request->data['customActionName'] ) ), 'contain' => FALSE ) );
            if( !empty( $ticket ) ) {
                $this->Ticket->id = intval( $this->request->data['customActionName'] );
                if( $this->Ticket->saveField( 'lock_status', NULL ) ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The ticket has been unlocked.';
                }
                else {
                    $errors = '';
                    foreach( $this->Ticket->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = $errors;
                }
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Invalid Ticket ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.supplier'       => $this->loginUser['Supplier']['name'],
            'Ticket.lock_status'    => LOCK,
            'Ticket.pending_status' => NULL,
        );
        
        $sub_center = $this->Session->read( 'sub_center' );
        if( !empty( $this->request->data['sub_center'] ) ) {
            $this->Session->write( 'sub_center', $this->request->data['sub_center'] );
            $conditions['Ticket.sub_center'] = $this->request->data['sub_center'];
        }
        else if( !empty( $sub_center ) ) {
            $conditions['Ticket.sub_center'] = $sub_center;
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            2 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            8 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'date' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[ $col['field'] ] . '%';
                }
                else {
                    $conditions["{$col['model']}"] = $this->request->data[ $col['field'] ];
                }
            }
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'User.name' ),
            'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
            'offset'     => intval( $this->request->data['start'] ),
            'order'      => $order,
        ) );
        
        $this->set( array(
            'request' => $this->request->data,
            'result'  => $result,
            'data'    => $data,
            'total'   => $total,
        ) );
    }
    
    /**
     * Pending tr actions via ajax datatable
     */
    public function pending_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.supplier'        => $this->loginUser['Supplier']['name'],
            'Ticket.pending_status'  => PENDING,
            'Ticket.approval_status' => NULL,
        );
        
        $sub_center = $this->Session->read( 'sub_center' );
        if( !empty( $this->request->data['sub_center'] ) ) {
            $this->Session->write( 'sub_center', $this->request->data['sub_center'] );
            $conditions['Ticket.sub_center'] = $this->request->data['sub_center'];
        }
        else if( !empty( $sub_center ) ) {
            $conditions['Ticket.sub_center'] = $sub_center;
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            2 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            8 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'date' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[ $col['field'] ] . '%';
                }
                else {
                    $conditions["{$col['model']}"] = $this->request->data[ $col['field'] ];
                }
            }
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'User.name' ),
            'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
            'offset'     => intval( $this->request->data['start'] ),
            'order'      => $order,
        ) );
        
        $this->set( array(
            'request' => $this->request->data,
            'result'  => $result,
            'data'    => $data,
            'total'   => $total,
        ) );
    }
    
    /**
     * Approved tr actions via ajax datatable
     */
    public function approved_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.supplier'        => $this->loginUser['Supplier']['name'],
            'Ticket.approval_status' => APPROVE,
            'Ticket.invoice_id'      => 0,
        );
        
        $sub_center = $this->Session->read( 'sub_center' );
        if( !empty( $this->request->data['sub_center'] ) ) {
            $this->Session->write( 'sub_center', $this->request->data['sub_center'] );
            $conditions['Ticket.sub_center'] = $this->request->data['sub_center'];
        }
        else if( !empty( $sub_center ) ) {
            $conditions['Ticket.sub_center'] = $sub_center;
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            2 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            8 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'date' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[ $col['field'] ] . '%';
                }
                else {
                    $conditions["{$col['model']}"] = $this->request->data[ $col['field'] ];
                }
            }
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'User.name' ),
            'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
            'offset'     => intval( $this->request->data['start'] ),
            'order'      => $order,
        ) );
        
        $this->set( array(
            'request' => $this->request->data,
            'result'  => $result,
            'data'    => $data,
            'total'   => $total,
        ) );
    }
    
    /**
     * Rejected tr actions via ajax datatable
     */
    public function rejected_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.supplier'        => $this->loginUser['Supplier']['name'],
            'Ticket.approval_status' => DENY,
        );
        
        $sub_center = $this->Session->read( 'sub_center' );
        if( !empty( $this->request->data['sub_center'] ) ) {
            $this->Session->write( 'sub_center', $this->request->data['sub_center'] );
            $conditions['Ticket.sub_center'] = $this->request->data['sub_center'];
        }
        else if( !empty( $sub_center ) ) {
            $conditions['Ticket.sub_center'] = $sub_center;
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            8 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'date' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[ $col['field'] ] . '%';
                }
                else {
                    $conditions["{$col['model']}"] = $this->request->data[ $col['field'] ];
                }
            }
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'User.name' ),
            'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
            'offset'     => intval( $this->request->data['start'] ),
            'order'      => $order,
        ) );
        
        $this->set( array(
            'request' => $this->request->data,
            'result'  => $result,
            'data'    => $data,
            'total'   => $total,
        ) );
    }
    
    /**
     * Add a services
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function add( $trId ) {
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.lock_status' => LOCK, 'Ticket.supplier' => $this->loginUser['Supplier']['name'] ),
            'contain'    => FALSE,
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid ticket ID.' );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                //<editor-fold desc="Validation" defaultstate="collapsed">
                $services = array();
                foreach( $this->request->data['TrService'] as $trs ) {
                    $quantity = floatval( $trs['quantity'] );
                    if( $quantity == 0 ) {
                        throw new Exception( 'Quantity cannot be zero.', STATUS_INPUT_UNACCEPTABLE );
                    }
                    if( in_array( $trs['service'], $services ) ) {
                        throw new Exception( 'Duplicate item is not allowed.', STATUS_INPUT_UNACCEPTABLE );
                    }
                    $services[] = $trs['service'];
                }
                if( count( $services ) == 0 ) {
                    throw new Exception( 'Please provide at least one valid item.', STATUS_INPUT_UNACCEPTABLE );
                }
                //</editor-fold>
                
                $total = $vat_total = $total_with_vat = 0;
                
                //<editor-fold desc="Save services" defaultstate="collapsed">
                foreach( $this->request->data['TrService'] as $trs ) {
                    $service = $this->Service->find( 'first', array(
                        'conditions' => array(
                            'Service.supplier_id'  => $this->loginUser['User']['supplier_id'],
                            'Service.service_name' => $trs['service'],
//                            'Service.asset_group'  => $data['Ticket']['asset_group'],
                        ),
                        'contain'    => FALSE,
                    ) );

                    $quantity = floatval( $trs['quantity'] );
                    $saveData = array( 'TrService' => array(
                        'ticket_id'           => $this->request->data['Ticket']['id'],
                        'service'             => $service['Service']['service_name'],
                        'service_desc'        => $service['Service']['service_desc'],
                        'supplier'            => $this->loginUser['Supplier']['name'],
                        'quantity'            => $quantity,
                        'unit_price'          => $service['Service']['service_unit_price'],
                        'vat'                 => $service['Service']['vat'],
                        'unit_price_with_vat' => $service['Service']['vat_plus_price'],
                        'total'               => $service['Service']['service_unit_price'] * $quantity,
                        'vat_total'           => $service['Service']['service_unit_price'] * $quantity * $service['Service']['vat'] / 100,
                        'total_with_vat'      => $service['Service']['vat_plus_price'] * $quantity,
                        'delivery_date'       => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                        'warranty_status'     => NO,
                    ) );
                    
                    $lastServiceConditions = array(
                        'Ticket.site'                 => $data['Ticket']['site'],
                        'Ticket.supplier'             => $data['Ticket']['supplier'],
                        'TrService.service'           => $service['Service']['service_name'],
                        'TrService.delivery_date <= ' => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                        'TrService.status'            => ACTIVE,
                        'TrService.is_deleted'        => NO,
                    );
                    
                    $exist = $this->TrService->find( 'first', array(
                        'conditions' => array(
                            'TrService.ticket_id' => $this->request->data['Ticket']['id'],
                            'TrService.service'   => $service['Service']['service_name'],
                            'TrService.status'    => ACTIVE,
                        ),
                        'contain'    => FALSE,
                    ) );
                    if( $exist ) {
                        $saveData['TrService']['id'] = $exist['TrService']['id'];
                        $lastServiceConditions['TrService.id !='] = $exist['TrService']['id'];
                    }
                    
                    $lastService = $this->TrService->find( 'first', array(
                        'conditions' => $lastServiceConditions,
                        'contain'    => array( 'Ticket.site' ),
                        'order'      => array( 'TrService.delivery_date' => 'DESC' ),
                    ) );
                    if( !empty( $lastService ) ) {
                        $saveData['TrService']['last_service'] = $lastService['TrService']['id'];
                        
                        //<editor-fold desc="Check Warranty" defaultstate="collapsed">
                        if( $service['Service']['warranty_days'] > 0 || $service['Service']['warranty_hours'] > 0 ) {
                            $warrantyHours = 24 * $service['Service']['warranty_days'] + $service['Service']['warranty_hours'];
                            
                            if( $lastService['TrService']['warranty_status'] == NO && strtotime( $lastService['TrService']['delivery_date'] ) >= strtotime( "-{$warrantyHours} hours", strtotime( $trs['delivery_date'] ) ) ) {
                                $saveData['TrService']['warranty_status'] = YES;
                                $saveData['TrService']['total'] = 0;
                                $saveData['TrService']['vat_total'] = 0;
                                $saveData['TrService']['total_with_vat'] = 0;
                            }
                        }
                        //</editor-fold>
                    }
                    
                    $this->TrService->create();
                    if( !$this->TrService->save( $saveData ) ) {
                        throw new Exception( 'Failed to save service data.' );
                    }
                    
                    $total += $saveData['TrService']['total'];
                    $vat_total += $saveData['TrService']['vat_total'];
                    $total_with_vat += $saveData['TrService']['total_with_vat'];
                }
                //</editor-fold>
                
                //<editor-fold desc="Update ticket" defaultstate="collapsed">
                $trData = array( 'Ticket' => array(
                    'id'             => $this->request->data['Ticket']['id'],
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
                    throw new Exception( 'Failed to save ticket data.' );
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Service saved successfully.', 'id' => $this->Service->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Service saved successfully.' ), 'messages/success' );
                    $this->redirect( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index', '#' => 'locked' ) );
                }
            }
            catch( Exception $e ) {
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => FALSE, 'message' => __( $e->getMessage() ) ) ) );
                }
                else {
                    $this->Session->setFlash( __( $e->getMessage() ), 'messages/failed' );
                }
            }
        }
        
        $services = $this->WarrantyLookup->getServiceList( $this->loginUser['User']['supplier_id'], TRUE,null );
        $serviceList = array();
        foreach( $services as $s ) {
            $serviceList[] = array( 'name' => $s['Service']['service_desc'], 'value' => $s['Service']['service_name'], 'data-id' => $s['Service']['id'] );
        }
        
        $this->set( array(
            'data'             => $data,
            'serviceList'      => $serviceList,
            'title_for_layout' => 'Service Add',
        ) );
    }
    
    /**
     * View a tr details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view( $trId ) {
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.supplier' => $this->loginUser['Supplier']['name'] ),
            'contain'    => array(
                'TrService' => array(
                    'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Ticket ID.' );
        }
        
        $this->set( array(
            'data'             => $data,
            'title_for_layout' => 'Ticket Details',
        ) );
    }
    
    /**
     * View rejected TR
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function rejected_tr_view( $trId ) {
        //<editor-fold desc="Get ticket details" defaultstate="collapsed">
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.supplier' => $this->loginUser['Supplier']['name'], 'Ticket.approval_status' => DENY ),
            'contain'    => array(
                'TrService' => array(
                    'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Ticket ID.' );
        }
        //</editor-fold>
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                //<editor-fold desc="Validation" defaultstate="collapsed">
                $services = array();
                foreach( $this->request->data['TrService'] as $trs ) {
                    if( $trs['status'] == ACTIVE ) {
                        $quantity = floatval( $trs['quantity'] );
                        if( $quantity == 0 ) {
                            throw new Exception( 'Quantity cannot be zero.', STATUS_INPUT_UNACCEPTABLE );
                        }
                        if( in_array( $trs['service'], $services ) ) {
                            throw new Exception( 'Duplicate item is not allowed.', STATUS_INPUT_UNACCEPTABLE );
                        }
                        $services[] = $trs['service'];
                    }
                }
                if( count( $services ) == 0 ) {
                    throw new Exception( 'Please provide at least one valid service.', STATUS_INPUT_UNACCEPTABLE );
                }
                //</editor-fold>
                
                $total = $vat_total = $total_with_vat = 0;
                
                //<editor-fold desc="Save services" defaultstate="collapsed">
                foreach( $this->request->data['TrService'] as $trs ) {
                    $service = $this->Service->find( 'first', array(
                        'conditions' => array(
                            'Service.supplier_id'  => $this->loginUser['User']['supplier_id'],
                            'Service.service_name' => $trs['service'],
//                            'Service.asset_group'  => $data['Ticket']['asset_group'],
                        ),
                        'contain'    => FALSE,
                    ) );
                    
                    $quantity = floatval( $trs['quantity'] );
                    $saveData = array( 'TrService' => array(
                        'ticket_id'           => $this->request->data['Ticket']['id'],
                        'service'             => $service['Service']['service_name'],
                        'service_desc'        => $service['Service']['service_desc'],
                        'supplier'            => $this->loginUser['Supplier']['name'],
                        'quantity'            => $quantity,
                        'unit_price'          => $service['Service']['service_unit_price'],
                        'vat'                 => $service['Service']['vat'],
                        'unit_price_with_vat' => $service['Service']['vat_plus_price'],
                        'total'               => $service['Service']['service_unit_price'] * $quantity,
                        'vat_total'           => $service['Service']['service_unit_price'] * $quantity * $service['Service']['vat'] / 100,
                        'total_with_vat'      => $service['Service']['vat_plus_price'] * $quantity,
                        'delivery_date'       => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                        'warranty_status'     => NO,
                        'status'              => $trs['status'],
                    ) );
                    
                    if( !empty( $trs['id'] ) ) {
                        $saveData['TrService']['id'] = $trs['id'];
                        $trService = $this->TrService->find( 'first', array( 'conditions' => array( 'TrService.id' => $trs['id'] ), 'contain' => FALSE ) );
                    }
                    else {
                        $exist = $this->TrService->find( 'first', array(
                            'conditions' => array(
                                'TrService.ticket_id' => $this->request->data['Ticket']['id'],
                                'TrService.service'   => $service['Service']['service_name'],
                                'TrService.status'    => $trs['status'],
                            ),
                            'contain'    => FALSE,
                        ) );
                        if( $exist ) {
                            $saveData['TrService']['id'] = $exist['TrService']['id'];
                        }
                    }
                    
                    $latestComment = empty( $trs['comments'] ) ? '' : ( '<b>' . $this->loginUser['User']['name'] . "</b>: {$trs['comments']} (" . date( 'Y-m-d H:i:s' ) . ')' );
                    $saveData['TrService']['comments'] = ( !empty( $trService['TrService']['comments'] ) ? "{$trService['TrService']['comments']}<br />" : '' ) . $latestComment;
                    
                    if( $trs['status'] == ACTIVE ) {
                        $conditions = array(
                            'Ticket.site'                 => $data['Ticket']['site'],
                            'Ticket.supplier'             => $data['Ticket']['supplier'],
                            'TrService.service'           => $service['Service']['service_name'],
                            'TrService.delivery_date <= ' => date( 'Y-m-d H:i:s', strtotime( $trs['delivery_date'] ) ),
                            'TrService.status'            => ACTIVE,
                            'TrService.is_deleted'        => NO,
                        );
                        if( !empty( $trs['id'] ) ) {
                            $conditions['TrService.id !='] = $trs['id'];
                        }
                        $lastService = $this->TrService->find( 'first', array(
                            'conditions' => $conditions,
                            'contain'    => array( 'Ticket.site' ),
                            'order'      => array( 'TrService.delivery_date' => 'DESC' ),
                        ) );
                        if( !empty( $lastService ) ) {
                            $saveData['TrService']['last_service'] = $lastService['TrService']['id'];
                            
                            //<editor-fold desc="Check Warranty" defaultstate="collapsed">
                            if( $service['Service']['warranty_days'] > 0 || $service['Service']['warranty_hours'] > 0 ) {
                                $warrantyHours = 24 * $service['Service']['warranty_days'] + $service['Service']['warranty_hours'];
                                
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
                        throw new Exception( 'Failed to save service data.' );
                    }
                    
                    if( $trs['status'] == ACTIVE ) {
                        $total += $saveData['TrService']['total'];
                        $vat_total += $saveData['TrService']['vat_total'];
                        $total_with_vat += $saveData['TrService']['total_with_vat'];
                    }
                }
                //</editor-fold>
                
                //<editor-fold desc="Update ticket" defaultstate="collapsed">
                $trData = array( 'Ticket' => array(
                    'id'              => $this->request->data['Ticket']['id'],
                    'total'           => $total,
                    'vat_total'       => $vat_total,
                    'total_with_vat'  => $total_with_vat,
                    'approval_status' => NULL,
                ) );
                if( !$this->Ticket->save( $trData, array( 'fieldList' => array_keys( $trData['Ticket'] ) ) ) ) {
                    throw new Exception( 'Failed to save ticket data.' );
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Ticket updated successfully.', 'id' => $this->Ticket->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Ticket updated successfully.' ), 'messages/success' );
                    $this->redirect( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index', '#' => 'rejected' ) );
                }
            }
            catch( Exception $e ) {
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => FALSE, 'message' => __( $e->getMessage() ) ) ) );
                }
                else {
                    $this->Session->setFlash( __( $e->getMessage() ), 'messages/failed' );
                }
            }
        }
        
        $services = $this->WarrantyLookup->getServiceList( $this->loginUser['User']['supplier_id'], TRUE, null );
        $serviceList = array();
        foreach( $services as $s ) {
            $serviceList[] = array( 'name' => $s['Service']['service_desc'], 'value' => $s['Service']['service_name'], 'data-id' => $s['Service']['id'] );
        }
        
        $this->set( array(
            'data'             => $data,
            'serviceList'      => $serviceList,
            'title_for_layout' => 'Rejected Ticket Details',
        ) );
    }
}