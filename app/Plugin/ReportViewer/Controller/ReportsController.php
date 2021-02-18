<?php
App::uses( 'ReportViewerAppController', 'ReportViewer.Controller' );

/**
 * Reports Controller
 */
class ReportsController extends ReportViewerAppController {
    
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
     * Services Report
     */
    public function services() {
        if( !empty( $this->request->query ) ) {
            $this->loadModel( 'Ticket' );
            $data = $this->Ticket->find( 'count', array( 'conditions' => $this->prepareConditions(), 'contain' => FALSE ) );
            $this->set( 'data', $data );
        }
        
        $sites = $this->WarrantyLookup->getSiteList( $this->loginUser['User']['sub_center_id'] );
        $siteList = array();
        foreach( $sites as $id => $name ) {
            $siteList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $assetGroups = $this->WarrantyLookup->getAssetGroupList( array_keys( $sites ), TRUE );
        $assetGroupList = array();
        foreach( $assetGroups as $id => $name ) {
            $assetGroupList[] = array( 'name' => $name, 'value' => $name );
        }
        
        $suppliers = $this->WarrantyLookup->getSupplierList( NULL );
        $supplierList = array();
        foreach( $suppliers as $id => $name ) {
            $supplierList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $this->set( array(
            'search'           => $this->request->query,
            'siteList'         => $siteList,
            'assetGroupList'   => $assetGroupList,
            'supplierList'     => $supplierList,
            'title_for_layout' => 'Tickets Report',
        ) );
    }
    
    /**
     * Tickets Report
     */
    public function tickets() {
        if( !empty( $this->request->query ) ) {
            $this->loadModel( 'Ticket' );
            $data = $this->Ticket->find( 'count', array( 'conditions' => $this->prepareConditions(), 'contain' => FALSE ) );
            $this->set( 'data', $data );
        }
        
        $sites = $this->WarrantyLookup->getSiteList( $this->loginUser['User']['sub_center_id'] );
        $siteList = array();
        foreach( $sites as $id => $name ) {
            $siteList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $suppliers = $this->WarrantyLookup->getSupplierList( NULL );
        $supplierList = array();
        foreach( $suppliers as $id => $name ) {
            $supplierList[] = array( 'name' => $name, 'value' => $name, 'data-id' => $id );
        }
        
        $this->set( array(
            'search'           => $this->request->query,
            'siteList'         => $siteList,
            'supplierList'     => $supplierList,
            'title_for_layout' => 'Tickets Report',
        ) );
    }
    
    /**
     * Populate AssetGroup dropdown via ajax upon Site selection
     */
    public function siteSelected() {
        $this->autoRender = FALSE;
        
        $data['AssetGroup'] = $this->WarrantyLookup->getAssetGroupList( $this->request->data['site_id'] );
        
        die( json_encode( $data ) );
    }
    
    /**
     * Populate AssetNumber and TrClass dropdowns via ajax upon AssetGroup selection
     */
    public function assetGroupSelected() {
        $this->autoRender = FALSE;
        
        $data['AssetNumber'] = $this->WarrantyLookup->getAssetNumberList( $this->request->data['asset_group_id'] );
        $data['TrClass'] = $this->WarrantyLookup->getTrClassList( $this->request->data['asset_group_id'] );
        
        die( json_encode( $data ) );
    }
    
    /**
     * Prepare conditions based on search criteria
     */
    private function prepareConditions() {
        $conditions = array( 'Ticket.sub_center' => $this->loginUser['SubCenter']['sub_center_name'] );
        
        if( !empty( $this->request->query['site'] ) ) {
            $conditions['Ticket.site'] = $this->request->query['site'];
        }
        if( !empty( $this->request->query['asset_group'] ) ) {
            $conditions['Ticket.asset_group'] = $this->request->query['asset_group'];
        }
        if( !empty( $this->request->query['asset_number'] ) ) {
            $conditions['Ticket.asset_number'] = $this->request->query['asset_number'];
        }
        if( !empty( $this->request->query['tr_class'] ) ) {
            $conditions['Ticket.tr_class'] = $this->request->query['tr_class'];
        }
        if( !empty( $this->request->query['supplier'] ) ) {
            $conditions['Ticket.supplier'] = $this->request->query['supplier'];
        }
        if( !empty( $this->request->query['period_from'] ) && !empty( $this->request->query['period_to'] ) ) {
            $conditions['Ticket.received_at_supplier >='] = $this->request->query['period_from'] . ' 00:00:00';
            $conditions['Ticket.received_at_supplier <='] = $this->request->query['period_to'] . ' 23:59:59';
        }
        if( !empty( $this->request->query['status'] ) ) {
            if( $this->request->query['status'] == 1 ) {
                $conditions['Ticket.lock_status'] = NULL;
            }
            else if( $this->request->query['status'] == 2 ) {
                $conditions['Ticket.lock_status'] = LOCK;
                $conditions['Ticket.pending_status'] = NULL;
            }
            else if( $this->request->query['status'] == 3 ) {
                $conditions['Ticket.pending_status'] = PENDING;
                $conditions['Ticket.approval_status'] = NULL;
            }
            else if( $this->request->query['status'] == 4 ) {
                $conditions['Ticket.approval_status'] = APPROVE;
                $conditions['Ticket.is_invoiceable'] = NO;
            }
            else if( $this->request->query['status'] == 5 ) {
                $conditions['Ticket.approval_status'] = DENY;
            }
        }
        
        return $conditions;
    }
    
    /**
     * Download service report as Excel
     *
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function download_services() {
        $this->autoRender = FALSE;
        set_time_limit( 0 );
        ini_set( 'memory_limit', '-1' );
        
        App::import( 'Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ) );
        App::import( 'Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ) );
        
        $objPHPExcel = new PHPExcel();
        $objSheet = $objPHPExcel->getActiveSheet();
        
        //<editor-fold desc="Sheet header" defaultstate="collapsed">
        $this->commonSheetHeader( $objSheet );
        
        $objSheet->setCellValue( 'S1', 'Item Code' );
        $objSheet->setCellValue( 'T1', 'Item Description' );
        $objSheet->setCellValue( 'U1', 'Unit price' );
        $objSheet->setCellValue( 'V1', 'Quantity' );
        $objSheet->setCellValue( 'W1', 'Total (with vat)' );
        $objSheet->setCellValue( 'X1', 'Delivery Date' );
        $objSheet->setCellValue( 'Y1', 'Invoice ID' );
        //</editor-fold>
        
        //<editor-fold desc="Style the header" defaultstate="collapsed">
        $styleHeader = array( 'font' => array( 'bold' => TRUE ) );
        for( $col = 1; $col <= 25; $col++ ) {
            $objSheet->getStyle( $this->getExcelColumnName( $col ) . '1' )->applyFromArray( $styleHeader );
        }
        //</editor-fold>
        
        //<editor-fold desc="Fetch tickets and write into sheet" defaultstate="collapsed">
        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $this->prepareConditions(),
            'contain'    => array(
                'CreatedBy.name',
                'ClosedBy.name',
                'ValidatedBy.name',
                'TrService' => array( 'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ) ),
            ),
            'order'      => array( 'Ticket.id' => 'DESC' ),
        ) );
        if( !empty( $data ) ) {
            $row = 2;
            foreach( $data as $d ) {
                $count = 0;
                if( !empty( $d['TrService'] ) ) {
                    foreach( $d['TrService'] as $trs ) {
                        $this->commonSheetValues( $objSheet, $row, $d );
                        $objSheet->setCellValue( "S{$row}", $trs['service'] );
                        $objSheet->setCellValue( "T{$row}", $trs['service_desc'] );
                        $objSheet->setCellValue( "U{$row}", $trs['unit_price'] );
                        $objSheet->setCellValue( "V{$row}", $trs['quantity'] );
                        $objSheet->setCellValue( "W{$row}", $trs['total_with_vat'] );
                        $objSheet->setCellValue( "X{$row}", $trs['delivery_date'] );
                        $objSheet->setCellValue( "Y{$row}", $d['Ticket']['invoice_id'] );
                        $count++;
                        $row++;
                    }
                }
                if( $count == 0 ) {
                    $this->commonSheetValues( $objSheet, $row, $d );
                    $objSheet->setCellValue( "Y{$row}", $d['Ticket']['invoice_id'] );
                    $row++;
                }
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Resize sheet columns" defaultstate="collapsed">
        for( $col = 1; $col <= 25; $col++ ) {
            $objSheet->getColumnDimension( $this->getExcelColumnName( $col ) )->setAutoSize( TRUE );
        }
        //</editor-fold>
        
        //<editor-fold desc="Download the report" defaultstate="collapsed">
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment;filename="service_report_' . date( 'Y-m-d-h-i-A' ) . '.xlsx"' );
        header( 'Cache-Control: max-age=0' );
        $objWriter->save( 'php://output' );
        exit;
        //</editor-fold>
    }
    
    /**
     * Download ticket report as Excel
     *
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function download_tickets() {
        $this->autoRender = FALSE;
        set_time_limit( 0 );
        ini_set( 'memory_limit', '-1' );
        
        App::import( 'Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ) );
        App::import( 'Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ) );
        
        $objPHPExcel = new PHPExcel();
        $objSheet = $objPHPExcel->getActiveSheet();
        
        //<editor-fold desc="Sheet header" defaultstate="collapsed">
        $this->commonSheetHeader( $objSheet );
        
        for( $col = 19, $line = 1; $col < 88; $col += 7, $line++ ) {
            $column = $col;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', "TR line {$line}" );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Item Code' );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Item Description' );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Unit price' );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Quantity' );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Total (with vat)' );
            $column++;
            $objSheet->setCellValue( $this->getExcelColumnName( $column ) . '1', 'Delivery Date' );
        }
        
        $objSheet->setCellValue( 'CK1', 'Grand Total' );
        $objSheet->setCellValue( 'CL1', 'Invoice ID' );
        //</editor-fold>
        
        //<editor-fold desc="Style the header" defaultstate="collapsed">
        $styleHeader = array( 'font' => array( 'bold' => TRUE ) );
        for( $col = 1; $col <= 90; $col++ ) {
            $objSheet->getStyle( $this->getExcelColumnName( $col ) . '1' )->applyFromArray( $styleHeader );
        }
        //</editor-fold>
        
        //<editor-fold desc="Fetch tickets and write into sheet" defaultstate="collapsed">
        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $this->prepareConditions(),
            'contain'    => array(
                'CreatedBy.name',
                'ClosedBy.name',
                'ValidatedBy.name',
                'TrService' => array( 'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ) ),
            ),
            'order'      => array( 'Ticket.id' => 'DESC' ),
        ) );
        if( !empty( $data ) ) {
            $row = 2;
            foreach( $data as $d ) {
                $this->commonSheetValues( $objSheet, $row, $d );
                if( !empty( $d['TrService'] ) ) {
                    $col = 18;
                    foreach( $d['TrService'] as $trs ) {
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, '' );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['service'] );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['service_desc'] );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['unit_price'] );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['quantity'] );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['total_with_vat'] );
                        ++$col;
                        $objSheet->setCellValue( $this->getExcelColumnName( $col ) . $row, $trs['delivery_date'] );
                    }
                }
                $objSheet->setCellValue( "CK{$row}", $d['Ticket']['total_with_vat'] );
                $objSheet->setCellValue( "CL{$row}", $d['Ticket']['invoice_id'] );
                $row++;
            }
        }
        //</editor-fold>
        
        //<editor-fold desc="Resize sheet columns">
        for( $col = 1; $col <= 90; $col++ ) {
            $objSheet->getColumnDimension( $this->getExcelColumnName( $col ) )->setAutoSize( TRUE );
        }
        //</editor-fold>
        
        //<editor-fold desc="Download the report" defaultstate="collapsed">
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment;filename="ticket_report_' . date( 'Y-m-d-h-i-A' ) . '.xlsx"' );
        header( 'Cache-Control: max-age=0' );
        $objWriter->save( 'php://output' );
        exit;
        //</editor-fold>
    }
    
    /**
     * Put common header names into the sheet
     *
     * @param $objSheet
     */
    private function commonSheetHeader( &$objSheet ) {
        $objSheet->setCellValue( 'A1', 'TR Number' );
        $objSheet->setCellValue( 'B1', 'TR Status' );
        $objSheet->setCellValue( 'C1', 'Category' );
        $objSheet->setCellValue( 'D1', 'Asset Group' );
        $objSheet->setCellValue( 'E1', 'Asset Number' );
        $objSheet->setCellValue( 'F1', 'TR Class' );
        $objSheet->setCellValue( 'G1', 'Site' );
        $objSheet->setCellValue( 'H1', 'SC' );
        $objSheet->setCellValue( 'I1', 'Region' );
        $objSheet->setCellValue( 'J1', 'TR Created by' );
        $objSheet->setCellValue( 'K1', 'TR Closed by' );
        $objSheet->setCellValue( 'L1', 'TR validate by' );
        $objSheet->setCellValue( 'M1', 'TR Creation date' );
        $objSheet->setCellValue( 'N1', 'Recv Supp date' );
        $objSheet->setCellValue( 'O1', 'Proposed Com date' );
        $objSheet->setCellValue( 'P1', 'TR Closing date' );
        $objSheet->setCellValue( 'Q1', 'TR Validation date' );
        $objSheet->setCellValue( 'R1', 'Supplier' );
    }
    
    /**
     * Put common values into the sheet
     *
     * @param $objSheet
     */
    private function commonSheetValues( &$objSheet, &$row, &$data ) {
        $objSheet->setCellValue( "A{$row}", $data['Ticket']['id'] );
        $objSheet->setCellValue( "B{$row}", $this->getTicketStatus( $data ) );
        $objSheet->setCellValue( "C{$row}", substr( $data['Ticket']['tr_class'], 0, 2 ) );
        $objSheet->setCellValue( "D{$row}", $data['Ticket']['asset_group'] );
        $objSheet->setCellValue( "E{$row}", $data['Ticket']['asset_number'] );
        $objSheet->setCellValue( "F{$row}", $data['Ticket']['tr_class'] );
        $objSheet->setCellValue( "G{$row}", $data['Ticket']['site'] );
        $objSheet->setCellValue( "H{$row}", $data['Ticket']['sub_center'] );
        $objSheet->setCellValue( "I{$row}", $data['Ticket']['region'] );
        $objSheet->setCellValue( "J{$row}", $data['CreatedBy']['name'] );
        $objSheet->setCellValue( "K{$row}", $data['ClosedBy']['name'] );
        $objSheet->setCellValue( "L{$row}", $data['ValidatedBy']['name'] );
        $objSheet->setCellValue( "M{$row}", $data['Ticket']['created'] );
        $objSheet->setCellValue( "N{$row}", $data['Ticket']['received_at_supplier'] );
        $objSheet->setCellValue( "O{$row}", $data['Ticket']['complete_date'] );
        $objSheet->setCellValue( "P{$row}", $data['Ticket']['closing_date'] );
        $objSheet->setCellValue( "Q{$row}", $data['Ticket']['validation_date'] );
        $objSheet->setCellValue( "R{$row}", $data['Ticket']['supplier'] );
    }
    
    /**
     * Get ticket status
     *
     * @param array $data
     *
     * @return string
     */
    private function getTicketStatus( &$data ) {
        $status = '';
        if( $data['Ticket']['lock_status'] == NULL ) {
            $status = 'Assigned';
        }
        else if( $data['Ticket']['lock_status'] == LOCK && $data['Ticket']['pending_status'] == NULL ) {
            $status = 'Locked';
        }
        else if( $data['Ticket']['pending_status'] == PENDING && $data['Ticket']['approval_status'] == NULL ) {
            $status = 'Pending';
        }
        else if( $data['Ticket']['approval_status'] == APPROVE ) {
            $status = 'Approved';
        }
        else if( $data['Ticket']['approval_status'] == DENY ) {
            $status = 'Rejected';
        }
        
        return $status;
    }
}