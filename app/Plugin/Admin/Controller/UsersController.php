<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Users Controller
 *
 * @property WarrantyLookupComponent WarrantyLookup
 */
class UsersController extends AdminAppController {
    
    public $uses = array( 'User' );
    
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
     * Region List
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return array
     */
    private function regionList() {
        App::import( 'Model', 'Region' );
        $objRegion = new Region();
        $regionObj = $objRegion->find('list', array(
            'contain'    => FALSE,
            'order'      => array( 'Region.id' => 'ASC' ),
            'fields'     => array( 'Region.id', 'Region.region_name' ),
        ) );
        $regionList = array();
        foreach( $regionObj as $id => $name ) {
            $regionList[] = array('name' => $name, 'value' => $name, 'data-id' => $id);
        }
        return $regionList;
    }

    /**
     * Years List
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return array
     */
    private function yearList() {
        $years = range(date('Y'), 2001);
        $yearList = array();
        foreach( $years as $id => $name ) {
            $yearList[] = array('name' => $name, 'value' => $name, 'data-id' => $name);
        }
        return $yearList;
    }

    /**
     * Month List
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return array
     */
    private function monthList() {
        $monthList = array();
        for($i = 1 ; $i <= 12; $i++){
            $monthList[] = array('name' => date("F",mktime(0,0,0,$i,1,date("Y"))), 'value' => $i, 'data-id' => $i);
        }
        return $monthList;
    }
    
    /**
     * Company Admin Dashboard
     */
    public function dashboard() {
        $regionList = $this->regionList();
        $yearList   = $this->yearList();
        $monthList  = $this->monthList();


        $this->loadModel( 'Region' );
        $regionName = $this->Region->find( 'first', array(
            'contain' =>FALSE,
            'fields'  => 'Region.id, Region.region_name',
            'order'   => 'Region.id ASC'
        ) );

        $this->set( array(
            'regionList'       => $regionList,
            'regionDefault'    => $regionName['Region']['region_name'],
            'yearList'         => $yearList,
            'monthList'        => $monthList,
            'title_for_layout' => 'Admin Dashboard'
        ));
    }

    /**
     * Budget vs Expense Report
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     */
    public function dashboard_budget_data() {
        $this->autoRender = FALSE;
        $region = $this->request->data['region'];
        $year   = $this->request->data['year'];
        $month  = $this->request->data['month'];

        if($region == 0){
            $region_condition = '1 = 1';
        }else{
            $region_condition = 'Region.id = '.$region;
        }
        $this->loadModel( 'Region' );
        $regionName = $this->Region->find( 'first', array(
            'conditions' => array(
                $region_condition
            ),
            'contain' =>FALSE,
            'fields'  => 'Region.id, Region.region_name',
            'order'   => 'Region.id ASC'
        ) );

        $this->loadModel( 'SubCenter' );
        $this->loadModel( 'SubCenterBudget' );
        $subCenterBudget = $this->SubCenterBudget->find('all', array(
            'conditions' => array(
                'SubCenter.region_id' => $regionName['Region']['id'],
                'SubCenterBudget.sub_center_id = SubCenter.id',
                'SubCenterBudget.year' => $year,
                'SubCenterBudget.month' => $month
            ),
            'contain' => array('SubCenter'),
            'fields' => array(
                'SubCenterBudget.*','SubCenter.*'
            )
        ));
        //dd($subCenterBudget->sql());

        $AC_budget  = 0;
        $AC_expense = 0;

        $CW_budget  = 0;
        $CW_expense = 0;

        $DV_budget  = 0;
        $DV_expense = 0;

        $EB_budget  = 0;
        $EB_expense = 0;

        $FM_budget  = 0;
        $FM_expense = 0;

        $GN_budget  = 0;
        $GN_expense = 0;

        $PG_budget  = 0;
        $PG_expense = 0;

        $RF_budget  = 0;
        $RF_expense = 0;

        $SS_budget  = 0;
        $SS_expense = 0;

        $chartData = array(array($regionName['Region']['region_name'], 'Budget', 'Expense'));
        foreach($subCenterBudget as $row){
            $AC_budget  += $row['SubCenterBudget']['AC_initial_budget'] + $row['SubCenterBudget']['AC_forwarded_budget'];
            $AC_expense += $row['SubCenterBudget']['AC_consumed_budget'] + $row['SubCenter']['AC_min_budget'];

            $CW_budget  += $row['SubCenterBudget']['CW_initial_budget'] + $row['SubCenterBudget']['CW_forwarded_budget'];
            $CW_expense += $row['SubCenterBudget']['CW_consumed_budget'] + $row['SubCenter']['CW_min_budget'];

            $DV_budget  += $row['SubCenterBudget']['DV_initial_budget'] + $row['SubCenterBudget']['DV_forwarded_budget'];
            $DV_expense += $row['SubCenterBudget']['DV_consumed_budget'] + $row['SubCenter']['DV_min_budget'];

            $EB_budget  += $row['SubCenterBudget']['EB_initial_budget'] + $row['SubCenterBudget']['EB_forwarded_budget'];
            $EB_expense += $row['SubCenterBudget']['EB_consumed_budget'] + $row['SubCenter']['EB_min_budget'];

            $FM_budget  += $row['SubCenterBudget']['FM_initial_budget'] + $row['SubCenterBudget']['FM_forwarded_budget'];
            $FM_expense += $row['SubCenterBudget']['FM_consumed_budget'] + $row['SubCenter']['FM_min_budget'];

            $GN_budget  += $row['SubCenterBudget']['GN_initial_budget'] + $row['SubCenterBudget']['GN_forwarded_budget'];
            $GN_expense += $row['SubCenterBudget']['GN_consumed_budget'] + $row['SubCenter']['GN_min_budget'];

            $PG_budget  += $row['SubCenterBudget']['PG_initial_budget'] + $row['SubCenterBudget']['PG_forwarded_budget'];
            $PG_expense += $row['SubCenterBudget']['PG_consumed_budget'] + $row['SubCenter']['PG_min_budget'];

            $RF_budget  += $row['SubCenterBudget']['RF_initial_budget'] + $row['SubCenterBudget']['RF_forwarded_budget'];
            $RF_expense += $row['SubCenterBudget']['RF_consumed_budget'] + $row['SubCenter']['RF_min_budget'];

            $SS_budget  += $row['SubCenterBudget']['SS_initial_budget'] + $row['SubCenterBudget']['SS_forwarded_budget'];
            $SS_expense += $row['SubCenterBudget']['SS_consumed_budget'] + $row['SubCenter']['SS_min_budget'];
        }
        $chartData[] = array('AC', $AC_budget, $AC_expense);
        $chartData[] = array('CW', $CW_budget, $CW_expense);
        $chartData[] = array('DV', $DV_budget, $DV_expense);
        $chartData[] = array('EB', $EB_budget, $EB_expense);
        $chartData[] = array('FM', $FM_budget, $FM_expense);
        $chartData[] = array('GN', $GN_budget, $GN_expense);
        $chartData[] = array('PG', $PG_budget, $PG_expense);
        $chartData[] = array('RF', $RF_budget, $RF_expense);
        $chartData[] = array('SS', $SS_budget, $SS_expense);

        die(json_encode($chartData));
    }

    /**
     * Region Expense Report
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     */
    public function dashboard_expense_data() {
        $this->autoRender = FALSE;
        $region = $this->request->data['region'];
        $year   = $this->request->data['year'];
        $month  = $this->request->data['month'];

        if($region == 0){
            $region_condition = '1 = 1';
        }else{
            $region_condition = 'Region.id = '.$region;
        }
        $this->loadModel( 'Region' );
        $regionName = $this->Region->find( 'first', array(
            'conditions' => array(
                $region_condition
            ),
            'contain' =>FALSE,
            'fields'  => 'Region.id, Region.region_name',
            'order'   => 'Region.id ASC'
        ) );

        $this->loadModel( 'SubCenter' );
        $this->loadModel( 'SubCenterBudget' );
        $subCenterBudget = $this->SubCenterBudget->find('all', array(
            'conditions' => array(
                'SubCenter.region_id' => $regionName['Region']['id'],
                'SubCenterBudget.sub_center_id = SubCenter.id',
                'SubCenterBudget.year' => $year,
                'SubCenterBudget.month' => $month
            ),
            'contain' => array('SubCenter'),
            'fields' => array(
                'SubCenterBudget.*','SubCenter.*'
            )
        ));
        //dd($subCenterBudget->sql());

        $AC_expense = 0;
        $CW_expense = 0;
        $DV_expense = 0;
        $EB_expense = 0;
        $FM_expense = 0;
        $GN_expense = 0;
        $PG_expense = 0;
        $RF_expense = 0;
        $SS_expense = 0;

        $chartData = array(array($regionName['Region']['region_name'], 'Expense'));
        foreach($subCenterBudget as $row){

            $AC_expense += $row['SubCenterBudget']['AC_consumed_budget'] + $row['SubCenter']['AC_min_budget'];
            $CW_expense += $row['SubCenterBudget']['CW_consumed_budget'] + $row['SubCenter']['CW_min_budget'];
            $DV_expense += $row['SubCenterBudget']['DV_consumed_budget'] + $row['SubCenter']['DV_min_budget'];
            $EB_expense += $row['SubCenterBudget']['EB_consumed_budget'] + $row['SubCenter']['EB_min_budget'];
            $FM_expense += $row['SubCenterBudget']['FM_consumed_budget'] + $row['SubCenter']['FM_min_budget'];
            $GN_expense += $row['SubCenterBudget']['GN_consumed_budget'] + $row['SubCenter']['GN_min_budget'];
            $PG_expense += $row['SubCenterBudget']['PG_consumed_budget'] + $row['SubCenter']['PG_min_budget'];
            $RF_expense += $row['SubCenterBudget']['RF_consumed_budget'] + $row['SubCenter']['RF_min_budget'];
            $SS_expense += $row['SubCenterBudget']['SS_consumed_budget'] + $row['SubCenter']['SS_min_budget'];
        }
        $chartData[] = array('AC', $AC_expense);
        $chartData[] = array('CW', $CW_expense);
        $chartData[] = array('DV', $DV_expense);
        $chartData[] = array('EB', $EB_expense);
        $chartData[] = array('FM', $FM_expense);
        $chartData[] = array('GN', $GN_expense);
        $chartData[] = array('PG', $PG_expense);
        $chartData[] = array('RF', $RF_expense);
        $chartData[] = array('SS', $SS_expense);

        die(json_encode($chartData));
    }

    /**
     * Region & SLA wise Ticket Report
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @return void
     */
    public function dashboard_ticket_data() {
        $this->autoRender = FALSE;
        $region = $this->request->data['region'];
        $year   = $this->request->data['year'];
        $month  = $this->request->data['month'];

        if($region == 0){
            $region_condition = '1 = 1';
        }else{
            $region_condition = 'Region.id = '.$region;
        }
        $this->loadModel( 'Region' );
        $regionName = $this->Region->find( 'first', array(
            'conditions' => array(
                $region_condition
            ),
            'contain' =>FALSE,
            'fields'  => 'Region.id, Region.region_name',
            'order'   => 'Region.id ASC'
        ) );

        $this->loadModel( 'Ticket' );
        $this->loadModel( 'SubCenter' );
        $this->loadModel( 'Site' );
        $this->loadModel( 'AssetGroup' );
        $this->loadModel( 'TrClass' );

        $tickets = $this->Ticket->find('all', array(
            'contain' =>FALSE,
            'joins' => array(
                array(
                    'table' => 'sub_centers',
                    'alias' => 'OfficeJoin',
                    'type' => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'OfficeJoin.sub_center_name = Ticket.sub_center'
                    )
                ),
                array(
                    'table' => 'sites',
                    'alias' => 'SiteJoin',
                    'type' => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'SiteJoin.sub_center_id = OfficeJoin.id',
                        'SiteJoin.site_name = Ticket.site'
                    )
                ),
                array(
                    'table' => 'asset_groups',
                    'alias' => 'AssetGroupJoin',
                    'type' => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'AssetGroupJoin.site_id = SiteJoin.id',
                        'AssetGroupJoin.asset_group_name = Ticket.asset_group'
                    )
                ),
                array(
                    'table' => 'tr_classes',
                    'alias' => 'TrClassJoin',
                    'type' => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'TrClassJoin.asset_group_id = AssetGroupJoin.id',
                        'TrClassJoin.tr_class_name = Ticket.tr_class'
                    )
                )
            ),
            'conditions' => array(
                'Ticket.region' => $regionName['Region']['region_name'],
                'YEAR(Ticket.created)' => $year,
                'MONTH(Ticket.created)' => $month
            ),
            'group' => '`TrClassJoin`.`no_of_days`',
            'fields' => array('TrClassJoin.no_of_days','COUNT(`Ticket`.`id`) as `total_ticket`'),
            'order' => '`TrClassJoin`.`no_of_days` ASC'
        ));
        //dd($tickets->sql());

        $chartData = array(array($regionName['Region']['region_name'], 'Total tickets'));
        foreach($tickets as $row){
            $chartData[] = array( 'SLA : '.$row['TrClassJoin']['no_of_days'], (int) $row[0]['total_ticket']);
        }

        die(json_encode($chartData));
    }
    
    /**
     * User List
     */
    public function index(){
        $this->set( 'title_for_layout', 'User List' );
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
                if(!empty($this->request->data['User']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['User']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['User']['file_name']['tmp_name'], WWW_ROOT.'files/user/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            $this->loadModel('Supplier');
                            $this->loadModel('Region');
                            $this->loadModel('SubCenter');

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/user/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){
                                if(!empty($excel->sheets[0]['cells'][$x][1])){

                                    //<editor-fold desc="Check User" defaultstate="collapsed">
                                    $userCheck = $this->User->find('first', array(
                                            'conditions'=>array(
                                                'email' => trim($excel->sheets[0]['cells'][$x][1])
                                            ),
                                            'contain'=>FALSE,
                                            'fields' => 'User.id'
                                        )
                                    );
                                    if(!empty($userCheck)): // Excel Value is Available in Database.
                                        $user_id = $userCheck['User']['id'];
                                        $userStatus = 0;
                                    else:                   // Excel Value is 'NOT' Available in Database.
                                        $user_id = NULL;
                                        $userStatus = 1;
                                    endif;
                                    //</editor-fold>

                                    //<editor-fold desc="Check Region" defaultstate="collapsed">
                                    $regionCheck = $this->Region->find('first', array(
                                            'conditions'=>array(
                                                'region_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][4])) ,
                                                'status'      => ACTIVE
                                            ),
                                            'contain'=>FALSE,
                                            'fields' => 'Region.id, Region.region_name'
                                        )
                                    );
                                    if(!empty($regionCheck)): // Excel Value is Available in Database.
                                        $region_id = $regionCheck['Region']['id'];
                                    else:                   // Excel Value is 'NOT' Available in Database.
                                        $region_id = 0;
                                    endif;
                                    //</editor-fold>

                                    //<editor-fold desc="Check Office" defaultstate="collapsed">
                                    $officeCheck = $this->SubCenter->find('first', array(
                                            'conditions'=>array(
                                                'sub_center_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][5])),
                                                'status'          => ACTIVE
                                            ),
                                            'contain'=>FALSE,
                                            'fields' => 'SubCenter.id, SubCenter.sub_center_name'
                                        )
                                    );
                                    if(!empty($officeCheck)): // Excel Value is Available in Database.
                                        $office_id = $officeCheck['SubCenter']['id'];
                                    else:                   // Excel Value is 'NOT' Available in Database.
                                        $office_id = 0;
                                    endif;
                                    //</editor-fold>

                                    //<editor-fold desc="Check Supplier" defaultstate="collapsed">
                                    $supplierCheck = $this->Supplier->find('first', array(
                                            'conditions'=>array(
                                                'name'   => strtoupper(trim($excel->sheets[0]['cells'][$x][6])),
                                                'status' => ACTIVE
                                            ),
                                            'contain'=>FALSE,
                                            'fields' => 'Supplier.id, Supplier.name'
                                        )
                                    );
                                    if(!empty($supplierCheck)): // Excel Value is Available in Database.
                                        $supplier_id = $supplierCheck['Supplier']['id'];
                                    else:                       // Excel Value is 'NOT' Available in Database.
                                        $supplier_id = 0;
                                    endif;
                                    //</editor-fold>

                                    $excelData[] = array('User' => array(
                                        'user_id'     => $user_id,
                                        'region_id'   => $region_id,
                                        'office_id'   => $office_id,
                                        'supplier_id' => $supplier_id,

                                        'region'   => strtoupper(trim($excel->sheets[0]['cells'][$x][4])),
                                        'office'   => strtoupper(trim($excel->sheets[0]['cells'][$x][5])),
                                        'supplier' => strtoupper(trim($excel->sheets[0]['cells'][$x][6])),

                                        'role'       => trim($excel->sheets[0]['cells'][$x][7]),
                                        'name'       => trim($excel->sheets[0]['cells'][$x][2]),
                                        'email'      => trim($excel->sheets[0]['cells'][$x][1]),
                                        'password'   => trim($excel->sheets[0]['cells'][$x][8]),
                                        'phone'      => trim($excel->sheets[0]['cells'][$x][8]),
                                        'status'     => trim($excel->sheets[0]['cells'][$x][9]),
                                        'department' => trim($excel->sheets[0]['cells'][$x][3]),
                                        'userStatus' => $userStatus,
                                    ));
                                    $x++;
                                }
                            }
                            unlink(WWW_ROOT.'files/user/'.$fileName);
                            $this->set('userBulkData', $excelData);
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

        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData,TRUE);

        $insert_one = 0;
        $update_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                $saveData = array( 'User' => array(
                    'role'          => $this->getRole(trim($tableData[$i]['usr_role'])),
                    'name'          => trim($tableData[$i]['usr_name']),
                    'email'         => trim($tableData[$i]['usr_email']),
                    'password'      => $this->Auth->password(trim($tableData[$i]['usr_phone'])),
                    'phone'         => trim($tableData[$i]['usr_phone']),
                    'status'        => !empty($tableData[$i]['usr_status']) ? (strtoupper(trim($tableData[$i]['usr_status'])) == 'ACTIVE' ? ACTIVE : INACTIVE ) : ACTIVE,
                    'region_id'     => trim($tableData[$i]['region_id']),
                    'sub_center_id' => trim($tableData[$i]['office_id']),
                    'department'    => trim($tableData[$i]['usr_dept']),
                    'supplier_id'   => trim($tableData[$i]['supplier_id'])
                ) );

                if(!is_null($tableData[$i]['user_id']) && ($tableData[$i]['user_id'] != '')){
                    $saveData['User']['id'] = $tableData[$i]['user_id'];
                    $update_one++;
                }else{
                    $insert_one++;
                }
                $this->User->create();
                $this->User->save($saveData);
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * User List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        /* group activate/deactivate/delete */
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->User->updateAll( array( $field => $value ), array( 'User.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' users.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' users.';
            }
        }
        
        /* delete */
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $user = $this->User->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'User.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $user ) ) {
                $deleteResult = $this->User->updateAll( array( 'is_deleted' => YES ), array( 'User.id' => $user['User']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The user has been deleted.';
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
                $result['customActionMessage'] = 'Invalid User ID: ' . $this->request->data['customActionName'];
            }
        }
        
        /* Settings: START */
        $conditions = array( 'User.role !=' => ADMIN );
        $order = array( 'User.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'User.name', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'User.phone', 'field' => 'phone', 'search' => 'like' ),
            3 => array( 'model' => 'User.email', 'field' => 'email', 'search' => 'like' ),
            4 => array( 'model' => 'User.role', 'field' => 'type', 'search' => 'equal' ),
            5 => array( 'model' => 'User.status', 'field' => 'status', 'search' => 'equal' ),
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
        /* Settings: END */
        
        $total = $this->User->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->User->find( 'all', array(
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
     * Add/edit a user
     *
     * @param integer $userId
     *
     * @throws NotFoundException
     */
    public function add( $userId = NULL ) {
        if( !empty( $userId ) ) {
            $data = $this->User->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'User.id' => $userId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid User ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $conditions = array( 'User.email' => $this->request->data['User']['email'] );
                if( !empty( $userId ) ) {
                    $conditions['User.id !='] = $userId;
                }
                $duplicate = $this->User->find( 'first', array( 'conditions' => $conditions, 'contain' => FALSE, 'noStatus' => TRUE ) );
                if( !empty( $duplicate ) ) {
                    throw new ForbiddenException( 'Duplicate email address.' );
                }
                
                if( empty( $this->request->data['User']['password'] ) ) {
                    unset( $this->request->data['User']['password'] );
                }
                else {
                    $this->request->data['User']['password'] = $this->Auth->password( $this->request->data['User']['password'] );
                }
                
                if( $this->request->data['User']['role'] != SUPPLIER ) {
                    $this->request->data['User']['supplier_id'] = 0;
                }
                
                if( in_array( $this->request->data['User']['role'], array( SUPPLIER, SECURITY, INVOICE_VALIDATOR ) ) ) {
                    unset( $this->request->data['User']['region_id'] );
                }
                if( in_array( $this->request->data['User']['role'], array( SUPPLIER, SECURITY, INVOICE_CREATOR, INVOICE_VALIDATOR ) ) ) {
                    unset( $this->request->data['User']['sub_center_id'] );
                }
                
                if( !$this->User->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->User->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'User saved successfully.', 'id' => $this->User->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'User saved successfully.' ), 'messages/success' );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }
            catch( Exception $e ) {
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => FALSE, 'message' => __( $e->getMessage() ) ) ) );
                }
                else {
                    $this->set( 'data', $this->request->data );
                    $this->Session->setFlash( __( $e->getMessage() ), 'messages/failed' );
                }
            }
        }
        
        $this->set( array(
            'title_for_layout' => 'User ' . ( empty( $userId ) ? 'Add' : 'Edit' ),
            'regionList'       => $this->WarrantyLookup->getRegionList(),
            'subCenterList'    => $this->WarrantyLookup->getSubCenterList(),
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
        ) );
    }
    
    /**
     * View a user details
     *
     * @param integer $userId
     *
     * @throws NotFoundException
     */
    public function view( $userId = NULL ) {
        $data = $this->User->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'User.id' => !empty( $userId ) ? $userId : $this->loginUser['User']['id'] ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid User ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'User Details' );
    }
    
    /**
     * User profile
     */
    public function profile() {
        $this->layout = 'profile';
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $this->request->data['User']['id'] = $this->loginUser['User']['id'];
                
                //<editor-fold defaultstate="collapsed" desc="upload photo">
                if( !empty( $this->request->data['User']['image'] ) && $this->request->data['User']['image']['size'] > 0 ) {
                    $Qimage = $this->Components->load( 'Qimage' );
                    $photo_name = $Qimage->copy( array( 'file' => $this->request->data['User']['image'], 'path' => WWW_ROOT . "resource/company_{$this->loginUser['User']['company_id']}/profile_photo/" ) );
                    if( !$photo_name ) {
                        throw new Exception( 'Failed to save photo. Please, try again.' );
                    }
                    if( !empty( $this->request->data['User']['image'] ) ) {
                        if( !empty( $this->loginUser['User']['image'] ) && file_exists( WWW_ROOT . "resource/company_{$this->loginUser['User']['company_id']}/profile_photo/{$this->loginUser['User']['image']}" ) ) {
                            if( !unlink( WWW_ROOT . "resource/company_{$this->loginUser['User']['company_id']}/profile_photo/{$this->loginUser['User']['image']}" ) ) {
                                throw new Exception( 'Failed to delete old photo. Please, try again.' );
                            }
                        }
                    }
                    $this->request->data['User']['image'] = $photo_name;
                }
                else {
                    unset( $this->request->data['User']['image'] );
                }
//</editor-fold>
                
                if( empty( $this->request->data['User']['password'] ) ) {
                    unset( $this->request->data['User']['password'] );
                }
                else {
                    $this->request->data['User']['password'] = $this->Auth->password( $this->request->data['User']['password'] );
                }
                
                if( !$this->User->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->User->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Profile updated successfully.', 'id' => $this->User->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Profile updated successfully.' ), 'messages/success' );
                    $this->redirect( array( 'action' => 'dashboard' ) );
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
            'data'             => $this->User->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'User.id' => $this->loginUser['User']['id'] ) ) ),
            'title_for_layout' => 'User Profile',
        ) );
    }
    
    /**
     * Get Role ID
     *
     * @param string $roleStr
     *
     * @return int
     */
    private function getRole( $roleStr ) {
        switch( $roleStr ) {
            case 'Admin':
                return ADMIN;
            case 'TR Creator':
                return TR_CREATOR;
            case 'TR Creator (SS)':
                return SECURITY;
            case 'TR Validator':
                return TR_VALIDATOR;
            case 'Invoice Creator':
                return INVOICE_CREATOR;
            case 'Invoice validator':
                return INVOICE_VALIDATOR;
            default:
                return SUPPLIER;
        }
    }
    
    /**
     * Get SubCenter list
     *
     * @param null|int $regionId
     */
    public function get_sub_center_list( $regionId = NULL ) {
        die( json_encode( $this->WarrantyLookup->getSubCenterList( $regionId ) ) );
    }
}