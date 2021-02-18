<?php
App::uses('Ticket', 'Model');

/**
 * Ticket Test Case
 *
 */
class TicketTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.ticket',
		'app.user',
		'app.sub_center',
		'app.site',
		'app.asset_group',
		'app.asset_number',
		'app.tr_class',
		'app.service',
		'app.supplier',
		'app.supplier_category',
		'app.tr_service',
		'app.project',
		'app.sub_center_budget',
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
		$this->Ticket = ClassRegistry::init('Ticket');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Ticket);

		parent::tearDown();
	}

}
