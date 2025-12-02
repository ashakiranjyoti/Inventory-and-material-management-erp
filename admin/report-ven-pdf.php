<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

require_once('TCPDF-main/tcpdf.php');

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['vendor_name'], $_GET['fromdate'], $_GET['todate'])) {
    $vendor_name = $_GET['vendor_name'];
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];

    // Query to fetch data
    $query = "SELECT 
                'Inward' AS source,
                sno,
                status,
                supplier AS supplier_vendor,
                category,
                type,
                subtype,
                '' AS available_quantity,
                quantity,
                mat_loc,
                mat_status,
                unit,
                billno AS challan_no,
                '' AS purpose,
                '' AS des,
                checkedby AS check_issue,
                time
              FROM tblinward
              WHERE supplier = '$vendor_name' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            UNION
              SELECT 
                'Outward' AS source,
                sno,
                status,
                vendor_name AS supplier_vendor,
                category,
                type,
                subtype,
                available_quantity,
                quantity,
                mat_loc,
                mat_status,
                unit,
                billno,
                purpose,
                des,
                issuedby AS check_issue,
                time
              FROM tbloutward
              WHERE vendor_name = '$vendor_name' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            ORDER BY time ASC";

    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    class MYPDF extends TCPDF {
        public function ColoredTable($header, $data) {
            $this->SetFillColor(51, 122, 183); 
            $this->SetTextColor(255); 
            $this->SetDrawColor(128, 0, 0); 
            $this->SetLineWidth(0.3);
            $this->SetFont('helvetica', 'B', 9); 

            // Optimized column widths
            $w = array(8, 17, 11, 32, 32, 32, 32, 10, 20,20,10, 15, 17, 17, 15);
            
            // Header
            foreach ($header as $i => $heading) {
                $this->Cell($w[$i], 7, $heading, 1, 0, 'C', 1);
            }
            $this->Ln();
    
            $this->SetFillColor(224, 235, 255); 
            $this->SetTextColor(0); 
            $this->SetFont('helvetica', '', 8);

            $fill = 0;
            $firstPage = true;
            
            foreach ($data as $row) {
                // Check if we need a new page BEFORE adding the row
                if ($this->GetY() + 10 > $this->getPageHeight() - $this->getBreakMargin()) {
                    $this->AddPage('L');
                    $this->SetFont('helvetica', 'B', 9);
                    foreach ($header as $i => $heading) {
                        $this->Cell($w[$i], 7, $heading, 1, 0, 'C', 1);
                    }
                    $this->Ln();
                    $this->SetFont('helvetica', '', 8);
                    $fill = 0; // Reset fill for new page
                }
                
                $rowHeight = 0;
                foreach ($row as $i => $column) {
                    $cellHeight = $this->getStringHeight($w[$i], $column);
                    $rowHeight = max($rowHeight, $cellHeight);
                }
                $rowHeight = max($rowHeight, 5); // Minimum height
                
                foreach ($row as $i => $column) {
                    $this->MultiCell($w[$i], $rowHeight, $column, 1, 'L', $fill, 0, '', '', true, 0, false, true, $rowHeight, 'M');
                }
                $this->Ln();
                $fill = !$fill;
            }
        }
    }

    $pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Vendor Report - '.$vendor_name);
    $pdf->SetSubject('Vendor Report');
    $pdf->SetKeywords('TCPDF, PDF, Vendor, Report');

    $pdf->SetHeaderData('images/Inventory Management Systemlogo.png', PDF_HEADER_LOGO_WIDTH, 'Vendor Report: '.$vendor_name, '');

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(5, 22, 5);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(5);
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();

    // Title and filters
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, 'Vendor Report: '.$vendor_name, 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 6, 'From: '.date('d/m/Y', strtotime($fromdate)).' To: '.date('d/m/Y', strtotime($todate)), 0, 1, 'L');
    $pdf->Ln(2);

    // Header array
    $header = array(
        'S.NO', 'Date', 'Status', 'Vendor', 'Category', 'Type', 'Subtype', 'Qty', 'Location', 'Mat.Status', 'Unit',
        'Ch No', 'Purpose', 'Des', 'Checked'
    );

    $data = array();
    $row_count = 0;

    $truncate = function($text, $length = 100) {
        return (strlen($text) > $length) ? substr($text, 0, $length-3).'...' : $text;
    };

    while ($row = mysqli_fetch_assoc($result)) {
        $row_count++;
        $date = date('d/m/Y', strtotime($row['time']));
        
        $data[] = array(
            $row_count,
            $date,
            $row['status'],
            $truncate($row['supplier_vendor']),
            // $truncate($row['category']),
            $row['category'],
            // $truncate($row['type']),
            $row['type'],
            // $truncate($row['subtype']),
            $row['subtype'],
            $row['quantity'],
            $row['mat_loc'],
            $row['mat_status'],
            $row['unit'],
            $truncate($row['challan_no'], 10),
            ($row['source'] == 'Inward') ? '---' : $truncate($row['purpose']),
            ($row['source'] == 'Inward') ? '---' : $truncate($row['des']),
            $truncate($row['check_issue'])
        );
    }

    $pdf->ColoredTable($header, $data);
    $pdf->Output('Vendor_Report_'.$vendor_name.'_'.date('Ymd').'.pdf', 'I');

    mysqli_close($con);
    exit();
} else {
    header("Location: dashboard.php"); 
    exit();
}
?>