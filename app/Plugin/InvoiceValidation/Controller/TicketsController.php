<?php
App::uses( 'InvoiceValidationAppController', 'InvoiceValidation.Controller' );

/**
 * Tickets Controller
 */
class TicketsController extends InvoiceValidationAppController {
    
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
     * Ticket list actions via ajax datatable
     *
     * @param  string $type
     */
    public function data( $type ) {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $columns = array(
            0 => array( 'modelName' => 'Ticket.id', 'searchName' => 'id', 'searchType' => '%like%' ),
            1 => array( 'modelName' => 'Ticket.supplier', 'searchName' => 'supplier', 'searchType' => '%like%' ),
            2 => array( 'modelName' => 'Ticket.supplier_category', 'searchName' => 'supplier_category', 'searchType' => '%like%' ),
            3 => array( 'modelName' => 'Ticket.site', 'searchName' => 'site', 'searchType' => '%like%' ),
            4 => array( 'modelName' => 'Ticket.asset_group', 'searchName' => 'asset_group', 'searchType' => '%like%' ),
            5 => array( 'modelName' => 'Ticket.asset_number', 'searchName' => 'asset_number', 'searchType' => '%like%' ),
            6 => array( 'modelName' => 'Ticket.tr_class', 'searchName' => 'tr_class', 'searchType' => '%like%' ),
            7 => array( 'modelName' => 'Ticket.project', 'searchName' => 'project', 'searchType' => '%like%' ),
        );
    
        if( $type == 'assigned' ) {
            $conditions = array( 'Ticket.lock_status' => NULL );
        }
        else if( $type == 'locked' ) {
            $conditions = array( 'Ticket.lock_status' => LOCK, 'Ticket.pending_status' => NULL );
        }
        else if( $type == 'pending' ) {
            $conditions = array( 'Ticket.pending_status' => PENDING, 'Ticket.approval_status' => NULL );
        }
        else if( $type == 'approved' ) {
            $conditions = array( 'Ticket.approval_status' => APPROVE, 'Ticket.invoice_id' => 0 );
        }
        else if( $type == 'rejected' ) {
            $conditions = array( 'Ticket.approval_status' => DENY );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['searchName'] ] ) && $this->request->data[ $col['searchName'] ] != '' ) {
                if( $col['searchType'] == '%like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = '%' . $this->request->data[ $col['searchName'] ] . '%';
                }
                else if( $col['searchType'] == 'like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = $this->request->data[ $col['searchName'] ] . '%';
                }
                else {
                    $conditions["{$col['modelName']}"] = $this->request->data[ $col['searchName'] ];
                }
            }
        }
    
        $order = array( 'Ticket.id' => 'DESC' );
        if( isset( $this->request->data['order'][0]['column'] ) ) {
            $order = array( $columns[ $this->request->data['order'][0]['column'] ]['modelName'] => $this->request->data['order'][0]['dir'] );
        }
        //</editor-fold>
        
        $this->loadModel( 'Ticket' );
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
            'type'    => $type,
        ) );
    }
    
    /**
     * View a ticket details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view( $trId = NULL ) {
        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId ),
            'contain'    => array(
                'TrService' => array(
                    'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Ticket ID.' );
        }
        
        if( $data['Ticket']['lock_status'] == NULL ) {
            $type = 'assigned';
        }
        else if( $data['Ticket']['lock_status'] == LOCK && $data['Ticket']['pending_status'] == NULL ) {
            $type = 'locked';
        }
        else if( $data['Ticket']['pending_status'] == PENDING && $data['Ticket']['approval_status'] == NULL ) {
            $type = 'pending';
        }
        else if( $data['Ticket']['approval_status'] == APPROVE && $data['Ticket']['invoice_id'] == 0 ) {
            $type = 'approved';
        }
        else if( $data['Ticket']['approval_status'] == DENY ) {
            $type = 'rejected';
        }
        
        $this->set( array(
            'data'             => $data,
            'type'             => $type,
            'title_for_layout' => 'Ticket Details',
        ) );
    }
}