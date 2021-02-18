<?php
App::uses( 'AppModel', 'Model' );

/**
 * Ticket Model
 *
 * @property User             $User
 * @property Region           $Region
 * @property SubCenter        $SubCenter
 * @property Site             $Site
 * @property Project          $Project
 * @property AssetGroup       $AssetGroup
 * @property AssetNumber      $AssetNumber
 * @property TrClass          $TrClass
 * @property Supplier         $Supplier
 * @property SupplierCategory $SupplierCategory
 * @property Invoice          $Invoice
 * @property TrService        $TrService
 */
class Ticket extends AppModel {
    
    public $belongsTo = array(
        'User'             => array(
            'className'  => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields'     => '',
            'order'      => '',
        ),
        'Region'           => array(
            'className'  => 'Region',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.region = Region.region_name' ),
        ),
        'SubCenter'        => array(
            'className'  => 'SubCenter',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.sub_center = SubCenter.sub_center_name' ),
        ),
        'Site'             => array(
            'className'  => 'Site',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.site = Site.site_name' ),
        ),
        'Project'          => array(
            'className'  => 'Project',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.project = Project.project_name' ),
        ),
        'AssetGroup'       => array(
            'className'  => 'AssetGroup',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.asset_group = AssetGroup.asset_group_name' ),
        ),
        'AssetNumber'      => array(
            'className'  => 'AssetNumber',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.asset_number = AssetNumber.asset_number' ),
        ),
        'TrClass'          => array(
            'className'  => 'TrClass',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.tr_class = TrClass.tr_class_name' ),
        ),
        'Supplier'         => array(
            'className'  => 'Supplier',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.supplier = Supplier.name' ),
        ),
        'SupplierCategory' => array(
            'className'  => 'SupplierCategory',
            'foreignKey' => FALSE,
            'conditions' => array( 'Ticket.supplier_category = SupplierCategory.category_name' ),
        ),
        'Invoice'          => array(
            'className'  => 'Invoice',
            'foreignKey' => 'invoice_id',
        ),
        'CreatedBy'        => array(
            'className'  => 'User',
            'foreignKey' => 'created_by',
        ),
        'ClosedBy'         => array(
            'className'  => 'User',
            'foreignKey' => 'tr_closed_by',
        ),
        'ValidatedBy'      => array(
            'className'  => 'User',
            'foreignKey' => 'tr_validation_by',
        ),
    );
    
    public $hasMany = array(
        'TrService' => array(
            'className'    => 'TrService',
            'foreignKey'   => 'ticket_id',
            'dependent'    => TRUE,
            'conditions'   => '',
            'fields'       => '',
            'order'        => '',
            'limit'        => '',
            'offset'       => '',
            'exclusive'    => '',
            'finderQuery'  => '',
            'counterQuery' => '',
        ),
    );
}