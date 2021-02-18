<?php
Router::connect( '/tr_validator', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/tr_validator/profile', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'profile' ) );

Router::connect( '/tr_validator/tickets', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/tr_validator/add-ticket', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'add' ) );
Router::connect( '/tr_validator/edit-ticket-:ticketId', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'add' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );
Router::connect( '/tr_validator/view-ticket-:ticketId', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );

Router::connect( '/tr_validator/service-report', array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'services' ) );
Router::connect( '/tr_validator/ticket-report', array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'tickets' ) );