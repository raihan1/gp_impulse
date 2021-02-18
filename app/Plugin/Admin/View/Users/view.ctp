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
                    <i class="fa fa-user"></i>
                    <?php echo $this->Html->link( 'User', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-user"></i>
                    <span>User Details</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">User Details</div>
                <div class="actions">
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'add', $data['User']['id'] ) ); ?>"
                       class="btn default yellow-stripe">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-480">Edit</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <label class="col-md-2 control-label">User Role</label>
                        <div class="col-md-8">
                            <?php
                            $role = array(
                                TR_CREATOR        => 'TR Creator',
                                SECURITY          => 'TR Creator (SS)',
                                TR_VALIDATOR      => 'TR Validator',
                                SUPPLIER          => 'Supplier',
                                INVOICE_CREATOR   => 'Invoice Creator',
                                INVOICE_VALIDATOR => 'Invoice Validator',
                            );
                            echo $role[ $data['User']['role'] ];
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">User Name</label>
                        <div class="col-md-8">
                            <?php echo $data['User']['name']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Phone</label>
                        <div class="col-md-8">
                            <?php echo $data['User']['phone']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Email</label>
                        <div class="col-md-8">
                            <?php echo $data['User']['email']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Address</label>
                        <div class="col-md-8">
                            <?php echo $data['User']['address']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-2 control-label">Status</label>
                        <div class="col-md-8">
                            <?php echo $data['User']['status'] == ACTIVE ? 'Active' : 'Inactive'; ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right">
                    <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>
                </div>
            </div>
        </div>
    </div>
</div>