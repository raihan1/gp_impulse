<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start <?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="<?php echo $this->request->params['controller'] == 'tickets' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'invoice_creation', 'controller' => 'tickets', 'action' => 'index' ) ); ?>">
                    <i class="fa fa-ticket"></i>
                    <span class="title">Tickets</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li <?php echo $this->request->params['controller'] == 'invoices' ? 'class="active open"' : ''; ?>>
                <a href="javascript:;">
                    <i class="fa fa-money"></i>
                    <span class="title">Invoices</span>
                    <span class="arrow<?php echo $this->request->params['controller'] == 'invoices' ? ' open' : ''; ?>"></span>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php echo $this->request->params['action'] == 'index' ? 'class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-list"></i> List', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'index' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                    <li <?php echo $this->request->params['action'] == 'add' ? 'class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-plus"></i> Create New', array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'add' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                </ul>
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
                        <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Services', array( 'plugin' => 'invoice_creation', 'controller' => 'reports', 'action' => 'services' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                    <li<?php echo $this->request->params['action'] == 'tickets' ? ' class="active"' : ''; ?>>
                        <?php echo $this->Html->link( '<i class="fa fa-bar-chart"></i> Tickets', array( 'plugin' => 'invoice_creation', 'controller' => 'reports', 'action' => 'tickets' ), array( 'escape' => FALSE ) ); ?>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
