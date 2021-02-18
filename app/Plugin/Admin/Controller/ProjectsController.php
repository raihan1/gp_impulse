<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Projects Controller
 */
class ProjectsController extends AdminAppController {
    
    public $uses = array( 'Site', 'Project' );
    
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
     * Project List
     */
    public function index() {
        $this->set( 'title_for_layout', 'Project List' );
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
                if(!empty($this->request->data['Project']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Project']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Project']['file_name']['tmp_name'], WWW_ROOT.'files/project/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/project/'.$fileName);

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

                                //<editor-fold desc="Check Project" defaultstate="collapsed">
                                $projectCheck = $this->Project->find('first', array(
                                        'conditions'=>array(
                                            'site_id'      => $site_id,
                                            'project_name' =>strtoupper($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Project.id, Project.project_name'
                                    )
                                );
                                if(!empty($projectCheck)): // Excel Value is Available in Database.
                                    $project_id = $projectCheck['Project']['id'];
                                    $projectStatus = 0;
                                else:                      // Excel Value is 'NOT' Available in Database.
                                    $project_id = null;
                                    $projectStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('Project' => array(
                                    'site_id'       => $site_id,
                                    'site_name'     => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'siteStatus'    => $siteStatus,
                                    'project_id'    => $project_id,
                                    'project_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'projectStatus' => $projectStatus,
                                    'project_ip'    => strtoupper(trim($excel->sheets[0]['cells'][$x][3])),
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/project/'.$fileName);
                            $this->set('projectBulkData', $excelData);
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
                if($tableData[$i]['site_status'] == 1){
                    $saveData = array('Project' => array(
                        'site_id'      => $tableData[$i]['site_id'],
                        'project_name' => strtoupper(trim($tableData[$i]['project_name'])),
                        'project_ip'   => strtoupper(trim($tableData[$i]['project_ip'])),
                        'created_by'   => 1,
                    ));

                    if(!is_null($tableData[$i]['project_id']) && ($tableData[$i]['project_id'] != '')){
                        $saveData['Project']['id'] = $tableData[$i]['project_id'];
                        $update_one++;
                    }else{
                        $insert_one++;
                    }
                    $this->Project->create();
                    $this->Project->save($saveData);
                }
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Project List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->Project->updateAll( array( $field => $value ), array( 'Project.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' project.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' project.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $project = $this->Project->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Project.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $project ) ) {
                $deleteResult = $this->Project->updateAll( array( 'is_deleted' => YES ), array( 'Project.id' => $project['Project']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The project has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Project ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'Project.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Site.site_name', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'Project.project_name', 'field' => 'p_name', 'search' => 'like' ),
            3 => array( 'model' => 'Project.project_ip', 'field' => 'p_ip', 'search' => 'like' ),
            4 => array( 'model' => 'Project.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->Project->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'Site' ) ) );
        $data = $this->Project->find( 'all', array(
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
     * Add/edit a project
     *
     * @param integer $pId
     *
     * @throws NotFoundException
     */
    public function add( $pId = NULL ) {
        if( !empty( $pId ) ) {
            $data = $this->Project->find( 'first', array( 'contain' => 'Site', 'conditions' => array( 'Project.id' => $pId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Project ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->Project->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->Project->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Project saved successfully.', 'id' => $this->Project->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Project saved successfully.' ), 'messages/success' );
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
            'title_for_layout' => 'Project ' . ( empty( $pId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a project details
     *
     * @param integer $pId
     *
     * @throws NotFoundException
     */
    public function view( $pId = NULL ) {
        $data = $this->Project->find( 'first', array( 'contain' => 'Site', 'conditions' => array( 'Project.id' => $pId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Project ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Project Details' );
    }
}