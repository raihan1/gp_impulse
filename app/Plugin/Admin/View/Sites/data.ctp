<?php
$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['Site']['id'] . '">',
        $this->Html->link( $d['SubCenter']['sub_center_name'], array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'view', $d['SubCenter']['id'] ) ),
        $this->Html->link( $d['Site']['site_name'], array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'view', $d['Site']['id'] ) ),
        '<span class="label label-sm label-' . $status[ $d['Site']['status'] ]['class'] . '">' . $status[ $d['Site']['status'] ]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'view', $d['Site']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'add', $d['Site']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this site?' ), 'data-id' => $d['Site']['id'] ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>