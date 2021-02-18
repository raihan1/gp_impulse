<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <h3 style="float: left; height: 100%; margin: 0px; padding: 15px 0px; color: #FFF; font-size: 16px !important" id="app-name">
                <?php echo PROJECT_TITLE; ?>
            </h3>
            <div class="menu-toggler sidebar-toggler pull-right"></div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php echo $this->Html->image( !empty( $loginUser['User']['photo'] ) && file_exists( WWW_ROOT . "resource/users/profile_photo/{$loginUser['User']['photo']}" ) ? "/resource/users/profile_photo/{$loginUser['User']['photo']}" : '/resource/photo_not_available_200x150.png' ); ?>
                        <span class="username username-hide-on-mobile"></span>
                        <?php echo $loginUser['User']['name']; ?> <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <?php echo $this->Html->link( '<i class="icon-user"></i> My Profile', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'profile' ), array( 'escape' => FALSE ) ); ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link( '<i class="icon-key"></i> Log Out', array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'logout' ), array( 'escape' => FALSE ) ); ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<script type="text/javascript">
    $( document ).ready( function() {
        $( '.menu-toggler' ).on( 'click', function() {
            var is_visible = $( '#app-name' ).is( ':visible' );
            if( is_visible ) {
                $( '#app-name' ).hide();
            }
            else {
                $( '#app-name' ).show();
            }
        } )
    } );
</script>