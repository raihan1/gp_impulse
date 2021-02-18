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
                    <i class="fa fa-user"></i>
                    <?php echo $this->Html->link('Users', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Users</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'User', array(
                    'id'            => 'region-form',
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
                    'controller' => 'users',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/USER_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($userBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
                                    <th class="text-left">Email</th>
                                    <th class="text-left">Role</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-left">Designation</th>
                                    <th class="text-left">Phone</th>
                                    <th class="text-left">Region</th>
                                    <th class="text-left">Office</th>
                                    <th class="text-left">Supplier</th>
                                    <th class="text-left">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($userBulkData as $usr){
                                    if($usr['User']['userStatus'] == 1):
                                        $userStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Not Exits."></i>';
                                    else:
                                        $userStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Exits."></i>';
                                    endif;

                                    $statusColor  = 'style="background-color : #e2f5d9;"';
                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="user_id" style="display:none;"><?= $usr['User']['user_id']; ?></td>
                                        <td id="region_id" style="display:none;"><?= $usr['User']['region_id']; ?></td>
                                        <td id="office_id" style="display:none;"><?= $usr['User']['office_id']; ?></td>
                                        <td id="supplier_id" style="display:none;"><?= $usr['User']['supplier_id']; ?></td>
                                        <td id="usr_email" style="display:none;"><?= $usr['User']['email']; ?></td>
                                        <td><?= $userStatus.$usr['User']['email']; ?></td>
                                        <td id="usr_role"><?= $usr['User']['role']; ?></td>
                                        <td id="usr_name"><?= $usr['User']['name']; ?></td>
                                        <td id="usr_dept"><?= $usr['User']['department']; ?></td>
                                        <td id="usr_phone"><?= $usr['User']['phone']; ?></td>
                                        <td><?= $usr['User']['region']; ?></td>
                                        <td><?= $usr['User']['office']; ?></td>
                                        <td><?= $usr['User']['supplier']; ?></td>
                                        <td id="usr_status"><?= $usr['User']['status']; ?></td>
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
                "user_id"     : $(tr).find('td:eq(1)').text(),
                "region_id"   : $(tr).find('td:eq(2)').text(),
                "office_id"   : $(tr).find('td:eq(3)').text(),
                "supplier_id" : $(tr).find('td:eq(4)').text(),
                "usr_email"   : $(tr).find('td:eq(5)').text(),
                "usr_role"    : $(tr).find('td:eq(7)').text(),
                "usr_name"    : $(tr).find('td:eq(8)').text(),
                "usr_dept"    : $(tr).find('td:eq(9)').text(),
                "usr_phone"   : $(tr).find('td:eq(10)').text(),
                "usr_status"   : $(tr).find('td:eq(14)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'users', 'action' => 'bulk_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'users', 'action' => 'index')); ?>';
            }
        });
    });
</script>