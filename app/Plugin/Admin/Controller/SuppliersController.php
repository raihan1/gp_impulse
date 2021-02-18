<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * Suppliers Controller
 *
 */
class SuppliersController extends AdminAppController {
    
    public $uses = array( 'Supplier' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Supplier List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Supplier List' );
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
                if(!empty($this->request->data['Supplier']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['Supplier']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['Supplier']['file_name']['tmp_name'], WWW_ROOT.'files/supplier/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/supplier/'.$fileName);

                            $x = 2;
                            $excelData = array();
                            while($x <= $excel->sheets[0]['numRows']){

                                //<editor-fold desc="Check Supplier" defaultstate="collapsed">
                                $supplierCheck = $this->Supplier->find('first', array(
                                        'conditions'=>array(
                                            'code'=>strtoupper($excel->sheets[0]['cells'][$x][1])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'Supplier.id, Supplier.code'
                                    )
                                );
                                if(!empty($supplierCheck)): // Excel Value is Available in Database.
                                    $supplier_id = $supplierCheck['Supplier']['id'];
                                    $supplierStatus = 0;
                                else:                       // Excel Value is 'NOT' Available in Database.
                                    $supplier_id = NULL;
                                    $supplierStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('Supplier' => array(
                                    'supplier_id'    => $supplier_id,
                                    'supplier_code'  => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'supplier_name'  => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'supplier_email' => trim($excel->sheets[0]['cells'][$x][3]),
                                    'supplier_phn'   => strtoupper(trim($excel->sheets[0]['cells'][$x][4])),
                                    'supplier_addr'  => trim($excel->sheets[0]['cells'][$x][5]),
                                    'supplier_rem'   => trim($excel->sheets[0]['cells'][$x][6]),
                                    'supplierStatus' => $supplierStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/supplier/'.$fileName);
                            $this->set('supplierBulkData', $excelData);
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
                $saveData = array( 'Supplier' => array(
                    'code'       => trim($tableData[$i]['supplier_code']),
                    'name'       => trim($tableData[$i]['supplier_name']),
                    'email'      => trim($tableData[$i]['supplier_email']),
                    'phone'      => trim($tableData[$i]['supplier_phn']),
                    'address'    => trim($tableData[$i]['supplier_addr']),
                    'remarks'    => trim($tableData[$i]['supplier_rem']),
                    'created_by' => 1,
                ) );

                if(!is_null($tableData[$i]['supplier_id']) && ($tableData[$i]['supplier_id'] != '')){
                    $saveData['Supplier']['id'] = $tableData[$i]['supplier_id'];
                    $update_one++;
                }else{
                    $insert_one++;
                }
                $this->Supplier->create();
                $this->Supplier->save($saveData);
            }
        }
        $this->Session->setFlash($insert_one.' new items had been found and INSERTED & '.$update_one.' items are UPDATED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Supplier List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->Supplier->updateAll( array( $field => $value ), array( 'Supplier.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' supplier.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' supplier.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $supplier = $this->Supplier->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Supplier.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $supplier ) ) {
                $deleteResult = $this->Supplier->updateAll( array( 'is_deleted' => YES ), array( 'Supplier.id' => $supplier['Supplier']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The supplier has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Supplier ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'Supplier.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.code', 'field' => 'code', 'search' => 'like' ),
            2 => array( 'model' => 'Supplier.name', 'field' => 'name', 'search' => 'like' ),
            3 => array( 'model' => 'Supplier.phone', 'field' => 'phone', 'search' => 'like' ),
            4 => array( 'model' => 'Supplier.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->Supplier->find( 'count', array( 'conditions' => $conditions, 'contain' => FALSE ) );
        $data = $this->Supplier->find( 'all', array(
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
     * Add/edit a supplier
     *
     * @param integer $supplierId
     *
     * @throws NotFoundException
     */
    public function add( $supplierId = NULL ) {
        if( !empty( $supplierId ) ) {
            $data = $this->Supplier->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Supplier.id' => $supplierId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Supplier ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( empty( $this->request->data['Supplier']['password'] ) ) {
                    unset( $this->request->data['Supplier']['password'] );
                }
                else {
                    $this->request->data['Supplier']['password'] = $this->Auth->password( $this->request->data['Supplier']['password'] );
                }
                
                if( !$this->Supplier->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->Supplier->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Supplier saved successfully.', 'id' => $this->Supplier->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Supplier saved successfully.' ), 'messages/success' );
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
            'title_for_layout' => 'Supplier ' . ( empty( $supplierId ) ? 'Add' : 'Edit' ),
            'regionList'       => $this->WarrantyLookup->getRegionList(),
            'subCenterList'    => $this->WarrantyLookup->getSubCenterList(),
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
        ) );
    }
    
    /**
     * View a supplier details
     *
     * @param integer $supplierId
     *
     * @throws NotFoundException
     */
    public function view( $supplierId = NULL ) {
        $data = $this->Supplier->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'Supplier.id' => !empty( $supplierId ) ? $supplierId : $this->loginSupplier['Supplier']['id'] ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Supplier ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Supplier Details' );
    }
}