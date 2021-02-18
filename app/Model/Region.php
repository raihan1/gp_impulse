<?php
App::uses( 'AppModel', 'Model' );

/**
 * Region Model
 *
 * @property User      $User
 * @property SubCenter $SubCenter
 * @property Ticket    $Ticket
 */
class Region extends AppModel {
    
    public $hasMany = array(
        'User'      => array(
            'className'    => 'User',
            'foreignKey'   => 'region_id',
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
        'SubCenter' => array(
            'className'  => 'SubCenter',
            'foreignKey' => 'region_id',
            'dependent'  => FALSE,
        ),
        'Ticket'    => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Ticket.region = Region.region_name' ),
        ),
    );
}