<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

$checkedBy = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$currentDateTime = date('Y-m-d H:i:s');

$eid = $_GET['editid'];

// Handle Form Submission
if (isset($_POST['submit'])) {
    $supplier = $_POST['supplier'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $subtype = $_POST['subtype'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $billno = $_POST['billno'];
    $mat_loc = $_POST['mat_loc'];
    $mat_status = $_POST['mat_status'];
    $challen = $_POST['challen'];
    $checkedby = $_POST['checkedby'];
    $time = $_POST['time'];

    // Update tblinward only
    $query = "UPDATE tblinward 
              SET supplier='$supplier', category='$category', type='$type', subtype='$subtype', 
                  quantity='$quantity', unit='$unit', billno='$billno', challen='$challen', 
                  checkedby='$checkedby',  mat_loc='$mat_loc', mat_status='$mat_status', time='$time' 
              WHERE sno='$eid'";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Inward record updated successfully.');</script>";
        echo "<script>window.location.href='inward-manage.php';</script>";
        exit();
    } else {
        echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
        echo "<script>window.location.href='inward-manage.php';</script>";
        exit();
    }
}

// Fetch Record for Form Display
$ret = mysqli_query($con, "SELECT * FROM tblinward WHERE sno='$eid'");
$row = mysqli_fetch_array($ret);
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Inward </title>
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
                                <li><a href="inward-manage.php">Inward</a></li>
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
                            <strong>Update Inward</strong>
                            <span style="float:right;">
                                <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                    <a href="inward-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <?php
                                $cid = $_GET['editid'];
                                $ret = mysqli_query($con, "select * from  tblinward where sno='$cid'");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {

                                ?>

                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="supplier" class="form-control-label">Supplier</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="supplier" id="supplier" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                $query_supplier = mysqli_query($con, "SELECT * FROM tblsupplier");
                                                while ($row_supplier = mysqli_fetch_array($query_supplier)) {
                                                    $selected = ($row_supplier['name'] == $row['supplier']) ? 'selected' : '';
                                                    echo "<option value='" . $row_supplier['name'] . "' " . $selected . ">" . $row_supplier['name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Include necessary HTML structure, PHP logic, and form elements -->
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

                                    <!-- Type dropdown -->
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
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Bill No</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="billno" name="billno" class="form-control" placeholder="Bill no" required="true" value="<?php echo $row['billno']; ?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">challan No</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="challen" name="challen" class="form-control" placeholder="challan no" required="true" value="<?php echo $row['challen']; ?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Location</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="mat_loc" name="mat_loc" class="form-control" placeholder="Location" required="true" value="<?php echo $row['mat_loc']; ?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Status</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="mat_status" name="mat_status" class="form-control" placeholder="Status" required="true" value="<?php echo $row['mat_status']; ?>"></div>
                                    </div>
                                    <?php if (!empty($row['checkedby'])) : ?>
                                        <div class="row form-group">
                                            <div class="col col-md-3"><label for="checkedby" class="form-control-label">Checked By</label></div>
                                            <div class="col-12 col-md-9">
                                                <input type="text" id="checkedby" name="checkedby" class="form-control" value="<?php echo $row['checkedby']; ?>" readonly>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
                    $('#subtype').empty().append('<option value="">---select---</option>');
                }
            });

            $('#type').change(function() {
                var type = $(this).val();
                if (type) {
                    $.ajax({
                        type: 'POST',
                        url: 'get-stp.php',
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
</body>

</html>