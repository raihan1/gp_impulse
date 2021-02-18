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
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'index', '#' => $type ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Ticket Details</span>
                </li>
            </ul>
        </div>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Ticket Details
                </div>
            </div>
            
            <div class="portlet-body" style="padding: 0px">
                <?php echo $this->element( 'details/tickets/details', array(), array( 'plugin' => 'invoice_validation' ) ); ?>
                
                <div class="row" style="margin: 0px">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                        <?php if( !empty( $data['TrService'] ) ) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>TR Class</th>
                                            <th>Service</th>
                                            <th class="text-right">Base Unit Price</th>
                                            <th>Vat</th>
                                            <th class="text-right">Unit Price</th>
                                            <th>Quantity</th>
                                            <th class="text-right">Total</th>
                                            <?php echo $type == 'rejected' ? '<th>Comments</th>' : '' ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $data['TrService'] as $trs ) { ?>
                                            <tr>
                                                <td><?php echo $data['Ticket']['tr_class']; ?></td>
                                                <td><?php echo $trs['service']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price'], 4 ); ?></td>
                                                <td><?php echo $trs['vat']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price_with_vat'], 4 ); ?></td>
                                                <td><?php echo $trs['quantity']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['total_with_vat'], 4 ); ?></td>
                                                <?php echo $type == 'rejected' ? "<td>{$trs['comments']}</td>" : '' ?>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th colspan="6" class="text-right"><b>Total</b></th>
                                            <th class="text-right"><?php echo number_format( $data['Ticket']['total_with_vat'], 4 ); ?></th>
                                            <?php echo $type == 'rejected' ? "<th>&nbsp;</th>" : '' ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-9 pull-right" style="margin-right: 15px; margin-bottom: 5px">
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'index', '#' => $type ), array( 'escape' => FALSE, 'class' => 'btn red pull-right' ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>