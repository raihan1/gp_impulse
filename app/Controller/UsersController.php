<?php
App::uses( 'MasterController', 'Controller' );

/**
 * Class UsersController
 *
 * @abstract All user action goes here
 */
class UsersController extends MasterController {
    
    public $uses = array( 'User', 'SubCenter', 'SubCenterBudget' );
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow( array( 'forgot_password' ) );
    }
    
    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        if( $this->action == 'login' ) {
            if( $this->Auth->loggedIn() ) {
                $this->redirect( $this->Auth->loginRedirect );
            }
            else {
                return TRUE;
            }
        }
        
        return parent::isAuthorized( $user );
    }
    
    /**
     * User login
     */
    public function login() {
        $this->layout = 'login';
        
        if( $this->request->is( 'post' ) ) {
            $user = $this->User->find( 'first', array(
                'conditions' => array(
                    'User.email'    => $this->request->data['User']['email'],
                    'User.password' => $this->Auth->password( $this->request->data['User']['password'] ),
                    'User.status'   => ACTIVE,
                ),
                'contain'    => array( 'Region.region_name', 'SubCenter.sub_center_name', 'Supplier.name' ),
            ) );
            
            if( !empty( $user ) && $this->Auth->login( $user['User'] ) ) {
                return $this->redirect( array( 'plugin' => FALSE, 'controller' => 'users', 'action' => 'dashboard' ) );
            }
            else {
                $this->Session->setFlash( 'Invalid credential. Please try again.', 'messages/failed' );
            }
        }
        
        $this->set( 'title_for_layout', 'Login' );
    }
    
    /**
     * Common function to redirect to specific dashboard
     */
    public function dashboard() {
        switch( $this->loginUser['User']['role'] ) {
            case ADMIN:
                $plugin = 'admin';
                break;
            case SUPPLIER:
                $plugin = 'supplier';
                break;
            case TR_CREATOR:
                $plugin = 'tr_creation';
                break;
            case SECURITY:
                $plugin = 'security';
                break;
            case TR_VALIDATOR:
                $plugin = 'tr_validation';
                break;
            case INVOICE_CREATOR:
                $plugin = 'invoice_creation';
                break;
            case INVOICE_VALIDATOR:
                $plugin = 'invoice_validation';
                break;
            default:
                $this->Auth->logoutRedirect();
                break;
        }
        
        $this->redirect( array( 'plugin' => $plugin, 'controller' => 'users', 'action' => 'dashboard' ) );
    }
    
    /**
     * Forgot Password
     */
    public function forgot_password() {
        $this->layout = 'forgot_password';
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $user = $this->User->find( 'first', array( 'conditions' => array( 'User.email' => $this->request->data['User']['email'] ), 'contain' => FALSE ) );
                if( empty( $user ) ) {
                    throw new Exception( 'Invalid email. Please try again.' );
                }
                
                $newPassword = $this->generate_password( 8 );
                
                $this->User->id = $user['User']['id'];
                $this->User->saveField( 'password', $this->Auth->password( $newPassword ) );
                
                $message = '<div>
                    <p style="color: #5B5861; line-height: 22px;">
                        Hi,<br />Your new password is ' . $newPassword . '
                    </p>
                </div>';
//                $result = $this->sendEmailGP( $this->request->data['User']['email'], '', 'New Password', $message );debug( $result );exit;
                if( !$this->sendEmailGP( $this->request->data['User']['email'], '', 'New Password', $message ) ) {
                    throw new Exception( 'Failed to send email to you with password. Please try again.' );
                }
                
                $this->Session->setFlash( 'A new password has been sent to your email.', 'messages/success' );
            }
            catch( Exception $e ) {
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => FALSE, 'message' => __( $e->getMessage() ) ) ) );
                }
                else {
                    $this->Session->setFlash( __( $e->getMessage() ), 'messages/failed' );
                }
            }
        }
        
        $this->set( 'title_for_layout', 'Forgot Password' );
    }
    
    /**
     * User logout
     */
    public function logout() {
        return $this->redirect( $this->Auth->logout() );
    }
    
    /**
     * Email Password to User
     *
     * @param type $to
     * @param type $from
     *
     * @return boolean
     */
    private function send_email( $to, $from, $pass ) {
        $subject = "New Password";
        $headers = "From: " . $from . " \r\n";
        $headers .= "Reply-To: " . $from . " \r\n";
        $headers .= "MIME-Version: 1.0\r\n"
            . "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
            . "Content-Transfer-Encoding: 7bit\r\n";
        
        $msg = "";
        
        $msg .= "<div>
                <p style='color: #5B5861; line-height: 22px'>
                    Hi Your new password is " . $pass . "
                </p>
            </div>";
        
        if( mail( $to, $subject, $msg, $headers ) ) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
    /**
     * Generate a random string for new password
     *
     * @param type $length
     *
     * @return type string
     */
    private function generate_password( $length = 8 ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr( str_shuffle( $chars ), 0, $length );
        
        return $password;
    }
}