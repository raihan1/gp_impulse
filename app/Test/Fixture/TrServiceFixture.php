<?php
/**
 * TrServiceFixture
 *
 */
class TrServiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'supplier_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'comments' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'unsigned' => false),
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
			'ticket_id' => 1,
			'service_id' => 1,
			'supplier_id' => 1,
			'quantity' => 1,
			'comments' => 'Lorem ipsum dolor sit amet',
			'created' => '2016-03-10 16:37:58',
			'status' => 1
		),
	);

}
