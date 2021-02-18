<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-money"></i>
                    <?php echo $this->Html->link( 'Invoice', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-money"></i>
                    <span>Create Invoice</span>
                </li>
            </ul>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i> Create Invoice
                </div>
            </div>
            
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <?php
                        echo $this->Form->create( '', array(
                            'type'          => 'get',
                            'url'           => array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'add' ),
                            'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                        ) );
                        ?>
                        <div class="row">
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label" style="margin-top: 5px; text-align: right">Office</label>
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'sub_center', array(
                                    'options' => $subCenterList,
                                    'empty'   => 'Select a Office',
                                    'class'   => 'form-control required',
                                    'id'      => 'sub_center',
                                    'value'   => !empty( $_GET['sub_center'] ) ? $_GET['sub_center'] : '',
                                ) );
                                ?>
                            </div>
                            
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label" style="margin-top: 5px; text-align: right">Supplier</label>
                            <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'supplier', array(
                                    'options' => $supplierList,
                                    'empty'   => 'Select a supplier',
                                    'class'   => 'form-control required',
                                    'id'      => 'supplier',
                                    'value'   => !empty( $_GET['supplier'] ) ? $_GET['supplier'] : '',
                                ) );
                                ?>
                            </div>
                            
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i> Tickets
                </div>
            </div>
            
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'Invoice', array(
                    'id'            => 'formInvoice',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                ?>
                <div class="form-body">
                    <?php
                    if( !empty( $data ) ) {
                        echo $this->Form->hidden( '', array( 'name' => 'data[Invoice][sub_center]', 'value' => $_GET['sub_center'] ) );
                        echo $this->Form->hidden( '', array( 'name' => 'data[Invoice][supplier]', 'value' => $_GET['supplier'] ) );
                        ?>
                        <div class="budget-container">
                            <?php echo $this->element( 'tickets/budget', array( 'month' => 'Last' ) ); ?>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label text-right">Year</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'year', array(
                                    'class'       => 'form-control required',
                                    'id'          => 'year',
                                    'value'       => '',
                                    'placeholder' => date( 'Y' ),
                                ) );
                                ?>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label text-right">Month</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <?php
                                $months = array();
                                for( $i = 1; $i <= 12; $i++ ) {
                                    $months[ $i ] = date( 'F', strtotime( "2016-{$i}-01 00:00:00" ) );
                                }
                                echo $this->Form->input( 'month', array(
                                    'options' => $months,
                                    'empty'   => 'Select',
                                    'class'   => 'form-control required',
                                    'id'      => 'month',
                                    'value'   => '',
                                ) );
                                ?>
                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="tickets_list">
                            <thead>
                                <tr>
                                    <th width="3%"><input type="checkbox" id="groupCheckbox" /></th>
                                    <th>TR ID</th>
                                    <th>Site Name</th>
                                    <th>TR Class</th>
                                    <th>Services (Quantity)</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $data as $d ) { ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="data[Invoice][id][]" class="checkboxes" value="<?php echo $d['Ticket']['id']; ?>" />
                                        </td>
                                        <td><?php echo $d['Ticket']['id']; ?></td>
                                        <td><?php echo $d['Ticket']['site']; ?></td>
                                        <td><?php echo $d['Ticket']['tr_class']; ?></td>
                                        <td>
                                            <?php
                                            $services = array();
                                            foreach( $d['TrService'] as $trs ) {
                                                $services[] = "{$trs['service']} (" . number_format( $trs['quantity'] ) . ")";
                                            }
                                            echo implode( ', ', $services );
                                            ?>
                                        </td>
                                        <td class="text-right"><?php echo number_format( $d['Ticket']['total_with_vat'], 4 ); ?></td>
                                    </tr>
                                <?php } ?>
                            <tbody>
                        </table>
                        <div class="col-md-12 col-sm-12 total" style="margin-top: 15px; padding: 0px">
                            <b>Total: <span id="total_with_vat">0.0000</span></b>
                        </div>
                        <?php
                    }
                    else {
                        echo 'No ticket found as marked for Invoice';
                    }
                    ?>
                </div>
                <div class="form-actions fluid" style="margin-top: 50px">
                    <div class="row">
                        <?php if( !empty( $data ) ) { ?>
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    /* Get total_with_vat for the selected tickets via ajax */
    var get_total = function() {
        var tr_ids = [];
        $( '#tickets_list tbody input[type="checkbox"]:checked' ).each( function() {
            tr_ids.push( $( this ).val() );
        } );
        
        /* Call ajax only if at least one ticket is selected */
        if( tr_ids.length > 0 ) {
            $.ajax( {
                type   : 'post',
                url    : '<?php echo $this->Html->url( array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'total' ) ); ?>',
                data   : { 'tr_ids': tr_ids },
                success: function( res ) {
                    $( '#total_with_vat' ).html( res );
                }
            } );
        }
        else {
            $( '#total_with_vat' ).html( '0.0000' );
        }
    };
    
    $( document ).ready( function() {
        gp_warranty.select_options( 'sub_center' );
        gp_warranty.select_options( 'supplier' );
        
        $( '#formInvoice' ).validate_popover( { popoverPosition: 'top' } );
        
        $( '#tickets_list #groupCheckbox' ).on( 'click', function() {
            var isChecked = $( this ).prop( 'checked' );
            
            $( '#tickets_list tbody input[type=checkbox]' ).each( function() {
                /* Check/uncheck checkboxes */
                $( this ).attr( 'checked', isChecked );
                
                /* Handle auto-added bootstrap classes */
                isChecked ? $( this ).closest( 'span' ).addClass( 'checked' ) : $( this ).closest( 'span' ).removeClass( 'checked' );
            } );
            
            get_total();
        } );
        
        $( '#tickets_list tbody input[type="checkbox"]' ).on( 'change', function() {
            /* Update the group checkbox accordingly */
            if( $( this ).is( ':checked' ) ) {
                if( $( '#tickets_list tbody input[type="checkbox"]' ).length == $( '#tickets_list tbody input[type="checkbox"]:checked' ).length ) {
                    $( '#tickets_list #groupCheckbox' ).attr( 'checked', true ).closest( 'span' ).addClass( 'checked' );
                }
            }
            else {
                $( '#tickets_list #groupCheckbox' ).attr( 'checked', false ).closest( 'span' ).removeClass( 'checked' );
            }
            
            get_total();
        } );
        
        $( '#formInvoice' ).on( 'submit', function( e ) {
            if( $( '#tickets_list tbody input[type="checkbox"]:checked' ).length == 0 ) {
                e.preventDefault();
                alert( 'Please select at least one ticket.' );
            }
        } );
    } );
</script>