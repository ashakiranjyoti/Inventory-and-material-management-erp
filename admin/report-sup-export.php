<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['supplier'], $_GET['fromdate'], $_GET['todate'])) {
    $supplier = $_GET['supplier'];
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];

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
              WHERE supplier = '$supplier' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
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
              WHERE vendor_name = '$supplier' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            ORDER BY time ASC";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    // Excel output headers
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Supplier_Report_Export.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    echo '<style>';
    echo 'body { font-family: Calibri, sans-serif; }';
    echo 'h2 { text-align: center; }'; // Center-align heading
    echo 'p { text-align: center; margin-bottom: 20px; }'; // Center-align paragraph
    echo 'table { width: 100%; border-collapse: collapse; }';
    echo 'th, td { padding: 8px; border: 1px solid #000; }';
    echo 'td { text-align: center; }';
    echo '.even { background-color: #E0EBFF; }';
    echo '.odd { background-color: #F9F9F9; }';
    echo '</style>';
    echo '</head>';
    echo '<body>';

    // Display the date range and report title
    echo '<h2>Supplier Report Details</h2>';
    echo '<p>From Date: ' . htmlspecialchars($fromdate, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>To Date: ' . htmlspecialchars($todate, ENT_QUOTES, 'UTF-8') . '</p>';

    echo '<table>';

    echo '<tr>
            <th width="5%" style="background-color: #800080; color: #FFFFFF; text-align: center;">S.No</th>
            <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Date</th>
            <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Status</th>
            <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Vendor</th>
            <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Category</th>
            <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Type</th>
            <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Subtype</th>
            <th width="5%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Quantity</th>
            <th width="5%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Location</th>
            <th width="5%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Mat.Status</th>
            <th width="5%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Unit</th>
            <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Challan No</th>
            <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Checked By</th>
          </tr>';

    $row_count = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $row_class = ($row_count % 2 == 0) ? 'even' : 'odd';
        echo '<tr class="' . $row_class . '">
                <td>' . htmlspecialchars($row_count + 1, ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['time'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['supplier_vendor'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['subtype'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['mat_loc'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['mat_status'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['unit'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['challan_no'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['check_issue'], ENT_QUOTES, 'UTF-8') . '</td>
              </tr>';
        $row_count++;
    }

    echo '</table>';

    echo '</body>';
    echo '</html>';

    mysqli_close($con);
    exit();
} else {
    header("Location: dashboard.php"); // Redirect if necessary parameters not provided
    exit();
}
?>
