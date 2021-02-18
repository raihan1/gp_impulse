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
                    <i class="fa fa-sort-numeric-asc"></i>
                    <?php echo $this->Html->link( 'Asset Number', array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-sort-numeric-asc"></i>
                    <span>Asset Number Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Asset Number Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'add', $data['AssetNumber']['id'] ) ); ?>" class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Site Name</label>
                        <div class="col-md-8">
                            <?php echo $this->Html->link( $data['AssetGroup']['Site']['site_name'], array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'view', $data['AssetGroup']['Site']['id'] ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Asset Group Name</label>
                        <div class="col-md-8">
                            <?php echo $this->Html->link( $data['AssetGroup']['asset_group_name'], array( 'plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'view', $data['AssetGroup']['id'] ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Asset Number</label>
                        <div class="col-md-8">
                            <?php echo $data['AssetNumber']['asset_number']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Asset Number Desc</label>
                        <div class="col-md-8">
                            <?php echo $data['AssetNumber']['asset_number_desc']; ?>
                        </div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8">
                        <?php echo $data['AssetNumber']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>
