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
                    <i class="fa fa-globe"></i>
                    <?php echo $this->Html->link( 'Office', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-globe"></i>
                    <span>Office Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">Office Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'add', $data['SubCenter']['id'] ) ); ?>"
                       class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">Region Name</label>
                        <div class="col-md-8"><?php echo $data['Region']['region_name']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Office Name</label>
                        <div class="col-md-8"><?php echo $data['SubCenter']['sub_center_name']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">AC Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['AC_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">AC Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['AC_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">CW Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['CW_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">CW Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['CW_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">DV Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['DV_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">DV Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['DV_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">EB Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['EB_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">EB Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['EB_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">FM Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['FM_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">FM Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['FM_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">GN Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['GN_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">GN Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['GN_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">PG Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['PG_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">PG Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['PG_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">RF Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['RF_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">RF Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['RF_min_budget']; ?></div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">SS Budget</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['SS_budget']; ?></div>
                        <label class="col-md-2 col-md-offset-1 control-label">SS Max Cost</label>
                        <div class="col-md-2 text-right"><?php echo $data['SubCenter']['SS_min_budget']; ?></div>
                    </div>
                    <div class="row">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-8"><?php echo $data['SubCenter']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?></div>
                </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>

    </div>
</div>