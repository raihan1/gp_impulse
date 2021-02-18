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
                    <span>Edit Invoice</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i> Invoice Edit Form
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
                echo $this->Form->hidden( 'id', array( 'value' => $data['Invoice']['id'] ) );
                ?>
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <p class="form-control-static"><?php echo $data['Invoice']['sub_center']; ?></p>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Supplier:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <p class="form-control-static"><?php echo $data['Invoice']['supplier']; ?></p>
                        </div>
                    </div>
                    
                    <div class="budget-container">
                        <?php echo $this->element( 'tickets/budget', array( 'month' => 'Last' ) ); ?>
                    </div>
                    
                    <table class="table table-striped table-bordered table-hover" id="tickets_list">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="groupCheckbox" /></th>
                                <th>TR ID</th>
                                <th>Site Name</th>
                                <th>TR Class</th>
                                <th class="text-right">Total</th>
                                <th>Services (Quantity)</th>
                                <th>Previous Comment</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach( $invoiceableTickets as $d ) {
                                ?>
                                <tr data-id="<?php echo $d['Ticket']['id']; ?>">
                                    <td>
                                        <?php
                                        echo $this->Form->hidden( '', array( 'name' => "data[Ticket][$i][id]", 'value' => $d['Ticket']['id'] ) );
                                        echo $this->Form->input( '', array(
                                            'name'    => "data[Ticket][$i][check]",
                                            'type'    => 'checkbox',
                                            'class'   => 'checkboxes',
                                            'checked' => in_array( $d['Ticket']['id'], $invoiceTickets ),
                                            'value'   => 1,
                                        ) );
                                        ?>
                                    </td>
                                    <td><?php echo $d['Ticket']['id']; ?></td>
                                    <td><?php echo $d['Ticket']['site']; ?></td>
                                    <td><?php echo $d['Ticket']['tr_class']; ?></td>
                                    <td class="text-right"><?php echo number_format( $d['Ticket']['total_with_vat'], 4 ); ?></td>
                                    <td>
                                        <?php
                                        $services = array();
                                        foreach( $d['TrService'] as $trs ) {
                                            $services[] = "{$trs['service']} (" . number_format( $trs['quantity'] ) . ")";
                                        }
                                        echo implode( ', ', $services );
                                        ?>
                                    </td>
                                    <td><?php echo $d['Ticket']['invoice_comment']; ?> &nbsp;</td>
                                    <td>
                                        <?php
                                        echo $this->Form->input( '', array(
                                            'name'  => "data[Ticket][$i][invoice_comment]",
                                            'type'  => 'text',
                                            'class' => 'form-control',
                                        ) );
                                        $i++
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <div class="col-md-12 col-sm-12 total" style="margin-top: 15px; padding: 0px">
                        <b>Total: <span id="total_with_vat"><?php echo number_format( $data['Invoice']['total_with_vat'], 4 ); ?></span></b>
                    </div>
                </div>
                <div class="form-actions fluid" style="margin-top: 50px">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Update</button>
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
                        </div>
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
            tr_ids.push( $( this ).closest( 'tr' ).attr( 'data-id' ) );
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
        
        $( '#formInvoice' ).validate_popover( { popoverPosition: 'top' } );
        
        $( '#formInvoice' ).on( 'submit', function( e ) {
            if( $( '#tickets_list tbody input[type="checkbox"]:checked' ).length == 0 ) {
                e.preventDefault();
                alert( 'Please select at least one ticket.' );
            }
        } );
    } );
</script>