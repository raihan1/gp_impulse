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
                    <i class="fa fa-adjust"></i>
                    <?php echo $this->Html->link( 'Item', array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <span><?php echo !empty( $data['Service']['id'] ) ? 'Edit' : 'Add'; ?> Item</span>
                </li>
            </ul>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['Site']['id'] ) ? 'Edit' : 'Add'; ?> Item Form
                </div>
            </div>
            
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Service', array(
                    'id'            => 'service-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Service']['id'] ) ? $data['Service']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Supplier</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'supplier_id', array(
                                'options' => $supplierList,
                                'empty'   => 'Select a supplier',
                                'class'   => 'form-control required',
                                'id'      => 'supplier-id',
                                'value'   => !empty( $data['Service']['supplier_id'] ) ? $data['Service']['supplier_id'] : '',
                            ) );
                            ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Item Name</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'service_name', array(
                                'type'        => 'text',
                                'class'       => 'form-control required',
                                'id'          => 'item-name',
                                'placeholder' => 'Item Name',
                                'value'       => !empty( $data['Service']['service_name'] ) ? $data['Service']['service_name'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Unit Price</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div class="unit-price-spinner">
                                <div class="input-group">
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-down red"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <?php
                                    echo $this->Form->input( 'service_unit_price', array(
                                        'type'        => 'text',
                                        'class'       => 'spinner-input form-control required',
                                        'id'          => 'unit-price',
                                        'placeholder' => 'Unit Price',
                                        'maxlength'   => '20',
                                        'value'       => !empty( $data['Service']['service_unit_price'] ) ? $data['Service']['service_unit_price'] : '',
                                    ) );
                                    ?>
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-up blue"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Vat</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div class="vat-spinner">
                                <div class="input-group">
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-down red"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <?php
                                    echo $this->Form->input( 'vat', array(
                                        'type'        => 'text',
                                        'class'       => 'spinner-input form-control required',
                                        'id'          => 'vat',
                                        'placeholder' => 'Vat',
                                        'maxlength'   => '20',
                                        'value'       => !empty( $data['Service']['vat'] ) ? $data['Service']['vat'] : '',
                                    ) );
                                    ?>
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-up blue"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Warranty Days</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div class="days-spinner">
                                <div class="input-group">
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-down red"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <?php
                                    echo $this->Form->input( 'warranty_days', array(
                                        'type'        => 'text',
                                        'class'       => 'spinner-input form-control required',
                                        'id'          => 'warranty-days',
                                        'placeholder' => 'Warranty Days',
                                        'maxlength'   => '20',
                                        'value'       => !empty( $data['Service']['warranty_days'] ) ? $data['Service']['warranty_days'] : 0,
                                    ) );
                                    ?>
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-up blue"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Warranty Hours</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div class="hours-spinner">
                                <div class="input-group">
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-down red"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <?php
                                    echo $this->Form->input( 'warranty_hours', array(
                                        'type'        => 'text',
                                        'class'       => 'spinner-input form-control required',
                                        'id'          => 'warranty-hours',
                                        'placeholder' => 'Warranty Hours',
                                        'maxlength'   => '20',
                                        'value'       => !empty( $data['Service']['warranty_hours'] ) ? $data['Service']['warranty_hours'] : 0,
                                    ) );
                                    ?>
                                    <div class="spinner-buttons input-group-btn">
                                        <button type="button" class="btn spinner-up blue"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Aggrement End Date</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <div style="width: 100% !important;" class="input-group input-medium date date-picker text-center" data-date-format="dd-mm-yyyy">
                                <?php
                                echo $this->Form->input( 'aggrement_end_date', array(
                                    'type'        => 'text',
                                    'class'       => 'form-control required',
                                    'id'          => 'aggrement-end-date',
                                    'placeholder' => 'Aggrement End Date',
                                    'value'       => !empty( $data['Service']['aggrement_end_date'] ) ? $data['Service']['aggrement_end_date'] : '',
                                ) );
                                ?>
                                <span class="input-group-btn">
                                    <button class="btn default" type="button" style="vertical-align: top"><i class="fa fa-calendar"></i></button>
								</span>
                            </div>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Item Description</label>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'service_desc', array(
                                'type'        => 'textarea',
                                'class'       => 'form-control',
                                'id'          => 'service-desc',
                                'placeholder' => 'Item Description',
                                'value'       => !empty( $data['Service']['service_desc'] ) ? $data['Service']['service_desc'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
<!--                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Group</label>-->
<!--                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">-->
<!--                            --><?php
//                            echo $this->Form->input( 'asset_group', array(
//                                'type'        => 'text',
//                                'class'       => 'form-control required',
//                                'id'          => 'asset_group',
//                                'placeholder' => 'Asset Group',
//                                'value'       => !empty( $data['Service']['asset_group'] ) ? $data['Service']['asset_group'] : '',
//                            ) );
//                            ?>
<!--                        </div>-->
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status</label>
                        <div class="col-md-4 col-sm-4  col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                'class'   => 'form-control required',
                                'id'      => 'status',
                                'value'   => isset( $data['Service']['status'] ) ? $data['Service']['status'] : ACTIVE,
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
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
        $( '.unit-price-spinner' ).spinner( { value: 0.0, step: 100.0, min: 0.0, max: 1000000000.00 } );
        $( '.vat-spinner' ).spinner( { value: 0.0, step: 1.0, min: 0.0, max: 100.00 } );
        $( '.hours-spinner' ).spinner( { value: 0.0, step: 1.0, min: 0.0, max: 24.00 } );
        $( '.days-spinner' ).spinner( { value: 0.0, step: 4.0, min: 0.0, max: 365.00 } );
        
        $( '.date-picker' ).datepicker( {
            format     : 'yyyy-mm-dd',
            rtl        : Metronic.isRTL(),
            orientation: 'left',
            autoclose  : true
        } );
        
        $( '#service-form' ).validate_popover( { popoverPosition: 'top' } );
    } );
</script>