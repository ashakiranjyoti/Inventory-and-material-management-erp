<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

$fdate = date('Y-m-d');
$msg = '';
if (isset($_POST['submit'])) {
    $fdate = $_POST['fromdate'];
}

if (isset($_GET['del'])) {
    $sid = $_GET['del'];

    // Just delete the inward record only
    mysqli_query($con, "DELETE FROM tblinward WHERE sno = '$sid'");
    echo "<script>window.location.href='inward-manage.php'</script>";
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Inward</title>
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
                                <li class="active">Inward</li>
                                <!-- <li><a href="inward-add.php">Add</a></li> -->
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
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name="bwdatesreport">
                                <p style="font-size:16px; color:red" align="center"><?php echo $msg; ?></p>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="fromdate" class="form-control-label">Select Date</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="date" name="fromdate" id="fromdate" class="form-control" required="true" value="<?php echo $fdate; ?>">
                                    </div>
                                </div>
                                <p style="text-align: center;">
                                    <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/submit.png" style="width:90px;height:50px;"></button>
                                </p>
                            </form>
                        </div>
                        <div class="card-header">
                            <strong class="card-title">Reports for <?php echo $fdate; ?></strong>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Pdf" style="cursor: pointer" class="abbr_class">
                                    <a href="inward-data-pdf.php?date=<?php echo $fdate; ?>" target="_blank">
                                        <img src="images/pdf.png" style="width:30px;height:30px;">
                                    </a>
                                </abbr>
                            </span>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Excel" style="cursor: pointer" class="abbr_class"><a href="inward-data-export.php?date=<?php echo $fdate; ?>">EXCEL</a></abbr>
                            </span>
                            <span style="float:right;">
                                <abbr title="Add New" style="cursor: pointer" class="abbr_class"><a href="inward-add.php"><img src="images/add.png" class="image_safe" style="width:30px;height:30px;"></a></abbr>
                            </span>

                        </div>
                        <div class="card-body">
                            <?php
                            $ret = mysqli_query($con, "SELECT * FROM tblinward WHERE DATE(time) = '$fdate'");
                            if (mysqli_num_rows($ret) > 0) {
                            ?>
                                <h5 align="center" style="color:blue">Report for <?php echo $fdate; ?></h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.NO</th>
                                            <th>Supplier</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Subtype</th>
                                            <th>Quantity</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Unit</th>
                                            <th>Bill No</th>
                                            <th>Challan No</th>
                                            <th>Checked</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($ret)) {
                                            $row_class = ($cnt % 2 == 0) ? 'even' : 'odd';
                                        ?>
                                            <tr class="<?php echo $row_class; ?>">
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['supplier']; ?></td>
                                                <td><?php echo $row['category']; ?></td>
                                                <td><?php echo $row['type']; ?></td>
                                                <td><?php echo $row['subtype']; ?></td>
                                                <td><?php echo $row['quantity']; ?></td>
                                                <td><?php echo $row['mat_loc']; ?></td>
                                                <td><?php echo $row['mat_status']; ?></td>
                                                <td><?php echo $row['unit']; ?></td>
                                                <td><?php echo $row['billno']; ?></td>
                                                <td><?php echo $row['challen']; ?></td>
                                                <td><?php echo $row['checkedby']; ?></td>
                                                <td><?php echo $row['time']; ?></td>
                                                <td>
                                                    <abbr title="Edit" style="cursor: pointer" class="abbr_class">
                                                        <a href="inward-edit.php?editid=<?php echo $row['sno']; ?>">
                                                            <img src="images/eee.png" style="width:25px;" class="image_safe">
                                                        </a>
                                                    </abbr>
                                                    <abbr title="Delete" style="cursor: pointer" class="abbr_class">
                                                        <a href="inward-manage.php?del=<?php echo $row['sno']; ?>" onClick="return confirm('Are you sure you want to delete?')">
                                                            <img src="images/lf.jpg" style="width:20px;" class="image_safe">
                                                        </a>
                                                    </abbr>
                                                </td>
                                            </tr>
                                        <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            <?php
                            } else {
                                echo '<p align="center" style="color:red; font-size:18px;">No records found for ' . $fdate . '</p>';
                            }
                            ?>
                        </div>
                    </div>
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
