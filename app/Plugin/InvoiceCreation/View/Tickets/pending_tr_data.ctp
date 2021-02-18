<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<strong>' . $d['Ticket']['id'] . '</strong>',
        '<strong>' . $d['Ticket']['supplier'] . '</strong>',
        '<strong>' . $d['Ticket']['supplier_category'] . '</strong>',
        '<strong>' . $d['Ticket']['site'] . '</strong>',
        //'<strong>' . $d['Ticket']['asset_group'] . '</strong>',
        //'<strong>' . $d['Ticket']['asset_number'] . '</strong>',
        '<strong>' . $d['Ticket']['tr_class'] . '</strong>',
        '<strong>' . $d['Ticket']['project'] . '</strong>',
        $this->Html->link( '<i class="fa fa-eye"></i> View', array( 'plugin' => 'invoice_creation', 'controller' => 'tickets', 'action' => 'view', $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) ),
    );
}

die( json_encode( $result ) );
?>