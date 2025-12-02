<?php

include('includes/dbconnection.php');

$query = mysqli_query($con, "SELECT cat_name FROM tblcat");

if (!$query) {
    echo "Error fetching categories: " . mysqli_error($con);
    exit();
}

$categories = array();
while ($row = mysqli_fetch_array($query)) {
    $categories[] = $row['cat_name'];
}

echo json_encode($categories);
mysqli_close($con);
?>
