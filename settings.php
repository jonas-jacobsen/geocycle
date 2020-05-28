<?php
session_start();
include("components/sessionAdmin.php");
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
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Teamuser</h2>
                            <table id="teammembers" class="table" width="100%" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="th-sm">id
                                    </th>
                                    <th class="th-sm">Email
                                    </th>
                                    <th class="th-sm">Teamzuweisung
                                    </th>
                                    <th class="th-sm"></th>
                                </tr>
                                </thead>
                                <?php showAllTeammembers($conn) ?>
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
                            <h2>Security Code <i class="fas fa-shield-alt"></i></h2>
                            <p>Zum Anmelden neuer Teammitglieder benötigen diese einen Zugangscode</p>
                            <h6>Security Code ändern:</h6>
                            <form class="form-inline md-form mr-auto mb-4" id="changeSecCode" method="post">
                                <input class="form-control mr-sm-4" name="secCode" type="text" placeholder="<?php showSecCode($conn);?>" aria-label="<?php showSecCode($conn);?>">
                                <button class="btn" name="changeSecCode" type="submit">Ändern</button>
                            </form>
                            <h6 class="mt-5">Security Code verschicken:</h6>
                            <form class="form-inline md-form mr-auto mb-4" id="SendSecCode" method="post">
                                <input class="form-control mr-sm-4" name="emailSecCode" type="text" placeholder="maxmustermann@gmx.de" aria-label="maxmustermann@gmx.de">
                                <button class="btn" name="sendSecCode" type="submit">Senden</button>
                            </form>
                        </div>
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

<!-- Script for Datatable-->
<script type="text/javascript" src="js/addons/datatables.min.js"></script>

</body>
</html>