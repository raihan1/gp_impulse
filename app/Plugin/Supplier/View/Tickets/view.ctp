<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'supplier', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Ticket Details</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Ticket Details
                </div>
            </div>
            
            <div class="portlet-body" style="padding: 0px">
                <?php echo $this->element( 'details/tickets/details', array(), array( 'plugin' => 'supplier' ) ); ?>
                <?php echo $this->element( 'list/tickets/services', array(), array( 'plugin' => 'supplier' ) ); ?>
                
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-9 pull-right" style="margin-right: 15px; margin-bottom: 5px;">
                            <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'index', '#' => $this->Lookup->decideTab( $data ) ), array( 'escape' => FALSE, 'class' => 'btn red pull-right' ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>