<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'TR List', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Ticket Details</span>
                </li>
            </ul>
        </div>
    
        <?php
        echo $this->Session->flash();
        echo $this->Form->create( 'Ticket', array(
            'id'            => 'formTicket',
            'class'         => 'form-horizontal',
            'autocomplete'  => 'off',
            'role'          => 'form',
            'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
        ) );
        echo $this->Form->hidden( 'id', array( 'value' => $data['Ticket']['id'] ) );
        ?>
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Ticket Details
                </div>
            </div>
            
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="dl-horizontal">
                            <dt>TR No</dt>
                            <dd><?php echo $data['Ticket']['id']; ?></dd>
                
                            <dt>Site Name</dt>
                            <dd><?php echo $data['Ticket']['site']; ?></dd>
                
                            <!--dt>Asset Group</dt>
                            <dd><?php //echo $data['Ticket']['asset_group']; ?></dd>
                
                            <dt>Asset Number</dt>
                            <dd><?php //echo $data['Ticket']['asset_number']; ?></dd-->
                
                            <dt>TR Class</dt>
                            <dd><?php echo $data['Ticket']['tr_class']; ?></dd>

                            <dt>Supplier Name</dt>
                            <dd><?php echo $data['Ticket']['supplier']; ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="dl-horizontal">
                            <dt>TR Creation Date</dt>
                            <dd><?php echo $this->Lookup->showDateTime( $data['Ticket']['created'] ); ?></dd>
                
                            <dt>Received at Supplier</dt>
                            <dd><?php echo $this->Lookup->showDateTime( $data['Ticket']['received_at_supplier'] ); ?></dd>
                
                            <dt>Proposed Completion Date</dt>
                            <dd><?php echo $this->Lookup->showDateTime( $data['Ticket']['complete_date'] ); ?></dd>
                
                            <dt>Comment</dt>
                            <dd><?php echo $data['Ticket']['comment']; ?></dd>
                        </dl>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php if( !empty( $data['TrService'] ) ) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>TR Class</th>
                                            <th>Service</th>
                                            <th class="text-right">Base Unit Price</th>
                                            <th class="text-right">Vat</th>
                                            <th class="text-right">Unit Price</th>
                                            <th class="text-right">Quantity</th>
                                            <th class="text-right">Total</th>
                                            <th>Delivery Date</th>
                                            <?php if( $type == 'pending' ) { ?>
                                                <th>Last Delivery Date</th>
                                                <th class="text-right">Last Served Quantity</th>
                                                <th>Previous Comments</th>
                                                <th>Comments</th>
                                            <?php } else if( $type == 'rejected' ) { ?>
                                                <th>Comments</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; foreach( $data['TrService'] as $trs ) { ?>
                                            <tr>
                                                <td><?php echo $data['Ticket']['tr_class']; ?></td>
                                                <td><?php echo $trs['service_desc']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price'], 2 ); ?></td>
                                                <td class="text-right"><?php echo $trs['vat']; ?>%</td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price_with_vat'], 4 ); ?></td>
                                                <td class="text-right"><?php echo $trs['quantity']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['total_with_vat'], 4 ); ?></td>
                                                <td><?php echo $this->Lookup->showDateTime( $trs['delivery_date'] ); ?></td>
                                                <?php if( $type == 'pending' ) { ?>
                                                    <td><?php echo !empty( $trs['LastService']['delivery_date'] ) ? $this->Lookup->showDateTime( $trs['LastService']['delivery_date'] ) : 'N/A'; ?></td>
                                                    <td class="text-right"><?php echo !empty( $trs['LastService']['quantity'] ) ? $trs['LastService']['quantity'] : 'N/A'; ?></td>
                                                    <td><?php echo $trs['comments']; ?></td>
                                                    <td>
                                                        <?php
                                                        echo $this->Form->hidden( '', array( 'name' => "data[TrService][{$i}][id]", 'value' => $trs['id'] ) );
                                                        echo $this->Form->input( '', array(
                                                            'name'      => "data[TrService][{$i}][comments]",
                                                            'type'      => 'text',
                                                            'class'     => 'form-control required',
                                                            'maxlength' => 1000,
                                                        ) );
                                                        $i++;
                                                        ?>
                                                    </td>
                                                <?php } else if( $type == 'rejected' ) { ?>
                                                    <td><?php echo $trs['comments']; ?></td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="6" style="text-align: right"><b>Total</b></td>
                                            <td class="text-right"><?php echo number_format( $data['Ticket']['total_with_vat'], 4 ); ?></td>
                                            <td<?php echo $type == 'pending' ? ' colspan="5"' : ( $type == 'rejected' ? ' colspan="2"' : '' ); ?>>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php if( $type == 'pending' ) { ?>
                        <button type="button" class="btn green" id="btnApprove"><i class="fa fa-check"></i> Approve</button>
                        <button type="submit" class="btn red"><i class="fa fa-times"></i> Reject</button>
                        <?php } ?>
                        <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index', '#' => $type ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $( '#formTicket' ).validate_popover( { popoverPosition: 'top' } );
        
        $( '#btnApprove' ).on( 'click', function() {
            $.ajax( {
                type       : 'POST',
                dataType   : 'json',
                url        : '<?php echo Router::url( array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'approve', $data['Ticket']['id'] ) ); ?>',
                success    : function( data ) {
                    alert( 'Ticket approved successfully.' );
                    window.location = '<?php echo Router::url( array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index', '#' => 'pending' ) ); ?>'
                },
                error      : function() {
                    alert( 'Failed to approve ticket. Please contact administrator.' );
                }
            } );
        } );
    } );
</script>