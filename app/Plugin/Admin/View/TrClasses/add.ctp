<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-cloud"></i>
                    <?php echo $this->Html->link( 'TR Class', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-cloud"></i>
                    <span><?php echo !empty( $data['TrClass']['id'] ) ? 'Edit' : 'Add'; ?> TrClass</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>
                    <?php echo !empty( $data['TrClass']['id'] ) ? 'Edit' : 'Add'; ?> TR Class Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'TrClass', array(
                    'id'            => 'tr-class-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['TrClass']['id'] ) ? $data['TrClass']['id'] : '' ) );
                ?>
                <div class="form-body">
<!--                    <div class="form-group">-->
<!--                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office</label>-->
<!--                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">-->
<!--                            --><?php
//                            echo $this->Form->input( 'sub_center_id', array(
//                                'options' => $subCenterList,
//                                'empty'   => 'Select a Office',
//                                'class'   => 'form-control required',
//                                'id'      => 'sub-center-id',
//                                'value'   => !empty( $data['TrClass']['id'] ) ? $subCenterData : '',
//                            ) );
//                            ?>
<!--                        </div>-->
<!--                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Site</label>-->
<!--                        <div class="col-md-4 col-sm-4  col-xs-12 form-group" id="site-container">-->
<!--                            --><?php
//                            echo $this->Form->input( 'site_id', array(
//                                'empty' => 'Select a site',
//                                'class' => 'form-control required',
//                                'id'    => 'site-id',
//                                'value' => !empty( $data['AssetGroup']['site_id'] ) ? $data['AssetGroup']['site_id'] : '',
//                            ) );
//                            ?>
<!--                        </div>-->
<!---->
<!--                    </div>-->
                    <div class="form-group">
<!--                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Group</label>-->
<!--                        <div class="col-md-4 col-sm-4  col-xs-12 form-group" id="ag-container">-->
<!--                            --><?php
//                            echo $this->Form->input( 'asset_group_id', array(
//                                'empty' => 'Select an asset group',
//                                'class' => 'form-control required',
//                                'id'    => 'asset-group-id',
//                                'value' => !empty( $data['TrClass']['asset_group_id'] ) ? $data['TrClass']['asset_group_id'] : '',
//                            ) );
//                            ?>
<!--                        </div>-->
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR Class Name</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'tr_class_name', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'tr-class-code-id',
                                'placeholder' => 'TR Class Name',
                                'value'       => !empty( $data['TrClass']['tr_class_name'] ) ? $data['TrClass']['tr_class_name'] : '',
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
                                'value'   => isset( $data['TrClass']['status'] ) ? $data['TrClass']['status'] : ACTIVE,
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">SLA</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div class="days-spinner">
                                <div class="input-group">
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-down red">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <?php
                                    echo $this->Form->input( 'no_of_days', array(
                                        'type'        => 'text',
                                        'class'       => 'spinner-input form-control required',
                                        'id'          => 'tr-class-desc-id',
                                        'placeholder' => 'No Of Days',
                                        'value'       => !empty( $data['TrClass']['no_of_days'] ) ? $data['TrClass']['no_of_days'] : '',
                                    ) );
                                    ?>
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-up blue">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
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
        $( '.days-spinner' ).spinner( {value: 0.0, step: 4.0, min: 0.0, max: 365.00} );

        $( '#tr-class-form' ).validate_popover( {popoverPosition: 'top'} );

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
                    site_options = '<select name="data[TrClass][site_id]" class="form-control required" id="site-id">'
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
                if( !empty( $data['TrClass']['asset_group_id'] ) ) {
                    echo $data['TrClass']['asset_group_id'];
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
                    ag_options = '<select name="data[TrClass][asset_group_id]" class="form-control required" id="asset-group-id">'
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