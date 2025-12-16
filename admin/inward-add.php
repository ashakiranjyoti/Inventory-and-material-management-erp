<?php
session_start();
error_reporting(E_ALL); // Enable all error reporting for debugging
include('includes/dbconnection.php');

// Redirect if not logged in
if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

// Initialize checkedBy
$checkedBy = isset($_SESSION['name']) ? $_SESSION['name'] : '';

// Get current date and time
$currentDateTime = date('Y-m-d H:i:s');

if (isset($_POST['submit'])) {
    // Get form data
    $supplier = mysqli_real_escape_string($con, $_POST['supplier']);
    $billno = mysqli_real_escape_string($con, $_POST['billno']);
    $mat_loc = mysqli_real_escape_string($con, $_POST['mat_loc']);
    $mat_status = mysqli_real_escape_string($con, $_POST['mat_status']);
    $challen = mysqli_real_escape_string($con, $_POST['challen']);
    $checkedby = mysqli_real_escape_string($con, $_POST['checkedby']);
    $time = mysqli_real_escape_string($con, $_POST['time']);

    $categories = $_POST['category'];
    $types = $_POST['type'];
    $subtypes = $_POST['subtype'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];
    // $material_loc = $_POST['mat_loc'];
    // $material_status = $_POST['mat_status'];

    $insertionSuccessful = true;

    // Start transaction
    mysqli_begin_transaction($con);

    try {
        for ($i = 0; $i < count($categories); $i++) {
            $category = mysqli_real_escape_string($con, $categories[$i]);
            $type = mysqli_real_escape_string($con, $types[$i]);
            $subtype = mysqli_real_escape_string($con, $subtypes[$i]);
            $quantity = mysqli_real_escape_string($con, $quantities[$i]);
            $unit = mysqli_real_escape_string($con, $units[$i]);
            // $mat_loc = mysqli_real_escape_string($con, $material_loc[$i]);
            // $mat_status = mysqli_real_escape_string($con, $material_status[$i]);
        
            $insertQuery = "INSERT INTO tblinward (supplier, category, type, subtype, quantity, unit, billno, challen, checkedby, mat_loc, mat_status, time) 
                            VALUES ('$supplier', '$category', '$type', '$subtype', '$quantity', '$unit', '$billno', '$challen', '$checkedby', '$mat_loc', '$mat_status', '$time')";
        
            if (!mysqli_query($con, $insertQuery)) {
                throw new Exception("Insertion failed for row $i: " . mysqli_error($con));
            }
        
            // Update quantity in tblsubtype with both type and subtype check
$checkQuery = "SELECT avail_quant FROM tblsubtype WHERE subtype_name = '$subtype' AND type = '$type'";
$checkResult = mysqli_query($con, $checkQuery);

if ($checkResult && mysqli_num_rows($checkResult) > 0) {
    $row = mysqli_fetch_assoc($checkResult);
    $currentQuant = $row['avail_quant'];

    if (empty($currentQuant)) {
        $currentQuant = 0;
    }

    $newQuant = $currentQuant + $quantity;
    $updateQuery = "UPDATE tblsubtype SET avail_quant = '$newQuant' WHERE subtype_name = '$subtype' AND type = '$type'";
    if (!mysqli_query($con, $updateQuery)) {
        throw new Exception("Quantity update failed for subtype $subtype and type $type: " . mysqli_error($con));
    }
} else {
    throw new Exception("Subtype '$subtype' under type '$type' not found in tblsubtype.");
}

        }
        

        // Commit transaction
        mysqli_commit($con);
        echo "<script>alert('Details have been added successfully.');</script>";
        echo "<script>window.location.href ='inward-manage.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($con);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
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

    <head>

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
                                    <li class="active">Add</li>
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
                                <strong>Add Inward</strong>
                                <span style="float:right;">
                                    <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                        <a href="inward-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                    </abbr>
                                </span>
                            </div>
                            <div class="card-body card-block">
                                <form id="inwardForm" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <!-- Fixed Data -->
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="supplier" class="form-control-label">Select Supplier</label></div>
                                        <div class="col-md-9">
                                            <select name="supplier" id="supplier" class="form-control" required>
                                                <option value="">---select---</option>
                                                <?php
                                                $query = mysqli_query($con, "SELECT * FROM tblsupplier");
                                                while ($row = mysqli_fetch_array($query)) {
                                                    echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Additional Form Fields -->
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="billno" class="form-control-label">Bill No</label></div>
                                        <div class="col-md-9"><input type="text" id="billno" name="billno" class="form-control" placeholder="Bill No" required></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="challen" class="form-control-label">Challan No</label></div>
                                        <div class="col-md-9"><input type="text" id="challen" name="challen" class="form-control" placeholder="Challan No" required></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="mat_loc" class="form-control-label">Location</label></div>
                                        <div class="col-md-9"><input type="text" id="mat_loc" name="mat_loc" class="form-control" placeholder="Location" required></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="mat_status" class="form-control-label">Status</label></div>
                                        <div class="col-md-9"><input type="text" id="mat_status" name="mat_status" class="form-control" placeholder="Status" required></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="checkedby" class="form-control-label">Checked by</label></div>
                                        <div class="col-md-9"><input type="text" id="checkedby" name="checkedby" class="form-control" placeholder="Checked by" required readonly value="<?php echo htmlspecialchars($checkedBy); ?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-3"><label for="time" class="form-control-label">Date & Time</label></div>
                                        <div class="col-md-9"><input type="datetime-local" id="time" name="time" class="form-control" required value="<?php echo htmlspecialchars($currentDateTime); ?>"></div>
                                    </div>

                                    <!-- Dynamic Data -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Type</th>
                                                    <th>Subtype</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dynamicRows">
                                                <!-- Initial Row -->
                                                <tr class="dynamic-row" id="defaultRow">
                                                    <td>
                                                        <select name="category[]" class="form-control category" required>
                                                            <option value="">---select---</option>
                                                            <?php
                                                            $query = mysqli_query($con, "SELECT * FROM tblcat");
                                                            while ($row = mysqli_fetch_array($query)) {
                                                                echo "<option value='" . htmlspecialchars($row['cat_name']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="type[]" class="form-control type" required>
                                                            <option value="">---select---</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="subtype[]" class="form-control subtype" required>
                                                            <option value="">---select---</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required></td>
                                                    <td>
                                                        <select name="unit[]" class="form-control" required>
                                                            <option value="">---select---</option>
                                                            <?php
                                                            $query = mysqli_query($con, "SELECT * FROM unit");
                                                            while ($row = mysqli_fetch_array($query)) {
                                                                echo "<option value='" . htmlspecialchars($row['unit_name']) . "'>" . htmlspecialchars($row['unit_name']) . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <abbr title="Remove Row" type="button" style="cursor: pointer" class="abbr_class remove-row">
                                                            <img src="images/lf.jpg" style="width:25px;">
                                                        </abbr>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Add Row Button -->
                                    <div class="form-group text-right">
                                        <!-- <button type="button" id="addRow" class="btn btn-primary">Add Row</button> -->
                                        <abbr title="Add Row" id="addRow" type="button" style="cursor: pointer" class="abbr_class">
                                            <img src="images/plus_button.jpg" style="width:40px;">
                                            </a>
                                        </abbr>
                                    </div>

                                    <!-- Submit Button -->
                                    <p class="text-center">
                                        <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/add_button.png" style="width:80px;height:50px;"></button>
                                    </p>
                                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add Row Functionality
            $('#addRow').click(function() {
                var newRow = `
            <tr class="dynamic-row">
                <td>
                    <select name="category[]" class="form-control category" required>
                        <option value="">---select---</option>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM tblcat");
                        while ($row = mysqli_fetch_array($query)) {
                            echo "<option value='" . htmlspecialchars($row['cat_name']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="type[]" class="form-control type" required>
                        <option value="">---select---</option>
                    </select>
                </td>
                <td>
                    <select name="subtype[]" class="form-control subtype" required>
                        <option value="">---select---</option>
                    </select>
                </td>
                <td><input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required></td>
                <td>
                    <select name="unit[]" class="form-control" required>
                        <option value="">---select---</option>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM unit");
                        while ($row = mysqli_fetch_array($query)) {
                            echo "<option value='" . htmlspecialchars($row['unit_name']) . "'>" . htmlspecialchars($row['unit_name']) . "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <abbr title="Remove Row" type="button" style="cursor: pointer" class="abbr_class remove-row">
                        <img src="images/lf.jpg" style="width:25px;">
                    </abbr>
                </td>
            </tr>`;
                $('#dynamicRows').append(newRow);
            });

            // Remove Row Functionality
            $(document).on('click', '.remove-row', function() {
                // Prevent removal of the default row
                if (!$(this).closest('tr').is('#defaultRow')) {
                    $(this).closest('tr').remove();
                }
            });

            // Populate Type Dropdown
            $(document).on('change', '.category', function() {
                var category = $(this).val();
                var typeSelect = $(this).closest('.dynamic-row').find('.type');
                if (category) {
                    $.ajax({
                        type: 'POST',
                        url: 'get-tp.php',
                        data: {
                            category: category
                        },
                        dataType: 'json',
                        success: function(response) {
                            typeSelect.empty().append('<option value="">---select---</option>');
                            $.each(response, function(index, type) {
                                typeSelect.append('<option value="' + type + '">' + type + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' - ' + error);
                        }
                    });
                } else {
                    typeSelect.empty().append('<option value="">---select---</option>');
                }
            });

            // Populate Subtype Dropdown
            $(document).on('change', '.type', function() {
                var type = $(this).val();
                var subtypeSelect = $(this).closest('.dynamic-row').find('.subtype');
                if (type) {
                    $.ajax({
                        type: 'POST',
                        url: 'get-stp.php',
                        data: {
                            type: type
                        },
                        dataType: 'json',
                        success: function(response) {
                            subtypeSelect.empty().append('<option value="">---select---</option>');
                            $.each(response, function(index, subtype) {
                                subtypeSelect.append('<option value="' + subtype.subtype_name + '">' + subtype.subtype_name + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + ' - ' + error);
                        }
                    });
                } else {
                    subtypeSelect.empty().append('<option value="">---select---</option>');
                }
            });
        });
    </script>

</html>
