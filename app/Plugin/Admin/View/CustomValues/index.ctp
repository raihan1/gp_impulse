
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
                    <i class="fa fa-calendar"></i>
                    <span>Settings</span>
                </li>
            </ul>
        </div>
        <form action="<?php echo $this->webroot;?>admin/custom_value" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="title" class="control-label col-md-2">Block Date Value</label>
                <div class="col-md-4">
                    <input type="number" min="1" max="100" name="block_date" value="<?php echo $pass_data['value'];?>" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <input type="submit" value="save" class="btn btn-primary" name="setting_save">
                </div>
            </div>
        </form>
    </div>
</div>

