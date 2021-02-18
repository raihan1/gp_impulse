<?php
App::uses( 'AppModel', 'Model' );

/**
 * Invoice Model
 *
 * @property Supplier  $Supplier
 * @property Region    $Region
 * @property SubCenter $SubCenter
 * @property Ticket    $Ticket
 */
class Invoice extends AppModel {
    
    public $belongsTo = array(
        'Supplier'  => array(
            'className'  => 'Supplier',
            'foreignKey' => FALSE,
            'conditions' => array( 'Supplier.name = Invoice.supplier' ),
            'fields'     => '',
            'order'      => '',
        ),
        'Region'    => array(
            'className'  => 'Region',
            'foreignKey' => FALSE,
            'conditions' => array( 'Region.region_name = Invoice.region' ),
        ),
        'SubCenter' => array(
            'className'  => 'SubCenter',
            'foreignKey' => FALSE,
            'conditions' => array( 'SubCenter.sub_center_name = Invoice.sub_center' ),
        ),
    );
    
    public $hasMany = array(
        'Ticket' => array(
            'className'    => 'Ticket',
            'foreignKey'   => 'invoice_id',
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
    );
}