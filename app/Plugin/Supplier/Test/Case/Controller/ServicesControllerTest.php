<?php
App::uses('ServicesController', 'Supplier.Controller');

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
		'plugin.supplier.service',
		'plugin.supplier.supplier',
		'plugin.supplier.sub_center',
		'plugin.supplier.site',
		'plugin.supplier.asset_group',
		'plugin.supplier.asset_number',
		'plugin.supplier.ticket',
		'plugin.supplier.user',
		'plugin.supplier.user_token',
		'plugin.supplier.project',
		'plugin.supplier.supplier_category',
		'plugin.supplier.tr_class',
		'plugin.supplier.invoice',
		'plugin.supplier.tr_service',
		'plugin.supplier.tr_services'
	);

}
