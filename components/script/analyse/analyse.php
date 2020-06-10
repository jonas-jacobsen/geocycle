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
        $paramEval = checkParamMaterial($paramJson);
        buildHTMLForMaterial($offeredPrice, $paramEval, $paramJson, $amount);
    } else {
        $paramEval = checkParamBurn($paramJson);
        buildHTMLForBurn($paramEval, $amount, $offeredPrice, $untererHeizwert);
    }
}

function buildHTMLForBurn($paramEval, $amount, $offeredPrice, $untererHeizwert)
{
    $table = "";
    $fazit = "";

    //Heizwert überprüfen Oveneinlauf oder Hauptbrenner
    if ($untererHeizwert > 20) {
        $burnForWhat = "Brennstoff für Hauptbrenner";
    } else {
        $burnForWhat = "Brennstoff für Ofeneinlauf";
    }

    //Menge Überprüfen
    $checkIsAmountGood = checkAmount($amount);
    if ($checkIsAmountGood == 1) {
        $htmlAmount = "Die Menge ist in Ordnung";
        $iconCheckAmount = iconChecker(1);
    } else {
        $htmlAmount = "Die Menge ist zu gering";
        $iconCheckAmount = iconChecker(0);
    }

    //Json  Liste Objekte zählen
    $countJson = count($paramEval);
    $paramEvalAll = $paramEval[$countJson - 1]["oneValueTooHigh"];

    if ($paramEvalAll == 1) {
        $oneParamTooHight = "Mindestens ein Parameter zu hoch";
    } else {
        $oneParamTooHight = "Alle Parameter im guten Bereich";
    }

    //Prüfen pb wirtschaftlich gut
    $isEconomic = checkEconomicConditionBurn($offeredPrice);
    if ($isEconomic == 1) {
        $economicHtml = "Wirtschaftlich interessant, da der Brennstoff günstiger als Kohle ist";
    } else {
        $economicHtml = "Wirtschaftlich nicht interessant, da der Brennstoff teurer als Kohle ist";
    }
    echo "<h4>Brennstoff</h4>";
    echo "<div class='row'><div class='col-3'>Heizwert</div><div class='col-3'>$untererHeizwert mj</div><div class='col-1'></div><div class='col-4'>$burnForWhat</div></div><br>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div>";
    for ($i = 0; $i < $countJson - 1; $i++) {
        $name = $paramEval[$i]["name"];
        $value = $paramEval[$i]["value"];
        $check = $paramEval[$i]["check"];
        $unit = $paramEval[$i]["unit"];
        $comment = $paramEval[$i]["comment"];

        $iconCheck = "";
        if ($check == "0") {
            $iconCheck = iconChecker(0);
        } else {
            $iconCheck = iconChecker(1);
        }
        //$test = $paramEvalName[$i];
        echo "<div class='row'><div class='col-3'>$name</div><div class='col-3'>$value $unit</div><div class='col-1'>$iconCheck</div><div class='col-4'>$comment</div></div>";
    }
    echo "<br><h4>Empfehlung:</h4>";
    echo "<span>Annehmen / Ablehen</span><br>";
    echo "<span>Begründung:</span>";
    echo "<ul>";
    echo "<li>" . $oneParamTooHight . "</li>";
    echo "<li>" . $economicHtml . "</li>";
    echo "</ul>";
    echo "<hr>";
}

function buildHTMLForMaterial($offeredPrice, $paramEval, $paramJson, $amount)
{
    $isEconomic = checkEconomicConditionMaterial($offeredPrice, $paramEval[0], $paramEval[1]);
    $preisProTonne = $isEconomic[1];
    $vergleichsPreis = $isEconomic[2];
    $isEconomic = $isEconomic[0];

    switch ($isEconomic) {
        //0 = dann größter Stoff hauptbestandteil aber Wirtschaftlich nicht interessant
        //1 = dann größter Stoff hauptbestandteil und wirtschaftlich interessant
        //2 = kein Hauptbestandteil aber wirtschaftlich interessant
        //3 = kein Hauptbestandteil und wirtschaftlich nicht interessant
        case 0:
            $isEconomicHtml = "Stoff ist Hauptbestandteil aber wirtschaftlich nicht interessant, da der $paramEval[0]preis mit " . abs($preisProTonne) . "€/Tonne teurer ist als der Vergleichspreis von $vergleichsPreis €/Tonne";
            break;
        case 1:
            $isEconomicHtml = "Stoff ist Hauptbestandteil und wirtschaftlich interessant, da der $paramEval[0]preis mit ".abs($preisProTonne)."€/Tonne günstiger ist als der Vergleichspreis von $vergleichsPreis €/Tonne";
            break;
        case 2:
            $isEconomicHtml = "Stoff ist kein Hauptbestandteil aber wirtschaftlich interessant, da die Zuzahlung an Geocycle mit ".abs($offeredPrice)."€/Tonne über 10€ liegt";
            break;
        case 3:
            if($offeredPrice > 0){
                $isEconomicHtml = "Stoff ist kein Hauptbestandteil und wirtschaftlich nicht interessant, da die Zuzahlung an Geocycle mit ".abs($offeredPrice)."€/Tonne zu gering ist";
            }else{
                $isEconomicHtml = "Stoff ist kein Hauptbestandteil und wirtschaftlich nicht interessant, da keine Zuzahlung an Geocycle geboten wird";
            }
            break;
        default:
    };

    $countJson = count($paramJson);

    //Menge Überprüfen
    $checkIsAmountGood = checkAmount($amount);
    if ($checkIsAmountGood == 1) {
        $htmlAmount = "Die Menge ist in Ordnung";
        $iconCheckAmount = iconChecker(1);
    } else {
        $htmlAmount = "Die Menge ist zu gering";
        $iconCheckAmount = iconChecker(0);
    }

    echo "<h4>Rohstoff</h4>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div><br>";
    for ($i = 0; $i < $countJson; $i++) {
        $name = $paramJson[$i]->param;
        $value = $paramJson[$i]->value;
        $unit = $paramJson[$i]->units;
        $comment = "";
        if($value == 0){
        }else{
            echo "<div class='row'><div class='col-3'>$name</div><div class='col-3'>$value $unit</div><div class='col-1'></div><div class='col-4'>$comment</div></div>";
        }
    }
    echo "<br><h4>Empfehlung:</h4>";
    echo "<span>Annehmen / Ablehen</span><br>";
    echo "<span>Begründung:</span>";
    echo "<ul>";
    echo "<li>" . $isEconomicHtml . "</li>";
    echo "</ul>";
    echo "<hr>";
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
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "1", "unit" => "mg / kg", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "0", "unit" => "mg / kg", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($wassergehalt <= $paramLimit["wasser"]) {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($aschegehalt <= $paramLimit["asche"]) {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($chlorgehalt <= $paramLimit["chlor"]) {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh = 1;
    }
    if ($schwefelgehalt <= $paramLimit["schwefel"]) {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "0", "unit" => " % ", "comment" => "");
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
    $vergleichsPreis = 0;
    $preisProTonne = $offeredPrice / ($highestValue / 100);

    $isEconomic = 0;
    switch ($highestParam) {
        case "Eisen":
            $vergleichsPreis = $verglPreisEisen;
            if ($preisProTonne > $verglPreisEisen) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Silizium":
            $vergleichsPreis = $verglPreisSilizium;
            if ($preisProTonne > $verglPreisSilizium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Aluminium":
            $vergleichsPreis = $verglPreisAluminium;
            if ($preisProTonne > $verglPreisAluminium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
            break;
        case "Calcium":
            $vergleichsPreis = $verglPreisCalcium;
            if ($preisProTonne > $verglPreisCalcium) {
                $isEconomic = 1;
            } else {
                $isEconomic = 0;
            };
        default:
            //Preis muss 2-Stellig sein weil kein Hauptbestandteil
            if ($offeredPrice >= 10) {
                $isEconomic = 2;
            } else {
                $isEconomic = 3;
            };

    }
    //0 = dann größter Stoff hauptbestandteil aber Wirtschaftlich nicht interessant
    //1 = dann größter Stoff hauptbestandteil und wirtschaftlich interessant
    //2 = kein Hauptbestandteil aber wirtschaftlich interessant
    //3 = kein Hauptbestandteil und wirtschaftlich nicht interessant
    return [$isEconomic, $preisProTonne, $vergleichsPreis];
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

function iconChecker($result){
    if($result == 1){
        return "<i style='color: green' class=\"far fa-check-circle\"></i>";
    }else {
        return "<i style='color: red' class=\"far fa-times-circle\"></i>";
    }
}

?>