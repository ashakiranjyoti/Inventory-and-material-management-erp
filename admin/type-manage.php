<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if ($_GET['del']) {
    $sid = $_GET['del'];
    mysqli_query($con, "delete from tbltype where sno ='$sid'");
    echo "<script>window.location.href='type-manage.php'</script>";
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
    <link rel="stylesheet" href="assets/css/instyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                                <li class="Active">Configuration</li>
                                <li><a href="type-manage.php">Type</a></li>
                                <!-- <li><a href="type-add.php">Add Type</a></li> -->
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Manage Type</strong>
                            <span style="float:right;">
								<abbr title="Add New" style="cursor: pointer" class="abbr_class"><a href="type-add.php"><img src="images/add.png" class="image_safe" style="width:30px;height:30px;"></a></abbr>
							</span>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Category </th>
                                        <th>Type Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </tr>
                                </thead>
                                <?php
                                $ret = mysqli_query($con, "select *from  tbltype");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {

                                ?>

                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['cat']; ?></td>
                                        <td><?php echo $row['type_name']; ?></td>


                                        <td>
                                            <abbr title="Edit" style="cursor: pointer" class="abbr_class"><a href="type-edit.php?editid=<?php echo $row['sno']; ?>">
                                                    <img src="images/eee.png" style="width:25px;" class="image_safe">
                                            </abbr>
                                    
                                            <abbr title="Delete" style="cursor: pointer" class="abbr_class"><a href="type-manage.php?del=<?php echo $row['sno']; ?>"  onClick="return confirm('Are you sure you want to delete?')">
                                                    <img src="images/lf.jpg" style="width:20px;" class="image_safe">
                                            </abbr>

                                        </td>

                                    </tr>
                                <?php
                                    $cnt = $cnt + 1;
                                } ?>
                            </table>
                        </div>
                    </div>
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

</body>

</html>