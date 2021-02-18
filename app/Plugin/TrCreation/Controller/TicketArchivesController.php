<?php

/**
 * Created by IntelliJ IDEA.
 * User: Sayeed
 * Date: 17-May-17
 * Time: 6:27 PM
 */
class TicketArchivesController extends  TrCreationAppController
{

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
     * Ticket List
     */
    public function index() {
        $this->set( 'title_for_layout', 'Ticket Archive List' );
    }

    /**
        * Ticket list actions via ajax datatable
        *
        * @param  string $type
        */
       public function data( $type ) {

           $this->loadModel( 'TicketArchive' );
           $result = array();


           //</editor-fold>

           //<editor-fold desc="Settings" defaultstate="collapsed">
           $columns = array(
               1 => array( 'modelName' => 'TicketArchive.id', 'searchName' => 'id', 'searchType' => '%like%' ),
               2 => array( 'modelName' => 'User.name', 'searchName' => 'name', 'searchType' => '%like%' ),
               3 => array( 'modelName' => 'TicketArchive.supplier_category', 'searchName' => 'supplier_category', 'searchType' => '%like%' ),
               4 => array( 'modelName' => 'TicketArchive.site', 'searchName' => 'site', 'searchType' => '%like%' ),
               5 => array( 'modelName' => 'TicketArchive.asset_group', 'searchName' => 'asset_group', 'searchType' => '%like%' ),
               6 => array( 'modelName' => 'TicketArchive.asset_number', 'searchName' => 'asset_number', 'searchType' => '%like%' ),
               7 => array( 'modelName' => 'TicketArchive.tr_class', 'searchName' => 'tr_class', 'searchType' => '%like%' ),
               8 => array( 'modelName' => 'TicketArchive.received_at_supplier', 'searchName' => 'received_at_supplier', 'searchType' => 'date' ),
           );


           $commonConditions = array( 'TicketArchive.sub_center' => $this->loginUser['SubCenter']['sub_center_name'] );
                  if( $type == 'assigned' ) {
                      $conditions = $commonConditions + array( 'TicketArchive.lock_status' => null);
                  }
                  else if( $type == 'locked' ) {
                      $conditions = $commonConditions + array( 'TicketArchive.lock_status' => LOCK, 'TicketArchive.pending_status' => NULL );
                  }
                  else if( $type == 'pending' ) {
                      $conditions = $commonConditions + array( 'TicketArchive.pending_status' => PENDING, 'TicketArchive.approval_status' => NULL );
                  }
                  else if( $type == 'approved' ) {
                      $conditions = $commonConditions + array( 'TicketArchive.approval_status' => APPROVE, 'TicketArchive.invoice_id' => 0 );
                  }
                  else if( $type == 'rejected' ) {
                      $conditions = $commonConditions + array( 'TicketArchive.approval_status' => DENY );
                  }



           foreach( $columns as $col ) {
               if( isset( $this->request->data[ $col['searchName'] ] ) && $this->request->data[ $col['searchName'] ] != '' ) {
                   if( $col['searchType'] == '%like%' ) {
                       $conditions["{$col['modelName']} LIKE"] = '%' . $this->request->data[ $col['searchName'] ] . '%';
                   }
                   else if( $col['searchType'] == 'like%' ) {
                       $conditions["{$col['modelName']} LIKE"] = $this->request->data[ $col['searchName'] ] . '%';
                   }
                   else if( $col['searchType'] == 'date' ) {
                       $conditions["DATE( {$col['modelName']} )"] = date( 'Y-m-d', $this->request->data[ $col['searchName'] ] );
                   }
                   else {
                       $conditions["{$col['modelName']}"] = $this->request->data[ $col['searchName'] ];
                   }
               }
           }

           $order = array( 'TicketArchive.id' => 'DESC' );
           if( !empty( $this->request->data['order'][0]['column'] ) ) {
               $order = array( $columns[ $this->request->data['order'][0]['column'] ]['modelName'] => $this->request->data['order'][0]['dir'] );
           }
           //</editor-fold>

           $total = $this->TicketArchive->find( 'count', array( 'contain' => array( 'User.name' ),'conditions' => $conditions ) );

           $data = $this->TicketArchive->find( 'all', array(
               'contain'    => array( 'User.name' ),
               'conditions' => $conditions,
               'limit'      => intval( $this->request->data['length'] ) > 0 ? intval( $this->request->data['length'] ) : $total,
               'offset'     => intval( $this->request->data['start'] ),
               'order'      => $order,
           ) );
//           pr($total);
////           echo $this->element('sql_dump');
//                      die();

           $this->set( array(
               'request' => $this->request->data,
               'result'  => $result,
               'data'    => $data,
               'total'   => $total,
               'type'    => $type,
           ) );
       }


    /**
     * View a ticket details
     *
     * @param integer $trId
     *
     * @throws NotFoundException
     */
    public function view( $trId = NULL ) {
        $this->loadModel( 'TicketArchive' );
        $data = $this->TicketArchive->find( 'first', array(
            'conditions' => array( 'TicketArchive.id' => $trId, 'TicketArchive.sub_center' => $this->loginUser['SubCenter']['sub_center_name'] ),
            'contain'    => array(
                'TrServiceArchive' => array(
                    'conditions' => array( 'TrServiceArchive.status' => ACTIVE, 'TrServiceArchive.is_deleted' => NO ),
                ),
            ),
        ) );
        if( empty( $data ) ) {
            throw new NotFoundException( 'Invalid Ticket ID.' );
        }

        if( $data['TicketArchive']['lock_status'] == NULL ) {
            $type = 'assigned';
        }
        else if( $data['TicketArchive']['lock_status'] == LOCK && $data['TicketArchive']['pending_status'] == NULL ) {
            $type = 'locked';
        }
        else if( $data['TicketArchive']['pending_status'] == PENDING && $data['TicketArchive']['approval_status'] == NULL ) {
            $type = 'pending';
        }
        else if( $data['TicketArchive']['approval_status'] == APPROVE && $data['TicketArchive']['invoice_id'] == 0 ) {
            $type = 'approved';
        }
        else if( $data['TicketArchive']['approval_status'] == DENY ) {
            $type = 'rejected';
        }

        $this->set( array(
                        'data'             => $data,
                        'type'             => $type,
                        'title_for_layout' => 'Ticket Details',
                    ) );
    }
}