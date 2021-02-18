<?php
Router::connect( '/report_viewer', array( 'plugin' => 'report_viewer', 'controller' => 'users', 'action' => 'dashboard' ) );
Router::connect( '/report_viewer/profile', array( 'plugin' => 'report_viewer', 'controller' => 'users', 'action' => 'profile' ) );

Router::connect( '/report_viewer/service-report', array( 'plugin' => 'report_viewer', 'controller' => 'reports', 'action' => 'services' ) );
Router::connect( '/report_viewer/ticket-report', array( 'plugin' => 'report_viewer', 'controller' => 'reports', 'action' => 'tickets' ) );