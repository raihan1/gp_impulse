<?php
App::uses('TicketsController', 'InvoiceValidation.Controller');

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
		'plugin.invoice_validation.ticket',
		'plugin.invoice_validation.user',
		'plugin.invoice_validation.sub_center',
		'plugin.invoice_validation.site',
		'plugin.invoice_validation.asset_group',
		'plugin.invoice_validation.asset_number',
		'plugin.invoice_validation.tr_class',
		'plugin.invoice_validation.service',
		'plugin.invoice_validation.supplier',
		'plugin.invoice_validation.supplier_category',
		'plugin.invoice_validation.tr_service',
		'plugin.invoice_validation.project',
		'plugin.invoice_validation.sub_center_budget',
		'plugin.invoice_validation.user_token',
		'plugin.invoice_validation.invoice'
	);

}
