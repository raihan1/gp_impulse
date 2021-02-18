<?php
App::uses( 'InvoiceCreationAppController', 'InvoiceCreation.Controller' );
/**
 * InvoiceReports Controller
 */
class InvoiceReportsController extends InvoiceCreationAppController {
    
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
            'conditions' => array('Invoice.status' => APPROVE ),
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
    private function tr_wise_report( $invoiceId,$export ) {
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
        if ( $export == 'excel' ) {
            App::import('Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ));
            App::import('Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ));

            $objPHPExcel = new PHPExcel();
            $objSheet = $objPHPExcel->getActiveSheet();

            $objSheet->setCellValue('A1', 'TR Wise Report');
            $objSheet->getStyle('A1')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));

            $objSheet->setCellValue('A3', 'Month');
            $objSheet->setCellValue('B3', 'Name of the Vendor');
            $objSheet->setCellValue('C3', 'Office');
            $objSheet->setCellValue('D3', 'Bill Ref. No');
            $objSheet->getStyle('A3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('B3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('C3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('D3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->setCellValue('A4', date('F Y', strtotime("{$data['Invoice']['year']}-{$data['Invoice']['month']}-01 00:00:00")));
            $objSheet->setCellValue('B4', $data[ 'Invoice' ][ 'supplier' ]);
            $objSheet->setCellValue('C4', $data[ 'Invoice' ][ 'sub_center' ]);
            $objSheet->setCellValue('D4', $data[ 'Invoice' ][ 'invoice_id' ]);


            $objSheet->setCellValue('A6', 'TR No');
            $objSheet->setCellValue('B6', 'TR Class');
            $objSheet->setCellValue('C6', 'TR Date');
            $objSheet->setCellValue('D6', 'Site Name');
            $objSheet->setCellValue('E6', 'Asset Group');
            $objSheet->setCellValue('F6', 'Activity Type');
            $objSheet->setCellValue('G6', 'Asset ID');
            $objSheet->setCellValue('H6', 'Proposed Completion Date');
            $objSheet->setCellValue('I6', 'Work Completion Date');
            $objSheet->setCellValue('J6', 'Used Item Code');
            $objSheet->setCellValue('K6', 'Used Item Description');
            $objSheet->setCellValue('L6', 'Unit Price');
            $objSheet->setCellValue('M6', 'Qty');
            $objSheet->setCellValue('N6', 'Total Without VAT');
            $objSheet->setCellValue('O6', 'Applicable VAT');
            $objSheet->setCellValue('P6', 'Amt of VAT');
            $objSheet->setCellValue('Q6', 'Total with VAT');
            $objSheet->setCellValue('R6', 'SLA');
            $objSheet->getStyle('A6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('B6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('C6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('D6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('E6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('F6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('G6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('H6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('I6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('J6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('K6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('L6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('M6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('N6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('O6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('P6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('Q6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('R6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));

            $row = 7;
            foreach( $data['Ticket'] as $tr ) {
                foreach( $tr['TrService'] as $trs ) {
                    $objSheet->getStyle("L{$row}:N{$row}:P{$row}:Q{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $objSheet->setCellValue("A{$row}", $trs['ticket_id']);
                    $objSheet->setCellValue("B{$row}", $tr['tr_class']);
                    $objSheet->setCellValue("C{$row}", $tr['created'] );
                    $objSheet->setCellValue("D{$row}", $tr['site']);
                    $objSheet->setCellValue("E{$row}", $tr['asset_group']);
                    $objSheet->setCellValue("F{$row}", substr( $tr['asset_group'], 0, 2 ));
                    $objSheet->setCellValue("G{$row}", $tr['asset_number']);
                    $objSheet->setCellValue("H{$row}", $tr['complete_date'] );
                    $objSheet->setCellValue("I{$row}", $trs['delivery_date'] );
                    $objSheet->setCellValue("J{$row}", $trs['service']);
                    $objSheet->setCellValue("K{$row}", $trs['service_desc']);
                    $objSheet->setCellValue("L{$row}", $trs['unit_price'] );
                    $objSheet->setCellValue("M{$row}", $trs['quantity']);
                    $objSheet->setCellValue("N{$row}", $trs['total']);
                    $objSheet->setCellValue("O{$row}", $trs['vat']);
                    $objSheet->setCellValue("P{$row}", $trs['vat_total']);
                    $objSheet->setCellValue("Q{$row}", $trs['total_with_vat']);
                    $objSheet->setCellValue("R{$row}", strtotime( $tr['complete_date'] ) >= strtotime( $trs['delivery_date'] ) ? 'Achieved' : 'Not Achieved');
                    $row++;
                }
            }
            $objSheet->mergeCells("A{$row}:B{$row}:C{$row}:D{$row}:E{$row}:F{$row}:G{$row}:H{$row}:I{$row}:J{$row}:K{$row}:L{$row}:M{$row}");

            $objSheet->setCellValue("A{$row}", 'Grand Total');
            $objSheet->setCellValue("N{$row}", number_format( $data['Invoice']['total'], 4 ));
            $objSheet->setCellValue("P{$row}", number_format( $data['Invoice']['vat_total'], 4 ));
            $objSheet->setCellValue("Q{$row}", number_format( $data['Invoice']['total_with_vat'], 4 ));

            $objSheet->getStyle("A{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("N{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("P{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("Q{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));


            $row += 2;
            $objSheet->setCellValue("A{$row}", date('M j, Y g:i A'));

            $objSheet->getColumnDimension('A')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('B')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('C')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('D')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('E')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('F')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('G')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('H')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('I')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('J')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('K')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('L')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('M')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('N')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('O')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('P')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('Q')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('R')->setAutoSize(TRUE);

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . str_replace('/', '_', $data[ 'Invoice' ][ 'invoice_id' ]) . '_tr_wise_report.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        }
        $this->render( 'tr_wise_report' );
    }
    
    /**
     * Show Item Report
     *
     * @param $invoiceId
     *
     * @throws Exception
     */
    private function item_wise_report( $invoiceId,$export ) {
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

        if ( $export == 'excel' ) {
            App::import('Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ));
            App::import('Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ));

            $objPHPExcel = new PHPExcel();
            $objSheet = $objPHPExcel->getActiveSheet();

            $objSheet->setCellValue('A1', 'Item Report');
            $objSheet->getStyle('A1')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));

            $objSheet->setCellValue('A3', 'Month');
            $objSheet->setCellValue('B3', 'Name of the Vendor');
            $objSheet->setCellValue('C3', 'Office');
            $objSheet->setCellValue('D3', 'Bill Ref. No');
            $objSheet->getStyle('A3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('B3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('C3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('D3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->setCellValue('A4', date('F Y', strtotime("{$data['Invoice']['year']}-{$data['Invoice']['month']}-01 00:00:00")));
            $objSheet->setCellValue('B4', $data[ 'Invoice' ][ 'supplier' ]);
            $objSheet->setCellValue('C4', $data[ 'Invoice' ][ 'sub_center' ]);
            $objSheet->setCellValue('D4', $data[ 'Invoice' ][ 'invoice_id' ]);


            $objSheet->setCellValue('A6', 'SL');
            $objSheet->setCellValue('B6', 'Item Code');
            $objSheet->setCellValue('C6', 'Item Description');
            $objSheet->setCellValue('D6', 'Unit Price');
            $objSheet->setCellValue('E6', 'Qty');
            $objSheet->setCellValue('F6', 'Base Price');
            $objSheet->setCellValue('G6', '% of VAT');
            $objSheet->setCellValue('H6', 'VAT Amount');
            $objSheet->setCellValue('I6', 'Total (BDT)');

            $objSheet->getStyle('A6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('B6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('C6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('D6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('E6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('F6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('G6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('H6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('I6')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));


            $row = 7;
            $i =0;
            foreach( $services as $service ) {
                $i++;
                $objSheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $objSheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $objSheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $objSheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $objSheet->setCellValue("A{$row}", $i);
                $objSheet->setCellValue("B{$row}", $service['service']);
                $objSheet->setCellValue("C{$row}", $service['service_desc'] );
                $objSheet->setCellValue("D{$row}", $service['unit_price']);
                $objSheet->setCellValue("E{$row}", $service['quantity']);
                $objSheet->setCellValue("F{$row}", $service['total']);
                $objSheet->setCellValue("G{$row}", $service['vat']."%");
                $objSheet->setCellValue("H{$row}", $service['vat_total'] );
                $objSheet->setCellValue("I{$row}", $service['total_with_vat'] );
                $row++;
            }
            $objSheet->mergeCells("A{$row}:B{$row}:C{$row}:D{$row}:E{$row}");

            $objSheet->setCellValue("A{$row}", 'Grand Total');
            $objSheet->setCellValue("F{$row}", number_format( $data['Invoice']['total'], 4 ));
            $objSheet->setCellValue("H{$row}", number_format( $data['Invoice']['vat_total'], 4 ));
            $objSheet->setCellValue("I{$row}", number_format( $data['Invoice']['total_with_vat'], 4 ));

            $objSheet->getStyle("A{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("F{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("H{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle("I{$row}")->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));


            $row += 2;
            $objSheet->setCellValue("A{$row}", date('M j, Y g:i A'));

            $objSheet->getColumnDimension('A')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('B')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('C')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('D')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('E')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('F')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('G')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('H')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('I')->setAutoSize(TRUE);


            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . str_replace('/', '_', $data[ 'Invoice' ][ 'invoice_id' ]) . '_item_wise_report.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        }
        $this->render( 'item_wise_report' );
    }
    
    /**
     * Show Open TR Report (assigned, locked and pending)
     */
    private function open_tr_report($export) {
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

        if ( $export == 'excel' ) {
            App::import('Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ));
            App::import('Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ));

            $objPHPExcel = new PHPExcel();
            $objSheet = $objPHPExcel->getActiveSheet();

            $objSheet->setCellValue('A1', 'Open TR Report');
            $objSheet->getStyle('A1')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));


            $objSheet->setCellValue('A3', 'TR No');
            $objSheet->setCellValue('B3', 'TR DATE');
            $objSheet->setCellValue('C3', 'OPE REGION');
            $objSheet->setCellValue('D3', 'Site');
            $objSheet->setCellValue('E3', 'OFFICE');
            $objSheet->setCellValue('F3', 'EQUIPMENT');
            $objSheet->setCellValue('G3', 'ASSET GROUP DESCRIPTION');
            $objSheet->setCellValue('H3', 'ASSET NO.');
            $objSheet->setCellValue('I3', 'TR CLASS');
            $objSheet->setCellValue('J3', 'SUPPLIER');
            $objSheet->setCellValue('K3', 'RCV SUPP DATE');
            $objSheet->setCellValue('L3', 'PROPOSED COMPLETION DATE');

            $objSheet->getStyle('A3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('B3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('C3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('D3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('E3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('F3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('G3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('H3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('I3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('J3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('K3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));
            $objSheet->getStyle('L3')->applyFromArray(array( 'font' => array( 'bold' => TRUE ) ));


            $row = 4;
            foreach( $data as $tr ) {
                $objSheet->getStyle("L{$row}:N{$row}:P{$row}:Q{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $objSheet->setCellValue("A{$row}", $tr['Ticket']['id']);
                $objSheet->setCellValue("B{$row}", $tr['Ticket']['created']);
                $objSheet->setCellValue("C{$row}", $tr['Ticket']['region'] );
                $objSheet->setCellValue("D{$row}", $tr['Ticket']['site']);
                $objSheet->setCellValue("E{$row}", $tr['Ticket']['sub_center']);
                $objSheet->setCellValue("F{$row}", $tr['Ticket']['asset_group']);
                $objSheet->setCellValue("G{$row}", constant( $tr['Ticket']['asset_group'] . '_TXT' ));
                $objSheet->setCellValue("H{$row}", $tr['Ticket']['asset_number'] );
                $objSheet->setCellValue("I{$row}", $tr['Ticket']['tr_class'] );
                $objSheet->setCellValue("J{$row}", $tr['Ticket']['supplier']);
                $objSheet->setCellValue("K{$row}", $tr['Ticket']['received_at_supplier']);
                $objSheet->setCellValue("L{$row}", $tr['Ticket']['complete_date'] );
                $row++;
            }


            $row += 2;
            $objSheet->setCellValue("A{$row}", date('M j, Y g:i A'));

            $objSheet->getColumnDimension('A')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('B')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('C')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('D')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('E')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('F')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('G')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('H')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('I')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('J')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('K')->setAutoSize(TRUE);
            $objSheet->getColumnDimension('L')->setAutoSize(TRUE);


            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="open_tr_report.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            exit;
        }
        $this->render( 'open_tr_report' );
    }
}