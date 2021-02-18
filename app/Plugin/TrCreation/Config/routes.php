<?php
Router::connect( '/tr_creator', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/tr_creator/profile', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'profile' ) );

Router::connect( '/tr_creator/tickets', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/tr_creator/add-ticket', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'add' ) );
Router::connect( '/tr_creator/edit-ticket-:ticketId', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'add' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );
Router::connect( '/tr_creator/view-ticket-:ticketId', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'view' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );

Router::connect( '/tr_creator/service-report', array( 'plugin' => 'tr_creation', 'controller' => 'reports', 'action' => 'services' ) );
Router::connect( '/tr_creator/ticket-report', array( 'plugin' => 'tr_creation', 'controller' => 'reports', 'action' => 'tickets' ) );