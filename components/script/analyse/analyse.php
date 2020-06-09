<?php

//Funktion zu Preisfindung
function evaluatePrice()
{

}

//Funktion für die Findung des Größten Parameters
function showHighestParam($paramJson, $offeredPrice)
{
    $countJsonParam = count($paramJson);
    $highestValue = 0;
    $highestParam = "";

    $offeredPrice;

    $verglPreisEisen = -20;
    $verglPreisSilizium = -7;
    $verglPreisAluminium = -26;
    $verglPreisCalcium = 25;

    for ($i = 0; $i <= $countJsonParam; $i++) {
        if ($paramJson[$i]->value > $highestValue) {
            $highestValue = $paramJson[$i]->value;
            $highestParam = $paramJson[$i]->param;
        } else {
        }
    }
    echo "der Größte Parameter ist: " . $highestParam . " mit dem Wert " . $highestValue . "<br>";

    //Berechnung prozentualen Anteil des Stoffes
    $preisProTonne = $offeredPrice / ($highestValue / 100);

    //Prüfen, ob der höhste wert einer der vier hauptbestandteile von Klinker ist
    if ($highestParam == "Eisen") {
        echo "Stoff ist Hauptbestandteil";
        if ($verglPreisEisen < $offeredPrice) {
            echo "Stoff ist wirtschaftlich interessant";
        } else {
            echo "Stoff ist teurer als reiner Stoff";
        }
    } elseif ($highestParam == "Silizium") {
        echo "Stoff ist Hauptbestandteil";
        if ($verglPreisSilizium < $offeredPrice) {
            echo "Stoff ist wirtschaftlich interessant";
        } else {
            echo "Stoff ist teurer als reiner Stoff";
        }
    } elseif ($highestParam == "Aluminium") {
        echo "Stoff ist Hauptbestandteil";
        if ($verglPreisAluminium < $offeredPrice) {
            echo "Stoff ist wirtschaftlich interessant";
        } else {
            echo "Stoff ist teurer als reiner Stoff";
        }
    } elseif ($highestParam == "Calcium") {
        echo "Stoff ist Hauptbestandteil";
        if ($verglPreisCalcium < $offeredPrice) {
            echo "Stoff ist wirtschaftlich interessant";
        } else {
            echo "Stoff ist teurer als reiner Stoff";
        }
    } else {
        echo "Stoff ist nicht Hauotbestandteil";
        if ($offeredPrice >= 10) {
            echo "Ausschließlich wirtschaftlich Interessant";
        } else {
            echo "Wirtschaftlich und stofflich nicht intereessant";
        }
    }

    //Überprüfen ob zuzahlung oder Kosten für Geoocycle
    if ($preisProTonne < 0) {
        echo "<br>Für eine Tonne " . $highestParam . " kosten es Geocycle: " . round(abs($preisProTonne), 2) . " € Pro/Tonne<br>";
    } else {
        echo "<br>Für eine Tonne " . $highestParam . " erhält Geocycle: " . round($preisProTonne, 2) . " € Pro/Tonne<br>";
    }
}

function showAnalysis($requestId, $conn)
{

    $htmlCodeForAnalyse = "";

    $kohleVergPreis = 4;

    $sql = "SELECT * FROM userdata WHERE id = $requestId";
    $stmt = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($stmt);

    $offeredPrice = $row['OfferedPrice'];

    $amount = $row['JaTo'];
    $offeredPrice = floatval($row['OfferedPrice']);

    //ParameterLimits für die Vergleiche
    $paramLimitOfen = array(
        "unterHoParam" => "<20",
        "wasserParamLimit" => "<=25",
        "ascheParamLimit" => "<=30",
        "chlorParamLimit" => "<1",
        "schwefelParamLimit" => "<1",
        "quecksilberParamLimit" => "<=0.5",
    );
    $paramLimitHaupt = array(
        "unterHoParam" => ">=20",
        "wasserParamLimit" => "<=15",
        "ascheParamLimit" => "<=15",
        "chlorParamLimit" => "<1",
        "schwefelParamLimit" => "<1",
        "quecksilberParamLimit" => "<=0.5",
    );

    //Parameterliste aus DB laden und in Json objekt abspeichern;
    $paramJson = json_decode($row['ParameterList']);

    $unterHo = "";
    $wassergehalt = "";
    $aschegehalt = "";
    $chlor = "";
    $schwefel = "";
    $quecksilber = "";
    $calcium = "";
    $silicium = "";
    $eisen = "";
    $magnesium = "";
    $kalium = "";
    $natrium = "";
    $aluminium = "";

    //Paramter aus Json extrahieren und in varibalen speichern
    $unterHo = str_replace(',', '.', $paramJson[0]->value);
    $wassergehalt = str_replace(',', '.', $paramJson[1]->value);
    $aschegehalt = str_replace(',', '.', $paramJson[2]->value);
    $chlor = str_replace(',', '.', $paramJson[3]->value);
    $schwefel = str_replace(',', '.', $paramJson[4]->value);
    $quecksilber = str_replace(',', '.', $paramJson[5]->value);
    $calcium = str_replace(',', '.', $paramJson[6]->value);
    $silicium = str_replace(',', '.', $paramJson[7]->value);
    $eisen = str_replace(',', '.', $paramJson[8]->value);
    $magnesium = str_replace(',', '.', $paramJson[9]->value);
    $kalium = str_replace(',', '.', $paramJson[10]->value);
    $natrium = str_replace(',', '.', $paramJson[11]->value);
    $aluminium = str_replace(',', '.', $paramJson[12]->value);

    //Menge Überprüfen
    if ($amount > 5000) {
        $amounthtml = "Menge über 5000 Jahrestonnen";
        $checkamount = "<i style='color: green' class=\"far fa-check-circle\"></i>";
    } else {
        $amounthtml = "Menge zu gering";
        $checkamount = "<i style='color: red' class=\"far fa-times-circle\"></i>";
    }

    if ($unterHo < 10) {
        //heizwert unter 10 dann Rohstoff
        $materialOrBurn = "Rohstoff";
        showHighestParam($paramJson, $offeredPrice);

    } else {
        //heizwert über 10 dann Brennstoff
        $materialOrBurn = "Brennstoff";
        if ($unterHo < 20) {
            //Brennstoff Ofeneinlauf
            $fuelForWhat = "Offeneinlauf";
            $htmlAllBurn = checkParam($paramLimitOfen, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber);

        } else {
            //Brennstoff für Hauptbrenner
            $fuelForWhat = "Hauptbrenner";
            $htmlAllBurn = checkParam($paramLimitHaupt, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber);
        }//parameterübrüfung end
        //preiskalkulation
        $zuProKilo = $offeredPrice / 1000;
        if ($offeredPrice <= $kohleVergPreis) {
            $economicIsGood = "Wirtschaftlich und stofflich interessant, da der Brennstoff mit einem Preis von ".abs($offeredPrice)."€ günstiger ist als Kohle.";
        } elseif ($offeredPrice > $kohleVergPreis && $unterHo > 30) {
            //ToDo: was heißt brennt gut, wenn heizwert > 30 dann gut?
            $economicIsGood = "Wirtschaftlich nicht interessant, aber Stoff brennt heftig";
        } else {
            $economicIsGood = "Wirtschaftlich und stofflich nicht Interessant";
        }
        buildHtml("burn", $materialOrBurn,$fuelForWhat, $amount,$checkamount, $amounthtml, $htmlAllBurn, $economicIsGood, $unterHo);
    }
}

function buildHtml($type,$materialOrBurn,$fuelForWhat, $amount,$checkamount, $amounthtml, $htmlAllBurn, $economicIsGood, $unterHo){
    if ($type == "burn"){
        echo $htmlCodeForAnalyse = "
            <h4>$materialOrBurn</h4>
            <div class='row'>
                <div class='col-3'>
                    Unterer Heizwert
                </div>
                <div class='col-3'>
                   $unterHo  mj/t
                </div>
                <div class='col-1'>
                   
                </div>
                <div class='col-5'>
                    Für $fuelForWhat
                </div>
            </div>
            <div class='row'>
                <div class='col-3'>
                    Jahrestonnen: 
                </div>
                <div class='col-3'>
                   $amount t
                </div>
                <div class='col-1'>
                  $checkamount
                </div>
                <div class='col-5'>
                    $amounthtml
                </div>
            </div>
            $htmlAllBurn
            <br>
            Fazit: $economicIsGood
            <hr>
    ";
    }else{

    }

}

//funktion zur Überprüfung der Parameter für den Ofeneinlauf
function checkParam($paramLimit, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber)
{
    //build condition String
    $queckIf = "$quecksilber" . $paramLimit['quecksilberParamLimit'];
    $wasserIf = "$wassergehalt" . $paramLimit['wasserParamLimit'];
    $ascheIf = "$aschegehalt" . $paramLimit['ascheParamLimit'];
    $chlorIf = "$chlor" . $paramLimit['chlorParamLimit'];
    $schwefelIf = "$schwefel" . $paramLimit['schwefelParamLimit'];
    //String to Condition (Code)
    $conditQueck = eval("return $queckIf;");
    $conditWasser = eval("return $wasserIf;");
    $conditAsche = eval("return $ascheIf;");
    $conditChlor = eval("return $chlorIf;");
    $conditSchwefel = eval("return $schwefelIf;");

    if ($conditQueck) {
        //"Quecksilber im guten Bereich<br>";
        $checkQueck = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        $checkQueckHtml = "Wert ist unter ".str_replace("<=","",$paramLimit['quecksilberParamLimit']);
    } else {
        //"<br>Queckilber zu hoch<br>";
        $checkQueck = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        $checkQueckHtml = "Wert ist über ".str_replace("<=","",$paramLimit['quecksilberParamLimit']);
    }
    $htmlQueck = "<div class='row'><div class='col-3'>Quecksilber:</div> <div class='col-3'>$quecksilber mg/kg</div><div class='col-1'>$checkQueck</div><div class='col-4'>$checkQueckHtml mg/kg</div></div>";

    if ($conditWasser) {
        //"Wassergehalt im guten Bereich<br>";
        $checkWasser = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        $checkWasserHtml = "Wert ist unter ".str_replace("<=","",$paramLimit['wasserParamLimit']);
    } else {
        //"Wassergehalt zu hoch<br>";
        $checkWasser = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        $checkWasserHtml = "Wert ist über ".str_replace("<=","",$paramLimit['wasserParamLimit']);
    }
    $htmlWasser = "<div class='row'><div class='col-3'>Wassergehalt:</div><div class='col-3'> $wassergehalt %</div><div class='col-1'>$checkWasser</div><div class='col-4'>$checkWasserHtml %</div></div>";

    if ($conditAsche) {
        //"Aschegehalt im guten Bereich<br>";
        $checkAsche = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        $checkAscheHtml = "Wert ist unter ".str_replace("<=","",$paramLimit['ascheParamLimit']);
    } else {
        //"Aschegehalt zu hoch<br>";
        $checkAsche = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        $checkAscheHtml = "Wert ist über ".str_replace("<=","",$paramLimit['ascheParamLimit']);
    }
    $htmlAsche = "<div class='row'><div class='col-3'>Aschegehalt: </div> <div class='col-3'>$aschegehalt %</div><div class='col-1'>$checkAsche</div><div class='col-4'>$checkAscheHtml %</div></div>";

    if ($conditChlor) {
        //"Chlorgehalt im guten Bereich<br>";
        $checkChlor = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        $checkChlorHtml = "Wert ist unter ".str_replace("<","",$paramLimit['chlorParamLimit']);
    } else {
        //"Chlorgehalt zu hoch<br>";
        $checkChlor = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        $checkChlorHtml = "Wert ist über ".str_replace("<","",$paramLimit['chlorParamLimit']);
    }
    $htmlChlor = "<div class='row'><div class='col-3'>Chlorgehalt:</div><div class='col-3'> $chlor %</div><div class='col-1'>$checkChlor</div><div class='col-4'>$checkChlorHtml %</div></div>";

    if ($conditSchwefel) {
        //"Schwefel im guten Bereich<br>";
        $checkSchwefel = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        $checkSchwefelHtml = "Wert ist unter ".str_replace("<","",$paramLimit['schwefelParamLimit']);

    } else {
        //"Schwefel zu hoch<br>";
        $checkSchwefel = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        $checkSchwefelHtml = "Wert ist über ".str_replace("<","",$paramLimit['schwefelParamLimit']);
    }
    $htmlSchwefel = "<div class='row'><div class='col-3'>Schwefelgehalt: </div><div class='col-3'>$schwefel %</div><div class='col-1'>$checkSchwefel</div><div class='col-4'>$checkSchwefelHtml %</div></div>";

    $htmlAllBurn = $htmlQueck.$htmlWasser.$htmlAsche.$htmlChlor.$htmlSchwefel;
    return $htmlAllBurn;
}

?>

