<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
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
                            <span class="caption-subject bold font-yellow-lemon uppercase"><i class="fa fa-ticket"></i> TR List </span>
                            <span class="caption-helper">&nbsp;&nbsp;</span>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'add' ) ); ?> "
                               class="btn default yellow-stripe">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-480">Add New</span>
                            </a>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#portlet_tab1" data-toggle="tab" id="pt1">
                                    Assigned</a>
                            </li>
                            <li>
                                <a href="#portlet_tab2" data-toggle="tab" id="pt2">
                                    Locked TR </a>
                            </li>
                            <li>
                                <a href="#portlet_tab3" data-toggle="tab" id="pt3">
                                    Pending TR </a>
                            </li>
                            <li>
                                <a href="#portlet_tab4" data-toggle="tab" id="pt4">
                                    Approved TR </a>
                            </li>
                            <li>
                                <a href="#portlet_tab5" data-toggle="tab" id="pt5">
                                    Rejected TR </a>
                            </li>
                        </ul>
                    </div>

                    <div class="portlet-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="portlet_tab1">
                                <div class="table-container">
                                    <div class="table-actions-wrapper">
										<span>
										</span>
                                        <!--<select class="table-group-action-input form-control input-inline input-small input-sm">
											<option value="">Select...</option>
											<option value="<?php echo ACTIVE; ?>">Active</option>
											<option value="<?php echo INACTIVE; ?>">Inactive</option>
										</select>
										<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="assign_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%" class="no-sort no-image">TR ID</th>
                                            <th width="10%">Supplier Name</th>
                                            <th width="12%">Supplier Category</th>
                                            <th width="12%">Site Name</th>
                                            <th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th>
                                            <th width="11%">TR Class</th>
                                            <th width="12%">Project Name</th>
                                            <th width="15%" class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td class="no-sort no-image"></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_category">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="site_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_group">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_number">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="tr_class">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="project_name">
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="portlet_tab2">
                                <div class="table-container">
                                    <div class="table-actions-wrapper">
										<span>
										</span>
                                        <!--<select class="table-group-action-input form-control input-inline input-small input-sm">
											<option value="">Select...</option>
											<option value="<?php echo ACTIVE; ?>">Active</option>
											<option value="<?php echo INACTIVE; ?>">Inactive</option>
										</select>
										<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="locked_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%" class="no-sort no-image">TR ID</th>
                                            <th width="10%">Supplier Name</th>
                                            <th width="12%">Supplier Category</th>
                                            <th width="12%">Site Name</th>
                                            <th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th>
                                            <th width="11%">TR Class</th>
                                            <th width="12%">Project Name</th>
                                            <th width="15%" class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td class="no-sort no-image"></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_category">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="site_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_group">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_number">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="tr_class">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="project_name">
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="portlet_tab3">
                                <div class="table-container">
                                    <div class="table-actions-wrapper">
										<span>
										</span>
                                        <!--<select class="table-group-action-input form-control input-inline input-small input-sm">
											<option value="">Select...</option>
											<option value="<?php echo ACTIVE; ?>">Active</option>
											<option value="<?php echo INACTIVE; ?>">Inactive</option>
										</select>
										<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="pending_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%" class="no-sort no-image">TR ID</th>
                                            <th width="10%">Supplier Name</th>
                                            <th width="12%">Supplier Category</th>
                                            <th width="12%">Site Name</th>
                                            <th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th>
                                            <th width="11%">TR Class</th>
                                            <th width="12%">Project Name</th>
                                            <th width="15%" class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td class="no-sort no-image"></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_category">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="site_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_group">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_number">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="tr_class">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="project_name">
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="portlet_tab4">
                                <div class="table-container">
                                    <div class="table-actions-wrapper">
										<span>
										</span>
                                        <!--<select class="table-group-action-input form-control input-inline input-small input-sm">
											<option value="">Select...</option>
											<option value="<?php echo ACTIVE; ?>">Active</option>
											<option value="<?php echo INACTIVE; ?>">Inactive</option>
										</select>
										<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover"
                                           id="approved_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%" class="no-sort no-image">TR ID</th>
                                            <th width="10%">Supplier Name</th>
                                            <th width="12%">Supplier Category</th>
                                            <th width="12%">Site Name</th>
                                            <th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th>
                                            <th width="11%">TR Class</th>
                                            <th width="12%">Project Name</th>
                                            <th width="15%" class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td class="no-sort no-image"></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_category">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="site_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_group">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_number">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="tr_class">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="project_name">
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="portlet_tab5">
                                <div class="table-container">
                                    <div class="table-actions-wrapper">
										<span>
										</span>
                                        <!--<select class="table-group-action-input form-control input-inline input-small input-sm">
											<option value="">Select...</option>
											<option value="<?php echo ACTIVE; ?>">Active</option>
											<option value="<?php echo INACTIVE; ?>">Inactive</option>
										</select>
										<button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>-->
                                    </div>
                                    <table class="table table-striped table-bordered table-hover"
                                           id="rejected_tr_table">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%" class="no-sort no-image">TR ID</th>
                                            <th width="10%">Supplier Name</th>
                                            <th width="12%">Supplier Category</th>
                                            <th width="12%">Site Name</th>
                                            <th width="11%">Asset Group</th>
                                            <th width="12%">Asset Number</th>
                                            <th width="11%">TR Class</th>
                                            <th width="12%">Project Name</th>
                                            <th width="15%" class="no-sort text-center">Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td class="no-sort no-image"></td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="supplier_category">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="site_name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_group">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="asset_number">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="tr_class">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-filter input-sm"
                                                       name="project_name">
                                            </td>
                                            <td class="text-center">
                                                <div class="margin-bottom-5">
                                                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i></button>
                                                    <button class="btn btn-sm red filter-cancel"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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

        /*$('#assign_tr_table tr.filter td input[name=supplier_name]').on('keypress', function() {
         if($(this).val().length >= 2) {
         $('.filter-submit').trigger('click');
         }
         });*/

        $( '#pt1' ).on( 'click', function() {
            var table1 = $( '#assign_tr_table' ).DataTable();
            table1.destroy();
            gp_warranty.data_table( 'assign_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'assign_tr_data' ) ); ?>' );
        } );

        $( '#pt2' ).on( 'click', function() {
            var table2 = $( '#locked_tr_table' ).DataTable();
            table2.destroy();
            gp_warranty.data_table( 'locked_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'locked_tr_data' ) ); ?>' );
        } );

        $( '#pt3' ).on( 'click', function() {
            var table2 = $( '#pending_tr_table' ).DataTable();
            table2.destroy();
            gp_warranty.data_table( 'pending_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'pending_tr_data' ) ); ?>' );
        } );

        $( '#pt4' ).on( 'click', function() {
            var table2 = $( '#approved_tr_table' ).DataTable();
            table2.destroy();
            gp_warranty.data_table( 'approved_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'approved_tr_data' ) ); ?>' );
        } );

        $( '#pt5' ).on( 'click', function() {
            var table2 = $( '#rejected_tr_table' ).DataTable();
            table2.destroy();
            gp_warranty.data_table( 'rejected_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'rejected_tr_data' ) ); ?>' );
        } );

        gp_warranty.data_table( 'assign_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'assign_tr_data' ) ); ?>' );
    } );
</script>