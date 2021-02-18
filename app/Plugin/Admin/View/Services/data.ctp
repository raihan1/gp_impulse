<?php

$result['data'] = array();

$status = array(
    ACTIVE => array('label' => 'Active', 'class' => 'success'),
    INACTIVE => array('label' => 'Inactive', 'class' => 'danger'),
);

foreach ($data as $d) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['Service']['id'] . '">',
        '<strong>' . $d['Supplier']['name'] . '</strong>',
        '<strong>' . $d['Service']['service_name'] . '</strong>',
        '<strong>' . $d['Service']['service_unit_price'] . '</strong>',
        '<strong>' . $d['Service']['vat'] . '</strong>',
		'<span class="label label-sm label-' . $status[$d['Service']['status']]['class'] . '">' . $status[$d['Service']['status']]['label'] . '</span>',
        $this->Html->link('<i class="fa fa-eye"></i>', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'view', $d['Service']['id']), array('escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View'))
        . $this->Html->link('<i class="fa fa-edit"></i>', array('plugin' => 'admin', 'controller' => 'services', 'action' => 'add', $d['Service']['id']), array('escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit'))
        . $this->Html->link('<i class="fa fa-trash-o"></i>', 'javascript:;', array('escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __('Are you sure you want to delete this item?'), 'data-id' => $d['Service']['id'])),
    );
}

$result['draw'] = intval($this->request->data['draw']);
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die(json_encode($result));
?>
