<style type="text/css">
    .table-group-actions {
        float: none !important;
        display: none;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'invoice_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-money"></i>
                    <span>Invoice List</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-money"></i>Invoice List
                        </div>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="invoice_table">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="5%" class="no-sort no-image">ID</th>
                                        <th width="25%">Reff No</th>
                                        <th width="20%">Supplier Name</th>
                                        <th width="10%">Amount</th>
                                        <th width="15%">Invoice Date</th>
                                        <th width="15%">Status</th>
                                        <th width="10%" class="no-sort text-center">Action</th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td>&nbsp;</td>
                                        <td><input type="text" name="ref_no" class="form-control form-filter input-sm" /> </td>
                                        <td><input type="text" name="supplier_name" class="form-control form-filter input-sm" /></td>
                                        <td>&nbsp;</td>
                                        <td><input type="text" name="date" class="form-control form-filter input-sm" /></td>
                                        <td>
                                            <select name="status" class="form-control form-filter input-sm">
                                                <option value="">Select</option>
                                                <option value="-1">Pending</option>
                                                <option value="1">Approved</option>
                                                <option value="0">Rejected</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <div class="margin-bottom-5">
                                                <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
                                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i></button>
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
        gp_warranty.data_table( 'invoice_table', '<?php echo $this->Html->url( array( 'plugin' => 'invoice_creation', 'controller' => 'invoices', 'action' => 'data' ) ); ?>' );
    } );
</script>