<?php
App::uses( 'InvoiceValidationAppController', 'InvoiceValidation.Controller' );

/**
 * Invoices Controller
 */
class InvoicesController extends InvoiceValidationAppController {
    
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
     * Invoice List
     */
    public function index() {
        $suppliers = $this->WarrantyLookup->getSupplierList();
        $supplierList = array();
        foreach( $suppliers as $id => $name ) {
            $supplierList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $this->set( array(
            'supplierList'     => $supplierList,
            'title_for_layout' => 'Invoice List',
        ) );
    }
    
    /**
     * Invoice List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold defaultstate="collapsed" desc="settings">
        $columns = array(
            0 => array( 'modelName' => 'Invoice.invoice_id', 'searchName' => 'invoice_id', 'searchType' => '%like%' ),
            1 => array( 'modelName' => 'Invoice.supplier', 'searchName' => 'supplier', 'searchType' => '%like%' ),
            3 => array( 'modelName' => 'Invoice.created', 'searchName' => 'created', 'searchType' => 'date' ),
            4 => array( 'modelName' => 'Invoice.status', 'searchName' => 'status', 'searchType' => 'equal' ),
        );
        
        $conditions = array();
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['searchName'] ] ) && $this->request->data[ $col['searchName'] ] != '' ) {
                if( $col['searchName'] == 'status' ) {
                    $conditions["{$col['modelName']}"] = $this->request->data[ $col['searchName'] ] == -1 ? NULL : $this->request->data[ $col['searchName'] ];
                }
                else if( $col['searchType'] == '%like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = '%' . $this->request->data[ $col['searchName'] ] . '%';
                }
                else if( $col['searchType'] == 'like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = $this->request->data[ $col['searchName'] ] . '%';
                }
                else if( $col['searchType'] == 'date' ) {
                    $conditions["DATE({$col['modelName']})"] = date( 'Y-m-d H:i:s', strtotime( $this->request->data[ $col['searchName'] ] ) );
                }
                else {
                    $conditions["{$col['modelName']}"] = $this->request->data[ $col['searchName'] ];
                }
            }
        }
        
        $order = array( 'Invoice.id' => 'DESC' );
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $order = array( $columns[ $this->request->data['order'][0]['column'] ]['searchName'] => $this->request->data['order'][0]['dir'] );
        }
        //</editor-fold>
        
        $this->loadModel( 'Invoice' );
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
     * View an invoice details (on submit reject, approved handled by approve_invoice via ajax)
     *
     * @param integer $invoiceId
     *
     * @throws NotFoundException
     */
    public function view( $invoiceId = NULL ) {
        $this->loadModel( 'Invoice' );
        $data = $this->Invoice->find( 'first', array(
            'conditions' => array( 'Invoice.id' => $invoiceId ),
            'contain'    => array( 'Ticket' ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Invoice ID.' );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                //<editor-fold desc="Update tickets with invoice_comment" defaultstate="collapsed">
                $this->loadModel( 'Ticket' );
                foreach( $data['Ticket'] as $tr ) {
                    $mainType = $this->WarrantyLookup->getMainType( $tr['tr_class'] );
                    if( !empty( $this->request->data[ $mainType ]['invoice_comment'] ) ) {
                        $comment = ( empty( $tr['invoice_comment'] ) ? '' : "{$tr['invoice_comment']}<br />" ) . ( '<b>' . $this->loginUser['User']['name'] . '</b>: ' . $this->request->data[ $mainType ]['invoice_comment'] . ' (' . date( 'Y-m-d H:i:s' ) . ')' );
                        $this->Ticket->id = $tr['id'];
                        if( !$this->Ticket->saveField( 'invoice_comment', $comment ) ) {
                            throw new Exception( 'Failed to update ticket.' );
                        }
                    }
                }
                //</editor-fold>
                
                //<editor-fold desc="Update invoice" defaultstate="collapsed">
                $saveData = array(
                    'Invoice.status'      => DENY,
                    'Invoice.validate_by' => $this->loginUser['User']['id'],
                );
                if( !$this->Invoice->updateAll( $saveData, array( 'Invoice.id' => $data['Invoice']['id'] ) ) ) {
                    throw new Exception( 'Failed to update invoice.' );
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Invoice updated successfully.', 'id' => $data['Invoice']['id'] ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Invoice updated successfully.' ), 'messages/success' );
                    $this->redirect( array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'index' ) );
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
        
        //<editor-fold desc="Process MainType-wise total and comment" defaultstate="collapsed">
        $mainTypes = array();
        foreach( $data['Ticket'] as $tr ) {
            $mainType = $this->WarrantyLookup->getMainType( $tr['tr_class'] );
            if( isset( $mainTypes[ $mainType ] ) ) {
                $mainTypes[ $mainType ]['total_with_vat'] += $tr['total_with_vat'];
                $mainTypes[ $mainType ]['previous_comment'] = $tr['invoice_comment'];
            }
            else {
                $mainTypes[ $mainType ] = array(
                    'total_with_vat'   => $tr['total_with_vat'],
                    'previous_comment' => $tr['invoice_comment'],
                );
            }
        }
        unset( $data['Ticket'] );
        //</editor-fold>
        
        //<editor-fold desc="Get last month's Office budget" defaultstate="collapsed">
        $this->loadModel( 'SubCenterBudget' );
        $subCenterBudget = $this->SubCenterBudget->find( 'first', array(
            'conditions' => array(
                'SubCenter.sub_center_name' => $data['Invoice']['sub_center'],
                'SubCenterBudget.year'      => date( 'Y', strtotime( '-1 month', time() ) ),
                'SubCenterBudget.month'     => date( 'm', strtotime( '-1 month', time() ) ),
            ),
            'contain'    => array( 'SubCenter.sub_center_name' ),
        ) );
        //</editor-fold>
        
        $this->set( array(
            'data'             => $data,
            'mainTypes'        => $mainTypes,
            'subCenterBudget'  => $subCenterBudget,
            'title_for_layout' => 'Invoice Details',
        ) );
    }
    
    /**
     * TR Services
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function services( $invoiceId, $mainType ) {
        $this->autoLayout = FALSE;
        
        //<editor-fold desc="Conditions" defaultstate="collapsed">
        $conditions = array( 'Ticket.invoice_id' => $invoiceId );
        
        $mainTypes = $this->WarrantyLookup->getAssociatedMainTypes( $mainType );
        if( count( $mainTypes ) == 1 ) {
            $conditions['Ticket.tr_class LIKE'] = "{$mainTypes[0]}%";
        }
        else {
            foreach( $mainTypes as $mainType ) {
                if( $mainType == 'RFE' ) {
                    $conditions['OR'][] = array(
                        'Ticket.tr_class LIKE'     => 'RFE%',
                        'Ticket.tr_class NOT LIKE' => 'RFEX%',
                    );
                }
                else {
                    $conditions['OR'][] = array( 'Ticket.tr_class LIKE' => "{$mainType}%" );
                }
            }
        }
        //</editor-fold>
        
        $this->loadModel( 'Ticket' );
        $trIDs = $this->Ticket->find( 'list', array( 'conditions' => $conditions, 'contain' => FALSE, 'fields' => array( 'Ticket.id' ) ) );
        if( empty( $trIDs ) ) {
            throw new NotFoundException( 'Invalid main type.' );
        }
        
        $this->loadModel( 'TrService' );
        $data = $this->TrService->find( 'all', array(
            'conditions' => array( 'TrService.ticket_id' => $trIDs, 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
            'contain'    => FALSE,
        ) );
        
        $this->set( array(
            'data'             => $data,
            'title_for_layout' => 'Invoice Details',
        ) );
    }
    
    /**
     * Approve invoice via ajax from view
     *
     * @param integer $id
     */
    public function approve_invoice() {
        $this->autoRender = FALSE;
        try {
            $this->loadModel( 'Invoice' );
            $this->loadModel( 'Supplier' );
            
            $data = $this->Invoice->find( 'first', array( 'conditions' => array( 'Invoice.id' => $this->request->data['id'] ), 'contain' => FALSE ) );
            if( empty( $data ) ) {
                throw new Exception( 'Invalid Invoice ID.' );
            }
            
            //<editor-fold desc="Update invoice" defaultstate="collapsed">
            $saveData = array(
                'Invoice.status'      => APPROVE,
                'Invoice.validate_by' => $this->loginUser['User']['id'],
            );
            if( !$this->Invoice->updateAll( $saveData, array( 'Invoice.id' => $data['Invoice']['id'] ) ) ) {
                throw new Exception( 'Failed to update invoice.' );
            }
            //</editor-fold>
            
            //<editor-fold desc="Send email" defaultstate="collapsed">
            $message = '<div>
                        <p style="color: #5B5861; line-height: 22px;">
                            Dear Partner,
                            <br /><br />Invoice number (' . $data['Invoice']['invoice_id'] . ') amounting BDT: ' . number_format( $data['Invoice']['total_with_vat'], 4 ) . ' has been created.
                            You are requested to download necessary documents from
                            <a href="' . Router::url( array( 'plugin' => 'supplier', 'controller' => 'reports', 'action' => 'index' ), TRUE ) . '">' . Router::url( array( 'plugin' => 'supplier', 'controller' => 'reports', 'action' => 'index' ), TRUE ) . '</a>
                            and submit the invoice with necessary documents to GP OTC.
                        </p>
                    </div>';
            $supplier = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.name' => $data['Invoice']['supplier'] ), 'contain' => FALSE ) );
            $invoiceCreator = $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $data['Invoice']['created_by'] ), 'contain' => FALSE ) );
            $trValidators = $this->User->find( 'all', array( 'conditions' => array( 'User.role' => TR_VALIDATOR, 'SubCenter.sub_center_name' => $data['Invoice']['sub_center'] ), 'contain' => array( 'SubCenter.sub_center_name' ) ) );
            
            $ccEmails = $this->loginUser['User']['email'] . ', ' . $invoiceCreator['User']['email'];
            foreach( $trValidators as $u ) {
                $ccEmails .= ", {$u['User']['email']}";
            }
            
            if( !$this->sendEmailGP( $supplier['Supplier']['email'], $ccEmails, "Invoice number {$data['Invoice']['invoice_id']}", $message ) ) {
                throw new Exception( 'Failed to send email.' );
            }
            //</editor-fold>
            
            $this->Session->setFlash( __( 'Invoice updated successfully.' ), 'messages/success' );
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
}