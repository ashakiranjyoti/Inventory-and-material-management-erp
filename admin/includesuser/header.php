<div id="right-panel" class="right-panel" style="background-color: #fcf7fc;">
    <header id="header" class="header" style="background-color: #2a5d63;">
        <div class="top-left" style="background-color: #2a5d63;">
            <div class="navbar-header" style="background-color: #2a5d63;">
                
                <!-- <a class="navbar-brand" href="#"><h1>User</h1></a> -->
                <!-- <a class="navbar-brand hidden" href="./"><img src="images/logo3.png" alt="Logo"></a> -->
                <a id="menuToggle"  class="menutoggle"><i style="color: white;" class="fa fa-bars"></i></a>
            </div>
        </div>
        <div class="top-right" style="background-color: #2a5d63;">
            <div class="header-menu">
                <div class="header-left">

                    <div class="form-inline">

                    </div>


                </div>
                <a class="nav-link" href="#" style="color: #fcfcfc;">
                            <i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?>
                        </a>
                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-avatar rounded-circle" src="images/images.png" alt="User Avatar">
                    </a>

                    <div class="user-menu dropdown-menu" style="background-color: #cbecf5;">
                        <!-- <a class="nav-link" href="admin-profile.php"><i class="fa fa- user"></i>My Profile</a> -->
                        <a class="nav-link" href="#">
                            <i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?>
                        </a>


                        <!-- <a class="nav-link" href="change-password.php"><i class="fa fa -cog"></i>Change Password</a> -->

                        <a class="nav-link" href="index.php">
                            <i class="fa fa-arrow-right"></i> Logout
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </header>