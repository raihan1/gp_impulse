<style type="text/css">
    * {
        font-family: Arial;
        font-size: 12px;
    }
    
    h1 {
        font-size: 18px;
    }
    
    .text-left {
        text-align: left;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-right {
        text-align: right;
    }
    
    .export {
        float: right;
        font-size: 12px;
        color: #000;
        margin-left: 5px;
    }
</style>

<?php
if( empty( $fileName ) ) {
    echo $this->Html->link( 'PDF', 'javascript:;', array( 'class' => 'export', 'onClick' => 'printContent( \'Report\' )' ) );
    //echo $this->Html->link( 'Excel', Router::url( NULL, TRUE ) . '/excel', array( 'class' => 'export' ) );
}
?>
<div id="Report">
    <h1>TR Report</h1>
    
    <table width="100%" border="1" style="margin-bottom: 20px;">
        <tr>
            <th width="20%">Month</th>
            <th width="30%">Name of the Vendor</th>
            <th>Office</th>
            <th width="30%">Bill Ref. No</th>
        </tr>
        <tr>
            <td><?php echo date( 'F Y', strtotime( "{$data['Invoice']['year']}-{$data['Invoice']['month']}-01 00:00:00" ) ); ?></td>
            <td><?php echo $data['Invoice']['supplier']; ?></td>
            <td><?php echo $data['Invoice']['sub_center']; ?></td>
            <td><?php echo $data['Invoice']['invoice_id']; ?></td>
        </tr>
    </table>
    
    <table width="100%" border="1" style="margin-bottom: 10px;">
        <tr>
            <th>TR No</th>
            <th>TR Class</th>
            <th>TR Date</th>
            <th>Site Name</th>
<!--            <th>Asset Group</th>-->
<!--            <th>Activity Type</th>-->
<!--            <th>Asset ID</th>-->
            <th>Proposed Completion Date</th>
            <th>Work Completion Date</th>
            <th>Used Item Code</th>
            <th>Used Item Description</th>
            
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Total Without VAT</th>
            <th>Applicable VAT</th>
            <th>Amt of VAT</th>
            <th>Total with VAT</th>
            
            <th>SLA</th>
        </tr>
        <?php
        foreach( $data['Ticket'] as $tr ) {
            foreach( $tr['TrService'] as $trs ) {
                ?>
                <tr>
                    <td><?php echo $trs['ticket_id']; ?></td>
                    <td><?php echo $tr['tr_class']; ?></td>
                    <td><?php echo $this->Lookup->showDateTime( $tr['created'] ); ?></td>
                    <td><?php echo $tr['site']; ?></td>
<!--                    <td>--><?php //echo $tr['asset_group']; ?><!--</td>-->
<!--                    <td>--><?php //echo substr( $tr['asset_group'], 0, 2 ); ?><!--</td>-->
<!--                    <td>--><?php //echo $tr['asset_number']; ?><!--</td>-->
                    <td><?php echo $this->Lookup->showDateTime( $tr['complete_date'] ); ?></td>
                    <td><?php echo $this->Lookup->showDateTime( $trs['delivery_date'] ); ?></td>
                    <td><?php echo $trs['service']; ?></td>
                    <td><?php echo $trs['service_desc']; ?></td>
                    
                    <td class="text-right"><?php echo number_format( $trs['unit_price'], 4 ); ?></td>
                    <td class="text-right"><?php echo $trs['quantity']; ?></td>
                    <td class="text-right"><?php echo number_format( $trs['total'], 4 ); ?></td>
                    <td class="text-right"><?php echo $trs['vat']; ?>%</td>
                    <td class="text-right"><?php echo number_format( $trs['vat_total'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $trs['total_with_vat'], 4 ); ?></td>
                    
                    <td><?php echo strtotime( $tr['complete_date'] ) >= strtotime( $trs['delivery_date'] ) ? 'Achieved' : 'Not Achieved'; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        <tr>
            <th colspan="11" class="text-left">Grand Total</th>
            <th class="text-right"><?php echo number_format( $data['Invoice']['total'], 4 ); ?></th>
            <th>&nbsp;</th>
            <th class="text-right"><?php echo number_format( $data['Invoice']['vat_total'], 4 ); ?></th>
            <th class="text-right"><?php echo number_format( $data['Invoice']['total_with_vat'], 4 ); ?></th>
            <th>&nbsp;</th>
        </tr>
    </table>
    
    <?php echo date( 'M j, Y g:i A' ); ?>
</div>

<script type="text/javascript">
    function printContent( id ) {
        str    = document.getElementById( id ).innerHTML;
        newwin = window.open( '', 'printwin', 'left=70,top=70,width=500,height=500' );
        newwin.document.write( '<HTML>\n<HEAD>\n' );
        newwin.document.write( '<TITLE>TR Report</TITLE>\n' );
        newwin.document.write( '<script>\n' );
        newwin.document.write( 'function chkstate(){\n' );
        newwin.document.write( 'if(document.readyState=="complete"){\n' );
        newwin.document.write( 'window.close()\n' );
        newwin.document.write( '}\n' );
        newwin.document.write( 'else{\n' );
        newwin.document.write( 'setTimeout("chkstate()",2000)\n' );
        newwin.document.write( '}\n' );
        newwin.document.write( '}\n' );
        newwin.document.write( 'function print_win(){\n' );
        newwin.document.write( 'window.print();\n' );
        newwin.document.write( 'chkstate();\n' );
        newwin.document.write( '}\n' );
        newwin.document.write( '<\/script>\n' );
        newwin.document.write( '<style type="text/css">* { font-family: Arial; font-size: 12px; } h1 { font-size: 18px; } .text-left { text-align: left; } .text-right { text-align: right; }</style>\n' );
        newwin.document.write( '</HEAD>\n' );
        newwin.document.write( '<BODY onload="print_win()">\n' );
        newwin.document.write( str );
        newwin.document.write( '</BODY>\n' );
        newwin.document.write( '</HTML>\n' );
        newwin.document.close();
        return false;
    }
</script>