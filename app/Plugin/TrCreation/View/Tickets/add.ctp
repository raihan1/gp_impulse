<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_creation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-ticket"></i>
                    <?php echo $this->Html->link( 'Ticket List', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index' ) ); ?>
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
        echo $this->Form->hidden( 'id', array( 'value' => isset( $data['Ticket']['id'] ) ? $data['Ticket']['id'] : '' ) );
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
                                        'value'     => !empty( $loginUser['User']['name'] ) ? $loginUser['User']['name'] : '',
                                    ) );
                                    ?>
                                </div>
                            <?php } ?>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Office</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group">
                                <?php
                                echo $this->Form->input( 'sub_center', array(
                                    'options'  => $officeList,
                                    'empty'    => 'Select a office',
                                    'class'    => 'form-control',
                                    'id'       => 'sub_center',
                                    'value'    => !empty( $data['Ticket']['sub_center'] ) ? $data['Ticket']['sub_center'] : '',
                                    'disabled' => !empty( $data['Ticket']['id'] ),
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">Site</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="siteContainer">
                                <?php
                                echo $this->Form->input( 'site', array(
                                    'options'  => array(),
                                    'empty'    => 'Select a site',
                                    'class'    => 'form-control',
                                    'id'       => 'site',
                                    'value'    => !empty( $data['Ticket']['site'] ) ? $data['Ticket']['site'] : ''
                                ) );
                                ?>
                            </div>
                            <label class="col-md-4 col-sm-4 col-xs-12 control-label">TR Class</label>
                            <div class="col-md-8 col-sm-8 col-xs-12 form-group" id="trClassContainer">
                                <?php
                                echo $this->Form->input( 'tr_class', array(
                                    'options' => $trClass,
                                    'empty'   => 'Select a TR Class',
                                    'class'   => 'form-control',
                                    'id'      => 'tr_class',
                                    'value'   => !empty( $data['Ticket']['tr_class'] ) ? $data['Ticket']['tr_class'] : '',
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
                                        <td><?php echo $TR['supplier']; ?></td>
                                        <td><?php echo $TR['category']; ?></td>
                                        <td><?php echo $TR['received_at_supplier']; ?></td>
                                        <td><?php echo $TR['complete_date']; ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn green editTicket" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>
                                            <button type="button" class="btn green removeTicket" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][sub_center]" value="<?php echo $TR['sub_center']; ?>" class="sub-center-data" />
                                            <input type="hidden" name="data[Ticket][batch][<?php echo $i; ?>][site]" value="<?php echo $TR['site']; ?>" class="site-data" />
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
            <div class="col-md-12 text-center margin-bottom-20">
                <button type="button" class="btn green submit-form"><i class="fa fa-check"></i> Submit</button>
                <?php echo $this->Html->link( '<i class="fa fa-arrow-left"></i> Cancel', array( 'plugin' => 'tr_creation', 'controller' => 'tickets', 'action' => 'index' ), array( 'escape' => FALSE, 'class' => 'btn red' ) ); ?>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    var site              = '<?php echo !empty( $data['Ticket']['site'] ) ? $data['Ticket']['site'] : ''; ?>';
    var tr_class          = '<?php echo !empty( $data['Ticket']['tr_class'] ) ? $data['Ticket']['tr_class'] : ''; ?>';
    var supplier_category = '<?php echo !empty( $data['Ticket']['supplier_category'] ) ? $data['Ticket']['supplier_category'] : ''; ?>';
    
    var populate_complete_date = function() {
        $( '.fancybox-loading' ).show();
        $( '.mask' ).show();
        
        var tr_class_id          = $( '#tr_class' ).find( 'option:selected' ).attr( 'value' );
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
        gp_warranty.select_options( 'sub_center' );
        gp_warranty.select_options( 'site' );
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
                + '<td>' + $( '#tr_class' ).find('option:selected').text() + '</td>'
                + '<td>' + $( '#supplier' ).val() + '</td>'
                + '<td>' + $( '#supplier_category' ).val() + '</td>'
                + '<td>' + $( '#received_at_supplier' ).val() + '</td>'
                + '<td>' + $( '#complete_date' ).val() + '</td>'
                + '<td class="text-center">'
                + '<button type="button" class="btn green editTicket" style="padding: 1px 6px"><i class="fa fa-edit"></i></button>'
                + '<button type="button" class="btn green removeTicket" style="padding: 1px 6px"><i class="fa fa-minus"></i></button>'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][sub_center]" value="' + $( '#sub_center' ).find('option:selected').text() + '" class="sub-center-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][sub_center_id]" value="' + $( '#sub_center' ).find('option:selected').val() + '" class="sub-center-data-id" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][site]" value="' + $( '#site' ).val() + '" class="site-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][site_id]" value="' + $( '#site' ).find('option:selected').val() + '" class="site-data-id" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier]" value="' + $( '#supplier' ).val() + '" class="supplier-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][supplier_category]" value="' + $( '#supplier_category' ).val() + '" class="supplier-category-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][tr_class]" value="' + $( '#tr_class' ).find('option:selected').text() + '" class="tr-class-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][tr_class_id]" value="' + $( '#tr_class' ).find('option:selected').val() + '" class="tr-class-data-id" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][received_at_supplier]" value="' + $( '#received_at_supplier' ).val() + '" class="receive-date-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][complete_date]" value="' + $( '#complete_date' ).val() + '" class="complete-date-data" />'
                + '<input type="hidden" name="data[Ticket][batch][' + i + '][comment]" value="' + $( '#comment' ).val() + '" class="comment-data" />'
                + '</td>'
                + '</tr>';

            /* Append to the table */
            $( '#ticketList tbody' ).append( row );

            /* Reset form */
            $( '#sub_center' ).val( '' ).trigger( 'change' );
            $( '#site' ).val( '' );
            $( '#tr_class' ).val( '' ).trigger( 'change' );
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
            site              = $( this ).closest( 'tr' ).find( '.site-data-id' ).val();
            tr_class          = $( this ).closest( 'tr' ).find( '.tr-class-data-id' ).val();
            supplier_category = $( this ).closest( 'tr' ).find( '.supplier-category-data' ).val();

            $( '#sub_center' ).val( $( this ).closest( 'tr' ).find( '.sub-center-data-id' ).val() ).trigger( 'change' );
            $( '#site' ).val( site ).trigger( 'change' );
            $( '#tr_class' ).val( tr_class ).trigger( 'change' );
            $( '#supplier' ).val( $( this ).closest( 'tr' ).find( '.supplier-data' ).val() ).trigger( 'change' );
            $( '#supplier_category' ).val( supplier_category ).trigger( 'change' );
            $( '#received_at_supplier' ).val( $( this ).closest( 'tr' ).find( '.receive-date-data' ).val() );
            $( '.form_datetime' ).datetimepicker();
            $( '#complete_date' ).val( $( this ).closest( 'tr' ).find( '.complete-date-data' ).val() );
            $( '#comment' ).val( $( this ).closest( 'tr' ).find( '.comment-data' ).val() );

            $( this ).closest( 'tr' ).remove();
        } );
        
        var date = '<?php echo date( 'Y-m-d', strtotime( TR_MIN_DATE . ' Days' ) ); ?>';
        $( '.form_datetime' ).datetimepicker( {
            format        : 'yyyy-mm-dd hh:ii',
            rtl           : Metronic.isRTL(),
            startDate     : date,
            pickerPosition: ( Metronic.isRTL() ? 'bottom-right' : 'bottom-left' ),
            autoclose     : true
        } ).on( 'change', function() {
            if( $( '#tr_class' ).val() != '' ) {
                populate_complete_date();
            }
        } );
        
        $( '#sub_center' ).on( 'change', function() {
            $( '.fancybox-loading' ).show();
            $( '.mask' ).show();
            
            var office_id = $( this ).find( 'option:selected' ).attr( 'value' );
            
            $.ajax( {
                dataType   : 'json',
                type       : 'POST',
                evalScripts: true,
                url        : '<?php echo Router::url( array( 'controller' => 'tickets', 'action' => 'office_selected' ) ); ?>',
                data       : 'office_id=' + office_id,
                success    : function( data ) {
                    $('#siteContainer').html( '' );
                    var site_options = '<select name="data[Ticket][site]" class="form-control" id="site">';
                    if( office_id == 0 || office_id == '' || typeof office_id == 'undefined' ) {
                        site_options += '<option value="">Select a site</option>';
                    }
                    if( data[ 'Site' ].length > 0 ) {
                        site_options += '<option value="">Select a site</option>';
                        for( var i = 0; i < data[ 'Site' ].length; i++ ) {
                            site_options += '<option value="' + data[ 'Site' ][ i ][ 'Site' ][ 'site_name' ] + '" data-id="' + data[ 'Site' ][ i ][ 'Site' ][ 'id' ] + '"' + ( site == data[ 'Site' ][ i ][ 'Site' ][ 'site_name' ] ? ' selected' : '' ) + '>' + data[ 'Site' ][ i ][ 'Site' ][ 'site_name' ] + '</option>';
                        }
                    }
                    site_options += '</select>';
                    $( '#siteContainer' ).html( site_options );
                    gp_warranty.select_options( 'site' );

                    $( '#site' ).trigger( 'change' );
                    
                    $( '.fancybox-loading' ).hide();
                    $( '.mask' ).hide();
                }
            } );
        } ).trigger( 'change' );
        
//        $( document ).on( 'change', '#site', function() {
//            $( '.fancybox-loading' ).show();
//            $( '.mask' ).show();
//
//            var site_id = $( this ).find( 'option:selected' ).attr( 'data-id' );
//
//            $.ajax( {
//                dataType   : 'json',
//                type       : 'POST',
//                evalScripts: true,
//                url        : '<?php //echo Router::url( array( 'controller' => 'tickets', 'action' => 'site_selected_tr_class' ) ); ?>//',
//                data       : 'site_id=' + site_id,
//                success    : function( data ) {
//                    $( '#trClassContainer' ).html( '' );
//                    var tr_class_options = '<select name="data[Ticket][tr_class]" class="form-control" id="tr_class"><option value="">Select a TR Class</option>';
//                    if( data[ 'TrClass' ].length > 0 ) {
//                        for( var i = 0; i < data[ 'TrClass' ].length; i++ ) {
//                            tr_class_options += '<option value="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '" data-id="' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'id' ] + '"' + ( tr_class == data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] ? ' selected' : '' ) + '>' + data[ 'TrClass' ][ i ][ 'TrClass' ][ 'tr_class_name' ] + '</option>';
//                        }
//                    }
//                    tr_class_options += '</select>';
//                    $( '#trClassContainer' ).html( tr_class_options );
//                    gp_warranty.select_options( 'tr_class' );
//
//                    $( '.fancybox-loading' ).hide();
//                    $( '.mask' ).hide();
//                }
//            } );
//        } );
        
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
        } );
        
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