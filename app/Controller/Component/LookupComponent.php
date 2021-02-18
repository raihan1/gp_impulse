<?php
App::uses( 'Component', 'Controller' );

/**
 * Class LookupComponent
 *
 * @abstract      General lookup functions
 * @author        Md. Muntasir Enam <mme.ctg@gmail.com>
 * @copyright (c) 2015, Humac Lab Limited <http://www.humaclab.com>
 */
class LookupComponent extends Component {

    /**
     * Get user list
     *
     * @param $role
     *
     * @return array
     */
    public function getUserList( $role ) {
        App::import( 'Model', 'User' );
        $objUser = new User();
        return $objUser->find( 'list', array( 'conditions' => array( 'User.role' => $role, 'User.status' => ACTIVE ), 'fields' => array( 'id', 'email' ) ) );
    }

    /**
     * Generate Random String
     *
     * @param $length
     * @param $type
     *
     * @return string
     */
    public function generateRandomString( $length = 29, $type = 1 ) {
        if( $type == 2 ) {
            $characters = '0123456789';
        }
        else if( $type == 3 ) {
            $characters = 'abcdefghijklmnopqrstuvwxyz';
        }
        else if( $type == 4 ) {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else if( $type == 5 ) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        }
        else if( $type == 6 ) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else if( $type == 7 ) {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $randomString = '';
        for( $i = 0; $i < $length; $i++ ) {
            $randomString .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
        }
        return $randomString;
    }
}