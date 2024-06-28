<?php

    /* BARANGAY CLEARANCE */
    if (isset($_POST['clearance_button'])) {
        // Start output buffering
        ob_start();
        
        require('./tcpdf/tcpdf.php');
        
        $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $obj_pdf->SetTitle("DENISSE JOY ARAWAG");
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $obj_pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->setFooterMargin(PDF_MARGIN_FOOTER);
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
        $obj_pdf->setPrintHeader(false);
        $obj_pdf->setPrintFooter(false);
        $obj_pdf->SetAutoPageBreak(TRUE, 10);
        $obj_pdf->SetFont('helvetica', '', 12);
        $obj_pdf->AddPage();

        $content = '';
        $content .= '
        <style>
        * {
            text-align: center;
        }
        </style>
        

            <h1> BARANGAY CLEARANCE </h1>
            <h1>I LOVE YOU VERY MUCH</h1>
        

        ';
        $obj_pdf->writeHTML($content);

        // Set the correct headers for PDF output
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="arawag.pdf"');

        // Output the PDF
        $obj_pdf->Output('arawag.pdf', 'I');

        // Clear the output buffer and turn off output buffering
        
        // THESE 2 ARE IMPORTANT
        ob_end_flush();
        ob_end_clean();

        // Stop further execution of the script
        exit();



    }

?>