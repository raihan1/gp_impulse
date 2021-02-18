<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start <?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="<?php echo $this->request->params['controller'] == 'tickets' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ) ); ?>">
                    <i class="fa fa-ticket"></i>
                    <span class="title">Manage TR</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="last <?php echo $this->request->params['controller'] == 'reports' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'reports', 'action' => 'tickets' ) ); ?>">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Reports</span>
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
    </div>
</div>