<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
    'subTotal'        => isset( $subTotal ) ? $subTotal : 0,
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<input type="checkbox" name="id[]" value="' . $d['Ticket']['id'] . '">',
        '<strong>' . $d['Ticket']['id'] . '</strong>',
        '<strong>' . $d['Ticket']['supplier'] . '</strong>',
        '<strong>' . $d['Ticket']['site'] . '</strong>',
        '<strong>' . $d['Ticket']['asset_group'] . '</strong>',
        '<strong>' . $d['Ticket']['tr_class'] . '</strong>',
        '<strong>' . $this->Lookup->showDateTime( $d['Ticket']['received_at_supplier'] ) . '</strong>',
        '<strong>' . ( $d['Ticket']['is_invoiceable'] == YES ? 'Yes' : 'No' ) . '</strong>',
        '&nbsp;',
    );
}

die( json_encode( $result ) );
?>