<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200">
            <li class="start <?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="<?php echo $this->request->params['controller'] == 'tickets' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index' ) ); ?>">
                    <i class="fa fa-ticket"></i>
                    <span class="title">Tickets</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="<?php echo $this->request->params['controller'] == 'invoices' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'tr_validation', 'controller' => 'invoices', 'action' => 'index' ) ); ?>">
                    <i class="fa fa-money"></i>
                    <span class="title">Invoiceable Tickets</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="last<?php echo $this->request->params['controller'] == 'reports' ? ' active open' : ''; ?>">
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Reports</span>
                    <span class="arrow<?php echo $this->request->params['controller'] == 'reports' ? ' open' : ''; ?>"></span>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li<?php echo $this->request->params['action'] == 'services' ? ' class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Services', array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'services' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                    <li<?php echo $this->request->params['action'] == 'tickets' ? ' class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Tickets', array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'tickets' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>