<?php

/**
 * Users Related Routes
 */
Router::connect('/admin', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard'));
Router::connect('/admin/profile', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'profile'));
Router::connect('/admin/user', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'index'));
Router::connect('/admin/user/add', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'add'));
Router::connect('/admin/user/edit/*', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'add'));
Router::connect('/admin/user/details/*', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'view'));

/**
 * Region Define Routes
 */
Router::connect('/admin/region', array('plugin' => 'admin', 'controller' => 'regions', 'action' => 'index'));
Router::connect('/admin/region/add', array('plugin' => 'admin', 'controller' => 'regions', 'action' => 'add'));
Router::connect('/admin/region/edit/*', array('plugin' => 'admin', 'controller' => 'regions', 'action' => 'add'));
Router::connect('/admin/region/details/*', array('plugin' => 'admin', 'controller' => 'regions', 'action' => 'view'));

/**
 * Office Define Routes
 */
Router::connect('/admin/sub_center', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index'));
Router::connect('/admin/sub_center/add', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'add'));
Router::connect('/admin/sub_center/edit/*', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'add'));
Router::connect('/admin/sub_center/details/*', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'view'));

/**
 * Site Define Routes
 */
Router::connect('/admin/site', array('plugin' => 'admin', 'controller' => 'sites', 'action' => 'index'));
Router::connect('/admin/site/add', array('plugin' => 'admin', 'controller' => 'sites', 'action' => 'add'));
Router::connect('/admin/site/edit/*', array('plugin' => 'admin', 'controller' => 'sites', 'action' => 'add'));
Router::connect('/admin/site/details/*', array('plugin' => 'admin', 'controller' => 'sites', 'action' => 'view'));

/**
 * Project Define Routes
 */
Router::connect('/admin/project', array('plugin' => 'admin', 'controller' => 'projects', 'action' => 'index'));
Router::connect('/admin/project/add', array('plugin' => 'admin', 'controller' => 'projects', 'action' => 'add'));
Router::connect('/admin/project/edit/*', array('plugin' => 'admin', 'controller' => 'projects', 'action' => 'add'));
Router::connect('/admin/project/details/*', array('plugin' => 'admin', 'controller' => 'projects', 'action' => 'view'));

/**
 * Asset Group Define Routes
 */
Router::connect('/admin/asset_group', array('plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'index'));
Router::connect('/admin/asset_group/add', array('plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'add'));
Router::connect('/admin/asset_group/edit/*', array('plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'add'));
Router::connect('/admin/asset_group/details/*', array('plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'view'));

/**
 * Asset Number Define Routes
 */
Router::connect('/admin/asset_number', array('plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index'));
Router::connect('/admin/asset_number/add', array('plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'add'));
Router::connect('/admin/asset_number/edit/*', array('plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'add'));
Router::connect('/admin/asset_number/details/*', array('plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'view'));

/**
 * TR Class Define Routes
 */
Router::connect('/admin/tr_class', array('plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index'));
Router::connect('/admin/tr_class/add', array('plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'add'));
Router::connect('/admin/tr_class/edit/*', array('plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'add'));
Router::connect('/admin/tr_class/details/*', array('plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'view'));

/**
 * Supplier Define Routes
 */
Router::connect('/admin/supplier', array('plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index'));
Router::connect('/admin/supplier/add', array('plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'add'));
Router::connect('/admin/supplier/edit/*', array('plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'add'));
Router::connect('/admin/supplier/details/*', array('plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'view'));

/**
 * Supplier Category Define Routes
 */
Router::connect('/admin/supplier_category', array('plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index'));
Router::connect('/admin/supplier_category/add', array('plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'add'));
Router::connect('/admin/supplier_category/edit/*', array('plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'add'));
Router::connect('/admin/supplier_category/details/*', array('plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'view'));

/**
 * Item Define Routes
 */
Router::connect('/admin/item', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'index'));
Router::connect('/admin/item/add', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'add'));
Router::connect('/admin/item/edit/*', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'add'));
Router::connect('/admin/item/details/*', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'view'));
