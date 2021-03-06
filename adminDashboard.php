<?php
session_start();
include("components/sessionAdmin.php");
include("components/config.php");
include("components/script/adminDashboardScript.php");
include("components/headerAdmin.php");
include("components/script/diagrammScripts.php");
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
                <!--Grid column-->
                <div class="col-lg-4 col-md-4 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header white">Anzahl der Anfragen</div>
                        <!--Card content-->
                        <div class="card-body">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!-- End Grid column-->
                <!--Grid column-->
                <div class="col-lg-4 col-md-4 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header white">Produkt- / Abfallstatus</div>
                        <!--Card content-->
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--End Grid column-->
                <!--Grid column-->
                <div class="col-lg-4 col-md-4 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header white">AVV Top 10</div>
                        <!--Card content-->
                        <div class="card-body">
                            <canvas id="pieChartAVV"></canvas>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column-->
            </div><!--row column-->

            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Neue Anfragen</h2>
                            <table id="dtBasicExample" class="table" width="100%" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="th-sm">Anfrage ID
                                    </th>
                                    <th class="th-sm">Beschreibung
                                    </th>
                                    <th class="th-sm">Abfall oder Produktstatus
                                    </th>
                                    <th class="th-sm">AVV
                                    </th>
                                    <th class="th-sm">Menge
                                    </th>
                                    <th class="th-sm">Anlieferform
                                    </th>
                                    <th class="th-sm">Anfrageeingang
                                    </th>
                                    <th class="th-sm">Zuweisen an</th>
                                    <th class="th-sm"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php showNewRequest($conn) ?>
                                </tbody>
                            </table>
                        </div><!--End card body-->
                    </div><!--End Card-->
                </div><!--End col md -->
            </div><!--End row-->

            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Alle Anfragen</h2>
                            <table id="allRequests" class="table" width="100%" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="th-sm">Anfrage ID
                                    </th>
                                    <th class="th-sm">Beschreibung
                                    </th>
                                    <th class="th-sm">Abfall oder Produktstatus
                                    </th>
                                    <th class="th-sm">AVV
                                    </th>
                                    <th class="th-sm">Menge
                                    </th>
                                    <th class="th-sm">Anfrageeingang
                                    </th>
                                    <th class="th-sm">Zugewiesen an
                                    </th>
                                    <th class="th-sm">Zuweisung ändern
                                    </th>
                                    <th class="th-sm"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php showAllRequest($conn) ?>
                                </tbody>
                            </table>
                        </div><!--End card body-->
                    </div><!--End Card-->
                </div><!--End col md -->
            </div><!--End row-->
            <!--Section: Content-->
            <section class="white-text green p-5 rounded mb-4">
                <div class="row">

                    <div class="col-md-4 mb-4">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <h4 class="display-4 text-right mb-0 count-up" data-from="0" data-to="<?php echo $rowCountOpenRequest['TotalCountOpen'] ?>"
                                    data-time="2000">42</h4>
                            </div>

                            <div class="col-6">
                                <p class="text-uppercase font-weight-normal mb-1">Offene Anfragen</p>
                                <p class="mb-0"><i class="fas fa-briefcase fa-2x mb-0"></i></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <h4 class="display-4 text-right mb-0 count1" data-from="0" data-to="<?php echo $rowCountOpenUser['TotalCountUser'] ?>"
                                    data-time="2000">3,500</h4>
                            </div>

                            <div class="col-6">
                                <p class="text-uppercase font-weight-normal mb-1">Nutzer</p>
                                <p class="mb-0"><i class="fas fa-user fa-2x mb-0"></i></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <h4 class="display-4 text-right"><span class="d-flex justify-content-end"><span
                                                class="count2" data-from="0" data-to="<?php echo $acceptedPercent ?>"
                                                data-time="2000">0</span> %</span></h4>
                            </div>
                            <div class="col-6">
                                <p class="text-uppercase font-weight-normal mb-1">Erfolgreich angenommen</p>
                                <p class="mb-0"><i class="fas fa-smile fa-2x mb-0"></i></p>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
            <!--Section: Content-->

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
<!-- variablen for Charts-->
<script type="text/javascript">
    //Variablen für Monatsdiagramm
    var jan = <?php echo $totalJan ?>;var feb = <?php echo $totalFeb ?>; var mae = <?php echo $totalMae ?>; var apr = <?php echo $totalApr ?>; var mai = <?php echo $totalMai ?>; var jun = <?php echo $totalJun ?>; var jul = <?php echo $totalJul ?>; var aug = <?php echo $totalAug ?>; var sep = <?php echo $totalSep ?>; var okt = <?php echo $totalOkt ?>; var nov = <?php echo $totalNov ?>; var dec = <?php echo $totalDec ?>;
    //Variablen für AVV
    var avvLables = [<?php echo $avvPiechartLables ?>];
    var avvData = [<?php echo $avvPiechartData ?>];
    //Piechart Abfall/Produkt
    var prodAbfData = [<?php echo $totalProd ?>,<?php echo $totalAbf ?>];
</script>
<!-- script for Charts -->
<script type="text/javascript" src="js/charts/charts.js"></script>
<!-- Script for Datatable-->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>

<!--Script for Ajaxcalls -->
<script type="text/javascript" src="js/adminScript.js"></script>

<!-- datatables anzeigen-->
<script type="text/javascript">
    $(document).ready(function () {
        $('#dtBasicExample').DataTable({
            "scrollX": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/German.json"
            }
        });
        $('#allRequests').DataTable({
            "scrollX": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/German.json"
            }
        });
    });


    //counter
    (function ($) {
        $.fn.counter = function () {
            const $this = $(this),
                numberFrom = parseInt($this.attr('data-from')),
                numberTo = parseInt($this.attr('data-to')),
                delta = numberTo - numberFrom,
                deltaPositive = delta > 0 ? 1 : 0,
                time = parseInt($this.attr('data-time')),
                changeTime = 10;

            let currentNumber = numberFrom,
                value = delta * changeTime / time;
            var interval1;
            const changeNumber = () => {
                currentNumber += value;
                //checks if currentNumber reached numberTo
                (deltaPositive && currentNumber >= numberTo) || (!deltaPositive && currentNumber <= numberTo) ? currentNumber = numberTo : currentNumber;
                this.text(parseInt(currentNumber));
                currentNumber == numberTo ? clearInterval(interval1) : currentNumber;
            }

            interval1 = setInterval(changeNumber, changeTime);
        }
    }(jQuery));

    $(document).ready(function () {

        $('.count-up').counter();
        $('.count1').counter();
        $('.count2').counter();

        new WOW().init();

        setTimeout(function () {
            $('.count5').counter();
        }, 3000);
    });
</script>
</body>
</html>