<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Regions Controller
 */
class RegionsController extends AdminAppController {
    
    public $uses = array( 'Region' );
    
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
     */
    public function index() {
        $this->set( 'title_for_layout', 'Region List' );
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
                if(!empty($this->request->data['Region']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Region']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Region']['file_name']['tmp_name'], WWW_ROOT.'files/region/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/region/'.$fileName);

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
                                    $regionStatus = 0;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $regionStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('Region' => array(
                                    'region_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'regionStatus' => $regionStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/region/'.$fileName);
                            $this->set('regionBulkData', $excelData);
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
                if($tableData[$i]['region_status'] == 1){
                    $regionCheck = $this->Region->find('first', array(
                            'conditions'=>array(
                                'region_name' => strtoupper(trim($tableData[$i]['region_name']))
                            ),
                            'contain'=>FALSE,
                            'fields' => 'Region.id, Region.region_name')
                    );

                    if(empty($regionCheck)): // Region Value is "NOT" Available in Database.
                        $saveData = array( 'Region' => array(
                            'region_name' => strtoupper(trim($tableData[$i]['region_name'])),
                            'created_by'  => 1,
                        ) );
                        $this->Region->create();
                        $this->Region->save($saveData);
                        $select_one++;
                    endif;
                }
            }
        }
        $this->Session->setFlash($select_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Region List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->Region->updateAll( array( $field => $value ), array( 'Region.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' region.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' region.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $region = $this->Region->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Region.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $region ) ) {
                $deleteResult = $this->Region->updateAll( array( 'is_deleted' => YES ), array( 'Region.id' => $region['Region']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The region has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Region ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'Region.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Region.region_name', 'field' => 'name', 'search' => 'like' ),
            2 => array( 'model' => 'Region.status', 'field' => 'status', 'search' => 'equal' ),
        );
        
        if( !empty( $this->request->data['order'][0]['column'] ) ) {
            $column = $columns[ $this->request->data['order'][0]['column'] ]['field'];
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
        
        $total = $this->Region->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Region->find( 'all', array(
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
     * Add/edit a region
     *
     * @param integer $regionId
     *
     * @throws NotFoundException
     */
    public function add( $regionId = NULL ) {
        if( !empty( $regionId ) ) {
            $data = $this->Region->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Region.id' => $regionId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Region ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->Region->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->Brand->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Region saved successfully.', 'id' => $this->Region->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Region saved successfully.' ), 'messages/success' );
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
            'title_for_layout' => 'Region ' . ( empty( $regionId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a region details
     *
     * @param integer $regionId
     *
     * @throws NotFoundException
     */
    public function view( $regionId = NULL ) {
        $data = $this->Region->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Region.id' => $regionId ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Region ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Region Details' );
    }
}