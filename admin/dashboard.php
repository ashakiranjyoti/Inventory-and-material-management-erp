<!doctype html>
<html class="no-js" lang="">
<head>
    <title>Inventory Management System - Admin Dashboard</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-tM+PojeUhLZLR+H0hz7ZbMxQmX0sOyLYweMfJ4kKpI3t61IvN3me8jSn16by6KdRfxW3smJwv6aENwo9x7OKuA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        #weatherWidget .currentDesc {
            color: #ffffff !important;
        }
        .traffic-chart {
            min-height: 335px;
        }
        #flotPie1 {
            height: 150px;
        }
        #flotPie1 td {
            padding: 3px;
        }
        #flotPie1 table {
            top: 20px !important;
            right: -10px !important;
        }
        .chart-container {
            display: table;
            min-width: 400px;
            text-align: left;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        #flotLine5 {
            height: 105px;
        }
        #flotBarChart {
            height: 150px;
        }
        #cellPaiChart {
            height: 160px;
        }
        
        /* Custom styles for the charts */
        .chart-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: none;
        }
        .chart-card .card-header {
            background: linear-gradient(45deg, #2a5d63, #2a5d63);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            border: none;
        }
        .chart-container {
            position: relative;
            height: 250px;
            padding: 15px;
        }
        
        /* Debug border - remove after confirming charts work */
        #todayPieChart, #entriesBarChart {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include('includes/dbconnection.php');
     
    include('includes/functions.php');

    // Check if the user is logged in
    // if (!isset($_SESSION["FLAG"]) || $_SESSION["FLAG"] !== 'login') {
    //     header("Location: logout.php");
    //     exit();
    // }

    // Fetch data from the database with error handling
    // Today's Inward Entries
    $query_today_inward = mysqli_query($con, "SELECT COUNT(*) as count_today FROM tblinward WHERE DATE(time) = CURDATE();");
    if (!$query_today_inward) {
        die('Error: ' . mysqli_error($con));
    }
    $count_today_inward = mysqli_fetch_assoc($query_today_inward)['count_today'];

    // Today's Outward Entries
    $query_today_outward = mysqli_query($con, "SELECT COUNT(*) as count_today FROM tbloutward WHERE DATE(time) = CURDATE();");
    if (!$query_today_outward) {
        die('Error: ' . mysqli_error($con));
    }
    $count_today_outward = mysqli_fetch_assoc($query_today_outward)['count_today'];

    $count_shortage = 0;

    // Query to get all subtypes
    $query_shortage = mysqli_query($con, "SELECT * FROM tblsubtype");

    while ($row = mysqli_fetch_assoc($query_shortage)) {
        // Get the live stock for each type and subtype
        $liveStock = getLiveStock($con, $row['type'], $row['subtype_name']);
        
        // Check if the available stock is less than the minimum required quantity
        if ($liveStock < $row['min']) {
            $count_shortage++; // Increment the shortage count
        }
    }

    // Get last 7 days data for bar chart
    $last_seven_days = array();
    $date_labels = array();
    $inward_data = array();
    $outward_data = array();

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $date_labels[] = date('M j', strtotime($date)); // Format as "Sep 10" instead of full date
        
        // Get inward count for the date
        $inward_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tblinward WHERE DATE(time) = '$date'");
        $inward_count = $inward_query ? mysqli_fetch_assoc($inward_query)['count'] : 0;
        $inward_data[] = (int)$inward_count;
        
        // Get outward count for the date
        $outward_query = mysqli_query($con, "SELECT COUNT(*) as count FROM tbloutward WHERE DATE(time) = '$date'");
        $outward_count = $outward_query ? mysqli_fetch_assoc($outward_query)['count'] : 0;
        $outward_data[] = (int)$outward_count;
    }
    ?>

    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <!-- Content -->
    <div class="content">
        <!-- Animated -->
        <div class="animated fadeIn">
            <!-- Widgets -->
            <div class="row">

            <div class="col-lg-3 col-md-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-3">
                                    <h1><?php echo date('d'); ?></h1>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"> Date</div><br>
                                            <div class="stat-heading"><?php echo date('l') .' '.date('d') .'-'.date('m').'-'.date('y'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Today's Inward Entries -->
                <div class="col-lg-3 col-md-6">
                    <a href="inward-manage.php" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-1">
                                        <i class="pe-7s-download"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count"><?php echo $count_today_inward; ?></span></div>
                                            <div class="stat-heading">Today's Inward Entries</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Today's Outward Entries -->
                <div class="col-lg-3 col-md-6">
                    <a href="outward-manage.php?date=<?php echo date('Y-m-d'); ?>" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-2">
                                        <i class="pe-7s-upload"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count"><?php echo $count_today_outward; ?></span></div>
                                            <div class="stat-heading">Today's Outward Entries</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Shortage Items -->
                <div class="col-lg-3 col-md-6">
                    <a href="shortage.php" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body">
                                <div class="stat-widget-five">
                                    <div class="stat-icon dib flat-color-4">
                                        <i class="pe-7s-attention"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text"><span class="count"><?php echo $count_shortage; ?></span></div>
                                            <div class="stat-heading">Total Shortage Items</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- End Widgets -->
            
            <!-- Charts Row -->
            <div class="row">
                <!-- Pie Chart for Today's Entries -->
                <div class="col-lg-6">
                    <div class="card chart-card">
                        <div class="card-header">
                            <strong class="card-title">Today's Entries Overview</strong>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="todayPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart for Entries Comparison -->
                <div class="col-lg-6">
                    <div class="card chart-card">
                        <div class="card-header">
                            <strong class="card-title">Entries Comparison (Last 7 Days)</strong>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="entriesBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Animated -->
    </div>
    <!-- End Content -->
    <div class="clearfix"></div>
    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

    <!-- Scripts -->
    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Release jQuery's control of the $ variable and use noConflict
        var $jq = jQuery.noConflict();
    </script>
    
    <!-- Then load other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    
    <!-- Load main.js after jQuery is available -->
    <script>
        $jq(document).ready(function() {
            var script = document.createElement('script');
            script.src = 'assets/js/main.js';
            document.head.appendChild(script);
        });
    </script>

    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

    <!-- Other chart libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
    <script src="assets/js/init/weather-init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="assets/js/init/fullcalendar-init.js"></script>

    <!-- Custom Chart Scripts -->
    <script>
    // Use jQuery's document ready with noConflict
    $jq(document).ready(function($) {
        console.log("DOM loaded, initializing charts...");
        
        // Animate numbers
        $('.count').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });

        // Check if chart elements exist
        const pieCtx = document.getElementById('todayPieChart');
        const barCtx = document.getElementById('entriesBarChart');
        
        console.log("Pie canvas found:", !!pieCtx);
        console.log("Bar canvas found:", !!barCtx);

        if (pieCtx) {
            console.log("Initializing pie chart with data:", {
                inward: <?php echo $count_today_inward; ?>,
                outward: <?php echo $count_today_outward; ?>,
                shortage: <?php echo $count_shortage; ?>
            });
            
            // Initialize Pie Chart
            try {
                new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Inward Entries', 'Outward Entries', 'Shortage Items'],
                        datasets: [{
                            data: [
                                <?php echo $count_today_inward ?? 0; ?>, 
                                <?php echo $count_today_outward ?? 0; ?>, 
                                <?php echo $count_shortage ?? 0; ?>
                            ],
                            backgroundColor: [
                                '#2980b9',
                                '#e74c3c',
                                '#f39c12'
                            ],
                            hoverOffset: 10,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        if (total === 0) return `${label}: ${value} (0%)`;
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                console.log("Pie chart initialized successfully");
            } catch (e) {
                console.error("Error initializing pie chart:", e);
            }
        }

       $jq(document).ready(function() {
    const barCtx = document.getElementById('entriesBarChart').getContext('2d');

    if (barCtx) {
        console.log("Bar chart data:", {
            labels: <?php echo json_encode($date_labels); ?>,
            inward: <?php echo json_encode($inward_data); ?>,
            outward: <?php echo json_encode($outward_data); ?>
        });

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($date_labels); ?>,
                datasets: [
                    {
                        label: 'Inward Entries',
                        data: <?php echo json_encode($inward_data); ?>,
                        backgroundColor: '#2980b9',
                        barPercentage: 0.6,
                        borderRadius: 5
                    },
                    {
                        label: 'Outward Entries',
                        data: <?php echo json_encode($outward_data); ?>,
                        backgroundColor: '#e74c3c',
                        barPercentage: 0.6,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            max: <?php echo max(max($inward_data), max($outward_data)) + 1; ?>,
                            stepSize: 1,
                            precision: 0
                        },
                        gridLines: {
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                         gridLines: {
      display: true,        // ✅ Vertical lines ON
      drawBorder: false,    
      color: '#ccc',        // ✅ Light grey
      lineWidth: 1,         
      borderDash: []        // ✅ Solid lines only (no dotted)
    },
                        ticks: {
                            display: true
                        }
                    }]
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        });

        console.log("Bar chart initialized successfully");
    }
});


    });
    </script>
</body>
</html>
