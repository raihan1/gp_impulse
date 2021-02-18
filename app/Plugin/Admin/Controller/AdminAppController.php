<?php
App::uses( 'MasterController', 'Controller' );

class AdminAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'admin';
    }
    
    /**
     * Static authorization function for Admin plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return $user['role'] == ADMIN;
    }
}