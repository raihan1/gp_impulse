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
                    <i class="fa fa-cloud"></i>
                    <?php echo $this->Html->link( 'TR Class', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-cloud"></i>
                    <span>TrClass Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">TR Class Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'add', $data['TrClass']['id'] ) ); ?>" class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Asset Group Name</label>
                        <div class="col-md-8">
                            <?php echo $this->Html->link( $data['AssetGroup']['asset_group_name'], array( 'plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'view', $data['AssetGroup']['id'] ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">TR Class Name</label>
                        <div class="col-md-8">
                            <?php echo $data['TrClass']['tr_class_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">SLA</label>
                        <div class="col-md-8">
                            <?php echo $data['TrClass']['no_of_days']; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <?php echo $data['TrClass']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>
