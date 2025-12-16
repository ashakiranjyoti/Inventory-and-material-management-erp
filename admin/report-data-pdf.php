<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');
include('includes/functions.php');
require_once('TCPDF-main/tcpdf.php');

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['type'], $_GET['subtype'], $_GET['fromdate'], $_GET['todate'])) {
    $type = $_GET['type'];
    $subtype = $_GET['subtype'];
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];
    
    $availableQuantity = getLiveStock($con, $type, $subtype);

    class MYPDF extends TCPDF {
        protected $headerPrinted = false;
        protected $tableStarted = false;
        
        public function Header() {
            if (!$this->headerPrinted && $this->tableStarted) {
                parent::Header();
                $this->headerPrinted = true;
            }
        }
        
        public function ColoredTable($header, $data) {
            $this->tableStarted = true;
            $this->SetFillColor(51, 122, 183); 
            $this->SetTextColor(255); 
            $this->SetDrawColor(128, 0, 0); 
            $this->SetLineWidth(0.3);
            $this->SetFont('helvetica', 'B', 9); 

            $w = array(8, 15, 10, 30, 30, 30, 30, 15, 20,20,15, 15, 15, 15, 15);
            
            // Print header on first page
            $this->PrintTableHeader($header, $w);
    
            $this->SetFillColor(224, 235, 255); 
            $this->SetTextColor(0); 
            $this->SetFont('helvetica', '', 8);

            $fill = 0;
            $firstRow = true;
            
            foreach ($data as $row) {
                $rowHeight = $this->CalculateRowHeight($row, $w);
                
                // Check for page break BEFORE printing row
                if ($this->GetY() + $rowHeight > $this->getPageHeight() - $this->getBreakMargin()) {
                    $this->AddPage('L');
                    $this->headerPrinted = false;
                    $this->PrintTableHeader($header, $w);
                    $fill = 0;
                    $firstRow = true;
                }
                
                // Skip empty rows that might cause blank pages
                if (!empty(implode('', $row))) {
                    $this->PrintTableRow($row, $w, $rowHeight, $fill);
                    $fill = !$fill;
                    $firstRow = false;
                }
            }
        }
        
        protected function PrintTableHeader($header, $w) {
            $this->SetFont('helvetica', 'B', 9);
            foreach ($header as $i => $heading) {
                $this->Cell($w[$i], 7, $heading, 1, 0, 'C', 1);
            }
            $this->Ln();
            $this->headerPrinted = true;
        }
        
        protected function CalculateRowHeight($row, $w) {
            $rowHeight = 0;
            foreach ($row as $i => $column) {
                $cellHeight = $this->getStringHeight($w[$i], $column);
                $rowHeight = max($rowHeight, $cellHeight);
            }
            return max($rowHeight, 8);
        }
        
        protected function PrintTableRow($row, $w, $rowHeight, $fill) {
            foreach ($row as $i => $column) {
                $this->MultiCell(
                    $w[$i], 
                    $rowHeight, 
                    $column, 
                    1, 'L', $fill, 0, '', '', true, 0, false, true, $rowHeight, 'M'
                );
            }
            $this->Ln();
        }
    }

    $pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Item Report - '.$type.' - '.$subtype);
    $pdf->SetSubject('Item Report');
    $pdf->SetKeywords('TCPDF, PDF, Report, Export');

    $pdf->SetHeaderData('images/Inventory Management Systemlogo.png', PDF_HEADER_LOGO_WIDTH, 'Item Report: '.$type.' - '.$subtype, '');
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(5, 25, 5);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(5);
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();

    // Title and filters
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, 'Item Report: '.$type.' / '.$subtype, 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 8);
    $pdf->Cell(0, 6, 'From: '.date('d/m/Y', strtotime($fromdate)).' To: '.date('d/m/Y', strtotime($todate)), 0, 1, 'L');
    $pdf->Ln(2);

    // Header array
    $header = array(
        'SNO', 'Date', 'Status', 'Sup/Ven', 'Category', 'Type', 'Subtype', 
        'Qty','Location','Mat.Status', 'Unit', 'Ch No', 'Purpose', 'Des', 'Checked'
    );

    // Query execution and data processing
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
              WHERE type = '$type' AND subtype = '$subtype' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
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
              WHERE type = '$type' AND subtype = '$subtype' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            ORDER BY time ASC";

    $result = mysqli_query($con, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $data = array();
    $row_count = 0;

    // Function to truncate long text
    $truncate = function($text, $length = 60) {
        return (strlen($text) > $length) ? substr($text, 0, $length-3).'...' : $text;
    };

    while ($row = mysqli_fetch_assoc($result)) {
        $row_count++;
        $date = date('d/m/Y', strtotime($row['time']));
        
        // Skip empty rows that might cause blank pages
        if (!empty($row['supplier_vendor']) || !empty($row['quantity']) || !empty($row['check_issue'])) {
            $data[] = array(
                $row_count,
                $date,
                $truncate($row['status']),
                $truncate($row['supplier_vendor']),
                $row['category'],
                $row['type'],
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
    }

    $pdf->ColoredTable($header, $data);

    // Available Quantity
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Available Quantity: ' . $availableQuantity, 0, 1, 'R');

    $pdf->Output('Item_Report_'.$type.'_'.$subtype.'_'.date('Ymd').'.pdf', 'I');
    mysqli_close($con);
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
