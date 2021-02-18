<table class="table table-striped table-bordered table-hover" id="<?php echo $tableId; ?>">
    <thead>
        <tr role="row" class="heading">
            <th width="5%">TR ID</th>
            <th>User Name</th>
            <th>Supplier Category</th>
            <th>Site Name</th>
            <th>Asset Group</th>
            <th>Asset Number</th>
            <th>TR Class</th>
            <th>Received at supplier site</th>
            <th width="9%" class="no-sort text-center">Action</th>
        </tr>
        <tr role="row" class="filter">
            <td><input type="text" class="form-control form-filter input-sm" name="id" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="name" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="supplier_category" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="site" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="asset_group" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="asset_number" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="tr_class" /></td>
            <td><input type="text" class="form-control form-filter input-sm datepicker" name="received_at_supplier" /></td>
            <td class="text-center">
                <div class="margin-bottom-5">
                    <button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i></button>
                    <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i></button>
                </div>
            </td>
        </tr>
    </thead>
    <tbody></tbody>
</table>