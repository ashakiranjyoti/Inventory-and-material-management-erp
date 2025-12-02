<?php
include('includes/dbconnection.php');

if (isset($_POST['subtype']) && isset($_POST['type'])) {
    $subtype = $_POST['subtype'];
    $type = $_POST['type'];

    // Calculate total inward
    $queryInward = "SELECT COALESCE(SUM(quantity), 0) AS total_inward FROM tblinward WHERE type = '$type' AND subtype = '$subtype'";
    $resultInward = mysqli_query($con, $queryInward);
    $rowInward = mysqli_fetch_assoc($resultInward);
    $totalInward = $rowInward['total_inward'];

    // Calculate total outward
    $queryOutward = "SELECT COALESCE(SUM(quantity), 0) AS total_outward FROM tbloutward WHERE type = '$type' AND subtype = '$subtype'";
    $resultOutward = mysqli_query($con, $queryOutward);
    $rowOutward = mysqli_fetch_assoc($resultOutward);
    $totalOutward = $rowOutward['total_outward'];

    // Calculate available quantity
    $available_quantity = $totalInward - $totalOutward;

    echo json_encode(['available_quantity' => $available_quantity]);
} else {
    echo json_encode(['available_quantity' => 0]);
}
?>
