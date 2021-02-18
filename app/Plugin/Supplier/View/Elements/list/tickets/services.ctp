<div class="row" style="margin: 0px;">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 20px;">
        <?php if( !empty( $data['TrService'] ) ) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>TR Class</th>
                            <th>Service</th>
                            <th class="text-right">Base Unit Price</th>
                            <th class="text-right">Vat</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Quantity</th>
                            <th class="text-right">Total</th>
                            <th>Delivery Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $data['TrService'] as $trs ) { ?>
                            <tr>
                                <td><?php echo $data['Ticket']['tr_class']; ?></td>
                                <td><?php echo $trs['service']; ?></td>
                                <td class="text-right"><?php echo number_format( $trs['unit_price'], 2 ); ?></td>
                                <td class="text-right"><?php echo $trs['vat']; ?>%</td>
                                <td class="text-right"><?php echo number_format( $trs['unit_price_with_vat'], 4 ); ?></td>
                                <td class="text-right"><?php echo $trs['quantity']; ?></td>
                                <td class="text-right"><?php echo number_format( $trs['total_with_vat'], 4 ); ?></td>
                                <td><?php echo $this->Lookup->showDateTime( $trs['delivery_date'] ); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6" class="text-right"><b>Total</b></td>
                            <td class="text-right"><?php echo number_format( $data['Ticket']['total_with_vat'], 4 ); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>