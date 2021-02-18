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
                    <i class="fa fa-list"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index' ) ); ?>">Supplier
                        Category</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-list"></i>
                    <span><?php echo !empty( $data['SupplierCategory']['id'] ) ? 'Edit' : 'Add'; ?>
                        Supplier Category</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['SupplierCategory']['id'] ) ? 'Edit' : 'Add'; ?>
                    Supplier Category Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'SupplierCategory', array(
                    'id'            => 'supplier-category-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['SupplierCategory']['id'] ) ? $data['SupplierCategory']['id'] : '' ) );
                echo $this->Session->flash();
                ?>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Supplier List</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'supplier_id', array(
                                        'options' => $supplierList,
                                        'empty'   => 'Please select supplier',
                                        'class'   => 'form-control required bs-select',
                                        'id'      => 'supplier-id',
                                        'value'   => !empty( $data['SupplierCategory']['supplier_id'] ) ? $data['SupplierCategory']['supplier_id'] : '',
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
                                        'value'   => isset( $data['SupplierCategory']['status'] ) ? $data['SupplierCategory']['status'] : ACTIVE,
                                    ) );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">Category Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'category_name', array(
                                        'type'        => 'text',
                                        'class'       => 'form-control required',
                                        'id'          => 'name',
                                        'placeholder' => 'Name',
                                        'value'       => !empty( $data['SupplierCategory']['category_name'] ) ? $data['SupplierCategory']['category_name'] : '',
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
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index' ) ); ?>"
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
        gp_warranty.select_options( 'supplier-id' );
        $( '#supplier-category-form' ).validate_popover( {popoverPosition: 'top'} );
    } );
</script>
