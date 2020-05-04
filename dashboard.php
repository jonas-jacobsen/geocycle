<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/databaseScript.php");
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
                            <a href="#" class="nav-link waves-effect" target="_blank">
                                <i class="fas fa-phone"></i>
                            </a>
                        </li>
                        <br>
                        <li class="nav-item">
                            <a href="components/logout.php" class="nav-link waves-effect">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>
                        <!-- Navbar dropdown
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
                        </li> <!-- End li nav item dropdown -->
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
                            <p class="card-text">Anfrage zu <span id="progressValue"><?php echo $progressValue ?></span>%
                                abgeschlossen</p>
                        </div>
                    </div>
                </div>
            </div>

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
                <div class="sidebar">
                    <!--Grid column (checkliste)-->
                    <div class="col-md-4 mb-4">
                        <!--Card-->
                        <div class="sidecard mb-4">
                            <!-- Card header -->
                            <div class="sidecard-header">
                            </div>
                            <!--Card content-->
                            <div class="sidecard-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        Ansprechpartner
                                    </div>
                                    <div class="col-sm-4" id="contactPersCheck">
                                        <?php echo $contactPersCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-8">
                                        Anfrage
                                    </div>
                                    <div class="col-sm-4" id="requestCheck">
                                        <?php echo $requestCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-8">
                                        Weitere Details
                                    </div>
                                    <div class="col-sm-4" id="furtherInfoCheck">
                                        <?php echo $furtherInfoCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-8">
                                        Dokumente
                                    </div>
                                    <div class="col-sm-4" id="docOneCheck">
                                        <?php echo $docOneCheck ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column End (Checkliste)-->
                </div><!--End Sidebar -->

                <!--Grid column (AnfrageDetails)-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <form id="request_Form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Anfrage</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="didChangeRequest" id="didChangeRequest"></span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Worum gehts:</p>
                                        <!-- Default unchecked -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="produkt"
                                                   name="prodAbf" <?php echo $radioOnPro ?> value="Produktstatus">
                                            <label class="custom-control-label" for="produkt">Produktstatus</label>
                                        </div>

                                        <!-- Default checked -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="abfall"
                                                   name="prodAbf" value="Abfall" <?php echo $radioOnAbf ?>>
                                            <label class="custom-control-label" for="abfall">Abfall</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Sie sind:</p>
                                        <!-- Default unchecked -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="erzeuger"
                                                   name="erzHae" <?php echo $radioOnErz ?> value="Erzeuger">
                                            <label class="custom-control-label" for="erzeuger">Erzeuger</label>
                                        </div>

                                        <!-- Default checked -->
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="haendler"
                                                   name="erzHae" <?php echo $radioOnHae ?> value="Händler">
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
                                            <input type="text" id="materialQuantity" class="form-control" name="jato"
                                                   value="<?php echo $jato ?>">
                                            <label for="materialQuantity">Menge in Tonnen</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="producer" class="form-control" name="producer"
                                                   value="<?php echo $producer ?>">
                                            <label for="producer">ggf. Erzeuger</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-trash input-prefix"></i>
                                            <input type="text" id="abfallbezeichnung" class="form-control"
                                                   name="wasteDescription"
                                                   value="<?php echo $wasteDescription ?>">
                                            <label for="abfallbezeichnung">Abfallbezeichnung</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-trash input-prefix"></i>
                                            <input type="text" id="avv" class="form-control" name="avv"
                                                   value="<?php echo $avv ?>">
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
                                            <input type="text" id="delivery" class="form-control" name="deliveryForm"
                                                   value="<?php echo $deliveryForm ?>">
                                            <label for="delivery">Anlieferform</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="sumbitAnfrage" name="sumbitAnfrage"
                                        class="btn btn-light-green">
                                    Speichern
                                </button>
                            </form>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Weitere Infos</h4>
                                </div>
                                <div class="col-md-6">
                                    <span class="didChangeFurtherInfo" id="didChangeFurtherInfo"></span>
                                </div>
                            </div>
                            <br>
                            <p>Bitte beschrieben sie Kurz und so weier und so fort...</p>
                            <form id="furtherInformationForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <i class="fas fa-pencil-alt prefix"></i>
                                            <textarea id="aktEnt" class="md-textarea form-control"
                                                      rows="5" name="dispRoute"><?php echo $dispRoute ?></textarea>
                                            <label for="aktEnt">Aktueller Entsorgungsweg + Preis</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <i class="fas fa-pencil-alt prefix"></i>
                                            <textarea id="processDescr" class="md-textarea form-control"
                                                      rows="5" name="procDescr"><?php echo $procDescr ?></textarea>
                                            <label for="processDescr">Prozessbeschreibung</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="sumbitFurtherInfo" name="sumbitMoreData"
                                        class="btn btn-light-green">
                                    Speichern
                                </button>
                            </form>
                            <br><br>

                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Dokumente</h4>
                                </div>
                                <div class="col-md-6">
                                    <span class="didChangeFiles" id="didChangeFiles"></span>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Analytik/ SBA/ BA</p>
                                    <div id="dropZone">
                                        <input type="file" id="fileupload" name="attachments[]" multiple>
                                    </div>
                                    <small id="smalltext" class="form-text text-muted mb-4">Nicht älter als 12 Monate
                                    </small>
                                    <p id="error"></p>
                                    <p id="progess"></p>
                                    <div class="existingFiles">
                                        <h4>Hochgeladene Dokumente</h4>
                                        <div class="gallery">
                                            <div id="einbinden"></div>
                                            <?php showFiles($conn, $userId); ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <p> Zertifikate (ISO, EfB)/ Genehmigung</p>
                                </div>
                            </div>
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
<!-- script für dragDrop file Input -->
<script src="js/fileUpload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.fileupload.js" type="text/javascript"></script>

<!-- Form verarbeiten -->
<script type="text/javascript">
    //Variablen für Prgressbar
    contactPersCheckVar = <?php echo $contactPersCheckVar?>;
    requestCheckVar = <?php echo $requestCheckVar?>;
    furtherInfoCheckVar = <?php echo $furtherInfoCheckVar?>;
    docOneCheckVar = <?php echo $docOneCheckVar?>;
</script>

<script type="text/javascript" src="js/formHandler.js"></script>

</body>
</html>