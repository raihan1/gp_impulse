<?php
App::uses( 'AppModel', 'Model' );

/**
 * AssetGroup Model
 *
 * @property Site        $Site
 * @property AssetNumber $AssetNumber
 * @property TrClass     $TrClass
 * @property Ticket      $Ticket
 */
class AssetGroup extends AppModel {
    
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
        'AssetNumber' => array(
            'className'    => 'AssetNumber',
            'foreignKey'   => 'asset_group_id',
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
        'TrClass'     => array(
            'className'  => 'TrClass',
            'foreignKey' => 'asset_group_id',
            'dependent'  => FALSE,
        ),
        'Ticket'      => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Ticket.asset_group = AssetGroup.asset_group_name' ),
        ),
    );
    
    public function afterSave( $created, $options = array() ) {
        if( !empty( $this->data['AssetGroup']['site_id'] ) ) {
            App::import( 'Model', 'Site' );
            $objSite = new Site();
            $objSite->save( array( 'Site' => array( 'id' => $this->data['AssetGroup']['site_id'], 'modified' => date( 'Y-m-d H:i:s' ) ) ) );
        }
    }
}