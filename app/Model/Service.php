<?php
App::uses( 'AppModel', 'Model' );

/**
 * Service Model
 *
 * @property Supplier  $Supplier
 * @property TrClass   $TrClass
 * @property TrService $TrService
 */
class Service extends AppModel {
    
    public $belongsTo = array(
        'Supplier'  => array(
            'className'  => 'Supplier',
            'foreignKey' => 'supplier_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
        'TrClass'   => array(
            'className'  => 'TrClass',
            'foreignKey' => 'tr_class_id',
        ),
        'TrService' => array(
            'className'  => 'TrService',
            'foreignKey' => FALSE,
            'conditions' => array( 'TrService.service = Service.service_name' ),
        ),
    );
}