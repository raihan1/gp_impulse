<?php
App::uses( 'ApiAppController', 'Api.Controller' );
App::uses( 'AuthComponent', 'Controller/Component' );

/**
 * Users Controller
 */
class UsersController extends ApiAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Log In
     *
     * @input string $email *
     * @input string $password *
     */
    public function sign_in() {
        try {
            //<editor-fold desc="Validation" defaultstate="collapsed">
            $empty_fields = array();
            $required_fields = array(
                'email'    => 'Email',
                'password' => 'Password',
            );
            if( !empty( $required_fields ) ) {
                foreach( $required_fields as $id => $name ) {
                    if( empty( $_REQUEST[ $id ] ) ) {
                        $empty_fields[] = $name;
                    }
                }
            }
            if( !empty( $empty_fields ) ) {
                throw new Exception( 'Required data (' . implode( ', ', $empty_fields ) . ') missing.', STATUS_INPUT_UNACCEPTABLE );
            }
            //</editor-fold>
            
            $this->loadModel( 'User' );
            $this->loadModel( 'UserToken' );
            
            $user = $this->User->find( 'first', array(
                'conditions' => array(
                    'User.email'    => $_REQUEST['email'],
                    'User.password' => AuthComponent::password( $_REQUEST['password'] ),
                    'User.status'   => ACTIVE,
                ),
                'contain'    => FALSE,
            ) );
            if( empty( $user ) ) {
                throw new Exception( 'Invalid credential or account inactive.', STATUS_UNAUTHORIZED );
            }
            
            //<editor-fold desc="Generate token for future transactions" defaultstate="collapsed">
            if( !empty( $this->input['token'] ) ) {
                $token = $this->input['token'];
                $tokenDetails = $this->UserToken->find( 'first', array( 'conditions' => array( 'UserToken.token' => $token ), 'contain' => FALSE ) );
            }
            else {
                while( 1 ) {
                    $lookup = $this->Components->load( 'Lookup' );
                    $token = $lookup->generateRandomString( 29 );
                    $tokenDetails = $this->UserToken->find( 'first', array( 'conditions' => array( 'UserToken.token' => $token ), 'contain' => FALSE ) );
                    if( empty( $tokenDetails ) ) {
                        break;
                    }
                }
            }
            
            $tokenData = array( 'UserToken' => array(
                'user_id' => $user['User']['id'],
                'token'   => $token,
            ) );
            if( !empty( $tokenDetails ) ) {
                $tokenData['UserToken']['id'] = $tokenDetails['UserToken']['id'];
            }
            if( !$this->UserToken->save( $tokenData ) ) {
                throw new Exception( 'Failed to save token data. Please contact Administrator.', STATUS_SERVER_ERROR );
            }
            $user['User']['token'] = $token;
            //</editor-fold>
            
            $this->output['result'] = array( 'User' => $this->processUserOutput( $user['User'] ) );
            $this->output['message'] = 'Logged in successfully.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        $this->showOutput();
    }
    
    /**
     * Log Out
     *
     * @input string $token *
     */
    public function logout() {
        try {
            $this->loadModel( 'UserToken' );
            if( !$this->UserToken->deleteAll( array( 'UserToken.token' => $this->loginUser['User']['token'] ) ) ) {
                throw new Exception( 'Failed to delete token data. Please contact Administrator.', STATUS_SERVER_ERROR );
            }
            $this->output['message'] = 'Log out successfully.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        $this->showOutput();
    }
    
    /**
     * Forgot password
     *
     * @input string $email *
     */
    public function forgot_password() {
        try {
            if( empty( $this->input['data']['email'] ) ) {
                throw new Exception( 'Please specify your email address.', STATUS_INPUT_UNACCEPTABLE );
            }
            
            $this->loadModel( 'User' );
            $user = $this->User->find( 'first', array( 'conditions' => array( 'User.email' => $this->input['data']['email'] ), 'contain' => FALSE ) );
            if( empty( $user ) ) {
                throw new Exception( 'Wrong information provided.', STATUS_NOT_FOUND );
            }
            
            $token = rand( 1, 100000 );
            if( !$this->User->save( array( 'User' => array( 'id' => $user['User']['id'], 'password_reset_token' => $token ) ) ) ) {
                throw new Exception( 'Failed to save password reset token.', STATUS_SERVER_ERROR );
            }
            
            $message = '<div>
                            <p style="color: #5B5861; line-height: 22px;">
                                Hi ' . $user['User']['name'] . '<br />
                                Use this token for reset password: ' . $token . '
                            </p>
                        </div>';
            if( !$this->sendEmailGP( $user['User']['email'], '', 'Password Reset Request', $message ) ) {
                throw new Exception( 'Failed to send password reset token.', STATUS_SERVER_ERROR );
            }
            
            $this->output['message'] = 'You can reset your password now.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        $this->showOutput();
    }
    
    /**
     * Reset password
     *
     * @input string $email *
     * @input string $password
     */
    public function reset_password() {
        try {
            //<editor-fold desc="Validation" defaultstate="collapsed">
            $empty_fields = array();
            $required_fields = array(
                'email'                => 'Email',
                'password_reset_token' => 'Password Reset Token',
            );
            if( !empty( $required_fields ) ) {
                foreach( $required_fields as $id => $name ) {
                    if( empty( $_REQUEST[ $id ] ) ) {
                        $empty_fields[] = $name;
                    }
                }
            }
            if( !empty( $empty_fields ) ) {
                throw new Exception( 'Required data (' . implode( ', ', $empty_fields ) . ') missing.', STATUS_INPUT_UNACCEPTABLE );
            }
            //</editor-fold>
            
            $this->loadModel( 'User' );
            $user = $this->User->find( 'first', array(
                'conditions' => array(
                    'User.email'                => $this->input['data']['email'],
                    'User.password_reset_token' => $this->input['data']['password_reset_token'],
                ),
                'contain'    => FALSE,
            ) );
            if( empty( $user ) ) {
                throw new Exception( 'Wrong information provided.', STATUS_NOT_FOUND );
            }
            
            if( !$this->User->save( array( 'User' => array( 'id' => $user['User']['id'], 'password' => AuthComponent::password( $this->input['data']['password'] ) ) ) ) ) {
                throw new Exception( 'Failed to reset password.', STATUS_SERVER_ERROR );
            }
            
            $this->output['message'] = 'Your password has been reset successfully. You can login now.';
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
        }
        $this->showOutput();
    }
}