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
                    <i class="fa fa-plane"></i>
                    <?php echo $this->Html->link( 'Project', array( 'plugin' => 'admin', 'controller' => 'projects', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Project</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'Project', array(
                    'id'            => 'project-form',
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
                    'controller' => 'projects',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/PROJECT_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($projectBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
                                    <th class="text-left">Site Name</th>
                                    <th class="text-left">Project Name</th>
                                    <th class="text-left">Project IP</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($projectBulkData as $pgt){
                                    if($pgt['Project']['siteStatus'] == 1):
                                        $siteStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $siteStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($pgt['Project']['projectStatus'] == 1):
                                        $projectStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Not Exits."></i>';
                                    else:
                                        $projectStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Exits."></i>';
                                    endif;

                                    if($pgt['Project']['siteStatus'] == 1):
                                        $statusColor  = 'style="background-color : #e2f5d9;"';
                                    else:
                                        $statusColor = '';
                                    endif;

                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="site_status" style="display:none;"><?= $pgt['Project']['siteStatus']; ?></td>
                                        <td id="site_id" style="display:none;"><?= $pgt['Project']['site_id']; ?></td>
                                        <td id="project_status" style="display:none;"><?= $pgt['Project']['projectStatus']; ?></td>
                                        <td id="project_name" style="display:none;"><?= $pgt['Project']['project_name']; ?></td>
                                        <td id="project_id" style="display:none;"><?= $pgt['Project']['project_id']; ?></td>
                                        <td><?= $siteStatus.$pgt['Project']['site_name']; ?></td>
                                        <td><?= $projectStatus.$pgt['Project']['project_name']; ?></td>
                                        <td id="project_ip"><?= $pgt['Project']['project_ip']; ?></td>
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
                "site_status"    : $(tr).find('td:eq(1)').text(),
                "site_id"        : $(tr).find('td:eq(2)').text(),
                "project_status" : $(tr).find('td:eq(3)').text(),
                "project_name"   : $(tr).find('td:eq(4)').text(),
                "project_id"     : $(tr).find('td:eq(5)').text(),
                "project_ip"     : $(tr).find('td:eq(8)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'projects', 'action' => 'bulk_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'projects', 'action' => 'index')); ?>';
            }
        });
    });
</script>