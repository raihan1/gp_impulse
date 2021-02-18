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
                    <i class="fa fa-sort-numeric-asc"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index' ) ); ?>">Asset
                        Number</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-sort-numeric-asc"></i>
                    <span><?php echo !empty( $data['AssetNumber']['id'] ) ? 'Edit' : 'Add'; ?> Asset Number</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['AssetNumber']['id'] ) ? 'Edit' : 'Add'; ?> Asset
                    Number Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'AssetNumber', array(
                    'id'            => 'an-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['AssetNumber']['id'] ) ? $data['AssetNumber']['id'] : '' ) );
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
                                'value'   => !empty( $data['AssetNumber']['id'] ) ? $subCenterData : '',
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
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Group</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group" id="ag-container">
                            <?php
                            echo $this->Form->input( 'asset_group_id', array(
                                //'options' => $siteList,
                                'empty' => 'Select an asset group',
                                'class' => 'form-control required',
                                'id'    => 'asset-group-id',
                                'value' => !empty( $data['AssetNumber']['asset_group_id'] ) ? $data['AssetNumber']['asset_group_id'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Number</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'asset_number', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'asset-number-id',
                                'placeholder' => 'Asset Number',
                                'value'       => !empty( $data['AssetNumber']['asset_number'] ) ? $data['AssetNumber']['asset_number'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Number Description</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'asset_number_desc', array(
                                'type'        => 'textarea',
                                'class'       => 'form-control required',
                                'id'          => 'asset-number-desc-id',
                                'placeholder' => 'Asset Number Desc',
                                'value'       => !empty( $data['AssetNumber']['asset_number_desc'] ) ? $data['AssetNumber']['asset_number_desc'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                'class'   => 'form-control required',
                                'id'      => 'status',
                                'value'   => isset( $data['AssetNumber']['status'] ) ? $data['AssetNumber']['status'] : ACTIVE,
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index' ) ); ?>"
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
    var J = 0;

    $( document ).ready( function() {
        gp_warranty.select_options( 'sub-center-id' );
        gp_warranty.select_options( 'site-id' );
        gp_warranty.select_options( 'asset-group-id' );
        $( '#an-form' ).validate_popover( {popoverPosition: 'top'} );

        $( '#sub-center-id' ).on( 'change', function() {
            if( I == 1 ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
            }
            I                 = 1;
            var sub_center_id = $( this ).val();
            var site_id       = <?php
                if( !empty( $data['AssetGroup']['site_id'] ) ) {
                    echo $data['AssetGroup']['site_id'];
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
                    site_options = '<select name="data[AssetNumber][site_id]" class="form-control required" id="site-id">'
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
                    $( '#site-id' ).trigger( 'change' );
                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );

        $( document ).on( 'change', '#site-id', function() {
            if( J == 1 ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
            }
            J                  = 1;
            var site_id        = $( this ).val();
            var asset_group_id = <?php
                if( !empty( $data['AssetNumber']['asset_group_id'] ) ) {
                    echo $data['AssetNumber']['asset_group_id'];
                }
                else {
                    echo 0;
                }
                ?>;

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'asset_groups', 'action' => 'assetGroupList' ) ); ?>',
                data       : 'site_id=' + site_id,
                success    : function( data ) {
                    $( '#ag-container' ).html( '' );
                    ag_options = '<select name="data[AssetNumber][asset_group_id]" class="form-control required" id="asset-group-id">'
                        + '<option value="">Select an asset group</option>';
                    if( data.length > 0 ) {
                        for( var i = 0; i < data.length; i++ ) {
                            var select = '';

                            if( asset_group_id == data[i]['AssetGroup']['id'] ) {
                                select = 'selected';
                            }
                            ag_options += '<option value="' + data[i]['AssetGroup']['id'] + '" ' + select + '>' + data[i]['AssetGroup']['asset_group_name'] + '</option>';
                        }
                        ag_options += '</select>';
                        $( '#ag-container' ).html( ag_options );
                    }
                    else {
                        ag_options += '</select>';
                        $( '#ag-container' ).html( ag_options );
                    }
                    gp_warranty.select_options( 'asset-group-id' );
                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } );

    } );
</script>
