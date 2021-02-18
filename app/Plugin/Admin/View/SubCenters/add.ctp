<div class="page-content-wrapper">
    <div class="page-content">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-globe"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index' ) ); ?>">Sub
                        Center</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-globe"></i>
                    <span><?php echo !empty( $data['SubCenter']['id'] ) ? 'Edit' : 'Add'; ?> Office</span>
                </li>
            </ul>
        </div>

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i><?php echo !empty( $data['SubCenter']['id'] ) ? 'Edit' : 'Add'; ?> Sub
                    Center Form
                </div>
            </div>
            <div class="portlet-body form">
                <?php
                echo $this->Form->create( 'SubCenter', array(
                    'id'            => 'subc-form',
                    'class'         => 'form-horizontal',
                    'autocomplete'  => 'off',
                    'role'          => 'form',
                    'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
                ) );
                echo $this->Form->hidden( 'id', array( 'value' => isset( $data['SubCenter']['id'] ) ? $data['SubCenter']['id'] : '' ) );
                ?>
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Region</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'region_id', array(
                                    'options' => $regionList,
                                    'empty'   => 'Select a region',
                                    'class'   => 'form-control required',
                                    'id'      => 'region-id',
                                    'value'   => !empty( $data['SubCenter']['region_id'] ) ? $data['SubCenter']['region_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Office Name</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'sub_center_name', array(
                                    'type'        => 'text',
                                    'class'       => 'form-control required',
                                    'id'          => 'sub-center-code',
                                    'placeholder' => 'Office Name',
                                    'value'       => !empty( $data['SubCenter']['sub_center_name'] ) ? $data['SubCenter']['sub_center_name'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Exceed 80% Budget</label>
                            <div class="radio-list col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php if( !empty( $data['SubCenter']['id'] ) ) { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][eighty_percent_action]"
                                               id="optionsRadios1"
                                               value="1" <?php echo ( $data['SubCenter']['eighty_percent_action'] == YES ) ? 'checked' : ''; ?>>
                                        Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][eighty_percent_action]"
                                               id="optionsRadios2"
                                               value="0" <?php echo ( $data['SubCenter']['eighty_percent_action'] == NO ) ? 'checked' : ''; ?>>
                                        Warning
                                    </label>
                                <?php }
                                else { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][eighty_percent_action]"
                                               id="optionsRadios1" value="1"> Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][eighty_percent_action]"
                                               id="optionsRadios2" value="0" checked> Warning
                                    </label>
                                <?php } ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Exceed 90% Budget</label>
                            <div class="radio-list col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php if( !empty( $data['SubCenter']['id'] ) ) { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][ninety_percent_action]"
                                               id="optionsRadios3"
                                               value="1" <?php echo ( $data['SubCenter']['ninety_percent_action'] == YES ) ? 'checked' : ''; ?>>
                                        Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][ninety_percent_action]"
                                               id="optionsRadios4"
                                               value="0" <?php echo ( $data['SubCenter']['ninety_percent_action'] == NO ) ? 'checked' : ''; ?>>
                                        Warning
                                    </label>
                                <?php }
                                else { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][ninety_percent_action]"
                                               id="optionsRadios3" value="1"> Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][ninety_percent_action]"
                                               id="optionsRadios4" value="0" checked> Warning
                                    </label>
                                <?php } ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Exceed 100% Budget</label>
                            <div class="radio-list col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php if( !empty( $data['SubCenter']['id'] ) ) { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][hundred_percent_action]"
                                               id="optionsRadios5"
                                               value="1" <?php echo ( $data['SubCenter']['hundred_percent_action'] == YES ) ? 'checked' : ''; ?>>
                                        Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][hundred_percent_action]"
                                               id="optionsRadios6"
                                               value="0" <?php echo ( $data['SubCenter']['hundred_percent_action'] == NO ) ? 'checked' : ''; ?>>
                                        Warning
                                    </label>
                                <?php }
                                else { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][hundred_percent_action]"
                                               id="optionsRadios5" value="1" checked> Block TR
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="data[SubCenter][hundred_percent_action]"
                                               id="optionsRadios6" value="0"> Warning
                                    </label>
                                <?php } ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Status</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'status', array(
                                    'options' => array( INACTIVE => 'Inactive', ACTIVE => 'Active' ),
                                    'class'   => 'form-control required',
                                    'id'      => 'status',
                                    'value'   => isset( $data['SubCenter']['status'] ) ? $data['SubCenter']['status'] : '',
                                ) );
                                ?>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">AC Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="ac-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'AC_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'ac-budget',
                                            'placeholder' => 'AC Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['AC_budget'] ) ? $data['SubCenter']['AC_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">AC Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="ac-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'AC_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'ac-min-budget',
                                            'placeholder' => 'AC Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['AC_min_budget'] ) ? $data['SubCenter']['AC_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">CW Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="cw-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'CW_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'cw-budget',
                                            'placeholder' => 'CW Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['CW_budget'] ) ? $data['SubCenter']['CW_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">CW Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="cw-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'CW_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'cw-min-budget',
                                            'placeholder' => 'CW Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['CW_min_budget'] ) ? $data['SubCenter']['CW_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">DV Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="dv-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'DV_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'dv-budget',
                                            'placeholder' => 'DV Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['DV_budget'] ) ? $data['SubCenter']['DV_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">DV Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="dv-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'DV_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'dv-min-budget',
                                            'placeholder' => 'DV Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['DV_min_budget'] ) ? $data['SubCenter']['DV_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">EB Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="eb-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'EB_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'eb-budget',
                                            'placeholder' => 'EB Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['EB_budget'] ) ? $data['SubCenter']['EB_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">EB Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="eb-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'EB_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'eb-min-budget',
                                            'placeholder' => 'EB Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['EB_min_budget'] ) ? $data['SubCenter']['EB_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">FM Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="fm-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'FM_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'fm-budget',
                                            'placeholder' => 'FM Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['FM_budget'] ) ? $data['SubCenter']['FM_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">FM Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="fm-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'FM_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'fm-min-budget',
                                            'placeholder' => 'FM Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['FM_min_budget'] ) ? $data['SubCenter']['FM_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">GN Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="gn-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'GN_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'gn-budget',
                                            'placeholder' => 'GN Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['GN_budget'] ) ? $data['SubCenter']['GN_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">GN Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="gn-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'GN_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'gn-min-budget',
                                            'placeholder' => 'GN Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['GN_min_budget'] ) ? $data['SubCenter']['GN_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">PG Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="pg-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'PG_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'pg-budget',
                                            'placeholder' => 'PG Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['PG_budget'] ) ? $data['SubCenter']['PG_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">PG Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="pg-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'PG_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'pg-min-budget',
                                            'placeholder' => 'PG Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['PG_min_budget'] ) ? $data['SubCenter']['PG_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">RF Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="rf-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'RF_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'rf-budget',
                                            'placeholder' => 'RF Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['RF_budget'] ) ? $data['SubCenter']['RF_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">RF Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="rf-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'RF_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'rf-min-budget',
                                            'placeholder' => 'RF Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['RF_min_budget'] ) ? $data['SubCenter']['RF_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">SS Budget</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="ss-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'SS_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'ss-budget',
                                            'placeholder' => 'SS Budget',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['SS_budget'] ) ? $data['SubCenter']['SS_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-2 col-sm-2 col-xs-12 control-label">SS Max Cost</label>
                            <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                                <div class="ss-min-budget-spinner">
                                    <div class="input-group">
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-down red">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <?php
                                        echo $this->Form->input( 'SS_min_budget', array(
                                            'type'        => 'text',
                                            'class'       => 'spinner-input form-control required',
                                            'id'          => 'ss-min-budget',
                                            'placeholder' => 'SS Min Cost',
                                            'maxlength'   => '20',
                                            'value'       => !empty( $data['SubCenter']['SS_min_budget'] ) ? $data['SubCenter']['SS_min_budget'] : '',
                                        ) );
                                        ?>
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn spinner-up blue">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                            <a href="<?php echo $this->Html->url( array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'index' ) ); ?>"
                               class="btn red"><i class="fa fa-arrow-left"></i> Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        gp_warranty.select_options( 'region-id' );
        $( '.ac-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.cw-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.dv-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.eb-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.fm-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.gn-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.pg-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.rf-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );
        $( '.ss-budget-spinner' ).spinner( {value: 0.0, step: 10000.0, min: 0.0, max: 1000000000.00} );

        $( '.ac-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.cw-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.dv-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.eb-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.fm-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.gn-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.pg-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.rf-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );
        $( '.ss-min-budget-spinner' ).spinner( {value: 0.0, step: 1000.0, min: 0.0, max: 1000000000.00} );

        $( '#subc-form' ).validate_popover( {popoverPosition: 'top'} );
    } );
</script>