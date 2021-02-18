<?php
App::uses( 'TrCreationAppController', 'TrCreation.Controller' );

/**
 * Tickets Controller
 */
class TicketsController extends TrCreationAppController {
    
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
        $this->set( 'title_for_layout', 'TR List' );
    }

    /**
     * After upload a file, process the excel data for confirmation to INSERT.
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @throws NotFoundException "Excel file in missing."
     * @throws UnexpectedValueException "Unexpected Excel file extension."
     * @throws Exception "Unexpected Excel file extension."
     *
     * @return void
     **/
    public function bulk_import(){
        if($this->request->is(array('post', 'put'))){
            try{
                if(!empty($this->request->data['Ticket']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Ticket']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Ticket']['file_name']['tmp_name'], WWW_ROOT.'files/ticket/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/ticket/'.$fileName);

                            $this->loadModel('Notification');
                            $this->loadModel('User');
                            $this->loadModel('SubCenter');
                            $this->loadModel('Site');
                            $this->loadModel('TrClass');
                            $this->loadModel('AssetGroup');
                            $this->loadModel('Supplier');
                            $this->loadModel('SupplierCategory');

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Office" defaultstate="collapsed">
                                $officeCheck = $this->SubCenter->find('first', array(
                                        'conditions'=>array(
                                            'sub_center_name'=>strtoupper(trim($excel->sheets[0]['cells'][$x][1]))
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'SubCenter.id, SubCenter.sub_center_name'
                                    )
                                );

                                if(!empty($officeCheck)): // Excel Value is Available in Database.
                                    $office_id = $officeCheck['SubCenter']['id'];
                                    $officeStatus = 1;
                                    $regionName = $this->SubCenter->find('first', array(
                                        'contain' => array('Region'),
                                        'conditions' => array(
                                            'SubCenter.sub_center_name' => $officeCheck['SubCenter']['sub_center_name']
                                        ),
                                        'fields' => 'Region.region_name'
                                    ));
                                    $regionName = $regionName["Region"]["region_name"];

                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $office_id = null;
                                    $officeStatus = 0;
                                    $regionName = null;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Site" defaultstate="collapsed">
                                $siteCheck = $this->Site->find('first', array(
                                        'conditions'=>array(
                                            'site_name'     => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                            'sub_center_id' => $office_id
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Site.id, Site.site_name'
                                    )
                                );
                                if(!empty($siteCheck)): // Excel Value is Available in Database.
                                    $site_id = $siteCheck['Site']['id'];
                                    $siteStatus = 1;
                                else:                   // Excel Value is 'NOT' Available in Database.
                                    $site_id = null;
                                    $siteStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check TR Class" defaultstate="collapsed">
                                $trClassCheck = $this->TrClass->find('first', array(
                                    'contain' => array('AssetGroup'),
                                    'conditions' => array(
                                        'AssetGroup.site_id' => $site_id,
                                        'TrClass.tr_class_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][3]))
                                    ),
                                    'fields' => 'TrClass.id, TrClass.tr_class_name'
                                ));
                                if(!empty($trClassCheck)): // Excel Value is Available in Database.
                                    $trClassStatus = 1;
                                    $astGroupName = $this->TrClass->find('first', array(
                                        'contain' => array('AssetGroup'),
                                        'conditions' => array(
                                            'TrClass.tr_class_name' => $trClassCheck['TrClass']['tr_class_name']
                                        ),
                                        'fields' => 'AssetGroup.asset_group_name'
                                    ));
                                    $astGroupName = $astGroupName["AssetGroup"]["asset_group_name"];

                                else:                   // Excel Value is 'NOT' Available in Database.
                                    $trClassStatus = 0;
                                    $astGroupName = null;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Supplier" defaultstate="collapsed">
                                $supplierCheck = $this->Supplier->find('first', array(
                                        'conditions'=>array(
                                            'name'=>strtoupper(trim($excel->sheets[0]['cells'][$x][4]))
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Supplier.id, Supplier.name'
                                    )
                                );
                                if(!empty($supplierCheck)): // Excel Value is Available in Database.
                                    $supplier_id = $supplierCheck['Supplier']['id'];
                                    $supplierStatus = 1;
                                else:                       // Excel Value is 'NOT' Available in Database.
                                    $supplier_id = null;
                                    $supplierStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Supplier Category" defaultstate="collapsed">
                                $supCatCheck = $this->SupplierCategory->find('first', array(
                                        'conditions'=>array(
                                            'category_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][5])),
                                            'supplier_id'   => $supplier_id
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'SupplierCategory.id, SupplierCategory.category_name'
                                    )
                                );
                                if(!empty($supCatCheck)): // Excel Value is Available in Database.
                                    $supCatStatus = 1;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $supCatStatus = 0;
                                endif;
                                //</editor-fold>

                                /*
                                echo $regionName."<br>";
                                echo $officeStatus." - ".strtoupper(trim($excel->sheets[0]['cells'][$x][1]))."<br>";
                                echo $siteStatus." - ".strtoupper(trim($excel->sheets[0]['cells'][$x][2]))."<br>";
                                echo $trClassStatus." - ".strtoupper(trim($excel->sheets[0]['cells'][$x][3]))."<br>";
                                echo $astGroupName."<br>";
                                echo $supplierStatus." - ".strtoupper(trim($excel->sheets[0]['cells'][$x][4]))."<br>";
                                echo $supCatStatus." - ".strtoupper(trim($excel->sheets[0]['cells'][$x][5]))."<br>";
                                exit;
                                */

                                $received_at = DateTime::createFromFormat('d/m/Y H:i', $excel->sheets[0]['cells'][$x][6]);
                                $received_at = $received_at->format('Y-m-d h:i:s');

                                $excelData[] = array('Ticket' => array(
                                    'officeStatus'  => $officeStatus,
                                    'siteStatus'    => $siteStatus,
                                    'trClassStatus' => $trClassStatus,
                                    'supStatus'     => $supplierStatus,
                                    'supCatStatus'  => $supCatStatus,

                                    'region'               => $regionName,
                                    'sub_center'           => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'site'                 => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'asset_group'          => $astGroupName,
                                    'tr_class'             => strtoupper(trim($excel->sheets[0]['cells'][$x][3])),
                                    'supplier'             => strtoupper(trim($excel->sheets[0]['cells'][$x][4])),
                                    'supplier_category'    => strtoupper(trim($excel->sheets[0]['cells'][$x][5])),
                                    'received_at_supplier' => $received_at,
                                    'comment'              => trim($excel->sheets[0]['cells'][$x][7]),
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/ticket/'.$fileName);
                            $this->set('ticketBulkData', $excelData);
                        }

                    }else{
                        throw new UnexpectedValueException('Please upload a valid file.');
                    }

                }else{
                    throw new NotFoundException('Please upload a file.');
                }

            }catch(Exception $e){
                $this->Session->setFlash( __( $e->getMessage() ), 'messages/failed' );
            }
        }
    }

    /**
     * Processed excel data INSERT in here.
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     **/
    public function bulk_import_post(){
        $this->autoRender = false;

        $tableData = $_POST['pTableData'];
        $tableData = json_decode($tableData,TRUE);

        $select_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                if(($tableData[$i]['officeStatus'] == 1) &&
                    ($tableData[$i]['siteStatus'] == 1) &&
                    ($tableData[$i]['trClassStatus'] == 1) &&
                    ($tableData[$i]['supStatus'] == 1) &&
                    ($tableData[$i]['supCatStatus'] == 1)){

                    $saveData = array('Ticket' => array(
                        'user_id'              => $this->loginUser['User']['id'],
                        'region'               => strtoupper(trim($tableData[$i]['region'])),
                        'sub_center'           => strtoupper(trim($tableData[$i]['sub_center'])),
                        'department'           => $this->loginUser['User']['department'],
                        'site'                 => strtoupper(trim($tableData[$i]['site'])),
                        'asset_group'          => strtoupper(trim($tableData[$i]['asset_group'])),
                        'tr_class'             => strtoupper(trim($tableData[$i]['tr_class'])),
                        'supplier'             => strtoupper(trim($tableData[$i]['supplier'])),
                        'supplier_category'    => strtoupper(trim($tableData[$i]['supplier_category'])),
                        'received_at_supplier' => $tableData[$i]['received_at_supplier'],
                        'comment'              => $tableData[$i]['comment'],
                        'stage'                => SUPPLIER_STAGE,
                        'created_by'           => $this->loginUser['User']['id'],
                        'status'               => 1,
                    ) );
                    $this->Ticket->create();
                    $this->Ticket->save($saveData);
                    $select_one++;


                    $this->loadModel('Ticket');
                    $this->loadModel( 'Notification' );
                    $this->loadModel( 'Supplier' );
                    $this->loadModel( 'User' );

                    //<editor-fold desc="Send email" defaultstate="collapsed">
                    $supplierEmail = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.name' => $saveData['Ticket']['supplier'] ), 'contain' => FALSE ) );
                    $subCenterUsers = $this->User->find( 'all', array(
                        'contain'    => FALSE,
                        'conditions' => array(
                            'User.id !='         => $this->loginUser['User']['id'],
                            'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
                            'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                        ),
                        'fields'     => array( 'User.email' ),
                    ) );
                    $ccEmails = $this->loginUser['User']['email'];
                    foreach( $subCenterUsers as $u ) {
                        $ccEmails .= ", {$u['User']['email']}";
                    }
                    $message = '<div>
                        <p style="color: #5B5861; line-height: 22px;">
                            Dear ' . $saveData['Ticket']['supplier'] . ',
                            <br /><br />SITE NAME: ' . $saveData['Ticket']['site'] . '
                            <br />SUBCENTER: ' . $saveData['Ticket']['sub_center'] . '
                            <br />REGION: ' . $saveData['Ticket']['region'] . '
                            <br />TR NUMBER: ' . $this->Ticket->id . '
                            <br />ASSET GROUP: ' . $saveData['Ticket']['asset_group'] . '
                            <br />SUPPLIER NAME: ' . $saveData['Ticket']['supplier'] . '
                            <br />RECEIVED AT SUPPLIER SITE: ' . date( 'j-M-y G:i:s A', strtotime( $saveData['Ticket']['received_at_supplier'] ) ) . '
                            <br />TR CLASS: ' . $saveData['Ticket']['tr_class'] . '
                            <br />TR ISSUER: ' . $this->loginUser['User']['name'] . '
                            <br />TR COMMENT: ' . $saveData['Ticket']['comment'] . '
                        </p>
                    </div>';
                    $emailResult = 1;
                    if( !$this->sendEmailGP( $supplierEmail['Supplier']['email'], $ccEmails, "{ $this->Ticket->id}_{$saveData['Ticket']['site']}_{$saveData['Ticket']['tr_class']}", $message ) ) {
                        $emailResult = 0;
                        $errors[] = 'Email send unsuccessful for TR ID: ' . $this->Ticket->id;
                    }

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
                                    'Supplier.name' => $saveData['Ticket']['supplier'],
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
                        $message = "TR: {$this->Ticket->id}"
                            . "\nSITE: {$saveData['Ticket']['site']}"
                            . "\nSC: {$saveData['Ticket']['sub_center']}"
                            . "\nCLASS: {$saveData['Ticket']['tr_class']}"
                            . "\nCreator: " . $this->loginUser['User']['name']
                            . "\nRcv Date: {$saveData['Ticket']['received_at_supplier']}"
                            . "\nComment: " . substr( $saveData['Ticket']['comment'], 0, 30 );
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
                }
            }
        }
        $this->Session->setFlash($select_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Ticket list actions via ajax datatable
     *
     * @param  string $type
     */
    public function data( $type ) {
        $this->loadModel( 'Ticket' );
        $result = array();
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $tr = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => intval( $this->request->data['customActionName'] ), 'Ticket.lock_status' => NULL ), 'contain' => FALSE ) );
            if( !empty( $tr ) ) {
                $this->Ticket->id = $tr['Ticket']['id'];
                if( $this->Ticket->saveField( 'is_deleted', YES ) ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The TR has been deleted.';
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
        $columns = array(
            1 => array( 'modelName' => 'Ticket.id', 'searchName' => 'id', 'searchType' => '%like%' ),
            2 => array( 'modelName' => 'User.name', 'searchName' => 'name', 'searchType' => '%like%' ),
            3 => array( 'modelName' => 'Ticket.supplier_category', 'searchName' => 'supplier_category', 'searchType' => '%like%' ),
            4 => array( 'modelName' => 'Ticket.site', 'searchName' => 'site', 'searchType' => '%like%' ),
            5 => array( 'modelName' => 'Ticket.asset_group', 'searchName' => 'asset_group', 'searchType' => '%like%' ),
            6 => array( 'modelName' => 'Ticket.asset_number', 'searchName' => 'asset_number', 'searchType' => '%like%' ),
            7 => array( 'modelName' => 'Ticket.tr_class', 'searchName' => 'tr_class', 'searchType' => '%like%' ),
            8 => array( 'modelName' => 'Ticket.received_at_supplier', 'searchName' => 'received_at_supplier', 'searchType' => 'date' ),
        );
        
        $commonConditions = array( 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name'] );
        if( $type == 'assigned' ) {
            $conditions = $commonConditions + array( 'Ticket.lock_status' => NULL );
        }
        else if( $type == 'locked' ) {
            $conditions = $commonConditions + array( 'Ticket.lock_status' => LOCK, 'Ticket.pending_status' => NULL );
        }
        else if( $type == 'pending' ) {
            $conditions = $commonConditions + array( 'Ticket.pending_status' => PENDING, 'Ticket.approval_status' => NULL );
        }
        else if( $type == 'approved' ) {
            $conditions = $commonConditions + array( 'Ticket.approval_status' => APPROVE, 'Ticket.invoice_id' => 0 );
        }
        else if( $type == 'rejected' ) {
            $conditions = $commonConditions + array( 'Ticket.approval_status' => DENY );
        }
        
        foreach( $columns as $col ) {
            if( isset( $this->request->data[ $col['searchName'] ] ) && $this->request->data[ $col['searchName'] ] != '' ) {
                if( $col['searchType'] == '%like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = '%' . $this->request->data[ $col['searchName'] ] . '%';
                }
                else if( $col['searchType'] == 'like%' ) {
                    $conditions["{$col['modelName']} LIKE"] = $this->request->data[ $col['searchName'] ] . '%';
                }
                else if( $col['searchType'] == 'date' ) {
                    $conditions["DATE( {$col['modelName']} )"] = date( 'Y-m-d', $this->request->data[ $col['searchName'] ] );
                }
                else {
                    $conditions["{$col['modelName']}"] = $this->request->data[ $col['searchName'] ];
                }
            }
        }
        
        $order = array( 'Ticket.id' => 'DESC' );
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $order = array( $columns[ $this->request->data['order'][0]['column'] ]['modelName'] => $this->request->data['order'][0]['dir'] );
        }
        //</editor-fold>
        
        $total = $this->Ticket->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'User.name' ) ) );
        $data = $this->Ticket->find( 'all', array(
            'contain'    => array( 'User.name' ),
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
     * Add/edit a ticket
     *
     * @param integer|null $trId
     *
     * @throws Exception
     */
    public function add($trId = NULL){
        $this->loadModel('Ticket');
        $this->loadModel( 'TrClass' );

        if(!empty($trId)){
            $data = $this->Ticket->find( 'first', array(
                'conditions' => array(
                    'Ticket.id' => $trId,
                    'Ticket.lock_status' => NULL,
                    'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name']
                )
            ));
            if( empty( $data ) ) {
                throw new Exception( 'Invalid Ticket ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            $this->loadModel( 'Notification' );
            $this->loadModel( 'Supplier' );
            $this->loadModel( 'User' );

            $errors = $unsavedTR = array();
            try {
                if( empty( $this->request->data['Ticket']['batch'] ) ) {
                    throw new Exception( 'Please provide at least one TR information.' );
                }
                
                $common = array(
                    'user_id'        => $this->loginUser['User']['id'],
                    'department'     => $this->loginUser['User']['department'],
                    'created_by'     => $this->loginUser['User']['id'],
                    'stage'          => SUPPLIER_STAGE,
                    'form_open_time' => $this->request->data['Ticket']['form_open_time'],
                );
                
                foreach( $this->request->data['Ticket']['batch'] as $ticket ) {
                    //<editor-fold desc="Save ticket" defaultstate="collapsed">
                    $saveData = $common + $ticket;

                    $this->loadModel( 'SubCenter' );
                    $subCenterID = $this->SubCenter->find('first', array(
                        'contain' => FALSE,
                        'conditions' => array(
                            'SubCenter.sub_center_name' => $saveData['sub_center']
                        ),
                        'fields' => array('SubCenter.id')
                    ));

                    unset($saveData['sub_center_id']);
                    unset($saveData['site_id']);
                    unset($saveData['tr_class_id']);
                    
                    $budgetValidation = $this->check($saveData['tr_class'], $subCenterID['SubCenter']['id']);
                    if( in_array( $budgetValidation['status'], array( 1, 2 ) ) ) {
                        $errors[] = $budgetValidation['msg'];
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

                    $subCenterName = $this->SubCenter->find('first', array(
                        'contain' => array('Region'),
                        'conditions' => array(
                            'SubCenter.sub_center_name' => $saveData['sub_center']
                        ),
                        'fields' => array(
                            'Region.region_name'
                        )
                    ));

                    if(!empty($subCenterName)){
                        $saveData['region'] = $subCenterName["Region"]["region_name"];
                    }

                    $this->loadModel( 'TrClass' );
                    $this->loadModel( 'AssetGroup' );
                    $assetGroupName = $this->TrClass->find('first', array(
                        'contain' => array('AssetGroup'),
                        'conditions' => array(
                            'TrClass.tr_class_name' => $saveData['tr_class']
                        ),
                        'fields' => array(
                            'AssetGroup.asset_group_name'
                        )
                    ));

                    if(!empty($assetGroupName)){
                        $saveData['asset_group'] = $assetGroupName["AssetGroup"]["asset_group_name"];
                    }
                    
                    $this->Ticket->create();
                    if( !$this->Ticket->save( $saveData ) ) {
                        $saveErrors = '';
                        foreach( $this->User->validationErrors as $field => $validationError ) {
                            $saveErrors .= ( $saveErrors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $validationError );
                        }
                        $errors[] = $saveErrors;
                        $unsavedTR[] = $saveData;
                        continue;
                    }
                    //</editor-fold>
                    
                    $ticketData = $this->Ticket->find( 'first', array( 'conditions' => array( 'Ticket.id' => $this->Ticket->id ), 'contain' => FALSE ) );
                    
                    //<editor-fold desc="Send email" defaultstate="collapsed">
                    $supplierEmail = $this->Supplier->find( 'first', array( 'conditions' => array( 'Supplier.name' => $saveData['supplier'] ), 'contain' => FALSE ) );
                    $subCenterUsers = $this->User->find( 'all', array(
                        'contain'    => FALSE,
                        'conditions' => array(
                            'User.id !='         => $this->loginUser['User']['id'],
                            'User.role'          => array( TR_CREATOR, TR_VALIDATOR ),
                            'User.sub_center_id' => $this->loginUser['User']['sub_center_id'],
                        ),
                        'fields'     => array( 'User.email' ),
                    ) );
                    $ccEmails = $this->loginUser['User']['email'];
                    foreach( $subCenterUsers as $u ) {
                        $ccEmails .= ", {$u['User']['email']}";
                    }
                    $message = '<div>
                        <p style="color: #5B5861; line-height: 22px;">
                            Dear ' . $ticketData['Ticket']['supplier'] . ',
                            <br /><br />SITE NAME: ' . $ticketData['Ticket']['site'] . '
                            <br />SUBCENTER: ' . $ticketData['Ticket']['sub_center'] . '
                            <br />REGION: ' . $ticketData['Ticket']['region'] . '
                            <br />TR NUMBER: ' . $ticketData['Ticket']['id'] . '
                            <br />ASSET GROUP: ' . $ticketData['Ticket']['asset_group'] . '
                            <br />SUPPLIER NAME: ' . $ticketData['Ticket']['supplier'] . '
                            <br />TR CREATION DATE: ' . $ticketData['Ticket']['created'] . '
                            <br />RECEIVED AT SUPPLIER SITE: ' . date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['received_at_supplier'] ) ) . '
                            <br />TR CLASS: ' . $ticketData['Ticket']['tr_class'] . '
                            <br />TR ISSUER: ' . $this->loginUser['User']['name'] . '
                            <br />PROPOSED COMPLETION DATE &amp; TIME: ' . ( !empty( $ticketData['Ticket']['complete_date'] ) ? date( 'j-M-y G:i:s A', strtotime( $ticketData['Ticket']['complete_date'] ) ) : '' ) . '
                            <br />TR COMMENT: ' . $ticketData['Ticket']['comment'] . '
                        </p>
                    </div>';
                    $emailResult = 1;
                    if( !$this->sendEmailGP( $supplierEmail['Supplier']['email'], $ccEmails, "{$ticketData['Ticket']['id']}_{$ticketData['Ticket']['site']}_{$ticketData['Ticket']['tr_class']}", $message ) ) {
                        $emailResult = 0;
                        $errors[] = 'Email send unsuccessful for TR ID: ' . $this->Ticket->id;
                    }
                    
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
                                    'Supplier.name' => $saveData['supplier'],
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
                        $message = "TR: {$ticketData['Ticket']['id']}"
                            . "\nSITE: {$ticketData['Ticket']['site']}"
                            . "\nSC: {$ticketData['Ticket']['sub_center']}"
                            . "\nCLASS: {$ticketData['Ticket']['tr_class']}"
                            . "\nCreator: " . $this->loginUser['User']['name']
                            . "\nRcv Date: {$ticketData['Ticket']['received_at_supplier']}"
                            . "\nComment: " . substr( $ticketData['Ticket']['comment'], 0, 30 );
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
                }
                
                $this->Session->setFlash( __( empty( $errors ) ? 'Ticket saved successfully.' : implode( '<br />', $errors ) ), 'messages/info' );
                if( empty( $errors ) ) {
                    $this->redirect( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'assigned' ) );
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

//        $offices = $this->WarrantyLookup->getOfficeList($this->loginUser['User']['sub_center_id']);
//        $officeList = array();
//        foreach( $offices as $id => $name ) {
//            $officeList[] = array('name' => $name, 'value' => $name, 'data-id' => $id);
//        }
        $this->loadModel('SubCenter');
        $officeList = $this->SubCenter->find( 'list', array(
            'contain'    => FALSE,
            'order'      => array( 'SubCenter.id' => 'ASC' ),
            'fields'     => array( 'SubCenter.id', 'SubCenter.sub_center_name' ),
        ) );
        
        $suppliers = $this->WarrantyLookup->getSupplierList();
        $supplierList = array();
        foreach( $suppliers as $id => $name ) {
            $supplierList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }

        $trClass = $this->TrClass->find( 'list', array(
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );

        $this->set( array(
            'officeList'       => $officeList,
            'supplierList'     => $supplierList,
            'trClass'          => $trClass,
            'title_for_layout' => 'Ticket ' . ( empty( $trId ) ? 'Add' : 'Edit' ),
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
            'conditions' => array( 'Ticket.id' => $trId, 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name'] ),
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
    
    /**
     * Validate ticket for budget
     *
     * @param string $trClassName
     *
     * @return array
     */
    private function check($trClassName, $subCenterID) {
        $mainTypes = Configure::read( 'mainTypes' );
        $mainType = $this->WarrantyLookup->getMainType( $trClassName );
        
        if( !in_array( $mainType, $mainTypes ) ) {
            //return array( 'status' => 2, 'msg' => 'No budget for this TR class' );
            return array( 'status' => -1, 'msg' => '' );
        }
        
        $this->loadModel( 'SubCenter' );
        $min_cost = $this->SubCenter->find( 'first', array( 'conditions' => array( 'SubCenter.id' => $subCenterID ), 'contain' => FALSE ) );
        
        $this->loadModel( 'SubCenterBudget' );
        $budget_data = $this->SubCenterBudget->find( 'first', array(
            'conditions' => array(
                'SubCenterBudget.sub_center_id' => $subCenterID,
                'SubCenterBudget.year'          => date( 'Y' ),
                'SubCenterBudget.month'         => date( 'm' ),
            ),
            'contain'    => array( 'SubCenter' ),
        ) );
        if( empty( $budget_data ) ) {
            return array( 'status' => -1, 'msg' => '' );
        }
    
        $total_budget = $budget_data['SubCenterBudget'][ $mainType . '_initial_budget' ] + $budget_data['SubCenterBudget'][ $mainType . '_forwarded_budget' ];
        $total_use = $budget_data['SubCenterBudget'][ $mainType . '_consumed_budget' ] + $min_cost['SubCenter'][ $mainType . '_min_budget' ];
        $get_cost_percent = $total_use / $total_budget * 100;
        
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

    /**
     * Ticket create: ajax function after office selection
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     */
    public function office_selected() {
        $this->autoRender = FALSE;

        $sub_center_id = $this->request->data['office_id'];

        $this->loadModel( 'Site' );
        $data['Site'] = $this->Site->find( 'all', array(
            'conditions' => array( 'Site.sub_center_id' => $sub_center_id ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'site_name' ),
        ) );

        die( json_encode( $data ) );
    }

    /**
     * Ticket create: ajax function for load TR Class
     * after site selection
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     */
    public function site_selected_tr_class() {
        $this->autoRender = FALSE;

        $site_id = $this->request->data['site_id'];

        $this->loadModel( 'TrClass' );
        $this->loadModel( 'AssetGroup' );

        $data['TrClass'] = $this->TrClass->find('all', array(
            'contain' => array('AssetGroup'),
            'conditions' => array(
                'AssetGroup.site_id' => $site_id
            ),
            'fields' => array(
                'TrClass.id',
                'TrClass.tr_class_name'
            )
        ));

        die( json_encode( $data ) );
    }
    
    /**
     * Ticket create: ajax function after site selection
     */
    public function site_selected() {
        $this->autoRender = FALSE;
        
        $this->loadModel( 'Project' );
        $data['Project'] = $this->Project->find( 'all', array(
            'conditions' => array( 'Project.site_id' => $this->request->data['site_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'project_name' ),
        ) );
        
        $this->loadModel( 'AssetGroup' );
        $data['AssetGroup'] = $this->AssetGroup->find( 'all', array(
            'conditions' => array( 'AssetGroup.site_id' => $this->request->data['site_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_group_name' ),
        ) );
        
        die( json_encode( $data ) );
    }
    
    /**
     * Ticket create: ajax function after asset_group selection
     */
    public function asset_group_selected() {
        $this->autoRender = FALSE;
        
        $this->loadModel( 'AssetNumber' );
        $data['AssetNumber'] = $this->AssetNumber->find( 'all', array(
            'conditions' => array( 'AssetNumber.asset_group_id' => $this->request->data['asset_group_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'asset_number' ),
        ) );
        
        $this->loadModel( 'TrClass' );
        $data['TrClass'] = $this->TrClass->find( 'all', array(
            'conditions' => array( 'TrClass.asset_group_id' => $this->request->data['asset_group_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );
        
        die( json_encode( $data ) );
    }
    
    /**
     * Ticket create: ajax function after supplier selection
     */
    public function supplier_selected() {
        $this->autoRender = FALSE;
    
        $this->loadModel( 'SupplierCategory' );
        $data['SupplierCategory'] = $this->SupplierCategory->find( 'all', array(
            'conditions' => array( 'SupplierCategory.supplier_id' => $this->request->data['supplier_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'category_name' ),
        ) );
        
        die( json_encode( $data ) );
    }
    
    /**
     * Auto-populate Completion date via ajax
     */
    public function calculate_complete_date() {
        $this->autoRender = FALSE;
    
        $this->loadModel( 'TrClass' );
        $data = $this->TrClass->find( 'first', array(
            'conditions' => array( 'TrClass.id' => $this->request->data['tr_class_id'] ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name', 'no_of_days' ),
        ) );
        $sla = !empty( $data['TrClass']['no_of_days'] ) ? ( 24 * $data['TrClass']['no_of_days'] ) : 0;
        $completeDate = date( 'Y-m-d H:i:s', strtotime( '+ ' . round( $sla, 0 ) . ' hours', strtotime( $this->request->data['received_at_supplier'] ) ) );

        die( json_encode( $completeDate ) );
    }
}