<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'role' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false, 'comment' => '1 = Admin, 2 = Vendor, 3 = Tr Creator, 4 = Tr Validator, 5 = Invoice Creator, 6 = Invoice Validator'),
		'region_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'sub_center_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'supplier_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'department' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'photo' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'gender' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false, 'comment' => '0=FEMALE, 1=MALE'),
		'security_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'login_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'unsigned' => false),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false, 'comment' => '0=INACTIVE, 1=ACTIVE, 9=DELETED'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'username' => array('column' => 'username', 'unique' => 1)
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
			'id' => 1,
			'role' => 1,
			'region_id' => 1,
			'sub_center_id' => 1,
			'supplier_id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'department' => 'Lorem ipsum dolor sit amet',
			'email' => 'Lorem ipsum dolor sit amet',
			'username' => 'Lorem ipsum dolor sit amet',
			'password' => 'Lorem ipsum dolor sit amet',
			'phone' => 'Lorem ipsum dolor sit amet',
			'address' => 'Lorem ipsum dolor sit amet',
			'photo' => 'Lorem ipsum dolor sit amet',
			'gender' => 1,
			'security_name' => 'Lorem ipsum dolor sit amet',
			'login_attempt' => 1,
			'last_login' => '2016-04-03 14:32:25',
			'created' => '2016-04-03 14:32:25',
			'created_by' => 1,
			'modified' => '2016-04-03 14:32:25',
			'modified_by' => 1,
			'status' => 1
		),
	);

}
