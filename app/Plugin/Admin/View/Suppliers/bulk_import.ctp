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
                    <?php echo $this->Html->link('Suppliers', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Supplier</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'Supplier', array(
                    'id'            => 'supplier-form',
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
                    'controller' => 'suppliers',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/SUPPLIER_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($supplierBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Email</th>
                                    <th class="text-left">Phone</th>
                                    <th class="text-left">Address</th>
                                    <th class="text-left">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($supplierBulkData as $sup){
                                    if($sup['Supplier']['supplierStatus'] == 1):
                                        $supplierStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Not Exits."></i>';
                                    else:
                                        $supplierStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Exits."></i>';
                                    endif;

                                    $statusColor  = 'style="background-color : #e2f5d9;"';
                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="supplier_id" style="display:none;"><?= $sup['Supplier']['supplier_id']; ?></td>
                                        <td id="supplier_code" style="display:none;"><?= $sup['Supplier']['supplier_code']; ?></td>
                                        <td><?= $supplierStatus.$sup['Supplier']['supplier_code']; ?></td>
                                        <td id="supplier_name"><?= $sup['Supplier']['supplier_name']; ?></td>
                                        <td id="supplier_email"><?= $sup['Supplier']['supplier_email']; ?></td>
                                        <td id="supplier_phn"><?= $sup['Supplier']['supplier_phn']; ?></td>
                                        <td id="supplier_addr"><?= $sup['Supplier']['supplier_addr']; ?></td>
                                        <td id="supplier_rem"><?= $sup['Supplier']['supplier_rem']; ?></td>
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
                "supplier_id"    : $(tr).find('td:eq(1)').text(),
                "supplier_code"  : $(tr).find('td:eq(2)').text(),
                "supplier_name"  : $(tr).find('td:eq(4)').text(),
                "supplier_email" : $(tr).find('td:eq(5)').text(),
                "supplier_phn"   : $(tr).find('td:eq(6)').text(),
                "supplier_addr"  : $(tr).find('td:eq(7)').text(),
                "supplier_rem"   : $(tr).find('td:eq(8)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'suppliers', 'action' => 'bulk_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'suppliers', 'action' => 'index')); ?>';
            }
        });
    });
</script>