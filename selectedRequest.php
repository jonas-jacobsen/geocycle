<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/headerAdmin.php");

$requestId = $_POST['selectedRequest'];
$sqlSelectRequest = "SELECT * FROM userdata WHERE id = $requestId";
$stmtSelectRequest = mysqli_query($conn, $sqlSelectRequest);
$rowRequest = mysqli_fetch_array($stmtSelectRequest);

?>

<body>
<div class="container-for-admin">
    <!--Main Navigation-->
    <?php include("components/navbarAdmin.php") ?>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Anfrage <?php echo $rowRequest['id']?></h2>
                            <div class="row wow fadeIn">
                                <!--Grid column-->
                                <div class="col-sm-4">
                                    <h4>Vorname</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Firstname']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-4">
                                    <h4>Nachname</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Surname']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-4">
                                    <h4>Vorname</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Phone']?></p>
                                </div> <!-- End Grid column-->
                            </div><!-- End row-->
                            <div class="row wow fadeIn">
                                <!--Grid column-->
                                <div class="col-sm-4">
                                    <h4>Straße</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Street']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-4">
                                    <h4>Ort</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Town']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-4">
                                    <h4>Postleitzahl</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Zip']?></p>
                                </div> <!-- End Grid column-->
                            </div><!-- End row-->
                            <hr>
                            <div class="row wow fadeIn mt-5">
                                <!--Grid column-->
                                <div class="col-sm-3">
                                    <h4>Typ</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['ProdAbf']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4>Kunde ist</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['ErzHae']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4>Erzeuger</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Producer']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4>Abfallbeschreibung</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['WasteDescription']?></p>
                                </div> <!-- End Grid column-->
                            </div><!-- End row-->
                            <div class="row wow fadeIn">
                                <!--Grid column-->
                                <div class="col-sm-3">
                                    <h4>Menge</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['JaTo']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4>Anlieferform</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['DeliveryForm']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4>AVV</h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['Avv']?></p>
                                </div> <!-- End Grid column-->
                                <div class="col-sm-3">
                                    <h4></h4>
                                    <p class="text-muted mb-4"><?php echo $rowRequest['']?></p>
                                </div> <!-- End Grid column-->
                            </div><!-- End row-->


                        </div><!--End card body-->
                    </div><!--End Card-->
                </div><!--End col md -->
            </div><!--End row-->
        </div><!--container-fluid mt-5-->
    </main>
</div>

<!-- Footer anzeigen -->
<?php include("components/footer.php") ?>

<!-- JQuery -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/js/mdb.min.js"></script>

</body>
</html>