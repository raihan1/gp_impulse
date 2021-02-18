<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

foreach( $data as $d ) {
    $result['data'][] = array(
        $this->Html->link( $d['Ticket']['id'], array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ) ),
        $d['Ticket']['supplier'],
        $d['Ticket']['supplier_category'],
        $d['Ticket']['site'],
        //$d['Ticket']['asset_group'],
        //$d['Ticket']['asset_number'],
        $d['Ticket']['tr_class'],
        $d['Ticket']['project'],
        $this->Html->link( '<i class="fa fa-eye"></i> View', array( 'plugin' => 'invoice_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) ),
    );
}

die( json_encode( $result ) );
?>