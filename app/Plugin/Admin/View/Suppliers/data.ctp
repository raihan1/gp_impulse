<?php

$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['Supplier']['id'] . '">',
        '<strong>' . $d['Supplier']['code'] . '</strong>',
        '<strong>' . $d['Supplier']['name'] . '</strong>',
        '<strong>' . $d['Supplier']['email'] . '</strong>',
        '<strong>' . $d['Supplier']['phone'] . '</strong>',
		'<span class="label label-sm label-' . $status[$d['Supplier']['status']]['class'] . '">' . $status[$d['Supplier']['status']]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'view', $d['Supplier']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'suppliers', 'action' => 'add', $d['Supplier']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this supplier?' ), 'data-id' => $d['Supplier']['id'] ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>
