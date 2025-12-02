<?php
include('includes/dbconnection.php'); 

if (isset($_POST['category'])) {
    $category = $_POST['category'];

    $category = mysqli_real_escape_string($con, $category);

    $query = "SELECT type_name FROM tbltype WHERE cat = '$category'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $types = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $types[] = $row['type_name'];
        }
        echo json_encode($types);
    } else {
        echo json_encode(array('error' => 'Failed to fetch types.'));
    }
} else {
    echo json_encode(array('error' => 'Category not specified.'));
}
?>
