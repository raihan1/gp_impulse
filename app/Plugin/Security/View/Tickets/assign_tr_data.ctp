<?php
$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $action = $this->Html->link( '<i class="fa fa-edit"></i> Edit', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'add', $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-eye"></i> View', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'view', $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i> Delete', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this TR?' ), 'data-id' => $d['Ticket']['id'] ) );

    $result['data'][] = array(
        '<strong>' . $d['Ticket']['id'] . '</strong>',
        '<strong>' . $d['Supplier']['name'] . '</strong>',
        '<strong>' . $d['SupplierCategory']['category_name'] . '</strong>',
        '<strong>' . $d['Site']['site_name'] . '</strong>',
        '<strong>' . $d['AssetGroup']['asset_group_name'] . '</strong>',
        '<strong>' . $d['AssetNumber']['asset_number'] . '</strong>',
        '<strong>' . $d['TrClass']['tr_class_name'] . '</strong>',
        '<strong>' . $d['Project']['project_name'] . '</strong>',
        $action,
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>