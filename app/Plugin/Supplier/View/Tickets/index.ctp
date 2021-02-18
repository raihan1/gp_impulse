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
                    <span>TR List</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <span class="caption-subject bold font-yellow-lemon uppercase"><i class="fa fa-ticket"></i> TR List</span>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo $this->Form->input( '', array(
                                'name'    => 'data[sub_center]',
                                'options' => $subCenterList,
                                'empty'   => 'Select a Office',
                                'class'   => 'form-control',
                                'id'      => 'sub_center',
                                'value'   => $sub_center,
                            ) );
                            ?>
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
                                    <table class="table table-striped table-bordered table-hover" id="assigned_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="12%">TR ID</th>
                                            <th width="10%">User Name</th>
                                            <th width="16%">Supplier Category</th>
                                            <th width="15%">Site Name</th>
                                            <!--th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th-->
                                            <th width="16%">TR Class</th>
                                            <th width="16%">Received at supplier site</th>
                                            <th class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="id" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="name" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="supplier_category" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="site_name" />
                                            </td>
                                            <!--td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_group" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_number" />
                                            </td-->
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="tr_class" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" />
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel">
                                                        <i class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="locked">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover" id="locked_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="12%">TR ID</th>
                                            <th width="10%">User Name</th>
                                            <th width="16%">Supplier Category</th>
                                            <th width="15%">Site Name</th>
                                            <!--th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th-->
                                            <th width="16%">TR Class</th>
                                            <th width="16%">Received at supplier site</th>
                                            <th class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="id" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="name" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="supplier_category" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="site_name" />
                                            </td>
                                            <!--td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_group" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_number" />
                                            </td-->
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="tr_class" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" />
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel">
                                                        <i class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="pending">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover" id="pending_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="12%">TR ID</th>
                                            <th width="10%">User Name</th>
                                            <th width="16%">Supplier Category</th>
                                            <th width="15%">Site Name</th>
                                            <!--th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th-->
                                            <th width="16%">TR Class</th>
                                            <th width="16%">Received at supplier site</th>
                                            <th class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="id" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="name" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="supplier_category" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="site_name" />
                                            </td>
                                            <!--td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_group" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_number" />
                                            </td-->
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="tr_class" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" />
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel">
                                                        <i class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="approved">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover" id="approved_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="12%">TR ID</th>
                                            <th width="10%">User Name</th>
                                            <th width="16%">Supplier Category</th>
                                            <th width="15%">Site Name</th>
                                            <!--th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th-->
                                            <th width="16%">TR Class</th>
                                            <th width="16%">Received at supplier site</th>
                                            <th class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="id" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="name" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="supplier_category" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="site_name" />
                                            </td>
                                            <!--td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_group" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_number" />
                                            </td-->
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="tr_class" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" />
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel">
                                                        <i class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane" id="rejected">
                                <div class="table-container">
                                    <table class="table table-striped table-bordered table-hover" id="rejected_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="12%">TR ID</th>
                                            <th width="10%">User Name</th>
                                            <th width="16%">Supplier Category</th>
                                            <th width="15%">Site Name</th>
                                            <!--th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th-->
                                            <th width="16%">TR Class</th>
                                            <th width="16%">Received at supplier site</th>
                                            <th class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="id" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="name" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="supplier_category" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="site_name" />
                                            </td>
                                            <!--td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_group" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="asset_number" />
                                            </td-->
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm" name="tr_class" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" />
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom">
                                                        <i class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel">
                                                        <i class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
    $( document ).ready( function() {
        gp_warranty.select_options( 'sub_center' );
        
        $( '#sub_center' ).on( 'change', function() {
            var tab   = $( '.nav-tabs li.active a' ).attr( 'id' );
            var term  = tab.substring( 0, tab.length - 4 );
            var table = $( '#' + term + '_tr_table' ).DataTable();
            table.ajax.reload();
        } );
        
        $( '.datepicker' ).datepicker( {
            format        : 'yyyy-mm-dd',
            rtl           : Metronic.isRTL(),
            pickerPosition: Metronic.isRTL() ? 'bottom-right' : 'bottom-left',
            autoclose     : true
        } );
        
        $( '#assigned_tab' ).on( 'click', function() {
            var assigned_table = $( '#assigned_tr_table' ).DataTable();
            assigned_table.destroy();
            gp_warranty.assign_data_table( 'assigned_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'assign_tr_data' ) ); ?>' );
        } );
        
        $( '#locked_tab' ).on( 'click', function() {
            var locked_table = $( '#locked_tr_table' ).DataTable();
            locked_table.destroy();
            gp_warranty.lock_data_table( 'locked_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'locked_tr_data' ) ); ?>' );
        } );
        
        $( '#pending_tab' ).on( 'click', function() {
            var pending_table = $( '#pending_tr_table' ).DataTable();
            pending_table.destroy();
            gp_warranty.data_table( 'pending_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'pending_tr_data' ) ); ?>' );
        } );
        
        $( '#approved_tab' ).on( 'click', function() {
            var approved_table = $( '#approved_tr_table' ).DataTable();
            approved_table.destroy();
            gp_warranty.data_table( 'approved_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'approved_tr_data' ) ); ?>' );
        } );
        
        $( '#rejected_tab' ).on( 'click', function() {
            var rejected_table = $( '#rejected_tr_table' ).DataTable();
            rejected_table.destroy();
            gp_warranty.data_table( 'rejected_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'rejected_tr_data' ) ); ?>' );
        } );
        
        gp_warranty.assign_data_table( 'assigned_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'assign_tr_data' ) ); ?>' );
        
        var tab = window.location.hash;
        
        if( tab == '#locked' ) {
            var locked_table = $( '#locked_tr_table' ).DataTable();
            locked_table.destroy();
            gp_warranty.data_table( 'locked_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'locked_tr_data' ) ); ?>' );
        }
        else if( tab == '#pending' ) {
            var pending_table = $( '#pending_tr_table' ).DataTable();
            pending_table.destroy();
            gp_warranty.data_table( 'pending_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'pending_tr_data' ) ); ?>' );
        }
        else if( tab == '#approved' ) {
            var approved_table = $( '#approved_tr_table' ).DataTable();
            approved_table.destroy();
            gp_warranty.data_table( 'approved_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'approved_tr_data' ) ); ?>' );
        }
        else if( tab == '#rejected' ) {
            var rejected_table = $( '#rejected_tr_table' ).DataTable();
            rejected_table.destroy();
            gp_warranty.data_table( 'rejected_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'supplier', 'controller' => 'tickets', 'action' => 'rejected_tr_data' ) ); ?>' );
        }
    } );
</script>