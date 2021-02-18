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

$type = array(
    TR_CREATOR        => array( 'label' => 'TR Creator', 'class' => 'primary' ),
    SECURITY          => array( 'label' => 'TR Creator (SS)', 'class' => 'primary' ),
    TR_VALIDATOR      => array( 'label' => 'TR Validator', 'class' => 'success' ),
    SUPPLIER          => array( 'label' => 'Supplier', 'class' => 'primary' ),
    INVOICE_CREATOR   => array( 'label' => 'Invoice Creator', 'class' => 'success' ),
    INVOICE_VALIDATOR => array( 'label' => 'Invoice Validator', 'class' => 'primary' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['User']['id'] . '">',
        '<strong>' . $d['User']['name'] . '</strong>',
        '<strong>' . $d['User']['phone'] . '</strong>',
        '<strong>' . $d['User']['email'] . '</strong>',
        '<span class="label label-sm label-' . $type[ $d['User']['role'] ]['class'] . '">' . $type[ $d['User']['role'] ]['label'] . '</span>',
        '<span class="label label-sm label-' . $status[ $d['User']['status'] ]['class'] . '">' . $status[ $d['User']['status'] ]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'view', $d['User']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'users', 'action' => 'add', $d['User']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this user?' ), 'data-id' => $d['User']['id'] ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>