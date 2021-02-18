<?php
App::uses('SubCenterBudget', 'Model');

/**
 * SubCenterBudget Test Case
 *
 */
class SubCenterBudgetTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sub_center_budget',
		'app.sub_center',
		'app.site',
		'app.asset_group',
		'app.asset_number',
		'app.ticket',
		'app.user',
		'app.supplier',
		'app.service',
		'app.tr_services',
		'app.supplier_category',
		'app.tr_service',
		'app.user_token',
		'app.project',
		'app.tr_class',
		'app.invoice'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SubCenterBudget = ClassRegistry::init('SubCenterBudget');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SubCenterBudget);

		parent::tearDown();
	}

}
