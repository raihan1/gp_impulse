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
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Site</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 form-group">
                            <?php
                            echo $this->Form->input( 'site', array(
                                'options' => $siteList,
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
                        <div class="col-md-1 col-sm-1 col-xs-12 form-group text-center">to</div>
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

<script type="text/javascript">
    var asset_group  = '<?php echo !empty( $search['asset_group'] ) ? $search['asset_group'] : ''; ?>';
    var asset_number = '<?php echo !empty( $search['asset_number'] ) ? $search['asset_number'] : ''; ?>';
    var tr_class     = '<?php echo !empty( $search['tr_class'] ) ? $search['tr_class'] : ''; ?>';
    
    $( document ).ready( function() {
        gp_warranty.select_options( 'TicketSite' );
        gp_warranty.select_options( 'TicketAssetGroup' );
        gp_warranty.select_options( 'TicketAssetNumber' );
        gp_warranty.select_options( 'TicketTrClass' );
        gp_warranty.select_options( 'TicketSupplier' );
        gp_warranty.select_options( 'TicketStatus' );
        
        $( '#TicketPeriodFrom, #TicketPeriodTo' ).datepicker( {
            format     : 'yyyy-mm-dd',
            rtl        : Metronic.isRTL(),
            orientation: 'left',
            autoclose  : true
        } );
        
//        $( '#TicketSite' ).on( 'change', function() {
//            var siteId = $( this ).find( 'option:selected' ).attr( 'data-id' );
//            if( siteId != '' && siteId != 0 && typeof siteId != 'undefined' ) {
//                $( '.fancybox-loading' ).show();
//                $( '.mask' ).show();
//
//                $.ajax( {
//                    dataType: 'json',
//                    type    : 'POST',
//                    url     : '<?php //echo Router::url( array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'siteSelected' ) ); ?>//',
//                    data    : 'site_id=' + siteId,
//                    success : function( data ) {
//                        $( '#AssetGroupContainer' ).html( '' );
//
//                        var asset_group_options = '<select name="asset_group" id="TicketAssetGroup" class="form-control"><option value="">Select an asset group</option>';
//                        $.each( data[ 'AssetGroup' ], function( id, value ) {
//                            asset_group_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == asset_group ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        asset_group_options += '</select>';
//                        $( '#AssetGroupContainer' ).html( asset_group_options );
//                        gp_warranty.select_options( 'TicketAssetGroup' );
//                        $( '#TicketAssetGroup' ).trigger( 'change' );
//
//                        $( '.fancybox-loading' ).hide();
//                        $( '.mask' ).hide();
//                    }
//                } );
//            }
//        } ).trigger( 'change' );
        
//        $( document ).on( 'change', '#TicketAssetGroup', function() {
//            var assetGroupId = $( this ).find( 'option:selected' ).attr( 'data-id' );
//            if( assetGroupId != '' && assetGroupId != 0 && typeof assetGroupId != 'undefined' ) {
//                $( '.fancybox-loading' ).show();
//                $( '.mask' ).show();
//
//                $.ajax( {
//                    dataType: 'json',
//                    type    : 'POST',
//                    url     : '<?php //echo Router::url( array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'assetGroupSelected' ) ); ?>//',
//                    data    : 'asset_group_id=' + assetGroupId,
//                    success : function( data ) {
//                        $( '#AssetNumberContainer' ).html( '' );
//                        var asset_number_options = '<select name="asset_number" id="TicketAssetNumber" class="form-control"><option value="">Select an asset number</option>';
//                        $.each( data[ 'AssetNumber' ], function( id, value ) {
//                            asset_number_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == asset_number ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        asset_number_options += '</select>';
//                        $( '#AssetNumberContainer' ).html( asset_number_options );
//                        gp_warranty.select_options( 'TicketAssetNumber' );
//                        $( '#TicketAssetNumber' ).trigger( 'change' );
//
//                        $( '#TrClassContainer' ).html( '' );
//                        var tr_class_options = '<select name="tr_class" id="TicketTrClass" class="form-control"><option value="">Select a TR Class</option>';
//                        $.each( data[ 'TrClass' ], function( id, value ) {
//                            tr_class_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == tr_class ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        tr_class_options += '</select>';
//                        $( '#TrClassContainer' ).html( tr_class_options );
//                        gp_warranty.select_options( 'TicketTrClass' );
//                        $( '#TicketTrClass' ).trigger( 'change' );
//
//                        $( '.fancybox-loading' ).hide();
//                        $( '.mask' ).hide();
//                    }
//                } );
//            }
//        } );
    } );
</script>
