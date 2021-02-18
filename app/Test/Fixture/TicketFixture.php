<?php
/**
 * TicketFixture
 *
 */
class TicketFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 11, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'sub_center_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'department' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'site_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'asset_group_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'asset_number_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'supplier_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'supplier_category_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'tr_class_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'tr_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false),
		'received_at_supplier' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'complete_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'comment' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'recurring_tr' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'tr_creation_confirmed_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'tr_validation_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'invoice_created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'invoice_creation_confirmed_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'invoice_validation_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'stage' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'lock_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false, 'comment' => '0 = UNLOCK,1 = LOCK'),
		'pending_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false, 'comment' => '0 = \'Pending\''),
		'approval_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false, 'comment' => '1 = Approved, 0 = Deny'),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false, 'comment' => '1 = Active, 0 = Inactive'),
		'invoice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'invoice_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'is_invoiced' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '',
			'user_id' => 1,
			'sub_center_id' => 1,
			'department' => 'Lorem ipsum dolor sit amet',
			'site_id' => 1,
			'asset_group_id' => 1,
			'asset_number_id' => 1,
			'project_id' => 1,
			'supplier_id' => 1,
			'supplier_category_id' => 1,
			'tr_class_id' => 1,
			'tr_status' => 1,
			'received_at_supplier' => '2016-03-30 11:21:11',
			'complete_date' => '2016-03-30 11:21:11',
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'recurring_tr' => 1,
			'created' => '2016-03-30 11:21:11',
			'created_by' => 1,
			'tr_creation_confirmed_by' => 1,
			'tr_validation_by' => 1,
			'invoice_created_by' => 1,
			'invoice_creation_confirmed_by' => 1,
			'invoice_validation_by' => 1,
			'stage' => 1,
			'lock_status' => 1,
			'pending_status' => 1,
			'approval_status' => 1,
			'status' => 1,
			'invoice_id' => 1,
			'invoice_date' => '2016-03-30 11:21:11',
			'is_invoiced' => 1
		),
	);

}
