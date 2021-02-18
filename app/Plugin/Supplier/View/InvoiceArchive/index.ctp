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
                    <i class="fa fa-bar-chart"></i>
                    <span>InvoiceArchive</span>
                </li>
            </ul>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bar-chart"></i> Invoice Archive Reports
                </div>
            </div>
            
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Ticket', array(
                    'type'          => 'file',
                    'id'            => 'ticket-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Invoice Archive Report Type</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php
                            echo $this->Form->input( 'type_id', array(
                                'options' => array(
//                                    array( 'name' => 'Open TR Report', 'value' => 1 ),
                                    array( 'name' => 'Item wise Report', 'value' => 2 ),
                                    array( 'name' => 'TR wise Report', 'value' => 3 ),
                                    array( 'name' => 'Invoice Summary Report', 'value' => 4 ),
                                ),
                                'empty'   => 'Select a report type',
                                'class'   => 'form-control required',
                                'id'      => 'type-id',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group" id="invoiceContainer">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Invoice Number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php
                            echo $this->Form->input( 'invoice_id', array(
                                'options' => $invoiceList,
                                'empty'   => 'Select an invoice number',
                                'class'   => 'form-control required',
                                'id'      => 'invoice-id',
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="button" class="btn green" id="run-report"><i class="fa fa-check"></i> Run Report</button>
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
        gp_warranty.select_options( 'invoice-id' );
        gp_warranty.select_options( 'type-id' );
        
        $( '#type-id' ).on( 'change', function() {
            if( $( this ).val() == 1 ) {
                $( '#invoiceContainer' ).hide();
            }
            else {
                $( '#invoiceContainer' ).show();
            }
        } );
        
        $( '#run-report' ).on( 'click', function() {
            if( $( '#type-id' ).val().length == 0 ) {
                alert( 'Select a report type' );
                return false;
            }
            if( $( '#type-id' ).val() != 1 && $( '#invoice-id' ).val().length == 0 ) {
                alert( 'Select an invoice number' );
                return false;
            }
            var width  = $( window ).width() - 20,
                height = $( window ).height() - 10,
                left   = 5,
                top    = 5;
            
            window.open(
                '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'invoice_archive', 'action' => 'view' ) ); ?>/' + ( $( '#invoice-id' ).val().length == 0 ? 0 : $( '#invoice-id' ).val() ) + '/' + $( '#type-id' ).val(),
                '',
                'width=' + width + ', height=' + height + ', top=' + top + ', left=' + left
            );
        } );
    } );
</script>