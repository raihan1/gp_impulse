<style type="text/css">
    .add-more, .remove {
        padding: 7px 14px;
        background-color: green;
        border-width: 0;
        font-size: 14px;
        outline: none !important;
        background-image: none !important;
        filter: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        text-shadow: none;
        color: #fff;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Add Service</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Add Service Form
                </div>
            </div>

            <div class="portlet-body form">
                <?php echo $this->element( 'details/tickets/details', array(), array( 'plugin' => 'supplier' ) ); ?>

                <?php
                echo $this->Form->create( 'Ticket', array(
                    'type'          => 'file',
                    'id'            => 'ticket-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => $data['Ticket']['id'] ) );
                ?>
                <div class="form-body">
                    <div class="form-group trs" data-id="0">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-1 col-sm-1 col-xs-12 control-label">Item</label>
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( '', array(
                                    'name'    => 'data[TrService][0][service]',
                                    'options' => $serviceList,
                                    'empty'   => 'Select a service',
                                    'class'   => 'form-control required service',
                                    'id'      => 'service-0',
                                ) );
                                ?>
                                <br />
                                <p class="desc"></p>
                            </div>
                            <label class="col-md-1 col-sm-1 col-xs-12 control-label">Quantity</label>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( '', array(
                                    'name'  => 'data[TrService][0][quantity]',
                                    'type'  => 'text',
                                    'class' => 'form-control required',
                                    'id'    => 'quantity-0',
                                    'value' => 1,
                                ) );
                                ?>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">Delivery Date</label>
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group date form_datetime">
                                    <?php
                                    echo $this->Form->input( '', array(
                                        'name'      => 'data[TrService][0][delivery_date]',
                                        'type'      => 'text',
                                        'class'     => 'form-control required',
                                        'id'        => 'received-date-id',
                                        'maxlength' => 100,
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
										<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
									</span>
                                </div>
                            </div>
                            <span class="input-group-btn">
								<button class="add-more" type="button"><i class="fa fa-plus"></i></button>
							</span>
							<?php if( $CurMeter ) { ?>
                            <label class="col-md-1 col-sm-1 col-xs-12 control-label">Current Meter Reading</label>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( '', array(
                                    'name'  => 'data[TrService][0][current_meter_reading]',
                                    'type'  => 'text',
                                    'class' => 'form-control',
                                    'id'    => 'current_meter_rd',
                                    'value' => 0,
                                    /*'disabled' => 'disabled'*/
                                ) );
                                ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="button" class="btn green" id="submit-form"><i class="fa fa-check"></i> Submit
                            </button>
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'index'), array( 'class' => 'btn red', 'escape' => FALSE ) ); ?>
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
        <?php

        $serviceOptions = '<option value="">Select a service</option>';
        foreach( $serviceList as $service ) {
            $serviceOptions .= '<option value="' . addslashes( $service['value'] ) . '" data-id="' . $service['data-id'] . '">' . addslashes( $service['name'] ) . '</option>';
        }
        ?>
        var serviceOptions = '<?php echo $serviceOptions; ?>';

        gp_warranty.select_options( 'service-0' );

        var date = new Date();

        $( 'body' ).on( 'focus', '.form_datetime', function() {
            $( this ).datetimepicker( {
                format        : 'yyyy-mm-dd hh:ii',
                rtl           : Metronic.isRTL(),
                endDate       : date,
                pickerPosition: Metronic.isRTL() ? 'bottom-right' : 'bottom-left',
                autoclose     : true
            } );
        } );

        $( document ).on( 'change', '.service', function() {
            $( this ).closest( 'div.trs' ).find( '.desc' ).html(
                $( this ).find( 'option:selected' ).text()
            );
            var service = new RegExp('[R][F][L,R,E,D][A-Z]');
            if (service.test($(this).val())) {
                $('.current_meter_rd').prop('disabled', false);
            }
            else{
                $('.current_meter_rd').prop('disabled', true);
            }
        } );


        var serviceCount = 1;

        $( document ).on( 'click', '.add-more', function() {
            var content = '<div class="form-group remove-group trs" data-id="' + serviceCount + '">'
                + '<div class="col-md-12 col-sm-12 col-xs-12">'
                + '<label class="col-md-1 col-sm-1 col-xs-12 control-label">Item</label>'
                + '<div class="col-md-3 col-sm-3 col-xs-12 form-group">'
                + '<select name="data[TrService][' + serviceCount + '][service]" id="service-' + serviceCount + '" class="form-control required service">'
                + serviceOptions
                + '</select>'
                + '<br /><p class="desc"></p>'
                + '</div>'

                + '<label class="col-md-1 col-sm-1 col-xs-12 control-label">Quantity</label>'
                + '<div class="col-md-2 col-sm-2 col-xs-12 form-group">'
                + '<input type="text" name="data[TrService][' + serviceCount + '][quantity]" id="quantity-' + serviceCount + '" class="spinner-input form-control required" value="1" />'
                + '</div>'


                + '<label class="col-md-2 col-sm-2 col-xs-12 control-label">Delivery Date</label>'
                + '<div class="col-md-3 col-sm-3 col-xs-12 form-group">'
                + '<div style="width: 100% !important;" class="input-group date form_datetime">'
                + '<input type="text" name="data[TrService][' + serviceCount + '][delivery_date]" class="form-control required" value="" />'
                + '<span class="input-group-btn">'
                + '<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>'
                + '</span>'
                + '</div>'
                + '</div>'
                + '<label class="col-md-1 col-sm-1 col-xs-12 control-label">Current Meter Reading</label>'
                + '<div class="col-md-2 col-sm-2 col-xs-12 form-group">'
                + '<input type="text" name="data[TrService][' + serviceCount + '][current_meter_reading]" id="current_meter_rd-' + serviceCount + '" class="spinner-input form-control required" value="0" />'
                + '</div>'
                + '<span class="input-group-btn">'
                + '<button class="remove" type="button"><i class="fa fa-minus"></i></button>'
                + '</span>'
                + '</div>';
            +'</div>';

            $( '.form-body' ).append( content );
            gp_warranty.select_options( 'service-' + serviceCount );
            serviceCount++
        } );

        $( document ).on( 'click', '.remove', function() {
            $( this ).closest( '.remove-group' ).remove();
        } );

        $( '#submit-form' ).on( 'click', function() {
            var msg   = '';
            var items = [];

            $( '.trs' ).each( function() {
                var selectedItem = $( this ).find( 'select' ).val();
                if( $.inArray( selectedItem, items ) === -1 ) {
                    items.push( selectedItem );
                }
                else {
                    msg = 'Item should not be same';
                    return false;
                }

                if( !( $( '#quantity-' + $( this ).attr( 'data-id' ) ).val() > 0 ) ) {
                    msg = 'Invalid quantity';
                    return false;
                }

                if( $( '#delivery_date_' + $( this ).attr( 'data-id' ) ).val() == '' ) {
                    msg = 'Provide delivery date';
                    return false;
                }
            } );

            if( msg != '' ) {
                Metronic.alert( {
                    type   : 'danger',
                    icon   : 'warning',
                    message: msg,
                    place  : 'prepend'
                } );
                return false;
            }

            $( '#ticket-form' ).validate_popover( { popoverPosition: 'top' } );

            $( '#ticket-form' ).submit();
        } );
    } );
</script>