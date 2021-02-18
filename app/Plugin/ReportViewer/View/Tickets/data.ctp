<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
    'subTotal'        => isset( $subTotal ) ? number_format( $subTotal, 4 ) : 0,
);

foreach( $data as $d ) {
    if( $type == 'assigned' ) {
        $result['data'][] = array(
            $this->Html->link( $d['Ticket']['id'], array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ) ),
            $d['User']['name'],
            $d['Ticket']['supplier_category'],
            $d['Ticket']['site'],
            $d['Ticket']['asset_group'],
            $d['Ticket']['asset_number'],
            $d['Ticket']['tr_class'],
            $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ),
            $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) )
            . $this->Html->link( '<i class="fa fa-edit"></i>', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'add', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) )
            . $this->Html->link( '<i class="fa fa-trash-o"></i>', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red delete', 'title' => 'Delete', 'data-msg' => __( 'Are you sure you want to delete this ticket?' ), 'data-id' => $d['Ticket']['id'] ) ),
        );
    }
    else if( $type == 'approved' ) {
        $result['data'][] = array(
            '<input type="checkbox" name="id[]" value="' . $d['Ticket']['id'] . '">',
            $this->Html->link( $d['Ticket']['id'], array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ) ),
            $d['Ticket']['supplier'],
            $d['Ticket']['site'],
            $d['Ticket']['asset_group'],
            $d['Ticket']['tr_class'],
            $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ),
            number_format( $d['Ticket']['total_with_vat'], 4 ),
            $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) ),
        );
    }
    else {
        $result['data'][] = array(
            $this->Html->link( $d['Ticket']['id'], array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ) ),
            $d['User']['name'],
            $d['Ticket']['supplier_category'],
            $d['Ticket']['site'],
            $d['Ticket']['asset_group'],
            $d['Ticket']['asset_number'],
            $d['Ticket']['tr_class'],
            $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ),
            $this->Html->link( '<i class="fa fa-eye"></i>', array( 'plugin' => 'tr_validation', 'controller' => 'tickets', 'action' => 'view', 'ticketId' => $d['Ticket']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs green', 'title' => 'View Detail' ) ),
        );
    }
}

die( json_encode( $result ) );
?>