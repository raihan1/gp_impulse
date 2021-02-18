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
                        <?php echo $this->Html->link( 'Export to Excel', array( 'plugin' => 'security', 'controller' => 'reports', 'action' => 'export_excel', '?' => http_build_query( $_REQUEST ) ) ); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <table id="datatableReport" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>TR ID</th>
                <th>Supplier Name</th>
                <th>Site Name</th>
<!--                <th>Asset Group</th>-->
<!--                <th>Asset Number</th>-->
                <th>TR Class</th>
                <th>Received at supplier site</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if( !empty( $data ) ) {
                foreach( $data as $d ) {
                    ?>
                    <tr>
                        <td><?php echo $d['Ticket']['id']; ?></td>
                        <td><?php echo $d['Supplier']['name']; ?></td>
                        <td><?php echo $d['Site']['site_name']; ?></td>
<!--                        <td>--><?php //echo $d['AssetGroup']['asset_group_name']; ?><!--</td>-->
<!--                        <td>--><?php //echo $d['AssetNumber']['asset_number']; ?><!--</td>-->
                        <td><?php echo $d['TrClass']['tr_class_name']; ?></td>
                        <td><?php echo $d['Ticket']['received_at_supplier']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>