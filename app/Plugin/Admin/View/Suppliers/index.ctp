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
                    <i class="fa fa-adjust"></i>
                    <span>Suppliers</span>
                </li>
            </ul>
        </div>

        <?= $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-adjust"></i>Supplier List
                        </div>
                        <div class="actions">
                            <?php echo $this->Html->link( '<i class="fa fa-plus"></i> <span class="hidden-480">Add New</span>', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'add' ), array( 'escape' => FALSE, 'class' => 'btn default yellow-stripe' ) ); ?>

                            <?php echo $this->Html->link( ' <i class="fa fa-upload"></i> <span class="hidden-480">Import Bulk ( <i class="fa fa-file-excel-o"></i> ) Supplier</span>', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'bulk_import' ), array( 'escape' => FALSE, 'class' => 'btn green' ) ); ?>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
                                <span></span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select</option>
                                    <option value="<?php echo ACTIVE; ?>">Active</option>
                                    <option value="<?php echo INACTIVE; ?>">Inactive</option>
                                    <option value="9">Delete</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i>
                                    Submit
                                </button>
                            </div>
                            <table style="font-size: 13px" class="table table-striped table-bordered table-hover"
                                   id="supplier_table">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="2%" class="no-sort no-image">
                                        <input type="checkbox" class="group-checkable">
                                    </th>
                                    <th width="17%">Code</th>
                                    <th width="17%">Name</th>
                                    <th width="17%">Email</th>
                                    <th width="17%">Phone</th>
                                    <th width="17%" class="text-center">Status</th>
                                    <th width="15%" class="text-center no-sort">Actions</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="code">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="name">
                                    </td>
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="phone">
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

<script type="text/javascript">
    $( document ).ready( function() {
        gp_warranty.data_table( 'supplier_table', '<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'data' ) ); ?>' );
    } );
</script>