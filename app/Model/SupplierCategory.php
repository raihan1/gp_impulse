<?php
App::uses( 'AppModel', 'Model' );

/**
 * SupplierCategory Model
 *
 * @property Supplier $Supplier
 * @property Ticket   $Ticket
 */
class SupplierCategory extends AppModel {
    
    public $belongsTo = array(
        'Supplier' => array(
            'className'  => 'Supplier',
            'foreignKey' => 'supplier_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
    
    public $hasMany = array(
        'Ticket' => array(
            'className'    => 'Ticket',
            'foreignKey'   => FALSE,
            'dependent'    => FALSE,
            'conditions'   => array( 'Ticket.supplier_category = SupplierCategory.category_name' ),
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