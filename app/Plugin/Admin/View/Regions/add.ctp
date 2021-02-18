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
                    <i class="fa fa-globe"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'index' ) ); ?>">Region</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-globe"></i>
                    <span><?php echo !empty( $data['Region']['id'] ) ? 'Edit' : 'Add'; ?> Region</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['Region']['id'] ) ? 'Edit' : 'Add'; ?> Region
                    Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Region', array(
                    'id'            => 'region-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Region']['id'] ) ? $data['Region']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Region Name</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'region_name', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'region-name',
                                'placeholder' => 'Region Name',
                                'value'       => !empty( $data['Region']['region_name'] ) ? $data['Region']['region_name'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array( ACTIVE => 'Active', INACTIVE => 'Inactive' ),
                                'class'   => 'form-control required',
                                'id'      => 'status',
                                'value'   => isset( $data['Region']['status'] ) ? $data['Region']['status'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'index' ) ); ?>" class="btn red"><i class="fa fa-arrow-left"></i> Cancel</a>
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
        $( '#region-form' ).validate_popover( {popoverPosition: 'top'} );
    } );
</script>
