<?php
include('includes/dbconnection.php'); 


function getLiveStock($con, $type, $subtype) {
    
    $type = mysqli_real_escape_string($con, $type);
    $subtype = mysqli_real_escape_string($con, $subtype);

   
    $inwardQuery = "SELECT SUM(quantity) AS total_inward FROM tblinward WHERE type = '$type' AND subtype = '$subtype'";
    $inwardResult = mysqli_query($con, $inwardQuery);
    $inwardData = mysqli_fetch_assoc($inwardResult);
    $totalInward = floatval($inwardData['total_inward']);

   
    $outwardQuery = "SELECT SUM(quantity) AS total_outward FROM tbloutward WHERE type = '$type' AND subtype = '$subtype'";
    $outwardResult = mysqli_query($con, $outwardQuery);
    $outwardData = mysqli_fetch_assoc($outwardResult);
    $totalOutward = floatval($outwardData['total_outward']);

    
    return $totalInward - $totalOutward;
}

function getLiveStockForDashboard($con) {
    $count_shortage = 0;
    $query_shortage = mysqli_query($con, "SELECT * FROM tblsubtype");

    while ($row = mysqli_fetch_assoc($query_shortage)) {
        $liveStock = getLiveStock($con, $row['type'], $row['subtype_name']);
        if ($liveStock < $row['min']) {
            $count_shortage++;
        }
    }

    return $count_shortage;
}
?>
