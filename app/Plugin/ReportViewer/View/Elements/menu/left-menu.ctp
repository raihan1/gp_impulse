<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200">
            <li class="start <?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'report_viewer', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="<?php echo $this->request->params['controller'] == 'reports' ? ' active open' : ''; ?>">
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Reports</span>
                    <span class="arrow<?php echo $this->request->params['controller'] == 'reports' ? ' open' : ''; ?>"></span>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                   <!-- <li<?php /*echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; */?>>
                        <?php /*echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Services', array( 'plugin' => 'report_viewer', 'controller' => 'reports', 'action' => 'services' ), array( 'escape' => FALSE ) ); */?>
                    </li>
                    <li<?php /*echo $this->request->params['action'] == 'tickets' ? ' class="active"' : ''; */?>>
                        <?php /*echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Tickets', array( 'plugin' => 'report_viewer', 'controller' => 'reports', 'action' => 'tickets' ), array( 'escape' => FALSE ) ); */?>
                    </li>-->
                    <li<?php echo $this->request->params['action'] == 'invoice' ? ' class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Invoices', array( 'plugin' => 'report_viewer', 'controller' => 'invoice_reports', 'action' => 'index' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                </ul>
            </li>
            <li class="last <?php echo $this->request->params['controller'] == 'invoice_archive' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'report_viewer', 'controller' => 'invoice_archive', 'action' => 'index' ) ); ?>">
                    <i class="fa fa-archive"></i>
                    <span class="title">Invoice Archive</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="<?php echo $this->request->params['controller'] == 'reports' ? ' active open' : ''; ?>">

                <a href="javascript:;">
                    <i class="fa fa-user-circle"></i>
                    <span class="title">Switch User</span>
                    <span class="arrow<?php echo $this->request->params['controller'] == 'reports' ? ' open' : ''; ?>"></span>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <?php $roleData = $this->Session->read('userRole');
                    foreach($roleData as $role){
                        if($role==2){?>
                            <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                                <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Supplier', array( 'plugin' => 'supplier', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE ) ); ?>
                            </li>
                            <?php
                        }elseif($role == 3){?>
                            <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                                <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Tr Creator', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE ) ); ?>
                            </li>
                            <?php
                        }elseif($role == 4){?>
                            <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                                <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Tr Validation', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE ) ); ?>
                            </li>
                            <?php
                        }elseif($role == 5){?>
                            <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                                <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Invoice Creator', array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE ) ); ?>
                            </li>
                            <?php
                        }elseif($role == 6){?>
                            <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                                <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Invoice Validation', array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'dashboard' ), array( 'escape' => FALSE ) ); ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
</div>