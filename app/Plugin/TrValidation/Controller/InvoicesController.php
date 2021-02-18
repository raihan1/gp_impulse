<?php
App::uses( 'TrValidationAppController', 'TrValidation.Controller' );

/**
 * Invoices Controller
 */
class InvoicesController extends TrValidationAppController {
    
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
        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array(
                'Ticket.sub_center'             => $this->loginUser['SubCenter']['sub_center_name'],
                'Ticket.approval_status'        => APPROVE,
                //'Ticket.received_at_supplier <' => date( 'Y-m-01 00:00:00' ),
                'Ticket.invoice_id'             => 0,
            ),
            'contain'    => FALSE,
            'fields' => array( 'SUM( Ticket.total_with_vat ) AS total_with_vat' ),
        ) );
    
        $this->loadModel( 'SubCenterBudget' );
        $subCenterBudget = $this->SubCenterBudget->find( 'first', array(
            'conditions' => array(
                'SubCenterBudget.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                'SubCenterBudget.year'          => date( 'Y', strtotime( '-1 month', time() ) ),
                'SubCenterBudget.month'         => date( 'm', strtotime( '-1 month', time() ) ),
            ),
            'contain'    => FALSE,
        ) );
        
        $this->set( array(
            'total'            => $data[0]['total_with_vat'],
            'subCenterBudget'  => $subCenterBudget,
            'title_for_layout' => 'Invoiceable TR List',
        ) );
    }
    
    /**
     * Invoice List actions via ajax datatable
     */
    public function data() {
        $this->loadModel( 'Ticket' );
        $result = array();
        
        //<editor-fold desc="Group actions (mark/unmark invoiceable, subtotal)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            if( in_array( $this->request->data['customActionName'], array( 'mark_invoiceable', 'unmark_invoiceable' ) ) ) {
                $status = $this->request->data['customActionName'] == 'mark_invoiceable' ? YES : NO;
                $this->Ticket->unbindModel( array(
                    'belongsTo' => array( 'User', 'Region', 'SubCenter', 'Site', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Invoice', 'CreatedBy', 'ClosedBy', 'ValidatedBy', ),
                    'hasMany'   => array( 'TrService' ),
                ) );
                if( $this->Ticket->updateAll( array( 'is_invoiceable' => $status ), array( 'Ticket.id' => $this->request->data['id'] ) ) ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' tickets.';
                }
                else {
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' tickets.';
                }
            }
            if( $this->request->data['customActionName'] == 'is_subtotal' ) {
                $data = $this->Ticket->find( 'first', array(
                    'conditions' => array( 'Ticket.id' => $this->request->data['id'] ),
                    'contain'    => FALSE,
                    'fields'     => array( 'SUM(total_with_vat) AS total_with_vat' ),
                ) );
                $this->set( array( 'subTotal' => number_format( $data[0]['total_with_vat'], 4 ) ) );
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array(
            'Ticket.sub_center'             => $this->loginUser['SubCenter']['sub_center_name'],//CG_NORTH
            'Ticket.approval_status'        => APPROVE, //1
            //'Ticket.received_at_supplier <' => date( 'Y-m-01 00:00:00' ),
            'Ticket.invoice_id'             => 0,
        );
        $order = array( 'Ticket.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Ticket.id', 'field' => 'ticket_id', 'search' => 'like' ),
            2 => array( 'model' => 'Ticket.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            3 => array( 'model' => 'Ticket.site', 'field' => 'site_name', 'search' => 'like' ),
            4 => array( 'model' => 'Ticket.asset_group', 'field' => 'asset_group_name', 'search' => 'like' ),
            5 => array( 'model' => 'Ticket.tr_class', 'field' => 'tr_class_name', 'search' => 'like' ),
            6 => array( 'model' => 'Ticket.received_at_supplier', 'field' => 'received_at_supplier', 'search' => 'like' ),
            7 => array( 'model' => 'Ticket.is_invoiceable', 'field' => 'is_invoiceable', 'search' => 'like' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['field'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['search'] == 'like' ) {
                    $conditions["{$col['model']} LIKE"] = $this->request->data[ $col['field'] ] . '%';
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
}