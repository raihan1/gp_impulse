<?php
App::uses( 'MasterController', 'Controller' );

class InvoiceCreationAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'invoice_creation';
    }
    
    /**
     * Static authorization function for Invoice_Creator plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return $user['role'] == INVOICE_CREATOR;
    }
}