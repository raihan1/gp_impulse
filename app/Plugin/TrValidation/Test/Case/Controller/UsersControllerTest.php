<?php
App::uses('UsersController', 'TrValidation.Controller');

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
		'plugin.tr_validation.user',
		'plugin.tr_validation.sub_center',
		'plugin.tr_validation.site',
		'plugin.tr_validation.asset_group',
		'plugin.tr_validation.asset_number',
		'plugin.tr_validation.ticket',
		'plugin.tr_validation.project',
		'plugin.tr_validation.supplier',
		'plugin.tr_validation.service',
		'plugin.tr_validation.tr_services',
		'plugin.tr_validation.supplier_category',
		'plugin.tr_validation.tr_service',
		'plugin.tr_validation.tr_class',
		'plugin.tr_validation.invoice',
		'plugin.tr_validation.user_token'
	);

}
