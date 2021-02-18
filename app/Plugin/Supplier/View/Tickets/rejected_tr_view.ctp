<style type="text/css">
    .highlight {
        border: 1px solid #FF0000 !important;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'supplier', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'TR List', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Ticket Details</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Ticket Details
                </div>
            </div>
            
            <div class="portlet-body">
                <?php
                echo $this->element( 'details/tickets/details', array(), array( 'plugin' => 'supplier' ) );
                
                echo $this->Form->create( 'Ticket', array(
                    'type'          => 'file',
                    'id'            => 'ticket-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => TRUE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => $data['Ticket']['id'] ) );
                ?>
                <div class="form-body">
                    <div class="row" style="margin: 0px">
                        <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                            <?php if( !empty( $data['TrService'] ) ) { ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="serviceList">
                                        <thead>
                                            <tr>
                                                <th width="15%">Item</th>
                                                <th width="10%">Quantity</th>
                                                <th width="22%">Delivery Date</th>
                                                <th width="25%">Previous Comments</th>
                                                <th width="23%">Comments</th>
                                                <th width="5%">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            foreach( $data['TrService'] as $trs ) { ?>
                                                <tr data-id="<?php echo $i; ?>">
                                                    <td>
                                                        <?php
                                                        echo $this->Form->hidden( '', array( 'name' => "data[TrService][{$i}][id]", 'value' => $trs['id'], 'id' => 'id_' . $i ) );
                                                        echo $this->Form->hidden( '', array( 'name' => "data[TrService][{$i}][status]", 'value' => $trs['status'], 'id' => 'status_' . $i, ) );
                                                        echo $this->Form->input( '', array(
                                                            'options' => $serviceList,
                                                            'name'    => "data[TrService][{$i}][service]",
                                                            'id'      => 'service_' . $i,
                                                            'class'   => 'form-control required',
                                                            'value'   => $trs['service'],
                                                        ) );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $this->Form->input( '', array(
                                                            'type'  => 'text',
                                                            'name'  => "data[TrService][{$i}][quantity]",
                                                            'id'    => 'quantity_' . $i,
                                                            'class' => 'form-control required',
                                                            'value' => $trs['quantity'],
                                                        ) );
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="input-group date form_datetime">
                                                            <?php
                                                            echo $this->Form->input( '', array(
                                                                'type'      => 'text',
                                                                'name'      => "data[TrService][{$i}][delivery_date]",
                                                                'id'        => 'delivery_date_' . $i,
                                                                'class'     => 'form-control required',
                                                                'value'     => $trs['delivery_date'],
                                                                'maxlength' => 100,
                                                            ) );
                                                            ?>
                                                            <span class="input-group-btn">
                                                                <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $trs['comments']; ?></td>
                                                    <td style="text-align: center; vertical-align: middle;">
                                                        <?php
                                                        echo $this->Form->input( '', array(
                                                            'type'      => 'text',
                                                            'name'      => "data[TrService][$i][comments]",
                                                            'id'        => 'comments_' . $i,
                                                            'class'     => 'form-control required',
                                                            'maxlength' => 1000,
                                                            'value'     => '',
                                                        ) );
                                                        ?>
                                                    </td>
                                                    <td style="text-align: center; vertical-align: middle">
                                                        <button type="button" class="remove btn red"><i class="fa fa-minus"></i></button>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-actions fluid">
                        <div class="row">
                            <div class="col-md-12" style="text-align: right;">
                                <button type="button" id="addService" class="btn green" style="float: left;"><i class="fa fa-plus"></i> Add Service</button>
                                <button type="button" id="submit-form" class="btn green"><i class="fa fa-check"></i> Submit</button>
                                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index', '#' => 'rejected' ), array( 'class' => 'btn red', 'escape' => FALSE ) ); ?>
                            </div>
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
            $serviceOptions .= '<option value="' . addslashes( $service['value'] ) . '" data-id="' . $service['data-id'] . '">' . addslashes( $service['name'] )     . '</option>';
        }
        ?>
        var serviceOptions = '<?php echo $serviceOptions; ?>';
        
        <?php
        foreach( $data['TrService'] as $i => $trs ) {
            echo "\ngp_warranty.select_options( 'service_{$i}' );";
        }
        ?>
        
        var date = new Date();
        var serviceCount = <?php echo count( $data['TrService'] ); ?>;
        
        $( 'body' ).on( 'focus', '.form_datetime', function() {
            $( this ).datetimepicker( {
                format        : 'yyyy-mm-dd hh:ii',
                rtl           : Metronic.isRTL(),
                endDate       : date,
                pickerPosition: Metronic.isRTL() ? 'bottom-right' : 'bottom-left',
                autoclose     : true
            } );
        } );
        
        $( '.remove' ).on( 'click', function() {
            $( this ).closest( 'tr' ).addClass( 'hide' );
            $( '#status_' + $( this ).closest( 'tr' ).attr( 'data-id' ) ).val( 0 );
        } );
        
        $( '#addService' ).on( 'click', function() {
            var content = '<tr data-id="' + serviceCount + '">'
                            + '<td>'
                                + '<input type="hidden" name="data[TrService][' + serviceCount + '][id]" value="" id="id_' + serviceCount + '" />'
                                + '<input type="hidden" name="data[TrService][' + serviceCount + '][status]" value="1" id="status_' + serviceCount + '" />'
                                + '<select name="data[TrService][' + serviceCount + '][service]" class="form-control required" id="service_' + serviceCount + '">' + serviceOptions + '</select>'
                            + '</td>'
                            + '<td width="150">'
                                + '<input type="text" name="data[TrService][' + serviceCount + '][quantity]" class="form-control required" id="quantity_' + serviceCount + '" value="1.00" maxlength="11" />'
                            + '</td>'
                            + '<td>'
                                + '<div class="input-group date form_datetime">'
                                    + '<input type="text" name="data[TrService][' + serviceCount + '][delivery_date]" class="form-control required" id="delivery_date_' + serviceCount + '" value="" maxlength="100" />'
                                    + '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button></span>'
                                + '</div>'
                            + '</td>'
                            + '<td>&nbsp;</td>'
                            + '<td><input type="text" name="data[TrService][' + serviceCount + '][comments]" class="form-control required" id="comments_' + serviceCount + '" maxlength="1000" value="" /></td>'
                            + '<td style="text-align: center; vertical-align: middle">'
                                + '<button type="button" class="remove btn red"><i class="fa fa-minus"></i></button>'
                            + '</td>'
                        + '</tr>';
            
            $( '#serviceList tbody' ).append( content );
            
            gp_warranty.select_options( 'service_' + serviceCount );
            
            $( '#delivery_date_' + serviceCount ).on( 'focus', function() {
                $( this ).datetimepicker( {
                    format        : 'yyyy-mm-dd hh:ii',
                    rtl           : Metronic.isRTL(),
                    endDate       : date,
                    pickerPosition: Metronic.isRTL() ? 'bottom-right' : 'bottom-left',
                    autoclose     : true
                } );
            } );
            
            $( '#serviceList tbody tr[data-id="' + serviceCount + '"] .remove' ).on( 'click', function() {
                $( this ).closest( 'tr' ).remove();
            } );
            
            serviceCount++
        } );
        
        $( '#submit-form' ).on( 'click', function() {
            var validServices = 0;
            var msg          = '';
            var services        = [];
            
            $( '#serviceList tbody tr' ).each( function() {
                var rowId = $( this ).attr( 'data-id' );
                
                if( $( '#status_' + rowId ).val() == 1 ) {
                    var selectedService = $( '#service_' + rowId ).val();
                    if( selectedService == '' ) {
                        $( '#service_' + rowId ).closest( 'td' ).addClass( 'highlight' );
                        msg += ( msg == '' ? '' : '<br />' ) + 'Select a service';
                    }
                    else {
                        if( $.inArray( selectedService, services ) === -1 ) {
                            services.push( selectedService );
                            $( '#service_' + rowId ).closest( 'td' ).removeClass( 'highlight' );
                        }
                        else {
                            $( '#service_' + rowId ).closest( 'td' ).addClass( 'highlight' );
                            msg += ( msg == '' ? '' : '<br />' ) + 'Service should not be same';
                        }
                    }
                    
                    if( $( '#quantity_' + rowId ).val() > 0 ) {
                        $( '#quantity_' + rowId ).closest( 'td' ).removeClass( 'highlight' );
                    }
                    else {
                        $( '#quantity_' + rowId ).closest( 'td' ).addClass( 'highlight' );
                        msg += ( msg == '' ? '' : '<br />' ) + 'Invalid quantity';
                    }
                    
                    if( $( '#delivery_date_' + rowId ).val() == '' ) {
                        $( '#delivery_date_' + rowId ).closest( 'td' ).addClass( 'highlight' );
                        msg += ( msg == '' ? '' : '<br />' ) + 'Provide delivery date';
                    }
                    else {
                        $( '#delivery_date_' + rowId ).closest( 'td' ).removeClass( 'highlight' );
                    }
                    
                    if( $( '#comments_' + rowId ).val() == '' ) {
                        $( '#comments_' + rowId ).closest( 'td' ).addClass( 'highlight' );
                        msg += ( msg == '' ? '' : '<br />' ) + 'Provide comments';
                    }
                    else {
                        $( '#comments_' + rowId ).closest( 'td' ).removeClass( 'highlight' );
                    }
                    
                    validServices++;
                }
            } );
            
            if( validServices == 0 ) {
                msg += ( msg == '' ? '' : '<br />' ) + 'Please provide at least one service';
            }
            
            if( msg != '' ) {
                Metronic.alert( {
                    type   : 'danger',
                    icon   : 'warning',
                    message: msg,
                    place  : 'prepend'
                } );
                return false;
            }
            
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();
            
            $( '#ticket-form' ).submit();
        } );
    } );
</script>