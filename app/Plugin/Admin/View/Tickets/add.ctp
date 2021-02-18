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
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span><?php echo !empty( $data['Ticket']['id'] ) ? 'Edit' : 'Add'; ?> Ticket</span>
                </li>
            </ul>
        </div>

        <?php


        echo $this->Session->flash();
        echo $this->Form->create( 'Ticket', array(
            'id'            => 'formTicket',
            'class'         => 'form-horizontal',
            'autocomplete'  => 'off',
            'role'          => 'form',
            'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
        ) );
        echo $this->Form->hidden( 'form_open_time', array( 'value' => microtime( TRUE ) ) );
        echo $this->Form->hidden( 'id', array(
                        'value' => isset( $data['Ticket']['id'] ) ? $data['Ticket']['id'] : '',
                        'id' => 'tkt_id',
                         ) );
        ?>
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i><?php echo !empty( $data['Ticket']['id'] ) ? 'Edit' : 'Add'; ?> Ticket
                </div>
            </div>

            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php if( !empty( $data['Ticket']['id'] ) ) { ?>
                                <label class="col-md-4 col-sm-4 col-xs-12 control-label">User</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'user_name', array(
                                        'type'      => 'text',
                                        'class'     => 'form-control',
                                        'id'        => 'user-id',
                                        'readonly'  => TRUE,
                                        'minlength' => 4,
                                        'maxlength' => 100,
                                        'value'     => !empty( $data['User']['name'] ) ? $data['User']['name'] : '',
                                    ) );
                                    ?>
                                    <?php
                                    echo $this->Form->hidden( '', array(
                                                            'value' => !empty( $data['Ticket']['tr_class'] ) ? $data['Ticket']['tr_class'] : '',
                                                            'id' => 'tr_class_name',
                                                             ) );
                                    ?>
                                </div>
                            <?php } ?>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Site</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'site', array(
                                    'options'  => $siteList,
                                    'empty'    => 'Select a site',
                                    'id'       => 'site',
                                    'class'    => 'form-control',
                                    'readonly'  => TRUE,
                                    'value'    => !empty( $data['Ticket']['site'] ) ? $data['Ticket']['site'] : '',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">TR Class</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="trClassContainer">
                                <?php
                                echo $this->Form->input( 'tr_class', array(
                                    'options' => array(),
                                    'empty'   => 'Select a TR class',
                                    'class'   => 'form-control',
                                    'id'      => 'tr_class',
                                    'value'   => !empty( $data['Ticket']['tr_class'] ) ? $data['Ticket']['tr_class'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Asset Group</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="assetGroupContainer">
                                <?php
                                echo $this->Form->input( 'asset_group', array(
                                    'options' => array(),
                                    'empty'   => 'Select an asset group',
                                    'class'   => 'form-control',
                                    'id'      => 'asset_group',
                                    'value'   => !empty( $data['Ticket']['asset_group'] ) ? $data['Ticket']['asset_group'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Asset Number</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="assetNumberContainer">
                                <?php
                                echo $this->Form->input( 'asset_number', array(
                                    'options' => array(),
                                    'empty'   => 'Select an asset number',
                                    'class'   => 'form-control',
                                    'id'      => 'asset_number',
                                    'value'   => !empty( $data['Ticket']['asset_number'] ) ? $data['Ticket']['asset_number'] : '',
                                ) );

                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Supplier</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'supplier', array(
                                    'options'  => $supplierList,
                                    'empty'    => 'Select a supplier',
                                    'id'       => 'supplier',
                                    'class'    => 'form-control',
                                    'value'    => !empty( $data['Ticket']['supplier'] ) ? $data['Ticket']['supplier'] : '',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Category</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="supplierCategoryContainer">
                                <?php
                                echo $this->Form->input( 'supplier_category', array(
                                    'options' => array(),
                                    'empty'   => 'Select a supplier category',
                                    'id'      => 'supplier_category',
                                    'class'   => 'form-control',
                                    'value'   => !empty( $data['Ticket']['supplier_category'] ) ? $data['Ticket']['supplier_category'] : '',
                                ) );
                                ?>
                            </div>
                        </div>

                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Received at Supplier Site</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group date form_datetime">
                                    <?php
                                    echo $this->Form->input( 'received_at_supplier', array(
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'id'    => 'received_at_supplier',
                                        'value' => !empty( $data['Ticket']['received_at_supplier'] ) ? $data['Ticket']['received_at_supplier'] : '',
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Completion date</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group input-medium"
                                     data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                    <?php
                                    echo $this->Form->input( 'complete_date', array(
                                        'type'     => 'text',
                                        'class'    => 'form-control',
                                        'id'       => 'complete_date',
                                        'readonly' => TRUE,
                                        'value'    => !empty( $data['Ticket']['complete_date'] ) ? $data['Ticket']['complete_date'] : '',
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button" style="vertical-align: top"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Comment</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'comment', array(
                                    'type'  => 'textarea',
                                    'class' => 'form-control',
                                    'id'    => 'comment',
                                    'value' => !empty( $data['Ticket']['comment'] ) ? $data['Ticket']['comment'] : '',
                                ) );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-center">
                    <button type="button" class="btn green" id="addTicket"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="ticketList">
                        <thead>
                            <tr>
                                <th class="no-sort no-image">Site</th>
                                <th class="no-sort no-image">TrClass</th>
                                <th class="no-sort no-image">Asset Group</th>
                                <th class="no-sort no-image">Asset Number</th>
                                <th class="no-sort no-image">Supplier</th>
                                <th class="no-sort no-image">Category</th>
                                <th class="no-sort no-image">Receive at supplier</th>
                                <th class="no-sort no-image">Completion Date</th>
                                <th class="no-sort no-image"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if( !empty( $unsavedTR ) ) {
                                foreach( $unsavedTR as $i => $TR ) {
                                    ?>
                                    <tr>
                                        <td><?php echo $TR['site']; ?></td>
                                        <td><?php echo $TR['tr_class']; ?></td>
                                        <td><?php echo $TR['asset_group']; ?></td>
                                        <td><?php echo $TR['asset_number']; ?></td>
                                        <td><?php echo $TR['supplier']; ?></td>
                                        <td><?php echo $TR['category']; ?></td>
                                        <td><?php echo $TR['received_at_supplier']; ?></td>
                                        <td><?php echo $TR['complete_date']; ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn green editTicket" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn green removeTicket" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][site]" value="<?php echo $TR['site']; ?>" class="site-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][project]" value="<?php echo $TR['project']; ?>" class="project-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][asset_group]" value="<?php echo $TR['asset_group']; ?>" class="asset-group-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][asset_number]" value="<?php echo $TR['asset_number']; ?>" class="asset-number-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][supplier]" value="<?php echo $TR['supplier']; ?>" class="supplier-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][supplier_category]" value="<?php echo $TR['supplier_category']; ?>" class="supplier-category-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][tr_class]" value="<?php echo $TR['tr_class']; ?>" class="tr-class-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][received_at_supplier]" value="<?php echo $TR['received_at_supplier']; ?>" class="receive-date-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][complete_date]" value="<?php echo $TR['complete_date']; ?>" class="complete-date-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][comment]" value="<?php echo $TR['comment']; ?>" class="comment-data" />
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php if( !empty( $trs_data['TrService'] ) ) { ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <!--<th width="8%">Service Id</th>-->
                                    <th width="8%">TR Class</th>
                                    <th>Service</th>
                                    <th class="text-right" width="10%">Base Unit Price</th>
                                    <th class="text-right" width="8%">Vat</th>
                                    <th class="text-right" width="8%">Unit Price</th>
                                    <th class="text-right" width="8%">Quantity</th>
                                    <th class="text-right" width="10%">Total</th>
                                    <th>Delivery Date</th>
                                    <?php echo $type == 'rejected' ? '<th>Comments</th>' : ''; ?>
                                    <?php echo $type == 'pending' || $type == 'approved' ?  '<th> Action </th>' : ''; ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach( $trs_data['TrService'] as $trs ) { ?>
                                <tr>
                                    <!--<td><?php echo $id = $trs['id']; ?></td>-->
                                    <td><?php echo $data['Ticket']['tr_class']; ?></td>
                                    <td><?php echo $trs['TrService']['service_desc']; ?></td>
                                    <td class="text-right"><?php echo number_format( $trs['TrService']['unit_price'], 2 ); ?></td>
                                    <td class="text-right"><?php echo $trs['TrService']['vat']; ?>%</td>
                                    <td class="text-right"><?php echo number_format( $trs['TrService']['unit_price_with_vat'], 4 ); ?></td>
                                    <td class="text-right"><?php echo $trs['TrService']['quantity']; ?></td>
                                    <td class="text-right"><?php echo number_format( $trs['TrService']['total_with_vat'], 4 ); ?></td>
                                    <td><?php echo $this->Lookup->showDateTime( $trs['TrService']['delivery_date'] ); ?></td>
                                    <?php echo $type == 'rejected' ? "<td>{$trs['TrService']['comments']}</td>" : ''; ?>
                                    <td>
                                        <?php echo $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' =>
                                        'admin', 'controller' => 'tickets', 'action' => 'service',
                                        $trs['TrService']['id'] ), array(
                                        'escape' =>FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) ); ?>

                                        <?php echo $this->Html->link( '<i class="fa fa-trash-o"></i>', array( 'plugin' =>
                                        'admin', 'controller' => 'tickets', 'action' => 'deleteService',
                                        $trs['TrService']['id'] ), array(
                                        'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete' ) ); ?>

                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="6" class="text-right"><b>Total</b></td>
                                    <td class="text-right"><?php echo number_format( $data['Ticket']['total_with_vat'], 4 ); ?></td>
                                    <td>&nbsp;</td>
                                    <?php echo $type == 'rejected' ? '<td>&nbsp;</td>' : ''; ?>
                                    <td>&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center margin-bottom-20">
                <button type="button" class="btn green submit-form"><i class="fa fa-check"></i> Submit</button>
                <?php if( !empty( $trs_data['TrService'] ) ) { ?>
                    <?php echo $this->Html->link( 'Add Service', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'add_service' , $data['Ticket']['id']), array( 'escape' => FALSE, 'class' => 'btn green' ) ); ?>
                <?php } ?>
                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'index'), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    //var project           = '<?php echo !empty( $data['Ticket']['project'] ) ? $data['Ticket']['project'] : ''; ?>';
    var asset_group       = '<?php echo !empty( $data['Ticket']['asset_group'] ) ? $data['Ticket']['asset_group'] : ''; ?>';
    var asset_number      = '<?php echo !empty( $data['Ticket']['asset_number'] ) ? $data['Ticket']['asset_number'] : ''; ?>';
    var tr_class          = '<?php echo !empty( $data['Ticket']['tr_class'] ) ? $data['Ticket']['tr_class'] : ''; ?>';
    var supplier_category = '<?php echo !empty( $data['Ticket']['supplier_category'] ) ? $data['Ticket']['supplier_category'] : ''; ?>';

    var populate_complete_date = function() {
        $( '.fancybox-loading' ).show();
        $( '.mask' ).show();

        var tr_class_id          = $( '#tr_class' ).find( 'option:selected' ).attr( 'data-id' );
        var received_at_supplier = $( '#received_at_supplier' ).val();

        $.ajax( {
            dataType   : 'json',
            type       : 'POST',
            evalScripts: true,
            url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'calculate_complete_date' ) ); ?>',
            data       : 'tr_class_id=' + tr_class_id + '&received_at_supplier=' + received_at_supplier,
            success    : function( data ) {
                $( '#complete_date' ).val( data );

                $( '.fancybox-loading' ).hide();
                $( '.mask' ).hide();
            }
        } );
    };

    $( document ).ready( function() {
        gp_warranty.select_options( 'site' );
        gp_warranty.select_options( 'asset_group' );
        gp_warranty.select_options( 'asset_number' );
        gp_warranty.select_options( 'tr_class' );
        gp_warranty.select_options( 'supplier' );
        gp_warranty.select_options( 'supplier_category' );

        var i = <?php echo empty( $unsavedTR ) ? 0 : count( $unsavedTR ); ?>;

        $( '#addTicket' ).on( 'click', function() {
            /* Validation: start */
            if( $( "#site" ).val().length == 0 ) {
                return;
            }
            else if( $( "#tr_class" ).val().length == 0 ) {
                return;
            }
            else if( $( "#asset_group" ).val().length == 0 ) {
                return;
            }
            else if( $( "#asset_number" ).val().length == 0 ) {
                return;
            }
            else if( $( "#supplier" ).val().length == 0 ) {
                return;
            }
            else if( $( "#supplier_category" ).val().length == 0 ) {
                return;
            }
            else if( $( "#received_at_supplier" ).val().length == 0 ) {
                return;
            }
            /* Validation: end */

            var row = '<tr>'
                        + '<td>' + $( '#site' ).val() + '</td>'
                        + '<td>' + $( '#tr_class' ).val() + '</td>'
                        + '<td>' + $( '#asset_group' ).val() + '</td>'
                        + '<td>' + $( '#asset_number' ).val() + '</td>'
                        + '<td>' + $( '#supplier' ).val() + '</td>'
                        + '<td>' + $( '#supplier_category' ).val() + '</td>'
                        + '<td>' + $( '#received_at_supplier' ).val() + '</td>'
                        + '<td>' + $( '#complete_date' ).val() + '</td>'
                        + '<td class="text-center">'
                            + '<button type="button" class="btn green editTicket" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>'
                            + '<button type="button" class="btn green removeTicket" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][site]" value="' + $( '#site' ).val() + '" class="site-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][project]" value="' + $( '#project' ).val() + '" class="project-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][asset_group]" value="' + $( '#asset_group' ).val() + '" class="asset-group-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][asset_number]" value="' + $( '#asset_number' ).val() + '" class="asset-number-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier]" value="' + $( '#supplier' ).val() + '" class="supplier-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier_category]" value="' + $( '#supplier_category' ).val() + '" class="supplier-category-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][tr_class]" value="' + $( '#tr_class' ).val() + '" class="tr-class-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][received_at_supplier]" value="' + $( '#received_at_supplier' ).val() + '" class="receive-date-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][complete_date]" value="' + $( '#complete_date' ).val() + '" class="complete-date-data" />'
                            + '<input type="hidden" name="data[Ticket][batch][' + i + '][comment]" value="' + $( '#comment' ).val() + '" class="comment-data" />'
                        + '</td>'
                    + '</tr>';

            /* Append to the table */
            $( '#ticketList tbody' ).append( row );

            /* Reset form */
            $( '#site' ).val( '' ).trigger( 'change' );
            $( '#tr_class' ).val( '' ).trigger('change');
            $( '#asset_group' ).val( '' );
            $( '#asset_number' ).val( '' );
            $( '#supplier' ).val( '' ).trigger( 'change' );
            $( '#supplier_category' ).val( '' );
            $( '#received_at_supplier' ).val( '' );
            $( '#complete_date' ).val( '' );
            $( '#comment' ).val( '' );

            i++;
        } );

        $( document ).on( 'click', '.removeTicket', function() {
            $( this ).closest( 'tr' ).remove();
        } );

        $( document ).on( 'click', '.editTicket', function() {
            //project           = $( this ).closest( 'tr' ).find( '.project-data' ).val();
            asset_group       = $( this ).closest( 'tr' ).find( '.asset-group-data' ).val();
            asset_number      = $( this ).closest( 'tr' ).find( '.asset-number-data' ).val();
            tr_class          = $( this ).closest( 'tr' ).find( '.tr-class-data' ).val();
            supplier_category = $( this ).closest( 'tr' ).find( '.supplier-category-data' ).val();

            $( '#site' ).val( $( this ).closest( 'tr' ).find( '.site-data' ).val() ).trigger( 'change' );
            //$( '#project' ).val( project );
            $( '#tr_class' ).val( tr_class ).trigger( 'change' );
            $( '#asset_group' ).val( asset_group ).trigger( 'change' );
            $( '#asset_number' ).val( asset_number ).trigger( 'change' );
            $( '#supplier' ).val( $( this ).closest( 'tr' ).find( '.supplier-data' ).val() ).trigger( 'change' );
            $( '#supplier_category' ).val( supplier_category ).trigger( 'change' );
            $( '#received_at_supplier' ).val( $( this ).closest( 'tr' ).find( '.receive-date-data' ).val() );
//            $( '.form_datetime' ).datetimepicker();
            $( '#complete_date' ).val( $( this ).closest( 'tr' ).find( '.complete-date-data' ).val() );
            $( '#comment' ).val( $( this ).closest( 'tr' ).find( '.comment-data' ).val() );

            $( this ).closest( 'tr' ).remove();
        } );

        /* getting block date value from database*/
        var date = '<?php echo date( 'Y-m-d', strtotime( -$block_date['value'] . ' Days' ) ); ?>';

        $( '.form_datetime' ).datetimepicker( {
            format        : 'yyyy-mm-dd hh:ii',
            rtl           : Metronic.isRTL(),
            /*startDate     : date,*/
            pickerPosition: ( Metronic.isRTL() ? 'bottom-right' : 'bottom-left' ),
            autoclose     : true
        } ).on( 'change', function() {
            if( $( '#tr_class' ).val() != '' ) {
                populate_complete_date();
            }
        } );

        $( '#site' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var site_id = $( this ).find( 'option:selected' ).attr( 'data-id' );
            var tkt_id = $( '#tkt_id' ).val();
            var tr_class = $( '#tr_class_name' ).val();
            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'site_selected' ) ); ?>',
                data       :  'site_id=' + site_id + '&tkt_id=' + tkt_id + '&tr_class=' + tr_class,
                success    : function( data ) {
                    $( '#trClassContainer' ).html( '' );
                    var tr_class_options = '<select name="data[Ticket][tr_class]" class="form-control" id="tr_class"><option value="">Select a tr class</option>';
                    if( data[ 'TrClass' ].length > 0 ) {
                        for( var i = 0; i < data[ 'TrClass' ].length; i++ ) {
                            tr_class_options += '<option value="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '" data-id="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'id' ] + '"' + ( tr_class == data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] ? ' selected' : '' ) + '>' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '</option>';
                        }
                    }
                    tr_class_options += '</select>';
                    $( '#trClassContainer' ).html( tr_class_options );
                    gp_warranty.select_options( 'tr_class' );


                    $( '#assetGroupContainer' ).html( '' );
                    var asset_group_options = '<select name="data[Ticket][asset_group]" class="form-control" id="asset_group"><option value="">Select an asset group</option>';
                    if( data[ 'AssetGroup' ].length > 0 ) {
                        for( var i = 0; i < data[ 'AssetGroup' ].length; i++ ) {
                            asset_group_options += '<option value="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '" data-id="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'id' ] + '"' + ( asset_group == data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] ? ' selected' : '' ) + '>' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '</option>';
                        }
                    }
                    asset_group_options += '</select>';
                    $( '#assetGroupContainer' ).html( asset_group_options );
                    gp_warranty.select_options( 'asset_group_options' );


                    $( '#assetNumberContainer' ).html( '' );
                    var asset_number_options = '<select name="data[Ticket][asset_number]" class="form-control" id="asset_number"><option value="">Select an asset number</option>';
                    if( data[ 'AssetNumber' ].length > 0 ) {
                        for( var i = 0; i < data[ 'AssetNumber' ].length; i++ ) {
                            asset_number_options += '<option value="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '" data-id="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'id' ] + '"' + ( asset_number == data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] ? ' selected' : '' ) + '>' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '</option>';
                        }
                    }
                    asset_number_options += '</select>';
                    $( '#assetNumberContainer' ).html( asset_number_options );
                    gp_warranty.select_options( 'asset_number_options' );


                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );

/*        $( document ).on( 'change', '#asset_group', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var asset_group_id = $( this ).find( 'option:selected' ).attr( 'data-id' );

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'asset_group_selected' ) ); ?>',
                data       : 'asset_group_id=' + asset_group_id,
                success    : function( data ) {
                    $( '#assetNumberContainer' ).html( '' );
                    var asset_number_options = '<select name="data[Ticket][asset_number]" class="form-control" id="asset_number"><option value="">Select an asset number</option>';
                    if( data[ 'AssetNumber' ].length > 0 ) {
                        for( var i = 0; i < data[ 'AssetNumber' ].length; i++ ) {
                            asset_number_options += '<option value="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '" data-id="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'id' ] + '"' + ( asset_number == data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] ? ' selected' : '' ) + '>' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '</option>';
                        }
                    }
                    asset_number_options += '</select>';
                    $( '#assetNumberContainer' ).html( asset_number_options );
                    gp_warranty.select_options( 'asset_number' );

                    $( '#trClassContainer' ).html( '' );
                    var tr_class_options = '<select name="data[Ticket][tr_class]" class="form-control" id="tr_class"><option value="">Select a tr class</option>';
                    if( data[ 'TrClass' ].length > 0 ) {
                        for( var i = 0; i < data[ 'TrClass' ].length; i++ ) {
                            tr_class_options += '<option value="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '" data-id="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'id' ] + '"' + ( tr_class == data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] ? ' selected' : '' ) + '>' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '</option>';
                        }
                    }
                    tr_class_options += '</select>';
                    $( '#trClassContainer' ).html( tr_class_options );
                    gp_warranty.select_options( 'tr_class' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } );
*/
        $( '#supplier' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var supplier_id = $( this ).find( 'option:selected' ).attr( 'data-id' );

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'supplier_selected' ) ); ?>',
                data       : 'supplier_id=' + supplier_id,
                success    : function( data ) {
                    $( '#supplierCategoryContainer' ).html( '' );
                    var supplier_category_options = '<select name="data[Ticket][supplier_category]" class="form-control" id="supplier_category"><option value="">Select a supplier category</option>';
                    if( data[ 'SupplierCategory' ].length > 0 ) {
                        for( var i = 0; i < data[ 'SupplierCategory' ].length; i++ ) {
                            supplier_category_options += '<option value="' + data[ 'SupplierCategory' ][ i ][ 'SupplierCategory' ][ 'category_name' ] + '" data-id="' + data[ 'SupplierCategory' ][ i ][ 'SupplierCategory' ][ 'id' ] + '"' + ( supplier_category == data[ 'SupplierCategory' ][ i ][ 'SupplierCategory' ][ 'category_name' ] ? ' selected' : '' ) + '>' + data[ 'SupplierCategory' ][ i ][ 'SupplierCategory' ][ 'category_name' ] + '</option>';
                        }
                    }
                    supplier_category_options += '</select>';
                    $( '#supplierCategoryContainer' ).html( supplier_category_options );
                    gp_warranty.select_options( 'supplier_category' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );

        $( document ).on( 'change', '#tr_class', function() {
            if( $( '#received_at_supplier' ).val() != '' ) {
                populate_complete_date();
            }
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var tr_id = $( this ).find('option:selected' ).attr('data-id');

            $.ajax({
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'tr_class_selected' ) ); ?>',
                data       : 'tr_id=' + tr_id,
                success    : function(data){

                    $( '#assetGroupContainer' ).html( '' );
                    var asset_group_options = '<select name="data[Ticket][asset_group]" class="form-control" id="asset_group"><option value="">Select an asset group</option>';
                    if( data[ 'AssetGroup' ].length == 1 ) {
                        for( var i = 0; i < data[ 'AssetGroup' ].length; i++ ) {
                            asset_group_options = '<select name="data[Ticket][asset_group]" class="form-control" id="asset_group">';
                            asset_group_options += '<option value="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '" data-id="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'id' ] + '"' + ( asset_group == data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] ? ' selected' : '' ) + '>' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '</option>';
                        }
                    }
                    else if( data[ 'AssetGroup' ].length > 0 ) {
                        for( var i = 0; i < data[ 'AssetGroup' ].length; i++ ) {
                            asset_group_options += '<option value="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '" data-id="' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'id' ] + '"' + ( asset_group == data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] ? ' selected' : '' ) + '>' + data[ 'AssetGroup' ][ i ][ 'AssetGroup' ][ 'asset_group_name' ] + '</option>';
                        }
                    }
                    asset_group_options += '</select>';
                    $( '#assetGroupContainer' ).html( asset_group_options );
                    //$( '#assetGroupContainer' ).html( data[ 'AssetGroup' ].length );
                    gp_warranty.select_options( 'asset_group' );


                    $( '#assetNumberContainer' ).html( '' );
                    var asset_number_options = '<select name="data[Ticket][asset_number]" class="form-control" id="asset_number"><option value="">Select an asset number</option>';
                    if( data[ 'AssetNumber' ].length == 1 ) {
                        for( var i = 0; i < data[ 'AssetNumber' ].length; i++ ) {
                            asset_number_options = '<select name="data[Ticket][asset_number]" class="form-control" id="asset_number">';
                            asset_number_options += '<option value="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '" data-id="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'id' ] + '"' + ( asset_number == data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] ? ' selected' : '' ) + '>' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '</option>';
                        }
                    }
                    else if( data[ 'AssetNumber' ].length > 0 ) {
                        for( var i = 0; i < data[ 'AssetNumber' ].length; i++ ) {
                            asset_number_options += '<option value="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '" data-id="' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'id' ] + '"' + ( asset_number == data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] ? ' selected' : '' ) + '>' + data[ 'AssetNumber' ][ i ][ 'AssetNumber' ][ 'asset_number' ] + '</option>';
                        }
                    }
                    asset_number_options += '</select>';
                    $( '#assetNumberContainer' ).html( asset_number_options );
                    gp_warranty.select_options( 'asset_number' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();

                }
            });

        } ).trigger( 'change' );
        
        $( '#formTicket' ).validate_popover( { popoverPosition: 'top' } );
        
        $( '.submit-form' ).on( 'click', function() {
            if( $( '#ticketList > tbody > tr' ).length > 0 ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
                
                $( '#formTicket' ).submit();
            }
            else {
                alert( 'Please provide at least one ticket.' );
            }
        } );
    } );
</script>