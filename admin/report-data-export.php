<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include('includes/functions.php'); // Include functions.php

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['type'], $_GET['subtype'], $_GET['fromdate'], $_GET['todate'])) {
    $type = $_GET['type'];
    $subtype = $_GET['subtype'];
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];
    
    // Get available stock based on type and subtype
    $availableQuantity = getLiveStock($con, $type, $subtype);

    // SQL query for fetching report data
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

    // Excel headers
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Report_Export.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    echo '<style>
        body { font-family: Calibri, sans-serif; }
        h2, p { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #000; text-align: center; }
        .even { background-color: #E0EBFF; }
        .odd { background-color: #F9F9F9; }
    </style>';
    echo '</head><body>';

    echo '<h2>Report Details</h2>';
    echo '<p>Type: ' . htmlspecialchars($type) . ' | Subtype: ' . htmlspecialchars($subtype) . '</p>';
    echo '<p>From Date: ' . htmlspecialchars($fromdate) . ' | To Date: ' . htmlspecialchars($todate) . '</p>';

    echo '<table>';
    echo '<tr style="background-color: #800080; color: #fff;">
            <th>S.No</th>
            <th>Date</th>
            <th>Status</th>
            <th>Supplier/Vendor</th>
            <th>Category</th>
            <th>Type</th>
            <th>Subtype</th>
            <th>Quantity</th>
            <th>Location</th>
            <th>Mat. Status</th>
            <th>Unit</th>
            <th>Challan No</th>
            <th>Purpose</th>
            <th>Description</th>
            <th>Checked By</th>
          </tr>';

    $row_count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $row_class = ($row_count % 2 == 0) ? 'even' : 'odd';
        echo '<tr class="' . $row_class . '">
                <td>' . ($row_count + 1) . '</td>
                <td>' . $row['time'] . '</td>
                <td>' . $row['status'] . '</td>
                <td>' . $row['supplier_vendor'] . '</td>
                <td>' . $row['category'] . '</td>
                <td>' . $row['type'] . '</td>
                <td>' . $row['subtype'] . '</td>
                <td>' . $row['quantity'] . '</td>
                <td>' . $row['mat_loc'] . '</td>
                <td>' . $row['mat_status'] . '</td>
                <td>' . $row['unit'] . '</td>
                <td>' . $row['challan_no'] . '</td>
                <td>' . ($row['source'] == 'Inward' ? '---' : $row['purpose']) . '</td>
                <td>' . ($row['source'] == 'Inward' ? '---' : $row['des']) . '</td>
                <td>' . $row['check_issue'] . '</td>
              </tr>';
        $row_count++;
    }

    echo '<tr style="font-weight: bold;">
            <td colspan="7" style="text-align: right; background-color: #f0f0f0;">Available Quantity:</td>
            <td style="background-color: #d1ffd1;">' . $availableQuantity . '</td>
          </tr>';
    echo '</table></body></html>';

    mysqli_close($con);
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
