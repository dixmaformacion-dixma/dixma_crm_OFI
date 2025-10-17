<?php

    require_once "../vendor/autoload.php";

    ob_start();

    require "../crearContrato3.php";

    $prueba = ob_get_status();




    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // add a page
    $pdf->AddPage();

    // set some text to print
    $txt = "<div style='border: #b0d588 2px solid; border-radius: 6px;'>";

    // print a block of text using Write()
    $pdf->Write(0, $prueba, '', 0, 'C', true, 0, false, false, 0);

    // ---------------------------------------------------------

    //Close and output PDF document
    $pdf->Output('example_002.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+




?>