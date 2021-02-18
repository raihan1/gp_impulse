<?php
/**
 * SubCenterBudgetFixture
 *
 */
class SubCenterBudgetFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'sub_center_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'initial_budget' => array('type' => 'decimal', 'null' => false, 'default' => '0.00', 'length' => '12,2', 'unsigned' => false),
		'current_budget' => array('type' => 'decimal', 'null' => false, 'default' => '0.00', 'length' => '12,2', 'unsigned' => false),
		'month' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3, 'unsigned' => false),
		'year' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 4),
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
			'sub_center_id' => 1,
			'initial_budget' => '',
			'current_budget' => '',
			'month' => 1,
			'year' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);

}
