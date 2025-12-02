<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if ($_SESSION["FLAG"] != 'login') {
    header("Location: logout.php");
    exit();
}

if (isset($_POST['submit'])) {
    $eid = $_GET['editid'];
    $cat = $_POST['category'];
    $type = $_POST['type'];
    $subtype_name = $_POST['subtype_name'];
    $min = $_POST['min'];
    $rac = $_POST['rac'];
    $avail_quant = $_POST['avail_quant'];

    $query = mysqli_query($con, "UPDATE tblsubtype SET cat='$cat', type='$type', subtype_name='$subtype_name', min='$min', rac='$rac', avail_quant='$avail_quant' WHERE sno='$eid'");
    if ($query) {
        echo "<script>alert('Details updated');</script>";
        echo "<script>window.location.href='subtype-manage.php'</script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}

$eid = $_GET['editid'];
$ret = mysqli_query($con, "SELECT * FROM tblsubtype WHERE sno='$eid'");
$row = mysqli_fetch_array($ret);

$selected_category = htmlspecialchars($row['cat']);
$selected_type = htmlspecialchars($row['type']);
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <title>Inventory Management System - Subtype</title>
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
                                <li class="Active">Configuration</li>
                                <li><a href="subtype-manage.php">Subtype</a></li>
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Update Subtype</strong>
                            <span style="float:right;">
                                <abbr title="Back" style="cursor: pointer" class="abbr_class">
                                    <a href="subtype-manage.php"><img src="images/back-new.png" class="image_safe" style="width:30px;height:30px;margin-right:0px;"></a>
                                </abbr>
                            </span>
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="category" class="form-control-label">Select Category</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="">---select---</option>
                                            <?php
                                            $query_category = mysqli_query($con, "SELECT * FROM tblcat");
                                            while ($row_category = mysqli_fetch_array($query_category)) {
                                                $selected = ($row_category['cat_name'] == $selected_category) ? 'selected' : '';
                                                echo "<option value='" . $row_category['cat_name'] . "' " . $selected . ">" . $row_category['cat_name'] . "</option>";
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
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="subtype_name" class="form-control-label">SubType Name</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="subtype_name" name="subtype_name" class="form-control" placeholder="SubType name" required value="<?php echo htmlspecialchars($row['subtype_name']); ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="min" class="form-control-label">Minimum Quantity</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="min" name="min" class="form-control" placeholder="Minimum Quantity" required value="<?php echo htmlspecialchars($row['min']); ?>">
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="rac" class="form-control-label">Box No</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="rac" name="rac" class="form-control" placeholder="Box Number" required value="<?php echo htmlspecialchars($row['rac']); ?>">
                                    </div>
                                </div>

                                <!-- <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="min" class="form-control-label">Available Quantity</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="avail_quant" name="avail_quant" class="form-control" placeholder="Available Quantity" readonly required value="<?php echo htmlspecialchars($row['avail_quant']); ?>">
                                    </div>
                                </div> -->

                                <p style="text-align: center;">
                                    <button style="background-color: white;" type="submit" class="btn btn-sm" name="submit"><img src="images/update.png" style="width:90px;height:50px;"></button>
                                </p>
                            </form>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadTypes(category) {
                if (category) {
                    $.ajax({
                        type: 'POST',
                        url: 'get_types.php',
                        data: {
                            category: category
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#type').empty(); // Clear all existing options
                            $('#type').append('<option value="">---select---</option>'); // Always add the default option

                            if (Array.isArray(response) && response.length > 0) {
                                $.each(response, function(index, type) {
                                    $('#type').append('<option value="' + type + '">' + type + '</option>');
                                });
                            } else {
                                // If no types are returned, ensure the default option is visible
                                $('#type').append('<option value="">---select---</option>');
                            }

                            // Set the initial type value if available
                            var initialType = $('#type').data('initial-type');
                            if (initialType) {
                                $('#type').val(initialType);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, '-', error);
                        }
                    });
                } else {
                    $('#type').empty();
                    $('#type').append('<option value="">---select---</option>'); // Default option
                }
            }

            // On category change
            $('#category').change(function() {
                var category = $(this).val();
                loadTypes(category);
            });

            // Trigger change event on category select if it has a value
            var initialCategory = $('#category').val();
            if (initialCategory) {
                loadTypes(initialCategory);
            }

            // Set initial type value
            $('#type').data('initial-type', '<?php echo htmlspecialchars($selected_type); ?>');
        });
    </script>
</body>

</html>