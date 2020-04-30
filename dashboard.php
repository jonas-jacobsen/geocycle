<?php
session_start();
include("components/session.php");
include("components/config.php");
//check ob Daten vorhanden sind
$sql_all = "SELECT * FROM userdata WHERE id = $_SESSION[userId]";
$statement = mysqli_query($conn, $sql_all);

$row = mysqli_fetch_array($statement);

$firstname = $row['Firstname'];
$surname = $row['Surname'];
$phone = $row['Phone'];
$street = $row['Street'];
$town = $row['Town'];
$zip = $row['Zip'];


if ($row['Firstname'] && $row['Surname'] && $row['Street'] && $row['Town'] && $row['Zip']) {
    $ansprechCheck = "<i class=\"far fa-check-circle green-text\"></i>";
    $contactPersCheckVar = 1;
} else {
    $ansprechCheck = "<i class=\"far fa-times-circle red-text\"></i>";
    $contactPersCheckVar = 0;
}

//Progressbar check
if ($contactPersCheckVar == 1) {
    $progressBarValue = "100%";
    $progressValue = "100";
} else {
    $progressBarValue = "0%";
    $progressValue = "0";
}


include("components/header.php");


?>
<body>
<div class="container-for-admin">
    <!--Main Navigation-->
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

                    <!-- Left -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link waves-effect" href="#">
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Right -->
                    <ul class="navbar-nav nav-flex-icons">
                        <li class="nav-item">
                            <a href="https://twitter.com/MDBootstrap" class="nav-link waves-effect" target="_blank">
                                <i class="fas fa-phone"></i>
                            </a>
                        </li>
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
                    </ul>

                </div>

            </div>
        </nav>
        <!-- Navbar -->

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <!--Grid row-->

            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Status der Anfrage</h2>
                            <div class="progress">
                                <div class="progress-bar progress-bar-info progress-bar-striped active"
                                     style="width:<?php echo $progressBarValue ?>;"></div>
                            </div>
                            <p class="card-text">Anfrage zu <span id="progressValue"><?php echo $progressValue?></span>% abgeschlossen</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="test" id="test"></div>

            <div class="row wow fadeIn">
                <!--Grid column-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <form id="ansprech_Form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Ansprechpartner</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="didChangeContactPers" id="didChangeContactPers"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="firstname" name="firstname" class="form-control"
                                                   value="<?php echo $firstname ?>">
                                            <label for="firstname">Vorname</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="surname" name="surname" class="form-control"
                                                   value="<?php echo $surname ?>">
                                            <label for="surname">Nachname</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                   value="<?php echo $phone ?>">
                                            <label for="phone">Telephonnummer</label>
                                        </div>
                                    </div>
                                </div>
                                <h4>Unternehmensadresse</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="street" name="street" class="form-control"
                                                   value="<?php echo $street ?>">
                                            <label for="street">Straße und Hausnummer</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="zipcode" name="zip" class="form-control"
                                                   value="<?php echo $zip ?>">
                                            <label for="zipcode">Postleitzahl</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="town" name="town" class="form-control"
                                                   value="<?php echo $town ?>">
                                            <label for="town">Ort</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="submitAnsprech" name="submitAnsprech" value="1"
                                        class="btn btn-light-green">Speichern
                                </button>
                            </form>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column-->

                <!--Grid column (checkliste)-->
                <div class="col-md-4 mb-4">
                    <!--Card-->
                    <div class="card mb-4">
                        <!-- Card header -->
                        <div class="card-header text-center">
                            Checkliste
                        </div>
                        <!--Card content-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    Ansprechpartner
                                </div>
                                <div class="col-sm-4" id="contactPersCheck">
                                    <?php echo $ansprechCheck ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    Anfrage
                                </div>
                                <div class="col-md-4">
                                    <i class="far fa-check-circle green-text"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    Weitere Details
                                </div>
                                <div class="col-md-4">
                                    <i class="far fa-check-circle green-text"></i>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    ...
                                </div>
                                <div class="col-md-4">
                                    <i class="far fa-times-circle red-text"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column End (Checkliste)-->

                <!--Grid column (AnfrageDetails)-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">

                            <h4>Anfrage</h4>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Worum gehts:</p>
                                    <!-- Default unchecked -->
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="produkt" name="proAbf">
                                        <label class="custom-control-label" for="produkt">Produktstatus</label>
                                    </div>

                                    <!-- Default checked -->
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="abfall" name="proAbf">
                                        <label class="custom-control-label" for="abfall">Abfall</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p>Sie sind:</p>
                                    <!-- Default unchecked -->
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="erzeuger" name="erzHae">
                                        <label class="custom-control-label" for="erzeuger">Erzeuger</label>
                                    </div>

                                    <!-- Default checked -->
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="haendler" name="erzHae">
                                        <label class="custom-control-label" for="haendler">Händler</label>
                                    </div>
                                    <small id="smalltext" class="form-text text-muted mb-4">
                                        Wenn Sie Händler sind, bitte den Erzeuger nennen:
                                    </small>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="md-form input-with-post-icon">
                                        <i class="fas fa-weight-hanging input-prefix"></i>
                                        <input type="text" id="materialQuantity" class="form-control">
                                        <label for="materialQuantity">Menge in Tonnen</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form input-with-post-icon">
                                        <i class="fas fa-user input-prefix"></i>
                                        <input type="text" id="erzeugerHand" class="form-control">
                                        <label for="erzeugerHand">ggf. Erzeuger</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="md-form input-with-post-icon">
                                        <i class="fas fa-trash input-prefix"></i>
                                        <input type="text" id="abfallbezeichnung" class="form-control">
                                        <label for="abfallbezeichnung">Abfallbezeichnung</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form input-with-post-icon">
                                        <i class="fas fa-trash input-prefix"></i>
                                        <input type="text" id="avv" class="form-control">
                                        <label for="avv">AVV</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form input-with-post-icon">
                                        <i class="fas fa-truck-loading input-prefix"></i>
                                        <input type="text" id="delivery" class="form-control">
                                        <label for="delivery">Anlieferform</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="sumbitAnfrage" name="sumbitAnfrage" class="btn btn-light-green">
                                Speichern
                            </button>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <div class="col-md-4" id="output">

                </div>
                <!--Grid column (End Anfrage)-->

                <!--Grid column (Weitere Details)-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <h4>Weitere Details</h4>
                            <br>
                            <p>Bitte beschrieben sie Kurz und so weier und so fort...</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <i class="fas fa-pencil-alt prefix"></i>
                                        <textarea id="aktEnt" class="md-textarea form-control"
                                                  rows="3"></textarea>
                                        <label for="aktEnt">Aktueller Entsorgungsweg + Preis</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="md-form">
                                        <i class="fas fa-pencil-alt prefix"></i>
                                        <textarea id="processDescr" class="md-textarea form-control"
                                                  rows="3"></textarea>
                                        <label for="processDescr">Prozessbeschreibung</label>
                                    </div>
                                </div>
                            </div>

                            <h4>Benötigte Dokumente</h4>
                            <br>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>Analytik/ SBA/ BA</p>
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="input-file-now" class="file-upload"/>
                                    </div>
                                    <small id="smalltext" class="form-text text-muted mb-4">Nicht älter als 12 Monate
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <p> Zertifikate (ISO, EfB)/ Genehmigung</p>
                                    <div class="file-upload-wrapper">
                                        <input type="file" id="input-file-now" class="file-upload"/>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <button type="submit" id="sumbitMoreData" name="sumbitMoreData" class="btn btn-light-green">
                                Speichern
                            </button>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column (End Weitere Details)-->
            </div>
        </div> <!-- End Container Float -->
        <!--Grid row-->
    </main>
    <!--Main layout-->

</div>
<!-- Footer -->
<footer class="page-footer font-small success-color-dark">
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2020 Copyright:
        <a href="https://www.geocycle.com/"> Geocycle GmbH</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

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

<!--script zur Formularverabeitung -->
<script>
    var rotateCircle = "<p><i class=\"fas fa-sync\"></i></p>";

    $('#ansprech_Form').submit(function (event) {
        event.preventDefault(); //seitenreloud wird verhindert
        $('#didChangeContactPers').html(rotateCircle);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: 'components/updateDataContactPers.php',
            data: $(this).serialize(),
            success: function (response) {
                $('#contactPersCheck').html(response.contactPersCheck);

                contactPersCheckVar = response.contactPersCheckVar;

                showProgressBarValue(contactPersCheckVar);
                $('#test').html(progressBarvalue);
                //alert("Daten in Ansprechpartner geändert")
            }
        });
        //set Timeout for showing Anderungen vorgenommen
        setTimeout(function () {
            $('#didChangeContactPers').html('')
        }, 1000);
    });


    //Progressbar überprüfen
    function showProgressBarValue(contactPersCheckVar) {
        if (contactPersCheckVar == 1) {
            $('.progress-bar').css('width', '100%');
            $('#progressValue').html('100');
        } else {
            $('.progress-bar').css('width', '0%');
            $('#progressValue').html('0');
        }
    }


</script>
</body>
</html>