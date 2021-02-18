<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <?= $this->element('chart/budget'); ?>
                    <?php //echo $this->element('chart/region'); ?>
                    <?= $this->element('chart/tickets'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->script( array('backend/googleChartLoader')); ?>
<script type="text/javascript">
    var loader = $('.loader');


    //************************************** Report_1 : Budget vs Expense Report : START ********************************//
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(report_1_budget);
    function report_1_budget(){
        var region = $('#report_1_1').find(':selected').data('id');
        var year   = $('#report_1_2').find(':selected').data('id');
        var month  = $('#report_1_3').find(':selected').data('id');

        if((typeof region === 'undefined') || (region.length === 0)){ region = 0; }
        if((typeof year   === 'undefined') || (year.length   === 0)){ year = '<?= date('Y');?>'; }
        if((typeof month  === 'undefined') || (month.length  === 0)){ month = '<?= date('n');?>'; }

        $.ajax( {
            dataType : 'json',
            type : 'POST',
            evalScripts : true,
            url : '<?php echo Router::url( array( 'controller' => 'users', 'action' => 'dashboard_budget_data' ) ); ?>',
            data : { region:region, year:year, month:month },
            beforeSend : function () {
                $('#report_1_budget_data').html('Loading <?= $this->Html->image('/resource/loading_h.gif' ); ?>');
            },
            success : function(response){
                var data = google.visualization.arrayToDataTable(response);

                var barOptions = {
                    bars: 'vertical',
                    chartArea: {height: 250},
                    legend: {position: 'none'}
                };

                var chart = new google.charts.Bar(document.getElementById('report_1_budget_data'));
                chart.draw(data, barOptions);
            }
        } );
    }
    //************************************** Report_1 : Budget vs Expense Report : END ********************************//


    //************************************** Report_2 : Region Expense Report : START ********************************//
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(report_2_expense);
    function report_2_expense(){
        var region = $('#report_2_1').find(':selected').data('id');
        var year   = $('#report_2_2').find(':selected').data('id');
        var month  = $('#report_2_3').find(':selected').data('id');

        if((typeof region === 'undefined') || (region.length === 0)){ region = 0; }
        if((typeof year   === 'undefined') || (year.length   === 0)){ year = '<?= date('Y');?>'; }
        if((typeof month  === 'undefined') || (month.length  === 0)){ month = '<?= date('n');?>'; }

        $.ajax( {
            dataType : 'json',
            type : 'POST',
            evalScripts : true,
            url : '<?php echo Router::url( array( 'controller' => 'users', 'action' => 'dashboard_expense_data' ) ); ?>',
            data : { region:region, year:year, month:month },
            beforeSend : function () {
                $('#report_2_expense_data').html('Loading <?= $this->Html->image('/resource/loading_h.gif' ); ?>');
            },
            success : function(response){
                var data = google.visualization.arrayToDataTable(response);

                var barOptions = {
                    bars: 'vertical',
                    chartArea: {height: 250},
                    legend: {position: 'none'},
                    colors: ['#db4437']
                };

                var chart = new google.charts.Bar(document.getElementById('report_2_expense_data'));
                chart.draw(data, barOptions);
            }
        } );
    }
    //************************************** Report_2 : Region Expense Report : END ********************************//


    //************************************** Report_3 : Region & SLA wise Ticket Report : START ********************************//
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(report_3_ticket);
    function report_3_ticket(){
        var region = $('#report_3_1').find(':selected').data('id');
        var year   = $('#report_3_2').find(':selected').data('id');
        var month  = $('#report_3_3').find(':selected').data('id');

        if((typeof region === 'undefined') || (region.length === 0)){ region = 0; }
        if((typeof year   === 'undefined') || (year.length   === 0)){ year = '<?= date('Y');?>'; }
        if((typeof month  === 'undefined') || (month.length  === 0)){ month = '<?= date('n');?>'; }

        $.ajax( {
            dataType : 'json',
            type : 'POST',
            evalScripts : true,
            url : '<?php echo Router::url( array( 'controller' => 'users', 'action' => 'dashboard_ticket_data' ) ); ?>',
            data : { region:region, year:year, month:month },
            beforeSend : function () {
                $('#report_3_ticket_data').html('Loading <?= $this->Html->image('/resource/loading_h.gif' ); ?>');
            },
            success : function(response){
                var data = google.visualization.arrayToDataTable(response);

                var barOptions = {
                    bars: 'vertical',
                    chartArea: {height: 250},
                    legend: {position: 'none'},
                    colors: ['#35aa47']
                };

                var chart = new google.charts.Bar(document.getElementById('report_3_ticket_data'));
                chart.draw(data, barOptions);
            }
        } );
    }
    //************************************** Report_3 : Region & SLA wise Ticket Report : END ********************************//
</script>
