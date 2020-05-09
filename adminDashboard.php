<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/script/adminDashboardScript.php");
include("components/headerAdmin.php");
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
                            <h2>Alle Anfragen</h2>
                            <table id="dtBasicExample" class="table" width="100%"style="margin-bottom: 0px;">
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
                                    <?php showAllRequest($conn) ?>
                                </tbody>
                            </table>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/js/mdb.min.js"></script>
<!-- script for Charts -->
<script type="text/javascript" src="js/charts/charts.js"></script>
<!-- Script for Datatable-->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#dtBasicExample').DataTable( {
            "scrollX": true
        } );
    } );
</script>
</body>
</html>