<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search"></i> Tickets
        </div>
    </div>
    
    <div class="portlet-body">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right">
                        <?php echo $this->Html->link( 'Export to Excel', array( 'plugin' => 'invoice_validation', 'controller' => 'reports', 'action' => $action, '?' => http_build_query( $_REQUEST ) ) ); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <table id="datatableReport" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>TR ID</th>
                    <th>Supplier</th>
                    <th>Site</th>
                    <th>Asset Group</th>
                    <th>Asset Number</th>
                    <th>TR Class</th>
                    <th>Received at Supplier Site</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $data as $d ) { ?>
                    <tr>
                        <td><?php echo $d['Ticket']['id']; ?></td>
                        <td><?php echo $d['Ticket']['supplier']; ?></td>
                        <td><?php echo $d['Ticket']['site']; ?></td>
                        <td><?php echo $d['Ticket']['asset_group']; ?></td>
                        <td><?php echo $d['Ticket']['asset_number']; ?></td>
                        <td><?php echo $d['Ticket']['tr_class']; ?></td>
                        <td><?php echo $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>