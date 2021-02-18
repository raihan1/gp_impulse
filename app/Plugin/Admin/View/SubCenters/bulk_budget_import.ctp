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
                    <i class="fa fa-globe"></i>
                    <?php echo $this->Html->link( 'Office', array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index') ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-upload"></i>
                    <span>Import Bulk Office Budget</span>
                </li>
            </ul>
        </div>

        <?php echo $this->Session->flash(); ?>

        <div class="row">
            <div class="col-md-8">
                <?php
                echo $this->Form->create( 'SubCenter', array(
                    'id'            => 'sub-center-form',
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
                    'controller' => 'sub_centers',
                    'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' )); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 form-actions text-left">
                Import File Template : <?php echo $this->Html->link( '<i class="fa fa-download"></i> Download', BASEURL . 'files/templates/BUDGET_BULK_TEMPLATE.xls', array( 'escape' => FALSE) ); ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form id="form" data-toggle="validator" role="form">
                    <?php
                    if(!empty($budgetBulkData)){
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered rowCont budgetTable" id="sourceTable">
                                <thead>
                                <tr>
                                    <th class="text-center" width="5%" rowspan="2">S/N</th>
                                    <th class="text-left" rowspan="2">Region Name</th>
                                    <th class="text-left" rowspan="2">Office Name</th>
                                    <th class="text-left" rowspan="2">Exceed 80% Budget</th>
                                    <th class="text-left" rowspan="2">Exceed 90% Budget</th>
                                    <th class="text-left" rowspan="2">Exceed 100% Budget</th>
                                    <th class="text-center colSpanTh" colspan="2">AC</th>
                                    <th class="text-center colSpanTh" colspan="2">CW</th>
                                    <th class="text-center colSpanTh" colspan="2">DV</th>
                                    <th class="text-center colSpanTh" colspan="2">EB</th>
                                    <th class="text-center colSpanTh" colspan="2">FM</th>
                                    <th class="text-center colSpanTh" colspan="2">GN</th>
                                    <th class="text-center colSpanTh" colspan="2">PG</th>
                                    <th class="text-center colSpanTh" colspan="2">RF</th>
                                    <th class="text-center colSpanTh" colspan="2">SS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">TR Base Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sn = 1;
                                foreach($budgetBulkData as $bdgt){
                                    if($bdgt['SubCenter']['regionStatus'] == 1):
                                        $regionStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $regionStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($bdgt['SubCenter']['officeStatus'] == 1):
                                        $officeStatus = '<i class="fa fa-check-square excel-green-preview" title="Value Exits."></i>';
                                    else:
                                        $officeStatus = '<i class="fa fa-info-circle excel-red-preview" title="Value Not Exits."></i>';
                                    endif;

                                    if($bdgt['SubCenter']['regionStatus'] == 1):
                                        $statusColor  = 'style="background-color : #e2f5d9;"';
                                    else:
                                        $statusColor = '';
                                    endif;

                                    ?>
                                    <tr <?= $statusColor; ?>>
                                        <td class="text-center"><?= $sn; ?></td>
                                        <td id="region_status" style="display:none;"><?= $bdgt['SubCenter']['regionStatus']; ?></td>
                                        <td id="region_id" style="display:none;"><?= $bdgt['SubCenter']['region_id']; ?></td>
                                        <td id="office_status" style="display:none;"><?= $bdgt['SubCenter']['officeStatus']; ?></td>
                                        <td id="office_id" style="display:none;"><?= $bdgt['SubCenter']['office_id']; ?></td>
                                        <td id="office_name" style="display:none;"><?= $bdgt['SubCenter']['office_name']; ?></td>
                                        <td><?= $regionStatus.$bdgt['SubCenter']['region_name']; ?></td>
                                        <td><?= $officeStatus.$bdgt['SubCenter']['office_name']; ?></td>
                                        <td id="eighty_percent"><?= $bdgt['SubCenter']['eighty_percent_action']; ?></td>
                                        <td id="ninety_percent"><?= $bdgt['SubCenter']['ninety_percent_action']; ?></td>
                                        <td id="hundred_percent"><?= $bdgt['SubCenter']['hundred_percent_action']; ?></td>
                                        <td id="AC_budget"><?= $bdgt['SubCenter']['AC_budget']; ?></td>
                                        <td id="AC_min_budget"><?= $bdgt['SubCenter']['AC_min_budget']; ?></td>
                                        <td id="CW_budget"><?= $bdgt['SubCenter']['CW_budget']; ?></td>
                                        <td id="CW_min_budget"><?= $bdgt['SubCenter']['CW_min_budget']; ?></td>
                                        <td id="DV_budget"><?= $bdgt['SubCenter']['DV_budget']; ?></td>
                                        <td id="DV_min_budget"><?= $bdgt['SubCenter']['DV_min_budget']; ?></td>
                                        <td id="EB_budget"><?= $bdgt['SubCenter']['EB_budget']; ?></td>
                                        <td id="EB_min_budget"><?= $bdgt['SubCenter']['EB_min_budget']; ?></td>
                                        <td id="FM_budget"><?= $bdgt['SubCenter']['FM_budget']; ?></td>
                                        <td id="FM_min_budget"><?= $bdgt['SubCenter']['FM_min_budget']; ?></td>
                                        <td id="GN_budget"><?= $bdgt['SubCenter']['GN_budget']; ?></td>
                                        <td id="GN_min_budget"><?= $bdgt['SubCenter']['GN_min_budget']; ?></td>
                                        <td id="PG_budget"><?= $bdgt['SubCenter']['PG_budget']; ?></td>
                                        <td id="PG_min_budget"><?= $bdgt['SubCenter']['PG_min_budget']; ?></td>
                                        <td id="RF_budget"><?= $bdgt['SubCenter']['RF_budget']; ?></td>
                                        <td id="RF_min_budget"><?= $bdgt['SubCenter']['RF_min_budget']; ?></td>
                                        <td id="SS_budget"><?= $bdgt['SubCenter']['SS_budget']; ?></td>
                                        <td id="SS_min_budget"><?= $bdgt['SubCenter']['SS_min_budget']; ?></td>
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
                "region_status"   : $(tr).find('td:eq(1)').text(),
                "region_id"       : $(tr).find('td:eq(2)').text(),
                "office_status"   : $(tr).find('td:eq(3)').text(),
                "office_id"       : $(tr).find('td:eq(4)').text(),
                "office_name"     : $(tr).find('td:eq(5)').text(),
                "eighty_percent"  : $(tr).find('td:eq(8)').text(),
                "ninety_percent"  : $(tr).find('td:eq(9)').text(),
                "hundred_percent" : $(tr).find('td:eq(10)').text(),
                "AC_budget"       : $(tr).find('td:eq(11)').text(),
                "AC_min_budget"   : $(tr).find('td:eq(12)').text(),
                "CW_budget"       : $(tr).find('td:eq(13)').text(),
                "CW_min_budget"   : $(tr).find('td:eq(14)').text(),
                "DV_budget"       : $(tr).find('td:eq(15)').text(),
                "DV_min_budget"   : $(tr).find('td:eq(16)').text(),
                "EB_budget"       : $(tr).find('td:eq(17)').text(),
                "EB_min_budget"   : $(tr).find('td:eq(18)').text(),
                "FM_budget"       : $(tr).find('td:eq(19)').text(),
                "FM_min_budget"   : $(tr).find('td:eq(20)').text(),
                "GN_budget"       : $(tr).find('td:eq(21)').text(),
                "GN_min_budget"   : $(tr).find('td:eq(22)').text(),
                "PG_budget"       : $(tr).find('td:eq(23)').text(),
                "PG_min_budget"   : $(tr).find('td:eq(24)').text(),
                "RF_budget"       : $(tr).find('td:eq(25)').text(),
                "RF_min_budget"   : $(tr).find('td:eq(26)').text(),
                "SS_budget"       : $(tr).find('td:eq(27)').text(),
                "SS_min_budget"   : $(tr).find('td:eq(28)').text()
            }
        });
        TableData.shift();
        TableData.shift();
        TableData = $.toJSON(TableData);
        $.ajax({
            type: "POST",
            url: '<?= Router::url(array('controller' => 'sub_centers', 'action' => 'bulk_budget_import_post')); ?>',
            data: "pTableData=" + TableData,
            success: function(data){
                window.location = '<?= Router::url(array('controller' => 'sub_centers', 'action' => 'index')); ?>';
            }
        });
    });
</script>