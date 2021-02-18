<?php
App::uses( 'AppController', 'Controller' );

/**
 * Class ScriptsController
 *
 * @property WarrantyLookupComponent WarrantyLookup
 */
class ScriptsController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = FALSE;
    }
    
    /* Check SMS */
    public function check_sms( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            
            $lookup = $this->Components->load( 'Lookup' );
            $msgId = time() . $lookup->generateRandomString( 3, 2 );
            $message = urlencode( 'Testing SMS from Impulse' );
            $phone = '01711506415';
            
            $url = "http://192.168.206.65:4444/cpSubscriptionService/Default.aspx?key=GPTECH_eBN3459a&mobileNo=88{$phone}&body={$message}&msg_type=4&send_port=19172&in_Msg_Id=1&out_Msg_Id={$msgId}";
            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
            $output = curl_exec( $ch );
            curl_close( $ch );
            
            echo '<pre />';
            print_r( $msgId );
            echo '<pre />';
            print_r( $output );
            exit;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /* Recalculate budget */
    public function recalculate_budget( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            
            //<editor-fold desc="Reset budget" defaultstate="collapsed">
            $this->loadModel( 'SubCenterBudget' );
            $this->SubCenterBudget->query(
                'UPDATE `sub_center_budgets`
                SET
                `AC_current_budget` = `AC_initial_budget`,
                `CW_current_budget` = `CW_initial_budget`,
                `DV_current_budget` = `DV_initial_budget`,
                `EB_current_budget` = `EB_initial_budget`,
                `FM_current_budget` = `FM_initial_budget`,
                `GN_current_budget` = `GN_initial_budget`,
                `PG_current_budget` = `PG_initial_budget`,
                `RF_current_budget` = `RF_initial_budget`,
                `SS_current_budget` = `SS_initial_budget`
                WHERE
                `year` = ' . date( 'Y' ) . ' AND
                `month` = ' . date( 'm' )
            );
            //</editor-fold>
            
            $this->loadModel( 'Ticket' );
            $tickets = $this->Ticket->find( 'all', array(
                'conditions' => array(
                    'Ticket.approval_status'     => APPROVE,
                    'YEAR( `validation_date` )'  => date( 'Y' ),
                    'MONTH( `validation_date` )' => date( 'm' ),
                ),
                'contain'    => array( 'TrClass.tr_class_name', 'TrService' => array( 'Service' ) ),
            ) );
            if( !empty( $tickets ) ) {
                $mainTypes = Configure::read( 'mainTypes' );
                
                foreach( $tickets as $tr ) {
                    //<editor-fold desc="Calculate total" defaultstate="collapsed">
                    $total = 0;
                    foreach( $tr['TrService'] as $trs ) {
                        if( $trs['status'] == ACTIVE && $trs['warranty_status'] == NO ) {
                            $total += $trs['Service']['service_unit_price'] * $trs['quantity'] * ( 1 + $trs['Service']['vat'] / 100 );
                        }
                    }
                    //</editor-fold>
                    
                    $mainType = $this->WarrantyLookup->getMainType( $tr['TrClass']['tr_class_name'] );
                    if( !in_array( $mainType, $mainTypes ) ) {
                        continue;
                    }
                    
                    //<editor-fold desc="Update subcenter budget for this ticket" defaultstate="collapsed">
                    $column = $mainType . '_current_budget';
                    $this->SubCenterBudget->updateAll(
                        array( $column => $column . ' - ' . $total ),
                        array(
                            'sub_center_id' => $tr['Ticket']['sub_center_id'],
                            'month'         => date( 'm' ),
                            'year'          => date( 'Y' ),
                        )
                    );
                    //</editor-fold>
                }
            }
            
            die( 'Budget recalculated successfully.' );
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /* Forward Budget */
    public function forward_budget( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            $this->loadModel( 'SubCenterBudget' );
            $lastMonthBudgets = $this->SubCenterBudget->find( 'all', array(
                'conditions' => array(
                    'SubCenterBudget.year'  => date( 'Y', strtotime( '-1 month', time() ) ),
                    'SubCenterBudget.month' => date( 'm', strtotime( '-1 month', time() ) ),
                ),
                'contain'    => FALSE,
            ) );
            if( !empty( $lastMonthBudgets ) ) {
                foreach( $lastMonthBudgets as $lastMonthBudget ) {
                    $currentMonthBudget = $this->SubCenterBudget->find( 'first', array(
                        'conditions' => array(
                            'SubCenterBudget.sub_center_id' => $lastMonthBudget['SubCenterBudget']['sub_center_id'],
                            'SubCenterBudget.year'          => date( 'Y' ),
                            'SubCenterBudget.month'         => date( 'm' ),
                        ),
                        'contain'    => FALSE,
                    ) );
                    if( !empty( $currentMonthBudget ) ) {
                        if( $lastMonthBudget['SubCenterBudget']['AC_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['AC_current_budget'] += $lastMonthBudget['SubCenterBudget']['AC_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['CW_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['CW_current_budget'] += $lastMonthBudget['SubCenterBudget']['CW_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['DV_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['DV_current_budget'] += $lastMonthBudget['SubCenterBudget']['DV_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['EB_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['EB_current_budget'] += $lastMonthBudget['SubCenterBudget']['EB_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['FM_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['FM_current_budget'] += $lastMonthBudget['SubCenterBudget']['FM_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['GN_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['GN_current_budget'] += $lastMonthBudget['SubCenterBudget']['GN_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['PG_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['PG_current_budget'] += $lastMonthBudget['SubCenterBudget']['PG_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['RF_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['RF_current_budget'] += $lastMonthBudget['SubCenterBudget']['RF_current_budget'];
                        }
                        if( $lastMonthBudget['SubCenterBudget']['SS_current_budget'] > 0 ) {
                            $currentMonthBudget['SubCenterBudget']['SS_current_budget'] += $lastMonthBudget['SubCenterBudget']['SS_current_budget'];
                        }
                        
                        $this->SubCenterBudget->create();
                        if( !$this->SubCenterBudget->save( $currentMonthBudget ) ) {
                            throw new Exception( 'Failed to save updated budget data.' );
                        }
                    }
                }
            }
            
            die( 'Budget forwarded successfully.' );
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Fix last_service for all tr_services
     *
     * @param string $key
     *
     * @created 2016-11-01
     */
    public function fix_last_service( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo 'Fixing last_service started at: ' . date( 'Y-m-d H:i:s' );
            
            $fp = fopen( WWW_ROOT . 'fix_last_service.txt', 'w+' );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing last_service started.\r\n" );
            
            $this->loadModel( 'TrService' );
            $page = 1;
            $updated = 0;
            while( 1 ) {
                $trServices = $this->TrService->find( 'all', array(
                    'conditions' => array( 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                    'contain'    => array( 'Ticket.site' ),
                    'order'      => array( 'TrService.id' => 'ASC' ),
                    'limit'      => 10,
                    'page'       => $page,
                ) );
                if( empty( $trServices ) ) {
                    break;
                }
                
                foreach( $trServices as $trs ) {
                    $lastService = $this->TrService->find( 'first', array(
                        'conditions' => array(
                            'TrService.id !='             => $trs['TrService']['id'],
                            'Ticket.site'                 => $trs['Ticket']['site'],
                            'TrService.supplier'          => $trs['TrService']['supplier'],
                            'TrService.service'           => $trs['TrService']['service'],
                            'TrService.delivery_date <= ' => $trs['TrService']['delivery_date'],
                            'TrService.status'            => ACTIVE,
                            'TrService.is_deleted'        => NO,
                        ),
                        'contain'    => array( 'Ticket.site' ),
                        'order'      => array( 'TrService.delivery_date' => 'DESC' ),
                    ) );
                    $this->TrService->id = $trs['TrService']['id'];
                    $this->TrService->saveField( 'last_service', !empty( $lastService ) ? $lastService['TrService']['id'] : 0 );
                    
                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Updated service {$trs['TrService']['id']}.\r\n" );
                    $updated++;
                }
                
                $page++;
            }
            
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing last_service completed.\r\n" );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Updated: {$updated}.\r\n" );
            fclose( $fp );
            
            echo '<br />Fixing last_service completed at: ' . date( 'Y-m-d H:i:s' );
            echo '<br />Updated: ' . $updated;
            exit;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Fix warranty of un-invoiced tr_services
     *
     * @param string $key
     *
     * @created 2016-11-01
     */
    public function fix_warranty( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo 'Fixing warranty started at: ' . date( 'Y-m-d H:i:s' );
            
            $fp = fopen( WWW_ROOT . 'fix_warranty.txt', 'w+' );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing warranty started.\r\n" );
            
            $this->loadModel( 'Ticket' );
            $this->loadModel( 'TrService' );
            $this->loadModel( 'SubCenterBudget' );
            
            $mainTypes = Configure::read( 'mainTypes' );
            
            $budgetChanges = array();
            $page = 1;
            while( 1 ) {
                $tickets = $this->Ticket->find( 'all', array(
                    'conditions' => array( 'Ticket.invoice_id' => 0, 'Ticket.status' => ACTIVE, 'Ticket.is_deleted' => NO ),
                    'contain'    => array( 'SubCenter.id', 'TrService' => array( 'LastService', 'Service' ) ),
                    'limit'      => 10,
                    'page'       => $page,
                ) );
                if( empty( $tickets ) ) {
                    break;
                }
                
                foreach( $tickets as $tr ) {
                    $total = $vat_total = $total_with_vat = 0;
                    
                    //<editor-fold desc="Update tr_services and recalculate ticket totals" defaultstate="collapsed">
                    foreach( $tr['TrService'] as $trs ) {
                        $warrantyHours = 24 * $trs['Service']['warranty_days'] + $trs['Service']['warranty_hours'];
                        if( !empty( $trs['last_service'] ) && $trs['LastService']['warranty_status'] == NO && strtotime( $trs['LastService']['delivery_date'] ) >= strtotime( "-{$warrantyHours} hours", strtotime( $trs['delivery_date'] ) ) ) {
                            $saveData = array(
                                'TrService.warranty_status' => YES,
                                'TrService.total'           => 0,
                                'TrService.vat_total'       => 0,
                                'TrService.total_with_vat'  => 0,
                            );
                        }
                        else {
                            $saveData = array(
                                'TrService.warranty_status' => NO,
                                'TrService.total'           => $trs['unit_price'] * $trs['quantity'],
                                'TrService.vat_total'       => $trs['unit_price'] * $trs['quantity'] * $trs['vat'] / 100,
                                'TrService.total_with_vat'  => $trs['unit_price_with_vat'] * $trs['quantity'],
                            );
                        }
                        $this->TrService->updateAll( $saveData, array( 'TrService.id' => $trs['id'] ) );
                        
                        $total += $saveData['TrService.total'];
                        $vat_total += $saveData['TrService.vat_total'];
                        $total_with_vat += $saveData['TrService.total_with_vat'];
                    }
                    //</editor-fold>
                    
                    if( $tr['Ticket']['total_with_vat'] != $total_with_vat ) {
                        //<editor-fold desc="Update tickets" defaultstate="collapsed">
                        $saveData = array(
                            'Ticket.total'          => $total,
                            'Ticket.vat_total'      => $vat_total,
                            'Ticket.total_with_vat' => $total_with_vat,
                        );
                        $this->Ticket->updateAll( $saveData, array( 'Ticket.id' => $tr['Ticket']['id'] ) );
                        //</editor-fold>
                        
                        //<editor-fold desc="Prepare budget change" defaultstate="collapsed">
                        $mainType = $this->WarrantyLookup->getMainType( $tr['Ticket']['tr_class'] );
                        if( in_array( $mainType, $mainTypes ) ) {
                            $index = $tr['SubCenter']['id'] . '_' . date( 'm', strtotime( $tr['Ticket']['validation_date'] ) ) . '_' . date( 'Y', strtotime( $tr['Ticket']['validation_date'] ) );
                            if( isset( $budgetChanges[ $index ][ $mainType ] ) ) {
                                $budgetChanges[ $index ][ $mainType ] += $total_with_vat - $tr['Ticket']['total_with_vat'];
                            }
                            else {
                                $budgetChanges[ $index ][ $mainType ] = $total_with_vat - $tr['Ticket']['total_with_vat'];
                            }
                        }
                        //</editor-fold>
                    }
                }
                
                $page++;
            }
            
            //<editor-fold desc="Update SubCenterBudget" defaultstate="collapsed">
            if( !empty( $budgetChanges ) ) {
                foreach( $budgetChanges as $index => $mainTypes ) {
                    list( $subCenterId, $month, $year ) = explode( '_', $index );
                    $saveData = array();
                    foreach( $mainTypes as $mainType => $change ) {
                        $column = "{$mainType}_consumed_budget";
                        $saveData[ $column ] = $column . ' + ' . $change;
                    }
                    $this->SubCenterBudget->updateAll(
                        $saveData,
                        array(
                            'sub_center_id' => $subCenterId,
                            'month'         => $month,
                            'year'          => $year,
                        )
                    );
                }
            }
            //</editor-fold>
            
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing warranty completed.\r\n" );
            fclose( $fp );
            
            echo '<br />Fixing warranty completed at: ' . date( 'Y-m-d H:i:s' );
            exit;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Download zero quantity service report as Excel
     *
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function zero_quantity_report( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            App::import( 'Vendor', 'PHPExcel', array( 'file' => 'PHPExcel.php' ) );
            App::import( 'Vendor', 'IOFactory', array( 'file' => 'PHPExcel/Writer/Excel2007.php' ) );
            
            $objPHPExcel = new PHPExcel();
            $objSheet = $objPHPExcel->getActiveSheet();
            
            //<editor-fold desc="Sheet header" defaultstate="collapsed">
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
            
            $objSheet->setCellValue( 'S1', 'Item Code' );
            $objSheet->setCellValue( 'T1', 'Item Description' );
            $objSheet->setCellValue( 'U1', 'Unit price' );
            $objSheet->setCellValue( 'V1', 'Quantity' );
            $objSheet->setCellValue( 'W1', 'Total (with vat)' );
            $objSheet->setCellValue( 'X1', 'Delivery Date' );
            $objSheet->setCellValue( 'Y1', 'Invoice ID' );
            $objSheet->setCellValue( 'Z1', 'Item ID' );
            $objSheet->setCellValue( 'AA1', 'Action' );
            //</editor-fold>
            
            //<editor-fold desc="Style the header" defaultstate="collapsed">
            $styleHeader = array( 'font' => array( 'bold' => TRUE ) );
            for( $col = 1; $col <= 27; $col++ ) {
                $objSheet->getStyle( $this->getExcelColumnName( $col ) . '1' )->applyFromArray( $styleHeader );
            }
            //</editor-fold>
            
            //<editor-fold desc="Get all un-invoiced tickets with status <= pending" defaultstate="collapsed">
            $this->loadModel( 'Ticket' );
            $data = $this->Ticket->find( 'all', array(
                'conditions' => array(
                    'Ticket.invoice_id'     => 0,
                    'Ticket.pending_status' => PENDING,
                ),
                'contain'    => array(
                    'Supplier.name', 'SubCenter.sub_center_name', 'Region.region_name', 'Site.site_name', 'AssetGroup.asset_group_name',
                    'AssetNumber.asset_number', 'TrClass.tr_class_name', 'CreatedBy.name', 'ClosedBy.name', 'ValidatedBy.name', 'TrService' => array( 'Service' ),
                ),
                'order'      => array( 'Ticket.id' => 'DESC' ),
            ) );
            //</editor-fold>
            
            if( !empty( $data ) ) {
                $row = 2;
                foreach( $data as $d ) {
                    //<editor-fold desc="Decide status" defaultstate="collapsed">
                    $status = '';
                    if( $d['Ticket']['lock_status'] == NULL ) {
                        $status = 'Assigned';
                    }
                    else if( $d['Ticket']['lock_status'] == LOCK && $d['Ticket']['pending_status'] == NULL ) {
                        $status = 'Locked';
                    }
                    else if( $d['Ticket']['pending_status'] == PENDING && $d['Ticket']['approval_status'] == NULL ) {
                        $status = 'Pending';
                    }
                    else if( $d['Ticket']['approval_status'] == APPROVE ) {
                        $status = 'Approved';
                    }
                    else if( $d['Ticket']['approval_status'] == DENY ) {
                        $status = 'Rejected';
                    }
                    //</editor-fold>
                    
                    if( !empty( $d['TrService'] ) ) {
                        foreach( $d['TrService'] as $s ) {
                            if( $s['status'] == ACTIVE && $s['quantity'] == 0 ) {
                                //<editor-fold desc="Write one row into the sheet" defaultstate="collapsed">
                                $objSheet->setCellValue( "A{$row}", $d['Ticket']['id'] );
                                $objSheet->setCellValue( "B{$row}", $status );
                                $objSheet->setCellValue( "C{$row}", substr( $d['TrClass']['tr_class_name'], 0, 2 ) );
                                $objSheet->setCellValue( "D{$row}", $d['AssetGroup']['asset_group_name'] );
                                $objSheet->setCellValue( "E{$row}", $d['AssetNumber']['asset_number'] );
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
                                $objSheet->setCellValue( "R{$row}", $d['Supplier']['name'] );
                                
                                $objSheet->setCellValue( "S{$row}", $s['Service']['service_name'] );
                                $objSheet->setCellValue( "T{$row}", $s['Service']['service_desc'] );
                                $objSheet->setCellValue( "U{$row}", $s['Service']['service_unit_price'] );
                                $objSheet->setCellValue( "V{$row}", $s['quantity'] );
                                $objSheet->setCellValue( "W{$row}", $s['warranty_status'] == YES ? 0 : ( $s['Service']['service_unit_price'] * $s['quantity'] * ( 1 + $s['Service']['vat'] / 100 ) ) );
                                $objSheet->setCellValue( "X{$row}", $s['delivery_date'] );
                                $objSheet->setCellValue( "Y{$row}", $d['Ticket']['invoice_id'] );
                                $objSheet->setCellValue( "Z{$row}", $s['id'] );
                                //</editor-fold>
                                
                                $row++;
                            }
                        }
                    }
                }
            }
            
            //<editor-fold desc="Resize sheet columns" defaultstate="collapsed">
            for( $col = 1; $col <= 27; $col++ ) {
                $objSheet->getColumnDimension( $this->getExcelColumnName( $col ) )->setAutoSize( TRUE );
            }
            //</editor-fold>
            
            //<editor-fold desc="Download the report" defaultstate="collapsed">
            $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel2007' );
            header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
            header( 'Content-Disposition: attachment;filename="zero_quantity_report_' . date( 'Y-m-d-h-i-A' ) . '.xlsx"' );
            header( 'Cache-Control: max-age=0' );
            $objWriter->save( 'php://output' );
            exit;
            //</editor-fold>
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Fix Office budget
     *
     * @param string $key
     * @param int    $months
     * @param int    $forward
     *
     * @created 2016-07-17, 2016-07-31
     *
     * @throws Exception
     */
    public function fix_budget( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo 'Budget fixing started at: ' . date( 'Y-m-d H:i:s' );
            
            $this->loadModel( 'SubCenterBudget' );
            $this->loadModel( 'Ticket' );
            
            $mainTypes = Configure::read( 'mainTypes' );
            
            $months = array(
                array(
                    'year'                    => 2016,
                    'month'                   => 6,
                    'first_day'               => '2016-06-01 00:00:00',
                    'last_day'                => '2016-06-27 23:59:59',
                    'forward_from_last_month' => FALSE,
                ),
                array(
                    'year'                    => 2016,
                    'month'                   => 7,
                    'first_day'               => '2016-06-28 00:00:00',
                    'last_day'                => '2016-07-31 23:59:59',
                    'forward_from_last_month' => TRUE,
                ),
                array(
                    'year'                    => 2016,
                    'month'                   => 8,
                    'first_day'               => '2016-08-01 00:00:00',
                    'last_day'                => '2016-08-31 23:59:59',
                    'forward_from_last_month' => FALSE,
                ),
            );
            
            $fp = fopen( WWW_ROOT . 'debug.txt', 'w+' );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Budget fixing started.\r\n" );
            
            foreach( $months as $month ) {
                //<editor-fold desc="Reset forwarded and consumed budget" defaultstate="collapsed">
                $this->SubCenterBudget->query(
                    "UPDATE `sub_center_budgets`
                     SET
                        `AC_forwarded_budget` = 0, `AC_consumed_budget` = 0, `CW_forwarded_budget` = 0, `CW_consumed_budget` = 0, `DV_forwarded_budget` = 0, `DV_consumed_budget` = 0,
                        `EB_forwarded_budget` = 0, `EB_consumed_budget` = 0, `FM_forwarded_budget` = 0, `FM_consumed_budget` = 0, `GN_forwarded_budget` = 0, `GN_consumed_budget` = 0,
                        `PG_forwarded_budget` = 0, `PG_consumed_budget` = 0, `RF_forwarded_budget` = 0, `RF_consumed_budget` = 0, `SS_forwarded_budget` = 0, `SS_consumed_budget` = 0
                     WHERE
                        `year` = {$month['year']} AND `month` = {$month['month']}"
                );
                fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Budget reset successfully for {$month['year']}-{$month['month']}\r\n" );
                //</editor-fold>
                
                //<editor-fold desc="Forward budget" defaultstate="collapsed">
                if( $month['forward_from_last_month'] ) {
                    $lastMonthBudget = $this->SubCenterBudget->find( 'all', array(
                        'conditions' => array(
                            'SubCenterBudget.year'  => date( 'Y', strtotime( '-1 month', strtotime( "{$month['year']}-{$month['month']}-01 00:00:00" ) ) ),
                            'SubCenterBudget.month' => date( 'm', strtotime( '-1 month', strtotime( "{$month['year']}-{$month['month']}-01 00:00:00" ) ) ),
                        ),
                        'contain'    => FALSE,
                    ) );
                    if( !empty( $lastMonthBudget ) ) {
                        foreach( $lastMonthBudget as $budget ) {
                            $forward = array();
                            foreach( $mainTypes as $mainType ) {
                                $remainder = $budget['SubCenterBudget']["{$mainType}_initial_budget"] + $budget['SubCenterBudget']["{$mainType}_forwarded_budget"] - $budget['SubCenterBudget']["{$mainType}_consumed_budget"];
                                if( $remainder > 0 ) {
                                    $forward[ $mainType ] = $remainder;
                                }
                            }
                            
                            if( !empty( $forward ) ) {
                                $sql = 'UPDATE `sub_center_budgets` SET ';
                                $i = 0;
                                foreach( $forward as $mainType => $remainder ) {
                                    $sql .= ( $i > 0 ? ', ' : '' ) . "{$mainType}_forwarded_budget = {$remainder}";
                                    $i++;
                                }
                                $sql .= " WHERE `year` = {$month['year']} AND `month` = {$month['month']} AND sub_center_id = {$budget['SubCenterBudget']['sub_center_id']}";
                                
                                $this->SubCenterBudget->query( $sql );
                                fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Budget forwarded successfully for {$month['year']}-{$month['month']}. SQL: {$sql}\r\n" );
                            }
                        }
                    }
                }
                //</editor-fold>
                
                //<editor-fold desc="Get all approved tickets and update consumed budget" defaultstate="collapsed">
                $page = 1;
                while( 1 ) {
                    $tickets = $this->Ticket->find( 'all', array(
                        'conditions' => array(
                            'Ticket.approval_status'    => APPROVE,
                            'Ticket.validation_date >=' => $month['first_day'],
                            'Ticket.validation_date <=' => $month['last_day'],
                            'Ticket.total_with_vat >'   => 0,
                        ),
                        'contain'    => array( 'SubCenter' ),
                        'limit'      => 10,
                        'page'       => $page,
                    ) );
                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " {$month['year']}-{$month['month']} tickets: Page {$page}: " . count( $tickets ) . "\r\n" );
                    $page++;
                    if( empty( $tickets ) ) {
                        break;
                    }
                    else {
                        foreach( $tickets as $tr ) {
                            $mainType = $this->WarrantyLookup->getMainType( $tr['Ticket']['tr_class'] );
                            if( !in_array( $mainType, $mainTypes ) ) {
                                fwrite( $fp, date( 'Y-m-d H:i:s' ) . " {$month['year']}-{$month['month']} Skipped Ticket: {$tr['Ticket']['id']} Class: {$tr['Ticket']['tr_class']} ({$mainType})\r\n" );
                                continue;
                            }
                            
                            $column = $mainType . '_consumed_budget';
                            $this->SubCenterBudget->updateAll(
                                array( $column => $column . ' + ' . $tr['Ticket']['total_with_vat'] ),
                                array(
                                    'sub_center_id' => $tr['SubCenter']['id'],
                                    'year'          => $month['year'],
                                    'month'         => $month['month'],
                                )
                            );
                            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " {$month['year']}-{$month['month']} budget updated for TR: {$tr['Ticket']['id']}, TR Total: {$tr['Ticket']['total_with_vat']}, SC: {$tr['SubCenter']['id']}\r\n" );
                        }
                    }
                }
                //</editor-fold>
            }
            
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Budget fixed successfully.\r\n" );
            fclose( $fp );
            
            echo '<br />Budget fixing completed at: ' . date( 'Y-m-d H:i:s' );
            exit;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Move site tickets between Offices
     *
     * @param string $key
     */
    public function move_site_tickets( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo 'Moving site tickets started at: ' . date( 'Y-m-d H:i:s' );
            
            $fp = fopen( WWW_ROOT . 'move_site_tickets.txt', 'w+' );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Moving site tickets started.\r\n" );
            
            App::import( 'Vendor', 'excel_reader', [ 'file' => 'excel_reader/reader.php' ] );
            $excel = new Spreadsheet_Excel_Reader();
            $excel->read( WWW_ROOT . 'files/site/site_changes.xls' );
            
            $this->loadModel( 'Ticket' );
            
            $this->loadModel( 'Invoice' );
            
            $x = 2;
            $movedTickets = $recalculatedInvoices = 0;
            while( $x <= $excel->sheets[0]['numRows'] ) {
                $ticket = $this->Ticket->find( 'first', array(
                    'conditions' => array( 'Ticket.id' => trim( $excel->sheets[0]['cells'][ $x ][1] ), 'Ticket.is_deleted' => NO ),
                    'contain'    => FALSE,
                ) );
                if( !empty( $ticket ) ) {
                    $ticketData = array( 'Ticket.sub_center' => "'" . trim( $excel->sheets[0]['cells'][ $x ][4] ) . "'" );
                    
                    if( $ticket['Ticket']['invoice_id'] != 0 ) {
                        $invoice = $this->Invoice->find( 'first', array(
                            'conditions' => array( 'Invoice.id' => $ticket['Ticket']['invoice_id'], 'Invoice.is_deleted' => NO ),
                            'contain'    => FALSE,
                        ) );
                        if( !empty( $invoice ) ) {
                            if( $invoice['Invoice']['status'] == APPROVE ) {
                                /* Ignore the ticket if invoice is approved */
                                fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Ignored Ticket: {$ticket['Ticket']['id']} for approved Invoice: {$invoice['Invoice']['id']}.\r\n" );
                                continue;
                            }
                            else {
                                /* Remove the ticket from invoice */
                                $ticketData['Ticket.invoice_id'] = 0;
                                $ticketData['Ticket.invoice_date'] = NULL;
                                $ticketData['Ticket.is_invoiced'] = NO;
                                
                                //<editor-fold desc="Recalculate invoice" defaultstate="collapsed">
                                $tickets = $this->Ticket->find( 'first', array(
                                    'conditions' => array(
                                        'Ticket.id !='      => $ticket['Ticket']['id'],
                                        'Ticket.invoice_id' => $invoice['Invoice']['id'],
                                        'Ticket.status'     => ACTIVE,
                                        'Ticket.is_deleted' => NO,
                                    ),
                                    'contain'    => FALSE,
                                    'fields'     => array(
                                        'SUM( Ticket.total ) AS total',
                                        'SUM( Ticket.vat_total ) AS vat_total',
                                        'SUM( Ticket.vat_with_total ) AS vat_with_total',
                                    ),
                                ) );
                                if( !empty( $tickets ) ) {
                                    $invoiceData = array(
                                        'Invoice.total'          => $tickets[0]['total'],
                                        'Invoice.vat_total'      => $tickets[0]['vat_total'],
                                        'Invoice.vat_with_total' => $tickets[0]['vat_with_total'],
                                    );
                                    $this->Invoice->unbindModel( array(
                                        'belongsTo' => array( 'Supplier', 'Region', 'SubCenter' ),
                                        'hasMany'   => array( 'Ticket' ),
                                    ) );
                                    $this->Invoice->updateAll( $invoiceData, array( 'Invoice.id' => $invoice['Invoice']['id'] ) );
                                    
                                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Invoice: {$invoice['Invoice']['id']} recalculated for Ticket: {$ticket['Ticket']['id']}.\r\n" );
                                    $recalculatedInvoices++;
                                }
                                //</editor-fold>
                            }
                        }
                    }
                    
                    $this->Ticket->unbindModel( array(
                        'belongsTo' => array( 'User', 'Region', 'SubCenter', 'Site', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Invoice', 'CreatedBy', 'ClosedBy', 'ValidatedBy', ),
                        'hasMany'   => array( 'TrService' ),
                    ) );
                    $this->Ticket->updateAll( $ticketData, array( 'Ticket.id' => $ticket['Ticket']['id'] ) );
                    
                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Moved Ticket: {$ticket['Ticket']['id']}.\r\n" );
                    $movedTickets++;
                }
                
                $x++;
            }
            
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Moving site tickets completed.\r\n" );
            fclose( $fp );
            
            echo '<br />Moving site tickets completed at: ' . date( 'Y-m-d H:i:s' );
            echo '<br />Tickets moved: ' . $movedTickets;
            echo '<br />Invoices recalculated: ' . $recalculatedInvoices;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
    
    /**
     * Update invoice->tickets->services price of an invoice
     *
     * @param  string $key
     * @param  int    $invoiceId
     *
     * @created 2016-10-17
     */
    public function fix_invoice_tickets_services_price( $key, $invoiceId ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            if( empty( $invoiceId ) ) {
                throw new Exception( 'Please provide Invoice ID.' );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo 'Ticket processing started at: ' . date( 'Y-m-d H:i:s' );
            
            $this->loadModel( 'Ticket' );
            $this->loadModel( 'TrService' );
            $page = 1;
            $processed = 0;
            while( 1 ) {
                $tickets = $this->Ticket->find( 'all', array(
                    'conditions' => array( 'Ticket.invoice_id' => $invoiceId, 'Ticket.status' => ACTIVE, 'Ticket.is_deleted' => NO ),
                    'contain'    => FALSE,
                    'fields'     => array( 'Ticket.id' ),
                    'order'      => array( 'Ticket.id' => 'ASC' ),
                    'limit'      => 10,
                    'page'       => $page,
                ) );
                if( empty( $tickets ) ) {
                    break;
                }
                
                foreach( $tickets as $ticket ) {
                    //<editor-fold desc="Update TrService" defaultstate="collapsed">
                    $trServices = $this->TrService->find( 'all', array(
                        'conditions' => array( 'TrService.ticket_id' => $ticket['Ticket']['id'], 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                        'contain'    => FALSE,
                    ) );
                    foreach( $trServices as $trs ) {
                        $saveData = array(
                            'TrService.total'          => $trs['TrService']['warranty_status'] == YES ? 0 : $trs['TrService']['unit_price'] * $trs['TrService']['quantity'],
                            'TrService.vat_total'      => $trs['TrService']['warranty_status'] == YES ? 0 : $trs['TrService']['unit_price'] * $trs['TrService']['quantity'] * $trs['TrService']['vat'] / 100,
                            'TrService.total_with_vat' => $trs['TrService']['warranty_status'] == YES ? 0 : $trs['TrService']['unit_price'] * $trs['TrService']['quantity'] * ( 1 + $trs['TrService']['vat'] / 100 ),
                        );
                        $this->TrService->unbindModel( array(
                            'belongsTo' => array( 'Ticket', 'Service', 'Supplier' ),
                            'hasOne'    => array( 'LastService' ),
                        ) );
                        if( !$this->TrService->updateAll( $saveData, array( 'TrService.id' => $trs['TrService']['id'] ) ) ) {
                            $errors = '';
                            foreach( $this->TrService->validationErrors as $field => $error ) {
                                $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                            }
                            throw new Exception( $errors );
                        }
                    }
                    //</editor-fold>
                    
                    //<editor-fold desc="Update Ticket" defaultstate="collapsed">
                    $trServices = $this->TrService->find( 'first', array(
                        'conditions' => array( 'TrService.ticket_id' => $ticket['Ticket']['id'], 'TrService.status' => ACTIVE, 'TrService.is_deleted' => NO ),
                        'contain'    => FALSE,
                        'fields'     => array( 'SUM( TrService.total ) AS total', 'SUM( TrService.vat_total ) AS vat_total', 'SUM( TrService.total_with_vat ) AS total_with_vat' ),
                    ) );
                    $saveData = array(
                        'Ticket.total'          => !empty( $trServices[0]['total'] ) ? $trServices[0]['total'] : 0,
                        'Ticket.vat_total'      => !empty( $trServices[0]['vat_total'] ) ? $trServices[0]['vat_total'] : 0,
                        'Ticket.total_with_vat' => !empty( $trServices[0]['total_with_vat'] ) ? $trServices[0]['total_with_vat'] : 0,
                    );
                    $this->Ticket->unbindModel( array(
                        'belongsTo' => array( 'User', 'Region', 'SubCenter', 'Site', 'Project', 'AssetGroup', 'AssetNumber', 'TrClass', 'Supplier', 'SupplierCategory', 'Invoice', 'CreatedBy', 'ClosedBy', 'ValidatedBy', ),
                        'hasMany'   => array( 'TrService' ),
                    ) );
                    if( !$this->Ticket->updateAll( $saveData, array( 'Ticket.id' => $ticket['Ticket']['id'] ) ) ) {
                        $errors = '';
                        foreach( $this->Ticket->validationErrors as $field => $error ) {
                            $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                        }
                        throw new Exception( $errors );
                    }
                    //</editor-fold>
                    
                    $processed++;
                }
                
                unset( $tickets );
                $page++;
            }
            
            //<editor-fold desc="Update Invoice" defaultstate="collapsed">
            $sum = $this->Ticket->find( 'first', array(
                'conditions' => array( 'Ticket.invoice_id' => $invoiceId, 'Ticket.status' => ACTIVE, 'Ticket.is_deleted' => NO ),
                'contain'    => FALSE,
                'fields'     => array( 'SUM( Ticket.total ) AS total', 'SUM( Ticket.vat_total ) AS vat_total', 'SUM( Ticket.total_with_vat ) AS total_with_vat' ),
            ) );
            $this->loadModel( 'Invoice' );
            $saveData = array(
                'Invoice.total'          => !empty( $sum[0]['total'] ) ? $sum[0]['total'] : 0,
                'Invoice.vat_total'      => !empty( $sum[0]['vat_total'] ) ? $sum[0]['vat_total'] : 0,
                'Invoice.total_with_vat' => !empty( $sum[0]['total_with_vat'] ) ? $sum[0]['total_with_vat'] : 0,
            );
            if( !$this->Invoice->updateAll( $saveData, array( 'Invoice.id' => $invoiceId ) ) ) {
                $errors = '';
                foreach( $this->Invoice->validationErrors as $field => $error ) {
                    $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                }
                throw new Exception( $errors );
            }
            //</editor-fold>
            
            echo '<br />Ticket processing completed at: ' . date( 'Y-m-d H:i:s' );
            echo '<br />Processed tickets: ' . $processed;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
        
        exit;
    }
    
    /**
     * Fix tr_services with zero unit_price_with_vat
     *
     * @param string $key
     *
     * @created 2016-11-29
     */
    public function fix_tr_price( $key ) {
        try {
            if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
                throw new Exception( 'You are not authorized here.', STATUS_FORBIDDEN );
            }
            
            $this->autoRender = FALSE;
            set_time_limit( 0 );
            ini_set( 'memory_limit', -1 );
            
            echo date( 'Y-m-d H:i:s' ) . ' Fixing TR price started.';
            
            $fp = fopen( WWW_ROOT . 'fix_tr_price.txt', 'w+' );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing TR price started.\r\n" );
            
            $trsIds = array( '78351', '85816', '85810', '86847', '87517', '88660', '88585', '93336', '95598', '95604' );
            
            $this->loadModel( 'Ticket' );
            $this->loadModel( 'Service' );
            $this->loadModel( 'TrService' );
            $updated = 0;
            $trServices = $this->TrService->find( 'all', array( 'conditions' => array( 'TrService.id' => $trsIds ), 'contain' => array( 'Ticket' ), 'limit' => 10 ) );
            if( !empty( $trServices ) ) {
                foreach( $trServices as $trs ) {
                    $service = $this->Service->query( "SELECT `service_unit_price`, `vat`, `vat_plus_price` FROM `services` WHERE `supplier` = '{$trs['TrService']['supplier']}' AND `service_name` = '{$trs['TrService']['service']}' AND `asset_group` = '{$trs['Ticket']['asset_group']}'" );
                    $sql = "UPDATE `tr_services`
                            SET
                              `unit_price` = {$service[0]['services']['service_unit_price']},
                              `vat` = {$service[0]['services']['vat']},
                              `unit_price_with_vat` = {$service[0]['services']['vat_plus_price']},
                              `total` = " . ( $service[0]['services']['service_unit_price'] * $trs['TrService']['quantity'] ) . ",
                              `vat_total` = " . ( $service[0]['services']['service_unit_price'] * $trs['TrService']['quantity'] * $service[0]['services']['vat'] / 100 ) . ",
                              `total_with_vat` = " . ( $service[0]['services']['service_unit_price'] * $trs['TrService']['quantity'] * ( 1 + $service[0]['services']['vat'] / 100 ) ) . "
                            WHERE `id` = {$trs['TrService']['id']}";
                    $this->TrService->query( $sql );
                    
                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " TrService {$trs['TrService']['id']} updated.\r\n" );
                    echo '<br />' . date( 'Y-m-d H:i:s' ) . " TrService {$trs['TrService']['id']} updated.";
                    
                    $trTotal = $this->TrService->query( "SELECT SUM( `total` ) AS `total`, SUM( `vat_total` ) AS `vat_total`, SUM( `total_with_vat` ) AS `total_with_vat` FROM `tr_services` WHERE `ticket_id` = {$trs['Ticket']['id']}" );
                    $this->Ticket->query( "UPDATE `tickets` SET `total` = {$trTotal[0][0]['total']}, `vat_total` = {$trTotal[0][0]['vat_total']}, `total_with_vat` = {$trTotal[0][0]['total_with_vat']} WHERE `id` = {$trs['Ticket']['id']}" );
                    
                    fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Ticket {$trs['Ticket']['id']} updated.\r\n" );
                    echo '<br />' . date( 'Y-m-d H:i:s' ) . " Ticket {$trs['Ticket']['id']} updated.";
                    
                    $updated++;
                }
            }
            
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Fixing TR price completed.\r\n" );
            fwrite( $fp, date( 'Y-m-d H:i:s' ) . " Updated: {$updated}.\r\n" );
            fclose( $fp );
            
            echo '<br />' . date( 'Y-m-d H:i:s' ) . ' Fixing TR price completed.';
            echo '<br />Updated: ' . $updated;
            exit;
        }
        catch( Exception $e ) {
            die( $e->getMessage() );
        }
    }
}