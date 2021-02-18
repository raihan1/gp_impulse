<?php
App::uses('TicketsController', 'Supplier.Controller');

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
		'plugin.supplier.ticket',
		'plugin.supplier.user',
		'plugin.supplier.sub_center',
		'plugin.supplier.site',
		'plugin.supplier.asset_group',
		'plugin.supplier.asset_number',
		'plugin.supplier.tr_class',
		'plugin.supplier.project',
		'plugin.supplier.supplier',
		'plugin.supplier.service',
		'plugin.supplier.tr_services',
		'plugin.supplier.supplier_category',
		'plugin.supplier.tr_service',
		'plugin.supplier.user_token',
		'plugin.supplier.invoice'
	);

}
