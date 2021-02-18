<?php
App::uses('Invoice', 'Model');

/**
 * Invoice Test Case
 *
 */
class InvoiceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.invoice',
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
		'app.service',
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
		$this->Invoice = ClassRegistry::init('Invoice');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Invoice);

		parent::tearDown();
	}

}
