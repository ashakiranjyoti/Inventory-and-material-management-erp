<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");


// Fetch logged-in user's name
if (isset($_SESSION['name'])) {
    $issuedBy = $_SESSION['name'];
} else {
    $issuedBy = ''; // Set default value if session name is not set
}


function getAvailableQuantity($con, $subtype)
{
    $queryInward = "SELECT COALESCE(SUM(quantity), 0) AS total_inward FROM tblinward WHERE subtype = '$subtype'";
    $resultInward = mysqli_query($con, $queryInward);
    $rowInward = mysqli_fetch_assoc($resultInward);
    $totalInward = $rowInward['total_inward'];

    $queryOutward = "SELECT COALESCE(SUM(quantity), 0) AS total_outward FROM tbloutward WHERE subtype = '$subtype'";
    $resultOutward = mysqli_query($con, $queryOutward);
    $rowOutward = mysqli_fetch_assoc($resultOutward);
    $totalOutward = $rowOutward['total_outward'];

    $availableQuantity = $totalInward - $totalOutward;

    return $availableQuantity;
}


if (isset($_POST['submit'])) {
    $eid = $_GET['editid'];
    $req_name = $_POST['req_name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $subtype = $_POST['subtype'];
    $available_quantity = $_POST['available_quantity'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $purpose = $_POST['purpose'];
    $des = $_POST['des'];
    $status = $_POST['status'];
    $issuedby = $_POST['issuedby'];
    $time = $_POST['time'];

    $available_quantity = getAvailableQuantity($con, $subtype);

    if ($quantity <= $available_quantity) {
        $query = mysqli_query($con, "UPDATE tblreq SET req_name='$req_name', category='$category', type='$type', subtype='$subtype', available_quantity='$available_quantity', quantity='$quantity', unit='$unit', purpose='$purpose', des='$des', status='$status', issuedby='$issuedby', time='$time' WHERE sno='$eid'");

        if ($query) {
            $new_available_quantity = $available_quantity - $quantity;
            mysqli_query($con, "UPDATE tblinward SET available_quantity = '$new_available_quantity' WHERE subtype = '$subtype'");
            echo "<script>alert('Detail Updated');</script>";
            echo "<script>window.location.href ='req-manage.php'</script>";
        } else {
            $error = mysqli_error($con); // Get the error message from MySQL
            echo "<script>alert('Something Went Wrong. Please try again. Error: " . $error . "');</script>";
        }
    } else {
        echo "<script>alert('Quantity to be issued exceeds available quantity');</script>";
    }
}

$eid = $_GET['editid'];
$ret = mysqli_query($con, "SELECT * FROM tblinward WHERE sno='$eid'");
$row = mysqli_fetch_array($ret);

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Requirement </title>
    <!-- <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png"> -->
    <link rel="icon" type="image/png" href="images/logo-Inventory Management System.png"/>
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>
    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="req-manage.php">Requirement</a></li>
                                <li class="active">Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Update Requirement</strong>
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <?php
                                $cid = $_GET['editid'];
                                $ret = mysqli_query($con, "select * from  tblreq where sno='$cid'");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {

                                ?>

                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="req_name" class="form-control-label">Name</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="req_name" name="req_name" class="form-control" value="<?php echo $row['req_name']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="category" class="form-control-label">Category</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="category" id="category" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                $query_category = mysqli_query($con, "SELECT * FROM tblcat");
                                                while ($row_category = mysqli_fetch_array($query_category)) {
                                                    $selected = ($row_category['cat_name'] == $row['category']) ? 'selected' : '';
                                                    echo "<option value='" . $row_category['cat_name'] . "' " . $selected . ">" . $row_category['cat_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="type" class="form-control-label">Select Type</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="type" id="type" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                // Populate types based on the selected category
                                                $selected_category = $row['category'];
                                                $query_type = mysqli_query($con, "SELECT * FROM tbltype WHERE cat = '$selected_category'");
                                                while ($row_type = mysqli_fetch_array($query_type)) {
                                                    $selected = ($row_type['type_name'] == $row['type']) ? 'selected' : '';
                                                    echo "<option value='" . $row_type['type_name'] . "' " . $selected . ">" . $row_type['type_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Subtype dropdown -->
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="subtype" class="form-control-label">Select Subtype</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="subtype" id="subtype" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                // Populate subtypes based on the selected type
                                                $selected_type = $row['type'];
                                                $query_subtype = mysqli_query($con, "SELECT * FROM tblsubtype WHERE type = '$selected_type'");
                                                while ($row_subtype = mysqli_fetch_array($query_subtype)) {
                                                    $selected = ($row_subtype['subtype_name'] == $row['subtype']) ? 'selected' : '';
                                                    echo "<option value='" . $row_subtype['subtype_name'] . "' " . $selected . ">" . $row_subtype['subtype_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Available Quantity</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="available_quantity" name="available_quantity" class="form-control" readonly placeholder="Available Quantity" required="true" value="<?php echo $row['available_quantity']; ?>"></div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Quantity</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" required="true" value="<?php echo $row['quantity']; ?>"></div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="unit" class="form-control-label">Select Unit</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="unit" id="unit" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                $query_unit = mysqli_query($con, "SELECT * FROM unit");
                                                while ($row_unit = mysqli_fetch_array($query_unit)) {
                                                    $selected = ($row_unit['unit_name'] == $row['unit']) ? 'selected' : '';
                                                    echo "<option value='" . $row_unit['unit_name'] . "' " . $selected . ">" . $row_unit['unit_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="purpose" class="form-control-label">Purpose</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="purpose" id="purpose" class="form-control" required>
                                                <option value="">---select---</option>
                                                <option value="Assembly" <?php if ($row['purpose'] == 'Assembly') echo 'selected'; ?>>Assembly</option>
                                                <option value="Production" <?php if ($row['purpose'] == 'Production') echo 'selected'; ?>>Production</option>
                                                <option value="Design" <?php if ($row['purpose'] == 'Design') echo 'selected'; ?>>Design</option>
                                                <option value="Sample" <?php if ($row['purpose'] == 'Sample') echo 'selected'; ?>>Sample</option>
                                                <option value="Other" <?php if ($row['purpose'] == 'Other') echo 'selected'; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Description</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="des" name="des" class="form-control" placeholder="Description" required="true" value="<?php echo $row['des']; ?>"></div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="status" class="form-control-label">Status</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="PENDING" <?php if ($row['status'] == 'PENDING') echo 'selected'; ?>>PENDING</option>
                                                <option value="GIVEN" <?php if ($row['status'] == 'GIVEN') echo 'selected'; ?>>GIVEN</option>
                                                <option value="NOT AVAILABLE" <?php if ($row['status'] == 'NOT AVAILABLE') echo 'selected'; ?>>NOT AVAILABLE</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="issuedby" class="form-control-label">Issued By</label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" id="issuedby" name="issuedby" class="form-control" value="<?php echo $issuedBy; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Date & Time</label></div>
                                        <div class="col-12 col-md-9"><input type="datetime-local" id="time" name="time" class="form-control" placeholder="Date & Time" required="true" value="<?php echo $row['time']; ?>"></div>
                                    </div>

                                <?php } ?>

                                <p style="text-align: center;"> <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/update.png" style="width:90px;height:50px;"></button></p>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php include_once('includes/footer.php'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category').change(function() {
                var category = $(this).val();
                if (category) {
                    $.ajax({
                        type: 'POST',
                        url: 'get-tp.php',
                        data: {
                            category: category
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#type').empty().append('<option value="">---select---</option>');
                            $.each(response, function(index, type) {
                                $('#type').append('<option value="' + type + '">' + type + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' - ' + error);
                        }
                    });
                } else {
                    $('#type').empty().append('<option value="">---select---</option>');
                    $('#subtype').empty().append('<option value="">---select---</option>'); // Clear subtype dropdown if needed
                }
            });

            $('#type').change(function() {
                var type = $(this).val();
                if (type) {
                    $.ajax({
                        type: 'POST',
                        url: 'get-stp.php', // Replace with your PHP script to fetch subtypes based on type
                        data: {
                            type: type
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#subtype').empty().append('<option value="">---select---</option>');
                            $.each(response, function(index, subtype) {
                                $('#subtype').append('<option value="' + subtype.subtype_name + '">' + subtype.subtype_name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' - ' + error);
                        }
                    });
                } else {
                    $('#subtype').empty().append('<option value="">---select---</option>');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#subtype').change(function() {
                var subtype = $(this).val();
                if (subtype) {
                    $.ajax({
                        type: 'POST',
                        url: 'get_available_quantity.php', // Adjust URL as needed
                        data: {
                            subtype: subtype
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#available_quantity').val(response.available_quantity);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                        }
                    });
                } else {
                    $('#available_quantity').val('');
                }
            });

            // Trigger change event on page load to initialize available quantity
            $('#subtype').trigger('change');
        });
    </script>

</body>

</html>