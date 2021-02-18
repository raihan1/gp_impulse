<?php if( empty( $subCenterBudget ) ) { ?>
    <div class="portlet box yellow">
        <div class="portlet-title" data-toggle="collapse" data-target="#collapseBudget" aria-expanded="true" aria-controls="collapseBudget">
            <div class="caption">Subcenter Budget (<?php echo $month; ?> Month) <i class="fa fa-plus"></i></div>
        </div>
        <div class="portlet-body collapse" id="collapseBudget">
            No budget found.
        </div>
    </div>
    <?php
}
else {
    $total = $subCenterBudget['SubCenterBudget']['AC_initial_budget'] + $subCenterBudget['SubCenterBudget']['AC_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['CW_initial_budget'] + $subCenterBudget['SubCenterBudget']['CW_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['DV_initial_budget'] + $subCenterBudget['SubCenterBudget']['DV_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['EB_initial_budget'] + $subCenterBudget['SubCenterBudget']['EB_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['FM_initial_budget'] + $subCenterBudget['SubCenterBudget']['FM_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['GN_initial_budget'] + $subCenterBudget['SubCenterBudget']['GN_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['PG_initial_budget'] + $subCenterBudget['SubCenterBudget']['PG_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['RF_initial_budget'] + $subCenterBudget['SubCenterBudget']['RF_forwarded_budget']
        + $subCenterBudget['SubCenterBudget']['SS_initial_budget'] + $subCenterBudget['SubCenterBudget']['SS_forwarded_budget'];
    ?>
    <div class="portlet box yellow">
        <div class="portlet-title" data-toggle="collapse" data-target="#collapseBudget" aria-expanded="true" aria-controls="collapseBudget">
            <div class="caption">Subcenter Budget (<?php echo $month; ?> Month) <i class="fa fa-plus"></i></div>
            <div class="tools">Total Budget: <?php echo number_format( $total, 4 ); ?></div>
        </div>
        <div class="portlet-body collapse" id="collapseBudget">
            <table border="0" class="table table-bordered" width="100%">
                <tr style="background-color: #D6DFFF;">
                    <th>Item</th>
                    <th class="text-right">AC</th>
                    <th class="text-right">CW</th>
                    <th class="text-right">DV</th>
                    <th class="text-right">EB</th>
                    <th class="text-right">FM</th>
                    <th class="text-right">GN</th>
                    <th class="text-right">PG</th>
                    <th class="text-right">RF</th>
                    <th class="text-right">SS</th>
                </tr>
                <tr>
                    <th>Budget</th>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['AC_initial_budget'] + $subCenterBudget['SubCenterBudget']['AC_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['CW_initial_budget'] + $subCenterBudget['SubCenterBudget']['CW_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['DV_initial_budget'] + $subCenterBudget['SubCenterBudget']['DV_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['EB_initial_budget'] + $subCenterBudget['SubCenterBudget']['EB_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['FM_initial_budget'] + $subCenterBudget['SubCenterBudget']['FM_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['GN_initial_budget'] + $subCenterBudget['SubCenterBudget']['GN_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['PG_initial_budget'] + $subCenterBudget['SubCenterBudget']['PG_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['RF_initial_budget'] + $subCenterBudget['SubCenterBudget']['RF_forwarded_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['SS_initial_budget'] + $subCenterBudget['SubCenterBudget']['SS_forwarded_budget'], 4 ); ?></td>
                </tr>
                <tr>
                    <th>Consumed</th>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['AC_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['CW_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['DV_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['EB_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['FM_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['GN_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['PG_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['RF_consumed_budget'], 4 ); ?></td>
                    <td class="text-right"><?php echo number_format( $subCenterBudget['SubCenterBudget']['SS_consumed_budget'], 4 ); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
?>