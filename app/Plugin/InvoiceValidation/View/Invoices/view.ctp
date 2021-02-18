<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-money"></i>
                    <?php echo $this->Html->link( 'Invoice List', array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-money"></i>
                    <span>Invoice Details</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="budget-container">
            <?php echo $this->element( 'details/tickets/budget', array( 'month' => 'Last' ) ); ?>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i> Invoice Details
                </div>
            </div>
            
            <div class="portlet-body" style="padding: 0px">
                <div class="col-md-12 col-sm-12 col-xs-12" style="margin: 15px 0px 30px 0px; border-bottom: 1px solid #FDDADA;">
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Reff No:</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <?php echo $data['Invoice']['invoice_id']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Invoice No:</label>
                        <div class="col-md-4 col-sm-10 col-xs-12">
                            <?php echo $data['Invoice']['id']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Status:</label>
                        <div class="col-md-4 col-sm-10 col-xs-12">
                            <?php echo $data['Invoice']['status'] == NULL ? 'Pending' : ( $data['Invoice']['status'] == APPROVE ? 'Approved' : 'Rejected' ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Office:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Invoice']['sub_center']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Supplier Name:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Invoice']['supplier']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Amount:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo number_format( $data['Invoice']['total_with_vat'], 4 ); ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Date:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $this->Lookup->showDateTime( $data['Invoice']['created'] ); ?>
                        </div>
                    </div>
                </div>
                
                <?php
                echo $this->Form->create( 'Invoice', array(
                    'type'          => 'file',
                    'id'            => 'formInvoice',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => $data['Invoice']['id'], 'id' => 'invoiceId' ) );
                ?>
                <div class="row" style="margin: 0px">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Main Type</th>
                                        <th class="text-right">Amount</th>
                                        <th>Previous Comment</th>
                                        <th>Comment</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach( $mainTypes as $type => $mainType ) { ?>
                                        <tr>
                                            <td><?php echo $type; ?></td>
                                            <td class="text-right"><?php echo number_format( $mainType['total_with_vat'], 4 ); ?></td>
                                            <td><?php echo $mainType['previous_comment']; ?></td>
                                            <td>
                                                <?php
                                                echo $this->Form->input( '', array(
                                                    'name'      => "data[{$type}][invoice_comment]",
                                                    'type'      => 'text',
                                                    'class'     => 'form-control required',
                                                    'maxlength' => 1000,
                                                ) );
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                echo $this->Html->link(
                                                    '<i class="fa fa-eye"></i> Details',
                                                    array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'services', $data['Invoice']['id'], $type ),
                                                    array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Details', 'data-target' => '#ticketList', 'data-toggle' => 'modal' )
                                                );
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="modal fade" id="ticketList" role="basic" aria-hidden="true">
                            <div class="modal-dialog" style="width: 1024px;">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <?php echo $this->Html->image( 'loading-spinner-grey.gif', array( 'alt' => '', 'class' => 'loading' ) ); ?>
                                        <span>&nbsp;&nbsp;Loading... </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-9 pull-right" style="margin-right: 15px; margin-bottom: 5px">
                            <?php if( $data['Invoice']['status'] != APPROVE ) { ?>
                                <button type="button" class="btn green" id="approveInvoice"><i class="fa fa-check"></i> Approve Invoice</button>
                                <button type="submit" class="btn red"><i class="fa fa-check"></i> Reject Invoice</button>
                            <?php } ?>
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
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
        $( '#formInvoice' ).validate_popover( { popoverPosition: 'top' } );
        
        $( '#approveInvoice' ).on( 'click', function() {
            $.ajax( {
                type       : 'POST',
                url        : '<?php echo Router::url( array( 'controller' => 'invoices', 'action' => 'approve_invoice' ) ); ?>',
                data       : 'id=' + $( '#invoiceId' ).val(),
                dataType   : 'json',
                error: function( res ) {
                    alert( 'Failed to approve invoice.' );
                },
                success    : function( res ) {
                    alert( 'Invoice approved successfully.' );
                    window.location = '<?php echo Router::url( array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'index' ) ); ?>'
                }
            } );
        } );
    } );
</script>