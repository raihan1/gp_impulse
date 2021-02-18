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
                    <i class="fa fa-list"></i>
                    <?php echo $this->Html->link( 'Supplier Category', array( 'plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-list"></i>
                    <span>Supplier Category Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Supplier Category Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'add', $data['SupplierCategory']['id'] ) ); ?>" class="btn default yellow-stripe">
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
                        <label class="col-md-2 control-label">Category Name</label>
                        <div class="col-md-8">
                            <?php echo $data['SupplierCategory']['category_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <?php echo $data['SupplierCategory']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>
    </div>
</div>