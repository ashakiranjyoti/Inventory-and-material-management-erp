<?php
include('includes/dbconnection.php');

if (isset($_POST['category'])) {
    $category = $_POST['category'];

    $query = mysqli_query($con, "SELECT * FROM tbltype WHERE cat = '$category'");
    $types = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $types[] = $row['type_name'];
    }

    echo json_encode($types);
}
?>
