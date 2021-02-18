<?php
Router::connect( '/supplier', array( 'plugin' => 'supplier', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/supplier/profile', array( 'plugin' => 'supplier', 'controller' => 'users', 'action' => 'profile' ) );

Router::connect( '/supplier/tickets', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/supplier/add-service-:ticketId', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'add' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );
Router::connect( '/supplier/view-ticket-:ticketId', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'view' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );