<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include('includes/functions.php');

if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if ($_GET['del']) {
    $sid = $_GET['del'];
    mysqli_query($con, "delete from tblsubtype where sno ='$sid'");
    echo "<script>window.location.href='shortage.php'</script>";
}

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Shortage</title>
    <!-- <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png"> -->
    <link rel="icon" type="image/png" href="images/logo-Inventory Management System.png" />
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-tM+PojeUhLZLR+H0hz7ZbMxQmX0sOyLYweMfJ4kKpI3t61IvN3me8jSn16by6KdRfxW3smJwv6aENwo9x7OKuA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                                <li class="active">Reports</li>
                                <li><a href="shortage.php">Shortage Material </a></li>
                                <!-- <li><a href="subtype-add.php">Add Subtype</a></li> -->
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
                            <strong class="card-title">Shortage Material</strong>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Pdf" style="cursor: pointer" class="abbr_class">
                                    <a href="shortage-data-pdf.php?supplier=<?php echo urlencode($supplier); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>" target="_blank">
                                        <img src="images/pdf.png" style="width:30px;height:30px;">
                                    </a>
                                </abbr>
                            </span>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Excel" style="cursor: pointer" class="abbr_class">
                                    <a href="shortage-data-export.php?supplier=<?php echo urlencode($supplier); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>">
                                        <img src="images/excel.png" style="width:30px;height:30px;">
                                    </a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th> Category</th>
                                        <th> Type</th>
                                        <th>SubType</th>
                                        <th>Minimum Quantity</th>
                                        <th>Rac No</th>
                                        <th>Avail Quantity</th>
                                    </tr>
                                </thead>
                                <?php

                                $ret = mysqli_query($con, "SELECT * FROM tblsubtype");
                                $cnt = 1;

                                while ($row = mysqli_fetch_array($ret)) {
                                    $liveStock = getLiveStock($con, $row['type'], $row['subtype_name']);
                                
                                    if ($liveStock < $row['min']) {
                                ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><?php echo $row['cat']; ?></td>
                                            <td><?php echo $row['type']; ?></td>
                                            <td><?php echo $row['subtype_name']; ?></td>
                                            <td><?php echo $row['min']; ?></td>
                                            <td><?php echo $row['rac']; ?></td>
                                            <td><?php echo $liveStock; ?></td>
                                        </tr>
                                <?php
                                    }
                                }
?>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
