<?php
App::uses( 'AppController', 'Controller' );

/**
 * Class MasterController
 *
 * @abstract Master controller for all controllers except PublicPagesController and API plugin
 */
class MasterController extends AppController {
    
    public $components = array(
        'Auth' => array(
            'loginRedirect'  => array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'dashboard' ),
            'logoutRedirect' => array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'login' ),
            'loginAction'    => array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'login' ),
            'authenticate'   => array( 'Form' => array(
                'userModel' => 'User',
                'scope'     => array( 'User.status' => ACTIVE ),
            ) ),
            'authorize'      => array( 'Controller' ),
        ),
        'Session',
        'Cookie',
        'Paginator',
        'Lookup',
        'WarrantyLookup',
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        if( $this->Auth->loggedIn() ) {
            $this->loadModel( 'User' );
            $this->loginUser = $this->User->find( 'first', array(
                'conditions' => array( 'User.id' => $this->Auth->user( 'id' ) ),
                'contain'    => array( 'Region.region_name', 'SubCenter.sub_center_name', 'Supplier.name' ),
            ) );
        }
    }
    
    /**
     * Global dynamic authorization function
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return in_array( $user['role'], array( ADMIN, TR_CREATOR, SECURITY, TR_VALIDATOR, SUPPLIER, INVOICE_CREATOR, INVOICE_VALIDATOR ) );
    }
}