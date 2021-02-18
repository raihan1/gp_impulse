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
    <h1>Open TR Report</h1>
    
    <table width="100%" border="1" style="margin-bottom: 10px;">
        <tr>
            <th>TR No.</th>
            <th>TR DATE</th>
            <th>OPE REGION</th>
            <th>SITE</th>
            
            <th>OFFICE</th>
<!--            <th>EQUIPMENT</th>-->
<!--            <th>ASSET GROUP DESCRIPTION</th>-->
<!--            <th>ASSET NO.</th>-->
            
            <th>TR CLASS</th>
            <th>SUPPLIER</th>
            <th>RCV SUPP DATE</th>
            <th>PROPOSED COMPLETION DATE</th>
        </tr>
        <?php foreach( $data as $d ) { ?>
            <tr>
                <td><?php echo $d['Ticket']['id']; ?></td>
                <td><?php echo $this->Lookup->showDateTime( $d['Ticket']['created'] ); ?></td>
                <td><?php echo $d['Ticket']['region']; ?></td>
                <td><?php echo $d['Ticket']['site']; ?></td>
                
                <td><?php echo $d['Ticket']['sub_center']; ?></td>
<!--                <td>--><?php //echo $d['Ticket']['asset_group']; ?><!--</td>-->
<!--                <td>--><?php //echo constant( $d['Ticket']['asset_group'] . '_TXT' ); ?><!--</td>-->
<!--                <td>--><?php //echo $d['Ticket']['asset_number']; ?><!--</td>-->
                
                <td><?php echo $d['Ticket']['tr_class']; ?></td>
                <td><?php echo $d['Ticket']['supplier']; ?></td>
                <td><?php echo $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ); ?></td>
                <td><?php echo $this->Lookup->showDateTime( $d['Ticket']['complete_date'] ); ?></td>
            </tr>
        <?php } ?>
    </table>
    
    <?php echo date( 'M j, Y g:i A' ); ?>
</div>

<script type="text/javascript">
    function printContent( id ) {
        str    = document.getElementById( id ).innerHTML;
        newwin = window.open( '', 'printwin', 'left=70,top=70,width=500,height=500' );
        newwin.document.write( '<html>\n<head>\n' );
        newwin.document.write( '<title>Open TR Report</title>\n' );
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