<?php
//session_start();
//include("components/session.php");
include("components/config.php");
include("components/script/adminDashboardTeamScript.php");
include("components/headerAdmin.php");
?>

<body>
<div class="container-for-admin">
    <!--Main Navigation-->
    <?php include("components/adminTeamNavbar.php") ?>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">

        <?php echo $errorShow ?>
        <div class="container-fluid mt-5">
            <!--Grid row-->
            <div class="row wow fadeIn">
                <!--Grid column-->
                <div class="col-lg-6 col-md-6 mb-4">
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
                <!--Grid column-->

                <!--Grid column-->
                <div class="col-lg-6 col-md-6 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header white">Brennstoff / Rohstoff</div>
                        <!--Card content-->
                        <div class="card-body">
                            <canvas id="pieChart"></canvas>
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
                            <h2>Alle Anfragen für Team 1</h2>
                            <table id="dtBasicExample" class="table" width="100%" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="th-sm">Anfrage ID
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Ort
                                    </th>
                                    <th class="th-sm">Menge
                                    </th>
                                    <th class="th-sm">AVV
                                    </th>
                                    <th class="th-sm">Anlieferform
                                    </th>
                                    <th class="th-sm">Erzeuger
                                    </th>
                                    <th class="th-sm"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php showAllRequestForTeamOne($conn) ?>
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
                            <h2>Alle angenommenen Anfragen für Team 1</h2>
                            <table id="acceptedRequest" class="table" width="100%" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="th-sm">Anfrage ID
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Ort
                                    </th>
                                    <th class="th-sm">Menge
                                    </th>
                                    <th class="th-sm">AVV
                                    </th>
                                    <th class="th-sm">Anlieferform
                                    </th>
                                    <th class="th-sm">Erzeuger
                                    </th>
                                    <th class="th-sm"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php showAllAcceptedRequestForTeamOne($conn) ?>
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
                                <h4 class="display-4 text-right mb-0 count-up" data-from="0" data-to="250"
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
                                <h4 class="display-4 text-right mb-0 count1" data-from="0" data-to="50"
                                    data-time="2000">3,500</h4>
                            </div>

                            <div class="col-6">
                                <p class="text-uppercase font-weight-normal mb-1">Kunden</p>
                                <p class="mb-0"><i class="fas fa-user fa-2x mb-0"></i></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="row">
                            <div class="col-6 pr-0">
                                <h4 class="display-4 text-right"><span class="d-flex justify-content-end"><span
                                                class="count2" data-from="0" data-to="85"
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
<!-- script for Charts -->
<script type="text/javascript" src="js/charts/charts.js"></script>
<!-- Script for Datatable-->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#dtBasicExample').DataTable({
            "scrollX": true
        });
    });
    $(document).ready(function () {
        $('#acceptedRequest').DataTable({
            "scrollX": true
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