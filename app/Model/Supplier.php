<?php
App::uses( 'AppModel', 'Model' );

/**
 * Supplier Model
 *
 * @property User             $User
 * @property Service          $Service
 * @property SupplierCategory $SupplierCategory
 * @property Ticket           $Ticket
 * @property TrService        $TrService
 */
class Supplier extends AppModel {
    
    public $hasMany = array(
        'User'             => array(
            'className'    => 'User',
            'foreignKey'   => 'supplier_id',
            'dependent'    => FALSE,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => '',
        ),
        'Service'          => array(
            'className'  => 'Service',
            'foreignKey' => 'supplier_id',
            'dependent'  => FALSE,
        ),
        'SupplierCategory' => array(
            'className'  => 'SupplierCategory',
            'foreignKey' => 'supplier_id',
            'dependent'  => FALSE,
        ),
        'Ticket'           => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Ticket.supplier = Supplier.name' ),
        ),
        'TrService'        => array(
            'className'  => 'TrService',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'TrService.supplier = Supplier.name' ),
        ),
    );
}