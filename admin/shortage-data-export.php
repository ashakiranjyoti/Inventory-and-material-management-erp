<?php
include('includes/dbconnection.php');
include('includes/functions.php');
set_time_limit(0); 
ini_set('memory_limit', '512M'); 
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Shortage_Details.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '<style>';
echo 'body { font-family: Calibri, sans-serif; }';
echo 'table { width: 100%; border-collapse: collapse; }';
echo 'th, td { padding: 8px; border: 1px solid #000; }';
echo 'td { text-align: center; }';
echo '.even { background-color: #E0EBFF; }';
echo '.odd { background-color: #F9F9F9; }';
echo '</style>';
echo '</head>';
echo '<body>';
    // Display the date
    echo '<h2>Shortage Material Report</h2>';
    
echo '<table>';

echo '<tr>
        <th width="10%" style="background-color: #800080; color: #FFFFFF; text-align: center;">S No</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Category</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Type</th>
        <th width="20%" style="background-color: #800080; color: #FFFFFF; text-align: center;">SubType</th>
        <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Minimum Quantity</th>
        <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Rac No</th>
        <th width="15%" style="background-color: #800080; color: #FFFFFF; text-align: center;">Available Quantity</th>
      </tr>';

$query = "SELECT cat, type, subtype_name, min, rac 
          FROM tblsubtype";
$result = mysqli_query($con, $query);

$serial_no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $liveStock = getLiveStock($con, $row['type'], $row['subtype_name']);
    
    // Only include items where available quantity is less than minimum
    if ($liveStock < $row['min']) {
        $row_class = ($serial_no % 2 == 1) ? 'odd' : 'even';
        echo '<tr class="' . $row_class . '">
                <td>' . $serial_no . '</td>
                <td>' . htmlspecialchars($row['cat'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['subtype_name'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['min'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['rac'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($liveStock, ENT_QUOTES, 'UTF-8') . '</td>
              </tr>';
        $serial_no++;
    }
}

echo '</table>';
echo '</body>';
echo '</html>';

mysqli_close($con);
exit();
?>
