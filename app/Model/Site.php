<?php
App::uses( 'AppModel', 'Model' );

/**
 * Site Model
 *
 * @property SubCenter  $SubCenter
 * @property AssetGroup $AssetGroup
 * @property Project    $Project
 * @property Ticket     $Ticket
 */
class Site extends AppModel {
    
    public $belongsTo = array(
        'SubCenter' => array(
            'className'  => 'SubCenter',
            'foreignKey' => 'sub_center_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
    );
    
    public $hasMany = array(
        'AssetGroup' => array(
            'className'    => 'AssetGroup',
            'foreignKey'   => 'site_id',
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
        'Project'    => array(
            'className'  => 'Project',
            'foreignKey' => 'site_id',
            'dependent'  => FALSE,
        ),
        'Ticket'     => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => 'Ticket.site = Site.site_name',
        ),
    );
}