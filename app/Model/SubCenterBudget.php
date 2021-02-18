<?php
App::uses( 'AppModel', 'Model' );

/**
 * SubCenterBudget Model
 *
 * @property SubCenter $SubCenter
 */
class SubCenterBudget extends AppModel {
    
    public $belongsTo = array(
        'SubCenter' => array(
            'className'  => 'SubCenter',
            'foreignKey' => 'sub_center_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
}