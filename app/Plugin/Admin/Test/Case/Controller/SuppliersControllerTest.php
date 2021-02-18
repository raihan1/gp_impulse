<?php
App::uses('SuppliersController', 'Admin.Controller');

/**
 * SuppliersController Test Case
 *
 */
class SuppliersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.admin.supplier',
		'plugin.admin.sub_center',
		'plugin.admin.region',
		'plugin.admin.user',
		'plugin.admin.ticket',
		'plugin.admin.site',
		'plugin.admin.asset_group',
		'plugin.admin.asset_number',
		'plugin.admin.tr_class',
		'plugin.admin.service',
		'plugin.admin.project',
		'plugin.admin.supplier_category',
		'plugin.admin.invoice',
		'plugin.admin.tr_service',
		'plugin.admin.user_token',
		'plugin.admin.sub_center_budget'
	);

}
