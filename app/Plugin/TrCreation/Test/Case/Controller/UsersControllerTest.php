<?php
App::uses('UsersController', 'TrCreation.Controller');

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
		'plugin.tr_creation.user',
		'plugin.tr_creation.sub_center',
		'plugin.tr_creation.site',
		'plugin.tr_creation.asset_group',
		'plugin.tr_creation.asset_number',
		'plugin.tr_creation.ticket',
		'plugin.tr_creation.project',
		'plugin.tr_creation.supplier',
		'plugin.tr_creation.service',
		'plugin.tr_creation.tr_services',
		'plugin.tr_creation.supplier_category',
		'plugin.tr_creation.tr_service',
		'plugin.tr_creation.tr_class',
		'plugin.tr_creation.invoice',
		'plugin.tr_creation.user_token'
	);

}
