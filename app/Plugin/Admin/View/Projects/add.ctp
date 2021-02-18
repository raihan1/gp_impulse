<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-plane"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'projects', 'action' => 'index' ) ); ?>">Project</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-plane"></i>
                    <span><?php echo !empty( $data['Project']['id'] ) ? 'Edit' : 'Add'; ?> Project</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['Project']['id'] ) ? 'Edit' : 'Add'; ?> Project
                    Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Project', array(
                    'id'            => 'p-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Project']['id'] ) ? $data['Project']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'sub_center_id', array(
                                'options' => $subCenterList,
                                'empty'   => 'Select a Office',
                                'class'   => 'form-control required',
                                'id'      => 'sub-center-id',
                                'value'   => !empty( $data['Site']['sub_center_id'] ) ? $data['Site']['sub_center_id'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Site</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group" id="site-container">
                            <?php
                            echo $this->Form->input( 'site_id', array(
                                //'options' => $siteList,
                                'empty' => 'Select a site',
                                'class' => 'form-control required',
                                'id'    => 'site-id',
                                'value' => !empty( $data['AssetGroup']['site_id'] ) ? $data['AssetGroup']['site_id'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Project Name</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'project_name', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'ag-code',
                                'placeholder' => 'Project Name',
                                'value'       => !empty( $data['Project']['project_name'] ) ? $data['Project']['project_name'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Project IP</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'project_ip', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'ag-code',
                                'placeholder' => 'Project IP',
                                'value'       => !empty( $data['Project']['project_ip'] ) ? $data['Project']['project_ip'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                'class'   => 'form-control required',
                                'id'      => 'status',
                                'value'   => isset( $data['Project']['status'] ) ? $data['Project']['status'] : ACTIVE,
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'projects', 'action' => 'index' ) ); ?>"
                               class="btn red"><i class="fa fa-arrow-left"></i> Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var I = 0;

    $( document ).ready( function() {
        gp_warranty.select_options( 'sub-center-id' );
        gp_warranty.select_options( 'site-id' );
        $( '#p-form' ).validate_popover( {popoverPosition: 'top'} );

        $( '#sub-center-id' ).on( 'change', function() {
            if( I == 1 ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
            }
            I                 = 1;
            var sub_center_id = $( this ).val();
            var site_id       = <?php
                if( !empty( $data['Project']['site_id'] ) ) {
                    echo $data['Project']['site_id'];
                }
                else {
                    echo 0;
                }
                ?>;

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'sites', 'action' => 'siteList' ) ); ?>',
                data       : 'sub_center_id=' + sub_center_id,
                success    : function( data ) {
                    $( '#site-container' ).html( '' );
                    site_options = '<select name="data[Project][site_id]" class="form-control required" id="site-id">'
                        + '<option value="">Select a site</option>';
                    if( data.length > 0 ) {
                        for( var i = 0; i < data.length; i++ ) {
                            var select = '';
                            if( site_id == data[i]['Site']['id'] ) {
                                select = 'selected';
                            }
                            site_options += '<option value="' + data[i]['Site']['id'] + '" ' + select + '>' + data[i]['Site']['site_name'] + '</option>';
                        }
                        site_options += '</select>';
                        $( '#site-container' ).html( site_options );
                    }
                    else {
                        site_options += '</select>';
                        $( '#site-container' ).html( site_options );
                    }
                    gp_warranty.select_options( 'site-id' );
                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );
    } );
</script>
