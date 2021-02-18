<?php
define( 'PROJECT_TITLE', 'Impulse' );
define( 'PROJECT_NAME', 'Impulse' );

define( 'ENVIRONMENT', 'live' ); /* local, live */

/* Pagination items per page */
define( 'PAGINATION_LIMIT', 10 );

/* Boolean types */
define( 'NO', 0 );
define( 'YES', 1 );

/* Lock Status */
define( 'UNLOCK', 0 );
define( 'LOCK', 1 );

/* Approval Status */
define( 'DENY', 0 );
define( 'APPROVE', 1 );

/* Pending Status */
define( 'PENDING', 0 );

/* Status */
define( 'INACTIVE', 0 );
define( 'ACTIVE', 1 );

/* User role */
define( 'ADMIN', 1 );
define( 'SUPPLIER', 2 );
define( 'TR_CREATOR', 3 );
define( 'TR_VALIDATOR', 4 );
define( 'INVOICE_CREATOR', 5 );
define( 'INVOICE_VALIDATOR', 6 );
define( 'SECURITY', 7 );

/* Stage */
define( 'TR_CREATION_STAGE', 1 );
define( 'SUPPLIER_STAGE', 2 );
define( 'TR_VALIDATION_STAGE', 3 );
define( 'INVOICE_CREATION_STAGE', 4 );
define( 'INVOICE_VALIDATION_STAGE', 5 );

define( 'TR_MIN_DATE', -30 );

Configure::write( 'mainTypes', array( 'AC', 'CW', 'DV', 'EB', 'FM', 'GN', 'PG', 'RF', 'SS' ) );

/* Main Type description */
define( 'AC_TXT', 'AC maintenance' );
define( 'CW_TXT', 'Civil works' );
define( 'DV_TXT', 'DC ventilation system' );
define( 'EB_TXT', 'Electricity Bill Collection' );
define( 'FM_TXT', 'Fiber maintenance & petrolling' );
define( 'GN_TXT', 'Generator maintenance' );
define( 'PG_TXT', 'Portable Generator Running' );
define( 'RF_TXT', 'Refueling of auto Generator' );
define( 'SS_TXT', 'Security Services' );

/* Asset Group description */
define( '3012_TXT', 'SHELTERS-CIVIL WORKS' );
define( '3017_TXT', 'EQUIPMENT COOLING UNITS.AIR COOLER' );
define( '3018_TXT', 'EQUIPMENT COOLING UNITS-VENTILATION SYSTEM' );
define( '3045_TXT', 'SECONDARY POWER AND PLANT-POWER GENERATOR FOR BS' );