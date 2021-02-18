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
    echo $this->Html->link( 'Excel', Router::url( NULL, TRUE ) . '/excel', array( 'class' => 'export' ) );
}
?>
<div id="Report">
    <h1>Invoice Summary</h1>
    
    <table width="100%" border="1" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <th width="20%">Month</th>
            <th width="30%">Name of the Vendor</th>
            <th>Office</th>
            <th width="30%">Bill Ref. No</th>
        </tr>
        <tr>
            <td><?php echo date( 'F Y', strtotime( "{$data['InvoiceArchive']['year']}-{$data['InvoiceArchive']['month']}-01 00:00:00" ) ); ?></td>
            <td><?php echo $data['InvoiceArchive']['supplier']; ?></td>
            <td><?php echo $data['InvoiceArchive']['sub_center']; ?></td>
            <td><?php echo $data['InvoiceArchive']['invoice_id']; ?></td>
        </tr>
    </table>
    
    <table width="100%" border="1" style="margin-bottom: 20px;">
        <tr>
            <th width="50%">Activity Type</th>
            <th>Amount without VAT</th>
        </tr>
        <?php foreach( $mainTypes as $name => $value ) { ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td class="text-right"><?php echo number_format( $value, 4 ); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th class="text-left">Sub Total</th>
            <th class="text-right"><?php echo number_format( $data['InvoiceArchive']['total'], 4 ); ?></th>
        </tr>
    </table>
    
    <table width="100%" border="1" style="margin-bottom: 10px;">
        <tr>
            <th width="50%">VAT</th>
            <th>VAT Amount</th>
        </tr>
        <?php foreach( $vats as $id => $value ) { ?>
            <tr>
                <td><?php echo $id; ?>%</td>
                <td class="text-right"><?php echo number_format( $value, 4 ); ?></td>
            </tr>
        <?php } ?>
        <tr>
            <th class="text-left">Grand Total</th>
            <th class="text-right"><?php echo number_format( $data['InvoiceArchive']['total_with_vat'], 4 ); ?></th>
        </tr>
    </table>
    
    <?php echo date( 'M j, Y g:i A' ); ?>
</div>

<script type="text/javascript">
    function printContent( id ) {
        str    = document.getElementById( id ).innerHTML;
        newwin = window.open( '', 'printwin', 'left=70,top=70,width=500,height=500' );
        newwin.document.write( '<html>\n<head>\n' );
        newwin.document.write( '<title>Invoice Summary</title>\n' );
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
        newwin.document.write( '</head>\n' );
        newwin.document.write( '<body onload="print_win()">\n' );
        newwin.document.write( str );
        newwin.document.write( '</body>\n' );
        newwin.document.write( '</html>\n' );
        newwin.document.close();
        return false;
    }
</script>