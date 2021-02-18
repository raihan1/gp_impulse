<?php
$result['data'] = array();

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['AssetNumber']['id'] . '">',
        $this->Html->link( $d['AssetGroup']['Site']['site_name'], array( 'plugin' => 'admin', 'controller' => 'sites', 'action' => 'view', $d['AssetGroup']['Site']['id'] ) ),
        $this->Html->link( $d['AssetGroup']['asset_group_name'], array( 'plugin' => 'admin', 'controller' => 'asset_groups', 'action' => 'view', $d['AssetGroup']['id'] ) ),
        $this->Html->link( $d['AssetNumber']['asset_number'], array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'view', $d['AssetNumber']['id'] ) ),
        '<span class="label label-sm label-' . $status[ $d['AssetNumber']['status'] ]['class'] . '">' . $status[ $d['AssetNumber']['status'] ]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'view', $d['AssetNumber']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'asset_numbers', 'action' => 'add', $d['AssetNumber']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this asset number?' ), 'data-id' => $d['AssetNumber']['id'] ) ),
    );
}

$result['draw'] = intval( $this->request->data['draw'] );
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die( json_encode( $result ) );
?>