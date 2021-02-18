<?php
App::uses('AdminAppController', 'Admin.Controller');

/**
 * TrClasses Controller
 */
class TrClassesController extends AdminAppController
{

    public $uses = array('SubCenter', 'Site', 'AssetGroup', 'TrClass');

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    /**
     * TrClass List
     */
    public function index()
    {
        $this->set('title_for_layout', 'TrClass List');
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
    public function bulk_import()
    {
        if ($this->request->is(array('post', 'put'))) {
            try {
                if (!empty($this->request->data['TrClass']['file_name'])) {
                    $fileNameArray = explode('.', $this->request->data['TrClass']['file_name']['name']);
                    $fileExt = end($fileNameArray);
                    $fileExt = strtolower($fileExt);
                    $fileName = uniqid() . '_' . time() . '.' . $fileExt;

                    if ($fileExt == 'xls') {
                        if (!move_uploaded_file($this->request->data['TrClass']['file_name']['tmp_name'], WWW_ROOT . 'files/tr_class/' . $fileName)) {
                            throw new Exception('Error while upload the file!');

                        } else {
                            set_time_limit(0);
                            ini_set('memory_limit', -1);

                            App::import('Vendor', 'excel_reader', array('file' => 'excel_reader/reader.php'));
                            $excel = new Spreadsheet_Excel_Reader();
                            $excel->read(WWW_ROOT . 'files/tr_class/' . $fileName);

                            $x = 2;
                            $excelData = array();
                            while ($x <= $excel->sheets[0]['numRows']) {

                                //<editor-fold desc="Check Site Name/ Asset Group NOT USED" defaultstate="collapsed">
                                /*$site_name = ltrim(trim($excel->sheets[0]['cells'][$x][3]), '0');
                                $site_data = $this->Site->find('first',[
                                    'conditions' => [
                                        'Site.site_name' => $site_name
                                    ],
                                    'contain' => false
                                ]);
                                if(!empty($site_data)){
                                    $site_id = $site_data['Site']['id'];
                                }else{
                                    $site_id = '';
                                }

                                $aGroupCheck = $this->AssetGroup->find('first', array(
                                        'conditions'=>array(
                                            'AssetGroup.site_id' => $site_id
                                        ),
                                        'contain'=>FALSE,
                                        'fields' => 'AssetGroup.id, AssetGroup.asset_group_name'
                                    )
                                );
                                $aGroupCheck = $this->AssetGroup->find('first', array(
                                        'conditions'=>array(
                                            'asset_group_name' => ltrim(trim($excel->sheets[0]['cells'][$x][1]), '0'),
                                            'status'           => ACTIVE
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
                                endif;*/
                                //</editor-fold>

                                //<editor-fold desc="Check TR Class" defaultstate="collapsed">
                                $trClassCheck = $this->TrClass->find('first', array(
                                    'conditions' => array(
//                                        'asset_group_id' => $aGroup_id,
                                        'tr_class_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    ),
                                    'contain' => FALSE,
                                    'fields' => 'TrClass.id, TrClass.tr_class_name'
                                ));
                                if (!empty($trClassCheck)): // Excel Value is Available in Database.
                                    $trClass_id = $trClassCheck['TrClass']['id'];
                                    $trClassStatus = 0;
                                else:                      // Excel Value is 'NOT' Available in Database.
                                    $trClass_id = NULL;
                                    $trClassStatus = 1;
                                endif;
                                //</editor-fold>

                                $excelData[] = array('TrClass' => array(
//                                    'aGroup_id'     => $aGroup_id,
//                                    'aGroup_name'   => ltrim(trim($excel->sheets[0]['cells'][$x][1]), '0'),
//                                    'aGroupStatus'  => $aGroupStatus,
                                    'trClass_id' => $trClass_id,
                                    'trClass_name' => strtoupper(trim($excel->sheets[0]['cells'][$x][1])),
                                    'trClassStatus' => $trClassStatus,
                                    'trClassDays' => strtoupper(trim($excel->sheets[0]['cells'][$x][2]))
                                ));
                                $x++;
                            }
                            unlink(WWW_ROOT . 'files/tr_class/' . $fileName);
                            $this->set('trClassBulkData', $excelData);
                        }

                    } else {
                        throw new UnexpectedValueException('Please upload a valid file.');
                    }

                } else {
                    throw new NotFoundException('Please upload a file.');
                }

            } catch (Exception $e) {
                $this->Session->setFlash(__($e->getMessage()), 'messages/failed');
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
    public function bulk_import_post()
    {
        $this->autoRender = false;

        $tableData = stripcslashes($_POST['pTableData']);
        $tableData = json_decode($tableData, TRUE);

        $insert_one = 0;
        $update_one = 0;
        $tableDataSize = 0;
        if (sizeof($tableData) > 0) {
//            die(pr($tableData));
            $tableDataSize = sizeof($tableData);
            for ($i = 0; $i < $tableDataSize; $i++) {
                $saveData = array('TrClass' => array(
                    'asset_group_id' => trim($tableData[$i]['aGroup_id']),
                    'tr_class_name' => trim($tableData[$i]['trClass_name']),
                    'no_of_days' => trim($tableData[$i]['trClass_days']),
                ));

                $trClassCheck = $this->TrClass->find('first', array(
                    'conditions' => array(
                        'tr_class_name' => trim($tableData[$i]['trClass_name']),
                    ),
                    'contain' => FALSE,
                    'fields' => array('id', 'tr_class_name'),
                ));

                if (!empty($trClassCheck)) {
                    $saveData['TrClass']['id'] = $trClassCheck['TrClass']['id'];
                    $update_one++;
                } else {
                    $insert_one++;
                }

//                die(pr($saveData));

                $this->TrClass->create();
                $this->TrClass->save($saveData);

                /*if($tableData[$i]['aGroup_status'] == 1){
                    $groupLists = $this->AssetGroup->find('list', array(
                        'conditions' => array(
                            'asset_group_name' => $tableData[$i]['aGroup_name'],
                            'status'           => ACTIVE,
                        ),
                        'contain'    => FALSE,
                        'fields'     => array( 'id', 'asset_group_name' ),
                    ));

                    if(!empty($groupLists)) {
                        foreach($groupLists as $asset_group_id => $asset_group_name){
                            $saveData = array('TrClass' => array(
                                'asset_group_id' => $asset_group_id,
                                'tr_class_name'  => trim($tableData[$i]['trClass_name']),
                                'no_of_days'     => trim($tableData[$i]['trClass_days']),
                            ));

                            $trClassCheck = $this->TrClass->find('first', array(
                                'conditions' => array(
                                    'asset_group_id' => $asset_group_id,
                                    'tr_class_name'  => trim($tableData[$i]['trClass_name']),
                                ),
                                'contain'    => FALSE,
                                'fields'     => array( 'id', 'tr_class_name' ),
                            ) );
                            if(!empty($trClassCheck)){
                                $saveData['TrClass']['id'] = $trClassCheck['TrClass']['id'];
                                $update_one++;
                            }else{
                                $insert_one++;
                            }

                            $this->TrClass->create();
                            $this->TrClass->save($saveData);
                        }
                    }
                }*/
            }
        }
        $this->Session->setFlash($insert_one . ' new items had been found and INSERTED & ' . $update_one . ' items are UPDATED. out of ' . $tableDataSize . ' items. ', 'messages/success');
        die();
    }

    /**
     * TrClass List actions via ajax datatable
     */
    public function data()
    {
        $result = array();

        //<editor-fold desc="Group actions (activate/deactivate/delete)" defaultstate="collapsed">
        if (isset($this->request->data['customActionType']) && $this->request->data['customActionType'] == 'group_action') {
            $field = intval($this->request->data['customActionName']) == 9 ? 'is_deleted' : 'status';
            $value = intval($this->request->data['customActionName']) == 9 ? 1 : $this->request->data['customActionName'];

            if ($this->TrClass->updateAll(array($field => $value), array('TrClass.id' => $this->request->data['id']))) {
                $result['customActionStatus'] = 'OK';
                $result['customActionMessage'] = 'Status updated for ' . count($this->request->data['id']) . ' tr class.';
            } else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Failed to update status of ' . count($this->request->data['id']) . ' tr class.';
            }
        }
        //</editor-fold>

        //<editor-fold desc="Single delete" defaultstate="collapsed">
        if (isset($this->request->data['customActionType']) && $this->request->data['customActionType'] == 'delete') {
            $tr_class = $this->TrClass->find('first', array('contain' => FALSE, 'conditions' => array('TrClass.id' => intval($this->request->data['customActionName']))));
            if (!empty($tr_class)) {
                $deleteResult = $this->TrClass->updateAll(array('is_deleted' => YES), array('TrClass.id' => $tr_class['TrClass']['id']));
                if ($deleteResult === TRUE) {
                    $result['customActionStatus'] = 'OK';
                    $result['customActionMessage'] = 'The tr class has been deleted.';
                } else {
                    $errors = '';
                    foreach ($deleteResult as $field => $error) {
                        $errors .= ($errors == '' ? '' : '<br />') . $field . ': ' . implode(', ', $error);
                    }
                    $result['customActionStatus'] = 'FAIL';
                    $result['customActionMessage'] = $errors;
                }
            } else {
                $result['customActionStatus'] = 'FAIL';
                $result['customActionMessage'] = 'Invalid TrClass ID: ' . $this->request->data['customActionName'];
            }
        }
        //</editor-fold>

        //<editor-fold desc="Settings" defaultstate="collapsed">
        $columns = array(
            1 => array('model' => 'AssetGroup.asset_group_name', 'field' => 'ag_name', 'search' => 'like'),
            2 => array('model' => 'TrClass.tr_class_name', 'field' => 'tr_class_name', 'search' => 'like'),
            3 => array('model' => 'TrClass.no_of_days', 'field' => 'days', 'search' => 'equal'),
            4 => array('model' => 'TrClass.status', 'field' => 'status', 'search' => 'equal'),
        );

        $conditions = array('TrClass.status' => [1, 0]);
        foreach ($columns as $col) {
            if (isset($this->request->data[$col['field']]) && $this->request->data[$col['field']] != '') {
                if ($col['search'] == 'like') {
                    $conditions["{$col['model']} LIKE"] = '%' . $this->request->data[$col['field']] . '%';
                } else {
                    $conditions["{$col['model']}"] = $this->request->data[$col['field']];
                }
            }
        }

        $order = array('TrClass.id' => 'DESC');
        if (!empty($this->request->data['order'][0]['column'])) {
            $column = $columns[$this->request->data['order'][0]['column']]['model'];
            $direction = $this->request->data['order'][0]['dir'];
            $order = array($column => $direction);
        }
        //</editor-fold>
        $total = $this->TrClass->find('count', array('conditions' => $conditions, 'contain' => false));
        $data = $this->TrClass->find('all', array(
            'contain' => false,
            'conditions' => $conditions,
            'limit' => intval($this->request->data['length']) > 0 ? intval($this->request->data['length']) : $total,
            'offset' => intval($this->request->data['start']),
            'order' => $order,
        ));


        $this->set(array(
            'request' => $this->request->data,
            'result' => $result,
            'data' => $data,
            'total' => $total,
        ));
    }

    /**
     * Add/edit a asset number
     *
     * @param integer $trClassId
     *
     * @throws NotFoundException
     */
    public function add($trClassId = NULL)
    {
        $this->loadModel('AssetGroup');
        if (!empty($trClassId)) {
            $data = $this->TrClass->find('first', array('contain' => 'AssetGroup', 'conditions' => array('TrClass.id' => $trClassId), 'noStatus' => TRUE));
            if (empty($data)) {
                throw new NotFoundException('Invalid TrClass ID.');
            }
            $subCenterData = $this->Site->find('first', ['contain' => FALSE, 'conditions' => ['Site.id' => $data['AssetGroup']['site_id']]]);

            if (!empty($subCenterData)) {
                $this->set('subCenterData', $subCenterData['Site']['sub_center_id']);
            } else {
                $this->set('subCenterData', 0);
            }

            $this->set('data', $data);
        }

        if ($this->request->is(array('post', 'put'))) {
            try {
//                $site_id = $this->request->data['TrClass']['site_id'];
//
//                $asset_grp = $this->AssetGroup->find('first',[
//                    'conditions' =>[
//                        'AssetGroup.site_id' => $site_id,
//                    ],
//                    'contain' => false,
//                ]);
//
//                if(empty($asset_grp)){
//                    $asset_grp_data['AssetGroup'] = [
//                        'site_id' => $site_id,
//                        'asset_group_name' => 'test',
//                        'status' => 1
//                    ];
//
//                    $this->AssetGroup->save($asset_grp_data);
//                    $asset_group_id = $this->AssetGroup->id;
//                    $this->request->data['TrClass']['asset_group_id'] = $asset_group_id;
//                }else{
//                    $this->request->data['TrClass']['asset_group_id'] = $asset_grp['AssetGroup']['id'];
//                }

//                die(pr($this->request->data));
                if (!$this->TrClass->save($this->request->data)) {
                    $errors = '';
                    foreach ($this->TrClass->validationErrors as $field => $error) {
                        $errors .= ($errors == '' ? '' : '<br />') . $field . ': ' . implode(', ', $error);
                    }
                    throw new Exception($errors);
                }

                if ($this->request->is('ajax')) {
                    die(json_encode(array('result' => TRUE, 'message' => 'TrClass saved successfully.', 'id' => $this->TrClass->id)));
                } else {
                    $this->Session->setFlash(__('TrClass saved successfully.'), 'messages/success');
                    $this->redirect(array('action' => 'index'));
                }
            } catch (Exception $e) {
                if ($this->request->is('ajax')) {
                    die(json_encode(array('result' => FALSE, 'message' => __($e->getMessage()))));
                } else {
                    $this->Session->setFlash(__($e->getMessage()), 'messages/failed');
                }
            }
        }

        $this->set(array(
            'subCenterList' => $this->WarrantyLookup->getSubCenterList(),
            'siteList' => $this->WarrantyLookup->getSiteList(NULL),
            'title_for_layout' => 'TrClass ' . (empty($trClassId) ? 'Add' : 'Edit'),
        ));
    }

    /**
     * View a asset number details
     *
     * @param integer $trClassId
     *
     * @throws NotFoundException
     */
    public function view($trClassId = NULL)
    {
        $data = $this->TrClass->find('first', array('contain' => 'AssetGroup', 'conditions' => array('TrClass.id' => $trClassId), 'noStatus' => TRUE));
        if (empty($data)) {
            throw new NotFoundException('Invalid TrClass ID.');
        }
        $this->set('data', $data);
        $this->set('title_for_layout', 'TrClass Details');
    }
}