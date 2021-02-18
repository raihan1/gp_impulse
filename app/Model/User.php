<?php
App::uses( 'AppModel', 'Model' );

/**
 * User Model
 *
 * @property Region    $Region
 * @property SubCenter $SubCenter
 * @property Supplier  $Supplier
 * @property UserToken $UserToken
 */
class User extends AppModel {
    
    public $belongsTo = array(
        'Region'    => array(
            'className'  => 'Region',
            'foreignKey' => 'region_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
        'SubCenter' => array(
            'className'  => 'SubCenter',
            'foreignKey' => 'sub_center_id',
        ),
        'Supplier'  => array(
            'className'  => 'Supplier',
            'foreignKey' => 'supplier_id',
        ),
    );
    
    public $hasMany = array(
        'UserToken' => array(
            'className'    => 'UserToken',
            'foreignKey'   => 'user_id',
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