<?php
/**
 * Users Related Routes
 */
Router::connect( '/security', array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/security/profile', array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'profile' ) );

/**
 * Ticket Creation Related Routes
 */
Router::connect( '/security/tr', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ) );
Router::connect( '/security/tr/add', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'add' ) );
Router::connect( '/security/tr/details/*', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'view' ) );
Router::connect( '/security/rejected_tr/details/*', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'rejected_tr_view' ) );