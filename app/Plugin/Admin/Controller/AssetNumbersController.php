<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * AssetNumbers Controller
 */
class AssetNumbersController extends AdminAppController {
    
    public $uses = array( 'SubCenter', 'Site', 'AssetGroup', 'AssetNumber' );
    
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
     * Asset Number List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Asset Number List' );
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
                if(!empty($this->request->data['AssetNumber']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['AssetNumber']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['AssetNumber']['file_name']['tmp_name'], WWW_ROOT.'files/asset_number/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/asset_number/'.$fileName);

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
                                    $aGroup_id = $aGroupCheck['AssetGroup']['id'];
                                    $aGroupStatus = 1;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $aGroup_id = NULL;
                                    $aGroupStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Asset Number" defaultstate="collapsed">
                                $aNumberCheck = $this->AssetNumber->find( 'first', array(
                                    'conditions' => array(
                                        'asset_group_id' => $aGroup_id,
                                        'asset_number'   => strtoupper(trim($excel->sheets[0]['cells'][$x][3])),
                                    ),
                                    'contain'    => FALSE,
                                    'fields' => 'AssetNumber.id, AssetNumber.asset_number'
                                ) );
                                if(!empty($aNumberCheck)): // Excel Value is Available in Database.
                                    $aNumber_id = $aNumberCheck['AssetNumber']['id'];
                                    $aNumberStatus = 0;
                                else:                      // Excel Value is 'NOT' Available in Database.
                                    $aNumber_id = NULL;
                                    $aNumberStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('AssetNumber' => array(
                                    'site_name'     => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'siteStatus'    => $siteStatus,
                                    'aGroup_id'     => $aGroup_id,
                                    'aGroup_name'   => ltrim(trim($excel->sheets[0]['cells'][$x][2]), '0'),
                                    'aGroupStatus'  => $aGroupStatus,
                                    'aNumber_id'    => $aNumber_id,
                                    'aNumber_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][3])),
                                    'aNumberStatus' => $aNumberStatus,
                                    'aNumberDesc'   => strtoupper(trim($excel->sheets[0]['cells'][$x][4]))
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/asset_number/'.$fileName);
                            $this->set('aNumberBulkData', $excelData);
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
                if(($tableData[$i]['site_status'] == 1) && ($tableData[$i]['aGroup_status'] == 1)){
                    $saveData = array( 'AssetNumber' => array(
                        'asset_group_id'    => $tableData[$i]['aGroup_id'],
                        'asset_number'      => trim($tableData[$i]['aNumber_name']),
                        'asset_number_desc' => strtoupper(trim($tableData[$i]['aNumber_desc'])),
                        'created_by'        => 1,
                    ));

                    if(!is_null($tableData[$i]['aNumber_id']) && ($tableData[$i]['aNumber_id'] != '')){
                        $saveData['AssetNumber']['id'] = $tableData[$i]['aNumber_id'];
                        $update_one++;
                    }else{
                        $insert_one++;
                    }
                    $this->AssetNumber->create();
                    $this->AssetNumber->save($saveData);
                }
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Asset Number List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->AssetNumber->updateAll( array( $field => $value ), array( 'AssetNumber.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' asset number.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' asset number.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $an = $this->AssetNumber->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'AssetNumber.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $an ) ) {
                $deleteResult = $this->AssetNumber->updateAll( array( 'is_deleted' => YES ), array( 'AssetNumber.id' => $an['AssetNumber']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The asset number has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Asset Number ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'AssetNumber.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            2 => array( 'model' => 'AssetGroup.asset_group_name', 'field' => 'asset_group_name', 'search' => 'like' ),
            3 => array( 'model' => 'AssetNumber.asset_number', 'field' => 'asset_number', 'search' => 'like' ),
            4 => array( 'model' => 'AssetNumber.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->AssetNumber->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'AssetGroup' => array( 'Site' ) ) ) );
        $data = $this->AssetNumber->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array( 'AssetGroup' => array( 'Site' ) ),
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
     * Add/edit a asset number
     *
     * @param integer $anId
     *
     * @throws NotFoundException
     */
    public function add( $anId = NULL ) {
        if( !empty( $anId ) ) {
            $data = $this->AssetNumber->find( 'first', array( 'contain' => 'AssetGroup', 'conditions' => array( 'AssetNumber.id' => $anId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Asset Number ID.' );
            }
            $subCenterData = $this->Site->find( 'first', [ 'contain' => FALSE, 'conditions' => [ 'Site.id' => $data['AssetGroup']['site_id'] ] ] );
            
            $this->set( 'subCenterData', $subCenterData['Site']['sub_center_id'] );
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->AssetNumber->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->AssetNumber->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Asset Number saved successfully.', 'id' => $this->AssetNumber->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Asset Number saved successfully.' ), 'messages/success' );
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
            'title_for_layout' => 'Asset Number ' . ( empty( $anId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a asset number details
     *
     * @param integer $anId
     *
     * @throws NotFoundException
     */
    public function view( $anId = NULL ) {
        $data = $this->AssetNumber->find( 'first', array( 'contain' => array( 'AssetGroup' => array( 'Site' ) ), 'conditions' => array( 'AssetNumber.id' => $anId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Asset Number ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Asset Number Details' );
    }
}