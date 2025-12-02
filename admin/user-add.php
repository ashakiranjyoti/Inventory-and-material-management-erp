<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    $query = mysqli_query($con, "INSERT INTO admintbl(name, pass, role) VALUES ('$name', '$pass', '$role')");

    if ($query) {
        echo "<script>alert('Detail has been added');</script>";
        echo "<script>window.location.href='user-manage.php'</script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Registration </title>
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
                                <li><a href="user-manage.php">Reg Admin-User</a></li>
                                <li class="Active">Add</li>
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
                            <strong>Add Admin/User</strong> 
                            <span style="float:right;">
                                <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                    <a href="user-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">User Name</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="name" name="name" class="form-control" placeholder="User Name" required="true"></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Password</label></div>
                                    <div class="col-12 col-md-9"><input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required="true"></div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="role" class="form-control-label">Role</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="role" id="role" class="form-control">
                                            <option value="0">---select---</option>
                                            <option value="admin">admin</option>
                                            <option value="user">user</option>
                                        </select>
                                    </div>
                                </div>
                                <p style="text-align: center;"> <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/add_button.png" style="width:80px;height:50px;"></button></p>
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

</body>

</html>