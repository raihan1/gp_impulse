<?php
App::uses('SuppliersController', 'Api.Controller');

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
		'plugin.api.supplier',
		'plugin.api.sub_center',
		'plugin.api.site',
		'plugin.api.asset_group',
		'plugin.api.asset_number',
		'plugin.api.ticket',
		'plugin.api.user',
		'plugin.api.user_token',
		'plugin.api.project',
		'plugin.api.supplier_category',
		'plugin.api.tr_class',
		'plugin.api.invoice',
		'plugin.api.service'
	);

}
