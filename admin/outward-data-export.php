<?php
include('includes/dbconnection.php');
set_time_limit(0);
ini_set('memory_limit', '512M');
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Outward_Details.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '<style>';
echo 'body { font-family: Calibri, sans-serif; }';
echo 'h3 { text-align: center; }'; // Center-align heading
echo 'p { text-align: center; margin-bottom: 20px; }'; // Center-align paragraph and add margin
echo 'table { width: 100%; border-collapse: collapse; }';
echo 'th, td { padding: 8px; border: 1px solid #000; }';
echo 'td { text-align: center; }';
echo '.even { background-color: #E0EBFF; }';
echo '.odd { background-color: #F9F9F9; }';
echo '</style>';
echo '</head>';
echo '<body>';

$fdate = $_GET['date']; 

// Display the heading and date
echo '<h3>Outward Details Report</h3>';
echo '<p>Date: ' . htmlspecialchars($fdate, ENT_QUOTES, 'UTF-8') . '</p>';

echo '<table>';

echo '<tr>
        <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">S No</th>
        <th width="30%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Vendor Name</th>
        <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Category</th>
        <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Type</th>
        <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Subtype</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Avail Qty</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Quantity</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Location</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Status</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Unit</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Bill No</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Challan No</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Purpose</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Description</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">IssuedBy</th>
      </tr>';

$query = "SELECT sno, vendor_name, category, type, subtype, available_quantity, quantity, unit, billno, challen, purpose, des, issuedby, mat_loc, mat_status 
          FROM tbloutward 
          WHERE DATE(time) = '$fdate'";
$result = mysqli_query($con, $query);

$row_count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $row_class = ($row_count % 2 == 1) ? 'even' : 'odd';
    echo '<tr class="' . $row_class . '">
            <td>' . htmlspecialchars($row_count + 1, ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['vendor_name'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['subtype'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['available_quantity'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['mat_loc'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['mat_status'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['unit'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['billno'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['challen'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['purpose'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['des'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($row['issuedby'], ENT_QUOTES, 'UTF-8') . '</td>
          </tr>';
    $row_count++;
}

echo '</table>';
echo '</body>';
echo '</html>';

mysqli_close($con);
exit();
?>
