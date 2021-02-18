<?php
App::uses( 'AppModel', 'Model' );

/**
 * Notification Model
 *
 * @property Ticket $Ticket
 */
class Notification extends AppModel {
    
    public $belongsTo = array(
        'Ticket' => array(
            'className'  => 'Ticket',
            'foreignKey' => 'ticket_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
}