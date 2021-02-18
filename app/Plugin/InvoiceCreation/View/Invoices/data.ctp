<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

$status = array(
    NULL    => array( 'label' => 'Pending', 'class' => 'info' ),
    APPROVE => array( 'label' => 'Approved', 'class' => 'success' ),
    DENY    => array( 'label' => 'Rejected', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        '<strong>' . $d['Invoice']['id'] . '</strong>',
        '<strong>' . $d['Invoice']['invoice_id'] . '</strong>',
        '<strong>' . $d['Invoice']['supplier'] . '</strong>',
        '<strong>' . $d['Invoice']['total_with_vat'] . '</strong>',
        '<strong>' . $this->Lookup->showDateTime( $d['Invoice']['created'] ) . '</strong>',
        '<span class="label label-sm label-' . $status[ $d['Invoice']['status'] ]['class'] . '">' . $status[ $d['Invoice']['status'] ]['label'] . '</span>',
        $d['Invoice']['status'] != APPROVE ? $this->Html->link( '<i class="fa fa-edit"></i> Edit', array( 'plugin' => 'invoice_creation', 'action' => 'invoices', 'action' => 'edit', $d['Invoice']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'Edit' ) ) : '',
    );
}

die( json_encode( $result ) );
?>