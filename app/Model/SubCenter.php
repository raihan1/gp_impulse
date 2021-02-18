<?php
App::uses( 'AppModel', 'Model' );

/**
 * SubCenter Model
 *
 * @property Region          $Region
 * @property User            $User
 * @property Site            $Site
 * @property SubCenterBudget $SubCenterBudget
 * @property Ticket          $Ticket
 * @property Invoice         $Invoice
 */
class SubCenter extends AppModel {
    
    public $belongsTo = array(
        'Region' => array(
            'className'  => 'Region',
            'foreignKey' => 'region_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
    
    public $hasMany = array(
        'User'            => array(
            'className'    => 'User',
            'foreignKey'   => 'sub_center_id',
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
        'Site'            => array(
            'className'  => 'Site',
            'foreignKey' => 'sub_center_id',
            'dependent'  => FALSE,
        ),
        'SubCenterBudget' => array(
            'className'  => 'SubCenterBudget',
            'foreignKey' => 'sub_center_id',
            'dependent'  => FALSE,
        ),
        'Ticket'          => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Ticket.sub_center = SubCenter.sub_center_name' ),
        ),
        'Invoice'         => array(
            'className'  => 'Invoice',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Invoice.sub_center = SubCenter.sub_center_name' ),
        ),
    );
}