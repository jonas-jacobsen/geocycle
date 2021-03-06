<header>

    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
        <div class="container-fluid">

            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <img src="assets/logo/logo.png" height="30" alt="mdb logo">
            </a>

            <!-- Collapse -->
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Left
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link waves-effect" href="#">
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                </ul>
                -->
                <!-- Right -->
                <ul class="navbar-nav ml-auto nav-flex-icons">

                    <li class="nav-item">
                        <a href="userDashboard.php" class="nav-link waves-effect">
                            <i class="fas fa-folder-open"></i>
                        </a>
                    </li>
                    <br>
                    <li class="nav-item">
                        <a href="#" class="nav-link waves-effect">
                            <i class="fas fa-phone"></i>
                        </a>
                    </li>
                    <br>
                    <li class="nav-item">
                        <a href="components/logout.php" class="nav-link waves-effect">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                    <!-- Dropdown -->
                    <div class="chooseLang">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false"><span class="lang"><?php echo $lang['indexLangButton'] ?></span></a>
                            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="<?php echo $lang['userDashboardLangLinkRequestSite'] ?>"><?php echo $lang['indexLangButtonHover'] ?></a>
                            </div>
                        </li>
                    </div>

                    <!--
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-default"
                             aria-labelledby="navbarDropdownMenuLink-333">
                            <a class="dropdown-item" href="components/logout.php">Logout</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    -->
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar -->

</header>