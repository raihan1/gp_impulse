<?php
App::uses('SiteType', 'Model');

/**
 * SiteType Test Case
 *
 */
class SiteTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.site_type',
		'app.organization_site',
		'app.organization',
		'app.user_inspection',
		'app.user',
		'app.basic_information',
		'app.vendor',
		'app.completed_inspection_item',
		'app.question',
		'app.saved_inspection_item',
		'app.saved_inspection'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SiteType = ClassRegistry::init('SiteType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SiteType);

		parent::tearDown();
	}

}
