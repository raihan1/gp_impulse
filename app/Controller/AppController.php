<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses( 'Controller', 'Controller' );

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link           http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $loginUser = NULL;
    
    public $components = array( 'DebugKit.Toolbar', 'WarrantyLookup' );
    
    public $helpers = array( 'Lookup' );
    
    public function beforeRender() {
        parent::beforeRender();
        $this->set( 'loginUser', $this->loginUser );
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        //die( 'Service is under maintenance. Please check back later.' );
    }
    
    /**
     * Send mail
     */
    public function sendEmail( $config = array() ) {
        /*
        if( empty( $config['to'] ) || empty( $config['data'] ) || empty( $config['subject'] ) ) {
            return FALSE;
        }
        
        $config['layout'] = isset( $config['layout'] ) ? $config['layout'] : 'default';
        $config['template'] = isset( $config['template'] ) ? $config['template'] : 'default';
        
        App::uses( 'CakeEmail', 'Network/Email' );
        $email = new CakeEmail();
        $email->config( 'smtp' );
        $email->emailFormat( 'html' );
        $email->template( $config['template'], $config['layout'] );
        
        if( !empty( $config['attachment'] ) ) {
            $email->attachments( $config['attachment'] );
        }
        if( isset( $config['from'] ) ) {
            $email->from( $config['from'] );
        }
        
        $email->viewVars( [ 'data' => $config['data'] ] );
        $email->to( $config['to'] );
        $email->subject( $config['subject'] );
        
        return $email->send();
        */
        return 1;
    }
    
    /**
     * Send email through GP Gateway
     *
     * @param string $to      Example: "muntasir@humaclab.com, mme.ctg@gmail.com"
     * @param string $cc      Example: "muntasir@humaclab.com, mme.ctg@gmail.com"
     * @param string $subject Example: "Impulse: A ticket has been raised"
     * @param string $message Example: "New user registration pending for approval.<br>User Name: " . $user_name . " Mobile No: " . $user_mob . " Email: " . $email . "<br>"
     *
     * @return bool
     */
    public function sendEmailGP( $to, $cc, $subject, $message ) {
        /* Temporary: START */
        //$to = 'muntasir@humaclab.com';
        //$cc = 'nealam@grameenphone.com';
        /* Temporary: END */

        /*
        ini_set( 'SMTP', '192.168.206.171' );
        ini_set( 'sendmail_from', 'Impulse' );
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n" . "CC:" . $cc;
        
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
        <html>
        <head>
            <title></title>
        </head>
        <body>
        ' . $message . '
        </body>
        </html>';
        
        if( ENVIRONMENT == 'local' ) {
            $result = $this->sendEmail( array(
                'to'      => $to,
                'subject' => $subject,
                'data'    => $body,
                'layout'  => 'send_email',
            ) );
            return !empty( $result );
        }
        
        return mail( $to, $subject, $body, $headers );
        */
        return 1;
    }
    
    /**
     * Send SMS
     *
     * @param string $phone   Example: 01775500881
     * @param string $message Example: Hi%20Jueal.%20Update%20on%20it
     *
     * @return array
     */
    public function sendSMS( $phone, $message ) {
        /*
        if( ENVIRONMENT == 'local' ) {
            return array( 'result' => TRUE, 'msgId' => 1 );
        }
        if( empty( $phone ) ) {
            return array( 'success' => FALSE, 'message' => 'Phone number is required.' );
        }
        if( empty( $message ) ) {
            return array( 'success' => FALSE, 'message' => 'Message is required.' );
        }
        
        $lookup = $this->Components->load( 'Lookup' );
        $msgId = time() . $lookup->generateRandomString( 3, 2 );
        $message = urlencode( $message );
        
        /* Temporary: START */
        //$phone = '01711506415';
        /* Temporary: END */

        /*
        $url = "http://192.168.206.65:4444/cpSubscriptionService/Default.aspx?key=GPTECH_eBN3459a&mobileNo=88{$phone}&body={$message}&msg_type=4&send_port=19172&in_Msg_Id=1&out_Msg_Id={$msgId}";
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $output = curl_exec( $ch );
        curl_close( $ch );
        
        $xml = @simplexml_load_string( $output );
        $status = (string)$xml->STATUS;
        
        return array( 'result' => ( $status == 'SUCCESS' ), 'msgId' => $msgId );
        */
        return array( 'result' => 'SUCCESS', 'msgId' => 1 );
    }
    
    /**
     * Get Excel column name from number
     *
     * @param $num
     *
     * @return string
     */
    protected function getExcelColumnName( $num ) {
        $numeric = ( $num - 1 ) % 26;
        $letter = chr( 65 + $numeric );
        $num2 = intval( ( $num - 1 ) / 26 );
        if( $num2 > 0 ) {
            return $this->getExcelColumnName( $num2 ) . $letter;
        }
        else {
            return $letter;
        }
    }
}