<?php
App::uses( 'Component', 'Controller' );

/**
 * Class SelliscopeLookupComponent
 *
 * @abstract      General lookup functions
 * @author        Md. Sohel Rana <sohel_adust@yahoo.com>
 * @copyright (c) 2015, Humac Lab Limited <http://www.humaclab.com>
 */
class WarrantyLookupComponent extends Component {
    
    /**
     * Get region list
     *
     * @return array|null
     */
    public function getRegionList() {
        App::import( 'Model', 'Region' );
        $objRegion = new Region();
        
        return $objRegion->find( 'list', array(
            'conditions' => array( 'Region.is_deleted' => NO ),
            'contain'    => FALSE,
            'order'      => array( 'Region.id' => 'ASC' ),
            'fields'     => array( 'Region.id', 'Region.region_name' ),
        ) );
    }
    
    /**
     * Get Office list
     *
     * @param null|integer|array $regionId
     *
     * @return array|null
     */
    public function getSubCenterList( $regionId = NULL ) {
        App::import( 'Model', 'SubCenter' );
        $objSubCenter = new SubCenter();
        
        $conditions = array();
        if( !empty( $regionId ) ) {
            $conditions['SubCenter.region_id'] = $regionId;
        }
        
        return $objSubCenter->find( 'list', array(
            'conditions' => $conditions,
            'contain'    => FALSE,
            'order'      => array( 'SubCenter.id' => 'ASC' ),
            'fields'     => array( 'SubCenter.id', 'SubCenter.sub_center_name' ),
        ) );
    }

    /**
     * Get Office list
     *
     * IF sub-center-id = NULL, this function will return
     * all Office list,IF sub-center-id is not NULL, this
     * function will return selected Office list.
     *
     * @author Md. Abdullah Al mamun <abdullah.mamun@bs-23.net>
     * @copyright  2018 Brain Station 23 Ltd.
     *
     * @param null|integer $subCenterId
     *
     * @return array
     *
     */
    public function getOfficeList($subCenterId = NULL) {
        App::import( 'Model', 'SubCenter' );
        $objOffice = new SubCenter();

        $conditions = array();
        if(!empty($subCenterId)){
            $conditions['SubCenter.id'] = $subCenterId;
        }

        return $objOffice->find( 'list', array(
            'conditions' => $conditions,
            'contain'    => FALSE,
            'order'      => array( 'SubCenter.id' => 'ASC' ),
            'fields'     => array( 'SubCenter.id', 'SubCenter.sub_center_name' ),
        ) );
    }
    
    /**
     * Get site list
     *
     * @param null|integer|array $subCenterId
     *
     * @return array|null
     *
     */
    public function getSiteList( $subCenterId = NULL ) {
        App::import( 'Model', 'Site' );
        $objSite = new Site();
        
        $conditions = array();
        if( !empty( $subCenterId ) ) {
            $conditions['Site.sub_center_id'] = $subCenterId;
        }
        
        return $objSite->find( 'list', array(
            'conditions' => $conditions,
            'contain'    => FALSE,
            'order'      => array( 'Site.id' => 'ASC' ),
            'fields'     => array( 'Site.id', 'Site.site_name' ),
        ) );
    }
    
    /**
     * Get project list
     *
     * @param null|integer|array $siteId
     * @param bool               $unique
     *
     * @return array|null
     *
     */
    public function getProjectList( $siteId = NULL, $unique = FALSE ) {
        App::import( 'Model', 'Project' );
        $objProject = new Project();
        
        $conditions = array();
        if( !empty( $siteId ) ) {
            if( intval( $siteId ) == 0 ) {
                $conditions['Site.site_name'] = $siteId;
            }
            else {
                $conditions['Project.site_id'] = $siteId;
            }
        }
        
        if( $unique ) {
            $projects = $objProject->find( 'all', array(
                'conditions' => $conditions,
                'contain'    => !empty( $siteId ) && intval( $siteId ) == 0 ? array( 'Site' ) : FALSE,
                'order'      => array( 'Project.project_name' => 'ASC' ),
                'fields'     => array( 'DISTINCT Project.project_name' ),
            ) );
            
            return Set::extract( $projects, '/Project/project_name' );
        }
        else {
            return $objProject->find( 'list', array(
                'conditions' => $conditions,
                'contain'    => !empty( $siteId ) && !is_int( $siteId ) ? array( 'Site' ) : FALSE,
                'order'      => array( 'Project.project_name' => 'ASC' ),
                'fields'     => array( 'Project.id', 'Project.project_name' ),
            ) );
        }
    }
    
    /**
     * Get asset group list
     *
     * @param null|integer|array $siteId
     * @param bool               $unique
     *
     * @return array|null
     *
     */
    public function getAssetGroupList( $siteId = NULL, $unique = FALSE ) {
        App::import( 'Model', 'AssetGroup' );
        $objAssetGroup = new AssetGroup();
        
        $conditions = array();
        if( !empty( $siteId ) ) {
            if( intval( $siteId ) == 0 ) {
                $conditions['Site.site_name'] = $siteId;
            }
            else {
                $conditions['AssetGroup.site_id'] = $siteId;
            }
        }
        
        if( $unique ) {
            $assetGroups = $objAssetGroup->find( 'all', array(
                'conditions' => $conditions,
                'contain'    => !empty( $siteId ) && intval( $siteId ) == 0 ? array( 'Site' ) : FALSE,
                'order'      => array( 'AssetGroup.asset_group_name' => 'ASC' ),
                'fields'     => array( 'DISTINCT AssetGroup.asset_group_name' ),
            ) );
            
            return Set::extract( $assetGroups, '/AssetGroup/asset_group_name' );
        }
        else {
            return $objAssetGroup->find( 'list', array(
                'conditions' => $conditions,
                'contain'    => !empty( $siteId ) && !is_int( $siteId ) ? array( 'Site' ) : FALSE,
                'order'      => array( 'AssetGroup.asset_group_name' => 'ASC' ),
                'fields'     => array( 'AssetGroup.id', 'AssetGroup.asset_group_name' ),
            ) );
        }
    }
    
    /**
     * Get asset number list
     *
     * @param null|integer|array $assetGroupId
     * @param bool               $unique
     *
     * @return array|null
     *
     */
    public function getAssetNumberList( $assetGroupId = NULL, $unique = FALSE ) {
        App::import( 'Model', 'AssetNumber' );
        $objAssetNumber = new AssetNumber();
        
        $conditions = array();
        if( !empty( $assetGroupId ) ) {
            if( intval( $assetGroupId ) == 0 ) {
                $conditions['AssetNumber.asset_number'] = $assetGroupId;
            }
            else {
                $conditions['AssetNumber.asset_group_id'] = $assetGroupId;
            }
        }
        
        if( $unique ) {
            $assetNumbers = $objAssetNumber->find( 'all', array(
                'conditions' => $conditions,
                'contain'    => FALSE,
                'order'      => array( 'AssetNumber.asset_number' => 'ASC' ),
                'fields'     => array( 'DISTINCT AssetNumber.asset_number' ),
            ) );
            
            return Set::extract( $assetNumbers, '/AssetNumber/asset_number' );
        }
        else {
            return $objAssetNumber->find( 'list', array(
                'conditions' => $conditions,
                'contain'    => FALSE,
                'order'      => array( 'AssetNumber.id' => 'ASC' ),
                'fields'     => array( 'AssetNumber.id', 'AssetNumber.asset_number' ),
            ) );
        }
    }
    
    /**
     * Get TrClass list
     *
     * @param null|integer|array $assetGroupId
     *
     * @return array|null
     *
     */
    public function getTrClassList( $assetGroupId = NULL ) {
        App::import( 'Model', 'TrClass' );
        $objTrClass = new TrClass();
        
        $conditions = array();
        if( !empty( $assetGroupId ) ) {
            $conditions['TrClass.asset_group_id'] = $assetGroupId;
        }
        
        return $objTrClass->find( 'list', array(
            'conditions' => $conditions,
            'contain'    => FALSE,
            'order'      => array( 'TrClass.id' => 'ASC' ),
            'fields'     => array( 'TrClass.id', 'TrClass.tr_class_name' ),
        ) );
    }
    
    /**
     * Get supplier list
     *
     * @return array|null
     */
    public function getSupplierList() {
        App::import( 'Model', 'Supplier' );
        $objSupplier = new Supplier();
        
        return $objSupplier->find( 'list', array(
            'conditions' => array(),
            'contain'    => FALSE,
            'order'      => array( 'Supplier.id' => 'ASC' ),
            'fields'     => array( 'Supplier.id', 'Supplier.name' ),
        ) );
    }
    
    /**
     * Get service list
     *
     * @param null|integer $supplierId
     * @param bool         $detail
     * @param null|string  $asset_group
     *
     * @return array|null
     */
    public function getServiceList( $supplierId = NULL, $detail = FALSE, $asset_group = NULL ) {
        App::import( 'Model', 'Service' );
        $objService = new Service();
        
        if( empty( $supplierId ) ) {
            return $objService->find( 'list', array(
                'contain' => FALSE,
                'order'   => array( 'Service.id' => 'ASC' ),
                'fields'  => array( 'Service.id', 'Service.service_name' ),
            ) );
        }
        
        $conditions = array( 'Service.supplier_id' => $supplierId );
        if( !empty( $asset_group ) ) {
            $conditions['Service.asset_group'] = $asset_group;
        }
        
        if( !$detail ) {
            return $objService->find( 'list', array(
                'conditions' => $conditions,
                'contain'    => FALSE,
                'order'      => array( 'Service.id' => 'ASC' ),
                'fields'     => array( 'Service.id', 'Service.service_name' ),
            ) );
        }
        else {
            return $objService->find( 'all', array(
                'conditions' => $conditions,
                'contain'    => FALSE,
                'order'      => array( 'Service.id' => 'ASC' ),
                'fields'     => array( 'Service.id', 'Service.service_name', 'Service.service_desc' ),
            ) );
        }
    }
    
    /**
     * Get MainType from TrClass Name
     *
     * @param string $trClassName
     *
     * @return string
     */
    public function getMainType( $trClassName ) {
        $type = substr( $trClassName, 0, 2 );
        if( in_array( $type, array( 'OD', 'PW' ) ) ) {
            $type = 'CW';
        }
        else if( $type == 'FP' ) {
            $type = 'FM';
        }
        else if( in_array( $type, array( 'GA', 'GE', 'GF', 'GP', 'GS', 'GT', 'GW', 'RP', 'RS', 'RT', 'RW' ) ) ) {
            $type = 'GN';
        }
        else if( substr( $trClassName, 0, 3 ) == 'RFE' && substr( $trClassName, 0, 4 ) != 'RFEX' ) {
            $type = 'GN';
        }
        
        return $type;
    }
    
    /**
     * Get associated main types
     *
     * @param string $mainType
     *
     * @return array
     */
    public function getAssociatedMainTypes( $mainType ) {
        if( $mainType == 'CW' ) {
            return array( 'CW', 'OD', 'PW' );
        }
        else if( $mainType == 'FM' ) {
            return array( 'FM', 'FP' );
        }
        else if( $mainType == 'GN' ) {
            return array( 'GN', 'GA', 'GE', 'GF', 'GP', 'GS', 'GT', 'GW', 'RP', 'RS', 'RT', 'RW', 'RFE' );
        }
        else {
            return array( $mainType );
        }
    }
    
    /**
     * Populate current month's Office budget
     *
     * @return bool
     */
    public function populate_subcenter_budget() {
        App::import( 'Model', 'SubCenter' );
        $objSubCenter = new SubCenter();
        
        $data = $objSubCenter->find( 'all', array( 'conditions' => array( 'SubCenter.status' => ACTIVE ), 'contain' => FALSE ) );
        if( !empty( $data ) ) {
            App::import( 'Model', 'SubCenterBudget' );
            $objSubCenterBudget = new SubCenterBudget();
            
            foreach( $data as $d ) {
                $subCenterBudget = $objSubCenterBudget->find( 'first', array(
                    'conditions' => array(
                        'sub_center_id' => $d['SubCenter']['id'],
                        'year'          => date( 'Y' ),
                        'month'         => date( 'm' ),
                    ),
                    'contain'    => FALSE,
                ) );
                if( empty( $subCenterBudget ) ) {
                    $saveData['SubCenterBudget'] = array(
                        'sub_center_id'     => $d['SubCenter']['id'],
                        'year'              => date( 'Y' ),
                        'month'             => date( 'm' ),
                        'AC_initial_budget' => $d['SubCenter']['AC_budget'],
                        'CW_initial_budget' => $d['SubCenter']['CW_budget'],
                        'DV_initial_budget' => $d['SubCenter']['DV_budget'],
                        'EB_initial_budget' => $d['SubCenter']['EB_budget'],
                        'FM_initial_budget' => $d['SubCenter']['FM_budget'],
                        'GN_initial_budget' => $d['SubCenter']['GN_budget'],
                        'PG_initial_budget' => $d['SubCenter']['PG_budget'],
                        'RF_initial_budget' => $d['SubCenter']['RF_budget'],
                        'SS_initial_budget' => $d['SubCenter']['SS_budget'],
                    );
                    $objSubCenterBudget->create();
                    $objSubCenterBudget->save( $saveData );
                }
            }
        }
        
        return TRUE;
    }
}