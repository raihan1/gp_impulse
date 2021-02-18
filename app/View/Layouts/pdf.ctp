<?php
require_once( APP . 'Vendor/html2pdf-4.5.1/vendor/autoload.php' );
try {
    $html2pdf = new HTML2PDF( ( !empty( $orientation ) ? $orientation : 'L' ), 'A4', 'en' );
    $html2pdf->pdf->SetDisplayMode( 'fullpage' );
    $html2pdf->writeHTML( $content_for_layout );
    $html2pdf->Output( $fileName, 'D' );
}
catch( HTML2PDF_exception $e ) {
    echo $e;
    exit;
}