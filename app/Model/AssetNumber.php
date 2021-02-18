<?php
App::uses( 'AppModel', 'Model' );

/**
 * AssetNumber Model
 *
 * @property AssetGroup $AssetGroup
 * @property Ticket     $Ticket
 */
class AssetNumber extends AppModel {
    
    public $belongsTo = array(
        'AssetGroup' => array(
            'className'  => 'AssetGroup',
            'foreignKey' => 'asset_group_id',
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
            'conditions'   => array( 'Ticket.asset_number = AssetNumber.asset_number' ),
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
        if( !empty( $this->data['AssetNumber']['asset_group_id'] ) ) {
            App::import( 'Model', 'AssetGroup' );
            $objAssetGroup = new AssetGroup();
            $assetGroup = $objAssetGroup->find( 'first', array( 'conditions' => array( 'AssetGroup.id' => $this->data['AssetNumber']['asset_group_id'] ), 'contain' => FALSE ) );
            if( !empty( $assetGroup ) ) {
                App::import( 'Model', 'Site' );
                $objSite = new Site();
                $objSite->save( array( 'Site' => array( 'id' => $assetGroup['AssetGroup']['site_id'], 'modified' => date( 'Y-m-d H:i:s' ) ) ) );
            }
        }
    }
}