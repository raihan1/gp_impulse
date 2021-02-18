<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <?php echo $this->Html->link( 'Supplier', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <span>Supplier Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Supplier Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'add', $data['Supplier']['id'] ) ); ?>" class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Supplier Code</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['code']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Supplier Name</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Supplier Email</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['email']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Supplier Phone</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['phone']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Address</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['address']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Remarks</label>
                        <div class="col-md-8">
                            <?php echo $data['Supplier']['remarks']; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <?php echo $data['Supplier']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>