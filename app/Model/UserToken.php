<?php
App::uses( 'AppModel', 'Model' );

/**
 * UserToken Model
 *
 * @property User $User
 */
class UserToken extends AppModel {
    
    public $belongsTo = array(
        'User' => array(
            'className'  => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
}