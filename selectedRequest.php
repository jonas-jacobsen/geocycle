<?php
//session_start();
//include("components/session.php");
include("components/config.php");
include("components/headerAdmin.php");
include("components/script/analyse/analyse.php");


$requestId = $_GET['selectedRequest'];
$sqlSelectRequest = "SELECT * FROM userdata WHERE id = $requestId";
$stmtSelectRequest = mysqli_query($conn, $sqlSelectRequest);
$rowRequest = mysqli_fetch_array($stmtSelectRequest);
$RequestIdFromUser = $rowRequest['id'];
$userIdFromRequest = $rowRequest['userId'];

if($rowRequest['Producer'] == ""){
    $producer = "Anfragender ist Produzent";
}else{
    $producer = $rowRequest['Producer'];
}

//Preisberechnung Zuzahlung für Geo oder User
$offeredPrice = $rowRequest['OfferedPrice'];
if($offeredPrice < 0){
    $offeredPriceHtml = "<u>Kosten</u> für Geocycle: ".abs($offeredPrice)."€";
}else{
    $offeredPriceHtml = "Kosten für Kunden: ".$offeredPrice."€";
}

//alle dokuemnte Anzeigen
function showFiles($conn, $requestId, $userId)
{
    $sql_show_files = "SELECT * FROM docOne WHERE RequestId = $requestId";
    $statement_show_fiels = mysqli_query($conn, $sql_show_files);
    while ($rowPath = mysqli_fetch_array($statement_show_fiels)) {
        $filePath = $rowPath['Path'];
        $fileName = explode("uploads/$userId/$requestId", $filePath);
        $fileId = $rowPath['id'];
        //untescheidung zwischen PDF oder bild
        //voschaubild
        //mit a href link
        if (stristr($filePath, '.pdf') == true) {
            $icon = "pdfIcon.png";
        } else {
            $icon = "imgIcon.png";
        }

        echo '
        <div id="' . $fileId . '">
            <div class="view overlay hm-green-slight">
                <figure><a href="' . $filePath . '" target="_blank"><img style="width: 100%" src="assets/images/' . $icon . '"></a>
                    <div class="mask flex-center">
                        <a type="button" href="' . $filePath . '" target="_blank" class="showButton">Anzeigen</a>
                    </div>
                </figure>
            </div>
            <div style="text-align: center">
            <br>
                <p>' . $fileName[1] . '</p>  
                <input type="hidden" name="deleteFileId" value="' . $fileId . '">
            </div>  
        </div>
        ';
    }
}


?>
<div class="container-for-admin">
    <!--Main Navigation-->
    <?php include("components/navbarAdmin.php") ?>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <!--Grid row-->

            <div class="row wow fadeIn">
                <!--Grid column-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h1>Anfrage <?php echo $rowRequest['id'] ?></h1>
                                </div>
                                <div class="col-md-6" style="text-align: right">
                                    <button onclick="{window.print()}" class="btn btn-outline-success waves-effect"><i class="fas fa-print"></i></button>
                                </div>
                            </div>
                            <?php analyse($requestId, $conn)?>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Status:</h5>
                                    <?php echo $rowRequest['ProdAbf'] ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Erzeuger/Händler: </h5>
                                    <?php echo $rowRequest['ErzHae'] ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Menge: </h5>
                                    <?php echo $rowRequest['JaTo'] ." ". $rowRequest['WeightForm']?>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Produzent:</h5>
                                    <?php echo $producer ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Abfallbezeichnung:</h5>
                                    <?php echo $rowRequest['WasteDescription'] ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>AVV: </h5>
                                    <?php echo $rowRequest['Avv'] ?>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Anlieferform: </h5>
                                    <?php echo $rowRequest['DeliveryForm'] ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Lieferkondition:</h5>
                                    <?echo $rowRequest['Avv']?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Preis:</h5>
                                    <?echo $offeredPriceHtml?>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Aktueller Entsorgungsweg: </h5>
                                    <?php echo $rowRequest['DisposalRoute'] ?>
                                </div>
                                <div class="col-md-6">
                                    <h5>Prozessbeschreibung: </h5>
                                    <?php echo $rowRequest['ProcessDescription'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="existingFiles">
                                <h5>Dokumente</h5><br>
                                <div class="gallery">
                                    <?php showFiles($conn, $RequestIdFromUser, $userIdFromRequest); ?>
                                    <div id="einbinden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column-->

                <!--Grid column (checkliste)-->
                <div class="col-md-4 mb-4">
                    <div class="sidebar">
                        <!--Card-->
                        <div class="sidecard mb-4">
                            <!-- Card header -->
                            <div class="sidecard-header">
                            </div>
                            <!--Card content-->
                            <div class="sidecard-body">
                                <i class="fas fa-user"></i> Ansprechpartner<br>
                                <?php echo $rowRequest['Firstname'] . " " . $rowRequest['Surname'] ?>
                                <hr>
                                <i class="fas fa-user"></i> Telefonnummer<br>
                                <?php echo $rowRequest['Phone'] ?>
                                <hr>
                                <i class="fas fa-user"></i> Emailadresse<br>
                                <?php echo $rowRequest['Phone'] ?>
                                <hr>
                                <i class="fas fa-home"></i> Addresse<br>
                                <?php echo $rowRequest['Street'] . " " . $rowRequest['Zip'] . " " . $rowRequest['Town'] ?>
                            </div>
                        </div>
                        <!--/.Card-->
                    </div><!--End Sidebar -->
                </div>
                <!--Grid column End (Checkliste)-->
            </div>
        </div> <!-- End Container Float -->
        <!--Grid row-->
    </main>
    <!--Main layout-->

</div>
<?php include("components/footer.php") ?>

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


</body>
</html>