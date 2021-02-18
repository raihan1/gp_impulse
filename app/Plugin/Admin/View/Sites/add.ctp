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
                    <i class="fa fa-map-marker"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'index' ) ); ?>">Site</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-map-marker"></i>
                    <span><?php echo !empty( $data['Site']['id'] ) ? 'Edit' : 'Add'; ?> Site</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['Site']['id'] ) ? 'Edit' : 'Add'; ?> Site Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Site', array(
                    'id'            => 's-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Site']['id'] ) ? $data['Site']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office</label>
                        <div class="col-md-2 col-sm-2  col-xs-12 form-group">
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
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Site Name</label>
                        <div class="col-md-2 col-sm-2  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'site_name', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'ag-code',
                                'placeholder' => 'Site Name',
                                'value'       => !empty( $data['Site']['site_name'] ) ? $data['Site']['site_name'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status</label>
                        <div class="col-md-2 col-sm-2  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                'class'   => 'form-control required',
                                'id'      => 'status',
                                'value'   => isset( $data['Site']['status'] ) ? $data['Site']['status'] : '',
                            ) );
                            ?>
                        </div>
                    </div>

                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'index' ) ); ?>"
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
    $( document ).ready( function() {
        gp_warranty.select_options( 'sub-center-id' );
        $( '#s-form' ).validate_popover( {popoverPosition: 'top'} );
    } );
</script>