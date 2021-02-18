<?php
Router::connect( '/api/sign_in', array( 'plugin' => 'api', 'controller' => 'users', 'action' => 'sign_in' ) );
Router::connect( '/api/sign_out', array( 'plugin' => 'api', 'controller' => 'users', 'action' => 'sign_out' ) );
Router::connect( '/api/forgot_password', array( 'plugin' => 'api', 'controller' => 'users', 'action' => 'forgot_password' ) );
Router::connect( '/api/reset_password', array( 'plugin' => 'api', 'controller' => 'users', 'action' => 'reset_password' ) );

Router::connect( '/api/sub_center', array( 'plugin' => 'api', 'controller' => 'lookup', 'action' => 'sub_center_list' ) );
Router::connect( '/api/site', array( 'plugin' => 'api', 'controller' => 'lookup', 'action' => 'site_list' ) );
Router::connect( '/api/supplier', array( 'plugin' => 'api', 'controller' => 'lookup', 'action' => 'supplier_list' ) );
Router::connect( '/api/item_list', array( 'plugin' => 'api', 'controller' => 'lookup', 'action' => 'service_list' ) );

Router::connect( '/api/tr_list', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'tr_list' ) );
Router::connect( '/api/tr_create', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'tr_create' ) );
Router::connect( '/api/delete_tr', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'delete_tr' ) );
Router::connect( '/api/update_status', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'status_change' ) );
Router::connect( '/api/add_service', array( 'plugin' => 'api', 'controller' => 'services', 'action' => 'add_service' ) );
Router::connect( '/api/validation_tr_list', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'tr_list_for_validation' ) );
Router::connect( '/api/validate_tr', array( 'plugin' => 'api', 'controller' => 'tickets', 'action' => 'validate_tr' ) );