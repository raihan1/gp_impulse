<?php
App::uses( 'SecurityAppController', 'Security.Controller' );

/**
 * Tickets Controller
 */
class TicketsController extends SecurityAppController {
    
    public $uses = array( 'User', 'Notification', 'Site', 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Service', 'TrService', 'SubCenter', 'SubCenterBudget' );
    
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
     * TR List
     */
    public function index() {
        $this->set( 'title_for_layout', 'TR List' );
    }
    
    /**
     * Assigned TR actions via ajax datatable
     */
    public function assign_tr_data() {
        $result = array();
        
        /* delete */
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $tr = $this->Ticket->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $tr ) ) {
                $deleteResult = $this->Ticket->updateAll( array( 'is_deleted' => YES ), array( 'Ticket.id' => $tr['Ticket']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The TR has been deleted.';
                }
                else {
                    $errors = '';
                    foreach( $deleteResult as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = $errors;
                }
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Invalid TR ID: ' . $this->request->data['customActionName'];
            }
        }
        /* settings */
        $conditions = array(
            'TrClass.tr_class_name LIKE' => 'SS%',
            'Ticket.lock_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'TrClass.tr_class_name', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Project.project_name', 'field' => 'project_name', 'search' => 'like' ),
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
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'Supplier', 'SupplierCategory', 'Site', 'AssetGroup', 'AssetNumber', 'Project', 'TrClass' ),
            'conditions' => $conditions,
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
    
    public function locked_tr_data() {
        $result = array();
        
        //<editor-fold defaultstate="collapsed" desc="settings">
        $conditions = array(
            'TrClass.tr_class_name LIKE' => 'SS%',
            'Ticket.lock_status'    => LOCK,
            'Ticket.pending_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'TrClass.tr_class_name', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Project.project_name', 'field' => 'project_name', 'search' => 'like' ),
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
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions ) );
        
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'Supplier', 'SupplierCategory', 'Site', 'AssetGroup', 'AssetNumber', 'Project', 'TrClass' ),
            'conditions' => $conditions,
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
    
    public function pending_tr_data() {
        $result = array();
        
        //<editor-fold defaultstate="collapsed" desc="settings">
        $conditions = array(
            'TrClass.tr_class_name LIKE' => 'SS%',
            'Ticket.pending_status'  => PENDING,
            'Ticket.approval_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'TrClass.tr_class_name', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Project.project_name', 'field' => 'project_name', 'search' => 'like' ),
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
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions ) );
        
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'Supplier', 'SupplierCategory', 'Site', 'AssetGroup', 'AssetNumber', 'Project', 'TrClass' ),
            'conditions' => $conditions,
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
    
    public function approved_tr_data() {
        $result = array();
        
        //<editor-fold defaultstate="collapsed" desc="settings">
        $conditions = array(
            'TrClass.tr_class_name LIKE' => 'SS%',
            'Ticket.approval_status' => APPROVE,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'TrClass.tr_class_name', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Project.project_name', 'field' => 'project_name', 'search' => 'like' ),
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
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions ) );
        
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'Supplier', 'SupplierCategory', 'Site', 'AssetGroup', 'AssetNumber', 'Project', 'TrClass' ),
            'conditions' => $conditions,
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
    
    public function rejected_tr_data() {
        $result = array();
        
        //<editor-fold defaultstate="collapsed" desc="settings">
        $conditions = array(
            'TrClass.tr_class_name LIKE' => 'SS%',
            'Ticket.approval_status' => DENY,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'TrClass.tr_class_name', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Project.project_name', 'field' => 'project_name', 'search' => 'like' ),
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
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions ) );
        
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'Supplier', 'SupplierCategory', 'Site', 'AssetGroup', 'AssetNumber', 'Project', 'TrClass' ),
            'conditions' => $conditions,
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
     * Add/edit a TR
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function add( $trId = NULL ) {
        if( !empty( $trId ) ) {
            $data = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => $trId, 'Ticket.lock_status' => NULL ) ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid TR ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            $errors = $unsavedTR = array();
            try {
                if( empty( $this->request->data['Ticket']['batch'] ) ) {
                    throw new Exception( 'Please provide at least one TR information.' );
                }
                
                $common = array(
                    'user_id'        => $this->request->data['Ticket']['user_id'],
                    'department'     => $this->request->data['Ticket']['department'],
                    'sub_center_id'  => $this->request->data['Ticket']['sub_center_id'],
                    'created_by'     => $this->request->data['Ticket']['created_by'],
                    'stage'          => $this->request->data['Ticket']['stage'],
                    'form_open_time' => $this->request->data['Ticket']['form_open_time'],
                );
                
                foreach( $this->request->data['Ticket']['batch'] as $ticket ) {
                    $saveData = am( $common, $ticket );
                    
                    $budgetValidation = $this->check( $saveData['tr_class_id'] );
                    if( in_array( $budgetValidation['status'], array( 1, 2 ) ) ) {
                        $errors[] = $budgetValidation['msg'];
                        if( empty( $saveData['site_name'] ) ) {
                            $site = $this->Site->find( 'first', array( 'conditions' => array( 'Site.id' => $saveData['site_id'] ), 'contain' => FALSE, 'fields' => array( 'site_name' ) ) );
                            $saveData['site_name'] = $site['Site']['site_name'];
                            
                            $trClass = $this->TrClass->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'TrClass.id' => $saveData['tr_class_id'] ], 'fields' => array( 'id', 'tr_class_name', 'no_of_days' ) ] );
                            $saveData['tr_class_name'] = $trClass['TrClass']['tr_class_name'];
                            
                            $assetGroup = $this->AssetGroup->find( 'first', array( 'conditions' => array( 'AssetGroup.id' => $saveData['asset_group_id'] ), 'contain' => FALSE, 'fields' => array( 'asset_group_name' ) ) );
                            $saveData['asset_group_name'] = $assetGroup['AssetGroup']['asset_group_name'];
                            
                            $assetNumber = $this->AssetNumber->find( 'first', array( 'conditions' => array( 'AssetNumber.id' => $saveData['asset_number_id'] ), 'contain' => FALSE, 'fields' => array( 'asset_number' ) ) );
                            $saveData['asset_number'] = $assetNumber['AssetNumber']['asset_number'];
                            
                            $supplier = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.id' => $saveData['supplier_id'] ), 'contain' => FALSE, 'fields' => array( 'name' ) ) );
                            $saveData['supplier_name'] = $supplier['Supplier']['name'];
                            
                            $supplierCategory = $this->SupplierCategory->find( 'first', array( 'conditions' => array( 'SupplierCategory.id' => $saveData['supplier_category_id'] ), 'contain' => FALSE, 'fields' => array( 'category_name' ) ) );
                            $saveData['category_name'] = $supplierCategory['SupplierCategory']['category_name'];
                        }
                        $unsavedTR[] = $saveData;
                        continue;
                    }
                    if( !empty( $this->request->data['Ticket']['id'] ) ) {
                        /* Edit Ticket */
                        $saveData['id'] = $this->request->data['Ticket']['id'];
                    }
                    else {
                        /* Handle multiple form submission */
                        $duplicate = $this->Ticket->find( 'first', array( 'conditions' => $saveData, 'contain' => FALSE ) );
                        if( !empty( $duplicate ) ) {
                            $saveData['id'] = $duplicate['Ticket']['id'];
                        }
                    }
                    
                    $this->Ticket->create();
                    if( !$this->Ticket->save( $saveData ) ) {
                        $saveErrors = '';
                        foreach( $this->User->validationErrors as $field => $validationError ) {
                            $saveErrors .= ( $saveErrors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $validationError );
                        }
                        $errors[] = $saveErrors;
                        if( empty( $saveData['site_name'] ) ) {
                            $site = $this->Site->find( 'first', array( 'conditions' => array( 'Site.id' => $saveData['site_id'] ), 'contain' => FALSE, 'fields' => array( 'site_name' ) ) );
                            $saveData['site_name'] = $site['Site']['site_name'];
                            
                            $trClass = $this->TrClass->find( 'first', array( 'conditions' => array( 'TrClass.id' => $saveData['tr_class_id'] ), 'contain' => FALSE, 'fields' => array( 'tr_class_name' ) ) );
                            $saveData['tr_class_name'] = $trClass['TrClass']['tr_class_name'];
                            
                            $assetGroup = $this->AssetGroup->find( 'first', array( 'conditions' => array( 'AssetGroup.id' => $saveData['asset_group_id'] ), 'contain' => FALSE, 'fields' => array( 'asset_group_name' ) ) );
                            $saveData['asset_group_name'] = $assetGroup['AssetGroup']['asset_group_name'];
                            
                            $assetNumber = $this->AssetNumber->find( 'first', array( 'conditions' => array( 'AssetNumber.id' => $saveData['asset_number_id'] ), 'contain' => FALSE, 'fields' => array( 'asset_number' ) ) );
                            $saveData['asset_number'] = $assetNumber['AssetNumber']['asset_number'];
                            
                            $supplier = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.id' => $saveData['supplier_id'] ), 'contain' => FALSE, 'fields' => array( 'name' ) ) );
                            $saveData['supplier_name'] = $supplier['Supplier']['name'];
                            
                            $supplierCategory = $this->SupplierCategory->find( 'first', array( 'conditions' => array( 'SupplierCategory.id' => $saveData['supplier_category_id'] ), 'contain' => FALSE, 'fields' => array( 'category_name' ) ) );
                            $saveData['category_name'] = $supplierCategory['SupplierCategory']['category_name'];
                        }
                        $unsavedTR[] = $saveData;
                        continue;
                    }
                    
                    $ticketData = $this->Ticket->find( 'first', array(
                        'conditions' => array( 'Ticket.id' => $this->Ticket->id ),
                        'contain'    => array(
                            'Supplier.name',
                            'User.name',
                            'Site.site_name',
                            'AssetGroup.asset_group_name',
                            'TrClass.tr_class_name',
                            'SubCenter' => array( 'Region.region_name' ),
                        ),
                    ) );
                    
                    /* Send Emails: START */
//                    $supplier_email = $this->Supplier->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Supplier.id' => $saveData['supplier_id'] ) ) );
//                    $subcenterUsers = $this->User->find( 'all', array(
//                        'contain'    => FALSE,
//                        'conditions' => array(
//                            'User.id !='         => $this->loginUser['User']['id'],
//                            'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
//                            'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
//                        ),
//                        'fields'     => array( 'User.email' ),
//                    ) );
//                    $ccEmails = $this->loginUser['User']['email'];
//                    foreach( $subcenterUsers as $u ) {
//                        $ccEmails .= ", {$u['User']['email']}";
//                    }
//                    $message = '<div>
//                        <p style="color: #5B5861; line-height: 22px;">
//                            Dear ' . $ticketData['Supplier']['name'] . ',
//                            <br /><br />SITE NAME: ' . $ticketData['Site']['site_name'] . '
//                            <br />SUBCENTER: ' . $ticketData['SubCenter']['sub_center_name'] . '
//                            <br />REGION: ' . $ticketData['SubCenter']['Region']['region_name'] . '
//                            <br />TR NUMBER: ' . $ticketData['Ticket']['id'] . '
//                            <br />ASSET GROUP: ' . $ticketData['AssetGroup']['asset_group_name'] . '
//                            <br />SUPPLIER NAME: ' . $ticketData['Supplier']['name'] . '
//                            <br />TR CREATION DATE: ' . $ticketData['Ticket']['created'] . '
//                            <br />RECEIVED AT SUPPLIER SITE: ' . date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['received_at_supplier'] ) ) . '
//                            <br />TR CLASS: ' . $ticketData['TrClass']['tr_class_name'] . '
//                            <br />TR ISSUER: ' . $ticketData['User']['name'] . '
//                            <br />PROPOSED COMPLETION DATE &amp; TIME: ' . ( !empty( $ticketData['Ticket']['complete_date'] ) ? date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['complete_date'] ) ) : '' ) . '
//                            <br />TR COMMENT: ' . $ticketData['Ticket']['comment'] . '
//                        </p>
//                    </div>';
//                    $emailResult = 1;
//                    if( !$this->sendEmailGP( $supplier_email['Supplier']['email'], $ccEmails, 'Email Notification', $message ) ) {
//                        $emailResult = 0;
//                        $errors[] = 'Email send unsuccessful for TR ID: ' . $this->Ticket->id;
//                    }
//
//                    $this->loadModel( 'Notification' );
//                    $this->Notification->create();
//                    $this->Notification->save( array( 'Notification' => array(
//                        'ticket_id' => $this->Ticket->id,
//                        'type'      => 0,
//                        'receiver'  => $supplier_email['Supplier']['email'],
//                        'result'    => $emailResult,
//                    ) ) );
                    /* Send Emails: END */
                    
                    /* Send SMS: START */
//                    $users = $this->User->find( 'all', array(
//                        'conditions' => array(
//                            'OR'          => array(
//                                array(
//                                    'User.role'        => SUPPLIER,
//                                    'User.supplier_id' => $saveData['supplier_id'],
//                                ),
//                                array(
//                                    'User.id !='         => $this->loginUser['User']['id'],
//                                    'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
//                                    'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
//                                ),
//                            ),
//                            'User.status' => ACTIVE,
//                        ),
//                        'contain'    => FALSE,
//                        'fields'     => array( 'User.phone' ),
//                    ) );
//                    if( !empty( $users ) ) {
//                        $message = "TR ID: {$ticketData['Ticket']['id']}\nSITE: {$ticketData['Site']['site_name']}\nSC: {$ticketData['SubCenter']['sub_center_name']}\nTR CLASS: {$ticketData['TrClass']['tr_class_name']}";
//                        foreach( $users as $u ) {
//                            $smsResult = $this->sendSMS( $u['User']['phone'], $message );
//                            if( !$smsResult['result'] ) {
//                                $errors[] = 'SMS send unsuccessful for TR ID: ' . $this->Ticket->id;
//                            }
//
//                            $this->Notification->create();
//                            $this->Notification->save( array( 'Notification' => array(
//                                'ticket_id' => $this->Ticket->id,
//                                'type'      => 1,
//                                'receiver'  => $u['User']['phone'],
//                                'result'    => $smsResult['result'] ? 1 : 0,
//                                'msg_id'    => $smsResult['msgId'],
//                            ) ) );
//                        }
//                    }
                    /* Send SMS: END */
                }
                
                $this->Session->setFlash( __( empty( $errors ) ? 'TR saved successfully.' : implode( '<br />', $errors ) ), 'messages/info' );
                if( empty( $errors ) ) {
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else {
                    $this->set( 'unsavedTR', $unsavedTR );
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
        
        $this->set( array(
            'regionList'       => $this->WarrantyLookup->getRegionList(),
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
            'title_for_layout' => 'TR ' . ( empty( $trId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a TR details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view( $trId = NULL ) {
        $data = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => $trId, 'Ticket.user_id' => $this->loginUser['User']['id'] ) ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid TR ID.' );
        }
        
        $trsData = [ ];
        foreach( $data['TrService'] as $trs ) {
            $sData = $this->Service->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'Service.id' => $trs['service_id'] ] ] );
            
            if( $trs['status'] == ACTIVE ) {
                $trsData[] = array(
                    'ticket_id'       => $trs['ticket_id'],
                    'service_name'    => $sData['Service']['service_desc'],
                    'base_unit_price' => $sData['Service']['service_unit_price'],
                    'vat'             => $sData['Service']['vat'],
                    'unit_price'      => round( ( $sData['Service']['service_unit_price'] + ( ( $sData['Service']['service_unit_price'] * $sData['Service']['vat'] ) / 100 ) ), 2 ),
                    'quantity'        => $trs['quantity'],
                );
            }
        }
        
        $this->set( array(
            'data'             => $data,
            'trsData'          => $trsData,
            'title_for_layout' => 'TR Details',
        ) );
    }
    
    /**
     * View rejected TR
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function rejected_tr_view( $trId = NULL ) {
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array(
                'Ticket.id'              => $trId,
                'Ticket.user_id'         => $this->loginUser['User']['id'],
                'Ticket.approval_status' => DENY,
            ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid TR ID.' );
        }
        
        $trsData = [ ];
        foreach( $data['TrService'] as $trs ) {
            $sData = $this->Service->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'Service.id' => $trs['service_id'] ] ] );
            
            if( $trs['status'] == ACTIVE ) {
                $trsData[] = array(
                    'ticket_id'       => $trs['ticket_id'],
                    'service_name'    => $sData['Service']['service_desc'],
                    'base_unit_price' => $sData['Service']['service_unit_price'],
                    'vat'             => $sData['Service']['vat'],
                    'unit_price'      => round( ( $sData['Service']['service_unit_price'] + ( ( $sData['Service']['service_unit_price'] * $sData['Service']['vat'] ) / 100 ) ), 2 ),
                    'quantity'        => $trs['quantity'],
                    'comments'        => $trs['comments'],
                );
            }
        }
        
        $this->set( array(
            'data'             => $data,
            'trsData'          => $trsData,
            'title_for_layout' => 'Rejected TR Details',
        ) );
    }
    
    /**
     * Validate TR for budget
     *
     * @param integer $tr_class_id
     *
     * @return array
     */
    private function check( $tr_class_id ) {
        $classArr = [ 'AC' => 1, 'CW' => 1, 'DV' => 1, 'EB' => 1, 'FM' => 1, 'GN' => 1, 'PG' => 1, 'RF' => 1, 'SS' => 1 ];
        
        $trClassName = $this->TrClass->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'TrClass.id' => $tr_class_id ] ] );
        $str = substr( $trClassName['TrClass']['tr_class_name'], 0, 2 );
        if( $str == 'FP' ) {
            $str = 'FM';
        }
        
        if( !array_key_exists( $str, $classArr ) ) {
            //return array( 'status' => 2, 'msg' => 'No budget for this TR class' );
        }
        
        $min_cost = $this->SubCenter->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'SubCenter.id' => $this->request->data['Ticket']['sub_center_id'] ] ] );
        
        $budget_data = $this->SubCenterBudget->find( 'first', [
            'contain'    => array( 'SubCenter' ),
            'conditions' => [
                'SubCenter.id'          => $this->request->data['Ticket']['sub_center_id'],
                'SubCenterBudget.month' => date( 'm' ),
                'SubCenterBudget.year'  => date( 'Y' ),
            ],
        ] );
        
        $total_budget = $budget_data['SubCenterBudget'][ $str . '_initial_budget' ];
        $current_budget = $budget_data['SubCenterBudget'][ $str . '_current_budget' ];
        $total_use = ( $total_budget - $current_budget ) + $min_cost['SubCenter'][ $str . '_min_budget' ];
        
        $get_cost_percent = ( round( ( $total_use / $total_budget ), 5 ) ) * 100;
        
        if( $get_cost_percent >= 80 && $get_cost_percent < 90 ) {
            if( $budget_data['SubCenter']['eighty_percent_action'] == YES ) {
                return array( 'status' => YES, 'msg' => 'You can not exceed 80% of Subcenter Budget' );
            }
            else {
                return array( 'status' => NO, 'msg' => 'You are exceeding 80% of Subcenter Budget' );
            }
        }
        else if( $get_cost_percent >= 90 && $get_cost_percent < 100 ) {
            if( $budget_data['SubCenter']['ninety_percent_action'] == YES ) {
                return array( 'status' => YES, 'msg' => 'You can not exceed 90% of Subcenter Budget' );
            }
            else {
                return array( 'status' => NO, 'msg' => 'You are exceeding 90% of Subcenter Budget' );
            }
        }
        else if( $get_cost_percent >= 100 ) {
            if( $budget_data['SubCenter']['hundred_percent_action'] == YES ) {
                return array( 'status' => YES, 'msg' => 'You can not exceed 100% of Subcenter Budget' );
            }
            else {
                return array( 'status' => NO, 'msg' => 'You are exceeding 100% of Subcenter Budget' );
            }
        }
        else {
            return array( 'status' => -1, 'msg' => '' );
        }
    }

    public function region_selected() {
        $this->autoRender = FALSE;

        $data['SubCenter'] = $this->SubCenter->find( 'all', [ 'contain' => FALSE, 'conditions' => [ 'SubCenter.region_id' => $this->request->data['region_id'] ] ] );

        die( json_encode( $data ) );
    }

    public function sub_center_selected() {
        $this->autoRender = FALSE;

        $data['Site'] = $this->Site->find( 'all', [ 'contain' => FALSE, 'conditions' => [ 'Site.sub_center_id' => $this->request->data['sub_center_id'] ] ] );

        die( json_encode( $data ) );
    }
    
    public function substitute() {
        $this->autoRender = FALSE;
        
        $data['Project'] = $this->Project->find( 'all', array(
            'contain'    => FALSE,
            'conditions' => array( 'Project.site_id' => $this->request->data['site_id'] ),
        ) );
        $data['AssetGroup'] = $this->AssetGroup->find( 'all', array(
            'contain'    => FALSE,
            'conditions' => array(
                'AssetGroup.site_id'          => $this->request->data['site_id'],
                'AssetGroup.asset_group_name' => 3012,
            ),
        ) );
        
        die( json_encode( $data ) );
    }
    
    public function asset_substitute() {
        $this->autoRender = FALSE;
        
        $data['AssetNumber'] = $this->AssetNumber->find( 'all', array(
            'contain'    => FALSE,
            'conditions' => array( 'AssetNumber.asset_group_id' => $this->request->data['asset_group_id'] ),
        ) );
        $data['TrClass'] = $this->TrClass->find( 'all', array(
            'contain'    => FALSE,
            'conditions' => array(
                'TrClass.asset_group_id' => $this->request->data['asset_group_id'],
                'TrClass.tr_class_name LIKE' => 'SS%',
            ),
        ) );
        
        die( json_encode( $data ) );
    }
    
    public function suppliers() {
        $this->autoRender = FALSE;
        
        $data = $this->SupplierCategory->find( 'all', [ 'contain' => FALSE, 'conditions' => [ 'SupplierCategory.supplier_id' => $this->request->data['supplier_id'] ] ] );
        
        die( json_encode( $data ) );
    }
    
    public function class_days() {
        $this->autoRender = FALSE;
        
        $data = $this->TrClass->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'TrClass.id' => $this->request->data['tr_class_id'] ] ] );
        if( !empty( $data['TrClass']['no_of_days'] ) ) {
            $r_date = strtotime( $this->request->data['received_date'] );
            $h = ( 24 * $data['TrClass']['no_of_days'] );
            $date_data = date( 'Y-m-d H:i:s', strtotime( '+ ' . round( $h, 0 ) . ' hours', $r_date ) );
        }
        else {
            $r_date = strtotime( $this->request->data['received_date'] );
            $h = ( 24 * 0 );
            $date_data = date( 'Y-m-d H:i:s', strtotime( '+ ' . $h . ' hours', $r_date ) );
        }
        
        die( json_encode( $date_data ) );
    }
    
    public function date_days() {
        $this->autoRender = FALSE;
        
        $data = $this->TrClass->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'TrClass.id' => $this->request->data['tr_class_id'] ] ] );
        if( !empty( $data['TrClass']['no_of_days'] ) ) {
            $r_date = strtotime( $this->request->data['received_date'] );
            $h = ( 24 * $data['TrClass']['no_of_days'] );
            $date_data = date( 'Y-m-d H:i:s', strtotime( '+ ' . $h . ' hours', $r_date ) );
        }
        else {
            $r_date = strtotime( $this->request->data['received_date'] );
            $h = ( 24 * 0 );
            $date_data = date( 'Y-m-d H:i:s', strtotime( '+ ' . $h . ' hours', $r_date ) );
        }
        
        die( json_encode( $date_data ) );
    }
    /**
     * Ajax Call End
     */
}