<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);
//pr($data);
//die();
foreach( $data as $d ) {
    $result['data'][] = array(
        $this->Html->link( $d['TicketArchive']['id'], array( 'plugin' => 'tr_creation', 'controller' => 'ticket_archives', 'action' => 'view', 'ticketId' => $d['TicketArchive']['id'] ) ),
        $d['User']['name'],
        $d['TicketArchive']['supplier_category'],
        $d['TicketArchive']['site'],
        $d['TicketArchive']['asset_group'],
        $d['TicketArchive']['asset_number'],
        $d['TicketArchive']['tr_class'],
        $this->Lookup->showDateTime( $d['TicketArchive']['received_at_supplier'] ),

        $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'ticket_archives', 'action' => 'view', 'ticketId' => $d['TicketArchive']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) )
//        . ( $type == 'assigned' ? $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'tr_creation', 'controller' => 'ticket_archive', 'action' => 'add', 'ticketId' => $d['TicketArchive']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) ) : '' )
//        . ( $type == 'assigned' ? $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this ticket?' ), 'data-id' => $d['TicketArchive']['id'] ) ) : '' ),
    );
}

die( json_encode( $result ) );
?>