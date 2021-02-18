<?php
App::uses('Region', 'Model');

/**
 * Region Test Case
 *
 */
class RegionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.region',
		'app.sub_center',
		'app.invoice',
		'app.supplier',
		'app.service',
		'app.tr_class',
		'app.asset_group',
		'app.site',
		'app.project',
		'app.ticket',
		'app.user',
		'app.user_token',
		'app.asset_number',
		'app.supplier_category',
		'app.tr_service',
		'app.sub_center_budget'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Region = ClassRegistry::init('Region');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Region);

		parent::tearDown();
	}

}
