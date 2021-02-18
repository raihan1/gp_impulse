<?php
App::uses('UsersController', 'Api.Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.api.user',
		'plugin.api.sub_center',
		'plugin.api.site',
		'plugin.api.asset_group',
		'plugin.api.asset_number',
		'plugin.api.ticket',
		'plugin.api.project',
		'plugin.api.supplier',
		'plugin.api.service',
		'plugin.api.supplier_category',
		'plugin.api.tr_class',
		'plugin.api.invoice',
		'plugin.api.user_token'
	);

}
