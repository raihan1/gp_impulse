<?php
App::uses( 'AdminAppController', 'Admin.Controller' );

/**
 * SupplierCategories Controller
 *
 */
class SupplierCategoriesController extends AdminAppController {
    
    public $uses = array( 'Supplier', 'SupplierCategory' );
    
    /**
     * Supplier Category List
     */
    public function index(){
        $this->set( 'title_for_layout', 'Supplier Category List' );
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
                if(!empty($this->request->data['SupplierCategory']['file_name'])){
                    $fileNameArray = explode('.', $this->request->data['SupplierCategory']['file_name']['name']);
                    $fileExt       = end($fileNameArray);
                    $fileExt       = strtolower($fileExt);
                    $fileName      = uniqid() . '_' . time() . '.' . $fileExt;

                    if($fileExt == 'xls'){
                        if(!move_uploaded_file($this->request->data['SupplierCategory']['file_name']['tmp_name'], WWW_ROOT.'files/supplier_category/'.$fileName)){
                            throw new Exception('Error while upload the file!');

                        }else{
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file'=>'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT.'files/supplier_category/'.$fileName);

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
                                    $supplierStatus = 1;
                                else:                       // Excel Value is 'NOT' Available in Database.
                                    $supplier_id = null;
                                    $supplierStatus = 0;
                                endif;
                                //</editor-fold>

                                //<editor-fold desc="Check Supplier Category" defaultstate="collapsed">
                                $supCatCheck = $this->SupplierCategory->find('first', array(
                                        'conditions'=>array(
                                            'category_name'=>strtoupper($excel->sheets[0]['cells'][$x][2])
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'SupplierCategory.id, SupplierCategory.category_name'
                                    )
                                );
                                if(!empty($supCatCheck)): // Excel Value is Available in Database.
                                    $supCatStatus = 0;
                                else:                     // Excel Value is 'NOT' Available in Database.
                                    $supCatStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('SupplierCategory' => array(
                                    'supplier_id'    => $supplier_id,
                                    'supplier_code'  => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'supplierStatus' => $supplierStatus,
                                    'supCat_name'    => strtoupper(trim($excel->sheets[0]['cells'][$x][2])),
                                    'supCatStatus'   => $supCatStatus,
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT.'files/supplier_category/'.$fileName);
                            $this->set('supCatBulkData', $excelData);
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
                if(($tableData[$i]['supplier_status'] == 1) && ($tableData[$i]['supCat_status'] == 1)){
                    $supCatCheck = $this->SupplierCategory->find('first', array(
                            'conditions'=>array(
                                'category_name' => strtoupper(trim($tableData[$i]['supCat_name']))
                            ),
                            'contain'=>FALSE,
                            'fields' => 'SupplierCategory.id, SupplierCategory.category_name')
                    );

                    if(empty($supCatCheck)): // Supplier Category Value is "NOT" Available in Database.
                        $saveData = array('SupplierCategory' => array(
                            'supplier_id'   => trim($tableData[$i]['supplier_id']),
                            'category_name' => strtoupper(trim($tableData[$i]['supCat_name'])),
                            'created_by'    => 1,
                        ) );
                        $this->SupplierCategory->create();
                        $this->SupplierCategory->save($saveData);
                        $select_one++;
                    endif;
                }
            }
        }
        $this->Session->setFlash($select_one.' new items had been found and INSERTED out of '.sizeof($tableData).'.' ,'messages/success');
        die();
    }
    
    /**
     * Supplier Category List actions via ajax datatable
     */
    public function data() {
        $result = array();
        
        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'group_action' ) {
            $field = intval( $this->request->data['customActionName'] ) == 9 ? 'is_deleted' : 'status';
            $value = intval( $this->request->data['customActionName'] ) == 9 ? 1 : $this->request->data['customActionName'];
            
            if( $this->SupplierCategory->updateAll( array( $field => $value ), array( 'SupplierCategory.id' => $this->request->data['id'] ) ) ) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count( $this->request->data['id'] ) . ' supplier category.';
            }
            else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count( $this->request->data['id'] ) . ' supplier category.';
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if( isset( $this->request->data['customActionType'] ) && $this->request->data['customActionType'] == 'delete' ) {
            $supplierCategory = $this->SupplierCategory->find( 'first', array( 'contain' => FALSE, 'conditions' => array( 'SupplierCategory.id' => intval( $this->request->data['customActionName'] ) ) ) );
            if( !empty( $supplierCategory ) ) {
                $deleteResult = $this->SupplierCategory->updateAll( array( 'is_deleted' => YES ), array( 'SupplierCategory.id' => $supplierCategory['SupplierCategory']['id'] ) );
                if( $deleteResult === TRUE ) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The supplier category has been deleted.';
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
                $result['customActionMessage'] = 'Invalid Supplier Category ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Settings" defaultstate="collapsed">
        $conditions = array();
        $order = array( 'SupplierCategory.id' => 'DESC' );
        
        $columns = array(
            1 => array( 'model' => 'Supplier.name', 'field' => 'supplier_name', 'search' => 'like' ),
            2 => array( 'model' => 'SupplierCategory.category_name', 'field' => 'category_name', 'search' => 'like' ),
            3 => array( 'model' => 'SupplierCategory.status', 'field' => 'status', 'search' => 'equal' ),
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
        
        $total = $this->SupplierCategory->find( 'count', array( 'conditions' => $conditions, 'contain' => array( 'Supplier' ) ) );
        $data = $this->SupplierCategory->find( 'all', array(
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
     * Add/edit a supplierCategory
     *
     * @param integer $supplierCategoryId
     *
     * @throws NotFoundException
     */
    public function add( $supplierCategoryId = NULL ) {
        if( !empty( $supplierCategoryId ) ) {
            $data = $this->SupplierCategory->find( 'first', array( 'contain' => 'Supplier', 'conditions' => array( 'SupplierCategory.id' => $supplierCategoryId ), 'noStatus' => TRUE ) );
            if( empty( $data ) ) {
                throw new NotFoundException( 'Invalid Supplier Category ID.' );
            }
            $this->set( 'data', $data );
        }
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                if( !$this->SupplierCategory->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->SupplierCategory->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Supplier category saved successfully.', 'id' => $this->SupplierCategory->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Supplier category saved successfully.' ), 'messages/success' );
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
            'title_for_layout' => 'Supplier Category ' . ( empty( $supplierCategoryId ) ? 'Add' : 'Edit' ),
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
        ) );
    }
    
    /**
     * View a supplierCategory details
     *
     * @param integer $supplierCategoryId
     *
     * @throws NotFoundException
     */
    public function view( $supplierCategoryId = NULL ) {
        $data = $this->SupplierCategory->find( 'first', array( 'contain' => 'Supplier', 'conditions' => array( 'SupplierCategory.id' => !empty( $supplierCategoryId ) ? $supplierCategoryId : $this->loginSupplierCategory['SupplierCategory']['id'] ), 'noStatus' => TRUE ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Supplier Category ID.' );
        }
        $this->set( 'data', $data );
        $this->set( 'title_for_layout', 'Supplier Category Details' );
    }
}