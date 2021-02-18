<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number"><?php echo $assigned; ?></div>
                        <div class="desc">Assigned Tickets</div>
                    </div>
                    <?php echo $this->Html->link( 'View more <i class="m-icon-swapright m-icon-white"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'assigned' ), array( 'escape' => FALSE, 'class' => 'more' ) ); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="dashboard-stat blue-hoki">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number"><?php echo $locked; ?></div>
                        <div class="desc">Locked Tickets</div>
                    </div>
                    <?php echo $this->Html->link( 'View more <i class="m-icon-swapright m-icon-white"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'locked' ), array( 'escape' => FALSE, 'class' => 'more' ) ); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="dashboard-stat purple-studio">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number"><?php echo $pending; ?></div>
                        <div class="desc">Pending Tickets</div>
                    </div>
                    <?php echo $this->Html->link( 'View more <i class="m-icon-swapright m-icon-white"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'pending' ), array( 'escape' => FALSE, 'class' => 'more' ) ); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="dashboard-stat green">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number"><?php echo $approved; ?></div>
                        <div class="desc">Approved Tickets</div>
                    </div>
                    <?php echo $this->Html->link( 'View more <i class="m-icon-swapright m-icon-white"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'approved' ), array( 'escape' => FALSE, 'class' => 'more' ) ); ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="dashboard-stat red-intense">
                    <div class="visual">
                        <i class="fa fa-bar-chart-o"></i>
                    </div>
                    <div class="details">
                        <div class="number"><?php echo $rejected; ?></div>
                        <div class="desc">Rejected Tickets</div>
                    </div>
                    <?php echo $this->Html->link( 'View more <i class="m-icon-swapright m-icon-white"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index', '#' => 'rejected' ), array( 'escape' => FALSE, 'class' => 'more' ) ); ?>
                </div>
            </div>
        </div>
    </div>
</div>