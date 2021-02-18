<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start <?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard')); ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="<?php echo $this->request->params['controller'] == 'regions' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'regions', 'action' => 'index')); ?>">
                    <i class="fa fa-globe"></i>
                    <span class="title">Define Region</span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="<?php echo $this->request->params['controller'] == 'sub_centers' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index')); ?>">
                    <i class="fa fa-globe"></i>
                    <span class="title">Define Office</span>
                    <span class="selected"></span>
                </a>
            </li>

            <li class="<?php echo $this->request->params['controller'] == 'sites' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'sites', 'action' => 'index')); ?>">
                    <i class="fa fa-map-marker"></i>
                    <span class="title">Define Site</span>
                    <span class="selected"></span>
                </a>
            </li>

			<!--li class="<?php //echo $this->request->params['controller'] == 'projects' ? 'active' : ''; ?>">
				<a href="<?php //echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'projects', 'action' => 'index')); ?>">
					<i class="fa fa-plane"></i>
					<span class="title">Define Project</span>
					<span class="selected"></span>
				</a>
			</li>

			<li class="<?php //echo $this->request->params['controller'] == 'asset_groups' ? 'active' : ''; ?>">
				<a href="<?php //echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'index')); ?>">
					<i class="fa fa-plane"></i>
					<span class="title">Define Asset Group</span>
					<span class="selected"></span>
				</a>
			</li>

			<li-- class="<?php //echo $this->request->params['controller'] == 'asset_numbers' ? 'active' : ''; ?>">
				<a href="<?php //echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'index')); ?>">
					<i class="fa fa-sort-numeric-asc"></i>
					<span class="title">Define Asset Number</span>
					<span class="selected"></span>
				</a>
			</li-->

			<li class="<?php echo $this->request->params['controller'] == 'tr_classes' ? 'active' : ''; ?>">
				<a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index')); ?>">
					<i class="fa fa-cloud"></i>
					<span class="title">Define TR Class</span>
					<span class="selected"></span>
				</a>
			</li>

			<li class="<?php echo $this->request->params['controller'] == 'suppliers' ? 'active' : ''; ?>">
				<a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index')); ?>">
					<i class="fa fa-adjust"></i>
					<span class="title">Define Supplier</span>
					<span class="selected"></span>
				</a>
			</li>

			<li class="<?php echo $this->request->params['controller'] == 'supplier_categories' ? 'active' : ''; ?>">
				<a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'supplier_categories', 'action' => 'index')); ?>">
					<i class="fa fa-list"></i>
					<span class="title">Define Supplier Category</span>
					<span class="selected"></span>
				</a>
			</li>

			<li class="<?php echo $this->request->params['controller'] == 'services' ? 'active' : ''; ?>">
				<a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'services', 'action' => 'index')); ?>">
					<i class="fa fa-adjust"></i>
					<span class="title">Define Items</span>
					<span class="selected"></span>
				</a>
			</li>

            <li class="<?php echo $this->request->params['controller'] == 'users' && $this->request->params['action'] != 'dashboard' && $this->request->params['action'] != 'send_email' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'users', 'action' => 'index')); ?>">
                    <i class="icon-user"></i>
                    <span class="title">Define Users</span>
                    <span class="selected"></span>
                </a>
            </li>

            <!--<li <?php echo in_array($this->request->params['controller'], array('user_inspections', 'organization_sites')) ? 'class="active open"' : ''; ?>>
                <a href="javascript:;">
                    <i class="icon-user"></i>
                    <span class="title">Assign</span>
                    <span <?php echo in_array($this->request->params['controller'], array('user_inspections', 'organization_sites')) ? 'class="arrow open"' : 'class="arrow"'; ?>></span>
                    <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php echo $this->request->params['controller'] == 'organization_sites' ? 'class="active"' : ''; ?>>
                        <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'organization_sites', 'action' => 'index')); ?>">
                            <i class="fa fa-map-marker"></i> Sites
                        </a>
                    </li>
                    <li <?php echo $this->request->params['controller'] == 'user_inspections' ? 'class="active"' : ''; ?>>
                        <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'user_inspections', 'action' => 'index')); ?>">
                            <i class="fa fa-info"></i> Inspections
                        </a>
                    </li>
                </ul>
            </li>

            <li class="<?php echo $this->request->params['controller'] == 'reports' ? 'active' : ''; ?>">
                <a href="<?php echo $this->Html->url(array('plugin' => 'admin', 'controller' => 'reports', 'action' => 'index')); ?>">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Inspection Reports</span>
                    <span class="selected"></span>
                </a>
            </li>-->

        </ul>
    </div>
</div>
