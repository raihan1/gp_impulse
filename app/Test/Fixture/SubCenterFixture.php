<?php
/**
 * SubCenterFixture
 *
 */
class SubCenterFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'region_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'sub_center_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'sub_center_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'budget' => array('type' => 'decimal', 'null' => false, 'default' => '0.00', 'length' => '12,2', 'unsigned' => false),
		'eighty_percent_action' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false),
		'ninety_percent_action' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false),
		'hundred_percent_action' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false, 'comment' => '0 = \'INACTIVE\', 1 = \'ACTIVE\''),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'region_id' => 1,
			'sub_center_name' => 'Lorem ipsum dolor sit amet',
			'sub_center_name' => 'Lorem ipsum dolor sit amet',
			'budget' => '',
			'eighty_percent_action' => 1,
			'ninety_percent_action' => 1,
			'hundred_percent_action' => 1,
			'created' => '2016-04-03 14:32:04',
			'created_by' => 1,
			'modified' => '2016-04-03 14:32:04',
			'modified_by' => 1,
			'status' => 1
		),
	);

}
