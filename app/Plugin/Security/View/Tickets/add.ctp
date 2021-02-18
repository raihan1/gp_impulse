<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>">Dashboard</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <a href="<?php echo $this->Html->url( array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ) ); ?>">TR
                        List</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <span><?php echo !empty( $data['Ticket']['id'] ) ? 'Edit' : 'Add'; ?> TR</span>
                </li>
            </ul>
        </div>

        <?php
        echo $this->Session->flash();
        echo $this->Form->create( 'Ticket', array(
            'type'          => 'file',
            'id'            => 'ticket-form',
            'class'         => 'form-horizontal',
            'autocomplete'  => 'off',
            'role'          => 'form',
            'inputDefaults' => array( 'required' => FALSE, 'div' => FALSE, 'label' => FALSE, 'legend' => FALSE ),
        ) );
        echo $this->Form->hidden( 'form_open_time', array( 'value' => microtime( TRUE ) ) );
        echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Ticket']['id'] ) ? $data['Ticket']['id'] : '' ) );
        echo $this->Form->hidden( '', array( 'name' => 'data[Ticket][user_id]', 'value' => isset( $loginUser['User']['id'] ) ? $loginUser['User']['id'] : '' ) );
        echo $this->Form->hidden( '', array( 'name' => 'data[Ticket][department]', 'value' => !empty( $data['Ticket']['department'] ) ? $data['Ticket']['department'] : $loginUser['User']['department'] ) );
        echo $this->Form->hidden( '', array( 'name' => 'data[Ticket][created_by]', 'value' => isset( $loginUser['User']['id'] ) ? $loginUser['User']['id'] : '' ) );
        echo $this->Form->hidden( '', array( 'name' => 'data[Ticket][stage]', 'value' => SUPPLIER_STAGE ) );
        if( !empty( $data['Ticket']['id'] ) ) {
            echo $this->Form->hidden( '', array( 'name' => 'data[Ticket][supplier_id]', 'value' => $data['Ticket']['supplier_id'] ) );
        }
        ?>
        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-ticket"></i><?php echo !empty( $data['Ticket']['id'] ) ? 'Edit' : 'Add'; ?> TR Form
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php if( !empty( $data['Ticket']['id'] ) ) { ?>
                                <label class="col-md-3 col-sm-3 col-xs-12 control-label">User</label>
                                <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                                    <?php
                                    echo $this->Form->input( 'user_name', array(
                                        'type'      => 'text',
                                        'class'     => 'form-control',
                                        'id'        => 'user-id',
                                        'readonly'  => TRUE,
                                        'minlength' => 4,
                                        'maxlength' => 100,
                                        'value'     => !empty( $loginUser['User']['name'] ) ? $loginUser['User']['name'] : '',
                                    ) );
                                    ?>
                                </div>
                            <?php } ?>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Region</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'region_id', array(
                                    'options'  => $regionList,
                                    'empty'    => 'Select a region',
                                    'class'    => 'form-control',
                                    'id'       => 'region_id',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                    'value'    => !empty( $data['Ticket']['region_id'] ) ? $data['Ticket']['region_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Office</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="sub-center-container">
                                <?php
                                echo $this->Form->input( 'sub_center_id', array(
                                    'options'  => array(),
                                    'empty'    => 'Select a Office',
                                    'class'    => 'form-control',
                                    'id'       => 'sub_center_id',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                    'value'    => !empty( $data['Ticket']['sub_center_id'] ) ? $data['Ticket']['sub_center_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Site</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="site-container">
                                <?php
                                echo $this->Form->input( 'site_id', array(
                                    'options'  => array(),
                                    'empty'    => 'Select a site',
                                    'class'    => 'form-control',
                                    'id'       => 'site-id',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                    'value'    => !empty( $data['Ticket']['site_id'] ) ? $data['Ticket']['site_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Project</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="project-container">
                                <?php
                                echo $this->Form->input( 'project_id', array(
                                    'empty' => 'Select a project',
                                    'class' => 'form-control',
                                    'id'    => 'project-id',
                                    'value' => !empty( $data['Ticket']['project_id'] ) ? $data['Ticket']['project_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Asset Group</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="asset-group-container">
                                <?php
                                echo $this->Form->input( 'asset_group_id', array(
                                    'empty' => 'Select an asset group',
                                    'class' => 'form-control',
                                    'id'    => 'asset-group-id',
                                    'value' => !empty( $data['Ticket']['asset_group_id'] ) ? $data['Ticket']['asset_group_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Asset Number</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="asset-number-container">
                                <?php
                                echo $this->Form->input( 'asset_number_id', array(
                                    'empty' => 'Select an asset number',
                                    'class' => 'form-control',
                                    'id'    => 'asset-number-id',
                                    'value' => !empty( $data['Ticket']['asset_number_id'] ) ? $data['Ticket']['asset_number_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">TR Class</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 form-group" id="tr-class-container">
                                <?php
                                echo $this->Form->input( 'tr_class_id', array(
                                    'empty' => 'Select a TR class',
                                    'class' => 'form-control',
                                    'id'    => 'tr-class-id',
                                    'value' => !empty( $data['Ticket']['tr_class_id'] ) ? $data['Ticket']['tr_class_id'] : '',
                                ) );
                                ?>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label class="col-md-5 col-sm-5 col-xs-12 control-label">Supplier</label>
                            <div class="col-md-7 col-sm-7 col-xs-12 form-group">
                                <?php
                                $bool = !empty( $data['Ticket']['id'] ) ? TRUE : FALSE;
                                echo $this->Form->input( 'supplier_id', array(
                                    'options'  => $supplierList,
                                    'empty'    => 'Select a supplier',
                                    'class'    => 'form-control',
                                    'id'       => 'supplier-id',
                                    'disabled' => $bool,
                                    'value'    => !empty( $data['Ticket']['supplier_id'] ) ? $data['Ticket']['supplier_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-5 col-sm-5 col-xs-12 control-label">Category</label>
                            <div class="col-md-7 col-sm-7 col-xs-12 form-group" id="supplier-category-container">
                                <?php
                                echo $this->Form->input( 'supplier_category_id', array(
                                    'empty' => 'Select a supplier category',
                                    'class' => 'form-control',
                                    'id'    => 'supplier-category-id',
                                    'value' => !empty( $data['Ticket']['supplier_category_id'] ) ? $data['Ticket']['supplier_category_id'] : '',
                                ) );
                                ?>
                            </div>
                            <label class="col-md-5 col-sm-5 col-xs-12 control-label">Received at supplier site</label>
                            <div class="col-md-7 col-sm-7 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group date form_datetime">
                                    <?php
                                    echo $this->Form->input( 'received_at_supplier', array(
                                        'type'  => 'text',
                                        'class' => 'form-control',
                                        'id'    => 'received-date-id',
                                        'value' => !empty( $data['Ticket']['received_at_supplier'] ) ? $data['Ticket']['received_at_supplier'] : '',
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
										<button class="btn default date-set" type="button"><i
                                                class="fa fa-calendar"></i></button>
									</span>
                                </div>
                            </div>
                            <label class="col-md-5 col-sm-5 col-xs-12 control-label">Completion date</label>
                            <div class="col-md-7 col-sm-7 col-xs-12 form-group">
                                <div style="width: 100% !important;" class="input-group input-medium"
                                     data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
                                    <?php
                                    echo $this->Form->input( 'complete_date', array(
                                        'type'     => 'text',
                                        'class'    => 'form-control',
                                        'id'       => 'completetion-date-id',
                                        'readonly' => TRUE,
                                        'value'    => !empty( $data['Ticket']['complete_date'] ) ? $data['Ticket']['complete_date'] : '',
                                    ) );
                                    ?>
                                    <span class="input-group-btn">
                                    	<button class="btn default" type="button" style="vertical-align: top"><i
                                                class="fa fa-calendar"></i></button>
									</span>
                                </div>
                            </div>
                            <label class="col-md-5 col-sm-5 col-xs-12 control-label">Comment</label>
                            <div class="col-md-7 col-sm-7 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'comment', array(
                                    'type'  => 'textarea',
                                    'class' => 'form-control',
                                    'id'    => 'comment-id',
                                    'value' => !empty( $data['Ticket']['comment'] ) ? $data['Ticket']['comment'] : '',
                                ) );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="button" class="btn green" id="add-to-table"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin: 0px">
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="tr-table">
                        <thead>
                            <tr>
                                <th class="no-sort no-image">Site</th>
                                <th class="no-sort no-image">TR Class</th>
                                <th class="no-sort no-image">Asset Group</th>
                                <th class="no-sort no-image">Asset Number</th>
                                <th class="no-sort no-image">Supplier</th>
                                <th class="no-sort no-image">Category</th>
                                <th class="no-sort no-image">Receive at supplier</th>
                                <th class="no-sort no-image">Completion Date</th>
                                <th class="no-sort no-image"></th>
                            </tr>
                        </thead>
                        <tbody id="more-tr">
                            <?php
                            if( !empty( $unsavedTR ) ) {
                                foreach( $unsavedTR as $i => $TR ) {
                                    ?>
                                    <tr>
                                        <td><?php echo $TR['site_name']; ?></td>
                                        <td><?php echo $TR['tr_class_name']; ?></td>
                                        <td><?php echo $TR['asset_group_name']; ?></td>
                                        <td><?php echo $TR['asset_number']; ?></td>
                                        <td><?php echo $TR['supplier_name']; ?></td>
                                        <td><?php echo $TR['category_name']; ?></td>
                                        <td><?php echo $TR['received_at_supplier']; ?></td>
                                        <td><?php echo $TR['complete_date']; ?></td>
                                        <td style="text-align: center; vertical-align: middle">
                                            <button type="button" class="btn green" id="edit-row" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn green" id="remove-from-table" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>
                                        </td>
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][site_id]" value="<?php echo $TR['site_id']; ?>" class="site-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][project_id]" value="<?php echo $TR['project_id']; ?>" class="project-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][asset_group_id]" value="<?php echo $TR['asset_group_id']; ?>" class="asset-group-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][asset_number_id]" value="<?php echo $TR['asset_number_id']; ?>" class="asset-number-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][supplier_id]" value="<?php echo $TR['supplier_id']; ?>" class="supplier-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][supplier_category_id]" value="<?php echo $TR['supplier_category_id']; ?>" class="supplier-category-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][tr_class_id]" value="<?php echo $TR['tr_class_id']; ?>" class="tr-class-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][received_at_supplier]" value="<?php echo $TR['received_at_supplier']; ?>" class="receive-date-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][complete_date]" value="<?php echo $TR['complete_date']; ?>" class="complete-date-data">
                                        <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][comment]" value="<?php echo $TR['comment']; ?>" class="comment-data">
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
            <div class="col-md-12 text-center margin-bottom-20">
                <button type="button" class="btn green submit-form"><i class="fa fa-check"></i> Submit</button>
                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    var sub_center_id = <?php echo !empty( $data['Ticket']['sub_center_id'] ) ? $data['Ticket']['sub_center_id'] : 0; ?>;
    var site_id = <?php echo !empty( $data['Ticket']['site_id'] ) ? $data['Ticket']['site_id'] : 0; ?>;
    var tr_class_id = <?php echo !empty( $data['Ticket']['tr_class_id'] ) ? $data['Ticket']['tr_class_id'] : 0; ?>;
    var asset_group_id = <?php echo !empty( $data['Ticket']['asset_group_id'] ) ? $data['Ticket']['asset_group_id'] : 0; ?>;
    var asset_number_id = <?php echo !empty( $data['Ticket']['asset_number_id'] ) ? $data['Ticket']['asset_number_id'] : 0; ?>;
    var supplier_category_id = <?php echo !empty( $data['Ticket']['supplier_category_id'] ) ? $data['Ticket']['supplier_category_id'] : 0; ?>;

    $( document ).ready( function() {
        gp_warranty.select_options( 'region_id' );
        gp_warranty.select_options( 'sub_center_id' );
        gp_warranty.select_options( 'site-id' );
        gp_warranty.select_options( 'project-id' );
        gp_warranty.select_options( 'asset-group-id' );
        gp_warranty.select_options( 'asset-number-id' );
        gp_warranty.select_options( 'tr-class-id' );
        gp_warranty.select_options( 'supplier-id' );
        gp_warranty.select_options( 'supplier-category-id' );

        $( '#ticket-form' ).validate_popover( {popoverPosition: 'top'} );

        var i = <?php echo empty( $unsavedTR ) ? 0 : count( $unsavedTR ); ?>;

        $( '#add-to-table' ).on( 'click', function() {
            if( $( "#site-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#tr-class-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#asset-group-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#asset-number-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#supplier-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#supplier-category-id" ).val().length == 0 ) {
                return;
            }
            else if( $( "#received-date-id" ).val().length == 0 ) {
                return;
            }

            var supplier_val  = $().val();
            var supplier_text = $().text();
            var row           = '<tr>'
                + '<td>' + $( '#site-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#tr-class-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#asset-group-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#asset-number-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#supplier-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#supplier-category-id option:selected' ).text() + '</td>'
                + '<td>' + $( '#received-date-id' ).val() + '</td>'
                + '<td>' + $( '#completetion-date-id' ).val() + '</td>'
                + '<td style="text-align: center; vertical-align: middle">'
                + '<button type="button" class="btn green" id="edit-row" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>'
                + '<button type="button" class="btn green" id="remove-from-table" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>'
                + '</td>'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][site_id]" value="' + $( '#site-id' ).val() + '" class="site-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][project_id]" value="' + $( '#project-id' ).val() + '" class="project-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][asset_group_id]" value="' + $( '#asset-group-id' ).val() + '" class="asset-group-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][asset_number_id]" value="' + $( '#asset-number-id' ).val() + '" class="asset-number-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier_id]" value="' + $( '#supplier-id' ).val() + '" class="supplier-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier_category_id]" value="' + $( '#supplier-category-id' ).val() + '" class="supplier-category-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][tr_class_id]" value="' + $( '#tr-class-id' ).val() + '" class="tr-class-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][received_at_supplier]" value="' + $( '#received-date-id' ).val() + '" class="receive-date-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][complete_date]" value="' + $( '#completetion-date-id' ).val() + '" class="complete-date-data">'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][comment]" value="' + $( '#comment-id' ).val() + '" class="comment-data">'
                + '</tr>';

            /* Append to the table */
            $( '#more-tr' ).append( row );

            /* Reset form */
            $( '#site-id' ).val( '' ).trigger( 'change' );
            $( '#received-date-id' ).val( '' );
            $( '#tr-class-id' ).val( '' ).trigger( 'change' );
            $( "#asset-group-id" ).val( '' ).trigger( 'change' );
            $( '#supplier-id' ).val( '' ).trigger( 'change' );
            $( '#supplier-category-id' ).val( '' ).trigger( 'change' );
            $( '#completetion-date-id' ).val( '' );
            $( '#ticket-form' )[0].reset();

            i++;
        } );

        $( document ).on( 'click', '#remove-from-table', function() {
            $( this ).closest( 'tr' ).remove();
        } );

        $( document ).on( 'click', '#edit-row', function() {
            tr_class_id          = $( this ).closest( 'tr' ).find( '.tr-class-data' ).val();
            asset_group_id       = $( this ).closest( 'tr' ).find( '.asset-group-data' ).val();
            asset_number_id      = $( this ).closest( 'tr' ).find( '.asset-number-data' ).val();
            supplier_category_id = $( this ).closest( 'tr' ).find( '.supplier-category-data' ).val();

            $( '#site-id' ).val( $( this ).closest( 'tr' ).find( '.site-data' ).val() ).trigger( 'change' );
            $( '#tr-class-id' ).val( tr_class_id ).trigger( 'change' );
            $( "#asset-group-id" ).val( asset_group_id ).trigger( 'change' );
            $( "#asset-number-id" ).val( asset_number_id ).trigger( 'change' );
            $( '#supplier-id' ).val( $( this ).closest( 'tr' ).find( '.supplier-data' ).val() ).trigger( 'change' );
            $( '#supplier-category-id' ).val( supplier_category_id ).trigger( 'change' );
            $( '#received-date-id' ).val( $( this ).closest( 'tr' ).find( '.receive-date-data' ).val() );
            $( '.form_datetime' ).datetimepicker();
            $( '#completetion-date-id' ).val( $( this ).closest( 'tr' ).find( '.complete-date-data' ).val() );
            $( '#comment-id' ).val( $( this ).closest( 'tr' ).find( '.comment-data' ).val() );

            $( this ).closest( 'tr' ).remove();
        } );

        var date = '<?php echo date( 'Y-m-d', strtotime( TR_MIN_DATE . ' Days' ) ); ?>'
        $( '.form_datetime' ).datetimepicker( {
            format        : 'yyyy-mm-dd hh:ii',
            rtl           : Metronic.isRTL(),
            startDate     : date,
            pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left"),
            autoclose     : true
        } ).on( 'change', function() {
            if( $( '#tr-class-id' ).val() != '' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();

                var tr_class_id   = $( '#tr-class-id' ).val();
                var received_date = $( '#received-date-id' ).val();

                $.ajax( {
                    dataType   : 'json',
                    type       : 'POST',
                    evalScripts: true,
                    url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'date_days' ) ); ?>',
                    data       : 'tr_class_id=' + tr_class_id + '&received_date=' + received_date,
                    success    : function( data ) {
                        $( '#completetion-date-id' ).val( data );

                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } );

        $( '#region_id' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var region_id = $( this ).val();

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'region_selected' ) ); ?>',
                data       : 'region_id=' + region_id,
                success    : function( data ) {
                    $( '#sub-center-container' ).html( '' );
                    var sub_center_options = '<select name="data[Ticket][sub_center_id]" class="form-control" id="sub_center_id">'
                    if( region_id == 0 || region_id == '' || data['SubCenter'].length == 0 ) {
                        sub_center_options += '<option value="">Select a project</option>';
                    }
                    if( data['SubCenter'].length > 0 ) {
                        for( var i = 0; i < data['SubCenter'].length; i++ ) {
                            sub_center_options += '<option value="' + data['SubCenter'][i]['SubCenter']['id'] + '"' + ( data['SubCenter'][i]['SubCenter']['id'] == sub_center_id ? ' selected' : '' ) + '>' + data['SubCenter'][i]['SubCenter']['sub_center_name'] + '</option>';
                        }
                    }
                    sub_center_options += '</select>';
                    $( '#sub-center-container' ).html( sub_center_options );
                    gp_warranty.select_options( 'sub_center_id' );
                    $( '#sub_center_id' ).trigger( 'change' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );

        $( document ).on( 'change', '#sub_center_id', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var sub_center_id = $( this ).val();
            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'sub_center_selected' ) ); ?>',
                data       : 'sub_center_id=' + sub_center_id,
                success    : function( data ) {
                    $( '#site-container' ).html( '' );
                    var site_options = '<select name="data[Ticket][site_id]" class="form-control" id="site-id">';
                    if( sub_center_id == 0 || sub_center_id == '' || data['Site'].length == 0 ) {
                        site_options += '<option value="">Select a site</option>';
                    }
                    if( data['Site'].length > 0 ) {
                        for( var i = 0; i < data['Site'].length; i++ ) {
                            site_options += '<option value="' + data['Site'][i]['Site']['id'] + '"' + ( site_id == data['Site'][i]['Site']['id'] ? ' selected' : '' ) + '>' + data['Site'][i]['Site']['site_name'] + '</option>';
                        }
                    }
                    site_options += '</select>';
                    $( '#site-container' ).html( site_options );
                    gp_warranty.select_options( 'site-id' );
                    $( '#site-id' ).trigger( 'change' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } );

        $( document ).on( 'change', '#site-id', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var site_id = $( this ).val();
            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'substitute' ) ); ?>',
                data       : 'site_id=' + site_id,
                success    : function( data ) {
                    $( '#project-container' ).html( '' );
                    project_options = '<select name="data[Ticket][project_id]" class="form-control" id="project-id">'
                    if( site_id == 0 || site_id == '' || data['Project'].length == 0 ) {
                        project_options += '<option value="">Select a project</option>';
                    }
                    if( data['Project'].length > 0 ) {
                        for( var i = 0; i < data['Project'].length; i++ ) {
                            project_options += '<option value="' + data['Project'][i]['Project']['id'] + '">' + data['Project'][i]['Project']['project_name'] + '</option>';
                        }
                    }
                    project_options += '</select>';
                    $( '#project-container' ).html( project_options );
                    gp_warranty.select_options( 'project-id' );

                    $( '#asset-group-container' ).html( '' );
                    asset_group_options = '<select name="data[Ticket][asset_group_id]" class="form-control" id="asset-group-id">';
                    if( site_id == 0 || site_id == '' || data['AssetGroup'].length == 0 ) {
                        asset_group_options += '<option value="">Select an asset group</option>';
                    }
                    if( data['AssetGroup'].length > 0 ) {
                        for( var i = 0; i < data['AssetGroup'].length; i++ ) {
                            asset_group_options += '<option value="' + data['AssetGroup'][i]['AssetGroup']['id'] + '"' + ( asset_group_id == data['AssetGroup'][i]['AssetGroup']['id'] ? ' selected' : '' ) + '>' + data['AssetGroup'][i]['AssetGroup']['asset_group_name'] + '</option>';
                        }
                    }
                    asset_group_options += '</select>';
                    $( '#asset-group-container' ).html( asset_group_options );

                    gp_warranty.select_options( 'asset-group-id' );
                    $( '#asset-group-id' ).trigger( 'change' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } );

        $( document ).on( 'change', '#asset-group-id', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var asset_group_id = $( this ).val();
            var text           = $( "#asset-group-id option:selected" ).text();

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'asset_substitute' ) ); ?>',
                data       : 'asset_group_id=' + asset_group_id,
                success    : function( data ) {
                    $( '#asset-number-container' ).html( '' );
                    asset_number_options = '<select name="data[Ticket][asset_number_id]" class="form-control" id="asset-number-id">';
                    if( asset_group_id == 0 || asset_group_id == '' || data['AssetNumber'].length == 0 ) {
                        asset_number_options += '<option value="">Select an asset number</option>';
                    }
                    if( data['AssetNumber'].length > 0 ) {
                        for( var i = 0; i < data['AssetNumber'].length; i++ ) {
                            asset_number_options += '<option value="' + data['AssetNumber'][i]['AssetNumber']['id'] + '"' + ( asset_number_id == data['AssetNumber'][i]['AssetNumber']['id'] ? ' selected' : '' ) + '>' + data['AssetNumber'][i]['AssetNumber']['asset_number'] + '</option>';
                        }
                    }
                    asset_number_options += '</select>';
                    $( '#asset-number-container' ).html( asset_number_options );
                    gp_warranty.select_options( 'asset-number-id' );

                    $( '#tr-class-container' ).html( '' );
                    tr_class_options = '<select name="data[Ticket][tr_class_id]" class="form-control" id="tr-class-id">';
                    if( asset_group_id == 0 || asset_group_id == '' || data['TrClass'].length == 0 ) {
                        tr_class_options += '<option value="">Select a TR Class</option>';
                    }
                    if( data['TrClass'].length > 0 ) {
                        for( var i = 0; i < data['TrClass'].length; i++ ) {
                            tr_class_options += '<option value="' + data['TrClass'][i]['TrClass']['id'] + '"' + ( tr_class_id == data['TrClass'][i]['TrClass']['id'] ? ' selected' : '' ) + '>' + data['TrClass'][i]['TrClass']['tr_class_name'] + '</option>';
                        }
                    }
                    tr_class_options += '</select>';
                    $( '#tr-class-container' ).html( tr_class_options );
                    gp_warranty.select_options( 'tr-class-id' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } );

        $( '#supplier-id' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();

            var supplier_id = $( this ).val();

            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'suppliers' ) ); ?>',
                data       : 'supplier_id=' + supplier_id,
                success    : function( data ) {
                    $( '#supplier-category-container' ).html( '' );
                    supplier_category_options = '<select name="data[Ticket][supplier_category_id]" class="form-control" id="supplier-category-id">';
                    if( supplier_id == 0 || supplier_id == '' || data.length == 0 ) {
                        supplier_category_options += '<option value="">Select a supplier category</option>';
                    }
                    if( data.length > 0 ) {
                        for( var i = 0; i < data.length; i++ ) {
                            supplier_category_options += '<option value="' + data[i]['SupplierCategory']['id'] + '"' + ( supplier_category_id == data[i]['SupplierCategory']['id'] ? ' selected' : '' ) + '>' + data[i]['SupplierCategory']['category_name'] + '</option>';
                        }
                    }
                    supplier_category_options += '</select>';
                    $( '#supplier-category-container' ).html( supplier_category_options );
                    gp_warranty.select_options( 'supplier-category-id' );

                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );

        $( document ).on( 'change', '#tr-class-id', function() {
            if( $( '#received-date-id' ).val() != '' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();

                var tr_class_id   = $( this ).val();
                var received_date = $( '#received-date-id' ).val();

                $.ajax( {
                    dataType   : 'json',
                    type       : 'POST',
                    evalScripts: true,
                    url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'class_days' ) ); ?>',
                    data       : 'tr_class_id=' + tr_class_id + '&received_date=' + received_date,
                    success    : function( data ) {
                        $( '#completetion-date-id' ).val( data );

                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } );

        $( '.submit-form' ).on( 'click', function() {
            var rowCount = $( '#tr-table > tbody > tr' ).length;
            if( rowCount > 0 ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();

                $( '#ticket-form' ).submit();
            }
            else {
                alert( 'Please add at least one TR' );
            }
        } );
    } );
</script>