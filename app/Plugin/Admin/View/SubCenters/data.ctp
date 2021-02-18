<?php

$result['data'] = array();

$action = array(
    YES => array('label' => 'Block TR Creation', 'class' => 'danger'),
    NO => array('label' => 'Warning', 'class' => 'warning'),
);

$status = array(
	ACTIVE => array('label' => 'Active', 'class' => 'success'),
	INACTIVE => array('label' => 'Inactive', 'class' => 'danger'),
);

foreach ($data as $d) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['SubCenter']['id'] . '">',
		$this->Html->link( $d['Region']['region_name'], array( 'plugin' => 'admin', 'controller' => 'regions', 'action' => 'view', $d['Region']['id'] ) ),
		$this->Html->link( $d['SubCenter']['sub_center_name'], array( 'plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'view', $d['SubCenter']['id'] ) ),
		'<span class="label label-sm label-' . $action[!empty($d['SubCenter']['eighty_percent_action']) ? $d['SubCenter']['eighty_percent_action'] : 0]['class'] . '">' . $action[!empty($d['SubCenter']['eighty_percent_action']) ? $d['SubCenter']['eighty_percent_action'] : 0]['label'] . '</span>',
		'<span class="label label-sm label-' . $action[!empty($d['SubCenter']['ninety_percent_action']) ? $d['SubCenter']['ninety_percent_action'] : 0]['class'] . '">' . $action[!empty($d['SubCenter']['ninety_percent_action']) ? $d['SubCenter']['ninety_percent_action'] : 0]['label'] . '</span>',
		'<span class="label label-sm label-' . $action[!empty($d['SubCenter']['hundred_percent_action'])? $d['SubCenter']['hundred_percent_action']: 0]['class'] . '">' . $action[!empty($d['SubCenter']['hundred_percent_action'])? $d['SubCenter']['hundred_percent_action']: 0]['label'] . '</span>',
		'<span class="label label-sm label-' . $status[$d['SubCenter']['status']]['class'] . '">' . $status[$d['SubCenter']['status']]['label'] . '</span>',
        $this->Html->link('<i class="fa fa-eye"></i>', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'view', $d['SubCenter']['id']), array('escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View'))
        . $this->Html->link('<i class="fa fa-edit"></i>', array('plugin' => 'admin', 'controller' => 'sub_centers', 'action' => 'add', $d['SubCenter']['id']), array('escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit'))
        . $this->Html->link('<i class="fa fa-trash-o"></i>', 'javascript:;', array('escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __('Are you sure you want to delete this Office?'), 'data-id' => $d['SubCenter']['id'])),
    );
}

$result['draw'] = intval($this->request->data['draw']);
$result['recordsTotal'] = $total;
$result['recordsFiltered'] = $total;

die(json_encode($result));
?>
