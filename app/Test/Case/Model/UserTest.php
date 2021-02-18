<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.sub_center',
		'app.invoice',
		'app.supplier',
		'app.service',
		'app.tr_class',
		'app.asset_group',
		'app.site',
		'app.project',
		'app.ticket',
		'app.asset_number',
		'app.supplier_category',
		'app.tr_service',
		'app.sub_center_budget',
		'app.user_token'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
