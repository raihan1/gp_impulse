/**
 * Author: Md. Sohel Rana <sohel_adust.@yahoo.com>
 * Copyright: (c) 2015 Humac Lab Limited <http://www.humaclab.com>
 */
var gp_warranty = function() {
    
    var dataTable = function( tableId, dataURL ) {
        var grid        = new Datatable();
        var selectedIds = [];
        
        grid.init( {
            src               : $( '#' + tableId ),
            onSuccess         : function( grid ) {},
            onError           : function( grid ) {
            },
            onDataLoad        : function( grid ) {
                $( '.tooltips' ).tooltip();
                
                $( '#' + tableId + ' .delete' ).on( 'click', function( e ) {
                    e.preventDefault();
                    var title  = $( this ).attr( 'data-msg' );
                    var id     = $( this ).attr( 'data-id' );
                    var status = $( this ).attr( 'data-val' );
                    bootbox.confirm( title, function( result ) {
                        if( result ) {
                            grid.setAjaxParam( 'customActionType', 'delete' );
                            grid.setAjaxParam( 'customActionName', id );
                            grid.setAjaxParam( 'customStatus', status );
                            grid.setAjaxParam( 'id', grid.getSelectedRows() );
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                        }
                    } );
                } );
            },
            beforeDrawCallback: function() {
                if( typeof subTotal != 'undefined' && subTotal == true && ( tableId == 'approved_tr_table' || tableId == 'invoiceable_tr_table' ) ) {
                    selectedIds = [];
                    $( '#' + tableId + ' tbody input[type="checkbox"]:checked' ).each( function() {
                        selectedIds.push( $( this ).val() );
                    } );
                }
            },
            afterDrawCallback : function() {
                if( typeof subTotal != 'undefined' && subTotal == true && ( tableId == 'approved_tr_table' || tableId == 'invoiceable_tr_table' ) ) {
                    $.each( selectedIds, function( index, id ) {
                        $( '#' + tableId + ' tbody input[type="checkbox"][value="' + id + '"]' ).prop( 'checked', true ).parent().addClass( 'checked' );
                    } );
                    $( '#' + tableId ).closest( '.dataTables_wrapper' ).find( '.table-group-actions > span' ).text( selectedIds.length + ' records selected:' );
                }
            },
            loadingMessage    : 'Loading...',
            dataTable         : {
                'bStateSave': false,
                'lengthMenu': [
                    [ 10, 20, 50, 100, -1 ],
                    [ 10, 20, 50, 100, 'All' ]
                ],
                'pageLength': 100,
                'ajax'      : { 'url': dataURL },
                'order'     : [ [ 0, 'desc' ] ],
                'columnDefs': [
                    { 'targets': 'no-sort', 'orderable': false },
                    { 'targets': 'text-right', 'class': 'text-right' },
                    { 'targets': 'text-center', 'class': 'text-center' }
                ]
            }
        } );
        
        grid.getTableWrapper().on( 'click', '.table-group-action-submit', function( e ) {
            e.preventDefault();
            var action = $( '.table-group-action-input', grid.getTableWrapper() );
            if( action.val() != '' && grid.getSelectedRowsCount() > 0 ) {
                grid.setAjaxParam( 'customActionType', 'group_action' );
                grid.setAjaxParam( 'customActionName', action.val() );
                grid.setAjaxParam( 'id', grid.getSelectedRows() );
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            }
            else if( action.val() == '' ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'Please select an action',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
            else if( grid.getSelectedRowsCount() === 0 ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'No record selected',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
        } );
    };
    
    var assignDataTable = function( tableId, dataURL ) {
        var grid = new Datatable();
        
        grid.init( {
            src           : $( '#' + tableId ),
            onSuccess     : function( grid ) {
            },
            onError       : function( grid ) {
            },
            onDataLoad    : function( grid ) {
                $( '.tooltips' ).tooltip();
                
                $( '#' + tableId + ' .lock' ).click( function( e ) {
                    e.preventDefault();
                    var title  = $( this ).attr( 'data-msg' );
                    var id     = $( this ).attr( 'data-id' );
                    var status = $( this ).attr( 'data-val' );
                    bootbox.confirm( title, function( result ) {
                        if( result ) {
                            grid.setAjaxParam( 'customActionType', 'lock' );
                            grid.setAjaxParam( 'customActionName', id );
                            grid.setAjaxParam( 'customStatus', status );
                            grid.setAjaxParam( 'id', grid.getSelectedRows() );
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                        }
                    } );
                } );
            },
            loadingMessage: 'Loading...',
            dataTable     : {
                'bStateSave': false,
                'lengthMenu': [
                    [ 10, 20, 50, 100, -1 ],
                    [ 10, 20, 50, 100, 'All' ]
                ],
                'pageLength': 10,
                'ajax'      : { 'url': dataURL },
                'order'     : [ [ 0, 'desc' ] ],
                'columnDefs': [ { 'targets': 'no-sort', 'orderable': false } ]
            }
        } );
        
        grid.getTableWrapper().on( 'click', '.table-group-action-submit', function( e ) {
            e.preventDefault();
            var action = $( '.table-group-action-input', grid.getTableWrapper() );
            if( action.val() != '' && grid.getSelectedRowsCount() > 0 ) {
                grid.setAjaxParam( 'customActionType', 'group_action' );
                grid.setAjaxParam( 'customActionName', action.val() );
                grid.setAjaxParam( 'id', grid.getSelectedRows() );
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            }
            else if( action.val() == '' ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'Please select an action',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
            else if( grid.getSelectedRowsCount() === 0 ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'No record selected',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
        } );
    };
    
    var lockDataTable = function( tableId, dataURL ) {
        var grid = new Datatable();
        
        grid.init( {
            src           : $( '#' + tableId ),
            onSuccess     : function( grid ) {
            },
            onError       : function( grid ) {
            },
            onDataLoad    : function( grid ) {
                $( '.tooltips' ).tooltip();
                
                $( '#' + tableId + ' .unlock' ).click( function( e ) {
                    e.preventDefault();
                    var title  = $( this ).attr( 'data-msg' );
                    var id     = $( this ).attr( 'data-id' );
                    var status = $( this ).attr( 'data-val' );
                    bootbox.confirm( title, function( result ) {
                        if( result ) {
                            grid.setAjaxParam( 'customActionType', 'unlock' );
                            grid.setAjaxParam( 'customActionName', id );
                            grid.setAjaxParam( 'customStatus', status );
                            grid.setAjaxParam( 'id', grid.getSelectedRows() );
                            grid.getDataTable().ajax.reload();
                            grid.clearAjaxParams();
                        }
                    } );
                } );
            },
            loadingMessage: 'Loading...',
            dataTable     : {
                'bStateSave': false,
                'lengthMenu': [
                    [ 10, 20, 50, 100, -1 ],
                    [ 10, 20, 50, 100, 'All' ]
                ],
                'pageLength': 10,
                'ajax'      : { 'url': dataURL },
                'order'     : [ [ 0, 'desc' ] ],
                'columnDefs': [ { 'targets': 'no-sort', 'orderable': false } ]
            }
        } );
        
        grid.getTableWrapper().on( 'click', '.table-group-action-submit', function( e ) {
            e.preventDefault();
            var action = $( '.table-group-action-input', grid.getTableWrapper() );
            if( action.val() != '' && grid.getSelectedRowsCount() > 0 ) {
                grid.setAjaxParam( 'customActionType', 'group_action' );
                grid.setAjaxParam( 'customActionName', action.val() );
                grid.setAjaxParam( 'id', grid.getSelectedRows() );
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            }
            else if( action.val() == '' ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'Please select an action',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
            else if( grid.getSelectedRowsCount() === 0 ) {
                Metronic.alert( {
                    type     : 'danger',
                    icon     : 'warning',
                    message  : 'No record selected',
                    container: grid.getTableWrapper(),
                    place    : 'prepend'
                } );
            }
        } );
    };
    
    var expandableDataTable = function( tableId, data, dataUrl ) {
        var table = $( '#' + tableId );
        $( '.tooltips' ).tooltip();
        
        function fnFormatDetails( oTable, nTr, index ) {
            var obj = jQuery.parseJSON( data );
            var img = '';
            for( var rv = 0; rv < obj[ index ].rating_value; rv++ ) {
                img += '<img src="' + RATINGIMAGE + '/star-on.png">'
            }
            for( var rv = 0; rv < 5 - obj[ index ].rating_value; rv++ ) {
                img += '<img src="' + RATINGIMAGE + '/star-off.png">'
            }
            
            var sOut = '<table class="pull-left" style="width: 60%">';
            sOut += '<tr><td><b>Route : </b></td><td><b>' + obj[ index ].route_name + '</b></td><td><b>Category : </b></td><td><b>' + obj[ index ].category_name + '</b></td></tr>';
            sOut += '<tr><td><b>Business Category : </b></td><td><b>' + obj[ index ].business_category_name + '</b></td><td><b>Type : </b></td><td><b>' + obj[ index ].type + '</b></td></tr>';
            sOut += '<tr><td><b>Due : </b></td><td><b>' + obj[ index ].due + '</b></td><td><b>Rating : </b></td><td><b>' + img + '</b></td></tr>';
            sOut += '</table>';
            return sOut;
        }
        
        var nCloneTh       = document.createElement( 'th' );
        nCloneTh.className = "table-checkbox";
        
        var nCloneTd       = document.createElement( 'td' );
        nCloneTd.innerHTML = '<span class="row-details row-details-close"></span>';
        
        table.find( 'thead tr' ).each( function() {
            this.insertBefore( nCloneTh, this.childNodes[ 0 ] );
        } );
        
        table.find( 'tbody tr' ).each( function() {
            this.insertBefore( nCloneTd.cloneNode( true ), this.childNodes[ 0 ] );
        } );
        
        var oTable = table.dataTable( {
            "language"  : {
                "aria"        : {
                    "sortAscending" : ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable"  : "No data available in table",
                "info"        : "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty"   : "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu"  : "Show _MENU_ entries",
                "search"      : "Search:",
                "zeroRecords" : "No matching records found"
            },
            "columnDefs": [ {
                "orderable": false,
                "targets"  : [ 0 ]
            } ],
            "order"     : [
                [ 1, 'asc' ]
            ],
            "lengthMenu": [
                [ 10, 15, 20, -1 ],
                [ 10, 15, 20, "All" ]
            ],
            "pageLength": 10,
            'columnDefs': [ { 'targets': 0, 'orderable': false }, { 'targets': 'no-sort', 'orderable': false } ]
        } );
        
        var tableWrapper = $( '#sample_3_wrapper' );
        
        tableWrapper.find( '.dataTables_length select' ).select2();
        
        table.on( 'click', ' tbody td .row-details', function() {
            var nTr   = $( this ).parents( 'tr' )[ 0 ];
            var index = $( this ).parent( 'td' ).parent( 'tr' ).attr( 'data-id' );
            
            if( oTable.fnIsOpen( nTr ) ) {
                $( this ).addClass( 'row-details-close' ).removeClass( 'row-details-open' );
                oTable.fnClose( nTr );
            }
            else {
                $( this ).addClass( 'row-details-open' ).removeClass( 'row-details-close' );
                oTable.fnOpen( nTr, fnFormatDetails( oTable, nTr, index ), 'details' );
            }
        } );
        
        $( '.delete' ).click( function( e ) {
            e.preventDefault();
            var title = $( this ).attr( 'data-msg' );
            var id    = $( this ).attr( 'data-id' );
            bootbox.confirm( title, function( result ) {
                if( result ) {
                    $.ajax( {
                        dataType   : 'json',
                        type       : 'POST',
                        evalScripts: true,
                        url        : dataUrl,
                        data       : 'id=' + id,
                        success    : function( response ) {
                            location.reload();
                        }
                    } );
                }
            } );
        } );
    };
    
    return {
        init              : function() {
            
        },
        data_table        : function( tableId, dataURL ) {
            dataTable( tableId, dataURL );
        },
        assign_data_table : function( tableId, dataURL ) {
            assignDataTable( tableId, dataURL );
        },
        lock_data_table   : function( tableId, dataURL ) {
            lockDataTable( tableId, dataURL );
        },
        expandable_table  : function( tableId, data, dataUrl ) {
            expandableDataTable( tableId, data, dataUrl );
        },
        showMessage       : function( msg_container, msg, type, show_time, hide ) {
            msg_container = !msg_container ? '#form_result' : msg_container;
            msg           = !msg ? 'Something is wrong! Please check your input.' : msg;
            type          = !type ? 'danger' : type;
            show_time     = !show_time ? 5000 : show_time;
            msg           = msg + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
            $( msg_container ).html( msg ).addClass( 'alert alert-' + type + ' fade in' ).show();
            if( hide ) {
                setTimeout( function() {
                    $( msg_container ).hide( 'slow', function() {
                        $( this ).html( '' ).removeClass( 'alert alert-' + type + ' fade in' );
                        //$.validator.reposition();
                    } );
                }, show_time );
            }
        },
        select_options    : function( selectId ) {
            function format( state ) {
                if( !state.id ) {
                    return state.text;
                }
                return state.text;
            }
            
            $( '#' + selectId ).select2( {
                placeholder    : 'Please select an option',
                allowClear     : true,
                formatResult   : format,
                formatSelection: format,
                escapeMarkup   : function( m ) {
                    return m;
                }
            } );
        },
        date_picker       : function() {
            $( '.date-picker' ).datepicker( {
                format     : 'yyyy-mm-dd',
                rtl        : Metronic.isRTL(),
                orientation: 'left',
                autoclose  : true
            } );
        },
        custom_date_picker: function( inputId ) {
            $( '#' + inputId ).datepicker( {
                format     : 'yyyy-mm-dd',
                //defaultDate: '+0d',
                rtl        : Metronic.isRTL(),
                orientation: 'left',
                autoclose  : true
            } );
        }
    }
}();

$( document ).ready( function() {
    gp_warranty.init();
} );