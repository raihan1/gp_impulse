<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Sites Controller
 */
class SitesController extends AdminAppController {
    
    public $uses = array( 'SubCenter', 'Site' );
    
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
     * Site List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Site List' );
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
                if(!empty($this->request->data['Site']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Site']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Site']['file_name']['tmp_name'], WWW_ROOT.'files/site/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/site/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Office" defaultstate="collapsed">
                                $officeCheck = $this->SubCenter->find('first', array(
                                        'conditions'=>array(
                                            'sub_center_name'=>strtoupper($excel->sheets[0]['cells'][$x][1])
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

                                //<editor-fold desc="Check Site" defaultstate="collapsed">
                                $siteCheck = $this->Site->find('first', array(
                                        'conditions'=>array(
                                            'site_name'=>strtoupper($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Site.id, Site.site_name'
                                    )
                                );
                                if(!empty($siteCheck)): // Excel Value is Available in Database.
                                    $siteStatus = 0;
                                else:                   // Excel Value is 'NOT' Available in Database.
                                    $siteStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('Site' => array(
                                    'office_id'    => $office_id,
                                    'office_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'officeStatus' => $officeStatus,
                                    'site_name'    => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'siteStatus'   => $siteStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/site/'.$fileName);
                            $this->set('siteBulkData', $excelData);
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

        $select_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
                if(($tableData[$i]['office_status'] == 1) && ($tableData[$i]['site_status'] == 1)){
                    $siteCheck = $this->Site->find('first', array(
                            'conditions'=>array(
                                'site_name' => strtoupper(trim($tableData[$i]['site_name']))
                            ),
                            'contain'=>FALSE,
                            'fields' => 'Site.id, Site.site_name')
                    );

                    if(empty($siteCheck)): // Site Value is "NOT" Available in Database.
                        $saveData = array('Site' => array(
                            'sub_center_id' => strtoupper(trim($tableData[$i]['office_id'])),
                            'site_name'     => strtoupper(trim($tableData[$i]['site_name'])),
                            'created_by'    => 1,
                        ) );
                        $this->Site->create();
                        $this->Site->save($saveData);
                        $select_one++;
                    endif;
                }
            }
        }
        $this->Session->setFlash($select_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Site List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->Site->updateAll( array( $field => $value ), array( 'Site.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' site.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' site.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $site = $this->Site->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Site.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $site ) ) {
                $deleteResult = $this->Site->updateAll( array( 'is_deleted' => YES ), array( 'Site.id' => $site['Site']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The site has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Site ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'Site.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'SubCenter.sub_center_name', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'Site.site_name', 'field' => 'site_name', 'search' => 'like' ),
            3 => array( 'model' => 'Site.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->Site->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'SubCenter' ) ) );
        $data = $this->Site->find( 'all', array(
            'contain'    => array( 'SubCenter' ),
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
     * Add/edit a site
     *
     * @param integer $siteId
     *
     * @throws NotFoundException
     */
    public function add( $siteId = NULL ) {
        if( !empty( $siteId ) ) {
            $data = $this->Site->find( 'first', array( 'contain' => 'SubCenter', 'conditions' => array( 'Site.id' => $siteId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Site ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->Site->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->Site->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Site saved successfully.', 'id' => $this->Project->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Site saved successfully.' ), 'messages/success' );
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
            'subCenterList'    => $this->WarrantyLookup->getSubCenterList( NULL ),
            'title_for_layout' => 'Site ' . ( empty( $siteId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a site details
     *
     * @param integer $siteId
     *
     * @throws NotFoundException
     */
    public function view( $siteId = NULL ) {
        $data = $this->Site->find( 'first', array( 'contain' => 'SubCenter', 'conditions' => array( 'Site.id' => $siteId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Site ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Site Details' );
    }
    
    /**
     * Ajax call for site
     */
    public function siteList() {
        $this->autoRender = FALSE;
        
        if( $this->request->is( 'ajax' ) ) {
            $sub_center_id = $this->request->data['sub_center_id'];
            $scData = $this->Site->find( 'all', array( 'contain' => FALSE, 'conditions' => array( 'Site.sub_center_id' => $sub_center_id ), 'order' => array( 'Site.id' => 'ASC' ) ) );
            if( !empty( $scData ) ) {
                die( json_encode( $scData ) );
            }
            else {
                die( json_encode( array() ) );
            }
        }
    }
}