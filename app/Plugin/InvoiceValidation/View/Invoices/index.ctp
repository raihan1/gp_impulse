<style type="text/css">
    table#invoice_table td:nth-child(6) {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
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
                            <i class="fa fa-money"></i> Invoice List
                        </div>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="table-container">
                            <table class="table table-striped table-bordered table-hover" id="invoice_table">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="30%">Reff No</th>
                                        <th>Supplier Name</th>
                                        <th class="text-right">Amount</th>
                                        <th>Invoice Date</th>
                                        <th>Status</th>
                                        <th width="10%" class="no-sort text-center">Action</th>
                                    </tr>
                                    <tr role="row" class="filter">
                                        <td><input type="text" class="form-control form-filter input-sm" name="invoice_id" /></td>
                                        <td>
                                            <?php
                                            echo $this->Form->input( '', array(
                                                'options' => $supplierList,
                                                'empty'   => 'Select',
                                                'name'    => 'supplier',
                                                'class'   => 'form-control form-filter input-sm',
                                                'div'     => FALSE,
                                                'label'   => FALSE,
                                            ) );
                                            ?>
                                        </td>
                                        <td class="text-right">&nbsp;</td>
                                        <td><input type="text" class="form-control form-filter input-sm date-picker" name="created" /></td>
                                        <td>
                                            <?php
                                            echo $this->Form->input( '', array(
                                                'options' => array(
                                                    -1 => 'Pending',
                                                    1  => 'Approved',
                                                    0  => 'Rejected',
                                                ),
                                                'empty'   => 'Select',
                                                'name'    => 'status',
                                                'class'   => 'form-control form-filter input-sm',
                                                'div'     => FALSE,
                                                'label'   => FALSE,
                                            ) );
                                            ?>
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
    var invoiceDataURL = '<?php echo $this->Html->url( array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'data' ) ); ?>';
    
    $( document ).ready( function() {
        gp_warranty.data_table( 'invoice_table', invoiceDataURL );
        gp_warranty.date_picker();
    } );
</script>