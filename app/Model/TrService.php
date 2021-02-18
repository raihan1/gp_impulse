<?php
App::uses( 'AppModel', 'Model' );

/**
 * TrService Model
 *
 * @property Ticket      $Ticket
 * @property Service     $Service
 * @property Supplier    $Supplier
 * @property LastService $TrService
 */
class TrService extends AppModel {
    
    public $belongsTo = array(
        'Ticket'   => array(
            'className'  => 'Ticket',
            'foreignKey' => 'ticket_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
        'Service'  => array(
            'className'  => 'Service',
            'foreignKey' => FALSE,
            'conditions' => array( 'TrService.supplier = Service.supplier', 'TrService.service = Service.service_name' ),
        ),
        'Supplier' => array(
            'className'  => 'Supplier',
            'foreignKey' => FALSE,
            'conditions' => array( 'TrService.supplier = Supplier.name' ),
        ),
    );
    
    public $hasOne = array(
        'LastService' => array(
            'className'  => 'TrService',
            'foreignKey' => 'last_service',
            'conditions' => array( 'LastService.status' => ACTIVE ),
            'fields'     => array(),
            'order'      => array(),
            'dependent'  => FALSE,
        ),
    );
}