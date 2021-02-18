<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses( 'Model', 'Model' );
App::uses( 'AuthComponent', 'Controller/Component' );

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    public $actsAs = array( 'Containable' );
    
    /**
     * Filter delete or inactive data
     *
     * @param type $query
     *
     * @return type array
     */
    public function beforeFind( $query ) {
        parent::beforeFind( $query );
        
        $action = Router::getParams();
        if(
            empty( $query['noStatus'] )
            && $this->hasField( 'status' )
            && !isset( $query['conditions'][ $this->alias . '.status' ] )
            && !isset( $query['conditions'][ $this->alias . '.status !=' ] )
            && ( $action['plugin'] != 'invoice_creation' && $action['controller'] != 'invoices' )
            && ( $action['plugin'] != 'invoice_validation' && $action['controller'] != 'invoices' )
            && !( $action['plugin'] == 'supplier' && $action['controller'] == 'reports' )
            && !( $action['plugin'] == 'admin' && $action['controller'] == 'users' && $action['action'] == 'bulk_import' )
        ) {
            $query['conditions'][ $this->alias . '.status !=' ] = INACTIVE;
        }
        
        if(
            $this->hasField( 'is_deleted' )
            && !isset( $query['conditions'][ $this->alias . '.is_deleted' ] )
            && !isset( $query['conditions'][ $this->alias . '.is_deleted !=' ] )
        ) {
            $query['conditions'][ $this->alias . '.is_deleted =' ] = NO;
        }
        
        return $query;
    }
    
    /**
     * Insert created_by or Update modified_by, last_action
     *
     * @param type $options
     *
     * @return type array
     */
    public function beforeSave( $options = array() ) {
        parent::beforeSave( $options );
        
        if( AuthComponent::user() ) {
            if( empty( $this->data[ $this->alias ]['id'] ) && parent::hasField( 'created_by' ) ) {
                $this->data[ $this->alias ]['created_by'] = AuthComponent::user( 'id' );
            }
            else if( !empty( $this->data[ $this->alias ]['id'] ) && parent::hasField( 'modified_by' ) ) {
                $this->data[ $this->alias ]['modified_by'] = AuthComponent::user( 'id' );
            }
        }
        
        return $this->data;
    }
    
    /**
     * Delete row (set is_deleted as YES)
     *
     * @param integer $id
     * @param boolean $cascade (default TRUE)
     * @param boolean $hard    (default FALSE)
     *
     * @return array|boolean
     */
    public function delete( $id = NULL, $cascade = TRUE, $hard = FALSE ) {
        if( empty( $id ) || !parent::exists( $id ) ) {
            throw new NotFoundException( 'Invalid ' . $this->alias . ' ID.' );
        }
        if( !$hard && parent::hasField( 'is_deleted' ) ) {
            if( parent::save( array( $this->alias => array( 'id' => $id, 'is_deleted' => YES ) ), FALSE ) ) {
                return TRUE;
            }
            else {
                return $this->validationErrors;
            }
        }
        else {
            if( parent::delete( $id ) ) {
                return TRUE;
            }
            else {
                return $this->validationErrors;
            }
        }
    }
}