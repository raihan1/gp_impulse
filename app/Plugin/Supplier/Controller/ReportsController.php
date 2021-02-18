<?php
App::uses( 'SupplierAppController', 'Supplier.Controller' );

/**
 * Reports Controller
 */
class ReportsController extends SupplierAppController {
    
    public $uses = array( 'Ticket', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Service', 'Invoice' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return parent::isAuthorized( $user );
    }
    
    /**
     * Reports
     */
    public function index() {
        $invoiceList = $this->Invoice->find( 'list', array(
            'conditions' => array( 'Invoice.supplier' => $this->loginUser['Supplier']['name'], 'Invoice.status' => APPROVE ),
            'contain'    => FALSE,
            'fields'     => array( 'id', 'invoice_id' ),
        ) );
        $this->set( array(
            'invoiceList'      => $invoiceList,
            'title_for_layout' => 'Reports',
        ) );
    }
    
    /**
     * View a report
     *
     * @param integer|NULL $invoiceId
     * @param integer      $reportType
     * @param string|NULL  $export
     *
     * @throws Exception
     */
    public function view( $invoiceId = NULL, $reportType, $export = NULL ) {
        $this->layout = FALSE;
        
        if( $reportType == 4 ) {
            $this->summary_report( $invoiceId, $export );
        }
        else if( $reportType == 3 ) {
            $this->tr_wise_report( $invoiceId, $export );
        }
        else if( $reportType == 2 ) {
            $this->item_wise_report( $invoiceId, $export );
        }
        else if( $reportType == 1 ) {
            $this->open_tr_report( $export );
        }
        else {
            throw new Exception( 'Invalid Report Type.' );
        }
    }
    
    /**
     * Show Invoice Summary Report
     *
     * @param integer     $invoiceId
     * @param string|NULL $invoiceId
     *
     * @throws Exception
     */
    private function summary_report( $invoiceId, $export ) {
        $data = $this->Invoice->find( 'first', array(
            'conditions' => array( 'Invoice.id' => $invoiceId, 'Invoice.status' => APPROVE ),
            'contain'    => array(
                'Ticket' => array(
                    'TrService' => array(
                        'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO, 'TrService.warranty_status' => NO ),
                    ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new Exception( 'Invalid Invoice ID.' );
        }
        
        //<editor-fold desc="Calculate main-type and vat-rate wise values" defaultstate="collapsed">
        $mainTypes = $vats = array();
        
        foreach( $data['Ticket'] as $tr ) {
            foreach( $tr['TrService'] as $trs ) {
                if( isset( $vats[ $trs['vat'] ] ) ) {
                    $vats[ $trs['vat'] ] += $trs['vat_total'];
                }
                else {
                    $vats[ $trs['vat'] ] = $trs['vat_total'];
                }
            }
            
            $mainType = $this->WarrantyLookup->getMainType( $tr['tr_class'] );
            
            if( isset( $mainTypes[ $mainType . ' - ' . constant( $mainType . '_TXT' ) ] ) ) {
                $mainTypes[ $mainType . ' - ' . constant( $mainType . '_TXT' ) ] += $tr['total'];
            }
            else {
                $mainTypes[ $mainType . ' - ' . constant( $mainType . '_TXT' ) ] = $tr['total'];
            }
        }
        //</editor-fold>
        
        unset( $data['Ticket'] );
        
        $this->set( array(
            'data'      => $data,
            'mainTypes' => $mainTypes,
            'vats'      => $vats,
        ) );
        
        if( $export == 'excel' ) {
            App::import( 'Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ) );
            App::import( 'Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ) );
            
            $objPHPExcel = new PHPExcel();
            $objSheet = $objPHPExcel->getActiveSheet();
            
            $objSheet->setCellValue( 'A1', 'Invoice Summary' );
            $objSheet->getStyle( 'A1' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            
            $objSheet->setCellValue( 'A3', 'Month' );
            $objSheet->setCellValue( 'B3', 'Name of the Vendor' );
            $objSheet->setCellValue( 'C3', 'Office' );
            $objSheet->setCellValue( 'D3', 'Bill Ref. No' );
            $objSheet->getStyle( 'A3' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( 'B3' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( 'C3' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( 'D3' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->setCellValue( 'A4', date( 'F Y', strtotime( "{$data['Invoice']['year']}-{$data['Invoice']['month']}-01 00:00:00" ) ) );
            $objSheet->setCellValue( 'B4', $data['Invoice']['supplier'] );
            $objSheet->setCellValue( 'C4', $data['Invoice']['sub_center'] );
            $objSheet->setCellValue( 'D4', $data['Invoice']['invoice_id'] );
    
            $objSheet->mergeCells( 'A6:B6' );
            $objSheet->mergeCells( 'C6:D6' );
            $objSheet->setCellValue( 'A6', 'Activity Type' );
            $objSheet->setCellValue( 'C6', 'Amount without VAT' );
            $objSheet->getStyle( 'A6' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( 'C6' )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $row = 7;
            foreach( $mainTypes as $name => $value ) {
                $objSheet->mergeCells( "A{$row}:B{$row}" );
                $objSheet->mergeCells( "C{$row}:D{$row}" );
                $objSheet->setCellValue( "A{$row}", $name );
                $objSheet->setCellValue( "C{$row}", $value );
                $row++;
            }
            $objSheet->mergeCells( "A{$row}:B{$row}" );
            $objSheet->mergeCells( "C{$row}:D{$row}" );
            $objSheet->setCellValue( "A{$row}", 'Sub Total' );
            $objSheet->setCellValue( "C{$row}", $data['Invoice']['total'] );
            $objSheet->getStyle( "A{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( "C{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            
            $row += 2;
            $objSheet->mergeCells( "A{$row}:B{$row}" );
            $objSheet->mergeCells( "C{$row}:D{$row}" );
            $objSheet->setCellValue( "A{$row}", 'VAT' );
            $objSheet->setCellValue( "C{$row}", 'VAT Amount' );
            $objSheet->getStyle( "A{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( "C{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $row++;
            foreach( $vats as $id => $value ) {
                $objSheet->mergeCells( "A{$row}:B{$row}" );
                $objSheet->mergeCells( "C{$row}:D{$row}" );
                $objSheet->setCellValue( "A{$row}", $id );
                $objSheet->setCellValue( "C{$row}", $value );
                $row++;
            }
            $objSheet->mergeCells( "A{$row}:B{$row}" );
            $objSheet->mergeCells( "C{$row}:D{$row}" );
            $objSheet->setCellValue( "A{$row}", 'Grand Total' );
            $objSheet->setCellValue( "C{$row}", $data['Invoice']['total_with_vat'] );
            $objSheet->getStyle( "A{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
            $objSheet->getStyle( "C{$row}" )->applyFromArray( array( 'font' => array( 'bold' => TRUE ) ) );
    
            $row += 2;
            $objSheet->setCellValue( "A{$row}", date( 'M j, Y g:i A' ) );
    
            $objSheet->getColumnDimension( 'A' )->setAutoSize( TRUE );
            $objSheet->getColumnDimension( 'B' )->setAutoSize( TRUE );
            $objSheet->getColumnDimension( 'C' )->setAutoSize( TRUE );
            $objSheet->getColumnDimension( 'D' )->setAutoSize( TRUE );
    
            $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
            header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
            header( 'Content-Disposition: attachment;filename="' . str_replace( '/', '_', $data['Invoice']['invoice_id'] ) . '_summary_report.xlsx"' );
            header( 'Cache-Control: max-age=0' );
            $objWriter->save( 'php://output' );
            exit;
        }
        
        $this->render( 'summary_report' );
    }
    
    /**
     * Show TR Report
     *
     * @param $invoiceId
     *
     * @throws Exception
     */
    private function tr_wise_report( $invoiceId ) {
        $data = $this->Invoice->find( 'first', array(
            'conditions' => array( 'Invoice.id' => $invoiceId, 'Invoice.status' => APPROVE ),
            'contain'    => array(
                'Ticket' => array(
                    'TrService' => array(
                        'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                    ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new Exception( 'Invalid Invoice ID.' );
        }
        
        $this->set( 'data', $data );
        $this->render( 'tr_wise_report' );
    }
    
    /**
     * Show Item Report
     *
     * @param $invoiceId
     *
     * @throws Exception
     */
    private function item_wise_report( $invoiceId ) {
        $data = $this->Invoice->find( 'first', array(
            'conditions' => array( 'Invoice.id' => $invoiceId, 'Invoice.status' => APPROVE ),
            'contain'    => array(
                'Ticket' => array(
                    'TrService' => array(
                        'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                    ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new Exception( 'Invalid Invoice ID.' );
        }
        
        //<editor-fold desc="Calculate service wise values" defaultstate="collapsed">
        $services = array();
        foreach( $data['Ticket'] as $tr ) {
            foreach( $tr['TrService'] as $trs ) {
                if( !isset( $services[ $trs['service'] ] ) ) {
                    $services[ $trs['service'] ] = array(
                        'service'        => $trs['service'],
                        'service_desc'   => $trs['service_desc'],
                        'unit_price'     => $trs['unit_price'],
                        'vat'            => $trs['vat'],
                        'quantity'       => $trs['quantity'],
                        'total'          => $trs['total'],
                        'vat_total'      => $trs['vat_total'],
                        'total_with_vat' => $trs['total_with_vat'],
                    );
                }
                else {
                    $services[ $trs['service'] ]['quantity'] += $trs['quantity'];
                    $services[ $trs['service'] ]['total'] += $trs['total'];
                    $services[ $trs['service'] ]['vat_total'] += $trs['vat_total'];
                    $services[ $trs['service'] ]['total_with_vat'] += $trs['total_with_vat'];
                }
            }
        }
        //</editor-fold>
        
        unset( $data['Ticket'] );
        
        $this->set( array(
            'services' => $services,
            'data'     => $data,
        ) );
        $this->render( 'item_wise_report' );
    }
    
    /**
     * Show Open TR Report (assigned, locked and pending)
     */
    private function open_tr_report() {
        $data = $this->Ticket->find( 'all', array(
            'conditions' => array(
                'Ticket.supplier' => $this->loginUser['Supplier']['name'],
                'OR'              => array(
                    'Ticket.lock_status' => NULL,
                    array(
                        'Ticket.lock_status'    => LOCK,
                        'Ticket.pending_status' => NULL,
                    ),
                    array(
                        'Ticket.pending_status'  => PENDING,
                        'Ticket.approval_status' => NULL,
                    ),
                ),
            ),
            'contain'    => FALSE,
        ) );
        
        $this->set( 'data', $data );
        $this->render( 'open_tr_report' );
    }
}