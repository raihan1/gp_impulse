<?php
App::uses('AssetNumber', 'Model');

/**
 * AssetNumber Test Case
 *
 */
class AssetNumberTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.asset_number',
		'app.asset_group',
		'app.site',
		'app.ticket',
		'app.tr_class'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->AssetNumber = ClassRegistry::init('AssetNumber');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AssetNumber);

		parent::tearDown();
	}

}
