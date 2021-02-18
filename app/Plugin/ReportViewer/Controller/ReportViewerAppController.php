<?php
App::uses( 'MasterController', 'Controller' );

class ReportViewerAppController extends MasterController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'report_viewer';
    }
    
    /**
     * Static authorization function for Tr_validation plugin
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        $roleData = $this->Session->read('userRole');
        return in_array(REPORT_VIEWER, $roleData); /*$user['role'] == TR_VALIDATOR;*/
    }
}