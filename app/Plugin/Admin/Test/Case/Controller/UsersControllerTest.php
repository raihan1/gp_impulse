<?php
App::uses('UsersController', 'Admin.Controller');

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
		'plugin.admin.user',
		'plugin.admin.region',
		'plugin.admin.sub_center',
		'plugin.admin.invoice',
		'plugin.admin.supplier',
		'plugin.admin.service',
		'plugin.admin.tr_class',
		'plugin.admin.asset_group',
		'plugin.admin.site',
		'plugin.admin.project',
		'plugin.admin.ticket',
		'plugin.admin.asset_number',
		'plugin.admin.supplier_category',
		'plugin.admin.tr_service',
		'plugin.admin.sub_center_budget',
		'plugin.admin.user_token'
	);

}
