<?php
include('includes/dbconnection.php'); 

if (isset($_POST['type'])) {
    $type = $_POST['type'];
    $query = mysqli_query($con, "SELECT type FROM tblsubtype WHERE subtype_name = '$type'");
    $subtypes = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $subtypes[] = $row;
    }
    echo json_encode($subtypes);
}
?>
