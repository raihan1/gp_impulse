var TableManaged = function() {

    var initTable2 = function() {

        var table = $( '#sample_2' );

        table.dataTable( {
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
            "bStateSave": false, // save datatable state(pagination, sort, etc) in cookie.

            "lengthMenu": [
                [10, 15, 20, -1],
                [10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,
            "language"  : {
                "lengthMenu": " _MENU_ records",
                "paging"    : {
                    "previous": "Prev",
                    "next"    : "Next"
                }
            },
            "columnDefs": [
                {'orderable': false, 'targets': [0]},
                {"searchable": false, "targets": [0]}
            ],
            "order"     : [
                [1, "desc"]
            ] // set first column as a default sort by asc
        } );

        var tableWrapper = jQuery( '#sample_2_wrapper' );

        table.find( '.group-checkable' ).change( function() {
            var isChecked = $( this ).is( ":checked" );
            table.find( '.checkboxes' ).each( function() {
                if( isChecked ) {
                    $( this ).attr( 'checked', true ).trigger( 'change' );
                    $( this ).parent( 'span' ).addClass( 'checked' );
                }
                else {
                    $( this ).attr( 'checked', false ).trigger( 'change' );
                    $( this ).parent( 'span' ).removeClass( 'checked' );
                }
            } );
        } );

        tableWrapper.find( '.dataTables_length select' ).select2();
    }

    return {
        init: function() {
            if( !jQuery().dataTable ) {
                return;
            }
            initTable2();
        }
    };
}();
