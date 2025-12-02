<?php
session_start();
include('includes/dbconnection.php');

$_SESSION["FLAG"] = "New";

if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass']; 

    $query = mysqli_prepare($con, "SELECT sno, role FROM admintbl WHERE name=? AND pass=?");
    mysqli_stmt_bind_param($query, "ss", $name, $pass);
    mysqli_stmt_execute($query);
    mysqli_stmt_store_result($query);

    if (mysqli_stmt_num_rows($query) > 0) {
        mysqli_stmt_bind_result($query, $sno, $role);
        mysqli_stmt_fetch($query);

        $_SESSION['vpmsaid'] = $sno;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        $_SESSION["FLAG"] = "login";

        if ($role == 'admin') {
            header('location: dashboard.php');
        } else if ($role == 'user') {
            header('location: req-user-manage.php');
        }
        exit;
    } else {

        $_SESSION["FLAG"] = "New";
        echo "<script>alert('Invalid Username or Password');</script>";
    }
    mysqli_stmt_close($query);
    mysqli_close($con);
}
?>
<!doctype html>
 <html class="no-js" lang="">
<head>
    <title>Inventory Management System-Login Page</title>
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

    <style>
       .login-form {
    background-color: #cbecf5;
    border-radius: 15px;
    padding: 30px;
    border: 1px solid #2a5d63; /* Border added */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); /* Soft shadow */
    max-width: 500px;
    margin: auto;
    margin-top: 20px;
}

.form-control {
    border-radius: 10px;
    box-shadow: none;
    border: 1px solid #ccc;
    transition: 0.3s ease;
}

.form-control:focus {
    border-color: #2a5d63;
    box-shadow: 0 0 5px rgba(46, 58, 89, 0.5);
}

.btn-flat {
    border-radius: 10px;
    background-color: #2a5d63;
    color: white;
    transition: background-color 0.3s ease;
}

.btn-flat:hover {
    background-color: #3e4c72;
}

label {
    font-weight: 600;
}

a {
    color: #2a5d63;
    font-weight: 500;
}

    </style>
    
</head>
<body class="bg-dark" style="background-image: url('images/water-droplets.jpg'); background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;">

    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo text-center mb-3">
    
    <h3 style="color: #2a5d63; margin-top: 10px; font-weight: 600; letter-spacing: 1px;">ASSET MANAGEMENT</h3>
</div>

                <div class="login-form" style="background-color: #cbecf5 ; ">
                    <form method="post">
                         <label class="pull-right">
    <a href="../index.php" style="font-size: 14px; font-weight: 600;">← Back to Home</a>
</label>

                        <div class="form-group">
                            <label style="color: black;" >User Name</label>
                           <input class="form-control" type="text" placeholder="Username" required="true" name="name">
                        </div>
                        <div class="form-group">
                            <label style="color: black;">Password</label>
                            <input type="password" class="form-control" name="pass" placeholder="Password" required="true">
                        </div>
                        <div class="checkbox">
                            
                            

                        </div>
                        <button type="submit" name="login" class="btn  btn-flat m-b-30 m-t-30" style="background-color: #2a5d63; color:white" >Log in</button>
                       <div class="checkbox" style="padding-bottom: 20px;padding-top: 20px;">
                            
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
