<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
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
                                            <th width="12%">TR Class</th>
                                            <th>Service</th>
                                            <th class="text-right" width="12%">Base Unit Price</th>
                                            <th class="text-right" width="8%">Vat</th>
                                            <th class="text-right" width="10%">Unit Price</th>
                                            <th class="text-right" width="8%">Quantity</th>
                                            <th class="text-right" width="10%">Total</th>
                                            <th>Delivery Date</th>
                                            <?php echo $type == 'rejected' ? '<th>Comments</th>' : ''; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $data['TrService'] as $trs ) { ?>
                                            <tr>
                                                <td><?php echo $data['Ticket']['tr_class']; ?></td>
                                                <td><?php echo $trs['service_desc']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price'], 2 ); ?></td>
                                                <td class="text-right"><?php echo $trs['vat']; ?>%</td>
                                                <td class="text-right"><?php echo number_format( $trs['unit_price_with_vat'], 4 ); ?></td>
                                                <td class="text-right"><?php echo $trs['quantity']; ?></td>
                                                <td class="text-right"><?php echo number_format( $trs['total_with_vat'], 4 ); ?></td>
                                                <td><?php echo $this->Lookup->showDateTime( $trs['delivery_date'] ); ?></td>
                                                <?php echo $type == 'rejected' ? "<td>{$trs['comments']}</td>" : ''; ?>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td colspan="6" class="text-right"><b>Total</b></td>
                                            <td class="text-right"><?php echo number_format( $data['Ticket']['total_with_vat'], 4 ); ?></td>
                                            <td>&nbsp;</td>
                                            <?php echo $type == 'rejected' ? '<td>&nbsp;</td>' : ''; ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-right">
                        <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => $type ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>