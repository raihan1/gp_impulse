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
                    <i class="fa fa-adjust"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index' ) ); ?>">Supplier</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <span><?php echo !empty( $data['Supplier']['id'] ) ? 'Edit' : 'Add'; ?> Supplier</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['Supplier']['id'] ) ? 'Edit' : 'Add'; ?> Supplier
                    Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Supplier', array(
                    'id'            => 'supplier-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Supplier']['id'] ) ? $data['Supplier']['id'] : '' ) );
                echo $this->Session->flash();
                ?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Supplier Code</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'code', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'code',
                                        'placeholder' => 'Code',
                                        'value'       => !empty( $data['Supplier']['code'] ) ? $data['Supplier']['code'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Supplier Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'name', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'name',
                                        'placeholder' => 'Name',
                                        'value'       => !empty( $data['Supplier']['name'] ) ? $data['Supplier']['name'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Email</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'email', array(
                                        'type'        => 'textarea',
                                        'class'       => 'form-control required',
                                        'id'          => 'email',
                                        'placeholder' => 'Email',
                                        'value'       => !empty( $data['Supplier']['email'] ) ? $data['Supplier']['email'] : '',
                                    ) );
                                    ?>
                                    <span class="help-block">Email should be comma (,) separated</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Phone</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'phone', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'phone',
                                        'placeholder' => 'Phone',
                                        'value'       => !empty( $data['Supplier']['phone'] ) ? $data['Supplier']['phone'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Address</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'address', array(
                                        'type'        => 'textarea',
                                        'class'       => 'form-control',
                                        'id'          => 'address',
                                        'placeholder' => 'Address',
                                        'value'       => !empty( $data['Supplier']['address'] ) ? $data['Supplier']['address'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Remarks</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'remarks', array(
                                        'type'        => 'textarea',
                                        'class'       => 'form-control',
                                        'id'          => 'remarks',
                                        'placeholder' => 'Remarks',
                                        'value'       => !empty( $data['Supplier']['remarks'] ) ? $data['Supplier']['remarks'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Status</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'status', array(
                                        'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                        'class'   => 'form-control required',
                                        'id'      => 'status',
                                        'value'   => isset( $data['Supplier']['status'] ) ? $data['Supplier']['status'] : ACTIVE,
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index' ) ); ?>"
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
        $( '#supplier-form' ).validate_popover( {popoverPosition: 'top'} );
    } );
</script>
