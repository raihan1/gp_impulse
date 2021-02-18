<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'security', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
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
                <?php echo $this->element( 'tickets/search', array( 'plugin' => 'security' ) ); ?>
            </div>
        </div>

        <?php if( isset( $data ) ) { ?>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $this->element( 'tickets/list', array( 'plugin' => 'security' ) ); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    var sub_center_id = <?php echo !empty( $search['sub_center_id'] ) ? $search['sub_center_id'] : 0; ?>;
    var site_id = <?php echo !empty( $search['site_id'] ) ? $search['site_id'] : 0; ?>;
    var asset_group_id = <?php echo !empty( $search['asset_group_id'] ) ? $search['asset_group_id'] : 0; ?>;
    var asset_number_id = <?php echo !empty( $search['asset_number_id'] ) ? $search['asset_number_id'] : 0; ?>;
    var tr_class_id = <?php echo !empty( $search['tr_class_id'] ) ? $search['tr_class_id'] : 0; ?>;

    $( document ).ready( function() {
        gp_warranty.select_options( 'TicketRegionId' );
        gp_warranty.select_options( 'TicketSubCenterId' );
        gp_warranty.select_options( 'TicketSiteId' );
        gp_warranty.select_options( 'TicketAssetGroupId' );
        gp_warranty.select_options( 'TicketAssetNumberId' );
        gp_warranty.select_options( 'TicketTrClassId' );
        gp_warranty.select_options( 'TicketSupplierId' );
        gp_warranty.select_options( 'TicketStatusId' );

        $( '#TicketRegionId' ).on( 'change', function() {
            var regionId = $( this ).val();
            if( regionId != '' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();

                $.ajax( {
                    dataType: 'json',
                    type    : 'POST',
                    url     : '<?php echo Router::url( array( 'controller' => 'reports', 'action' => 'regionSelected' ) ); ?>',
                    data    : 'region_id=' + regionId,
                    success : function( data ) {
                        $( '#SubCenterContainer' ).html( '' );

                        var sub_center_options = '<select name="sub_center_id" id="TicketSubCenterId" class="form-control"><option value="">Select a Office</option>';
                        $.each( data['SubCenter'], function( id, value ) {
                            sub_center_options += '<option value="' + id + '"' + ( id == sub_center_id ? ' selected' : '' ) + '>' + value + '</option>';
                        } );
                        sub_center_options += '</select>';
                        $( '#SubCenterContainer' ).html( sub_center_options );
                        gp_warranty.select_options( 'TicketSubCenterId' );
                        $( '#TicketSubCenterId' ).trigger( 'change' );

                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } ).trigger( 'change' );

        $( document ).on( 'change', '#TicketSubCenterId', function() {
            var sub_center_id = $( this ).val();
            if( sub_center_id != '' ) {
                $( '.fancybox-loading' ).show();
                $( '.mask' ).show();

                $.ajax( {
                    dataType: 'json',
                    type    : 'POST',
                    url     : '<?php echo Router::url( array( 'controller' => 'reports', 'action' => 'subCenterSelected' ) ); ?>',
                    data    : 'sub_center_id=' + sub_center_id,
                    success : function( data ) {
                        $( '#SiteContainer' ).html( '' );

                        var site_options = '<select name="site_id" id="TicketSiteId" class="form-control"><option value="">Select a site</option>';
                        $.each( data['Site'], function( id, value ) {
                            site_options += '<option value="' + id + '"' + ( id == site_id ? ' selected' : '' ) + '>' + value + '</option>';
                        } );
                        site_options += '</select>';

                        $( '#SiteContainer' ).html( site_options );
                        gp_warranty.select_options( 'TicketSiteId' );
                        $( '#TicketSiteId' ).trigger( 'change' );

                        $( '.fancybox-loading' ).hide();
                        $( '.mask' ).hide();
                    }
                } );
            }
        } );

//        $( document ).on( 'change', '#TicketSiteId', function() {
//            var siteId = $( this ).val();
//            if( siteId != '' ) {
//                $( '.fancybox-loading' ).show();
//                $( '.mask' ).show();
//
//                $.ajax( {
//                    dataType: 'json',
//                    type    : 'POST',
//                    url     : '<?php //echo Router::url( array( 'controller' => 'reports', 'action' => 'siteSelected' ) ); ?>//',
//                    data    : 'site_id=' + siteId,
//                    success : function( data ) {
//                        $( '#AssetGroupContainer' ).html( '' );
//
//                        var asset_group_options = '<select name="asset_group_id" id="TicketAssetGroupId" class="form-control"><option value="">Select an asset group</option>';
//                        $.each( data['AssetGroup'], function( id, value ) {
//                            asset_group_options += '<option value="' + id + '"' + ( id == asset_group_id ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        asset_group_options += '</select>';
//                        $( '#AssetGroupContainer' ).html( asset_group_options );
//                        gp_warranty.select_options( 'TicketAssetGroupId' );
//                        $( '#TicketAssetGroupId' ).trigger( 'change' );
//
//                        $( '.fancybox-loading' ).hide();
//                        $( '.mask' ).hide();
//                    }
//                } );
//            }
//        } );
//
//        $( document ).on( 'change', '#TicketAssetGroupId', function() {
//            var assetGroupId = $( this ).val();
//            if( assetGroupId != '' ) {
//                $( '.fancybox-loading' ).show();
//                $( '.mask' ).show();
//
//                $.ajax( {
//                    dataType: 'json',
//                    type    : 'POST',
//                    url     : '<?php //echo Router::url( array( 'controller' => 'reports', 'action' => 'assetGroupSelected' ) ); ?>//',
//                    data    : 'asset_group_id=' + assetGroupId,
//                    success : function( data ) {
//                        $( '#AssetNumberContainer' ).html( '' );
//
//                        var asset_number_options = '<select name="asset_number_id" id="TicketAssetNumberId" class="form-control"><option value="">Select an asset number</option>';
//                        $.each( data['AssetNumber'], function( id, value ) {
//                            asset_number_options += '<option value="' + id + '"' + ( id == asset_number_id ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        asset_number_options += '</select>';
//                        $( '#AssetNumberContainer' ).html( asset_number_options );
//                        gp_warranty.select_options( 'TicketAssetNumberId' );
//                        $( '#TicketAssetNumberId' ).trigger( 'change' );
//
//                        $( '#TrClassContainer' ).html( '' );
//
//                        var tr_class_options = '<select name="tr_class_id" id="TicketTrClassId" class="form-control"><option value="">Select a TR Class</option>';
//                        $.each( data['TrClass'], function( id, value ) {
//                            tr_class_options += '<option value="' + id + '"' + ( id == tr_class_id ? ' selected' : '' ) + '>' + value + '</option>';
//                        } );
//                        tr_class_options += '</select>';
//                        $( '#TrClassContainer' ).html( tr_class_options );
//                        gp_warranty.select_options( 'TicketTrClassId' );
//                        $( '#TicketTrClassId' ).trigger( 'change' );
//
//                        $( '.fancybox-loading' ).hide();
//                        $( '.mask' ).hide();
//                    }
//                } );
//            }
//        } );
    } );
</script>