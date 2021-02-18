<?php
App::uses( 'MasterController', 'Controller' );

class TrCreationAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'tr_creation';
    }
    
    /**
     * Static authorization function for Tr_Creator plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return $user['role'] == TR_CREATOR;
    }
}