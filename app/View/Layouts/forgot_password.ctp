<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo PROJECT_TITLE; ?> | <?php echo $this->fetch( 'title' ); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <script type="text/javascript">
        var BASEURL   = '<?php echo BASEURL; ?>';
        var IMAGEPATH = '<?php echo BASEURL; ?>img/';
    </script>
    
    <?php
    echo $this->Html->css( array(
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'common/font-awesome.min',
        'backend/simple-line-icons.min',
        'common/bootstrap.min',
        'backend/uniform.default',
        'backend/login3',
        'common/components',
        'backend/plugins',
        'backend/layout',
        'backend/darkblue',
        'backend/custom',
    ) );
    ?>
    <link rel="shortcut icon" href="favicon.ico" />
    
    <?php
    echo $this->Html->script( array(
        'common/respond.min',
        'common/jquery.min',
        'common/jquery-migrate.min',
        'common/bootstrap.min',
        'backend/jquery.blockui.min',
        'backend/jquery.uniform.min',
        'backend/jquery.validate.min',
        'backend/select2.min',
        'backend/metronic',
        'backend/layout',
        'backend/demo',
        'backend/login',
        'backend/gp_warranty',
    ) );
    ?>
    <script type="text/javascript">
        $( document ).ready( function() {
            Metronic.init();
            Layout.init();
            Demo.init();
            Login.init();
        } );
    </script>
</head>

<body class="login">
    <div class="logo" style="padding: 0;">
        <?php echo $this->Html->image( '/logo/logo.png', array( 'url' => BASEURL ) ); ?>
    </div>
    <div class="menu-toggler sidebar-toggler"></div>
    
    <div class="content">
        <?php echo $this->fetch( 'content' ); ?>
    </div>
    
    <div class="copyright">
        <?php echo date( 'Y' ) . ' &copy; ' . $this->Html->link( 'Grameenphone Ltd.', 'http://www.grameenphone.com', array( 'target' => '_blank' ) ); ?>
    </div>
</body>
</html>