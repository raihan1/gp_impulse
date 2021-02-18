<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <?php echo $this->Html->link( 'Item', array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <span>Item Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Item Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'add', $data['Service']['id'] ) ); ?>" class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Supplier Name</label>
                        <div class="col-md-8">
                            <?php echo $this->Html->link( $data['Supplier']['name'], array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'view', $data['Supplier']['id'] ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Item Name</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['service_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Unit Price</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['service_unit_price']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Vat</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['vat']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Warranty Days</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['warranty_days']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Warranty Hours</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['warranty_hours']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Aggrement End Date</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['aggrement_end_date']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Item Description</label>
                        <div class="col-md-8">
                            <?php echo $data['Service']['service_desc']; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <?php echo $data['Service']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>
