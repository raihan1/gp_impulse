<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Invoiceable TR List</span>
                </li>
            </ul>
        </div>
        
        <?php echo $this->Session->flash(); ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold font-yellow-lemon uppercase"><i class="fa fa-ticket"></i> Invoiceable TR List</span>
                            <span class="caption-helper">&nbsp;&nbsp;</span>
                        </div>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="budget-container">
                            <?php echo $this->element( 'tickets/budget', array( 'month' => 'Last' ), array( 'plugin' => 'tr_validation' ) ); ?>
                        </div>
                        <div class="table-container">
                            <div class="table-actions-wrapper">
                                <span></span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select</option>
                                    <option value="mark_invoiceable">Mark for Invoice</option>
                                    <option value="unmark_invoiceable">Un-mark for Invoice</option>
                                    <option value="is_subtotal">Sub Total</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="invoiceable_tr_table">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="5%" class="no-sort no-image"><input type="checkbox" class="group-checkable" /></th>
                                        <th>TR ID</th>
                                        <th>Supplier Name</th>
                                        <th>Site Name</th>
                                        <!--th>Asset Group</th-->
                                        <th>TR Class</th>
                                        <th>Received at supplier site</th>
                                        <th>Marked for Invoice?</th>
                                        <th width="10%" class="no-sort text-center">Action</th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td class="no-sort no-image">&nbsp;</td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="ticket_id" /></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="supplier_name" /></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="site_name" /></td>
                                        <!--td><input type="text" class="form-control form-filter input-sm" name="asset_group_name" /></td-->
                                        <td><input type="text" class="form-control form-filter input-sm" name="tr_class_name" /></td>
                                        <td><input type="text" class="form-control form-filter input-sm" name="received_at_supplier" /></td>
                                        <td>
                                            <select name="is_invoiceable" class="form-control form-filter input-sm">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
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
                            <div style="margin-top: 15px">
                                <b>Total: <?php echo number_format( $total, 4 ); ?></b>
                                <b style="margin-left: 20px;">Subtotal: <span class="sub_total"><?php echo number_format( $total, 4 ); ?></span></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var subTotal = true;
    
    $( document ).ready( function() {
        gp_warranty.data_table( 'invoiceable_tr_table', '<?php echo $this->Html->url( array( 'plugin' => 'tr_validation', 'controller' => 'invoices', 'action' => 'data' ) ); ?>' );
    } );
</script>