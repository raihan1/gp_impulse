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
                    <i class="fa fa-map-marker"></i>
                    <?php echo $this->Html->link( 'Site', array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-map-marker"></i>
                    <span>Site Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Site Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'add', $data['Site']['id'] ) ); ?>" class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Office Name</label>
                        <div class="col-md-8">
                            <?php echo $this->Html->link( $data['SubCenter']['sub_center_name'], array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'view', $data['SubCenter']['id'] ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Site Name</label>
                        <div class="col-md-8">
                            <?php echo $data['Site']['site_name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-8">
                            <?php echo $data['Site']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>