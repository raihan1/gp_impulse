<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'admin', 'controller' =>
                    'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'admin', 'controller' =>
                    'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Edit Ticket', array( 'plugin' => 'admin', 'controller' =>
                                        'tickets', 'action' => 'add', $tkt_id ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span>Edit Service</span>
                </li>
            </ul>
        </div>

        <?php


        echo $this->Session->flash();
        echo $this->Form->create( 'TrService', array(
            'id' => 'formService',
            'class' => 'form-horizontal',
            'autocomplete' => 'off',
            'role' => 'form',
            'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
        ) );
        echo $this->Form->hidden( 'form_open_time', array( 'value' => microtime( TRUE ) ) );
        echo $this->Form->hidden( 'id', array( 'value' => isset( $tr_data['TrService']['id'] ) ? $tr_data['TrService']['id'] : '' )
        );
        ?>
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i> Edit Service of "<?php echo $tr_data['TrService']['service_desc'] ?>"
                </div>
            </div>

            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-5 col-sm-5 col-xs-12">

                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Base Unit Price</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'unit_price', array(
                                'class' => 'form-control',
                                'id' => 'unit_price',
                                'min' => '0',
                                'readonly' => true,
                                'value' => !empty( $tr_data['TrService']['unit_price'] ) ? $tr_data['TrService']['unit_price'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Vat(%)</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="assetGroupContainer">
                                <?php
                                echo $this->Form->input( 'vat', array(
                                'class' => 'form-control',
                                'id' => 'vat',
                                'min' => '0',
                                'readonly' => true,
                                'value' => !empty( $tr_data['TrService']['vat'] ) ? $tr_data['TrService']['vat'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Quantity</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'quantity', array(
                                'class' => 'form-control',
                                'id' => 'quantity',
                                'min' => '0',
                                'value' => !empty( $tr_data['TrService']['quantity'] ) ? $tr_data['TrService']['quantity'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Price with vat</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'unit_price_with_vat', array(
                                'class' => 'form-control',
                                'id' => 'unit_price_with_vat',
                                'readonly' => true,
                                'min' => '0',
                                'value' => !empty( $tr_data['TrService']['unit_price_with_vat'] ) ? $tr_data['TrService']['unit_price_with_vat'] : '',
                                ) );
                                ?>
                            </div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Total</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'total', array(
                                'class' => 'form-control',
                                'id' => 'total',
                                'readonly' => true,
                                'min' => '0',
                                'value' => !empty( $tr_data['TrService']['total'] ) ? $tr_data['TrService']['total'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Vat total</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'vat_total', array(
                                'class' => 'form-control',
                                'id' => 'total_vat',
                                'readonly' => true,
                                'min' => '0',
                                'value' => !empty( $tr_data['TrService']['vat_total'] ) ? $tr_data['TrService']['vat_total'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Total with vat</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'total_with_vat', array(
                                'class' => 'form-control',
                                'id' => 'total_with_vat',
                                'readonly' => true,
                                'min' => '0',
                                'value' => !empty( $tr_data['TrService']['total_with_vat'] ) ? $tr_data['TrService']['total_with_vat'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Delivery Date</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group date form_datetime">
                                    <?php
                                    echo $this->Form->input( 'delivery_date', array(
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'id' => 'delivery_date',
                                    'value' => !empty( $tr_data['TrService']['delivery_date'] ) ? $tr_data['TrService']['delivery_date'] : ''
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button"><i
                                                class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Comments</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group">
                                    <?php
                                    echo $this->Form->input( 'comments', array(
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'id' => 'comments',
                                    'rows' => 3,
                                    'value' => !empty( $tr_data['TrService']['comments'] ) ? $tr_data['TrService']['comments'] : ''
                                    ) );
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-actions text-center">
                        <button type="submit" class="btn green" id="addTicket"> Update</button>
                    </div>
                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>
 $( document ).ready( function() {
        $( '.form_datetime' ).datetimepicker( {
            format        : 'yyyy-mm-dd hh:mm:ss',
            rtl           : Metronic.isRTL(),
            pickerPosition: ( Metronic.isRTL() ? 'bottom-right' : 'bottom-left' ),
            autoclose     : true
        } );
         $(document).on('change' , '#quantity' , function(){
            var vat = $('#vat').val();
            var unit_price = $('#unit_price').val();
            var quantity = $('#quantity').val();
            var vat_total = vat * 0.01 * unit_price * quantity;
            var total = unit_price*quantity;

            var total_price = parseFloat( vat_total) + parseFloat(unit_price*quantity);

            $('#total').val(total.toFixed(4));
            $('#total_vat').val(vat_total.toFixed(4) );
            $('#total_with_vat').val(total_price.toFixed(4));

         });
    } );
</script>