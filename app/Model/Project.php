<?php
App::uses( 'AppModel', 'Model' );

/**
 * Project Model
 *
 * @property Site   $Site
 * @property Ticket $Ticket
 */
class Project extends AppModel {
    
    public $belongsTo = array(
        'Site' => array(
            'className'  => 'Site',
            'foreignKey' => 'site_id',
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
            'conditions'   => array( 'Ticket.project = Project.project_name' ),
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => '',
        ),
    );
    
    public function afterSave( $created, $options = array() ) {
        if( !empty( $this->data['Project']['site_id'] ) ) {
            App::import( 'Model', 'Site' );
            $objSite = new Site();
            $objSite->save( array( 'Site' => array( 'id' => $this->data['Project']['site_id'], 'modified' => date( 'Y-m-d H:i:s' ) ) ) );
        }
    }
}