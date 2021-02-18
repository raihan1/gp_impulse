<?php
App::uses( 'AdminAppController', 'Admin.Controller' );
/**
 *  Custom Value Controller
 */

class CustomValuesController extends AdminAppController{

    public $uses = array('CustomValue');

    public function index(){

        if (isset($this->request->data['setting_save'])) {

               $result = $this->CustomValue->updateAll(

                   array('CustomValue.value' => $this->request->data['block_date']),

                   array('CustomValue.name' => 'TR_MIN_DATE')
               );

           }

        $data = $this->CustomValue->find(
                    'first',
                    array(
                        'conditions' => array(
                        'CustomValue.name' => 'TR_MIN_DATE'
                        )
                    )
                );

        $this->set('pass_data', $data['CustomValue']);

    }
}