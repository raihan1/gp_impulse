<?php
App::uses('SupplierCategory', 'Model');

/**
 * SupplierCategory Test Case
 *
 */
class SupplierCategoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.supplier_category',
		'app.supplier',
		'app.ticket'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SupplierCategory = ClassRegistry::init('SupplierCategory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SupplierCategory);

		parent::tearDown();
	}

}
