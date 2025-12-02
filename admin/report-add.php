<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if (isset($_SESSION['name'])) {
    $issuedBy = $_SESSION['name'];
} else {
    $issuedBy = '';
}

function getAvailableQuantity($con, $type, $subtype)
{
    $queryInward = "SELECT COALESCE(SUM(quantity), 0) AS total_inward FROM tblinward WHERE type = '$type' AND subtype = '$subtype'";
    $resultInward = mysqli_query($con, $queryInward);
    $rowInward = mysqli_fetch_assoc($resultInward);
    $totalInward = $rowInward['total_inward'];

    $queryOutward = "SELECT COALESCE(SUM(quantity), 0) AS total_outward FROM tbloutward WHERE type = '$type' AND subtype = '$subtype'";
    $resultOutward = mysqli_query($con, $queryOutward);
    $rowOutward = mysqli_fetch_assoc($resultOutward);
    $totalOutward = $rowOutward['total_outward'];

    return $totalInward - $totalOutward;
}


if (isset($_POST['submit'])) {
    $vendor_name = $_POST['vendor_name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $subtype = $_POST['subtype'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];
    $billno = $_POST['billno'];
    $challen = $_POST['challen'];
    $purpose = $_POST['purpose'];
    $des = $_POST['des'];
    $issuedby = $_POST['issuedby'];
    $time = $_POST['time'];

    $available_quantity = getAvailableQuantity($con, $type, $subtype);


    if ($quantity <= $available_quantity) {
        $query = mysqli_query($con, "INSERT INTO tbloutward (vendor_name, category, type, subtype, available_quantity, quantity, unit, billno, challen, purpose, des, issuedby, time) VALUES ('$vendor_name', '$category', '$type', '$subtype', '$available_quantity', '$quantity', '$unit', '$billno', '$challen', '$purpose', '$des', '$issuedby', '$time')");

        if ($query) {
            $new_available_quantity = $available_quantity - $quantity;
            // mysqli_query($con, "UPDATE tblinward SET available_quantity = '$new_available_quantity' WHERE subtype = '$subtype'");
            mysqli_query($con, "UPDATE tblinward SET available_quantity = '$new_available_quantity' WHERE type = '$type' AND subtype = '$subtype'");

            echo "<script>alert('Detail has been added');</script>";
            echo "<script>window.location.href ='report-manage.php'</script>";
        } else {
            $error = mysqli_error($con); // Get the error message from MySQL
            echo "<script>alert('Something Went Wrong. Please try again. Error: " . $error . "');</script>";
        }
    } else {
        echo "<script>alert('Quantity to be issued exceeds available quantity');</script>";
    }
}

$currentDateTime = date('Y-m-d\TH:i');

?>
<!doctype html>
<html class="no-js" lang="">

<head>

    <title>Inventory Management System - Reports</title>
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
                                <li><a href="report-add.php">Report</a></li>
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
                    </div> <!-- .card -->
                </div><!--/.col-->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>View Report</strong> 
                        </div>
                        <div class="card-body card-block">
                            <form action="report-manage.php" method="post" enctype="multipart/form-data" class="form-horizontal" name="bwdatesreport">
                                <p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
                                                                                            echo $msg;
                                                                                        }  ?> </p>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="category" class="form-control-label">Select Category</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="">---select---</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM tblcat");
                                            while ($row = mysqli_fetch_array($query)) {
                                                echo "<option value='" . $row['cat_name'] . "'>" . $row['cat_name'] . "</option>";
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
                                            <!-- Options will be populated dynamically using JavaScript -->
                                        </select>
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="subtype" class="form-control-label">Select Subtype</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="subtype" id="subtype" class="form-control" required>
                                            <option value="">---select---</option>
                                            <!-- Options will be populated dynamically using JavaScript -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="available_quantity" class="form-control-label">Available Quantity</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="available_quantity" name="available_quantity" class="form-control" placeholder="Available Quantity" readonly>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">From Date</label></div>
                                    <div class="col-12 col-md-9"><input type="date" name="fromdate" id="fromdate" class="form-control" required="true"></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="email-input" class=" form-control-label">To Date</label></div>
                                    <div class="col-12 col-md-9"><input type="date" name="todate" class="form-control" required="true"></div>
                                </div>

                                <p style="text-align: center;"> <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/submit.png" style="width:90px;height:50px;"></button></p>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->
    <div class="clearfix"></div>
    <?php include_once('includes/footer.php'); ?>
    </div><!-- /#right-panel -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/outward.js"></script>
</body>
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
   $('#subtype').change(function() {
    var subtype = $(this).val();
    var type = $('#type').val(); // also pass type

    if (subtype && type) {
        $.ajax({
            type: 'POST',
            url: 'get_available_quantity.php',
            data: {
                subtype: subtype,
                type: type
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


</script>

<script>
    $(document).ready(function() {
        $('#purpose').change(function() {
            var purpose = $(this).val();
            if (purpose === 'Other') {
                $('#descriptionField').show();
            } else {
                $('#descriptionField').hide();
            }
        });

        // Trigger change event on page load to check initial state
        $('#purpose').trigger('change');
    });
</script>
<script>
    $(document).ready(function() {
        $('#purpose').change(function() {
            var purpose = $(this).val();
            if (purpose === 'Assembly' || purpose === 'Production' || purpose === 'Design' || purpose === 'Sample') {
                $('#des').val('N/A');
            } else {
                $('#des').val('');
            }
        });
        $('#purpose').trigger('change');
    });
</script>

</html>