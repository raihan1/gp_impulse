<?php
App::uses( 'SecurityAppController', 'Security.Controller' );

/**
 * Reports Controller
 */
class ReportsController extends SecurityAppController {

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
     * Tickets Report
     */
    public function tickets() {
        if( !empty( $this->request->query ) ) {
            /* Prepare conditions: START */
            $conditions = array( 'TrClass.tr_class_name LIKE' => 'SS%' );
            $conditions = array(  );

            if( !empty( $this->request->query['region_id'] ) ) {
                $conditions['Ticket.region_id'] = $this->request->query['region_id'];
            }
            if( !empty( $this->request->query['sub_center_id'] ) ) {
                $conditions['Ticket.sub_center_id'] = $this->request->query['sub_center_id'];
            }
            if( !empty( $this->request->query['site_id'] ) ) {
                $conditions['Ticket.site_id'] = $this->request->query['site_id'];
            }
            if( !empty( $this->request->query['asset_group_id'] ) ) {
                $conditions['Ticket.asset_group_id'] = $this->request->query['asset_group_id'];
            }
            if( !empty( $this->request->query['asset_number_id'] ) ) {
                $conditions['Ticket.asset_number_id'] = $this->request->query['asset_number_id'];
            }
            if( !empty( $this->request->query['supplier_id'] ) ) {
                $conditions['Ticket.supplier_id'] = $this->request->query['supplier_id'];
            }
            if( !empty( $this->request->query['tr_class_id'] ) ) {
                $tr_class_id = $this->request->query['tr_class_id'];

                $this->loadModel('TrClass');
                $tr_class = $this->TrClass->find('first',[
                    'conditions' => [
                        'TrClass.id' => $tr_class_id
                    ],
                    'contain' => false
                ]);

                $tr_class_name = isset($tr_class['TrClass']['tr_class_name']) ? $tr_class['TrClass']['tr_class_name'] : '';
                $conditions['Ticket.tr_class'] = $tr_class_name;
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
            /* Prepare conditions: END */

            $this->loadModel( 'Ticket' );
            $data = $this->Ticket->find( 'all', array(
                'conditions' => $conditions,
                'contain'    => array( 'Supplier.name', 'Site.site_name', 'AssetGroup.asset_group_name', 'AssetNumber.asset_number', 'TrClass.tr_class_name' ),
                'order'      => array( 'Ticket.id' => 'DESC' ),
            ) );
            $this->set( 'data', $data );
        }

        $this->loadModel('TrClass');
        $trClass = $this->TrClass->find( 'list', array(
            'contain'    => FALSE,
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );

        $this->set( array(
            'search'           => $this->request->query,
            'regionList'       => $this->WarrantyLookup->getRegionList(),
            'supplierList'     => $this->WarrantyLookup->getSupplierList( NULL ),
            'title_for_layout' => 'Tickets Report',
            'trClass' => $trClass,
        ) );
    }

    public function regionSelected() {
        $this->autoRender = FALSE;

        $this->loadModel( 'SubCenter' );
        $data['SubCenter'] = $this->SubCenter->find( 'list', array(
            'conditions' => array( 'SubCenter.region_id' => $this->request->data['region_id'] ),
            'fields'     => array( 'id', 'sub_center_name' ),
        ) );

        die( json_encode( $data ) );
    }

    public function subCenterSelected() {
        $this->autoRender = FALSE;

        $this->loadModel( 'Site' );
        $data['Site'] = $this->Site->find( 'list', array(
            'conditions' => array( 'Site.sub_center_id' => $this->request->data['sub_center_id'] ),
            'fields'     => array( 'id', 'site_name' ),
        ) );

        die( json_encode( $data ) );
    }

    public function siteSelected() {
        $this->autoRender = FALSE;

        $this->loadModel( 'AssetGroup' );
        $data['AssetGroup'] = $this->AssetGroup->find( 'list', array(
            'conditions' => array(
                'AssetGroup.site_id'          => $this->request->data['site_id'],
                'AssetGroup.asset_group_name' => 3012,
            ),
            'fields'     => array( 'id', 'asset_group_name' ),
        ) );

        die( json_encode( $data ) );
    }

    public function assetGroupSelected() {
        $this->autoRender = FALSE;

        $this->loadModel( 'AssetNumber' );
        $data['AssetNumber'] = $this->AssetNumber->find( 'list', array(
            'conditions' => array( 'AssetNumber.asset_group_id' => $this->request->data['asset_group_id'] ),
            'fields'     => array( 'id', 'asset_number' ),
        ) );

        $this->loadModel( 'TrClass' );
        $data['TrClass'] = $this->TrClass->find( 'list', array(
            'conditions' => array(
                'TrClass.asset_group_id'     => $this->request->data['asset_group_id'],
                'TrClass.tr_class_name LIKE' => 'SS%',
            ),
            'fields'     => array( 'id', 'tr_class_name' ),
        ) );

        die( json_encode( $data ) );
    }

    /**
     * Download Report as Excel
     *
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function export_excel() {
        /* keep running all the time */
        set_time_limit( 0 );
        ini_set( 'memory_limit', '-1' );

        App::import( 'Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ) );
        App::import( 'Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ) );

        $objPHPExcel = new PHPExcel();
        $objSheet = $objPHPExcel->getActiveSheet();

        /* Sheet header */
        $objSheet->setCellValue( 'A1', 'TR Number' );
        $objSheet->setCellValue( 'B1', 'TR Status' );
        $objSheet->setCellValue( 'C1', 'Category' );
//        $objSheet->setCellValue( 'D1', 'Asset Group' );
        $objSheet->setCellValue( 'D1', '' );
//        $objSheet->setCellValue( 'E1', 'Asset Number' );
        $objSheet->setCellValue( 'E1', '' );
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

        $objSheet->setCellValue( 'R1', 'TR line 1' );
        $objSheet->setCellValue( 'S1', 'Item Code' );
        $objSheet->setCellValue( 'T1', 'Item Description' );
        $objSheet->setCellValue( 'U1', 'Unit price' );
        $objSheet->setCellValue( 'V1', 'Quantity' );
        $objSheet->setCellValue( 'W1', 'Total (with vat)' );

        $objSheet->setCellValue( 'X1', 'TR line 2' );
        $objSheet->setCellValue( 'Y1', 'Item Code' );
        $objSheet->setCellValue( 'Z1', 'Item Description' );
        $objSheet->setCellValue( 'AA1', 'Unit price' );
        $objSheet->setCellValue( 'AB1', 'Quantity' );
        $objSheet->setCellValue( 'AC1', 'Total (with vat)' );

        $objSheet->setCellValue( 'AD1', 'TR line 3' );
        $objSheet->setCellValue( 'AE1', 'Item Code' );
        $objSheet->setCellValue( 'AF1', 'Item Description' );
        $objSheet->setCellValue( 'AG1', 'Unit price' );
        $objSheet->setCellValue( 'AH1', 'Quantity' );
        $objSheet->setCellValue( 'AI1', 'Total (with vat)' );

        $objSheet->setCellValue( 'AJ1', 'TR line 4' );
        $objSheet->setCellValue( 'AK1', 'Item Code' );
        $objSheet->setCellValue( 'AL1', 'Item Description' );
        $objSheet->setCellValue( 'AM1', 'Unit price' );
        $objSheet->setCellValue( 'AN1', 'Quantity' );
        $objSheet->setCellValue( 'AO1', 'Total (with vat)' );

        $objSheet->setCellValue( 'AP1', 'TR line 5' );
        $objSheet->setCellValue( 'AQ1', 'Item Code' );
        $objSheet->setCellValue( 'AR1', 'Item Description' );
        $objSheet->setCellValue( 'AS1', 'Unit price' );
        $objSheet->setCellValue( 'AT1', 'Quantity' );
        $objSheet->setCellValue( 'AU1', 'Total (with vat)' );

        $objSheet->setCellValue( 'AV1', 'TR line 6' );
        $objSheet->setCellValue( 'AW1', 'Item Code' );
        $objSheet->setCellValue( 'AX1', 'Item Description' );
        $objSheet->setCellValue( 'AY1', 'Unit price' );
        $objSheet->setCellValue( 'AZ1', 'Quantity' );
        $objSheet->setCellValue( 'BA1', 'Total (with vat)' );

        $objSheet->setCellValue( 'BB1', 'TR line 7' );
        $objSheet->setCellValue( 'BC1', 'Item Code' );
        $objSheet->setCellValue( 'BD1', 'Item Description' );
        $objSheet->setCellValue( 'BE1', 'Unit price' );
        $objSheet->setCellValue( 'BF1', 'Quantity' );
        $objSheet->setCellValue( 'BG1', 'Total (with vat)' );

        $objSheet->setCellValue( 'BH1', 'TR line 8' );
        $objSheet->setCellValue( 'BI1', 'Item Code' );
        $objSheet->setCellValue( 'BJ1', 'Item Description' );
        $objSheet->setCellValue( 'BK1', 'Unit price' );
        $objSheet->setCellValue( 'BL1', 'Quantity' );
        $objSheet->setCellValue( 'BM1', 'Total (with vat)' );

        $objSheet->setCellValue( 'BN1', 'TR line 9' );
        $objSheet->setCellValue( 'BO1', 'Item Code' );
        $objSheet->setCellValue( 'BP1', 'Item Description' );
        $objSheet->setCellValue( 'BQ1', 'Unit price' );
        $objSheet->setCellValue( 'BR1', 'Quantity' );
        $objSheet->setCellValue( 'BS1', 'Total (with vat)' );

        $objSheet->setCellValue( 'BT1', 'TR line 10' );
        $objSheet->setCellValue( 'BU1', 'Item Code' );
        $objSheet->setCellValue( 'BV1', 'Item Description' );
        $objSheet->setCellValue( 'BW1', 'Unit price' );
        $objSheet->setCellValue( 'BX1', 'Quantity' );
        $objSheet->setCellValue( 'BY1', 'Total (with vat)' );

        $objSheet->setCellValue( 'BZ1', 'Grand Total' );

        /* Style the header */
        $styleHeader = array( 'font' => array( 'bold' => TRUE ) );
        $objSheet->getStyle( 'A1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'B1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'C1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'D1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'E1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'F1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'G1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'H1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'I1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'J1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'K1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'L1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'M1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'N1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'O1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'P1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'Q1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'R1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'S1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'T1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'U1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'V1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'W1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'X1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'Y1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'Z1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AA1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AB1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AC1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AD1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AE1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AF1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AG1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AH1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AI1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AJ1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AK1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AL1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AM1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AN1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AO1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AP1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AQ1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AR1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AS1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AT1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AU1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AV1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AW1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AX1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AY1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'AZ1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BA1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BB1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BC1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BD1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BE1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BF1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BG1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BH1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BI1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BJ1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BK1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BL1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BM1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BN1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BO1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BP1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BQ1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BR1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BS1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BT1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BU1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BV1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BW1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BX1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BY1' )->applyFromArray( $styleHeader );
        $objSheet->getStyle( 'BZ1' )->applyFromArray( $styleHeader );

        /* Prepare conditions: START */
        $conditions = array(//'Ticket.sub_center_id' => $this->loginUser['User']['sub_center_id']
        );

        if( !empty( $this->request->query['site_id'] ) ) {
            $conditions['Ticket.site_id'] = $this->request->query['site_id'];
        }
        if( !empty( $this->request->query['asset_group_id'] ) ) {
            $conditions['Ticket.asset_group_id'] = $this->request->query['asset_group_id'];
        }
        if( !empty( $this->request->query['asset_number_id'] ) ) {
            $conditions['Ticket.asset_number_id'] = $this->request->query['asset_number_id'];
        }
        if( !empty( $this->request->query['supplier_id'] ) ) {
            $conditions['Ticket.supplier_id'] = $this->request->query['supplier_id'];
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
        /* Prepare conditions: END */

        $this->loadModel( 'Ticket' );
        $data = $this->Ticket->find( 'all', array(
            'conditions' => $conditions,
            'contain'    => array(
                'Supplier.name', 'SubCenter.sub_center_name', 'Region.region_name', 'Site.site_name', 'AssetGroup.asset_group_name',
                'AssetNumber.asset_number', 'TrClass.tr_class_name', 'CreatedBy.name', 'ClosedBy.name', 'ValidatedBy.name', 'TrService' => array( 'Service' ),
            ),
            'order'      => array( 'Ticket.id' => 'DESC' ),
        ) );

        if( !empty( $data ) ) {
            $row = 2;
            foreach( $data as $d ) {
                /* Write one row into the sheet */
                $objSheet->setCellValue( "A{$row}", $d['Ticket']['id'] );

                $status = '';
                if( $d['Ticket']['approval_status'] == DENY ) {
                    $status = 'Rejected';
                }
                else if( $d['Ticket']['approval_status'] == APPROVE && $d['Ticket']['is_invoiceable'] == NO ) {
                    $status = 'Approved';
                }
                else if( $d['Ticket']['pending_status'] == PENDING && $d['Ticket']['approval_status'] == NULL ) {
                    $status = 'Pending';
                }
                else if( $d['Ticket']['lock_status'] == LOCK && $d['Ticket']['pending_status'] == NULL ) {
                    $status = 'Locked';
                }
                else if( $d['Ticket']['lock_status'] == NULL ) {
                    $status = 'Assigned';
                }
                $objSheet->setCellValue( "B{$row}", $status );

                $objSheet->setCellValue( "C{$row}", substr( $d['TrClass']['tr_class_name'], 0, 2 ) );
//                $objSheet->setCellValue( "D{$row}", $d['AssetGroup']['asset_group_name'] );
                $objSheet->setCellValue( "D{$row}", '' );
//                $objSheet->setCellValue( "E{$row}", $d['AssetNumber']['asset_number'] );
                $objSheet->setCellValue( "E{$row}", '' );
                $objSheet->setCellValue( "F{$row}", $d['TrClass']['tr_class_name'] );
                $objSheet->setCellValue( "G{$row}", $d['Site']['site_name'] );
                $objSheet->setCellValue( "H{$row}", $d['SubCenter']['sub_center_name'] );
                $objSheet->setCellValue( "I{$row}", $d['Region']['region_name'] );
                $objSheet->setCellValue( "J{$row}", $d['CreatedBy']['name'] );
                $objSheet->setCellValue( "K{$row}", $d['ClosedBy']['name'] );
                $objSheet->setCellValue( "L{$row}", $d['ValidatedBy']['name'] );
                $objSheet->setCellValue( "M{$row}", $d['Ticket']['created'] );
                $objSheet->setCellValue( "N{$row}", $d['Ticket']['received_at_supplier'] );
                $objSheet->setCellValue( "O{$row}", $d['Ticket']['complete_date'] );
                $objSheet->setCellValue( "P{$row}", $d['Ticket']['closing_date'] );
                $objSheet->setCellValue( "Q{$row}", $d['Ticket']['validation_date'] );

                $total = 0;
                if( !empty( $d['TrService'] ) ) {
                    $col = 17;
                    foreach( $d['TrService'] as $s ) {
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, '' );
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, $s['Service']['service_name'] );
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, $s['Service']['service_desc'] );
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, $s['Service']['service_unit_price'] );
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, $s['quantity'] );

                        $sTotal = $s['Service']['service_unit_price'] * $s['quantity'] * ( 1 + $s['Service']['vat'] / 100 );
                        $total += $sTotal;
                        $objSheet->setCellValue( $this->getExcelColumnName( ++$col ) . $row, $sTotal );
                    }
                }
                $objSheet->setCellValue( "BZ{$row}", $total );

                $row++;
            }
        }

        /* Resize sheet columns */
        $objSheet->getColumnDimension( 'A' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'B' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'C' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'D' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'E' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'F' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'G' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'H' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'I' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'J' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'K' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'L' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'M' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'N' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'O' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'P' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'Q' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'R' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'S' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'T' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'U' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'V' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'W' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'X' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'Y' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'Z' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AA' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AB' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AC' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AD' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AE' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AF' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AG' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AH' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AI' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AJ' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AK' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AL' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AM' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AN' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AO' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AP' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AQ' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AR' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AS' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AT' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AU' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AV' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AW' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AX' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AY' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'AZ' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BA' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BB' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BC' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BD' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BE' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BF' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BG' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BH' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BI' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BJ' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BK' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BL' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BM' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BN' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BO' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BP' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BQ' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BR' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BS' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BT' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BU' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BV' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BW' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BX' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BY' )->setAutoSize( TRUE );
        $objSheet->getColumnDimension( 'BZ' )->setAutoSize( TRUE );
        die('test');
        /* Download the report */
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
        header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        header( 'Content-Disposition: attachment;filename="tr_report_' . date( 'Y-m-d-h-i-A' ) . '.xlsx"' );
        header( 'Cache-Control: max-age=0' );
        $objWriter->save( 'php://output' );
        exit;
    }
}