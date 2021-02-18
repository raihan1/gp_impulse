<?php
App::uses( 'InvoiceCreationAppController', 'InvoiceCreation.Controller' );

/**
 * Invoices Controller
 *
 * @property WarrantyLookupComponent WarrantyLookup
 */
class InvoicesController extends InvoiceCreationAppController {
    
    public $uses = array( 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SubCenter', 'SupplierCategory', 'Service', 'Invoice', 'TrService' );
    
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
        $this->set( 'title_for_layout', 'Invoice List' );
    }
    
    /**
     * Invoice List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array( 'Invoice.region' => $this->loginUser['Region']['region_name'] );
        $order = array( 'Invoice.id' => 'DESC' );
        
        $columns = array(
            0 => array( 'model' => 'Invoice.id', 'field' => 'id', 'search' => 'equal' ),
            1 => array( 'model' => 'Invoice.invoice_id', 'field' => 'ref_no', 'search' => 'equal' ),
            2 => array( 'model' => 'Invoice.supplier', 'field' => 'supplier_name', 'search' => 'like' ),
            3 => array( 'model' => 'Invoice.total_with_vat', 'field' => 'total', 'search' => 'equal' ),
            4 => array( 'model' => 'Invoice.created', 'field' => 'date', 'search' => 'equal' ),
            5 => array( 'model' => 'Invoice.status', 'field' => 'status', 'search' => 'equal' ),
        );
        
        if( isset( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['field'] ] ) && $this->request->data[ $col['field'] ] != '' ) {
                if( $col['field'] == 'status' && $this->request->data[ $col['field'] ] == -1 ) {
                    $conditions["{$col['model']}"] = NULL;
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
        
        $total = $this->Invoice->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Invoice->find( 'all', array(
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
     * Add an invoice
     *
     * @throws NotFoundException
     */
    public function add() {
        if( !empty( $_GET['sub_center'] ) && !empty( $_GET['supplier'] ) ) {
            //<editor-fold desc="Get tickets and calculate total" defaultstate="collapsed">
            $data = $this->Ticket->find( 'all', array(
                'conditions' => array(
                    'Ticket.sub_center'      => $_GET['sub_center'],
                    'Ticket.supplier'        => $_GET['supplier'],
                    'Ticket.approval_status' => APPROVE,
                    'Ticket.invoice_id'      => 0,
                    'Ticket.is_invoiceable'  => YES,
                ),
                'contain'    => array( 'TrService' => array( 'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ) ) ),
                'order'      => 'Ticket.id DESC',
            ) );
            $this->set( 'data', $data );
            
            $total_with_vat = 0;
            if( !empty( $data ) ) {
                foreach( $data as $id => $d ) {
                    $total_with_vat += $d['Ticket']['total_with_vat'];
                }
            }
            $this->set( 'total_with_vat', $total_with_vat );
            //</editor-fold>
            
            //<editor-fold desc="Get last months Office budgets" defaultstate="collapsed">
            $this->loadModel( 'SubCenterBudget' );
            $subCenterBudget = $this->SubCenterBudget->find( 'first', array(
                'conditions' => array(
                    'SubCenter.sub_center_name' => $_GET['sub_center'],
                    'SubCenterBudget.year'      => date( 'Y', strtotime( '-1 month', time() ) ),
                    'SubCenterBudget.month'     => date( 'm', strtotime( '-1 month', time() ) ),
                ),
                'contain'    => array( 'SubCenter' ),
            ) );
            $this->set( 'subCenterBudget', $subCenterBudget );
            //</editor-fold>
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( empty( $this->request->data['Invoice']['id'] ) ) {
                    throw new Exception( __( 'Please select at least one ticket.' ) );
                }
                
                //<editor-fold desc="Save invoice" defaultstate="collapsed">
                list( $total, $vat_total, $total_with_vat ) = $this->total( $this->request->data['Invoice']['id'] );
                
                $saveData = array( 'Invoice' => array(
                    'year'           => $this->request->data['Invoice']['year'],
                    'month'          => $this->request->data['Invoice']['month'],
                    'invoice_id'     => $this->request->data['Invoice']['sub_center'] . '/' . $this->request->data['Invoice']['supplier'] . '/' . date( 'M-Y' ),
                    'supplier'       => $this->request->data['Invoice']['supplier'],
                    'region'         => $this->loginUser['Region']['region_name'],
                    'sub_center'     => $this->request->data['Invoice']['sub_center'],
                    'total'          => $total,
                    'vat_total'      => $vat_total,
                    'total_with_vat' => $total_with_vat,
                    'created_by'     => $this->loginUser['User']['id'],
                    'validate_by'    => 0,
                    'remarks'        => '',
                ) );
                $this->Invoice->create();
                if( !$this->Invoice->save( $saveData ) ) {
                    throw new Exception( __( 'Failed to save invoice data.' ) );
                }
                
                $invoice_id = $saveData['Invoice']['invoice_id'] . '/' . $this->Invoice->id;
                if( !$this->Invoice->saveField( 'invoice_id', $invoice_id ) ) {
                    throw new Exception( __( 'Failed to update Invoice ID.' ) );
                }
                //</editor-fold>
                
                //<editor-fold desc="Update tickets" defaultstate="collapsed">
                $trData = array(
                    'Ticket.invoice_id'   => $this->Invoice->id,
                    'Ticket.invoice_date' => "'" . date( 'Y-m-d H:i:s' ) . "'",
                    'Ticket.is_invoiced'  => YES,
                );
                $this->Ticket->unbindModel( array(
                    'belongsTo' => array( 'User', 'Region', 'SubCenter', 'Site', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Invoice', 'CreatedBy', 'ClosedBy', 'ValidatedBy', ),
                    'hasMany'   => array( 'TrService' ),
                ) );
                if( !$this->Ticket->updateAll( $trData, array( 'Ticket.id' => $this->request->data['Invoice']['id'] ) ) ) {
                    throw new Exception( __( 'Failed to update tickets.' ) );
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Invoice saved successfully.', 'id' => $this->Invoice->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Invoice saved successfully.' ), 'messages/success' );
                    $this->redirect( array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ) );
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
        
        $subCenters = $this->WarrantyLookup->getSubCenterList( $this->loginUser['User']['region_id'] );
        $subCenterList = array();
        foreach( $subCenters as $id => $name ) {
            $subCenterList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $suppliers = $this->WarrantyLookup->getSupplierList();
        $supplierList = array();
        foreach( $suppliers as $id => $name ) {
            $supplierList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $this->set( array(
            'subCenterList'    => $subCenterList,
            'supplierList'     => $supplierList,
            'title_for_layout' => 'Invoice Create',
        ) );
    }
    
    /**
     * Add/edit a invoice
     *
     * @throws NotFoundException
     */
    public function edit( $invoiceId ) {
        //<editor-fold desc="Get invoice details" defaultstate="collapsed">
        $data = $this->Invoice->find( 'first', array(
            'conditions' => array(
                'Invoice.id' => $invoiceId,
                'OR'         => array(
                    'Invoice.status IS NULL',
                    'Invoice.status' => DENY,
                ),
            ),
            'contain'    => FALSE,
        ) );
        if( empty( $data ) ) {
            throw new Exception( 'Invalid Invoice ID.' );
        }
        //</editor-fold>
        
        $invoiceTickets = $this->Ticket->find( 'list', array( 'conditions' => array( 'Ticket.invoice_id' => $data['Invoice']['id'] ), 'fields' => array( 'Ticket.id' ) ) );
        
        //<editor-fold desc="Get all invoiceable tickets and calculate total" defaultstate="collapsed">
        $invoiceableTickets = $this->Ticket->find( 'all', array(
            'conditions' => array(
                'Ticket.sub_center'      => $data['Invoice']['sub_center'],
                'Ticket.supplier'        => $data['Invoice']['supplier'],
                'Ticket.approval_status' => APPROVE,
                'Ticket.invoice_id'      => array( 0, $data['Invoice']['id'] ),
                'Ticket.is_invoiceable'  => YES,
            ),
            'contain'    => array( 'TrService' => array( 'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ) ) ),
            'order'      => 'Ticket.id DESC',
        ) );
        
        $total_with_vat = 0;
        if( !empty( $invoiceableTickets ) ) {
            foreach( $invoiceableTickets as $id => $d ) {
                if( in_array( $d['Ticket']['id'], $invoiceTickets ) ) {
                    $total_with_vat += $d['Ticket']['total_with_vat'];
                }
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Get last month's Office budget" defaultstate="collapsed">
        $this->loadModel( 'SubCenterBudget' );
        $subCenterBudget = $this->SubCenterBudget->find( 'first', array(
            'conditions' => array(
                'SubCenter.sub_center_name' => $data['Invoice']['sub_center'],
                'SubCenterBudget.year'      => date( 'Y', strtotime( '-1 month', time() ) ),
                'SubCenterBudget.month'     => date( 'm', strtotime( '-1 month', time() ) ),
            ),
            'contain'    => array( 'SubCenter' ),
        ) );
        //</editor-fold>
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                //<editor-fold desc="Validation" defaultstate="collapsed">
                $checkedTR = $uncheckedTR = array();
                foreach( $this->request->data['Ticket'] as $tr ) {
                    if( $tr['check'] == 1 ) {
                        $checkedTR[] = $tr['id'];
                    }
                    else {
                        $uncheckedTR[] = $tr['id'];
                    }
                }
                if( empty( $checkedTR ) ) {
                    throw new Exception( __( 'Please select at least one ticket.' ) );
                }
                //</editor-fold>
                
                //<editor-fold desc="Update invoice" defaultstate="collapsed">
                $total = $this->Ticket->find( 'first', array(
                    'conditions' => array( 'Ticket.id' => $checkedTR ),
                    'contain'    => FALSE,
                    'fields'     => array(
                        'SUM(Ticket.total) as total',
                        'SUM(Ticket.vat_total) as vat_total',
                        'SUM(Ticket.total_with_vat) as total_with_vat',
                    ),
                ) );
                $invoiceData = array(
                    'Invoice.total'          => $total[0]['total'],
                    'Invoice.vat_total'      => $total[0]['vat_total'],
                    'Invoice.total_with_vat' => $total[0]['total_with_vat'],
                    'Invoice.status'         => NULL,
                );
                $this->Invoice->unbindModel( array(
                    'belongsTo' => array( 'Supplier', 'Region', 'SubCenter' ),
                    'hasMany'   => array( 'Ticket' ),
                ) );
                if( !$this->Invoice->updateAll( $invoiceData, array( 'Invoice.id' => $this->request->data['Invoice']['id'] ) ) ) {
                    throw new Exception( __( 'Failed to update invoice.' ) );
                }
                //</editor-fold>
                
                //<editor-fold desc="Update tickets" defaultstate="collapsed">
                foreach( $this->request->data['Ticket'] as $tr ) {
                    $ticketData = array(
                        'Ticket.invoice_id'      => $tr['check'] == 1 ? $this->request->data['Invoice']['id'] : 0,
                        'Ticket.is_invoiced'     => $tr['check'] == 1 ? YES : NO,
                        'Ticket.invoice_date'    => $tr['check'] == 1 ? ( "'" . date( 'Y-m-d H:i:s' ) . "'" ) : NULL,
                        'Ticket.invoice_comment' => "'" . $tr['invoice_comment'] . "'",
                    );
                    $this->Ticket->unbindModel( array(
                        'belongsTo' => array( 'User', 'Region', 'SubCenter', 'Site', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Invoice', 'CreatedBy', 'ClosedBy', 'ValidatedBy', ),
                        'hasMany'   => array( 'TrService' ),
                    ) );
                    if( !$this->Ticket->updateAll( $ticketData, array( 'Ticket.id' => $tr['id'] ) ) ) {
                        throw new Exception( __( 'Failed to update ticket.' ) );
                    }
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Invoice saved successfully.', 'id' => $this->Invoice->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Invoice saved successfully.' ), 'messages/success' );
                    $this->redirect( array( 'action' => 'index' ) );
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
            'data'               => $data,
            'invoiceTickets'     => $invoiceTickets,
            'invoiceableTickets' => $invoiceableTickets,
            'total_with_vat'     => $total_with_vat,
            'subCenterBudget'    => $subCenterBudget,
            'title_for_layout'   => 'Invoice Edit',
        ) );
    }
    
    /**
     * Get total_with_vat via ajax/internal call for the selected tr_ids
     *
     * @param array $trIds
     *
     * @return float
     */
    public function total( $trIds = array() ) {
        $this->autoRender = FALSE;
        
        $total = $vat_total = $total_with_vat = 0;
        
        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'all', array( 'conditions' => array( 'Ticket.id' => !empty( $trIds ) ? $trIds : $this->request->data['tr_ids'] ), 'contain' => FALSE ) );
        if( !empty( $data ) ) {
            foreach( $data as $d ) {
                $total += $d['Ticket']['total'];
                $vat_total += $d['Ticket']['vat_total'];
                $total_with_vat += $d['Ticket']['total_with_vat'];
            }
        }
        
        if( !empty( $trIds ) ) {
            return array( $total, $vat_total, $total_with_vat );
        }
        else {
            die( number_format( $total_with_vat, 4 ) );
        }
    }
}