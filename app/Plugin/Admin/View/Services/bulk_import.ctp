<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-adjust"></i>
                    <?php echo $this->Html->link('Item', array( 'plugin' => 'admin', 'controller' => 'services', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Item</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'Service', array(
                    'id'            => 'service-form',
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
                    'plugin' => 'admin',
                    'controller' => 'services',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/ITEM_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($serviceBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
                                    <th class="text-left">Supplier Code</th>
<!--                                    <th class="text-left">Asset Group</th>-->
                                    <th class="text-left">Item Name</th>
                                    <th class="text-left">Item Description</th>
                                    <th class="text-left">Unit Price</th>
                                    <th class="text-left">Vat</th>
                                    <th class="text-left">Working Days</th>
                                    <th class="text-left">Working Hours</th>
                                    <th class="text-left">Main Type</th>
                                    <th class="text-left">End Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                $service_site_exist = 1;
                                foreach($serviceBulkData as $svc){
                                    if($svc['Service']['supplierStatus'] == 1):
                                        $supplierStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $supplierStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($svc['Service']['serviceStatus'] == 1):
                                        $serviceStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Not Exits."></i>';
                                    else:
                                        $serviceStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Exits."></i>';
                                    endif;

                                    if($svc['Service']['supplierStatus'] == 1):
                                        $statusColor  = 'style="background-color : #e2f5d9;"';
                                    else:
                                        $statusColor = '';
                                    endif;

                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="supplier_id" style="display:none;"><?= $svc['Service']['supplier_id']; ?></td>
                                        <td id="supplierStatus" style="display:none;"><?= $svc['Service']['supplierStatus']; ?></td>
                                        <td id="srv_id" style="display:none;"><?= $svc['Service']['service_id']; ?></td>
                                        <td id="srv_name" style="display:none;"><?= $svc['Service']['service_name']; ?></td>
                                        <td><?= $supplierStatus.$svc['Service']['supplier_code']; ?></td>
<!--                                        <td id="ast_group">--><?//= $svc['Service']['asset_group']; ?><!--</td>-->
                                        <td><?= $serviceStatus.$svc['Service']['service_name']; ?></td>
                                        <td id="srv_desc"><?= $svc['Service']['service_desc']; ?></td>
                                        <td id="srv_prc"><?= $svc['Service']['service_price']; ?></td>
                                        <td id="srv_vat"><?= $svc['Service']['vat']; ?></td>
                                        <td id="srv_pv" style="display:none;"><?= $svc['Service']['vat_plus_price']; ?></td>
                                        <td id="srv_wd"><?= $svc['Service']['warranty_days']; ?></td>
                                        <td id="srv_wh"><?= $svc['Service']['warranty_hours']; ?></td>
                                        <td id="srv_mt"><?= $svc['Service']['main_type']; ?></td>
                                        <td id="srv_ed"><?= $svc['Service']['end_date']; ?></td>
                                        <td id="srv_site_exist" style="display:none;"><?= $service_site_exist; ?></td>
                                    </tr>
                                <?php
                                    $sn++;
                                    $service_site_exist= 1;
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
                "supplier_id"    : $(tr).find('td:eq(1)').text(),
                "supplierStatus" : $(tr).find('td:eq(2)').text(),
                "srv_id"         : $(tr).find('td:eq(3)').text(),
                "srv_name"       : $(tr).find('td:eq(4)').text(),
//                "ast_group"      : $(tr).find('td:eq(6)').text(),
                "srv_desc"       : $(tr).find('td:eq(7)').text(),
                "srv_prc"        : $(tr).find('td:eq(8)').text(),
                "srv_vat"        : $(tr).find('td:eq(9)').text(),
                "srv_pv"         : $(tr).find('td:eq(10)').text(),
                "srv_wd"         : $(tr).find('td:eq(11)').text(),
                "srv_wh"         : $(tr).find('td:eq(12)').text(),
                "srv_mt"         : $(tr).find('td:eq(13)').text(),
                "srv_ed"         : $(tr).find('td:eq(14)').text(),
                "srv_site_exist"         : $(tr).find('td:eq(15)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'services', 'action' => 'bulk_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
//                window.location = '<?//= Router::url(array('controller' => 'services', 'action' => 'index')); ?>//';
            }
        });
    });
</script>