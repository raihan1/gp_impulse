<?php
App::uses('AdminAppController', 'Admin.Controller');


class TicketsController extends AdminAppController
{
    public $uses = array( 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Service', 'TrService', 'User' );

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    /**
     * Ticket List
     */
    public function index()
    {
        $this->set('title_for_layout', 'TR List');
    }

    /**
     * Ticket list actions via ajax datatable
     *
     * @param  string $type
     */
    public function data($type)
    {

        $this->loadModel('Ticket');
        $this->loadModel('TicketArchive');
        $this->loadModel('TrService');
        $this->loadModel('TrServiceArchive');
        $result = array();

        //<editor-fold desc="Single delete" defaultstate="collapsed">

        /*customActionType ==  delete */
        if (isset($this->request->data['customActionType']) && $this->request->data['customActionType'] == 'delete') {
            $tr = $this->Ticket->find('first',
                array('conditions' => array('Ticket.id' => intval($this->request->data['customActionName'])/*, 'Ticket.lock_status' => NULL */),
                    'contain' => 'TrService'));


            foreach ($tr['TrService'] as $service) {
                $preService = $service['last_service'];
                $postService = $this->TrService->find('first',
                    array('conditions' => array('TrService.last_service' => $service['id']),
                        'contain' => FALSE));
                if (!empty($postService)) {
                    $this->TrService->id = $postService['TrService']['id'];
                    $this->TrService->saveField('last_service', $preService);
                }

                $this->TrServiceArchive->save($service);
                $this->TrService->delete($service, true, true);
            }

            if (!empty($tr)) {
                $success = $this->TicketArchive->save(
                    array(
                        'ticket_id' => $tr['Ticket']['id'],
                        'user_id' => $tr['Ticket']['user_id'],
                        'region' => $tr['Ticket']['region'],
                        'sub_center' => $tr['Ticket']['sub_center'],
                        'site' => $tr['Ticket']['site'],
                        'project' => $tr['Ticket']['project'],
                        'asset_group' => $tr['Ticket']['asset_group'],
                        'asset_number' => $tr['Ticket']['asset_number'],
                        'tr_class' => $tr['Ticket']['tr_class'],
                        'supplier' => $tr['Ticket']['supplier'],
                        'supplier_category' => $tr['Ticket']['supplier_category'],
                        'department' => $tr['Ticket']['department'],
                        'total' => $tr['Ticket']['total'],
                        'vat_total' => $tr['Ticket']['vat_total'],
                        'total_with_vat' => $tr['Ticket']['total_with_vat'],
                        'received_at_supplier' => $tr['Ticket']['received_at_supplier'],
                        'complete_date' => $tr['Ticket']['complete_date'],
                        'closing_date' => $tr['Ticket']['closing_date'],
                        'validation_date' => $tr['Ticket']['validation_date'],
                        'comment' => $tr['Ticket']['comment'],
                        'tr_creation_confirmed_by' => $tr['Ticket']['tr_creation_confirmed_by'],
                        'tr_closed_by' => $tr['Ticket']['tr_closed_by'],
                        'tr_validation_by' => $tr['Ticket']['tr_validation_by'],
                        'invoice_created_by' => $tr['Ticket']['invoice_created_by'],
                        'invoice_creation_confirmed_by' => $tr['Ticket']['invoice_creation_confirmed_by'],
                        'invoice_validation_by' => $tr['Ticket']['invoice_validation_by'],
                        'stage' => $tr['Ticket']['stage'],
                        'lock_status' => $tr['Ticket']['lock_status'],
                        'pending_status' => $tr['Ticket']['pending_status'],
                        'approval_status' => $tr['Ticket']['approval_status'],
                        'is_invoiceable' => $tr['Ticket']['is_invoiceable'],
                        'invoice_id' => $tr['Ticket']['invoice_id'],
                        'invoice_date' => $tr['Ticket']['invoice_date'],
                        'is_invoiced' => $tr['Ticket']['is_invoiced'],
                        'invoice_comment' => $tr['Ticket']['invoice_comment'],
                        'created' => $tr['Ticket']['created'],
                        'modified' => $tr['Ticket']['modified'],
                        'created_by' => $tr['Ticket']['created_by'],
                        'status' => $tr['Ticket']['status'],
                        'is_deleted' => 1,
                        'form_open_time' => $tr['Ticket']['form_open_time'],
                    )
                );

                if ($success) {
                    if ($this->Ticket->delete($tr['Ticket']['id'])) {
                        $result['customActionStatus'] = 'OK';
                        $result['customActionMessage'] = 'The TR has been deleted.';
                    }

                    //$this->Ticket->saveField( 'is_deleted', YES );
                } else {
                    $errors = '';
                    foreach ($this->Ticket->validationErrors as $field => $error) {
                        $errors .= ($errors == '' ? '' : '<br />') . $field . ': ' . implode(', ', $error);
                    }
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = $errors;
                }
            } else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Invalid Ticket ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>

        //<editor-fold desc="Settings" defaultstate="collapsed">

        $columns = array(
            1 => array('modelName' => 'Ticket.id', 'searchName' => 'id', 'searchType' => '%like%'),
            2 => array('modelName' => 'User.name', 'searchName' => 'name', 'searchType' => '%like%'),
            3 => array('modelName' => 'Ticket.supplier_category', 'searchName' => 'supplier_category', 'searchType' => '%like%'),
            4 => array('modelName' => 'Ticket.site', 'searchName' => 'site', 'searchType' => '%like%'),
            5 => array('modelName' => 'Ticket.asset_group', 'searchName' => 'asset_group', 'searchType' => '%like%'),
            6 => array('modelName' => 'Ticket.asset_number', 'searchName' => 'asset_number', 'searchType' => '%like%'),
            7 => array('modelName' => 'Ticket.tr_class', 'searchName' => 'tr_class', 'searchType' => '%like%'),
            8 => array('modelName' => 'Ticket.received_at_supplier', 'searchName' => 'received_at_supplier', 'searchType' => 'date'),
        );

        if ($type == 'assigned') {
            $conditions = array('Ticket.lock_status' => NULL);
        } else if ($type == 'locked') {
            $conditions = array('Ticket.lock_status' => LOCK, 'Ticket.pending_status' => NULL);;
        } else if ($type == 'pending') {
            $conditions = array('Ticket.pending_status' => PENDING, 'Ticket.approval_status' => NULL);
        } else if ($type == 'approved') {
            $conditions = array('Ticket.approval_status' => APPROVE, 'Ticket.invoice_id' => 0);
        } else if ($type == 'rejected') {
            $conditions = array('Ticket.approval_status' => DENY);
        }

        foreach ($columns as $col) {
            if (isset($this->request->data[$col['searchName']]) && $this->request->data[$col['searchName']] != '') {
                if ($col['searchType'] == '%like%') {
                    $conditions["{$col['modelName']} LIKE"] = '%' . $this->request->data[$col['searchName']] . '%';
                } else if ($col['searchType'] == 'like%') {
                    $conditions["{$col['modelName']} LIKE"] = $this->request->data[$col['searchName']] . '%';
                } else if ($col['searchType'] == 'date') {
                    $conditions["DATE( {$col['modelName']} )"] = date('Y-m-d', $this->request->data[$col['searchName']]);
                } else {
                    $conditions["{$col['modelName']}"] = $this->request->data[$col['searchName']];
                }
            }
        }

        $order = array('Ticket.id' => 'DESC');
        if (!empty($this->request->data['order'][0]['column'])) {
            $order = array($columns[$this->request->data['order'][0]['column']]['modelName'] => $this->request->data['order'][0]['dir']);
        }
        //</editor-fold>

        $total = $this->Ticket->find('count', array('conditions' => $conditions, 'contain' => array('User.name')));
        $data = $this->Ticket->find('all', array(
            'contain' => array('User.name'),
            'conditions' => $conditions,
            'limit' => intval($this->request->data['length']) > 0 ? intval($this->request->data['length']) : $total,
            'offset' => intval($this->request->data['start']),
            'order' => $order,
        ));

        $this->set(array(
            'request' => $this->request->data,
            'result' => $result,
            'data' => $data,
            'total' => $total,
            'type' => $type,
        ));
    }


    /**
     * Add/edit a ticket
     *
     * @param integer|null $trId
     *
     * @throws Exception
     */
//    public function calculate_unit_vat_total_price() {
//        $this->autoRender = FALSE;
//
//        $this->loadModel( 'TrClass' );
//        $data = $this->TrClass->find( 'first', array(
//            'conditions' => array( 'TrClass.id' => $this->request->data['tr_class_id'] ),
//            'contain'    => FALSE,
//            'fields'     => array( 'id', 'tr_class_name', 'no_of_days' ),
//        ) );
//        $sla = !empty( $data['TrClass']['no_of_days'] ) ? ( 24 * $data['TrClass']['no_of_days'] ) : 0;
//        $completeDate = date( 'Y-m-d H:i:s', strtotime( '+ ' . round( $sla, 0 ) . ' hours', strtotime( $this->request->data['received_at_supplier'] ) ) );
//
//        die( json_encode( $completeDate ) );
//    }
    public function add($trId = NULL)
    {
        $this->loadModel('Ticket');
        $this->loadModel('CustomValue');


        if (!empty($trId)) {
            $data = $this->Ticket->find('first', array(
                'conditions' => array('Ticket.id' => $trId)));
            /*, 'Ticket.lock_status' => NULL, 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name']*/
            if (empty($data)) {
                throw new Exception('Invalid Ticket ID.');
            }
            if ($data['Ticket']['lock_status'] == NULL) {
                $type = 'assigned';
            } else if ($data['Ticket']['lock_status'] == LOCK && $data['Ticket']['pending_status'] == NULL) {
                $type = 'locked';
            } else if ($data['Ticket']['pending_status'] == PENDING && $data['Ticket']['approval_status'] == NULL) {
                $type = 'pending';
            } else if ($data['Ticket']['approval_status'] == APPROVE && $data['Ticket']['invoice_id'] == 0) {
                $type = 'approved';
            } else if ($data['Ticket']['approval_status'] == DENY) {
                $type = 'rejected';
            }

            $this->loadModel('TrService');
            $trs_data['TrService'] = $this->TrService->find('all', array(
                'conditions' => array('TrService.status' => ACTIVE, 'TrService.is_deleted' => 0 , 'TrService.ticket_id' => $trId),
            )) ;

//            $this->loadModel('Ticket');
//            $data = $this->Ticket->find('first', array(
//                'conditions' => array('Ticket.id' => $trId),/*, 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name']*/
//                'contain' => array(
//                    'TrService' => array(
//                        'conditions' => array('TrService.status' => ACTIVE, 'TrService.is_deleted' => 0 , 'TrService.ticket_id' => $trId),
//                    ),
//                ),
//            ));
            $this->set(array(
                'data'=> $data,
                'trs_data' => $trs_data,
                'type' => $type,
            ));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->loadModel('Notification');
            $this->loadModel('Supplier');
            $this->loadModel('User');

            $errors = $unsavedTR = array();


            try {
                if (empty($this->request->data['Ticket']['batch'])) {
                    throw new Exception('Please provide at least one TR information.');
                }


                $common = array(
                    'user_id' => $data['User']['id'],
                    'department' => $data['User']['department'],
                    'region' => $data['Region']['region_name'],
                    'sub_center' => $data['SubCenter']['sub_center_name'],
                    'created_by' => $data['User']['id'],
                    'stage' => SUPPLIER_STAGE,    /*SUPPLIER_STAGE = 2*/
                    'form_open_time' => $this->request->data['Ticket']['form_open_time'],
                );

                foreach ($this->request->data['Ticket']['batch'] as $ticket) {
                    //<editor-fold desc="Save ticket" defaultstate="collapsed">
                    $saveData = $common + $ticket;

                    $budgetValidation = $this->check($saveData['tr_class']);
                    if (in_array($budgetValidation['status'], array(1, 2))) {
                        $errors[] = $budgetValidation['msg'];
                        $unsavedTR[] = $saveData;
                        continue;
                    }
                    if (!empty($this->request->data['Ticket']['id'])) {
                        /* Edit Ticket */
                        $saveData['id'] = $this->request->data['Ticket']['id'];
                    } else {
                        /* Handle multiple form submission */
                        $duplicate = $this->Ticket->find('first', array('conditions' => $saveData, 'contain' => FALSE));
                        if (!empty($duplicate)) {
                            $saveData['id'] = $duplicate['Ticket']['id'];
                        }
                    }

                    $this->Ticket->create();
                    if (!$this->Ticket->save($saveData)) {
                        $saveErrors = '';
                        foreach ($this->User->validationErrors as $field => $validationError) {
                            $saveErrors .= ($saveErrors == '' ? '' : '<br />') . $field . ': ' . implode(', ', $validationError);
                        }
                        $errors[] = $saveErrors;
                        $unsavedTR[] = $saveData;
                        continue;
                    }
                    //</editor-fold>

                    $ticketData = $this->Ticket->find('first', array('conditions' => array('Ticket.id' => $this->Ticket->id), 'contain' => FALSE));

//                       //<editor-fold desc="Send email" defaultstate="collapsed">
//                       $supplierEmail = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.name' => $saveData['supplier'] ), 'contain' => FALSE ) );
//
//                       $subCenterUsers = $this->User->find( 'all', array(
//                           'contain'    => FALSE,
//                           'conditions' => array(
//                               'User.id !='         => $data['User']['id'],
//                               'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
//                               'User.sub_center_id' => $data['User']['sub_center_id'],
//                           ),
//                           'fields'     => array( 'User.email' ),
//                       ) );
//
//                       $ccEmails = $this->loginUser['User']['email'];
//                       foreach( $subCenterUsers as $u ) {
//                           $ccEmails .= ", {$u['User']['email']}";
//                       }
//                       $message = '<div>
//                           <p style="color: #5B5861; line-height: 22px;">
//                               Dear ' . $ticketData['Ticket']['supplier'] . ',
//                               <br /><br />SITE NAME: ' . $ticketData['Ticket']['site'] . '
//                               <br />SUBCENTER: ' . $ticketData['Ticket']['sub_center'] . '
//                               <br />REGION: ' . $ticketData['Ticket']['region'] . '
//                               <br />TR NUMBER: ' . $ticketData['Ticket']['id'] . '
//                               <br />ASSET GROUP: ' . $ticketData['Ticket']['asset_group'] . '
//                               <br />SUPPLIER NAME: ' . $ticketData['Ticket']['supplier'] . '
//                               <br />TR CREATION DATE: ' . $ticketData['Ticket']['created'] . '
//                               <br />RECEIVED AT SUPPLIER SITE: ' . date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['received_at_supplier'] ) ) . '
//                               <br />TR CLASS: ' . $ticketData['Ticket']['tr_class'] . '
//                               <br />TR ISSUER: ' . $this->loginUser['User']['name'] . '
//                               <br />PROPOSED COMPLETION DATE &amp; TIME: ' . ( !empty( $ticketData['Ticket']['complete_date'] ) ? date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['complete_date'] ) ) : '' ) . '
//                               <br />TR COMMENT: ' . $ticketData['Ticket']['comment'] . '
//                           </p>
//                       </div>';
//                       $emailResult = 1;
//                       if( !$this->sendEmailGP( $supplierEmail['Supplier']['email'], $ccEmails, "{$ticketData['Ticket']['id']}_{$ticketData['Ticket']['site']}_{$ticketData['Ticket']['tr_class']}", $message ) ) {
//                           $emailResult = 0;
//                           $errors[] = 'Email send unsuccessful for TR ID: ' . $this->Ticket->id;
//                       }
//
//                       $this->Notification->create();
//                       $this->Notification->save( array( 'Notification' => array(
//                           'ticket_id' => $this->Ticket->id,
//                           'type'      => 0,
//                           'receiver'  => $supplierEmail['Supplier']['email'],
//                           'result'    => $emailResult,
//                       ) ) );
//                       //</editor-fold>
//
//                       //<editor-fold desc="Send SMS" defaultstate="collapsed">
//                       $users = $this->User->find( 'all', array(
//                           'conditions' => array(
//                               'OR'          => array(
//                                   array(
//                                       'User.role'     => SUPPLIER,
//                                       'Supplier.name' => $saveData['supplier'],
//                                   ),
//                                   array(
//                                       'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
//                                       'User.sub_center_id' => $data['User']['sub_center_id'],
//                                   ),
//                               ),
//                               'User.status' => ACTIVE,
//                           ),
//                           'contain'    => array( 'Supplier.name' ),
//                           'fields'     => array( 'User.phone' ),
//                       ) );
//
//
//                       if( !empty( $users ) ) {
//                           $message = "TR: {$ticketData['Ticket']['id']}"
//                               . "\nSITE: {$ticketData['Ticket']['site']}"
//                               . "\nSC: {$ticketData['Ticket']['sub_center']}"
//                               . "\nCLASS: {$ticketData['Ticket']['tr_class']}"
//                               . "\nCreator: " . $this->loginUser['User']['name']
//                               . "\nRcv Date: {$ticketData['Ticket']['received_at_supplier']}"
//                               . "\nComment: " . substr( $ticketData['Ticket']['comment'], 0, 30 );
//                           if( strlen( $message ) > 160 ) {
//                               $message = substr( $message, 0, 160 );
//                           }
//
//                           foreach( $users as $u ) {
//                               $smsResult = $this->sendSMS( $u['User']['phone'], $message );
//                               if( !$smsResult['result'] ) {
//                                   $errors[] = 'SMS send unsuccessful for TR ID: ' . $this->Ticket->id;
//                               }
//
//                               $this->Notification->create();
//                               $this->Notification->save( array( 'Notification' => array(
//                                   'ticket_id' => $this->Ticket->id,
//                                   'type'      => 1,
//                                   'receiver'  => $u['User']['phone'],
//                                   'result'    => $smsResult['result'] ? 1 : 0,
//                                   'msg_id'    => $smsResult['msgId'],
//                               ) ) );
//                           }
//                       }
//                       //</editor-fold>
                }

                $this->Session->setFlash(__(empty($errors) ? 'Ticket saved successfully.' : implode('<br />', $errors)), 'messages/info');
                if (empty($errors)) {
                    $this->redirect(array('plugin' => 'admin', 'controller' => 'tickets', 'action' => 'index', '#' => 'assigned'));
                } else {
                    $this->set('unsavedTR', $unsavedTR);
                }
            } catch (Exception $e) {
                if ($this->request->is('ajax')) {
                    die(json_encode(array('result' => FALSE, 'message' => __($e->getMessage()))));
                } else {
                    $this->Session->setFlash(__($e->getMessage()), 'messages/failed');
                }
            }
        }
        $blockDate = $this->CustomValue->find(
            'first',
            array(
                'conditions' => array(
                    'CustomValue.name' => 'TR_MIN_DATE'
                )
            )
        );


        /*filter the site using existing user id*/
        $sites = $this->WarrantyLookup->getSiteList($data['User']['sub_center_id']); /*$data['User']['sub_center_id']*/

        $siteList = array();
        foreach ($sites as $id => $name) {
            $siteList[] = array('name' => $name, 'value' => $name, 'data-id' => $id);
        }

        $suppliers = $this->WarrantyLookup->getSupplierList(NULL);
        $supplierList = array();

        foreach ($suppliers as $id => $name) {
            $supplierList[] = array('name' => $name, 'value' => $name, 'data-id' => $id);
        }


        $this->set(array(
            'siteList' => $siteList,
            'supplierList' => $supplierList,
            'block_date' => $blockDate["CustomValue"],
            'title_for_layout' => 'Ticket ' . (empty($trId) ? 'Add' : 'Edit'),
        ));
    }

    /**
     * TRCLass RF check without RE
     *
     * @param
     *
     * @throws
     */
    function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    /**
     * Add a services
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function add_service( $trId ) {
        $data = $this->Ticket->find( 'first', array(
            'conditions' => array( 'Ticket.id' => $trId),
            'contain'    => FALSE,
        ) );
        if( empty( $data ) ) {
            throw new Exception( 'Invalid ticket ID.' );
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
                            'Service.service_name' => $trs['service'],
                            'Service.asset_group'  => $data['Ticket']['asset_group'],
                        ),
                        'contain'    => FALSE,
                    ) );


                    $quantity = floatval( $trs['quantity'] );
                    $currentMeterReading = $trs['current_meter_reading'];
                    $saveData = array( 'TrService' => array(
                        'ticket_id'           => $this->request->data['Ticket']['id'],
                        'service'             => $service['Service']['service_name'],
                        'service_desc'        => $service['Service']['service_desc'],
                        'supplier'            => $service['Service']['supplier'],
                        'quantity'            => $quantity,
                        'current_meter_reading'=> $currentMeterReading,
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

                $oldPrices = $this->Ticket->find('first',array(
                   'conditions' => array('Ticket.id' => $this->request->data['Ticket']['id']),
                    'contain' => FALSE,
                ));

                $tkt_total = $oldPrices['Ticket']['total'];
                $tkt_vat = $oldPrices['Ticket']['vat_total'];
                $tkt_total_with_vat = $oldPrices['Ticket']['total_with_vat'];

                //<editor-fold desc="Update ticket" defaultstate="collapsed">
                $trData = array( 'Ticket' => array(
                    'id'             => $this->request->data['Ticket']['id'],
                    'total'          => $total+$tkt_total,
                    'vat_total'      => $vat_total+$tkt_vat,
                    'total_with_vat' => $total_with_vat+$tkt_total_with_vat,
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
                    $this->redirect( array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'add', $this->request->data['Ticket']['id'] ) );
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



        $services = $this->WarrantyLookup->getServiceListAdmin( TRUE, $data['Ticket']['asset_group'],$data['Ticket']['tr_class'] );

        $CurMeter = $this->startsWith($data['Ticket']['tr_class'],'RF');
        $serviceList = array();
        foreach( $services as $s ) {
            $serviceList[] = array( 'name' => $s['Service']['service_desc'], 'value' => $s['Service']['service_name'], 'data-id' => $s['Service']['id'] );
        }

        $this->set( array(
            'data'             => $data,
            'serviceList'      => $serviceList,
            'CurMeter'         => $CurMeter,
            'title_for_layout' => 'Service Add',
        ) );
    }
    /**
     * View a ticket details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view($trId = NULL)
    {

        $this->loadModel('Ticket');
        $data = $this->Ticket->find('first', array(
            'conditions' => array('Ticket.id' => $trId),/*, 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name']*/
            'contain' => array(
                'TrService' => array(
                    'conditions' => array('TrService.status' => ACTIVE, 'TrService.is_deleted' => 0 , 'TrService.ticket_id' => $trId),
                ),
            ),
        ));
        if (empty($data)) {
            throw new NotFoundException('Invalid Ticket ID.');
        }

        if ($data['Ticket']['lock_status'] == NULL) {
            $type = 'assigned';
        } else if ($data['Ticket']['lock_status'] == LOCK && $data['Ticket']['pending_status'] == NULL) {
            $type = 'locked';
        } else if ($data['Ticket']['pending_status'] == PENDING && $data['Ticket']['approval_status'] == NULL) {
            $type = 'pending';
        } else if ($data['Ticket']['approval_status'] == APPROVE && $data['Ticket']['invoice_id'] == 0) {
            $type = 'approved';
        } else if ($data['Ticket']['approval_status'] == DENY) {
            $type = 'rejected';
        }

        $this->set(array(
            'data' => $data,
            'type' => $type,
            'title_for_layout' => 'Ticket Details',
        ));
    }


    /**
     * View a service details
     *
     * @param integer $serviceId
     *
     * @throws NotFoundException
     */

    public function service($serviceId = NULL)
    {
        $this->loadModel('TrService');
        $Tr_data = $this->TrService->find('first', array(
            'conditions' => array('TrService.id' => $serviceId),
        ));

        if( empty( $Tr_data ) ) {
            throw new Exception( 'Invalid Ticket Service.' );
        }

        //Price update
        $vat_total = $this->TrService->query("SELECT * FROM `tr_services` WHERE id = $serviceId");
        $ticket_id = $this->TrService->query("SELECT ticket_id FROM `tr_services` WHERE id = $serviceId");

        $tkt_id = $ticket_id[0]['tr_services']['ticket_id'];
        $service_total_with_vat = $vat_total[0]['tr_services']['total_with_vat'];
        $service_vat_total = $vat_total[0]['tr_services']['vat_total'];
        $service_total = $vat_total[0]['tr_services']['total'];

        $this->loadModel('Ticket');
        $ticket_total = $this->Ticket->query("SELECT * FROM `tickets` WHERE id = $tkt_id");

        $tkt_total_with_vat = $ticket_total[0]['tickets']['total_with_vat'];
        $left_total_with_vat = $tkt_total_with_vat==0 ? 0 : $tkt_total_with_vat - $service_total_with_vat;

        $tkt_vat_total = $ticket_total[0]['tickets']['vat_total'];
        $left_vat_total = $tkt_vat_total==0 ? 0 : $tkt_vat_total - $service_vat_total;

        $tkt_total = $ticket_total[0]['tickets']['total'];
        $left_total = $tkt_total==0 ? 0 : $tkt_total - $service_total;



        if ($this->request->is(array('post', 'put'))) {
            $this->loadModel('TrService');
            $this->TrService->id = $serviceId;

            if ($this->TrService->save($this->request->data)) {
                $new_total_with_vat = $left_total_with_vat + $this->request->data['TrService']['total_with_vat'];
                $new_vat_total = $left_vat_total + $this->request->data['TrService']['vat_total'];
                $new_total = $left_total + $this->request->data['TrService']['total'];
                $this->Ticket->id = $this->Ticket->field('id', array('id' => $tkt_id));
                if ($this->Ticket->id) {
                    $this->Ticket->saveField('total_with_vat', $new_total_with_vat);
                    $this->Ticket->saveField('vat_total', $new_vat_total);
                    $this->Ticket->saveField('total', $new_total);
                    return $this->redirect(array('action' => 'add', $tkt_id));
                }
            }
        }

        $supplier = $this->TrService->query("SELECT supplier FROM `tr_services` WHERE id = $serviceId");
        $this->loadModel('Supplier');
        $supplierId = $this->Supplier->find('all',array(
            'conditions' => array('Supplier.name' => $supplier[0]['tr_services']['supplier']),
            'contain' => FALSE,
            'fields' => array('Supplier.id'),
            'limit' => 1,
        ));
        $services = $this->WarrantyLookup->getServiceNameListAdmin($supplierId[0]['Supplier']['id']);

        $serviceList = array();
        foreach( $services as $s ) {
            $serviceList[] = array( 'name' => $s['Service']['service_name'], 'value' => $s['Service']['service_name'], 'data-id' => $s['Service']['service_name'] );
        }
         $this->set( array(
             'tr_data'=> $Tr_data,
             'tkt_id' => $tkt_id,
             'serviceList'=>$serviceList
         ) );
    }

    /**
     * Delete service From Ticket
     *
     * @param integer $serviceId ,  $ticketId
     *
     * @throws NotFoundException
     */

    public function deleteService($serviceId = null){
        $this->loadModel('TrService');
        $total_with_vat = $this->TrService->query("SELECT total_with_vat FROM `tr_services` WHERE id = $serviceId");
        $vat_total = $this->TrService->query("SELECT vat_total FROM `tr_services` WHERE id = $serviceId");
        $total = $this->TrService->query("SELECT total FROM `tr_services` WHERE id = $serviceId");
        $ticket_id = $this->TrService->query("SELECT ticket_id FROM `tr_services` WHERE id = $serviceId");

        $tkt_id = $ticket_id[0]['tr_services']['ticket_id'];
        $service_total_with_vat = $total_with_vat[0]['tr_services']['total_with_vat'];
        $service_vat_total = $vat_total[0]['tr_services']['vat_total'];
        $service_total = $total[0]['tr_services']['total'];

        $this->loadModel('Ticket');
        $ticket_total = $this->Ticket->query("SELECT total FROM `tickets` WHERE id = $tkt_id");
        $ticket_vat_total = $this->Ticket->query("SELECT vat_total FROM `tickets` WHERE id = $tkt_id");
        $ticket_total_with_vat = $this->Ticket->query("SELECT total_with_vat FROM `tickets` WHERE id = $tkt_id");

        $tkt_total = $ticket_total[0]['tickets']['total'];
        $tkt_vat_total =$ticket_vat_total[0]['tickets']['vat_total'];
        $tkt_total_with_vat = $ticket_total_with_vat[0]['tickets']['total_with_vat'];


        $left_total_with_vat = $tkt_total_with_vat - $service_total_with_vat;
        $left_vat_total =$tkt_vat_total - $service_vat_total;
        $left_total = $tkt_total - $service_total;

        if($this->TrService->delete($serviceId)){
            $this->Ticket->id = $this->Ticket->field('id', array('id' => $tkt_id));
            if ($this->Ticket->id) {
                $this->Ticket->saveField('total_with_vat', $left_total_with_vat);
                $this->Ticket->saveField('vat_total', $left_vat_total);
                $this->Ticket->saveField('total', $left_total);
                return $this->redirect(array('action' => 'add',$tkt_id));
            }
        }
        else{
            $this->Flash->success('Sorry !');
        }
    }

    /**
     * Validate ticket for budget
     *
     * @param string $trClassName
     *
     * @return array
     */
    private function check($trClassName)
    {
        $mainTypes = Configure::read('mainTypes');
        $mainType = $this->WarrantyLookup->getMainType($trClassName);

        if (!in_array($mainType, $mainTypes)) {
            //return array( 'status' => 2, 'msg' => 'No budget for this TR class' );
            return array('status' => -1, 'msg' => '');
        }

        $this->loadModel('SubCenter');
        $min_cost = $this->SubCenter->find('first', array('conditions' => array('SubCenter.id' => $this->loginUser['User']['sub_center_id']), 'contain' => FALSE));

        $this->loadModel('SubCenterBudget');
        $budget_data = $this->SubCenterBudget->find('first', array(
            'conditions' => array(
                'SubCenterBudget.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                'SubCenterBudget.year' => date('Y'),
                'SubCenterBudget.month' => date('m'),
            ),
            'contain' => array('SubCenter'),
        ));
        if (empty($budget_data)) {
            return array('status' => -1, 'msg' => '');
        }

        $total_budget = $budget_data['SubCenterBudget'][$mainType . '_initial_budget'] + $budget_data['SubCenterBudget'][$mainType . '_forwarded_budget'];
        $total_use = $budget_data['SubCenterBudget'][$mainType . '_consumed_budget'] + $min_cost['SubCenter'][$mainType . '_min_budget'];
        $get_cost_percent = $total_use / $total_budget * 100;

        if ($get_cost_percent >= 80 && $get_cost_percent < 90) {
            if ($budget_data['SubCenter']['eighty_percent_action'] == YES) {
                return array('status' => YES, 'msg' => 'You can not exceed 80% of Subcenter Budget');
            } else {
                return array('status' => NO, 'msg' => 'You are exceeding 80% of Subcenter Budget');
            }
        } else if ($get_cost_percent >= 90 && $get_cost_percent < 100) {
            if ($budget_data['SubCenter']['ninety_percent_action'] == YES) {
                return array('status' => YES, 'msg' => 'You can not exceed 90% of Subcenter Budget');
            } else {
                return array('status' => NO, 'msg' => 'You are exceeding 90% of Subcenter Budget');
            }
        } else if ($get_cost_percent >= 100) {
            if ($budget_data['SubCenter']['hundred_percent_action'] == YES) {
                return array('status' => YES, 'msg' => 'You can not exceed 100% of Subcenter Budget');
            } else {
                return array('status' => NO, 'msg' => 'You are exceeding 100% of Subcenter Budget');
            }
        } else {
            return array('status' => -1, 'msg' => '');
        }
    }

    /**
     * Ticket create: ajax function after site selection
     */
//    public function site_selected()
//    {
//        $this->autoRender = FALSE;
//
//        $this->loadModel('Project');
//        $data['Project'] = $this->Project->find('all', array(
//            'conditions' => array('Project.site_id' => $this->request->data['site_id']),
//            'contain' => FALSE,
//            'fields' => array('id', 'project_name'),
//        ));
//
//        $this->loadModel('AssetGroup');
//        $data['AssetGroup'] = $this->AssetGroup->find('all', array(
//            'conditions' => array('AssetGroup.site_id' => $this->request->data['site_id']),
//            'contain' => FALSE,
//            'fields' => array('id', 'asset_group_name'),
//        ));
//
//        die(json_encode($data));
//    }
    /**
     *
     */
    public function site_selected() {
        $this->autoRender = FALSE;

        $this->loadModel('TrClass');
        $data['TrClass'] = $this->TrClass->find('all',array(
            'conditions' => array('TrClass.site_id' => $this->request->data['site_id']),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),

        ));
        $tr_class = $this->request->data['tr_class'];
        $site_id = $this->request->data['site_id'];
        $trid = $this->TrClass->query("SELECT id FROM `tr_classes` WHERE tr_class_name='$tr_class' AND site_id = $site_id");
        $tr_id = $trid[0]['tr_classes']['id'];
        $asset_grp_id = $this->TrClass->query("SELECT asset_group_id FROM `tr_classes` WHERE id = $tr_id");
        $this->loadModel( 'AssetGroup' );
        $val = $this->AssetGroup->find( 'all', array(
            'conditions' => array(
                'AssetGroup.id' => $asset_grp_id[0]['tr_classes']['asset_group_id'] ,
                'AssetGroup.site_id' =>  $this->request->data['site_id']),
            'contain'    => FALSE,
            'fields'     => array('id','asset_group_name' ),
        ));
//
        $data['AssetGroup'] = $val;
//
        $assetGroupIds = array();
        foreach ($val as $id){
            $assetGroupIds[] =  $id['AssetGroup']['id'];
        }

        $this->loadModel('AssetNumber');
        $data['AssetNumber'] = $this->AssetNumber->find('all', array(
            'conditions' => array( 'AssetNumber.asset_group_id' => $assetGroupIds ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_number' ),
        ));

        die(json_encode( $data ));
    }

    public function tr_class_selected() {
        $this->autoRender = FALSE;
        $this->loadModel('TrClass');
        $trId = $this->request->data['tr_id']!= 'undefined' ? $this->request->data['tr_id']:'null' ;

        $site_id = $this->TrClass->query("SELECT site_id FROM `tr_classes` WHERE id = $trId");
        $asset_grp_id = $this->TrClass->query("SELECT asset_group_id FROM `tr_classes` WHERE id = $trId");

        $this->loadModel( 'AssetGroup' );
        $val = $this->AssetGroup->find( 'all', array(
            'conditions' => array(
                'AssetGroup.id' => $asset_grp_id[0]['tr_classes']['asset_group_id'] ,
                'AssetGroup.site_id' =>  $site_id[0]['tr_classes']['site_id']),
            'contain'    => FALSE,
            'fields'     => array('id','asset_group_name' ),
        ));


        $data['AssetGroup'] = $val;

        $assetGroupIds = array();
        foreach ($val as $id){
            $assetGroupIds[] =  $id['AssetGroup']['id'];
        }

        $this->loadModel('AssetNumber');
        $data['AssetNumber'] = $this->AssetNumber->find('all', array(
            'conditions' => array( 'AssetNumber.asset_group_id' => $assetGroupIds ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_number' ),
        ));




        die( json_encode( $data ) );
    }

    /**
     * Ticket create: ajax function after asset_group selection
     */
//    public function asset_group_selected()
//    {
//        $this->autoRender = FALSE;
//
//        $this->loadModel('AssetNumber');
//        $data['AssetNumber'] = $this->AssetNumber->find('all', array(
//            'conditions' => array('AssetNumber.asset_group_id' => $this->request->data['asset_group_id']),
//            'contain' => FALSE,
//            'fields' => array('id', 'asset_number'),
//        ));
//
//        $this->loadModel('TrClass');
//        $data['TrClass'] = $this->TrClass->find('all', array(
//            'conditions' => array('TrClass.asset_group_id' => $this->request->data['asset_group_id']),
//            'contain' => FALSE,
//            'fields' => array('id', 'tr_class_name'),
//        ));
//
//        die(json_encode($data));
//    }
    public function asset_group_selected() {
        $this->autoRender = FALSE;

//        $this->loadModel( 'AssetNumber' );
//        $data['AssetNumber'] = $this->AssetNumber->find( 'all', array(
//            'conditions' => array( 'AssetNumber.asset_group_id' => $this->request->data['asset_group_id'] ),
//            'contain'    => FALSE,
//            'fields'     => array( 'id', 'asset_number' ),
//        ) );

        $this->loadModel( 'TrClass' );
        $data['TrClass'] = $this->TrClass->find( 'all', array(
            'conditions' => array( 'TrClass.asset_group_id' => $this->request->data['asset_group_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );

        die( json_encode( $data ) );
    }

    public function asset_number_selected() {
        $this->autoRender = FALSE;

        $this->loadModel( 'AssetNumber' );

        $assetGroupId = $this->AssetNumber->find( 'all', array(
            'conditions' => array(
                'AssetNumber.id' => $this->request->data['asset_number_id']
            ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_group_id' ),
        ) );


        $this->loadModel( 'AssetGroup' );

        $data['AssetGroup'] = $this->AssetGroup->find( 'all', array(
            'conditions' => array(
                'AssetGroup.site_id' => $this->request->data['site_id'],
                'AssetGroup.id' => $assetGroupId[0]['AssetNumber']['asset_group_id']
            ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_group_name' ),
        ) );

        $this->loadModel( 'TrClass' );
        $data['TrClass'] = $this->TrClass->find( 'all', array(
            'conditions' => array( 'TrClass.asset_group_id' => $assetGroupId[0]['AssetNumber']['asset_group_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );

        die( json_encode( $data ) );
    }

    /**
     * Ticket create: ajax function after supplier selection
     */
    public function supplier_selected()
    {
        $this->autoRender = FALSE;

        $this->loadModel('SupplierCategory');
        $data['SupplierCategory'] = $this->SupplierCategory->find('all', array(
            'conditions' => array('SupplierCategory.supplier_id' => $this->request->data['supplier_id']),
            'contain' => FALSE,
            'fields' => array('id', 'category_name'),
        ));

        die(json_encode($data));
    }

    /**
     * /**
     * Auto-populate Completion date via ajax
     */
    public function calculate_complete_date()
    {
        $this->autoRender = FALSE;

        $this->loadModel('TrClass');
        $data = $this->TrClass->find('first', array(
            'conditions' => array('TrClass.id' => $this->request->data['tr_class_id']),
            'contain' => FALSE,
            'fields' => array('id', 'tr_class_name', 'no_of_days'),
        ));
        $sla = !empty($data['TrClass']['no_of_days']) ? (24 * $data['TrClass']['no_of_days']) : 0;
        $completeDate = date('Y-m-d H:i:s', strtotime('+ ' . round($sla, 0) . ' hours', strtotime($this->request->data['received_at_supplier'])));

        die(json_encode($completeDate));
    }


}