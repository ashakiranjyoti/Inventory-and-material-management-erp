<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login') header("Location: logout.php");

if (isset($_SESSION['name'])) {
    $issuedBy = $_SESSION['name'];
} else {
    $issuedBy = '';
}

// Only calculate, don't update stock manually
function getAvailableQuantity($con, $type, $subtype)
{
    $queryInward = "SELECT COALESCE(SUM(quantity), 0) AS total_inward 
                    FROM tblinward WHERE type = '$type' AND subtype = '$subtype'";
    $resultInward = mysqli_query($con, $queryInward);
    $rowInward = mysqli_fetch_assoc($resultInward);
    $totalInward = $rowInward['total_inward'];

    $queryOutward = "SELECT COALESCE(SUM(quantity), 0) AS total_outward 
                     FROM tbloutward WHERE type = '$type' AND subtype = '$subtype'";
    $resultOutward = mysqli_query($con, $queryOutward);
    $rowOutward = mysqli_fetch_assoc($resultOutward);
    $totalOutward = $rowOutward['total_outward'];

    return $totalInward - $totalOutward;
}

if (isset($_POST['submit'])) {
    $vendor_name = mysqli_real_escape_string($con, $_POST['vendor_name']);
    $billno = mysqli_real_escape_string($con, $_POST['billno']);
    $mat_loc = mysqli_real_escape_string($con, $_POST['mat_loc']);
    $mat_status = mysqli_real_escape_string($con, $_POST['mat_status']);
    $challen = mysqli_real_escape_string($con, $_POST['challen']);
    $purpose = mysqli_real_escape_string($con, $_POST['purpose']);
    $des = mysqli_real_escape_string($con, $_POST['des']);
    $checkedby = mysqli_real_escape_string($con, $_POST['checkedby']);
    $time = mysqli_real_escape_string($con, $_POST['time']);

    $categories = $_POST['category'];
    $types = $_POST['type'];
    $subtypes = $_POST['subtype'];
    $quantities = $_POST['quantity'];
    $units = $_POST['unit'];

    mysqli_begin_transaction($con);

    try {
        for ($i = 0; $i < count($categories); $i++) {
            $category = mysqli_real_escape_string($con, $categories[$i]);
            $type = mysqli_real_escape_string($con, $types[$i]);
            $subtype = mysqli_real_escape_string($con, $subtypes[$i]);
            $quantity = mysqli_real_escape_string($con, $quantities[$i]);
            $unit = mysqli_real_escape_string($con, $units[$i]);

            $availableQuantity = getAvailableQuantity($con, $type, $subtype);

            if ($quantity > $availableQuantity) {
                throw new Exception("Quantity exceeds available stock for subtype $subtype (Available: $availableQuantity, Requested: $quantity)");
            }

            // Only INSERT - no stock update
            $query = "INSERT INTO tbloutward (status, vendor_name, category, type, subtype, available_quantity, quantity, unit, billno, challen, purpose, des, issuedby, mat_loc, mat_status, time) 
                      VALUES ('OUT', '$vendor_name', '$category', '$type', '$subtype', '$availableQuantity', '$quantity', '$unit', '$billno', '$challen', '$purpose', '$des', '$issuedBy', '$mat_loc', '$mat_status', '$time')";
            if (!mysqli_query($con, $query)) {
                throw new Exception('Failed to insert outward record: ' . mysqli_error($con));
            }
        }

        mysqli_commit($con);
        echo "<script>alert('Outward record added successfully!');</script>";
        echo "<script>window.location.href ='outward-manage.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

$currentDateTime = date('Y-m-d H:i:s');
?>



<!doctype html>
<html class="no-js" lang="">

<head>

    <title>Inventory Management System - Outward</title>
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
                                <li><a href="outward-manage.php">Outward</a></li>
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
                            <strong>Add Outward</strong>
                            <span style="float:right;">
                                <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                    <a href="outward-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body card-block">
                            <form id="outwardForm" action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <!-- Fixed Data -->
                                <div class="row form-group">
                                    <div class="col-md-3"><label for="vendor_name" class="form-control-label">Select Vendor</label></div>
                                    <div class="col-md-9">
                                        <select name="vendor_name" id="vendor_name" class="form-control" required>
                                            <option value="">---select---</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM tblvendor");
                                            while ($row = mysqli_fetch_array($query)) {
                                                echo "<option value='" . htmlspecialchars($row['ven_name']) . "'>" . htmlspecialchars($row['ven_name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="billno" class="form-control-label">Bill No.</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="billno" name="billno" class="form-control" placeholder="Bill No." required>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="challen" class="form-control-label">Challen</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="challen" name="challen" class="form-control" placeholder="Challen" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="mat_loc" class="form-control-label">Location</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="mat_loc" name="mat_loc" class="form-control" placeholder="Location" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="mat_status" class="form-control-label">Status</label></div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="mat_status" name="mat_status" class="form-control" placeholder="Status" required>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="purpose" class="form-control-label">Purpose</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <select name="purpose" id="purpose" class="form-control" required>
                                            <option value="">---select---</option>
                                            <option value="Assembly">Assembly</option>
                                            <option value="Production">Production</option>
                                            <option value="Design">Design</option>
                                            <option value="Sample">Sample</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="descriptionField" class="row form-group" style="display: none;">
                                    <div class="col col-md-3">
                                        <label for="des" class="form-control-label">Description</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="des" name="des" class="form-control" placeholder="Description">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="issuedby" class="form-control-label">Issued by</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="issuedby" name="issuedby" class="form-control" placeholder="Issued by" required="true" readonly value="<?php echo htmlspecialchars($issuedBy); ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class=" form-control-label">Date & Time</label></div>
                                    <div class="col-12 col-md-9"><input type="datetime-local" id="time" name="time" class="form-control" placeholder="Date & Time" required="true" value="<?php echo $currentDateTime; ?>"></div>
                                </div>

                                <!-- Dynamic Data -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Subtype</th>
                                                <th>Available Quantity</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dynamicRows">
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
                                                <td><input type="text" name="avail_quantity[]" class="form-control avail-quantity" readonly></td>
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
                                    <!-- <button type="button" id="addRow" class="btn btn-primary">Add Row</button> -->
                                </div>
                                <div class="form-group text-right">
                                    <!-- <button type="button" id="addRow" class="btn btn-primary">Add Row</button> -->
                                    <abbr title="Add Row" id="addRow" type="button" style="cursor: pointer" class="abbr_class">
                                        <img src="images/plus_button.jpg" style="width:40px;">
                                        </a>
                                    </abbr>
                                </div>

                                <!-- <div class="form-actions form-group">
                                    <button type="submit" name="submit" class="btn btn-success btn-sm">Submit</button>
                                </div> -->
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
    <script src="assets/js/outward.js"></script>
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
                <td><input type="text" name="avail_quantity[]" class="form-control avail-quantity" readonly></td>
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
            if (!$(this).closest('.dynamic-row').is('#defaultRow')) {
                $(this).closest('.dynamic-row').remove();
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

        
        // Update Available Quantity on Subtype Change
$(document).on('change', '.subtype', function() {
    var row = $(this).closest('.dynamic-row');
    var subtype = $(this).val();
    var type = row.find('.type').val(); // get type from same row

    var availQuantityInput = row.find('.avail-quantity');
    var quantityInput = row.find('input[name="quantity[]"]');

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
                var availableQuant = response.available_quantity;
                availQuantityInput.val(availableQuant);
                quantityInput.attr('max', availableQuant);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    } else {
        availQuantityInput.val('');
        quantityInput.attr('max', '');
    }
});

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