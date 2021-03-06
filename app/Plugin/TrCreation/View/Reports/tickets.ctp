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
                    <i class="fa fa-bar-chart"></i>
                    <span>Tickets Report</span>
                </li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->element( 'form/tickets/search', array(), array( 'plugin' => 'tr_creation' ) ); ?>
            </div>
        </div>
    
        <?php if( !empty( $search ) ) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-search"></i> Result
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if( empty( $data ) ) {
                                        echo 'No result found. Try searching again.';
                                    }
                                    else {
                                        echo "Total {$data} tickets found.";
                                        ?>
                                        <div class="btn-group pull-right">
                                            <?php echo $this->Html->link( 'Export to Excel', array( 'plugin' => 'tr_creation', 'controller' => 'reports', 'action' => 'download_tickets', '?' => http_build_query( $_REQUEST ) ) ); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    var asset_group  = '<?php echo !empty( $search['asset_group'] ) ? $search['asset_group'] : ''; ?>';
    var asset_number = '<?php echo !empty( $search['asset_number'] ) ? $search['asset_number'] : ''; ?>';
    var tr_class     = '<?php echo !empty( $search['tr_class'] ) ? $search['tr_class'] : ''; ?>';
    
    $( document ).ready( function() {
        gp_warranty.select_options( 'TicketSite' );
        gp_warranty.select_options( 'TicketAssetGroup' );
        gp_warranty.select_options( 'TicketAssetNumber' );
        gp_warranty.select_options( 'TicketTrClass' );
        gp_warranty.select_options( 'TicketSupplierId' );
        gp_warranty.select_options( 'TicketStatus' );
        
        $( '#TicketPeriodFrom, #TicketPeriodTo' ).datepicker( {
            format     : 'yyyy-mm-dd',
            rtl        : Metronic.isRTL(),
            orientation: 'left',
            autoclose  : true
        } );
        
        $( '#TicketSite' ).on( 'change', function() {
            var siteId = $( this ).find( 'option:selected' ).attr( 'data-id' );
            if( siteId != '' && siteId != 0 && typeof siteId != 'undefined' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
                
                $.ajax( {
                    dataType: 'json',
                    type    : 'POST',
                    url     : '<?php echo Router::url( array( 'plugin' => 'tr_creation', 'controller' => 'reports', 'action' => 'siteSelected' ) ); ?>',
                    data    : 'site_id=' + siteId,
                    success : function( data ) {
                        $( '#AssetGroupContainer' ).html( '' );
                        var asset_group_options = '<select name="asset_group" id="TicketAssetGroup" class="form-control"><option value="">Select an asset group</option>';
                        $.each( data[ 'AssetGroup' ], function( id, value ) {
                            asset_group_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == asset_group ? ' selected' : '' ) + '>' + value + '</option>';
                        } );
                        asset_group_options += '</select>';
                        $( '#AssetGroupContainer' ).html( asset_group_options );
                        gp_warranty.select_options( 'TicketAssetGroup' );
                        $( '#TicketAssetGroup' ).trigger( 'change' );
                        
                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } ).trigger( 'change' );
        
        $( document ).on( 'change', '#TicketAssetGroup', function() {
            var assetGroupId = $( this ).find( 'option:selected' ).attr( 'data-id' );
            if( assetGroupId != '' && assetGroupId != 0 && typeof assetGroupId != 'undefined' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();
                
                $.ajax( {
                    dataType: 'json',
                    type    : 'POST',
                    url     : '<?php echo Router::url( array( 'plugin' => 'tr_creation', 'controller' => 'reports', 'action' => 'assetGroupSelected' ) ); ?>',
                    data    : 'asset_group_id=' + assetGroupId,
                    success : function( data ) {
                        $( '#AssetNumberContainer' ).html( '' );
                        var asset_number_options = '<select name="asset_number" id="TicketAssetNumber" class="form-control"><option value="">Select an asset number</option>';
                        $.each( data[ 'AssetNumber' ], function( id, value ) {
                            asset_number_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == asset_number ? ' selected' : '' ) + '>' + value + '</option>';
                        } );
                        asset_number_options += '</select>';
                        $( '#AssetNumberContainer' ).html( asset_number_options );
                        gp_warranty.select_options( 'TicketAssetNumber' );
                        $( '#TicketAssetNumber' ).trigger( 'change' );
                        
                        $( '#TrClassContainer' ).html( '' );
                        var tr_class_options = '<select name="tr_class" id="TicketTrClass" class="form-control"><option value="">Select a TR Class</option>';
                        $.each( data[ 'TrClass' ], function( id, value ) {
                            tr_class_options += '<option value="' + value + '" data-id="' + id + '"' + ( value == tr_class ? ' selected' : '' ) + '>' + value + '</option>';
                        } );
                        tr_class_options += '</select>';
                        $( '#TrClassContainer' ).html( tr_class_options );
                        gp_warranty.select_options( 'TicketTrClass' );
                        $( '#TicketTrClass' ).trigger( 'change' );
                        
                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } );
    } );
</script>