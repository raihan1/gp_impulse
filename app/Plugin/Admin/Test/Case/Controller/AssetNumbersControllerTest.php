<?php
App::uses('AssetNumbersController', 'Admin.Controller');

/**
 * AssetNumbersController Test Case
 *
 */
class AssetNumbersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.admin.asset_number',
		'plugin.admin.asset_group',
		'plugin.admin.site',
		'plugin.admin.sub_center',
		'plugin.admin.region',
		'plugin.admin.user',
		'plugin.admin.supplier',
		'plugin.admin.service',
		'plugin.admin.tr_class',
		'plugin.admin.ticket',
		'plugin.admin.project',
		'plugin.admin.supplier_category',
		'plugin.admin.invoice',
		'plugin.admin.tr_service',
		'plugin.admin.user_token',
		'plugin.admin.sub_center_budget'
	);

}
