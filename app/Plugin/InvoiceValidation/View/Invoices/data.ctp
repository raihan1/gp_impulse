<?php
$result = array(
    'data'            => array(),
    'draw'            => intval( $this->request->data['draw'] ),
    'recordsTotal'    => $total,
    'recordsFiltered' => $total,
);

$status = array(
    NULL => array( 'label' => 'Pending', 'class' => 'info' ),
    1    => array( 'label' => 'Approved', 'class' => 'success' ),
    0    => array( 'label' => 'Rejected', 'class' => 'danger' ),
);

foreach( $data as $d ) {
    $result['data'][] = array(
        $this->Html->link( $d['Invoice']['invoice_id'], array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'view', 'invoiceId' => $d['Invoice']['id'] ) ),
        $d['Invoice']['supplier'],
        number_format( $d['Invoice']['total_with_vat'], 4 ),
        $this->Lookup->showDateTime( $d['Invoice']['created'] ),
        '<span class="label label-sm label-' . $status[ $d['Invoice']['status'] ]['class'] . '">' . $status[ $d['Invoice']['status'] ]['label'] . '</span>',
        $this->Html->link( '<i class="fa fa-eye"></i> View', array( 'plugin' => 'invoice_validation', 'controller' => 'invoices', 'action' => 'view', 'invoiceId' => $d['Invoice']['id'] ), array( 'escape' => FALSE, 'class' => 'btn btn-xs blue', 'title' => 'View' ) ),
    );
}

die( json_encode( $result ) );
?>