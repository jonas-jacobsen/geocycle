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
$name = $rowRequest["Firstname"].' '.$rowRequest["Surname"].",\n";

//Unternehmen aus DB laden
$sqlComapny = "SELECT Company, Email FROM user WHERE id = $userIdFromRequest";
$stmtCompany = mysqli_query($conn, $sqlComapny);
$rowCompany = mysqli_fetch_array($stmtCompany);

if ($rowRequest['Producer'] == "") {
    $producer = "Anfragender ist Produzent";
} else {
    $producer = $rowRequest['Producer'];
}

//Preisberechnung Zuzahlung für Geo oder User
$offeredPrice = $rowRequest['OfferedPrice'];
if ($offeredPrice < 0) {
    $offeredPriceHtml = "<u>Kosten</u> für Geocycle: " . abs($offeredPrice) . "€";
} else {
    $offeredPriceHtml = "Kosten für Kunden: " . $offeredPrice . "€";
}

//variable für html-code für Druck von PDFs
$printFiles = "";
//alle dokuemnte Anzeigen
function showFiles($conn, $requestId, $userId)
{
    $sql_show_files = "SELECT * FROM docOne WHERE RequestId = $requestId";
    $statement_show_fiels = mysqli_query($conn, $sql_show_files);
    while ($rowPath = mysqli_fetch_array($statement_show_fiels)) {
        $docsForPrint = "";
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

        //$docsForPrint .= '<iframe name="'.$filePath.'" width="100%" height="500px" src="' . $filePath . '"></iframe>';
    }
    return $docsForPrint;
}

?>
<div class="container-for-admin">
    <!--Main Navigation-->
    <?php include("components/adminTeamNavbar.php") ?>
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
                                    <h4><?php echo $rowCompany['Company']?></h4>
                                    <h5>Anfrage-ID: <?php echo $rowRequest['id']?></h5>
                                </div>
                                <div class="col-md-6" style="text-align: right">
                                    <button id="printHiddenDiv" onclick="{window.print()}" class="btn btn-outline-success waves-effect"><i
                                                class="fas fa-print"></i></button>
                                </div>
                            </div>
                            <!--Analyse funktionaufrufen -->
                            <?php analyse($requestId, $conn) ?>
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
                                    <?php echo $rowRequest['JaTo'] . " " . $rowRequest['WeightForm'] ?>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <h5>Produzent:</h5>
                                    <?php echo $rowRequest['Producer'] ?>
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
                                    <? echo $rowRequest['DeliveryForm'] ?>
                                </div>
                                <div class="col-md-4">
                                    <h5>Preis:</h5>
                                    <? echo $offeredPriceHtml ?>
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
                            <hr id="printHiddenDiv">
                            <div class="existingFiles">
                                <h5>Dokumente</h5><br>
                                <div class="gallery">
                                    <?php $docsForPrint = showFiles($conn, $RequestIdFromUser, $userIdFromRequest); ?>
                                    <div id="einbinden"></div>
                                </div>
                            </div>
                            <hr id="printHiddenDiv">
                            <div id="docForPrint">
                                <?php echo $docsForPrint ?>
                            </div>

                            <div id="printHiddenDiv">
                                <h4>Anfrage beantworten</h4>
                                <form action="adminDashboardTeam.php" id="replyBox" method="post">
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked1"
                                                       name="defaultExampleRadios" value="Ein oder mehrere Parameter sind nicht im gewünschten Bereich. ">
                                                <label class="custom-control-label" for="defaultUnchecked1">Ein oder mehrere Parameter sind nicht im gewünschten Bereich.</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked2"
                                                       name="defaultExampleRadios" value="Die Stoffmenge ist zu gering. ">
                                                <label class="custom-control-label" for="defaultUnchecked2">Die Stoffmenge ist zu gering.</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked3"
                                                       name="defaultExampleRadios" value="">
                                                <label class="custom-control-label" for="defaultUnchecked3"></label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked7"
                                                       name="defaultExampleRadios" value="Keinen Standardtext auswählen. ">
                                                <label class="custom-control-label" for="defaultUnchecked7">Keinen Standardtext auswählen.</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked4"
                                                       name="defaultExampleRadios" value="Keine Kapazitäten in den Werken frei. ">
                                                <label class="custom-control-label" for="defaultUnchecked4">Keine Kapazitäten in den Werken frei.</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked5"
                                                       name="defaultExampleRadios" value="Weitere Analysen notwendig. ">
                                                <label class="custom-control-label" for="defaultUnchecked5">Weitere Analysen notwendig.</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" id="defaultUnchecked6"
                                                       name="defaultExampleRadios" value="Angaben Fehlen. Bitte in der Anfrage ergänzen. ">
                                                <label class="custom-control-label" for="defaultUnchecked6">Angaben Fehlen. Bitte in der Anfrage ergänzen.</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <!--Material textarea-->
                                            <div class="md-form">
                                                <textarea id="form7" class="md-textarea form-control" rows="5" name="textfield">Hallo <?php echo $name?></textarea>
                                                <label for="form7">Freitext für Zu- oder Absage</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <input type="hidden" name="listForCSV"
                                               value="<?php echo $_SESSION['valuesForCSV'] ?>">
                                        <div class="col-6" style="text-align: left">
                                            <button type="submit" name="buttonSubmit" value="1"
                                                    class="btn btn-outline-success waves-effect">Annehmen
                                            </button>
                                        </div>
                                        <div class="col-6" style="text-align: right">
                                            <button type="submit" name="buttonSubmit" value="0"
                                                    class="btn btn-outline-danger waves-effect">Ablehnen
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="requestId" value="<?php echo $requestId ?>">
                                </form>
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
                                <?php echo $rowCompany['Email'] ?>
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
<script type="text/javascript">

    //for debugging
    var paramValuesForCSV = $("#paramListForCSV").val();

    $(document).ready(function(){

        $('#replyBox').change(function(){
            selected_value = $("input[name='defaultExampleRadios']:checked").val();
            $('#form7').append(selected_value);
        });
    });




</script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/js/mdb.min.js"></script>
</body>
</html>