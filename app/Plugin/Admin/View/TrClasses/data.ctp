<?php
$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['TrClass']['id'] . '">',
//        $this->Html->link( $d['AssetGroup']['asset_group_name'], array( 'plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'view', $d['AssetGroup']['id'] ) ),
        $this->Html->link( $d['TrClass']['tr_class_name'], array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'view', $d['TrClass']['id'] ) ),
        '<strong>' . $d['TrClass']['no_of_days'] . '</strong>',
        '<span class="label label-sm label-' . $status[ $d['TrClass']['status'] ]['class'] . '">' . $status[ $d['TrClass']['status'] ]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'view', $d['TrClass']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'tr_classes', 'action' => 'add', $d['TrClass']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this tr class?' ), 'data-id' => $d['TrClass']['id'] ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>