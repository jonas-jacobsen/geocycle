<?php

function analyse($requestId, $conn)
{
    //Paramterliste aus der Db laden
    $sql = "SELECT * FROM userdata WHERE id = $requestId";
    $stmt = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($stmt);

    $offeredPrice = $row['OfferedPrice'];
    $amount = $row['JaTo'];
    $paramJson = $row['ParameterList'];
    //Parameterliste in Json objekt abspeichern um damit arbeiten zu können;
    $paramJson = json_decode($paramJson);

    //heizwert aus Parameterliste holen und in Variable speichern
    $untererHeizwert = str_replace(',', '.', $paramJson[0]->value);
    //Prüfen ob es sich um einen Brenn oder Rohstoff handelt
    $materialOrBurn = checkBurnOrMaterial($untererHeizwert);


    /*Testing*/
    if ($materialOrBurn == "Rohstoff") {

    } else {
        $paramEval = checkParamBurn($paramJson);
        buildHTMLForBurn($paramEval, $amount, $offeredPrice);
    }
}

function buildHTMLForBurn($paramEval, $amount, $offeredPrice)
{
    $table = "";
    $fazit = "";

    //Menge Überprüfen
    $checkIsAmountGood = checkAmount($amount);
    if ($checkIsAmountGood == 1) {
        $htmlAmount = "Die Menge ist in Ordnung";
        $iconCheckAmount = "<i style='color: green' class=\"far fa-check-circle\"></i>";
    } else {
        $htmlAmount = "Die Menge ist zu gering";
        $iconCheckAmount = "<i style='color: red' class=\"far fa-times-circle\"></i>";
    }

    //Json  Liste Objekte zählen
    $countJson = count($paramEval);
    $paramEvalAll = $paramEval[$countJson - 1]["oneValueTooHigh"];

    if ($paramEvalAll == 1) {
        $oneParamTooHight = "<li>Mindestens ein Parameter zu hoch</li>";
    } else {
        $oneParamTooHight = "<li>Alle Parameter im guten Bereich</li>";
    }

    //Prüfen pb wirtschaftlich gut
    $isEconomic = checkEconomicConditionBurn($offeredPrice);
    if ($isEconomic == 1) {
        $economicHtml = "<li>Wirtschaftlich interessant, da der Brennstoff günstiger als Kohle ist</li>";
    } else {
        $economicHtml = "<li>Wirtschaftlich nicht interessant, da der Brennstoff teurer als Kohle ist</li>";
    }
    echo "<h4>Brennstoff</h4>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div>";
    for ($i = 0; $i < $countJson - 1; $i++) {
        $name = $paramEval[$i]["name"];
        $value = $paramEval[$i]["value"];
        $check = $paramEval[$i]["check"];
        $unit = $paramEval[$i]["unit"];
        $comment = $paramEval[$i]["comment"];

        $iconCheck = "";
        if ($check == "0") {
            $iconCheck = "<i style='color: red' class=\"far fa-times-circle\"></i>";
        } else {
            $iconCheck = "<i style='color: green' class=\"far fa-check-circle\"></i>";
        }
        //$test = $paramEvalName[$i];
        echo "<div class='row'><div class='col-3'>$name</div><div class='col-3'>$value $unit</div><div class='col-1'>$iconCheck</div><div class='col-4'>$comment</div></div>";
    }
    echo "<br><h4>Empfehlung:</h4>";
    echo "<span>Annehmen / Ablehen</span><br>";
    echo "<span>Begründung:</span>";
    echo "<ul>";
    echo $oneParamTooHight;
    echo $economicHtml;
    echo "</ul>";
    echo "<hr>";
}

function buildHTMLForMaterial()
{

}

function evalComment()
{

}

function checkParamBurn($paramJson)
{

    //Variablen für Paramterliste Initialisieren
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
    //ToDo: Werte überprüfen und michis Json Liste optmieren
    $untererHeizwert = str_replace(',', '.', $paramJson[0]->value);
    $wassergehalt = str_replace(',', '.', $paramJson[1]->value);
    $aschegehalt = str_replace(',', '.', $paramJson[2]->value);
    $chlorgehalt = str_replace(',', '.', $paramJson[3]->value);
    $schwefelgehalt = str_replace(',', '.', $paramJson[4]->value);
    $quecksilbergehalt = str_replace(',', '.', $paramJson[5]->value);
    $calcium = str_replace(',', '.', $paramJson[6]->value);
    $silicium = str_replace(',', '.', $paramJson[7]->value);
    $eisen = str_replace(',', '.', $paramJson[8]->value);
    $magnesium = str_replace(',', '.', $paramJson[9]->value);
    $kalium = str_replace(',', '.', $paramJson[10]->value);
    $natrium = str_replace(',', '.', $paramJson[11]->value);
    $aluminium = str_replace(',', '.', $paramJson[12]->value);

    if ($untererHeizwert > 20) {
        //Hauptbrenner
        //ParameterLimits für die Vergleiche
        $paramLimit = array(
            "wasser" => "15",
            "asche" => "15",
            "chlor" => "1",
            "schwefel" => "1",
            "quecksilber" => "0.5",
        );

    } else {
        //Ofeneinlauf
        //ParameterLimits für die Vergleiche
        $paramLimit = array(
            "wasser" => "25",
            "asche" => "30",
            "chlor" => "1",
            "schwefel" => "1",
            "quecksilber" => "0.5",
        );
    }

    $paramEval = array();
    $checkIfOneTooHigh = 0;
    if ($quecksilbergehalt <= $paramLimit["quecksilber"]) {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "1", "unit" => "mg/kg", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "0", "unit" => "mg/kg", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($wassergehalt <= $paramLimit["wasser"]) {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "1", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "0", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($aschegehalt <= $paramLimit["asche"]) {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "1", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "0", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($chlorgehalt <= $paramLimit["chlor"]) {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "1", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "0", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($schwefelgehalt <= $paramLimit["schwefel"]) {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "1", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "0", "unit" => "%", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }

    //Wenn ein Wert zu hoch dann varibale ins Array pushen
    $paramEvalAll = array("oneValueTooHigh" => $checkIfOneTooHigh);
    array_push($paramEval, $paramEvalAll);


    //0 = wert zu hoch
    //1 = wert in Ordnung
    //evalValues =  1 -> alle Werte in Ordnung
    //evalValues =  0 -> min 1 Wert zu hoch
    return $paramEval;
}

function checkParamMaterial($paramJson)
{
    $countJsonParam = count($paramJson);
    $highestValue = 0;
    $highestParam = "";

    for ($i = 0; $i <= $countJsonParam; $i++) {
        if ($paramJson[$i]->value > $highestValue) {
            $highestValue = $paramJson[$i]->value;
            $highestParam = $paramJson[$i]->param;
        } else {
        }
    }
    $highestParamArray = [$highestParam, $highestValue];
    return $highestParamArray;
}

function checkPriceCondition($offeredPrice)
{
    //Überprüfen ob zuzahlung oder Kosten für Geoocycle
    $priceCondition = 0;

    if ($offeredPrice < 0) {
        //O = Kosten für Geocycle
    } else {
        //1 = Zuzahlung an Geocycle
        $priceCondition = 1;
    }
    return $priceCondition;
}

function checkEconomicConditionMaterial($offeredPrice, $highestParam, $highestValue)
{
    $verglPreisEisen = -20;
    $verglPreisSilizium = -7;
    $verglPreisAluminium = -26;
    $verglPreisCalcium = 25;

    $preisProTonne = $offeredPrice / ($highestValue / 100);

    $isEconomic = 0;
    switch ($highestParam) {
        case "Eisen":
            if ($preisProTonne > $verglPreisEisen) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Silizium":
            if ($preisProTonne > $verglPreisSilizium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Aluminium":
            if ($preisProTonne > $verglPreisAluminium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Calcium":
            if ($preisProTonne > $verglPreisCalcium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
        default:
            //Preis muss 2-Stellig sein weil kein Hauptbestandteil
            if ($preisProTonne >= 10) {
                $isEconomic = 2;
            } else {
                $isEconomic = 3;
            };

    }
    //0 = dann größter Stoff hauptbestandteil aber Wirtschaftlich nicht interessant
    //1 = dann größter Stoff hauptbestandteil und wirtschaftlich interessant
    //2 = kein Hauptbestandteil aber wirtschaftlich interessant
    //3 = kein Hauptbestandteil und wirtschaftlich nicht interessant
    return $isEconomic;
}

function checkEconomicConditionBurn($offeredPrice)
{
    $kohleVergPreis = 4;
    $isEconomic = 0;
    //ToDo: Brennwert vergleichen mit Kohlepreis;
    return $isEconomic;
}

function checkAmount($amount)
{
    $amountEval = 0;
    // 1 = Menge in Ordnung (Über 5000)
    if ($amount >= 5000) {
        $amountEval = 1;
    } else {
        // 0 = Menge zu gering
    }
    return $amountEval;
}

//Überprüfung ob Brennstoff oder Rohstoff
function checkBurnOrMaterial($untererHeizwert)
{
    $materialOrBurn = "";
    if ($untererHeizwert < 10) {
        $materialOrBurn = "Rohstoff";
    } else {
        $materialOrBurn = "Brennstoff";
    }
    return $materialOrBurn;
}


?>