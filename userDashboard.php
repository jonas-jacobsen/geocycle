<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/script/userDashboardScript.php");
include("components/header.php");
?>

<body>
<div class="container-for-admin">
    <!--Main Navigation-->
    <?php include("components/navbar.php") ?>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <?php echo $msgModalSendRequest?>
            <form action="" method="post">
                <button type="submit" id="newRequest" name="newRequest" value="1"
                        class="btn btn-outline-success waves-effect"><?php echo $lang['userDashboardCreateNewRequest']?>
                </button>
            </form>
            <br><br>
            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2><?php echo $lang['userDashboardOpenRequest']?></h2>
                            <?php showOpenRequest($conn, $userId, $lang) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2><?php echo $lang['userDashboardSendRequest']?></h2>
                            <?php showCloseRequest($conn, $userId, $lang) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--End Grid row-->
    </main>
</div>

<?php include("components/footer.php") ?>
<!--Modal anzeigenlassen -->
<?php include("components/modal.php") ?>
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

<script type="text/javascript">
    //modal das erste mal anzeigen
    var modalShow = <?php echo $modalShow?>;
    //$('#fullHeightModalRight').modal('show');
    if (modalShow == 0) {
        // $('#fullHeightModalRight').modal('show');
    } else {

    }
</script>
</body>
</html>