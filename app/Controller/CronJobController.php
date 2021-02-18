<?php
App::uses( 'AppController', 'Controller' );

/**
 * Class CronJobController
 *
 * @abstract all cron jobs for the application are here
 *
 * @property WarrantyLookupComponent WarrantyLookup
 */
class CronJobController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Monthly CronJob to manage Office Budget
     *
     * @param string|null $key
     * @param bool        $internal
     *
     * @return bool
     */
    public function subcenter_budget( $key = NULL, $internal = FALSE ) {
        if( empty( $key ) || $key != 'hLlgpWaRRanTy' ) {
            throw new ForbiddenException( 'You are not authorized here.' );
        }
        
        $this->autoRender = FALSE;
        
        $this->WarrantyLookup->populate_subcenter_budget();
        
        if( $internal ) {
            return TRUE;
        }
        die( 'Office budget refreshed for ' . date( 'F Y' ) );
    }
}