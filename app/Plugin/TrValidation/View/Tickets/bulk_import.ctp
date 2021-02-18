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
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Ticket</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'Ticket', array(
                    'id'            => 'ticket-form',
                    'class'         => 'form-horizontal',
                    'type'          => 'file',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                ?>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="fileinput fileinput-new" data-provides="fileinput" style="float: left">
                            <div class="input-group input-large">
                                <div class="form-control uneditable-input" data-trigger="fileinput">
                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn default btn-file">
                                    <span class="fileinput-new">Select file</span>
                                    <span class="fileinput-exists">Change</span>
                                    <?php echo $this->Form->input( 'file_name', array( 'type' => 'file', 'placeholder' => 'Upload a xls file' ) ); ?>
                                </span>
                                <a href="#" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                        <button type="submit" class="btn green" style="margin-left: 4px"><i class="fa fa-upload"></i> Upload</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="col-md-4 form-actions text-right">
                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Back', array(
                    'plugin' => 'tr_validation',
                    'controller' => 'tickets',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/TICKET_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($ticketBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
                                    <th class="text-left">Region</th>
                                    <th class="text-left">Office</th>
                                    <th class="text-left">Site</th>
                                    <!--th class="text-left">Asset Group</th-->
                                    <th class="text-left">TR Class</th>
                                    <th class="text-left">Supplier Name</th>
                                    <th class="text-left">Supplier Category</th>
                                    <th class="text-left">Received at Supplier</th>
                                    <th class="text-left">Comment</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($ticketBulkData as $tkt){
                                    if($tkt['Ticket']['officeStatus'] == 1):
                                        $officeStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $officeStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($tkt['Ticket']['siteStatus'] == 1):
                                        $siteStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $siteStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($tkt['Ticket']['trClassStatus'] == 1):
                                        $trClassStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $trClassStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($tkt['Ticket']['supStatus'] == 1):
                                        $supStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $supStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($tkt['Ticket']['supCatStatus'] == 1):
                                        $supCatStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $supCatStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if(($tkt['Ticket']['officeStatus'] == 1) &&
                                        ($tkt['Ticket']['siteStatus'] == 1) &&
                                        ($tkt['Ticket']['trClassStatus'] == 1) &&
                                        ($tkt['Ticket']['supStatus'] == 1) &&
                                        ($tkt['Ticket']['supCatStatus'] == 1)):

                                        $statusColor  = 'style="background-color : #e2f5d9;"';
                                    else:
                                        $statusColor = '';
                                    endif;

                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="officeStatus" style="display:none;"><?= $tkt['Ticket']['officeStatus']; ?></td>
                                        <td id="siteStatus" style="display:none;"><?= $tkt['Ticket']['siteStatus']; ?></td>
                                        <td id="trClassStatus" style="display:none;"><?= $tkt['Ticket']['trClassStatus']; ?></td>
                                        <td id="supStatus" style="display:none;"><?= $tkt['Ticket']['supStatus']; ?></td>
                                        <td id="supCatStatus" style="display:none;"><?= $tkt['Ticket']['supCatStatus']; ?></td>
                                        <td id="region"><?= $tkt['Ticket']['region']; ?></td>
                                        <td><?= $officeStatus.$tkt['Ticket']['sub_center']; ?></td>
                                        <td><?= $siteStatus.$tkt['Ticket']['site']; ?></td>
                                        <td id="asset_group" style="display:none;"><?= $tkt['Ticket']['asset_group']; ?></td>
                                        <td><?= $trClassStatus.$tkt['Ticket']['tr_class']; ?></td>
                                        <td><?= $supStatus.$tkt['Ticket']['supplier']; ?></td>
                                        <td><?= $supCatStatus.$tkt['Ticket']['supplier_category']; ?></td>
                                        <td id="sub_center" style="display:none;"><?= $tkt['Ticket']['sub_center']; ?></td>
                                        <td id="site" style="display:none;"><?= $tkt['Ticket']['site']; ?></td>
                                        <td id="tr_class" style="display:none;"><?= $tkt['Ticket']['tr_class']; ?></td>
                                        <td id="supplier" style="display:none;"><?= $tkt['Ticket']['supplier']; ?></td>
                                        <td id="supplier_category" style="display:none;"><?= $tkt['Ticket']['supplier_category']; ?></td>
                                        <td id="received_at_supplier"><?= $tkt['Ticket']['received_at_supplier']; ?></td>
                                        <td id="comment"><?= $tkt['Ticket']['comment']; ?></td>
                                    </tr>
                                    <?php
                                    $sn++;
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="form-actions text-center">
                                <button type="submit" class="btn green" id="insertAll">Insert all</button>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#form").submit(function(event){
        event.preventDefault();
        var TableData = new Array();

        $('#sourceTable tr').each(function(row, tr){
            TableData[row]={
                "officeStatus"         : $(tr).find('td:eq(1)').text(),
                "siteStatus"           : $(tr).find('td:eq(2)').text(),
                "trClassStatus"        : $(tr).find('td:eq(3)').text(),
                "supStatus"            : $(tr).find('td:eq(4)').text(),
                "supCatStatus"         : $(tr).find('td:eq(5)').text(),
                "region"               : $(tr).find('td:eq(6)').text(),
                "asset_group"          : $(tr).find('td:eq(9)').text(),
                "sub_center"           : $(tr).find('td:eq(13)').text(),
                "site"                 : $(tr).find('td:eq(14)').text(),
                "tr_class"             : $(tr).find('td:eq(15)').text(),
                "supplier"             : $(tr).find('td:eq(16)').text(),
                "supplier_category"    : $(tr).find('td:eq(17)').text(),
                "received_at_supplier" : $(tr).find('td:eq(18)').text(),
                "comment"              : $(tr).find('td:eq(19)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'tickets', 'action' => 'bulk_import_post')); ?>',
            data: { pTableData : TableData },
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'tickets', 'action' => 'index')); ?>';
            }
        });
    });
</script>