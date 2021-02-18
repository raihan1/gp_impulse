<?php
App::uses( 'MasterController', 'Controller' );

class SecurityAppController extends MasterController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'security';
    }

    /**
     * Static authorization function for Tr_Creator plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        if( in_array( $user['role'], array( SECURITY ) ) ) {
            return parent::isAuthorized( $user );
        }
        else {
            $this->redirect( $this->Auth->loginRedirect );
        }
    }
}