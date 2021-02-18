<?php
App::uses('SitesController', 'Api.Controller');

/**
 * SitesController Test Case
 *
 */
class SitesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.api.site',
		'plugin.api.sub_center',
		'plugin.api.supplier',
		'plugin.api.service',
		'plugin.api.supplier_category',
		'plugin.api.ticket',
		'plugin.api.user',
		'plugin.api.user_token',
		'plugin.api.asset_group',
		'plugin.api.asset_number',
		'plugin.api.tr_class',
		'plugin.api.project',
		'plugin.api.invoice'
	);

}
