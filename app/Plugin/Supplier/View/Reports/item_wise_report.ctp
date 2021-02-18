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
    <h1>Item Report</h1>
    
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
            <th>SL</th>
            <th>Item Code</th>
            <th>Item Description</th>
            
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Base Price</th>
            <th>% of VAT</th>
            <th>VAT Amount</th>
            <th>Total (BDT)</th>
        </tr>
        <?php
        $i = 0;
        foreach( $services as $service ) {
            $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $service['service']; ?></td>
                <td><?php echo $service['service_desc']; ?></td>
                
                <td class="text-right"><?php echo number_format( $service['unit_price'], 4 ); ?></td>
                <td class="text-right"><?php echo $service['quantity']; ?></td>
                <td class="text-right"><?php echo number_format( $service['total'], 4 ); ?></td>
                <td class="text-right"><?php echo $service['vat']; ?>%</td>
                <td class="text-right"><?php echo number_format( $service['vat_total'], 4 ); ?></td>
                <td class="text-right"><?php echo number_format( $service['total_with_vat'], 4 ); ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="5" class="text-left">Grand Total</th>
            <th><?php echo number_format( $data['Invoice']['total'], 4 ); ?></th>
            <th>&nbsp;</th>
            <th><?php echo number_format( $data['Invoice']['vat_total'], 4 ); ?></th>
            <th><?php echo number_format( $data['Invoice']['total_with_vat'], 4 ); ?></th>
        </tr>
    </table>
    
    <?php echo date( 'M j, Y g:i A' ); ?>
</div>

<script type="text/javascript">
    function printContent( id ) {
        str    = document.getElementById( id ).innerHTML;
        newwin = window.open( '', 'printwin', 'left=70,top=70,width=500,height=500' );
        newwin.document.write( '<html>\n<head>\n' );
        newwin.document.write( '<title>Item Report</title>\n' );
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