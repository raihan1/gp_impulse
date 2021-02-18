<?php
App::uses( 'ApiAppController', 'Api.Controller' );

/**
 * Lookup Controller
 */
class LookupController extends ApiAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

    /**
     * Office List
     *
     * @param string last_sync_time *
     */
    public function sub_center_list() {
        try {
            if( empty( $_REQUEST['last_sync_time'] ) ) {
                throw new Exception( 'Please provide last sync time.', STATUS_INPUT_UNACCEPTABLE );
            }
            
            $this->loadModel( 'SubCenter' );
            $subCenters = $this->SubCenter->find( 'all', [
                'conditions' => array(
                    'SubCenter.modified >= ' => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                    'SubCenter.is_deleted'   => array( YES, NO ),
                ),
                'contain'    => FALSE,
                'noStatus'   => TRUE,
            ] );
            
            $this->output['result']['SubCenter'] = array();
            if( !empty( $subCenters ) ) {
                foreach( $subCenters as $sc ) {
                    if( $sc['SubCenter']['is_deleted'] == YES ) {
                        $sc['SubCenter']['status'] = INACTIVE;
                    }
                    $this->output['result']['SubCenter'][] = $sc['SubCenter'];
                }
            }
            
            $this->output['message'] = count( $this->output['result']['SubCenter'] ) . ' SubCenter found.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }

        $this->showOutput();
    }

    /**
     * Site, Project, AssetGroup, AssetNumber and TrClass
     *
     * @param string last_sync_time *
     */
    public function site_list() {
        try {
            if( empty( $_REQUEST['last_sync_time'] ) ) {
                throw new Exception( 'Please provide last sync time.', STATUS_INPUT_UNACCEPTABLE );
            }

            $this->loadModel( 'Site' );
            $this->loadModel( 'Project' );
            $this->loadModel( 'AssetGroup' );
            $this->loadModel( 'AssetNumber' );
            $this->loadModel( 'TrClass' );
            
            $conditions = array(
                'Site.modified > '   => date( 'Y-m-d H:i:s', strtotime( $_REQUEST['last_sync_time'] ) ),
                'Site.is_deleted'    => array( YES, NO ),
            );
            if( $this->loginUser['User']['role'] != SUPPLIER ) {
                $conditions['Site.sub_center_id'] = $this->loginUser['User']['sub_center_id'];
            }
            $sites = $this->Site->find( 'all', [
                'conditions' => $conditions,
                'contain'    => FALSE,
                'noStatus'   => TRUE,
            ] );

            $i = 0;
            foreach( $sites as $s ) {
                if( $s['Site']['is_deleted'] == YES ) {
                    $s['Site']['status'] = INACTIVE;
                }
                $this->output['result'][ $i ] = $s['Site'];
    
                //<editor-fold desc="Project" defaultstate="collapsed">
                $this->output['result'][ $i ]['Project'] = array();
                $projects = $this->Project->find( 'all', array(
                    'conditions' => array(
                        'Project.site_id'    => $s['Site']['id'],
                        'Project.is_deleted' => array( YES, NO ),
                    ),
                    'contain'    => FALSE,
                    'noStatus'   => TRUE,
                ) );
                if( !empty( $projects ) ) {
                    foreach( $projects as $p ) {
                        if( $s['Site']['status'] == INACTIVE || $p['Project']['is_deleted'] == YES ) {
                            $p['Project']['status'] = INACTIVE;
                        }
                        $this->output['result'][ $i ]['Project'][] = $p['Project'];
                    }
                }
                //</editor-fold>

                $this->output['result'][ $i ]['AssetGroup'] = array();
                $assetGroups = $this->AssetGroup->find( 'all', array(
                    'conditions' => array(
                        'AssetGroup.site_id'    => $s['Site']['id'],
                        'AssetGroup.is_deleted' => array( YES, NO ),
                    ),
                    'contain'    => FALSE,
                    'noStatus'   => TRUE,
                ) );
                if( !empty( $assetGroups ) ) {
                    $j = 0;
                    foreach( $assetGroups as $ag ) {
                        if( $s['Site']['status'] == INACTIVE || $ag['AssetGroup']['is_deleted'] == YES ) {
                            $ag['AssetGroup']['status'] = INACTIVE;
                        }
                        $this->output['result'][ $i ]['AssetGroup'][ $j ] = $ag['AssetGroup'];
    
                        //<editor-fold desc="AssetNumber" defaultstate="collapsed">
                        $this->output['result'][ $i ]['AssetGroup'][ $j ]['AssetNumber'] = array();
                        $assetNumbers = $this->AssetNumber->find( 'all', array(
                            'conditions' => array(
                                'AssetNumber.asset_group_id' => $ag['AssetGroup']['id'],
                                'AssetNumber.is_deleted'     => array( YES, NO ),
                            ),
                            'contain'    => FALSE,
                            'noStatus'   => TRUE,
                        ) );
                        if( !empty( $assetNumbers ) ) {
                            foreach( $assetNumbers as $an ) {
                                if( $s['Site']['status'] == INACTIVE || $ag['AssetGroup']['status'] == INACTIVE || $an['AssetNumber']['is_deleted'] == YES ) {
                                    $an['AssetNumber']['status'] = INACTIVE;
                                }
                                $this->output['result'][ $i ]['AssetGroup'][ $j ]['AssetNumber'][] = $an;
                            }
                        }
                        //</editor-fold>
    
                        //<editor-fold desc="TrClass" defaultstate="collapsed">
                        $this->output['result'][ $i ]['AssetGroup'][ $j ]['TrClass'] = array();
                        $TrClass = $this->TrClass->find( 'all', array(
                            'conditions' => array(
                                'TrClass.asset_group_id' => $ag['AssetGroup']['id'],
                                'TrClass.is_deleted'     => array( YES, NO ),
                            ),
                            'contain'    => FALSE,
                            'noStatus'   => TRUE,
                        ) );
                        if( !empty( $TrClass ) ) {
                            foreach( $TrClass as $trC ) {
                                if( $s['Site']['status'] == INACTIVE || $ag['AssetGroup']['status'] == INACTIVE || $trC['TrClass']['is_deleted'] == YES ) {
                                    $trC['TrClass']['status'] = INACTIVE;
                                }
                                $this->output['result'][ $i ]['AssetGroup'][ $j ]['TrClass'][] = $trC;
                            }
                        }
                        //</editor-fold>

                        $j++;
                    }
                }

                $i++;
            }

            $this->output['last_sync_time'] = date( 'Y-m-d H:i:s' );
            $this->output['message'] = count( $this->output['result'] ) . ' Site found.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }

        $this->showOutput();
    }
    
    /**
     * Supplier List
     */
    public function supplier_list() {
        try {
            $this->loadModel( 'Supplier' );
            $this->loadModel( 'SupplierCategory' );
            
            $data = $this->Supplier->find( 'all', array(
                'conditions' => array( 'Supplier.is_deleted' => array( YES, NO ) ),
                'contain'    => FALSE,
                'noStatus'   => TRUE,
            ) );
            if( !empty( $data ) ) {
                $i = 0;
                foreach( $data as $d ) {
                    if( $d['Supplier']['is_deleted'] == YES ) {
                        $d['Supplier']['status'] = INACTIVE;
                    }
                    $this->output['result'][ $i ] = $d['Supplier'];
    
                    //<editor-fold desc="SupplierCategory" defaultstate="collapsed">
                    $this->output['result'][ $i ]['SupplierCategory'] = array();
                    $supplierCategories = $this->SupplierCategory->find( 'all', array(
                        'conditions' => array(
                            'SupplierCategory.supplier_id' => $d['Supplier']['id'],
                            'SupplierCategory.is_deleted'  => array( YES, NO ),
                        ),
                        'contain'    => FALSE,
                        'noStatus'   => TRUE,
                    ) );
                    if( !empty( $supplierCategories ) ) {
                        foreach( $supplierCategories as $scat ) {
                            if( $d['Supplier']['status'] == INACTIVE || $scat['SupplierCategory']['is_deleted'] == YES ) {
                                $scat['SupplierCategory']['status'] = INACTIVE;
                            }
                            $this->output['result'][ $i ]['SupplierCategory'][] = $scat['SupplierCategory'];
                        }
                    }
                    //</editor-fold>
                    
                    $i++;
                }
            }
            
            $this->output['message'] = count( $this->output['result'] ) . ' Supplier found.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
    
    /**
     * Service list
     */
    public function service_list() {
        try {
            $this->loadModel( 'Service' );
            $data = $this->Service->find( 'all', array(
                'conditions' => array(
                    'Service.supplier_id' => $this->loginUser['User']['supplier_id'],
                    'Service.is_deleted'  => array( YES, NO ),
                ),
                'contain'    => FALSE,
                'noStatus'   => TRUE,
            ) );
            if( !empty( $data ) ) {
                foreach( $data as $d ) {
                    if( $d['Service']['is_deleted'] == YES ) {
                        $d['Service']['status'] = INACTIVE;
                    }
                    $this->output['result']['Service'][] = $d['Service'];
                }
            }
            $this->output['message'] = count( $this->output['result']['Service'] ) . ' Service found.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        
        $this->showOutput();
    }
}