<?php
App::uses( 'MasterController', 'Controller' );

class SupplierAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'supplier';
    }
    
    /**
     * Static authorization function for Supplier plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        if( in_array( $user['role'], array( SUPPLIER ) ) ) {
            return parent::isAuthorized( $user );
        }
        else {
            $this->redirect( $this->Auth->loginRedirect );
        }
    }
}