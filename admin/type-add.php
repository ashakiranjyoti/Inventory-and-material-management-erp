<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if (isset($_POST['submit'])) {
    $cat = $_POST['cat'];
    $type_name = $_POST['type_name'];

    $query = mysqli_query($con, "INSERT INTO tbltype(cat,type_name) VALUES ('$cat', '$type_name')");

    if ($query) {
        echo "<script>alert('Detail has been added');</script>";
        echo "<script>window.location.href='type-manage.php'</script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Type</title>
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
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
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
                                <li class="Active">Configuration</li>
                                <li><a href="type-manage.php">Type</a></li>
                                <li class="Active">Add Type</li>
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
                            <strong>Add Type</strong> 
                            <span style="float:right;">
                                <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                    <a href="type-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="cat" class="form-control-label">Select category</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="cat" id="cat" class="form-control" required>
                                            <option value="">--- Select ---</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM tblcat");
                                            while ($row = mysqli_fetch_array($query)) {
                                                echo "<option value='" . $row['cat_name'] . "'>" . $row['cat_name'] . "</option>";
                                            }
                                            ?>
                                            <!-- <option value="add_new">Add New Type</option>  -->
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Type Name</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="type_name" name="type_name" class="form-control" placeholder="Type Name" required="true"></div>
                                </div>

                                <p style="text-align: center;"> <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/add_button.png" style="width:80px;height:50px;"></button></p>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Document is ready'); // Check if document is ready
            $('#cat').change(function() {
                console.log('Dropdown changed'); // Check if dropdown change event triggers
                if ($(this).val() == 'add_new') {
                    console.log('Redirecting to cat-add.php');
                    window.location.href = 'cat-add.php'; // Redirect if "Add New Type" is selected
                } else {
                    $('#newTypeInput').hide(); // Optionally hide input field if existing type is selected
                }
            });
        });
    </script>
</body>

</html>