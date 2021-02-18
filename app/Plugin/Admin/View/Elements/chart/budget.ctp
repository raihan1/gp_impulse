<div class="col-md-12 reportChart">
    <h3>
        <i class="icon-bar-chart"></i>
        Budget vs Expense
    </h3>
    <div class="dashboard_report">
            <div id="report_1_budget">
                <?php
                echo $this->Form->input('', array(
                    'options'  => $regionList,
                    'empty'    => 'Region',
                    'class'    => 'dashboard_select',
                    'id'       => 'report_1_1',
                    'default'  => $regionDefault
                ) );

                echo $this->Form->input('', array(
                    'options'  => $yearList,
                    'empty'    => 'Year',
                    'class'    => 'dashboard_select',
                    'id'       => 'report_1_2',
                    'default'  => date('Y')
                ) );

                echo $this->Form->input('', array(
                    'options'  => $monthList,
                    'empty'    => 'Month',
                    'class'    => 'dashboard_select',
                    'id'       => 'report_1_3',
                    'default'  => date('n')
                ) );
                ?>
                <a class="dashboard_chart_search" onclick="report_1_budget()"><i class="fa fa-search"></i>Submit</a>
            </div>
        <div class="table-responsive" id="report_1_budget_data" style="min-height: 300px;"></div>
    </div>
</div>