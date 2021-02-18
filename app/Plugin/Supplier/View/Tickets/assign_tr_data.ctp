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
        '<strong>' . $d['User']['name'] . '</strong>',
        '<strong>' . $d['Ticket']['supplier_category'] . '</strong>',
        '<strong>' . $d['Ticket']['site'] . '</strong>',
        //'<strong>' . $d['Ticket']['asset_group'] . '</strong>',
        //'<strong>' . $d['Ticket']['asset_number'] . '</strong>',
        '<strong>' . $d['Ticket']['tr_class'] . '</strong>',
        '<strong>' . $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ) . '</strong>',
        $d['Ticket']['lock_status'] == NULL ? $this->Html->link( '<i class="fa fa-lock"></i> Lock', 'javascript:;', array( 'escape' => FALSE, 'class' => 'btn btn-xs red lock', 'title' => 'Lock', 'data-msg' => __( 'Are you sure you want to lock this ticket?' ), 'data-id' => $d['Ticket']['id'] ) ) : '',
    );
}

die( json_encode( $result ) );
?>