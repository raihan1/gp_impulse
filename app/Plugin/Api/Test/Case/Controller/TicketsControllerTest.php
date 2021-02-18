<?php
App::uses('TicketsController', 'Api.Controller');

/**
 * TicketsController Test Case
 *
 */
class TicketsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.api.ticket',
		'plugin.api.user',
		'plugin.api.sub_center',
		'plugin.api.site',
		'plugin.api.asset_group',
		'plugin.api.asset_number',
		'plugin.api.tr_class',
		'plugin.api.project',
		'plugin.api.supplier',
		'plugin.api.service',
		'plugin.api.supplier_category',
		'plugin.api.user_token',
		'plugin.api.invoice'
	);

}
