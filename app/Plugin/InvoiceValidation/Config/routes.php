<?php
Router::connect( '/invoice_validator', array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/invoice_validator/profile', array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'profile' ) );

Router::connect( '/invoice_validator/tickets', array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/invoice_validator/view-ticket-:ticketId', array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'view' ), array( 'ticketId' => '[0-9]+', 'pass' => array( 'ticketId' ) ) );

Router::connect( '/invoice_validator/invoices', array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'index' ) );
Router::connect( '/invoice_validator/view-invoice-:invoiceId', array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'view' ), array( 'invoiceId' => '[0-9]+', 'pass' => array( 'invoiceId' ) ) );