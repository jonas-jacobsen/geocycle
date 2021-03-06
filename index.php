<?php
session_start();
include("components/config.php");

//Prüfen ob cookieModal gezeigt werden soll
if(empty($_SESSION['showCookies'])){
    $cookieModal =  "$('#frameModalBottom').modal('show');";
    $_SESSION['showCookies'] = 1;
}else{
    $cookieModal = "";
}


/*Login */
if (isset($_POST['submit'])) {
    if ($_POST['email'] != "" && $_POST['password'] != "") {
        $sql = "SELECT * FROM user WHERE Email='$_POST[email]' AND Password = '$_POST[password]'";
        $result = mysqli_query($conn, $sql);
        $anzahl = mysqli_num_rows($result);
        if ($anzahl == 1) {
            //$sql = "UPDATE user SET lastlogin=" . time() . " WHERE name='$_POST[user]'AND passwort='$_POST[passwort]'";
            //mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result);
            $_SESSION ['userId'] = $row['id'];
            header('Location: userDashboard.php');
        }
        $errorMessage = '<div class="alert alert-danger" role="alert">Nutzername oder Passwort falsch</div>';
    } elseif ($_POST['email'] == "" && $_POST['password'] != "") {
        $errorMessage = '<div class="alert alert-danger" role="alert">Emailadresse eingeben</div>';
    } elseif ($_POST['email'] != "" && $_POST['password'] == "") {
        $errorMessage = '<div class="alert alert-danger" role="alert">Passwort eingeben</div>';
    } else {
        $errorMessage = '<div class="alert alert-danger" role="alert">Nutzername und Passwort eingeben</div>';
    }
}
/*Registrierung */
if (isset($_POST['submitOne'])) {
    if ($_POST['email'] != "" && $_POST['password'] != "") {

        $sql = "SELECT Email FROM user WHERE Email='$_POST[email]'";
        $result = mysqli_query($conn, $sql);
        $anzahl = mysqli_num_rows($result);

        $company = htmlspecialchars($_POST['company']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $phone = htmlspecialchars($_POST['phone']);

        if ($anzahl < 1) {
            $sql = "INSERT INTO user SET Company='$company', Email='$email', Password = '$password', Phone ='$phone'";
            $result = mysqli_query($conn, $sql);
            $_SESSION['userId'] = mysqli_insert_id($conn);
            header('Location: userDashboard.php');
        } else {
            $errorMessage = '<div class="alert alert-danger" role="alert">Emailadresse schon vergeben. Bitte suche dir einen neuen Nutzernamen</div>';
        }
    } else {
        $errorMessage = '<div class="alert alert-danger" role="alert">Emailadresse und Passwort eingeben</div>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Geocycle</title>
    <link rel="shortcut icon" href="https://www.geocycle.com/sites/geocycle/files/geo_favicon.ico" type="image/vnd.microsoft.icon" />
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/css/mdb.min.css" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body>

<nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
    <a class="navbar-brand" href="#">
        <img src="assets/logo/logo.png" height="30" alt="mdb logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav ml-auto">

            <form action="" method="post" class="form-inline my-2 my-lg-0 ml-auto">
                <input type="text" name="email" class="form-control" placeholder="E-Mail" aria-label="Login"
                       style="margin-right: 1px; margin-left: 1px">
                <input type="password" name="password" class="form-control" placeholder="<?php echo $lang['indexPassword'] ?>" aria-label="Login"
                       style="margin-right: 1px; margin-left: 1px">
                <button type="submit" id="submit" name="submit"
                        class="btn btn-outline-success waves-effect btn-md my-2 my-sm-0 ml-3">
                    Login
                </button>
            </form>

            <!-- Dropdown -->
            <div class="chooseLang">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><span class="lang"><?php echo $lang['indexLangButton'] ?></span></a>
                    <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?php echo $lang['indexLangLink'] ?>"><?php echo $lang['indexLangButtonHover'] ?></a>
                    </div>
                </li>
            </div>
        </ul>
    </div>
</nav>


<div class="container" style="margin-top: 80px">
    <?php echo $errorMessage ?>
    <!-- Default form register -->
    <form action="" method="post" class="text-center border border-light p-5">
        <p class="h4 mb-4"><?php echo $lang['indexFormTitle'] ?></p>
        <p mb-4"><?php echo $lang['indexFormInfoText'] ?></p>
        <!-- First name -->
        <input type="text" id="company" name="company" class="form-control mb-4"
               placeholder="<?php echo $lang['indexCompany'] ?>"
               required>
        <div class="form-row mb4">
            <div class="col">
                <!-- E-mail -->
                <input type="email" id="email" name="email" class="form-control mb-4" placeholder="E-mail" required>
            </div>
            <div class="col">
                <!-- Password -->
                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo $lang['indexPassword'] ?>"
                       aria-describedby="defaultRegisterFormPasswordHelpBlock" required>
                <small id="defaultRegisterFormPasswordHelpBlock" class="form-text text-muted mb-4">
                    <?php echo $lang['indexSecPass'] ?>
                </small>
            </div>
        </div>

        <!-- Phone number -->
        <input type="text" id="phone" name="phone" class="form-control" placeholder="<?php echo $lang['indexPhone'] ?>"
               aria-describedby="defaultRegisterFormPhoneHelpBlock">
        <small id="defaultRegisterFormPhoneHelpBlock" class="form-text text-muted mb-4">
            <?php echo $lang['indexTwoFactor'] ?>
        </small>
        <!-- Sign up button -->
        <button type="submit" id="submitOne" name="submitOne" value="1" class="btn btn-dark-green my-4 btn-block">
            <?php echo $lang['indexRegister'] ?>
        </button>
        <!-- Terms of service -->
        <p><?php echo $lang['indexTermsAndCondition'] ?></p>
    </form>
</div>
<!-- Frame Modal Bottom -->
<div class="modal fade bottom" id="frameModalBottom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-frame modal-bottom" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row d-flex justify-content-center align-items-center">
                    <p class="pt-3 pr-2"><?php echo $lang['indexCookie'] ?></p>
                    <button type="button" class="btn btn-success"
                            data-dismiss="modal"><?php echo $lang['indexCookieClose'] ?></button>
                    <a href="https://www.geocycle.com/cookies-policy" target="_blank">
                        <button type="button"
                                class="btn btn-outline-success waves-effect"><?php echo $lang['indexCookieFurtherInfo'] ?></button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Frame Modal Bottom End -->

<!-- Footer -->
<footer class="page-footer font-small success-color-dark fixed-bottom">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="https://www.geocycle.com/"> Geocycle GmbH</a> | <a href="index.php?lang=de">Deutsch </a><a href="index.php?lang=en">Englisch</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer End-->

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
<script type="text/javascript">
   <?php echo $cookieModal?>
</script>
</body>
</html>