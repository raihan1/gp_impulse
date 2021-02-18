<table class="table table-striped table-bordered table-hover" id="<?php echo $tableId; ?>">
    <thead>
        <tr role="row" class="heading">
            <th width="12%">TR ID</th>
            <th width="10%">Supplier</th>
            <th width="16%">Supplier Category</th>
            <th width="15%">Site</th>
            <!--th width="11%">Asset Group</th>
            <th width="12%">Asset Number</th-->
            <th width="16%">TR Class</th>
            <th width="16%">Project</th>
            <th class="no-sort text-center">Action</th>
        </tr>
        <tr role="row" class="filter">
            <td><input type="text" class="form-control form-filter input-sm" name="id" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="supplier" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="supplier_category" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="site" /></td>
            <!--td><input type="text" class="form-control form-filter input-sm" name="asset_group" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="asset_number" /></td-->
            <td><input type="text" class="form-control form-filter input-sm" name="tr_class" /></td>
            <td><input type="text" class="form-control form-filter input-sm" name="project" /></td>
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