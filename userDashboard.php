<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/script/userDashboardScript.php");
include("components/header.php");
?>

<body>
<div class="container-for-admin">
    <!--Main Navigation-->
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
                            <?php echo $rowIsnew['Company']?>
                        </a>
                    </li>
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
                                <a class="dropdown-item" href="<?php echo $lang['userDashboardLangLink'] ?>"><?php echo $lang['indexLangButtonHover'] ?></a>
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
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <?php echo $msgModalSendRequest?>
            <form action="" method="post">
                <button type="submit" id="newRequest" name="newRequest" value="1"
                        class="btn btn-outline-success waves-effect"><?php echo $lang['userDashboardCreateNewRequest']?>
                </button>
            </form>
            <br><br>
            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2><?php echo $lang['userDashboardOpenRequest']?></h2>
                            <?php showOpenRequest($conn, $userId, $lang) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2><?php echo $lang['userDashboardSendRequest']?></h2>
                            <?php showCloseRequest($conn, $userId, $lang) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--End Grid row-->
    </main>
</div>

<!-- Footer -->
<footer class="page-footer font-small success-color-dark footer">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="https://www.geocycle.com/"> Geocycle GmbH</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->
<!--Modal anzeigenlassen -->
<?php include("components/modal.php") ?>
<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/js/mdb.min.js"></script>
<!-- script für dragDrop file Input -->
<script src="js/fileUpload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.fileupload.js" type="text/javascript"></script>

<script type="text/javascript">
    //Modal nur nach dem ersten Anmelden anzeigen,
    //Modal-Code befindet sich in components/modal.php
    var modalShow = <?php echo $modalShow?>;
    if (modalShow == 0) {
        $('#fullHeightModalRight').modal('show');
    } else {
    }
</script>
</body>
</html>