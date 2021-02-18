<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * SubCenters Controller
 */
class SubCentersController extends AdminAppController {
    
    public $uses = array( 'SubCenter', 'SubCenterBudget', 'Region' );
    
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
     * Office List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Office List' );
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
    public function bulk_office_import(){
        if($this->request->is(array('post', 'put'))){
            try{
                if(!empty($this->request->data['SubCenter']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['SubCenter']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['SubCenter']['file_name']['tmp_name'], WWW_ROOT.'files/sub_center/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/sub_center/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Region" defaultstate="collapsed">
                                $regionCheck = $this->Region->find('first', array(
                                        'conditions'=>array(
                                            'region_name'=>strtoupper($excel->sheets[0]['cells'][$x][1])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Region.id, Region.region_name'
                                    )
                                );
                                if(!empty($regionCheck)): // Excel Value is Available in Database.
                                    $region_id = $regionCheck['Region']['id'];
                                    $regionStatus = 1;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $region_id = null;
                                    $regionStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Office" defaultstate="collapsed">
                                $officeCheck = $this->SubCenter->find('first', array(
                                        'conditions'=>array(
                                            'sub_center_name'=>strtoupper($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'SubCenter.id, SubCenter.sub_center_name'
                                    )
                                );
                                if(!empty($officeCheck)): // Excel Value is Available in Database.
                                    $officeStatus = 0;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $officeStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('SubCenter' => array(
                                    'region_id'    => $region_id,
                                    'region_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'regionStatus' => $regionStatus,
                                    'office_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'officeStatus' => $officeStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/sub_center/'.$fileName);
                            $this->set('officeBulkData', $excelData);
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
    public function bulk_office_import_post(){
        $this->autoRender = false;

        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData,TRUE);

        $select_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                if(($tableData[$i]['region_status'] == 1) && ($tableData[$i]['office_status'] == 1)){
                    $officeCheck = $this->SubCenter->find('first', array(
                            'conditions'=>array(
                                'sub_center_name' => strtoupper(trim($tableData[$i]['office_name']))
                            ),
                            'contain'=>FALSE,
                            'fields' => 'SubCenter.id, SubCenter.sub_center_name')
                    );

                    if(empty($officeCheck)): // Office Value is "NOT" Available in Database.
                        $saveData = array('SubCenter' => array(
                            'region_id'       => strtoupper(trim($tableData[$i]['region_id'])),
                            'sub_center_name' => strtoupper(trim($tableData[$i]['office_name'])),
                            'created_by'      => 1,
                        ) );
                        $this->SubCenter->create();
                        $this->SubCenter->save($saveData);
                        $select_one++;
                    endif;
                }
            }
        }
        $this->Session->setFlash($select_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
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
    public function bulk_budget_import(){
        if($this->request->is(array('post', 'put'))){
            try{
                if(!empty($this->request->data['SubCenter']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['SubCenter']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['SubCenter']['file_name']['tmp_name'], WWW_ROOT.'files/sub_center/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/sub_center/'.$fileName);

                            $x = 3;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Region" defaultstate="collapsed">
                                $regionCheck = $this->Region->find('first', array(
                                        'conditions'=>array(
                                            'region_name'=>strtoupper($excel->sheets[0]['cells'][$x][1])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Region.id, Region.region_name'
                                    )
                                );
                                if(!empty($regionCheck)): // Excel Value is Available in Database.
                                    $region_id = $regionCheck['Region']['id'];
                                    $regionStatus = 1;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $region_id = null;
                                    $regionStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Office" defaultstate="collapsed">
                                $officeCheck = $this->SubCenter->find('first', array(
                                        'conditions'=>array(
                                            'sub_center_name'=>strtoupper($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'SubCenter.id, SubCenter.sub_center_name'
                                    )
                                );
                                if(!empty($officeCheck)): // Excel Value is Available in Database.
                                    $office_id = $officeCheck['SubCenter']['id'];
                                    $officeStatus = 1;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $office_id = null;
                                    $officeStatus = 0;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('SubCenter' => array(
                                    'region_id'              => $region_id,
                                    'region_name'            => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'regionStatus'           => $regionStatus,
                                    'office_id'              => $office_id,
                                    'office_name'            => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'officeStatus'           => $officeStatus,
                                    'eighty_percent_action'  => $excel->sheets[0]['cells'][ $x ][3],
                                    'ninety_percent_action'  => $excel->sheets[0]['cells'][ $x ][4],
                                    'hundred_percent_action' => $excel->sheets[0]['cells'][ $x ][5],
                                    'AC_budget'              => $excel->sheets[0]['cells'][ $x ][6],
                                    'AC_min_budget'          => $excel->sheets[0]['cells'][ $x ][7],
                                    'CW_budget'              => $excel->sheets[0]['cells'][ $x ][8],
                                    'CW_min_budget'          => $excel->sheets[0]['cells'][ $x ][9],
                                    'DV_budget'              => $excel->sheets[0]['cells'][ $x ][10],
                                    'DV_min_budget'          => $excel->sheets[0]['cells'][ $x ][11],
                                    'EB_budget'              => $excel->sheets[0]['cells'][ $x ][12],
                                    'EB_min_budget'          => $excel->sheets[0]['cells'][ $x ][13],
                                    'FM_budget'              => $excel->sheets[0]['cells'][ $x ][14],
                                    'FM_min_budget'          => $excel->sheets[0]['cells'][ $x ][15],
                                    'GN_budget'              => $excel->sheets[0]['cells'][ $x ][16],
                                    'GN_min_budget'          => $excel->sheets[0]['cells'][ $x ][17],
                                    'PG_budget'              => $excel->sheets[0]['cells'][ $x ][18],
                                    'PG_min_budget'          => $excel->sheets[0]['cells'][ $x ][19],
                                    'RF_budget'              => $excel->sheets[0]['cells'][ $x ][20],
                                    'RF_min_budget'          => $excel->sheets[0]['cells'][ $x ][21],
                                    'SS_budget'              => $excel->sheets[0]['cells'][ $x ][22],
                                    'SS_min_budget'          => $excel->sheets[0]['cells'][ $x ][23],
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/sub_center/'.$fileName);
                            $this->set('budgetBulkData', $excelData);
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
     * @throws Exception "Office Budget not saving successfully."
     *
     * @return void
     **/
    public function bulk_budget_import_post(){
        $this->autoRender = false;

        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData,TRUE);

        $insert_one = 0;
        $update_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                if($tableData[$i]['region_status'] == 1){

                    //<editor-fold desc="Insert or Update SubCenter" defaultstate="collapsed">
                    $saveData = array( 'SubCenter' => array(
                        'region_id'              => $tableData[$i]['region_id'],
                        'sub_center_name'        => strtoupper(trim($tableData[$i]['office_name'])),
                        'AC_budget'              => $tableData[$i]['AC_budget'],
                        'AC_min_budget'          => $tableData[$i]['AC_min_budget'],
                        'CW_budget'              => $tableData[$i]['CW_budget'],
                        'CW_min_budget'          => $tableData[$i]['CW_min_budget'],
                        'DV_budget'              => $tableData[$i]['DV_budget'],
                        'DV_min_budget'          => $tableData[$i]['DV_min_budget'],
                        'EB_budget'              => $tableData[$i]['EB_budget'],
                        'EB_min_budget'          => $tableData[$i]['EB_min_budget'],
                        'FM_budget'              => $tableData[$i]['FM_budget'],
                        'FM_min_budget'          => $tableData[$i]['FM_min_budget'],
                        'GN_budget'              => $tableData[$i]['GN_budget'],
                        'GN_min_budget'          => $tableData[$i]['GN_min_budget'],
                        'PG_budget'              => $tableData[$i]['PG_budget'],
                        'PG_min_budget'          => $tableData[$i]['PG_min_budget'],
                        'RF_budget'              => $tableData[$i]['RF_budget'],
                        'RF_min_budget'          => $tableData[$i]['RF_min_budget'],
                        'SS_budget'              => $tableData[$i]['SS_budget'],
                        'SS_min_budget'          => $tableData[$i]['SS_min_budget'],
                        'eighty_percent_action'  => strtoupper(trim($tableData[$i]['eighty_percent'])) == 'BLOCK TR' ? 1 : 0,
                        'ninety_percent_action'  => strtoupper(trim($tableData[$i]['ninety_percent'])) == 'BLOCK TR' ? 1 : 0,
                        'hundred_percent_action' => strtoupper(trim($tableData[$i]['hundred_percent'])) == 'BLOCK TR' ? 1 : 0,
                        'created_by'             => 1,
                    ));

                    if(!is_null($tableData[$i]['office_id']) && ($tableData[$i]['office_id'] != '')){
                        $saveData['SubCenter']['id'] = $tableData[$i]['office_id'];
                        $update_one++;
                    }else{
                        $insert_one++;
                    }
                    $this->SubCenter->create();
                    $this->SubCenter->save($saveData);
                    //</editor-fold>

                    //<editor-fold desc="Insert or Update SubCenterBudget" defaultstate="collapsed">
                    $saveBudgetData = array( 'SubCenterBudget' => array(
                        'sub_center_id'     => $this->SubCenter->id,
                        'year'              => date('Y'),
                        'month'             => date('m'),
                        'AC_initial_budget' => $saveData['SubCenter']['AC_budget'],
                        'CW_initial_budget' => $saveData['SubCenter']['CW_budget'],
                        'DV_initial_budget' => $saveData['SubCenter']['DV_budget'],
                        'EB_initial_budget' => $saveData['SubCenter']['EB_budget'],
                        'FM_initial_budget' => $saveData['SubCenter']['FM_budget'],
                        'GN_initial_budget' => $saveData['SubCenter']['GN_budget'],
                        'PG_initial_budget' => $saveData['SubCenter']['PG_budget'],
                        'RF_initial_budget' => $saveData['SubCenter']['RF_budget'],
                        'SS_initial_budget' => $saveData['SubCenter']['SS_budget'],
                    ));

                    if(!is_null($tableData[$i]['office_id']) && ($tableData[$i]['office_id'] != '')){
                        $checkBudget = $this->SubCenterBudget->find( 'first', array(
                            'conditions' => array(
                                'sub_center_id' => $this->SubCenter->id,
                                'year' => date('Y'),
                                'month' => date('m')
                            ))
                        );
                        if(!empty($checkBudget)){
                            $saveBudgetData['SubCenterBudget']['id'] = $checkBudget['SubCenterBudget']['id'];
                            $saveBudgetData['SubCenterBudget']['AC_initial_budget'] = $checkBudget['SubCenterBudget']['AC_initial_budget'] + $saveBudgetData['SubCenterBudget']['AC_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['CW_initial_budget'] = $checkBudget['SubCenterBudget']['CW_initial_budget'] + $saveBudgetData['SubCenterBudget']['CW_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['DV_initial_budget'] = $checkBudget['SubCenterBudget']['DV_initial_budget'] + $saveBudgetData['SubCenterBudget']['DV_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['EB_initial_budget'] = $checkBudget['SubCenterBudget']['EB_initial_budget'] + $saveBudgetData['SubCenterBudget']['EB_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['FM_initial_budget'] = $checkBudget['SubCenterBudget']['FM_initial_budget'] + $saveBudgetData['SubCenterBudget']['FM_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['GN_initial_budget'] = $checkBudget['SubCenterBudget']['GN_initial_budget'] + $saveBudgetData['SubCenterBudget']['GN_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['PG_initial_budget'] = $checkBudget['SubCenterBudget']['PG_initial_budget'] + $saveBudgetData['SubCenterBudget']['PG_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['RF_initial_budget'] = $checkBudget['SubCenterBudget']['RF_initial_budget'] + $saveBudgetData['SubCenterBudget']['RF_initial_budget'];
                            $saveBudgetData['SubCenterBudget']['SS_initial_budget'] = $checkBudget['SubCenterBudget']['SS_initial_budget'] + $saveBudgetData['SubCenterBudget']['SS_initial_budget'];
                        }
                    }

                    $this->SubCenterBudget->create();
                    if(!$this->SubCenterBudget->save($saveBudgetData)){
                        $errors = '';
                        foreach( $this->SubCenterBudget->validationErrors as $field => $error ) {
                            $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                        }
                        throw new Exception($errors);
                    }
                    //</editor-fold>
                }
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Office List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->SubCenter->updateAll( array( $field => $value ), array( 'SubCenter.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' Office.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' Office.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $subc = $this->SubCenter->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'SubCenter.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $subc ) ) {
                $deleteResult = $this->SubCenter->updateAll( array( 'is_deleted' => YES ), array( 'SubCenter.id' => $subc['SubCenter']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The Office has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Office ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'SubCenter.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Region.region_name', 'field' => 'r_name', 'search' => 'like' ),
            2 => array( 'model' => 'SubCenter.sub_center_name', 'field' => 'subc_name', 'search' => 'like' ),
            3 => array( 'model' => 'SubCenter.eighty_percent_action', 'field' => 'eighty_percent_action', 'search' => 'equal' ),
            4 => array( 'model' => 'SubCenter.ninety_percent_action', 'field' => 'ninety_percent_action', 'search' => 'equal' ),
            5 => array( 'model' => 'SubCenter.hundred_percent_action', 'field' => 'hundred_percent_action', 'search' => 'equal' ),
            6 => array( 'model' => 'SubCenter.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->SubCenter->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'Region' ) ) );
        $data = $this->SubCenter->find( 'all', array(
            'contain'    => array( 'Region' ),
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
     * Add/edit a Office
     *
     * @param integer $subcId
     *
     * @throws NotFoundException
     */
    public function add( $subcId = NULL ) {
        if( !empty( $subcId ) ) {
            $data = $this->SubCenter->find( 'first', array( 'contain' => 'Region', 'conditions' => array( 'SubCenter.id' => $subcId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Office ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->SubCenter->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->SubCenter->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                //<editor-fold desc="Insert/Update Office budget for current month" defaultstate="collapsed">
                $budgetData = array( 'SubCenterBudget' => array(
                    'sub_center_id'     => $this->SubCenter->id,
                    'year'              => date( 'Y' ),
                    'month'             => date( 'm' ),
                    'AC_initial_budget' => $this->request->data['SubCenter']['AC_budget'],
                    'CW_initial_budget' => $this->request->data['SubCenter']['CW_budget'],
                    'DV_initial_budget' => $this->request->data['SubCenter']['DV_budget'],
                    'EB_initial_budget' => $this->request->data['SubCenter']['EB_budget'],
                    'FM_initial_budget' => $this->request->data['SubCenter']['FM_budget'],
                    'GN_initial_budget' => $this->request->data['SubCenter']['GN_budget'],
                    'PG_initial_budget' => $this->request->data['SubCenter']['PG_budget'],
                    'RF_initial_budget' => $this->request->data['SubCenter']['RF_budget'],
                    'SS_initial_budget' => $this->request->data['SubCenter']['SS_budget'],
                ) );
                $budget = $this->SubCenterBudget->find( 'first', array( 'conditions' => array( 'SubCenterBudget.sub_center_id' => $this->SubCenter->id, 'SubCenterBudget.year' => date( 'Y' ), 'SubCenterBudget.month' => date( 'n' ) ) ) );
                if( !empty( $budget ) ) {
                    $budgetData['SubCenterBudget']['id'] = $budget['SubCenterBudget']['id'];
                }
                if( !$this->SubCenterBudget->save( $budgetData ) ) {
                    $errors = '';
                    foreach( $this->SubCenterBudget->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Office saved successfully.', 'id' => $this->SubCenter->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Office saved successfully.' ), 'messages/success' );
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
            'regionList'       => $this->WarrantyLookup->getRegionList(),
            'title_for_layout' => 'Office ' . ( empty( $subcId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a Office details
     *
     * @param integer $subcId
     *
     * @throws NotFoundException
     */
    public function view( $subcId = NULL ) {
        $data = $this->SubCenter->find( 'first', array( 'contain' => 'Region', 'conditions' => array( 'SubCenter.id' => $subcId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Office ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Office Details' );
    }
}