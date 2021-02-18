<?php
App::uses('ServicesController', 'Admin.Controller');

/**
 * ServicesController Test Case
 *
 */
class ServicesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.admin.service',
		'plugin.admin.supplier',
		'plugin.admin.sub_center',
		'plugin.admin.region',
		'plugin.admin.user',
		'plugin.admin.ticket',
		'plugin.admin.site',
		'plugin.admin.asset_group',
		'plugin.admin.asset_number',
		'plugin.admin.tr_class',
		'plugin.admin.project',
		'plugin.admin.supplier_category',
		'plugin.admin.invoice',
		'plugin.admin.tr_service',
		'plugin.admin.user_token',
		'plugin.admin.sub_center_budget'
	);

}
