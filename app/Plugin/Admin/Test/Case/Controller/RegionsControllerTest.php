<?php
App::uses('RegionsController', 'Admin.Controller');

/**
 * RegionsController Test Case
 *
 */
class RegionsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'plugin.admin.user',
		'plugin.admin.user_token',
		'plugin.admin.asset_number',
		'plugin.admin.supplier_category',
		'plugin.admin.tr_service',
		'plugin.admin.sub_center_budget'
	);

}
