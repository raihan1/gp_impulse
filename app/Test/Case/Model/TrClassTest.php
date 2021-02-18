<?php
App::uses('TrClass', 'Model');

/**
 * TrClass Test Case
 *
 */
class TrClassTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tr_class',
		'app.asset_group',
		'app.site',
		'app.sub_center',
		'app.supplier',
		'app.service',
		'app.tr_services',
		'app.supplier_category',
		'app.ticket',
		'app.user',
		'app.user_token',
		'app.asset_number',
		'app.project',
		'app.invoice',
		'app.tr_service'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TrClass = ClassRegistry::init('TrClass');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TrClass);

		parent::tearDown();
	}

}
