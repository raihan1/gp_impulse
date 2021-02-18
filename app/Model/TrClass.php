<?php
App::uses( 'AppModel', 'Model' );

/**
 * TrClass Model
 *
 * @property AssetGroup $AssetGroup
 * @property Service    $Service
 * @property Ticket     $Ticket
 */
class TrClass extends AppModel {
    
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
        'Service' => array(
            'className'    => 'Service',
            'foreignKey'   => 'tr_class_id',
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
        'Ticket'  => array(
            'className'  => 'Ticket',
            'foreignKey' => FALSE,
            'dependent'  => FALSE,
            'conditions' => array( 'Ticket.tr_class = TrClass.tr_class_name' ),
        ),
    );
    
    public function afterSave( $created, $options = array() ) {
        if( !empty( $this->data['TrClass']['asset_group_id'] ) ) {
            App::import( 'Model', 'AssetGroup' );
            $objAssetGroup = new AssetGroup();
            $assetGroup = $objAssetGroup->find( 'first', array( 'conditions' => array( 'AssetGroup.id' => $this->data['TrClass']['asset_group_id'] ), 'contain' => FALSE ) );
            if( !empty( $assetGroup ) ) {
                App::import( 'Model', 'Site' );
                $objSite = new Site();
                $objSite->save( array( 'Site' => array( 'id' => $assetGroup['AssetGroup']['site_id'], 'modified' => date( 'Y-m-d H:i:s' ) ) ) );
            }
        }
    }
}