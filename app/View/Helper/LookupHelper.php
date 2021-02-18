<?php
App::uses( 'AppHelper', 'View/Helper' );

class LookupHelper extends AppHelper {
    
    public $helpers = array( 'Html' );
    
    /**
     * Show photo if photo uploaded and exists or else show no image
     *
     * @param string $photoFile , $path
     *
     * @return HTML image tag
     */
    public function showPhoto( $photoFile, $path ) {
        $file = !empty( $photoFile ) && file_exists( WWW_ROOT . $path ) ? $path : '/resource/photo_not_available_200x150.png';
        
        return $this->Html->image( $file, array( 'class' => 'img-responsive', 'style' => 'max-width: 100%; margin: 0 auto' ) );
    }
    
    /**
     * Get formatted datetime to show
     *
     * @param string $dateTime
     *
     * @return null|string
     */
    public function showDateTime( $dateTime ) {
        if( empty( $dateTime ) ) {
            return NULL;
        }
        
        return date( 'j-M-y g:i:s A', strtotime( $dateTime ) );
    }
    
    /**
     * Format money to show
     *
     * @param number $amount
     *
     * @return string
     */
    public function formatMoney( $amount ) {
        $amountStr = '';
        if( $amount != '' ) {
            $parts = explode( '.', $amount );
            $parts[1] = substr( $parts[1], 0, 4 );
            if( $parts[1][3] == '0' ) {
                $parts[1] = substr( $parts[1], 0, 3 );
            }
            if( $parts[1][2] == '0' ) {
                $parts[1] = substr( $parts[1], 0, 2 );
            }
            $amountStr = implode( '.', $parts );
        }
        
        return $amountStr;
    }
    
    /**
     * Get checkbox buttons HTML
     *
     * @param array $config
     *
     * @example         array(
     *                  'name' => 'data[DataPackage][supported_types][]',
     *                  'options' => array(
     *                  array( 'value' => 0, 'label' => 'Prepaid', 'id' => 'DataPackageSupportedType0' ),
     *                  array( 'value' => 0, 'label' => 'Postpaid', 'id' => 'DataPackageSupportedType1' ),
     *                  ),
     *                  'value' => array( 0, 1 ),
     *                  )
     * @return string HTML
     */
    public function getCheckboxButtons( $config ) {
        if( empty( $config['options'] ) ) {
            return NULL;
        }
        $html = '';
        foreach( $config['options'] as $opt ) {
            if( count( $config['options'] ) == 1 ) {
                $html .= '<label class="single">
                            <input  type="checkbox"
                                    name="' . $config['name'] . '"
                                    id="' . $opt['id'] . '"
                                    value="' . $opt['value'] . '"
                                    ' . ( in_array( $opt['value'], $config['value'] ) ? 'checked' : '' ) . ' />
                            ' . $opt['label'] . '
                        </label>';
            }
            else {
                $html .= '<label' . ( !empty( $config['inline'] ) ? ' class="checkbox-inline"' : '' ) . '>
                            <input  type="checkbox"
                                    name="' . $config['name'] . '"
                                    id="' . $opt['id'] . '"
                                    value="' . $opt['value'] . '"
                                    ' . ( in_array( $opt['value'], $config['value'] ) ? 'checked' : '' ) . ' />
                            ' . $opt['label'] . '
                        </label>';
            }
        }
        
        return $html;
    }
    
    /**
     * Get radio buttons HTML
     *
     * @param array $config
     *
     * @example         array(
     *                  'name' => 'data[User][status]',
     *                  'options' => array(
     *                  array( 'value' => 'A', 'label' => 'Active', 'id' => 'UserStatusA' ),
     *                  array( 'value' => 'I', 'label' => 'Inactive', 'id' => 'UserStatusI' ),
     *                  ),
     *                  'value' => 'A',
     *                  )
     * @return string
     */
    public function getRadioButtons( $config ) {
        if( empty( $config['options'] ) ) {
            return NULL;
        }
        $html = '';
        foreach( $config['options'] as $opt ) {
            $html .= '<label class="radio-inline">
                        <input type="radio" name="' . $config['name'] . '" id="' . $opt['id'] . '" value="' . $opt['value'] . '"' . ( isset( $config['value'] ) && $config['value'] == $opt['value'] ? ' checked' : '' ) . '> ' . $opt['label'] . '
                    </label>';
        }
        
        return $html;
    }
    
    /**
     * Get Supplier name by ID
     */
    public function getSupplierNameById( $id = NULL ) {
        App::import( 'Model', 'Supplier' );
        $objSupplier = new Supplier();
        $supplierArr = $objSupplier->find( 'first', [ 'conditions' => array( 'Supplier.id' => $id ), 'container' => FALSE ] );
        
        return $supplierArr['Supplier']['name'];
    }
    
    /**
     * Decide tab in the ticket list to go back
     *
     * @param array $data
     *
     * @return string
     */
    public function decideTab( &$data ) {
        if( $data['Ticket']['approval_status'] == APPROVE ) {
            return 'approved';
        }
        else if( $data['Ticket']['approval_status'] == DENY ) {
            return 'rejected';
        }
        else if( $data['Ticket']['pending_status'] == PENDING ) {
            return 'pending';
        }
        else if( $data['Ticket']['lock_status'] == LOCK ) {
            return 'locked';
        }
        else {
            return 'assigned';
        }
    }
    
    /**
     * Get MainType from TrClass Name
     *
     * @param string $trClassName
     *
     * @return string
     */
    public function getMainType( $trClassName ) {
        $type = substr( $trClassName, 0, 2 );
        if( in_array( $type, array( 'OD', 'PW' ) ) ) {
            $type = 'CW';
        }
        else if( $type == 'FP' ) {
            $type = 'FM';
        }
        else if( in_array( $type, array( 'GA', 'GE', 'GF', 'GP', 'GS', 'GT', 'GW', 'RP', 'RS', 'RT', 'RW' ) ) ) {
            $type = 'GN';
        }
        else if( substr( $trClassName, 0, 3 ) == 'RFE' && substr( $trClassName, 0, 4 ) != 'RFEX' ) {
            $type = 'GN';
        }
        
        return $type;
    }
}