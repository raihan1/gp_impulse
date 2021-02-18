<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <title><?php echo PROJECT_TITLE . ( !empty( $title_for_layout ) ? " | {$title_for_layout}" : '' ); ?></title>
    
    <script type="text/javascript">
        var BASEURL     = '<?php echo BASEURL; ?>';
        var RATINGIMAGE = '/selliscope/public_html/images';
        var IMAGEPATH   = '<?php echo BASEURL; ?>img/';
    </script>
    
    <?php
    echo $this->Html->meta( 'icon' );
    echo $this->Html->css( array(
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'common/font-awesome.min',
        'backend/simple-line-icons.min',
        'common/bootstrap.min',
        'public_site/jquery.fancybox',
        'backend/uniform.default',
        'backend/bootstrap-switch.min',
        'backend/dataTables.bootstrap',
        'backend/dataTables.tableTools.min',
        'backend/datepicker',
        'backend/daterangepicker-bs3',
        'backend/bootstrap-fileinput',
        'backend/jquery.tagsinput',
        'backend/select2',
        'backend/multi-select',
        'backend/jquery.raty',
        'backend/bootstrap-select.min',
        'backend/profile',
        'backend/summernote',
        'common/components',
        'backend/plugins',
        'backend/layout',
        'backend/light2',
        'backend/login',
        'backend/custom',
        'backend/style',
    ) );
    
    echo $this->Html->script( 'common/respond.min' );
    
    echo $this->Html->script( array(
        'common/jquery.min',
        'common/jquery-migrate.min',
        'backend/jquery-ui.min',
        'common/bootstrap.min',
        'common/jquery.json-2.4.min',
        'public_site/jquery.fancybox.pack',
        'backend/bootstrap-hover-dropdown.min',
        'backend/jquery.slimscroll.min',
        'backend/jquery.blockui.min',
        'backend/jquery.cokie.min',
        'backend/jquery.uniform.min',
        'backend/bootstrap-switch.min',
        'backend/jquery.dataTables.min',
        'backend/dataTables.tableTools.min',
        'backend/dataTables.bootstrap',
        'backend/moment.min',
        'backend/daterangepicker',
        'backend/bootstrap-datepicker',
        'backend/bootstrap-fileinput',
        'backend/jquery.tagsinput.min',
        'backend/spinner.min',
        'backend/bootstrap-maxlength.min',
        'backend/bootstrap.touchspin.min',
        'backend/jquery.inputmask.bundle.min',
        'backend/jquery.validate.min',
        'backend/additional-methods.min',
        'backend/bootstrap-select.min',
        'backend/select2.min',
        'backend/jquery.multi-select',
        'backend/jquery.raty',
        'backend/circle-progress',
        'backend/jquery.flot.min',
        'backend/jquery.flot.resize',
        'backend/jquery.flot.pie',
        'backend/jquery.flot.tooltip.min',
        'backend/bootbox.min',
        'backend/metronic',
        'backend/datatable',
        'backend/layout',
        'backend/demo',
        'backend/charts-flotcharts',
        'backend/bootstrap-hover-dropdown.min',
        'backend/summernote.min',
        'backend/jquery.validate.bootstrap.popover.min',
        'backend/gp_warranty',
    ) );
    echo $this->fetch( 'meta' );
    echo $this->fetch( 'css' );
    echo $this->fetch( 'script' );
    ?>
    
    <script type="text/javascript">
        $( document ).ready( function() {
            Metronic.init();
            Layout.init();
            Demo.init();
        } );
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

<body class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-footer-fixed page-sidebar-fixed">
    <div class="fancybox-loading" style="display: none"></div>
    <div class="mask" style="display: none"></div>
    
    <?php echo $this->element( 'menu/top-menu' ); ?>
    
    <div class="page-container">
        <?php echo $this->element( 'menu/left-menu' ); ?>
        <?php echo $this->fetch( 'content' ); ?>
    </div>
    
    <div class="page-footer">
        <div class="page-footer-inner" style="color: #FFFFFF">
            <?php echo date( 'Y' ) . ' &copy; ' . $this->Html->link( 'Grameenphone Ltd.', 'http://www.grameenphone.com', array( 'target' => '_blank', 'style' => 'color: #FFFFFF' ) ); ?>
            ALL Rights Reserved.
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
</body>
</html>