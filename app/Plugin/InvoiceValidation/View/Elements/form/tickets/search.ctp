<?php
echo $this->Form->create( 'Ticket', array(
    'id'            => 'formTicket',
    'type'          => 'get',
    'role'          => 'form',
    'autocomplete'  => 'off',
    'inputDefaults' => array( 'required' => FALSE, 'label' => FALSE, 'div' => FALSE, 'legend' => FALSE ),
) );
?>
<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-search"></i> Search
        </div>
    </div>
    <div class="portlet-body form">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Region</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'region', array(
                                'options' => $regionList,
                                'empty'   => 'Select a region',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['region'] ) ? $search['region'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Office</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="SubCenterContainer">
                            <?php
                            echo $this->Form->input( 'sub_center', array(
                                'options' => array(),
                                'empty'   => 'Select a Office',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['sub_center'] ) ? $search['sub_center'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Site</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="SiteContainer">
                            <?php
                            echo $this->Form->input( 'site', array(
                                'options' => array(),
                                'empty'   => 'Select a site',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['site'] ) ? $search['site'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
<!--                    <div class="form-group">-->
<!--                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Asset Group</label>-->
<!--                        <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="AssetGroupContainer">-->
<!--                            --><?php
//                            echo $this->Form->input( 'asset_group', array(
//                                'options' => array(),
//                                'empty'   => 'Select an asset group',
//                                'class'   => 'form-control',
//                                'value'   => !empty( $search['asset_group'] ) ? $search['asset_group'] : '',
//                            ) );
//                            ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Asset Number</label>-->
<!--                        <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="AssetNumberContainer">-->
<!--                            --><?php
//                            echo $this->Form->input( 'asset_number', array(
//                                'options' => array(),
//                                'empty'   => 'Select an asset number',
//                                'class'   => 'form-control',
//                                'value'   => !empty( $search['asset_number'] ) ? $search['asset_number'] : '',
//                            ) );
//                            ?>
<!--                        </div>-->
<!--                    </div>-->
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">TR Class</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="TrClassContainer">
                            <?php
                            echo $this->Form->input( 'tr_class', array(
                                'options' => $trClass,
                                'empty'   => 'Select a TR Class',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['tr_class'] ) ? $search['tr_class'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Supplier</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'supplier', array(
                                'options' => $supplierList,
                                'empty'   => 'Select a supplier',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['supplier'] ) ? $search['supplier'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Received at Supplier Site</label>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'period_from', array(
                                'type'  => 'text',
                                'class' => 'form-control datepicker',
                                'value' => !empty( $search['period_from'] ) ? $search['period_from'] : '',
                            ) );
                            ?>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12 form-group text-center"><p class="control-label">to</p></div>
                        <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'period_to', array(
                                'type'  => 'text',
                                'class' => 'form-control datepicker',
                                'value' => !empty( $search['period_to'] ) ? $search['period_to'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Status</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'status', array(
                                'options' => array(
                                    1 => 'Assigned',
                                    2 => 'Locked',
                                    3 => 'Pending',
                                    4 => 'Accepted',
                                    5 => 'Rejected',
                                ),
                                'empty'   => 'Select a ticket status',
                                'class'   => 'form-control',
                                'value'   => !empty( $search['status'] ) ? $search['status'] : '',
                            ) );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions right">
            <button type="submit" class="btn blue-ebonyclay-stripe"><i class="fa fa-search"></i> Search</button>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>