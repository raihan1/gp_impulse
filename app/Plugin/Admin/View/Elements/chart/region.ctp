<div class="col-md-12 reportChart">
    <h3>
        <i class="icon-bar-chart"></i>
        Expense
    </h3>
    <div class="box dashboard_report">
        <div id="report_2_expense">
            <?php
            echo $this->Form->input('', array(
                'options'  => $regionList,
                'empty'    => 'Region',
                'class'    => 'dashboard_select',
                'id'       => 'report_2_1',
                'default'  => $regionDefault
            ) );

            echo $this->Form->input('', array(
                'options'  => $yearList,
                'empty'    => 'Year',
                'class'    => 'dashboard_select',
                'id'       => 'report_2_2',
                'default'  => date('Y')
            ) );

            echo $this->Form->input('', array(
                'options'  => $monthList,
                'empty'    => 'Month',
                'class'    => 'dashboard_select',
                'id'       => 'report_2_3',
                'default'  => date('n')
            ) );
            ?>
            <a class="dashboard_chart_search" onclick="report_2_expense()"><i class="fa fa-search"></i>Submit</a>
        </div>
        <div class="table-responsive" id="report_2_expense_data" style="min-height: 300px;"></div>
    </div>
</div>