<?php

require_once('TCPDF-main/tcpdf.php');
include('includes/dbconnection.php');
include('includes/functions.php');


set_time_limit(0);
ini_set('memory_limit', '512M');

class MYPDF extends TCPDF {

    public function ColoredTable($header, $data) {
        $this->SetFillColor(51, 122, 183); 
        $this->SetTextColor(255); 
        $this->SetDrawColor(128, 0, 0); 
        $this->SetLineWidth(0.3); 
        // $this->SetFont('', 'B'); 
        $this->SetFont('helvetica', 'B', 9); 

        $w = array(10, 60, 60, 60, 30, 30, 30); // Column widths

        // Header
        foreach ($header as $i => $heading) {
            $this->Cell($w[$i], 10, $heading, 1, 0, 'C', 1);
        }
        $this->Ln();

        $this->SetFillColor(224, 235, 255); 
        $this->SetTextColor(0); 
        $this->SetFont('', '', 9);

        $fill = 0;
        foreach ($data as $row) {
            $rowHeight = 0;

            // Calculate the height needed for each cell in the row
            foreach ($row as $index => $column) {
                $cellHeight = $this->getStringHeight($w[$index], htmlspecialchars($column, ENT_QUOTES, 'UTF-8'));
                $rowHeight = max($rowHeight, $cellHeight); // Get the maximum height for the row
            }

            // Output the row with the calculated height
            foreach ($row as $index => $column) {
                $this->MultiCell($w[$index], $rowHeight, htmlspecialchars($column, ENT_QUOTES, 'UTF-8'), 1, 'L', $fill, 0, '', '', true);
            }
            $this->Ln(); // Move to the next line based on the row height
            $fill = !$fill; 

            // Check for page break
            if ($this->GetY() + $rowHeight > $this->getPageHeight() - $this->getBreakMargin()) {
                $this->AddPage();
                // Reprint the header on the new page
                foreach ($header as $i => $heading) {
                    $this->Cell($w[$i], 10, $heading, 1, 0, 'C', 1);
                }
                $this->Ln();
            }
        }

        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Shortage Details Report');
$pdf->SetSubject('Shortage Details Report');
$pdf->SetKeywords('TCPDF, PDF, Shortage, Details, Report');

$pdf->SetHeaderData('images/Inventory Management Systemlogo.png', PDF_HEADER_LOGO_WIDTH, 'Shortage Details Report', '');

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins('5', '22', '5');
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('helvetica', '', 8);

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Shortage Details Report', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 8);

$header = array('S.NO', 'Category', 'Type', 'SubType', 'Min Qty', 'Rac No', 'Avail Qty');

$query = "SELECT cat, type, subtype_name, min, rac 
          FROM tblsubtype";
$result = mysqli_query($con, $query);

$data = array();
$serial_no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $liveStock = getLiveStock($con, $row['type'], $row['subtype_name']);
    
    // Only include items where available quantity is less than minimum
    if ($liveStock < $row['min']) {
        $data[] = array(
            $serial_no,  
            htmlspecialchars($row['cat'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['subtype_name'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['min'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($row['rac'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($liveStock, ENT_QUOTES, 'UTF-8')
        );
        $serial_no++; 
    }
}

$pdf->ColoredTable($header, $data);

$pdf->Output('Shortage_Details_Report.pdf', 'I');

mysqli_close($con);

exit();
?>
