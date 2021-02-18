<?php
App::uses( 'SecurityAppController', 'Security.Controller' );

/**
 * Users Controller
 */
class UsersController extends SecurityAppController {
    
    public $uses = array( 'User' );
    
    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /**
     * Static authorization function for this controller only
     *
     * @param array $user The loggedIn user array automatically passed by Auth
     *
     * @return boolean
     */
    public function isAuthorized( $user ) {
        return parent::isAuthorized( $user );
    }
    
    /**
     * TR Creator Dashboard
     */
    public function dashboard() {
        $this->set( 'title_for_layout', 'TR Creator (SS) Dashboard' );
    }
    
    /**
     * User profile
     */
    public function profile() {
        $this->layout = 'profile';
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $this->request->data['User']['id'] = $this->loginUser['User']['id'];
                unset( $this->request->data['User']['sub_center'] );
                
                //<editor-fold defaultstate="collapsed" desc="upload photo">
                if( !empty( $this->request->data['User']['photo'] ) && $this->request->data['User']['photo']['size'] > 0 ) {
                    $Qimage = $this->Components->load( 'Qimage' );
                    $photo_name = $Qimage->copy( array( 'file' => $this->request->data['User']['photo'], 'path' => WWW_ROOT . "resource/users/profile_photo/" ) );
                    if( !$photo_name ) {
                        throw new Exception( 'Failed to save photo. Please, try again.' );
                    }
                    if( !empty( $this->request->data['User']['photo'] ) ) {
                        if( !empty( $this->loginUser['User']['photo'] ) && file_exists( WWW_ROOT . "resource/users/profile_photo/{$this->loginUser['User']['photo']}" ) ) {
                            if( !unlink( WWW_ROOT . "resource/users/profile_photo/{$this->loginUser['User']['photo']}" ) ) {
                                throw new Exception( 'Failed to delete old photo. Please, try again.' );
                            }
                        }
                    }
                    $this->request->data['User']['photo'] = $photo_name;
                }
                else {
                    unset( $this->request->data['User']['photo'] );
                }
                //</editor-fold>
                
                if( empty( $this->request->data['User']['password'] ) ) {
                    unset( $this->request->data['User']['password'] );
                }
                else {
                    $this->request->data['User']['password'] = $this->Auth->password( $this->request->data['User']['password'] );
                }
                
                if( !$this->User->save( $this->request->data ) ) {
                    $errors = '';
                    foreach( $this->User->validationErrors as $field => $error ) {
                        $errors .= ( $errors == '' ? '' : '<br />' ) . $field . ': ' . implode( ', ', $error );
                    }
                    throw new Exception( $errors );
                }
                
                if( $this->request->is( 'ajax' ) ) {
                    die( json_encode( array( 'result' => TRUE, 'message' => 'Profile updated successfully.', 'id' => $this->User->id ) ) );
                }
                else {
                    $this->Session->setFlash( __( 'Profile updated successfully.' ), 'messages/success' );
                    $this->redirect( array( 'action' => 'dashboard' ) );
                }
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
        
        $this->set( array(
            'data'             => $this->User->find( 'first', array( 'contain' => 'SubCenter', 'conditions' => array( 'User.id' => $this->loginUser['User']['id'] ) ) ),
            'title_for_layout' => 'TR Creator Profile',
        ) );
    }
}