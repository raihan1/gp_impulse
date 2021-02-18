<?php
App::uses( 'InvoiceCreationAppController', 'InvoiceCreation.Controller' );

/**
 * Tickets Controller
 */
class TicketsController extends InvoiceCreationAppController {
    
    public $uses = array( 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Service', 'Invoice' );
    
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
        $this->set( 'title_for_layout', 'Ticket List' );
    }
    
    /**
     * Assigned TR actions via ajax datatable
     */
    public function assign_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.region'      => $this->loginUser['Region']['region_name'],
            'Ticket.lock_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            1 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.project', 'field' => 'project_name', 'search' => 'like' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
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
            'contain'    => FALSE,
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
     * Locked TR actions via ajax datatable
     */
    public function locked_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.region'         => $this->loginUser['Region']['region_name'],
            'Ticket.lock_status'    => LOCK,
            'Ticket.pending_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            1 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.project', 'field' => 'project_name', 'search' => 'like' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
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
            'contain'    => FALSE,
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
     * Pending TR actions via ajax datatable
     */
    public function pending_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.region'          => $this->loginUser['Region']['region_name'],
            'Ticket.pending_status'  => PENDING,
            'Ticket.approval_status' => NULL,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            1 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.project', 'field' => 'project_name', 'search' => 'like' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
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
            'contain'    => FALSE,
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
     * Approved TR actions via ajax datatable
     */
    public function approved_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.region'          => $this->loginUser['Region']['region_name'],
            'Ticket.approval_status' => APPROVE,
            'Ticket.invoice_id'      => 0,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            1 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.project', 'field' => 'project_name', 'search' => 'like' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
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
            'contain'    => FALSE,
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
     * Rejected TR actions via ajax datatable
     */
    public function rejected_tr_data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.region'          => $this->loginUser['Region']['region_name'],
            'Ticket.approval_status' => DENY,
        );
        
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Ticket.id', 'field' => 'id', 'search' => 'like' ),
            1 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier_category', 'field' => 'supplier_category', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.project', 'field' => 'project_name', 'search' => 'like' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
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
            'contain'    => FALSE,
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
     * View a TR details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view( $trId ) {
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.region' => $this->loginUser['Region']['region_name'] ),
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
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.approval_status' => DENY, 'Ticket.region' => $this->loginUser['Region']['region_name'] ),
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
            'title_for_layout' => 'Rejected Ticket Details',
        ) );
    }
}