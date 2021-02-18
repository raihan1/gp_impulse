<?php
App::uses('UsersController', 'InvoiceCreation.Controller');

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
		'plugin.invoice_creation.user',
		'plugin.invoice_creation.sub_center',
		'plugin.invoice_creation.site',
		'plugin.invoice_creation.asset_group',
		'plugin.invoice_creation.asset_number',
		'plugin.invoice_creation.ticket',
		'plugin.invoice_creation.project',
		'plugin.invoice_creation.supplier',
		'plugin.invoice_creation.service',
		'plugin.invoice_creation.tr_services',
		'plugin.invoice_creation.supplier_category',
		'plugin.invoice_creation.tr_service',
		'plugin.invoice_creation.tr_class',
		'plugin.invoice_creation.invoice',
		'plugin.invoice_creation.user_token'
	);

}
