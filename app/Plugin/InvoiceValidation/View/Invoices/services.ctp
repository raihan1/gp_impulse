<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Service Details</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>TR ID</th>
                            <th>Item</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Total Without VAT</th>
                            <th class="text-right">Vat Rate</th>
                            <th class="text-right">Vat Amount</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $vat_total = 0;
                        $total_with_vat = 0;
                        foreach( $data as $d ) {
                            $total += $d['TrService']['total'];
                            $vat_total += $d['TrService']['vat_total'];
                            $total_with_vat += $d['TrService']['total_with_vat'];
                            ?>
                            <tr>
                                <td><?php echo $d['TrService']['ticket_id']; ?></td>
                                <td><?php echo $d['TrService']['service_desc']; ?></td>
                                <td class="text-right"><?php echo number_format( $d['TrService']['unit_price'], 4 ); ?></td>
                                <td class="text-right"><?php echo $d['TrService']['quantity']; ?></td>
                                <td class="text-right"><?php echo number_format( $d['TrService']['total'], 4 ); ?></td>
                                <td class="text-right"><?php echo $d['TrService']['vat']; ?>%</td>
                                <td class="text-right"><?php echo number_format( $d['TrService']['vat_total'], 4 ); ?></td>
                                <td class="text-right"><?php echo number_format( $d['TrService']['total_with_vat'], 4 ); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th colspan="4" class="text-left">Grand Total</th>
                            <th class="text-right"><?php echo number_format( $total, 4 ); ?></th>
                            <th>&nbsp;</th>
                            <th class="text-right"><?php echo number_format( $vat_total, 4 ); ?></th>
                            <th class="text-right"><?php echo number_format( $total_with_vat, 4 ); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>