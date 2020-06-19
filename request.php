<?php
session_start();
include("components/session.php");
include("components/config.php");
include("components/script/databaseScript.php");
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
                            <h2><?php echo $lang['requestStatusRequest'] ?></h2>
                            <div class="progress">
                                <div class="progress-bar progress-bar-info progress-bar-striped active"
                                     style="width:<?php echo $progressBarValue ?>;"></div>
                            </div>
                            <p class="card-text"><?php echo $lang['requestFilledOutToo'] ?><span
                                        id="progressValue"><?php echo $progressValue ?></span>%
                                <?php echo $lang['requestFilledOutTooFine'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row wow fadeIn">
                <!--Grid column-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <form id="ansprech_Form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4><?php echo $lang['requestContactPerson'] ?></h4>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="didChangeContactPers" id="didChangeContactPers"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="firstname" name="firstname" class="form-control"
                                                   value="<?php echo $firstname ?>">
                                            <label for="firstname"><?php echo $lang['requestFirstname'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="surname" name="surname" class="form-control"
                                                   value="<?php echo $surname ?>">
                                            <label for="surname"><?php echo $lang['requestSurname'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-user input-prefix"></i>
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                   value="<?php echo $phone ?>">
                                            <label for="phone"><?php echo $lang['requestPhoneNumber'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <h4><?php echo $lang['requestCompanyAddress'] ?></h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="street" name="street" class="form-control"
                                                   value="<?php echo $street ?>">
                                            <label for="street"><?php echo $lang['requestStreetAndNumber'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="zipcode" name="zip" class="form-control"
                                                   value="<?php echo $zip ?>">
                                            <label for="zipcode"><?php echo $lang['requestZip'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-home input-prefix"></i>
                                            <input type="text" id="town" name="town" class="form-control"
                                                   value="<?php echo $town ?>">
                                            <label for="town"><?php echo $lang['requestTown'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="facility" name="facility" value="<?php echo $factory ?>">
                                <input type="hidden" name="requestId" value="<?php $requestId ?>">
                                <button type="submit" id="submitAnsprech" name="submitAnsprech" value=""
                                        class="btn btn-light-green"><?php echo $lang['requestContactInformationButton'] ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column-->
                <div class="sidebar">
                    <!--Grid column (checkliste)-->
                    <div class="col-md-4 mb-4">
                        <!--Card-->
                        <div class="sidecard mb-4">
                            <!-- Card header -->
                            <div class="sidecard-header">
                            </div>
                            <!--Card content-->
                            <div class="sidecard-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <?php echo $lang['requestChecklistContactPerson'] ?>
                                    </div>
                                    <div class="col-sm-4" id="contactPersCheck">
                                        <?php echo $contactPersCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <?php echo $lang['requestChecklistRequest'] ?>
                                    </div>
                                    <div class="col-sm-4" id="requestCheck">
                                        <?php echo $requestCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <div id="checkIsHidden">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <?php echo $lang['requestChecklistDocuments'] ?>
                                        </div>
                                        <div class="col-sm-4" id="docOneCheck">
                                            <?php echo $docOneCheck ?>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <?php echo $lang['requestChecklistFurtherInfo'] ?>
                                    </div>
                                    <div class="col-sm-4" id="furtherInfoCheck">
                                        <?php echo $furtherInfoCheck ?>
                                    </div>
                                </div>
                                <hr>
                                <form id="requestFilledOut" action="userDashboard.php" method="post">
                                    <input type="hidden" name="requestId" value="<?php echo $requestId ?>">
                                    <div class="row" id="filledOut">
                                        <?php echo $buttonRequestFilledOut ?>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <!--/.Card-->
                    </div>
                    <!--Grid column End (Checkliste)-->
                </div><!--End Sidebar -->

                <!--Grid column (AnfrageDetails)-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <form id="request_Form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4><?php echo $lang['requestRequest'] ?></h4>
                                        <p>a. <?php echo $lang['requestSpecifyRequest'] ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="didChangeRequest" id="didChangeRequest"></span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><?php echo $lang['requestWhatsAbout'] ?>:</p>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="produkt"
                                                   name="prodAbf" value="Produktstatus" <?php echo $radioOnPro ?>>
                                            <label class="custom-control-label" for="produkt"> <?php echo $lang['requestWhatsAboutProduct'] ?></label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="abfall"
                                                   name="prodAbf" value="Abfall" <?php echo $radioOnAbf ?>>
                                            <label class="custom-control-label" for="abfall"><?php echo $lang['requestWhatsAboutWaste'] ?></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-trash input-prefix"></i>
                                            <input type="text" id="abfallbezeichnung" class="form-control"
                                                   name="wasteDescription"
                                                   value="<?php echo $wasteDescription ?>">
                                            <label for="abfallbezeichnung"><?php echo $lang['requestWasteDescription'] ?></label>
                                        </div>
                                        <div class="abfallstatus">
                                            <div class="md-form input-with-post-icon">
                                                <i class="fas fa-trash input-prefix"></i>
                                                <input type="text" id="avv" class="form-control" name="avv"
                                                       value="<?php echo $avv ?>">
                                                <label for="avv"><?php echo $lang['requestWasteDescriptionAVV'] ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="produktstatus">
                                    <br>
                                    <span class="didChangeFiles" id="didChangeFiles"></span>
                                    <p><?php echo $lang['requestLoadCertificate'] ?></p>
                                    <div id="dropZone">
                                        <input type="file" id="fileupload" name="attachments[]" multiple>
                                    </div>
                                    <small id="smalltext"
                                           class="form-text text-muted mb-4"><?php echo $lang['requestDocMutedText'] ?>
                                    </small>
                                    <p id="error"></p>
                                    <p id="progess"></p>
                                    <div class="existingFiles">
                                        <p><?php echo $lang['requestDocLoadDoc'] ?>:</p>
                                        <div class="gallery">
                                            <?php showFiles($conn, $requestId, $userId); ?>
                                            <div id="einbinden"></div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><?php echo $lang['requestYouAre'] ?>:</p>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="erzeuger"
                                                   name="erzHae" <?php echo $radioOnErz ?> value="Erzeuger">
                                            <label class="custom-control-label"
                                                   for="erzeuger"><?php echo $lang['requestProducer'] ?></label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="haendler"
                                                   name="erzHae" <?php echo $radioOnHae ?> value="Händler">
                                            <label class="custom-control-label"
                                                   for="haendler"><?php echo $lang['requestDealer'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="erzeuger">
                                            <div class="md-form input-with-post-icon">
                                                <i class="fas fa-user input-prefix"></i>
                                                <input type="text" id="producer" class="form-control" name="producer"
                                                       value="<?php echo $producer ?>">
                                                <label for="producer"><?php echo $lang['requestNameProducer'] ?></label>
                                            </div>
                                        </div>
                                        <div class="haendler">

                                        </div>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="incoterms"><?php echo $lang['requestLableIncoterm'] ?>:</label>
                                        <select class="form-control" id="incoterms" name="deliveryForm">
                                            <option value="EXW" <?php echo $deliveryFormEXW ?>>EXW</option>
                                            <option value="FCA" <?php echo $deliveryFormFCA ?>>FCA</option>
                                            <option value="CPT" <?php echo $deliveryFormCPT ?>>CPT</option>
                                            <option value="CIP" <?php echo $deliveryFormCIP ?>>CIP</option>
                                            <option value="DAP" <?php echo $deliveryFormDAP ?>>DAP</option>
                                            <option value="DAT" <?php echo $deliveryFormDAT ?>>DAT</option>
                                        </select>
                                        <br>
                                        <i><?php echo $lang['requestIcotermsDescription'] ?>
                                            <a href="https://www.stuttgart.ihk24.de/fuer-unternehmen/international/internationales-wirtschaftsrecht/internationale-liefergeschaefte/incoterms/incoterms-2010-684806"
                                                    target="_blank"><?php echo $lang['requestIcotermsDescriptionTwo'] ?>
                                            </a> <?php echo $lang['requestIcotermsDescriptionThree'] ?>
                                        </i>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-weight-hanging input-prefix"></i>
                                            <input type="text" id="materialQuantity" class="form-control" name="jato"
                                                   value="<?php echo $jato ?>">
                                            <label for="materialQuantity"><?php echo $lang['requestLableAmountTonne'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="incoterms"><?php echo $lang['requestLableWeightCondition'] ?></label>
                                        <select class="form-control" id="incoterms" name="deliveryForm">
                                            <option value="Jato"><?php echo $lang['requestLableSelectWeightConditionJato'] ?></option>
                                            <option value="Saison"><?php echo $lang['requestLableSelectWeightConditionSaison'] ?></option>
                                            <option value="Spotmenge"><?php echo $lang['requestLableSelectWeightConditionSpot'] ?></option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" id="sumbitAnfrage" name="sumbitAnfrage"
                                        class="btn btn-light-green">
                                    <?php echo $lang['requestRequestButtonSave'] ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <div class="col-md-4" id="output">

                </div>
                <!--Grid column (End Anfrage)-->

                <!--Grid column (Weitere Details)-->
                <div class="col-md-8 mb-4">
                    <!--Card-->
                    <div class="card">
                        <!--Card content-->
                        <div class="card-body">
                            <form id="furtherInformationForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4><?php echo $lang['requestFurtherInformationTitle'] ?></h4>
                                        <p>b. <?php echo $lang['requestFurtherInformationSubtitle'] ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="didChangeFurtherInfo" id="didChangeFurtherInfo"></span>
                                    </div>
                                </div>
                                <p><?php echo $lang['requestFurthInfWriteData'] ?>:</p>
                                <p><?php echo $lang['requestInfoParaList'] ?>!</>
                                <br>

                                <p><?php echo $lang['requestParamIsWhat'] ?>: <span style="font-weight: 600"
                                                                                    id="huResult"></span></p>
                                <div class="anfrage-wrapper">
                                    <div id="param-div">
                                        <!-- PFLICHT FELDER -->
                                        <!-- Unterer Heizwert -->
                                        <div class="ing-row" id="row0">
                                            <input type="text" name="param" value="Unterer Heizwert" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $unterHo ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option>mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option selected="">mj/kg</option>
                                            </select>
                                        </div>

                                        <!-- Wassergehalt -->
                                        <div class="ing-row brennstoff rohstoff" id="row1">
                                            <input type="text" name="param" value="Wassergehalt" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $wassergehalt ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option>mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option selected="">% OS</option>
                                            </select>
                                        </div>

                                        <!-- Aschegehalt -->
                                        <div class="ing-row brennstoff" id="row2">
                                            <input type="text" name="param" value="Aschegehalt" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $aschegehalt ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option>mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option selected="">% TS</option>
                                            </select>
                                        </div>

                                        <!-- Chlor -->
                                        <div class="ing-row brennstoff rohstoff" id="row3">
                                            <input type="text" name="param" value="Chlor" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $chlor ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option>mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option selected="">% TS</option>
                                            </select>
                                        </div>

                                        <!-- Schwefel -->
                                        <div class="ing-row brennstoff rohstoff" id="row4">
                                            <input type="text" name="param" value="Schwefel" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $schwefel ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option>mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option selected="">% TS</option>
                                            </select>
                                        </div>

                                        <!-- Quecksilber -->
                                        <div class="ing-row brennstoff rohstoff" id="row5">
                                            <input type="text" name="param" value="Quecksilber" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $quecksilber ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">mg/kg</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>


                                        <!-- Calcium -->
                                        <div class="ing-row rohstoff" id="row6">
                                            <input type="text" name="param" value="Calcium" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $calcium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>

                                        <!-- Silizium -->
                                        <div class="ing-row rohstoff" id="row7">
                                            <input type="text" name="param" value="Silizium" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $silicium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>


                                        <!-- Eisen -->
                                        <div class="ing-row rohstoff" id="row8">
                                            <input type="text" name="param" value="Eisen" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $eisen ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>

                                        <!-- Magnesium -->
                                        <div class="ing-row rohstoff" id="row9">
                                            <input type="text" name="param" value="Magnesium" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $magnesium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>

                                        <!-- Kaliumoxid -->
                                        <div class="ing-row rohstoff" id="row10">
                                            <input type="text" name="param" value="Kaliumoxid" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $kalium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>

                                        <!-- Natriumoxid -->
                                        <div class="ing-row rohstoff" id="row11">
                                            <input type="text" name="param" value="Natriumoxid" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $natrium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>

                                        <!-- Aluminium -->
                                        <div class="ing-row rohstoff" id="row12">
                                            <input type="text" name="param" value="Aluminium" disabled=""/>
                                            <input type="text" name="value" placeholder="Messwert"
                                                   value="<?php echo $aluminium ?>"
                                                   autocomplete="off"
                                                   required pattern="[0-9<>,]{1,}"
                                                   title="Nur '1-9', ',' und '< >'"/>
                                            <select name="unit" disabled="">
                                                <option selected="">% OS</option>
                                                <option>ng/kg</option>
                                                <option>µg/g</option>
                                                <option>mj/kg</option>
                                                <option>% TS</option>
                                                <option>% OS</option>
                                            </select>
                                        </div>


                                        <?php echo $rowContent ?>
                                    </div>

                                    <input id="analysisString" type="hidden" name="analysisString"
                                           placeholder="Json String of analysis-input" disabled="">
                                    <button type="button" id="addRowButton" class="btn addParam">
                                        + <?php echo $lang['requestAddParam'] ?>
                                    </button>
                                    <br>
                                    <!-- für entwicklungszwecke<?php echo "parameterlistenlänge: " . $countJsonParam ?>-->


                                </div>
                                <br>
                                <hr>
                                <br>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="priceCondition"><?php echo $lang['requestFurtherInfLabelGeoOrUser'] ?></label>
                                        <select class="form-control" id="priceCondition" name="priceCondition">
                                            <option value="0" <?php echo $preisForUser ?>><?php echo $lang['requestFurtherInfpaymentUser'] ?></option>
                                            <option value="1" <?php echo $preisForGeo ?>><?php echo $lang['requestFurtherInfpaymentGeo'] ?></option>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form input-with-post-icon">
                                            <i class="fas fa-weight-hanging input-prefix"></i>
                                            <input type="number" id="offeredPrice" class="form-control"
                                                   name="offeredPrice"
                                                   value="<?php echo $offeredPrice ?>">
                                            <label for="offeredPrice"><?php echo $lang['requestFurtherInfPriceTonn'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <i class="fas fa-pencil-alt prefix"></i>
                                            <textarea id="aktEnt" class="md-textarea form-control" id="dispRoute"
                                                      rows="5" name="dispRoute"><?php echo $dispRoute ?></textarea>
                                            <label for="aktEnt"><?php echo $lang['requestFurtherInfWasteway'] ?></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-form">
                                            <i class="fas fa-pencil-alt prefix"></i>
                                            <textarea id="processDescr" class="md-textarea form-control" id="procDescr"
                                                      rows="5" name="procDescr"><?php echo $procDescr ?></textarea>
                                            <label for="processDescr"><?php echo $lang['requestFurtherInfProcessDescription'] ?></label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" id="sumbitFurtherInfo" name="sumbitMoreData"
                                        class="btn btn-light-green">
                                    <?php echo $lang['requestFurtherInfSave'] ?>
                                </button>
                            </form>
                            <br><br>
                        </div>
                    </div>
                    <!--/.Card-->
                </div>
                <!--Grid column (End Weitere Details)-->
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
<!-- script für dragDrop file Input -->
<script src="js/fileUpload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="js/fileUpload/jquery.fileupload.js" type="text/javascript"></script>

<!-- Form verarbeiten -->
<script type="text/javascript">
    //Variablen für Prgressbar
    contactPersCheckVar = <?php echo $contactPersCheckVar?>;
    requestCheckVar = <?php echo $requestCheckVar?>;
    furtherInfoCheckVar = <?php echo $furtherInfoCheckVar?>;
    docOneCheckVar = <?php echo $docOneCheckVar?>;
</script>

<!--Requestseiten-scripte -->
<script type="text/javascript" src="js/requestJs/autocomplete.js"></script>
<script type="text/javascript">
    /**
     * Adds autocomplete list to AVV-Input field
     **/
    let avvInput = document.getElementById('avv');
    autocomplete(avvInput, avvSuggs); // adds autocomplete for Parameter-Input

    // popovers Initialization - Kommentarfelder
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>

<script type="text/javascript" src="js/requestJs/radioSelection.js"></script>
<script type="text/javascript" src="js/formHandler.js"></script>

</body>
</html>
