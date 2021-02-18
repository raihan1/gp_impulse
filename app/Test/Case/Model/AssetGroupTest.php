<?php
App::uses('AssetGroup', 'Model');

/**
 * AssetGroup Test Case
 *
 */
class AssetGroupTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.asset_group',
		'app.site',
		'app.asset_number',
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
		$this->AssetGroup = ClassRegistry::init('AssetGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AssetGroup);

		parent::tearDown();
	}

}
