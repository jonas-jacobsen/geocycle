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
    <?php include("components/navbar.php") ?>
    <!--Main Navigation-->
    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <div class="container-fluid mt-5">
            <!--Grid row-->
            <div class="row wow fadeIn">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2>Alle Anfragen</h2>
                            <table id="dtBasicExample" class="table" width="100%">
                                <thead>
                                <tr>
                                    <th class="th-sm">Anfrage ID
                                    </th>
                                    <th class="th-sm">Vorname
                                    </th>
                                    <th class="th-sm">Nachname
                                    </th>
                                    <th class="th-sm">Telefonnummer
                                    </th>
                                    <th class="th-sm">Straße
                                    </th>
                                    <th class="th-sm">Postleitzahl
                                    </th>
                                    <th class="th-sm">Ort
                                    </th>
                                    <th class="th-sm">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                    <td>$170,750</td>
                                    <td>
                                        <button type="" id="" name="" value=""
                                                class="btn btn-light-green">Anzeigen
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>2011/07/25</td>
                                    <td>$170,750</td>
                                    <td>$170,750</td>
                                    <td>
                                        <button type="" id="" name="" value=""
                                                class="btn btn-light-green">Anzeigen
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--End Grid row-->
    </main>
</div>


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
    $(document).ready(function () {
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
    });
</script>
</body>
</html>