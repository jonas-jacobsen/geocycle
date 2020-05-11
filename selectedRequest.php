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