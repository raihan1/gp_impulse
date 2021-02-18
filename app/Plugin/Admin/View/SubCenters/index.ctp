<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-globe"></i>
                    <span>Office</span>
                </li>
            </ul>
        </div>

        <?= $this->Session->flash();?>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>Office List
                        </div>
                        <div class="actions">

                            <?php echo $this->Html->link( '<i class="fa fa-plus"></i> <span class="hidden-480">Add New</span>', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'add' ), array( 'escape' => FALSE, 'class' => 'btn default yellow-stripe' ) ); ?>
                            <?php echo $this->Html->link( ' <i class="fa fa-upload"></i> <span class="hidden-480">Import Bulk ( <i class="fa fa-file-excel-o"></i> ) Office</span>', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'bulk_office_import' ), array( 'escape' => FALSE, 'class' => 'btn green' ) ); ?>

                            <?php echo $this->Html->link( ' <i class="fa fa-upload"></i> <span class="hidden-480">Import Bulk ( <i class="fa fa-file-excel-o"></i> ) Budget</span>', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'bulk_budget_import' ), array( 'escape' => FALSE, 'class' => 'btn blue' ) ); ?>

                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
                                <span></span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select</option>
                                    <option value="<?php echo ACTIVE ?>">Active</option>
                                    <option value="<?php echo INACTIVE ?>">Inactive</option>
                                    <option value="9">Delete</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i>
                                    Submit
                                </button>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="subc_table">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="2%" class="no-sort no-image">
                                            <input type="checkbox" class="group-checkable">
                                        </th>
                                        <th width="13%">Region Name</th>
                                        <th width="13%">Office Name</th>
                                        <th width="10%" class="text-center">80% Budget Exceed</th>
                                        <th width="10%" class="text-center">90% Budget Exceed</th>
                                        <th width="10%" class="text-center">100% Budget Exceed</th>
                                        <th width="15%" class="text-center">Status</th>
                                        <th width="15%" class="text-center no-sort">Actions</th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td></td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="r_name">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-filter input-sm" name="subc_name">
                                        </td>
                                        <td>
                                            <select name="eighty_percent_action" class="form-control form-filter input-sm">
                                                <option value="">Select Action</option>
                                                <option value="<?php echo YES; ?>">Block TR Creation</option>
                                                <option value="<?php echo NO; ?>">Show Warning</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="ninety_percent_action" class="form-control form-filter input-sm">
                                                <option value="">Select Action</option>
                                                <option value="<?php echo YES; ?>">Block TR Creation</option>
                                                <option value="<?php echo NO; ?>">Show Warning</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="hundred_percent_action" class="form-control form-filter input-sm">
                                                <option value="">Select Action</option>
                                                <option value="<?php echo YES; ?>">Block TR Creation</option>
                                                <option value="<?php echo NO; ?>">Show Warning</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="status" class="form-control form-filter input-sm">
                                                <option value="">Select Type</option>
                                                <option value="<?php echo ACTIVE; ?>">Active</option>
                                                <option value="<?php echo INACTIVE; ?>">Inactive</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i
                                                        class="fa fa-search"></i></button>
                                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i>
                                                </button>
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

<script type="text/javascript">
    $( document ).ready( function() {
        gp_warranty.data_table( 'subc_table', '<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'data' ) ); ?>' );
    } );
</script>