<head>
    <!-- Link the external CSS file -->
    <link rel="stylesheet" type="text/css" href="sidebar-style.css">
</head>
 <aside id="left-panel" class="left-panel" style="background-color: #cbecf5;">
        <nav class="navbar navbar-expand-sm navbar-default" style="background-color: #cbecf5;">
            <div id="main-menu" class="main-menu collapse navbar-collapse" >
                <ul class="nav navbar-nav" >
                    <li class="active" style="background-color: #cbecf5;">
                        <a href="dashboard.php"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                   
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-cog"></i>Configuration</a>
                        <ul class="sub-menu children dropdown-menu" style="background-color: #cbecf5;" >
                            <li><i class="fa fa-table"></i><a href="cat-manage.php">Category</a></li>
                            <li><i class="fa fa-table"></i><a href="type-manage.php">Type</a></li>
                            <li><i class="fa fa-table"></i><a href="subtype-manage.php">Subtype</a></li>
                            <li><i class="fa fa-table"></i><a href="unit-manage.php">Unit</a></li>
                        </ul>
                    </li>
                    
                    <li>
                        <a href="sup-manage.php"> <i class="menu-icon fa fa-industry"></i>Supplier</a>
                    </li>

                    <li>
                        <a href="vendor-manage.php"> <i class="menu-icon fa fa-th-large"></i>Vendor/Dept</a>
                    </li>

                    
                    <li>
                        <a href="inward-manage.php"> <i class="menu-icon fa fa-indent"></i>Inward</a>
                    </li>
                    <li>
                        <a href="outward-manage.php"> <i class="menu-icon fa fa-outdent"></i>Outward</a>
                    </li>

                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-file"></i>Report</a>
                        <ul class="sub-menu children dropdown-menu" style="background-color: #cbecf5;" >
                            <li><i class="menu-icon fa fa-th"></i><a href="report-add.php">Item Report</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="report-sup-add.php">Supplier Report</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="report-ven-add.php">Vendor Report</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="shortage.php">Shortage Material</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="req-manage.php"> <i class="menu-icon fa fa-clipboard-list"></i>Requirement</a>
                    </li>

                    
                    

                    <li>
                        <a href="user-manage.php"> <i class="menu-icon ti-user"></i>Admin/User</a>
                        <!-- <a href="report-manage.php"> <i class="menu-icon fa fa-th"></i>Report Manage</a> -->
                    </li>
                    
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>