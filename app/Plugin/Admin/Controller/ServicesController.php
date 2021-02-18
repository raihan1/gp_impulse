<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Services Controller
 */
class ServicesController extends AdminAppController {
    
    public $uses = array( 'Supplier', 'Service' ,'AssetGroup','Site');
    
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
     * Service List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Service List' );
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
                if(!empty($this->request->data['Service']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Service']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Service']['file_name']['tmp_name'], WWW_ROOT.'files/item/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/item/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Supplier" defaultstate="collapsed">
                                $supplierCheck = $this->Supplier->find('first', array(
                                        'conditions'=>array(
                                            'code' => trim($excel->sheets[0]['cells'][$x][1])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Supplier.id, Supplier.code'
                                    )
                                );
                                if(!empty($supplierCheck)): // Excel Value is Available in Database.
                                    $supplier_id = $supplierCheck['Supplier']['id'];
                                    $supplierStatus = 1;
                                else:                       // Excel Value is 'NOT' Available in Database.
                                    $supplier_id = NULL;
                                    $supplierStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Service" defaultstate="collapsed">
//                                $site_name = ltrim(trim($excel->sheets[0]['cells'][$x][2]), '0');
//                                $site_data = $this->Site->find('first',[
//                                    'conditions' => [
//                                        'Site.site_name' => $site_name
//                                    ],
//                                    'contain' => false
//                                ]);
//                                if(!empty($site_data)){
//                                    $site_id = $site_data['Site']['id'];
//                                }else{
//                                    $site_id = '';
//                                }
//
//                                $asset_grp = $this->AssetGroup->find('first', array(
//                                        'conditions'=>array(
//                                            'AssetGroup.site_id' => $site_id
//                                        ),
//                                        'contain'=>FALSE,
//                                        'fields' => 'AssetGroup.id, AssetGroup.asset_group_name'
//                                    )
//                                );
//                                $asset_grp_name = isset($asset_grp['AssetGroup']['asset_group_name']) ? $asset_grp['AssetGroup']['asset_group_name'] : '';

                                $serviceCheck = $this->Service->find('first', array(
                                        'conditions'=>array(
                                            'supplier_id'  => $supplier_id,
//                                            'asset_group'  => $asset_grp_name,
                                            'service_name' => trim($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Service.id, Service.service_name'
                                    )
                                );
                                if(!empty($serviceCheck)): // Excel Value is Available in Database.
                                    $service_id    = $serviceCheck['Service']['id'];
                                    $serviceStatus = 0;
                                else:                      // Excel Value is 'NOT' Available in Database.
                                    $service_id    = NULL;
                                    $serviceStatus = 1;
                                endif;
                                //</editor-fold>

                                $endDate = date('Y-m-d', strtotime(str_replace('/', '-', trim($excel->sheets[0]['cells'][$x][9]))));
                                $endDate = date('Y-m-d', strtotime('-1 day', strtotime($endDate)));

                                $excelData[] = array('Service' => array(
                                    'supplier_id'    => $supplier_id,
                                    'supplierStatus' => $supplierStatus,
                                    'service_id'     => $service_id,
                                    'serviceStatus'  => $serviceStatus,

                                    'supplier_code'  => trim($excel->sheets[0]['cells'][$x][1]),
//                                    'asset_group'    => ltrim(trim($excel->sheets[0]['cells'][$x][2]), '0'),
                                    'service_name'   => trim($excel->sheets[0]['cells'][$x][2]),
                                    'service_desc'   => str_replace('&','and' , trim($excel->sheets[0]['cells'][$x][3])),
                                    'service_price'  => floatval($excel->sheets[0]['cells'][$x][4]),
                                    'vat'            => floatval($excel->sheets[0]['cells'][$x][5]),
                                    'vat_plus_price' => floatval($excel->sheets[0]['cells'][$x][4]) * (1 + floatval($excel->sheets[0]['cells'][$x][5]) / 100),
                                    'warranty_days'  => intval($excel->sheets[0]['cells'][$x][6]),
                                    'warranty_hours' => intval($excel->sheets[0]['cells'][$x][7]),
                                    'main_type'      => trim($excel->sheets[0]['cells'][$x][8] ),
                                    'end_date'       => $endDate
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/item/'.$fileName);
                            $this->set('serviceBulkData', $excelData);
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
//        die(pr($tableData));
        $tableData = json_decode($tableData,TRUE);
        $insert_one = 0;
        $update_one = 0;
        if(sizeof($tableData) > 0){
            $tableDataSize = sizeof($tableData);
            for($i = 0; $i < $tableDataSize; $i++){
//                die(pr($tableData));
                if($tableData[$i]['supplierStatus'] == 1){
                    $saveData = array( 'Service' => array(
                        'supplier_id'        => trim($tableData[$i]['supplier_id']),
//                        'asset_group'        => ltrim(trim($tableData[$i]['ast_group']), '0'),
                        'main_type'          => trim($tableData[$i]['srv_mt']),
                        'service_name'       => trim($tableData[$i]['srv_name']),
                        'service_desc'       => trim($tableData[$i]['srv_desc']),
                        'service_unit_price' => floatval($tableData[$i]['srv_prc']),
                        'vat'                => floatval($tableData[$i]['srv_vat']),
                        'vat_plus_price'     => floatval($tableData[$i]['srv_pv']),
                        'warranty_days'      => intval($tableData[$i]['srv_wd']),
                        'warranty_hours'     => intval($tableData[$i]['srv_wh']),
                        'aggrement_end_date' => trim($tableData[$i]['srv_ed']),
                        'created_by' => 1,
                    ) );
                    if(!is_null($tableData[$i]['srv_id']) && ($tableData[$i]['srv_id'] != '')){
                        $saveData['Service']['id'] = $tableData[$i]['srv_id'];
                        $update_one++;
                    }else{
                        $insert_one++;
                    }
                    $this->Service->create();
                    $this->Service->save($saveData);
                }
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Service List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->Service->updateAll( array( $field => $value ), array( 'Service.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' service.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' service.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $service = $this->Service->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Service.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $service ) ) {
                $deleteResult = $this->Service->updateAll( array( 'is_deleted' => YES ), array( 'Service.id' => $service['Service']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The service has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Service ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'Service.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 's_name', 'search' => 'like' ),
            2 => array( 'model' => 'Service.service_name', 'field' => 'item_name', 'search' => 'like' ),
            3 => array( 'model' => 'Service.service_unit_price', 'field' => 'item_price', 'search' => 'like' ),
            4 => array( 'model' => 'Service.vat', 'field' => 'vat', 'search' => 'equal' ),
            5 => array( 'model' => 'Service.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->Service->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'Supplier' ) ) );
        $data = $this->Service->find( 'all', array(
            'contain'    => array( 'Supplier' ),
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
     * Add/edit a asset number
     *
     * @param integer $serviceId
     *
     * @throws NotFoundException
     */
    public function add( $serviceId = NULL ) {
        if( !empty( $serviceId ) ) {
            $data = $this->Service->find( 'first', array( 'conditions' => array( 'Service.id' => $serviceId ), 'contain' => FALSE, 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Service ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $this->request->data['Service']['vat_plus_price'] = floatval( $this->request->data['Service']['service_unit_price'] ) * ( 1 + floatval( $this->request->data['Service']['vat'] ) / 100 );
                if( !$this->Service->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->Service->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Service saved successfully.', 'id' => $this->Service->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Service saved successfully.' ), 'messages/success' );
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
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
            'title_for_layout' => 'Service ' . ( empty( $serviceId ) ? 'Add' : 'Edit' ),
        ) );
    }
    
    /**
     * View a asset number details
     *
     * @param integer $serviceId
     *
     * @throws NotFoundException
     */
    public function view( $serviceId = NULL ) {
        $data = $this->Service->find( 'first', array( 'conditions' => array( 'Service.id' => $serviceId ), 'contain' => array( 'Supplier' ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Service ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Service Details' );
    }
}