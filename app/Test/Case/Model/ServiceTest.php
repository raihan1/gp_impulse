<?php
App::uses('Service', 'Model');

/**
 * Service Test Case
 *
 */
class ServiceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.service',
		'app.supplier',
		'app.sub_center',
		'app.site',
		'app.asset_group',
		'app.asset_number',
		'app.ticket',
		'app.user',
		'app.user_token',
		'app.project',
		'app.supplier_category',
		'app.tr_class',
		'app.invoice',
		'app.tr_service',
		'app.tr_services'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Service = ClassRegistry::init('Service');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Service);

		parent::tearDown();
	}

}
