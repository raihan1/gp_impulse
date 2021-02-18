<?php
App::uses( 'InvoiceValidationAppController', 'InvoiceValidation.Controller' );

/**
 * Users Controller
 */
class UsersController extends InvoiceValidationAppController {
    
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
        $this->loadModel( 'Ticket' );
        
        $this->set( array(
            'title_for_layout' => 'Invoice Validator Dashboard',
            'assigned'         => $this->Ticket->find( 'count', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.lock_status' => NULL ) ) ),
            'locked'           => $this->Ticket->find( 'count', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.lock_status' => LOCK, 'Ticket.pending_status' => NULL ) ) ),
            'pending'          => $this->Ticket->find( 'count', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.pending_status' => PENDING, 'Ticket.approval_status' => NULL ) ) ),
            'approved'         => $this->Ticket->find( 'count', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.approval_status' => APPROVE, 'Ticket.invoice_id' => 0 ) ) ),
            'rejected'         => $this->Ticket->find( 'count', array( 'contain' => FALSE, 'conditions' => array( 'Ticket.approval_status' => DENY ) ) ),
        ) );
    }
    
    /**
     * User profile
     */
    public function profile() {
        $this->layout = 'profile';
        $this->loadModel( 'User' );
        
        if( $this->request->is( array( 'post', 'put' ) ) ) {
            try {
                $saveData = array(
                    'User.name'    => "'" . addslashes( $this->request->data['User']['name'] ) . "'",
                    'User.email'   => "'" . addslashes( $this->request->data['User']['email'] ) . "'",
                    'User.phone'   => "'" . addslashes( $this->request->data['User']['phone'] ) . "'",
                    'User.address' => "'" . addslashes( $this->request->data['User']['address'] ) . "'",
                );
                if( !empty( $this->request->data['User']['password'] ) ) {
                    $saveData['User.password'] = $this->Auth->password( $this->request->data['User']['password'] );
                }
                
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
                    $saveData['User.photo'] = $photo_name;
                }
                //</editor-fold>
                
                if( !$this->User->updateAll( $saveData, array( 'User.id' => $this->loginUser['User']['id'] ) ) ) {
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
                    $this->redirect( array( 'plugin' => 'invoice_validation', 'controller' => 'users', 'action' => 'dashboard' ) );
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
            'data'             => $this->User->find( 'first', array( 'conditions' => array( 'User.id' => $this->loginUser['User']['id'] ), 'contain' => FALSE ) ),
            'title_for_layout' => 'Invoice Validator Profile',
        ) );
    }
}