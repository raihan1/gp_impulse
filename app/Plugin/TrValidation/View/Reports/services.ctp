<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <?php echo $this->Html->link( 'Dashboard', array( 'plugin' => 'tr_validation', 'controller' => 'users', 'action' => 'dashboard' ) ); ?>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <i class="fa fa-bar-chart"></i>
                    <span>Service Report</span>
                </li>
            </ul>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->element( 'form/tickets/search', array(), array( 'plugin' => 'tr_validation' ) ); ?>
            </div>
        </div>
    
        <?php if( !empty( $search ) ) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-search"></i> Result
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if( empty( $data ) ) {
                                        echo 'No result found. Try searching again.';
                                    }
                                    else {
                                        echo "Total {$data} tickets found.";
                                        ?>
                                        <div class="btn-group pull-right">
                                            <?php echo $this->Html->link( 'Export to Excel', array( 'plugin' => 'tr_validation', 'controller' => 'reports', 'action' => 'download_services', '?' => http_build_query( $_REQUEST ) ) ); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>