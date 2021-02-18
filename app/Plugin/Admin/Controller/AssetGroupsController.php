<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * AssetGroups Controller
 */
class AssetGroupsController extends AdminAppController {
    
    public $uses = array( 'Site', 'AssetGroup', 'TrClass' );
    
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
     * Asset Group List
     */
    public function index() {
        $this->set( 'title_for_layout', 'Asset Group List' );
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
                if(!empty($this->request->data['AssetGroup']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['AssetGroup']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['AssetGroup']['file_name']['tmp_name'], WWW_ROOT.'files/asset_group/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/asset_group/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Site" defaultstate="collapsed">
                                $siteCheck = $this->Site->find('first', array(
                                        'conditions'=>array(
                                            'site_name'=>strtoupper($excel->sheets[0]['cells'][$x][1])
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

                                //<editor-fold desc="Check Asset Group" defaultstate="collapsed">
                                $aGroupCheck = $this->AssetGroup->find('first', array(
                                        'conditions'=>array(
                                            'site_id'          => $site_id,
                                            'asset_group_name' => ltrim(trim($excel->sheets[0]['cells'][$x][2]), '0')
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'AssetGroup.id, AssetGroup.asset_group_name'
                                    )
                                );
                                if(!empty($aGroupCheck)): // Excel Value is Available in Database.
                                    $aGroupStatus = 0;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $aGroupStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('AssetGroup' => array(
                                    'site_id'      => $site_id,
                                    'site_name'    => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'siteStatus'   => $siteStatus,
                                    'aGroup_name'  => ltrim(trim($excel->sheets[0]['cells'][$x][2]), '0'),
                                    'aGroupStatus' => $aGroupStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/asset_group/'.$fileName);
                            $this->set('aGroupBulkData', $excelData);
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
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                if(($tableData[$i]['site_status'] == 1) && ($tableData[$i]['aGroup_status'] == 1)){
                    $aGroupCheck = $this->AssetGroup->find('first', array(
                            'conditions'=>array(
                                'site_id'          => $tableData[$i]['site_id'],
                                'asset_group_name' => strtoupper(trim($tableData[$i]['aGroup_name']))
                            ),
                            'contain'=>FALSE,
                            'fields' => 'AssetGroup.id, AssetGroup.asset_group_name'
                        )
                    );

                    if(empty($aGroupCheck)): // Asset Group Value is "NOT" Available in Database.
                        $saveData = array('AssetGroup' => array(
                            'site_id'          => strtoupper(trim($tableData[$i]['site_id'])),
                            'asset_group_name' => strtoupper(trim($tableData[$i]['aGroup_name'])),
                            'created_by'       => 1,
                        ) );
                        $this->AssetGroup->create();
                        $this->AssetGroup->save($saveData);

                        $insert_one++;
                        $assetGroupId = $this->AssetGroup->id;

                        $firstAssetGroup = $this->AssetGroup->find('first', array(
                            'conditions' => array(
                                'AssetGroup.asset_group_name' => $saveData['AssetGroup']['asset_group_name']
                            ),
                            'contain'=> FALSE
                        ));

                        if(!empty($firstAssetGroup)){
                            $trClasses = $this->TrClass->find( 'all', array(
                                'conditions' => array(
                                    'TrClass.asset_group_id' => $firstAssetGroup['AssetGroup']['id']
                                ),
                                'contain' => FALSE)
                            );

                            if(!empty($trClasses)){
                                foreach($trClasses as $class){
                                    $this->TrClass->create();
                                    $this->TrClass->save( array('TrClass' => array(
                                        'asset_group_id' => $assetGroupId,
                                        'tr_class_name'  => $class['TrClass']['tr_class_name'],
                                        'tr_class_desc'  => $class['TrClass']['tr_class_desc'],
                                        'no_of_days'     => $class['TrClass']['no_of_days'],
                                    ) ) );
                                }
                            }
                        }
                    endif;
                }
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Asset Group List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group activate/deactivate/delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->AssetGroup->updateAll( array( $field => $value ), array( 'AssetGroup.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' asset group.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' asset group.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $ag = $this->AssetGroup->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'AssetGroup.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $ag ) ) {
                $deleteResult = $this->AssetGroup->updateAll( array( 'is_deleted' => YES ), array( 'AssetGroup.id' => $ag['AssetGroup']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The asset group has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Asset Group ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $columns = array(
            1 => array( 'model' => 'Site.site_name', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'ag_name', 'search' => 'like' ),
            3 => array( 'model' => 'AssetGroup.status', 'field' => 'status', 'search' => 'equal' ),
        );
        
        $conditions = array();
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
        
        $order = array( 'AssetGroup.id' => 'DESC' );
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array( $column => $direction );
        }
        //</editor-fold>
        
        $total = $this->AssetGroup->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'Site' ) ) );
        $data = $this->AssetGroup->find( 'all', array(
            'contain'    => array( 'Site' ),
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
     * Add/edit an asset group
     *
     * @param integer $assetGroupId
     *
     * @throws NotFoundException
     */
    public function add( $assetGroupId = NULL ) {
        if( !empty( $assetGroupId ) ) {
            $data = $this->AssetGroup->find( 'first', array( 'contain' => 'Site', 'conditions' => array( 'AssetGroup.id' => $assetGroupId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Asset Group ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->AssetGroup->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->AssetGroup->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                //<editor-fold desc="If the AssetGroup is new for this Site, duplicate TrClass as per the first AssetGroup" defaultstate="collapsed">
                if( empty( $this->request->data['AssetGroup']['id'] ) ) {
                    $firstAssetGroup = $this->AssetGroup->find( 'first', array(
                        'conditions' => array( 'AssetGroup.asset_group_name' => $this->request->data['AssetGroup']['asset_group_name'] ),
                        'contain'    => FALSE,
                    ) );
                    if( !empty( $firstAssetGroup ) ) {
                        $trClasses = $this->TrClass->find( 'all', array(
                            'conditions' => array( 'TrClass.asset_group_id' => $firstAssetGroup['AssetGroup']['id'], 'TrClass.status' => ACTIVE ),
                            'contain'    => FALSE,
                        ) );
                        if( !empty( $trClasses ) ) {
                            foreach( $trClasses as $class ) {
                                $this->TrClass->create();
                                $this->TrClass->save( array( 'TrClass' => array(
                                    'asset_group_id' => $this->AssetGroup->id,
                                    'tr_class_name'  => $class['TrClass']['tr_class_name'],
                                    'tr_class_desc'  => $class['TrClass']['tr_class_desc'],
                                    'no_of_days'     => $class['TrClass']['no_of_days'],
                                ) ) );
                            }
                        }
                    }
                }
                //</editor-fold>
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Asset Group saved successfully.', 'id' => $this->AssetGroup->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Asset Group saved successfully.' ), 'messages/success' );
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
            'subCenterList'    => $this->WarrantyLookup->getSubCenterList(),
            'siteList'         => $this->WarrantyLookup->getSiteList( NULL ),
            'title_for_layout' => 'Asset Group ' . ( empty( $assetGroupId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a asset group details
     *
     * @param integer $assetGroupId
     *
     * @throws NotFoundException
     */
    public function view( $assetGroupId = NULL ) {
        $data = $this->AssetGroup->find( 'first', array( 'contain' => 'Site', 'conditions' => array( 'AssetGroup.id' => $assetGroupId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Asset Group ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Asset Group Details' );
    }
    
    /**
     * Ajax call for asset group
     */
    public function assetGroupList() {
        $this->autoRender = FALSE;
        
        if( $this->request->is( 'ajax' ) ) {
            $site_id = $this->request->data['site_id'];
            $scData = $this->AssetGroup->find( 'all', array( 'contain' => FALSE, 'conditions' => array( 'AssetGroup.site_id' => $site_id ), 'order' => array( 'AssetGroup.id' => 'ASC' ) ) );
            if( !empty( $scData ) ) {
                die( json_encode( $scData ) );
            }
            else {
                die( json_encode( array() ) );
            }
        }
    }
}