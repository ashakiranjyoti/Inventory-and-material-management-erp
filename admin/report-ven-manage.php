<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if ($_SESSION["FLAG"] != 'login')  header("Location: logout.php");

if (isset($_POST['submit'])) {
    $vendor_name = $_POST['vendor_name'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];

    $query = "SELECT 
                'Inward' AS source,
                sno,
                status,
                supplier AS supplier_vendor,
                category,
                type,
                subtype,
                '' AS available_quantity,
                quantity,
                mat_loc,
                mat_status,
                unit,
                billno AS challan_no,
                '' AS purpose,
                '' AS des,
                checkedby AS check_issue,
                time
              FROM tblinward
              WHERE supplier = '$supplier' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            UNION
              SELECT 
                'Outward' AS source,
                sno,
                status,
                vendor_name AS supplier_vendor,
                category,
                type,
                subtype,
                available_quantity,
                quantity,
                mat_loc,
                mat_status,
                unit,
                billno,
                purpose,
                des,
                issuedby AS check_issue,
                time
              FROM tbloutward
              WHERE vendor_name = '$vendor_name' AND DATE(time) BETWEEN '$fromdate' AND '$todate'
            ORDER BY time ASC";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $availableQuantity = $_POST['available_quantity'];
}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/instyle.css">
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
                                <li class="active">Report</li>
                                <li><a href="report-add.php">Vendor Report</a></li>
                                <li class="active">Report Show</li>
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
                            <strong>Report from <?php echo $fromdate ?> to <?php echo $todate ?> for Vendor: <?php echo $vendor_name ?></strong>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Pdf" style="cursor: pointer" class="abbr_class">
                                    <a href="report-ven-pdf.php?vendor_name=<?php echo urlencode($vendor_name); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>" target="_blank">
                                        <img src="images/pdf.png" style="width:30px;height:30px;">
                                    </a>
                                </abbr>
                            </span>
                            <span style="float:right;margin-left:15px;">
                                <abbr title="Import In Excel" style="cursor: pointer" class="abbr_class">
                                    <a href="report-ven-export.php?vendor_name=<?php echo urlencode($vendor_name); ?>&fromdate=<?php echo urlencode($fromdate); ?>&todate=<?php echo urlencode($todate); ?>">
                                        <img src="images/excel.png" style="width:30px;height:30px;">
                                    </a>
                                </abbr>
                            </span>
                            <span style="float:right;">
								<abbr title="Add New" style="cursor: pointer" class="abbr_class"><a href="report-ven-add.php"><img src="images/add.png" class="image_safe" style="width:30px;height:30px;"></a></abbr>
							</span>
                        </div>
                        <div class="card-body card-block">
                            <?php if (isset($result) && mysqli_num_rows($result) > 0) { ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S.NO</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Vendor</th>
                                            <th>Category</th>
                                            <th>Type</th>
                                            <th>Subtype</th>
                                            <th>Quantity</th>
                                            <th>Location</th>
                                            <th>Mat.Status</th>
                                            <th>Unit</th>
                                            <th>Challan No</th>
                                            <th>Purpose</th>
                                            <th>Des</th>
                                            <th>Checked By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['time']; ?></td>
                                                <td><?php echo $row['status']; ?></td>
                                                <td><?php echo $row['supplier_vendor']; ?></td>
                                                <td><?php echo $row['category']; ?></td>
                                                <td><?php echo $row['type']; ?></td>
                                                <td><?php echo $row['subtype']; ?></td>
                                                <td><?php echo $row['quantity']; ?></td>
                                                <td><?php echo $row['mat_loc']; ?></td>
                                                <td><?php echo $row['mat_status']; ?></td>
                                                <td><?php echo $row['unit']; ?></td>
                                                <td><?php echo $row['challan_no']; ?></td>
                                                <td><?php echo ($row['source'] == 'Outward') ? $row['purpose'] : '---'; ?></td>
                                                <td><?php echo ($row['source'] == 'Outward') ? $row['des'] : '---'; ?></td>
                                                <td><?php echo $row['check_issue']; ?></td>
                                            </tr>
                                        <?php
                                            $cnt++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p>No data found.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php include_once('includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>