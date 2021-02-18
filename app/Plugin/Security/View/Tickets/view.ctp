<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ) ); ?>">TR
                        List</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>TR Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> TR Details
                </div>
            </div>
            <div class="portlet-body" style="padding: 0px">

                <div class="col-md-12 col-sm-12 col-xs-12" style="margin: 15px 0px 30px 0px; border-bottom: 1px solid #FDDADA">
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR No:</label>
                        <div class="col-md-4 col-sm-10 col-xs-12">
                            <?php echo $data['Ticket']['id']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR Creation Date:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Ticket']['created']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Supplier Name:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Supplier']['name']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Site Name:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Site']['site_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Asset Number:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['AssetNumber']['asset_number']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">TR Class:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['TrClass']['tr_class_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Received at Supplier:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Ticket']['received_at_supplier']; ?>
                        </div>
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Completion Date:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Ticket']['complete_date']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 col-sm-2 col-xs-12 control-label">Comment:</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <?php echo $data['Ticket']['comment']; ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin: 0px">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                        <?php if( !empty( $trsData ) ) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>TR Class</th>
                                        <th>Service</th>
                                        <th>Base Unit Price</th>
                                        <th>Vat</th>
                                        <th>Unit Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $total = 0;
                                    foreach( $trsData as $trsd ) : ?>
                                        <tr>
                                            <td><?php echo $data['TrClass']['tr_class_name']; ?></td>
                                            <td><?php echo $trsd['service_name']; ?></td>
                                            <td><?php echo $trsd['base_unit_price']; ?></td>
                                            <td><?php echo $trsd['vat']; ?></td>
                                            <td><?php echo $trsd['unit_price']; ?></td>
                                            <td><?php echo $trsd['quantity']; ?></td>
                                            <td><?php echo $trsd['unit_price'] * $trsd['quantity'];
                                                $total += ( $trsd['unit_price'] * $trsd['quantity'] ); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="6" style="text-align: right"><b>Total</b></td>
                                        <td><?php echo $total; ?></td>
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
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ) ); ?>"
                               class="btn red pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>