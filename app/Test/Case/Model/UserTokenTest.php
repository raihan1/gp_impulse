<?php
App::uses('UserToken', 'Model');

/**
 * UserToken Test Case
 *
 */
class UserTokenTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_token',
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserToken = ClassRegistry::init('UserToken');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserToken);

		parent::tearDown();
	}

}
