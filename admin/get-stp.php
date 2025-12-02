<?php
include('includes/dbconnection.php');

if (isset($_POST['type'])) {
    $type = $_POST['type'];

    $query = mysqli_query($con, "SELECT * FROM tblsubtype WHERE type = '$type'");
    $subtypes = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $subtypes[] = array(
            'subtype_name' => $row['subtype_name']
        );
    }

    echo json_encode($subtypes);
}
?>
