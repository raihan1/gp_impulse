<?php
App::uses( 'MasterController', 'Controller' );

class TrValidationAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'tr_validation';
    }
    
    /**
     * Static authorization function for Tr_validation plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return $user['role'] == TR_VALIDATOR;
    }
}