<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Ticket List</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <span class="caption-subject bold font-yellow-lemon uppercase"><i class="fa fa-ticket"></i> Ticket List </span>
                            <span class="caption-helper">&nbsp;&nbsp;</span>
                            <?php echo $this->Html->link( '<i class="fa fa-plus"></i> <span class="hidden-480">Add New</span>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'add' ), array( 'escape' => FALSE, 'class' => 'btn default yellow-stripe' ) ); ?>

                            <?php echo $this->Html->link( ' <i class="fa fa-upload"></i> <span class="hidden-480">Import Bulk ( <i class="fa fa-file-excel-o"></i> ) Ticket</span>', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'bulk_import' ), array( 'escape' => FALSE, 'class' => 'btn green' ) ); ?>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#assigned" data-toggle="tab" id="assigned_tab">Assigned</a></li>
                            <li><a href="#locked" data-toggle="tab" id="locked_tab">Locked</a></li>
                            <li><a href="#pending" data-toggle="tab" id="pending_tab">Pending</a></li>
                            <li><a href="#approved" data-toggle="tab" id="approved_tab">Approved</a></li>
                            <li><a href="#rejected" data-toggle="tab" id="rejected_tab">Rejected</a></li>
                        </ul>
                    </div>
                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="assigned">
                                <div class="table-container">
                                    <?php echo $this->element( 'list/tickets/common', array( 'tableId' => 'assign_tr_table' ), array( 'plugin' => 'tr_creation' ) ); ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="locked">
                                <div class="table-container">
                                    <?php echo $this->element( 'list/tickets/common', array( 'tableId' => 'locked_tr_table' ), array( 'plugin' => 'tr_creation' ) ); ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="pending">
                                <div class="table-container">
                                    <?php echo $this->element( 'list/tickets/common', array( 'tableId' => 'pending_tr_table' ), array( 'plugin' => 'tr_creation' ) ); ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="approved">
                                <div class="table-container">
                                    <?php echo $this->element( 'list/tickets/common', array( 'tableId' => 'approved_tr_table' ), array( 'plugin' => 'tr_creation' ) ); ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="rejected">
                                <div class="table-container">
                                    <?php echo $this->element( 'list/tickets/common', array( 'tableId' => 'rejected_tr_table' ), array( 'plugin' => 'tr_creation' ) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var assigned_data_url = '<?php echo $this->Html->url( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'data', 'assigned' ) ); ?>';
    var locked_data_url   = '<?php echo $this->Html->url( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'data', 'locked' ) ); ?>';
    var pending_data_url  = '<?php echo $this->Html->url( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'data', 'pending' ) ); ?>';
    var approved_data_url = '<?php echo $this->Html->url( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'data', 'approved' ) ); ?>';
    var rejected_data_url = '<?php echo $this->Html->url( array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'data', 'rejected' ) ); ?>';
    
    $( document ).ready( function() {
        $( '.datepicker' ).datepicker( {
            format        : 'yyyy-mm-dd',
            rtl           : Metronic.isRTL(),
            pickerPosition: Metronic.isRTL() ? "bottom-right" : "bottom-left",
            autoclose     : true
        } );
        
        $( '#assigned_tab' ).on( 'click', function() {
            var assigned_table = $( '#assign_tr_table' ).DataTable();
            assigned_table.destroy();
            gp_warranty.data_table( 'assign_tr_table', assigned_data_url );
        } );
        
        $( '#locked_tab' ).on( 'click', function() {
            var locked_table = $( '#locked_tr_table' ).DataTable();
            locked_table.destroy();
            gp_warranty.data_table( 'locked_tr_table', locked_data_url );
        } );
        
        $( '#pending_tab' ).on( 'click', function() {
            var pending_table = $( '#pending_tr_table' ).DataTable();
            pending_table.destroy();
            gp_warranty.data_table( 'pending_tr_table', pending_data_url );
        } );
        
        $( '#approved_tab' ).on( 'click', function() {
            var approved_table = $( '#approved_tr_table' ).DataTable();
            approved_table.destroy();
            gp_warranty.data_table( 'approved_tr_table', approved_data_url );
        } );
        
        $( '#rejected_tab' ).on( 'click', function() {
            var rejected_table = $( '#rejected_tr_table' ).DataTable();
            rejected_table.destroy();
            gp_warranty.data_table( 'rejected_tr_table', rejected_data_url );
        } );
        
        gp_warranty.data_table( 'assign_tr_table', assigned_data_url );
        
        var tab = window.location.hash;
        
        if( tab == '#locked' ) {
            var locked_table = $( '#locked_tr_table' ).DataTable();
            locked_table.destroy();
            gp_warranty.data_table( 'locked_tr_table', locked_data_url );
        }
        else if( tab == '#pending' ) {
            var pending_table = $( '#pending_tr_table' ).DataTable();
            pending_table.destroy();
            gp_warranty.data_table( 'pending_tr_table', pending_data_url );
        }
        else if( tab == '#approved' ) {
            var approved_table = $( '#approved_tr_table' ).DataTable();
            approved_table.destroy();
            gp_warranty.data_table( 'approved_tr_table', approved_data_url );
        }
        else if( tab == '#rejected' ) {
            var rejected_table = $( '#rejected_tr_table' ).DataTable();
            rejected_table.destroy();
            gp_warranty.data_table( 'rejected_tr_table', rejected_data_url );
        }
    } );
</script>