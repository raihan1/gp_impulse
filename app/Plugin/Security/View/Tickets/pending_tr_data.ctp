<?php
$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<strong>' . $d['Ticket']['id'] . '</strong>',
        '<strong>' . $d['Supplier']['name'] . '</strong>',
        '<strong>' . $d['SupplierCategory']['category_name'] . '</strong>',
        '<strong>' . $d['Site']['site_name'] . '</strong>',
        '<strong>' . $d['AssetGroup']['asset_group_name'] . '</strong>',
        '<strong>' . $d['AssetNumber']['asset_number'] . '</strong>',
        '<strong>' . $d['TrClass']['tr_class_name'] . '</strong>',
        '<strong>' . $d['Project']['project_name'] . '</strong>',
        $this->Html->link( '<i class="fa fa-eye"></i> View', array( 'plugin' => 'security', 'controller' => 'tickets', 'action' => 'view', $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>