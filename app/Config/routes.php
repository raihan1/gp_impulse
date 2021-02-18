<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
//Router::connect('/', array('controller' => 'scripts', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
//Router::connect('/scripts/*', array('controller' => 'scripts', 'action' => 'display'));

Router::connect( '/', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'login' ) );
Router::connect( '/ISRMS', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'login' ) );
Router::connect( '/forgot_password', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'forgot_password' ) );
Router::connect( '/logout', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'logout' ) );

Router::connect( '/dashboard', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'dashboard' ) );

Router::connect( '/cron_subcenter_budget/*', array( 'plugin' => FALSE, 'controller' => 'cron_job', 'action' => 'subcenter_budget' ) );

Router::connect( '/check_sms/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'check_sms' ) );
Router::connect( '/recalculate_budget/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'recalculate_budget' ) );
Router::connect( '/fix_created_date/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_created_date' ) );
Router::connect( '/forward_budget/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'forward_budget' ) );
Router::connect( '/fix_invoice_id/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_invoice_id' ) );
Router::connect( '/fix_invoice_total/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_invoice_total' ) );
Router::connect( '/fix_warranty/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_warranty' ) );
Router::connect( '/fix_all_created_date/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_all_created_date' ) );
Router::connect( '/zero_quantity_report/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'zero_quantity_report' ) );
Router::connect( '/fix_zero_quantity/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_zero_quantity' ) );

Router::connect( '/update_tickets/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_tickets' ) );
Router::connect( '/update_services/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_services' ) );
Router::connect( '/update_tr_services/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_tr_services' ) );
Router::connect( '/fix_DVSRM3/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_DVSRM3' ) );
Router::connect( '/update_ticket_price/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_ticket_price' ) );
Router::connect( '/update_invoices/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_invoices' ) );
Router::connect( '/fix_budget/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_budget' ) );
Router::connect( '/update_ticket_sites/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_ticket_sites' ) );
Router::connect( '/update_service_supplier/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_service_supplier' ) );
Router::connect( '/update_tr_services_fair/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'update_tr_services_fair' ) );
Router::connect( '/remove_duplicate_service/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'remove_duplicate_service' ) );
Router::connect( '/move_site_tickets/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'move_site_tickets' ) );
Router::connect( '/fix_last_service_id/*', array( 'plugin' => FALSE, 'controller' => 'scripts', 'action' => 'fix_last_service_id' ) );

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';

define( 'BASEURL', Router::url( '/', TRUE ) );