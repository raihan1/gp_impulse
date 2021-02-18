<?php
App::uses('TrService', 'Model');

/**
 * TrService Test Case
 *
 */
class TrServiceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tr_service',
		'app.ticket',
		'app.user',
		'app.sub_center',
		'app.site',
		'app.asset_group',
		'app.asset_number',
		'app.tr_class',
		'app.project',
		'app.supplier',
		'app.service',
		'app.supplier_category',
		'app.user_token',
		'app.invoice'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TrService = ClassRegistry::init('TrService');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TrService);

		parent::tearDown();
	}

}
