<?php
/**
 * Users Related Routes
 */
Router::connect( '/invoice_creator', array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/invoice_creator/profile', array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'profile' ) );

/**
 * Ticket Creation Related Routes
 */
Router::connect( '/invoice_creator/tr', array( 'plugin' => 'invoice_creation', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/invoice_creator/tr/details/*', array( 'plugin' => 'invoice_creation', 'controller' => 'tickets', 'action' => 'view' ) );
Router::connect( '/invoice_creator/rejected_tr/details/*', array( 'plugin' => 'invoice_creation', 'controller' => 'tickets', 'action' => 'rejected_tr_view' ) );

/**
 * Invoice Related Routes
 */
Router::connect( '/invoice_creator/invoice', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ) );
Router::connect( '/invoice_creator/create_invoice', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'add' ) );