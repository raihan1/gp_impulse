<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

$status = array(
    ACTIVE   => array( 'label' => 'Active', 'class' => 'success' ),
    INACTIVE => array( 'label' => 'Inactive', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['Region']['id'] . '">',
        
        $this->Html->link( $d['Region']['region_name'], array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'view', $d['Region']['id'] ) ),
        
        '<span class="label label-sm label-' . $status[ $d['Region']['status'] ]['class'] . '">' . $status[ $d['Region']['status'] ]['label'] . '</span>',
        
        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'view', $d['Region']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View' ) )
        . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'add', $d['Region']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
        . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this region?' ), 'data-id' => $d['Region']['id'] ) ),
    );
}

die( json_encode( $result ) );
?>