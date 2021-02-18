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
                    <i class="fa fa-user"></i>
                    <?php echo $this->Html->link( 'User', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-user"></i>
                    <span><?php echo !empty( $data['User']['id'] ) ? 'Edit' : 'Add'; ?> User</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['User']['id'] ) ? 'Edit' : 'Add'; ?> User Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'User', array(
                    'id'            => 'formUser',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['User']['id'] ) ? $data['User']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">User Role</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'role', array(
                                        'options' => array(
                                            array( 'name' => 'TR Creator', 'value' => TR_CREATOR ),
                                            array( 'name' => 'TR Creator (SS)', 'value' => SECURITY ),
                                            array( 'name' => 'TR Validator', 'value' => TR_VALIDATOR ),
                                            array( 'name' => 'Supplier', 'value' => SUPPLIER ),
                                            array( 'name' => 'Invoice Creator', 'value' => INVOICE_CREATOR ),
                                            array( 'name' => 'Invoice Validator', 'value' => INVOICE_VALIDATOR ),
                                        ),
                                        'empty'   => 'Please select user type',
                                        'class'   => 'form-control required',
                                        'id'      => 'role',
                                        'value'   => !empty( $data['User']['role'] ) ? $data['User']['role'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" id="supplier_container">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Supplier</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'supplier_id', array(
                                        'options'     => $supplierList,
                                        'empty'       => 'Please select supplier',
                                        'class'       => 'form-control required',
                                        'id'          => 'supplierId',
                                        'placeholder' => 'Supplier',
                                        'value'       => !empty( $data['User']['supplier_id'] ) ? $data['User']['supplier_id'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" id="region_container">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Region</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'region_id', array(
                                        'options'     => $regionList,
                                        'empty'       => 'Please select a region',
                                        'class'       => 'form-control required',
                                        'id'          => 'regionId',
                                        'placeholder' => 'Region',
                                        'value'       => !empty( $data['User']['region_id'] ) ? $data['User']['region_id'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" id="subcenter_container">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Office</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'sub_center_id', array(
                                        'options'     => $subCenterList,
                                        'empty'       => 'Please select a Office',
                                        'class'       => 'form-control required',
                                        'id'          => 'subCenterId',
                                        'placeholder' => 'Office',
                                        'value'       => !empty( $data['User']['sub_center_id'] ) ? $data['User']['sub_center_id'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group" id="department_container">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Department</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'department', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'departmentId',
                                        'placeholder' => 'Department',
                                        'value'       => !empty( $data['User']['department'] ) ? $data['User']['department'] : '',
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
                                        'value'   => isset( $data['User']['status'] ) ? $data['User']['status'] : ACTIVE,
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'name', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'name',
                                        'placeholder' => 'Name',
                                        'value'       => !empty( $data['User']['name'] ) ? $data['User']['name'] : '',
                                    ) );
                                    ?>
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
                                        'value'       => !empty( $data['User']['phone'] ) ? $data['User']['phone'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Email</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'email', array(
                                        'type'        => 'email',
                                        'class'       => 'form-control required',
                                        'id'          => 'email',
                                        'placeholder' => 'Email',
                                        'value'       => !empty( $data['User']['email'] ) ? $data['User']['email'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Password</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'password', array(
                                        'type'        => 'password',
                                        'class'       => empty( $data['User']['id'] ) ? 'form-control required' : 'form-control',
                                        'id'          => 'password',
                                        'placeholder' => 'Password',
                                        'value'       => '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Address</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'address', array(
                                        'type'        => 'textarea',
                                        'class'       => 'form-control',
                                        'id'          => 'address',
                                        'placeholder' => 'Address',
                                        'value'       => !empty( $data['User']['address'] ) ? $data['User']['address'] : '',
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions right">
                    <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    
    </div>
</div>

<script type="text/javascript">
    var sub_center_id = <?php echo !empty( $data['User']['sub_center_id'] ) ? $data['User']['sub_center_id'] : 0; ?>;
    
    $( document ).ready( function() {
        gp_warranty.select_options( 'role' );
        gp_warranty.select_options( 'supplierId' );
        gp_warranty.select_options( 'regionId' );
        gp_warranty.select_options( 'subCenterId' );
        
        $( '#role' ).on( 'change', function() {
            if( $( this ).val() == 2 ) {
                $( '#supplier_container' ).show();
                $( '#department_container, #region_container, #subcenter_container' ).hide();
            }
            else if( $( this ).val() == 3 || $( this ).val() == 4 ) {
                $( '#region_container, #subcenter_container, #department_container' ).show();
                $( '#supplier_container' ).hide();
            }
            else if( $( this ).val() == 5 ) {
                $( '#region_container, #department_container' ).show();
                $( '#supplier_container, #subcenter_container' ).hide();
            }
            else if( $( this ).val() == 6 || $( this ).val() == 7 ) {
                $( '#department_container' ).show();
                $( '#supplier_container, #region_container, #subcenter_container' ).hide();
            }
            else {
                $( '#supplier_container, #region_container, #subcenter_container, #department_container' ).show();
            }
        } ).trigger( 'change' );
        
        $( '#formUser #regionId' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();
            
            $.ajax( {
                type       : 'POST',
                url        : '<?php echo Router::url( array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'get_sub_center_list' ) ); ?>/' + $( this ).val(),
                dataType   : 'json',
                success    : function( data ) {
                    $( '#subcenter_container .form-group' ).html( '' );
                    var subcenter_options = '<select name="data[User][sub_center_id]" class="form-control required" id="subCenterId"><option value="">Please select a Office</option>'
                    $.each( data, function( id, value ) {
                        subcenter_options += '<option value="' + id + '"' + ( id == sub_center_id ? ' selected' : '' ) + '>' + value + '</option>';
                    } );
                    subcenter_options += '</select>';
                    $( '#subcenter_container .form-group' ).html( subcenter_options );
                    gp_warranty.select_options( 'subCenterId' );
            
                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );
        
        $( '#formUser' ).validate_popover( {
            popoverPosition: 'top',
            beforeShowError: function() {
                setTimeout( function() {
                    $( '.required' ).each( function() {
                        $.validator.hide_validate_popover( $( '#role' ) );
                        $.validator.hide_validate_popover( $( '#supplierId' ) );
                        $.validator.hide_validate_popover( $( '#regionId' ) );
                        $.validator.hide_validate_popover( $( '#subCenterId' ) );
                        $.validator.hide_validate_popover( $( '#departmentId' ) );
                        $.validator.hide_validate_popover( $( '#name' ) );
                        $.validator.hide_validate_popover( $( '#phone' ) );
                        $.validator.hide_validate_popover( $( '#email' ) );
                        $.validator.hide_validate_popover( $( '#password' ) );
                        $.validator.hide_validate_popover( $( '#address' ) );
                    } );
                }, 3000 );
            }
        } );
    } );
</script>