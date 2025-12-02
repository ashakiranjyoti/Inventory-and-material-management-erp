<div id="right-panel" class="right-panel" style="background-color: #fcf7fc;">
    <header id="header" class="header" style="background-color: #2a5d63; display: flex; justify-content: space-between; align-items: center; padding: 10px;">

        <!-- Left side: Logo -->
        <div class="top-left" style="background-color: #2a5d63; display: flex; align-items: center;">
            <div class="navbar-header" style="background-color: #2a5d63;">
                <a class="navbar-brand" href="dashboard.php" style="color: white; font-size: 24px; font-weight: bold; letter-spacing: 1px;">
                    ASSET MANAGEMENT
                </a>
                <a id="menuToggle" class="menutoggle"><i style="color: white;" class="fa fa-bars"></i></a>
            </div>
        </div>


        <!-- Right side: User info and Avatar -->
        <div class="top-right" style="background-color: #2a5d63; display: flex; align-items: center; justify-content: flex-end;">
            <!-- User info -->
            <a class="nav-link" href="#" style="color: #fcfcfc; margin-right: -15px;">
                <i class="fa fa-user" style="margin-right: 10px;" ></i> <?php echo $_SESSION['name']; ?>
            </a>

            <!-- User Avatar -->
            <div class="user-area dropdown float-right" style="margin-right: 20px;">
                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar rounded-circle" src="images/images.png" alt="User Avatar" style="width: 40px; height: 40px;">
                </a>
                <div class="user-menu dropdown-menu" style="background-color: #cbecf5;">
                    <a class="nav-link" href="#"> <i class="fa fa-user"></i> <?php echo $_SESSION['name']; ?> </a>
                    <a class="nav-link" href="index.php"><i class="fa fa-arrow-right"></i> Logout</a>
                </div>
            </div>

            
        </div>
    </header>