<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link('Dashboard', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard')); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-cloud"></i>
                    <?php echo $this->Html->link('TR Class', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'index')); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk TR Class</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'TrClass', array(
                    'id'            => 'tr-class-form',
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
                    'controller' => 'tr_classes',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/TR_CLASS_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($trClassBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%">S/N</th>
<!--                                    <th class="text-left">Asset Group</th>-->
                                    <th class="text-left">TR Class</th>
                                    <th class="text-left">Number of Days</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($trClassBulkData as $trCls){
//                                    if($trCls['TrClass']['aGroupStatus'] == 1):
//                                        $aGroupStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
//                                    else:
//                                        $aGroupStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
//                                    endif;

                                    if($trCls['TrClass']['trClassStatus'] == 1):
                                        $trClassStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Not Exits."></i>';
                                        $statusColor = 'style="background-color : #e2f5d9;"';
                                    else:
                                        $trClassStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Exits."></i>';
                                        $statusColor = '';
                                    endif;

//                                    if($trCls['TrClass']['aGroupStatus'] == 1):
//                                        $statusColor  = 'style="background-color : #e2f5d9;"';
//                                    else:
//                                        $statusColor = '';
//                                    endif;

                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
<!--                                        <td id="aGroup_status" style="display:none;">--><?//= $trCls['TrClass']['aGroupStatus']; ?><!--</td>-->
<!--                                        <td id="aGroup_id" style="display:none;">--><?//= $trCls['TrClass']['aGroup_id']; ?><!--</td>-->
<!--                                        <td id="aGroup_name" style="display:none;">--><?//= $trCls['TrClass']['aGroup_name']; ?><!--</td>-->
                                        <td id="trClass_id" style="display:none;"><?= $trCls['TrClass']['trClass_id']; ?></td>
                                        <td id="trClass_name" style="display:none;"><?= $trCls['TrClass']['trClass_name']; ?></td>
<!--                                        <td style="display: none">--><?//= $aGroupStatus.$trCls['TrClass']['aGroup_name']; ?><!--</td>-->
                                        <td><?= $trClassStatus.$trCls['TrClass']['trClass_name']; ?></td>
                                        <td id="trClass_days"><?= $trCls['TrClass']['trClassDays']; ?></td>
                                    </tr>
                                    <?php
                                    $sn++;
                                }
                                ?>
                                </tbody>
                            </table>
                            <div class="form-actions text-center">
                                <div id="bulk-loading-image" style="display: none;"> Loading ...</div>
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
//                "aGroup_status" : $(tr).find('td:eq(1)').text(),
//                "aGroup_id"     : $(tr).find('td:eq(2)').text(),
//                "aGroup_name"   : $(tr).find('td:eq(3)').text(),
                "trClass_id"    : $(tr).find('td:eq(1)').text(),
                "trClass_name"  : $(tr).find('td:eq(2)').text(),
                "trClass_days"  : $(tr).find('td:eq(4)').text()
            }
        });
        TableData.shift();
        TableData = $.toJSON(TableData);

        $('#bulk-loading-image').show();
        $.ajax({
            type: "POST",
            async: false,
            url: '<?= Router::url(array('controller' => 'tr_classes', 'action' => 'bulk_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'tr_classes', 'action' => 'bulk_import')); ?>';
            },
            complete: function(){
                $('#bulk-loading-image').hide();
            }
        });
    });
</script>