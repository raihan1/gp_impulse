<?php
App::uses( 'AppController', 'Controller' );

class ApiAppController extends AppController {
    
    public $input = array();
    
    public $output = array(
        'request'     => array(),
        'result'      => array(),
        'message'     => '',
        'status_code' => STATUS_OK,
    );
    
    public $loginUser = array();
    
    public $noTokenActions = array(
        'users/sign_in',
        'users/sign_out',
        'users/forgot_password',
        'users/reset_password',
    );
    
    public function beforeFilter() {
        parent::beforeFilter();
        //die( 'Mobile application is under maintenance. Please check back later.' );
        
        set_time_limit( 0 );
        ini_set( 'memory_limit', '-1' );
        
        $this->autoRender = FALSE;
        $address = $this->request->params['controller'] . '/' . $this->request->params['action'];
        
        if( $address == 'users/upload_photo' || $address == 'notes/upload_notes_photo' ) {
            //Do Nothing
        }
        else if( !empty( $_REQUEST['browser'] ) ) {
            $this->input = $this->request->input( 'json_decode', TRUE );
        }
        else {
            $this->input = json_decode( $this->request->data, TRUE );
        }
        
        $this->output['request'] = am( $_REQUEST, $this->input );
        $this->processInput();
        
        if( in_array( $address, $this->noTokenActions ) ) {
            //Do Nothing
        }
        else if( !empty( $this->input['token'] ) || !empty( $_REQUEST['token'] ) ) {
            $this->setLoginData();
        }
        else {
            $this->output['status_code'] = STATUS_FORBIDDEN;
            $this->output['message'] = 'Please provide your token for identification.';
            $this->showOutput();
        }
    }
    
    /**
     * Process Input to the API
     */
    private function processInput() {
        if( !empty( $this->input['data'] ) ) {
            foreach( $this->input['data'] as $key => $value ) {
                if( !is_array( $value ) ) {
                    $value = trim( $value );
                    if( $value == '' || $value == 'null' || $value == 'NULL' ) {
                        $this->input['data'][ $key ] = NULL;
                    }
                    else {
                        $this->input['data'][ $key ] = $value;
                    }
                }
            }
        }
    }
    
    /**
     * Output the output
     */
    protected function showOutput() {
        if( !empty( $this->output['request']['data']['photo'] ) ) {
            unset( $this->output['request']['data']['photo'] );
        }
        header( 'Content-type: text/json' );
        die( json_encode( $this->output ) );
    }
    
    /**
     * Set the global $loginUser
     */
    private function setLoginData() {
        try {
            $cond_token = !empty( $this->input['token'] ) ? $this->input['token'] : $_REQUEST['token'];
            
            $this->loadModel( 'UserToken' );
            $token = $this->UserToken->find( 'first', array( 'conditions' => array( 'UserToken.token' => $cond_token ), 'contain' => FALSE ) );
            if( empty( $token ) ) {
                throw new Exception( 'Invalid token provided.', STATUS_UNAUTHORIZED );
            }
            
            if( !empty( $token['UserToken']['user_id'] ) ) {
                $this->loadModel( 'User' );
                $this->loginUser = $this->User->find( 'first', array(
                    'conditions' => array( 'User.id' => $token['UserToken']['user_id'] ),
                    'contain'    => array( 'Region.region_name', 'SubCenter.sub_center_name', 'Supplier.name' ),
                ) );
                $this->loginUser['User']['token'] = $token['UserToken']['token'];
                
                unset( $this->loginUser['User']['password'], $this->loginUser['User']['image'] );
                unset( $this->loginUser['User']['created'], $this->loginUser['User']['modified'] );
                unset( $this->loginUser['User']['created_by'], $this->loginUser['User']['modified_by'] );
            }
            
            return TRUE;
        }
        catch( Exception $e ) {
            $this->output['status_code'] = $e->getCode();
            $this->output['message'] = $e->getMessage();
            $this->showOutput();
        }
    }
    
    /**
     * Process user profile before output
     *
     * @param array $user
     *
     * @return array
     */
    protected function processUserOutput( $user ) {
        if( !empty( $user['profile_photo'] ) && file_exists( WWW_ROOT . 'files/profile_photo/' . $user['profile_photo'] ) ) {
            $user['photo'] = BASEURL . "files/profile_photo/{$user['profile_photo']}";
        }
        
        return $user;
    }
}