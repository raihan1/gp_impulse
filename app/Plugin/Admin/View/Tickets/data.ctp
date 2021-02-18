<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

foreach( $data as $d ) {
    $result['data'][] = array(
        $this->Html->link( $d['Ticket']['id'], array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ) ),
        $d['User']['name'],
        $d['Ticket']['supplier_category'],
        $d['Ticket']['site'],
        $d['Ticket']['asset_group'],
        $d['Ticket']['asset_number'],
        $d['Ticket']['tr_class'],
        $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ),

        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) )
        . ( $type == 'assigned'||'locked'||'pending'||'approved'||'rejected' ? $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'admin', 'controller' => 'tickets', 'action' => 'add', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) ) : '' )
        . ( $type == 'assigned'||'locked'||'pending'||'approved'||'rejected' ? $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this ticket?' ), 'data-id' => $d['Ticket']['id'] ) ) : '' ),
    );
}

die( json_encode( $result ) );
?>