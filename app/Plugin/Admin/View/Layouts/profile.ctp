<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="" />
        <meta name="author" content="" />

        <title><?php echo PROJECT_TITLE; ?> | <?php echo $this->fetch('title'); ?></title>

        <script type="text/javascript">
            var BASEURL = '<?php echo BASEURL; ?>';
            var IMAGEPATH = '<?php echo BASEURL; ?>img/';
        </script>

        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->css(array(
            'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
            'common/font-awesome.min',
            'backend/simple-line-icons.min',
            'common/bootstrap.min',
            'backend/uniform.default',
            'backend/bootstrap-switch.min',
            'backend/dataTables.bootstrap',
            'backend/datepicker',
            'backend/bootstrap-fileinput',
            'backend/select2',
            'backend/bootstrap-select.min',
            'backend/profile',
            'common/components',
            'backend/plugins',
            'backend/layout',
            'backend/light2',
            'backend/custom',
        ));

        echo $this->Html->script('common/respond.min');
        
        echo $this->Html->script(array(
            'common/jquery.min',
            'common/jquery-migrate.min',
            'backend/jquery-ui.min',
            'common/bootstrap.min',
            'backend/bootstrap-hover-dropdown.min',
            'backend/jquery.slimscroll.min',
            'backend/jquery.blockui.min',
            'backend/jquery.cokie.min',
            'backend/jquery.uniform.min',
            'backend/bootstrap-switch.min',
            'backend/jquery.dataTables.min',
            'backend/dataTables.bootstrap',
            'backend/bootstrap-datepicker',
            'backend/bootstrap-fileinput',
            'backend/jquery.inputmask.bundle.min',
            'backend/jquery.validate.min',
            'backend/additional-methods.min',
            'backend/bootstrap-select.min',
            'backend/select2.min',
            'backend/bootbox.min',
            'backend/metronic',
            'backend/datatable',
            'backend/layout',
            'backend/demo',
            'backend/jquery.validate.bootstrap.popover.min'
        ));
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                Demo.init(); // init demo features 
            });
        </script>
        
        <style type="text/css">
            @media all and (max-width: 767px) {
                .form-group {
                    margin-right: 0px !important; 
                    margin-left: 0px !important; 
                }
            }
        </style>
    </head>
    <body class="page-header-fixed page-quick-sidebar-over-content page-full-width page-footer-fixed page-container-bg-solid">
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner">
                <div class="page-logo">
                    <?php
//                    echo $this->Html->image('/logo/logo-selliscope.png', array(
//                        'alt' => 'LOGO',
//                        'class' => 'logo-default',
//                        'url' => array('plugin' => true, 'controller' => 'users', 'action' => 'dashboard'),
//                        'style' => 'height: 46px'
//                    ));
                    ?>
                    <h3 style="float: left; height: 100%; margin: 0px; padding: 15px 0px; color: #FFF; font-size: 16px !important" id="app-name">GP SCS Inspection</h3>
                </div>
                <?php echo $this->Html->link('', 'javascript:;', array('class' => 'menu-toggler responsive-toggler', 'data-toggle' => 'collapse', 'data-target' => '.navbar-collapse')); ?>
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <?php
                                $photo = !empty($loginUser['User']['photo']) && file_exists(WWW_ROOT . "resource/users/profile_photo/{$loginUser['User']['photo']}") ? "/resource/users/profile_photo/{$loginUser['User']['photo']}" : '/resource/photo_not_available_200x150.png';
                                echo $this->Html->image($photo);
                                ?>
                                <span class="username username-hide-on-mobile"><?php echo $loginUser['User']['name']; ?></span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-home"></i> Dashboard', array('plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard'), array('escape' => FALSE)); ?>
                                </li>
                                <li class="divider"><li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-key"></i> Log Out', array('plugin' => FALSE, 'controller' => 'users', 'action' => 'logout'), array('escape' => FALSE)); ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="page-container">
            <div class="page-content-wrapper">
                <div class="page-content">
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>

        <div class="page-footer">
            <div class="page-footer-inner" style="color: #FFFFFF">
                <?php echo date('Y'); ?> &copy; <a href="http://www.grameenphone.com" target="_blank" style="color: #FFFFFF">Grameenphone.</a> ALL Rights Reserved.
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
    </body>
</html>