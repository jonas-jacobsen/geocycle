<?php
/* Start to develop here. Best regards https://php-download.com/ */

use Phpml\Classification\DecisionTree;
use Phpml\Dataset\CsvDataset;

require_once("vendor/autoload.php");


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

    //Werk und AVV aus db laden
    //Avv überprüfen
    $avv = $row["Avv"];
    $closestFactory = json_decode($row["ClosestFactory"]);
    $closestFactory = $closestFactory[0]->name;

    //Produktions oder Abfallstatus aus db laden
    $prodAbf = $row["ProdAbf"];

    //heizwert aus Parameterliste holen und in Variable speichern
    $untererHeizwert = str_replace(',', '.', $paramJson[0]->value);
    //Prüfen ob es sich um einen Brenn oder Rohstoff handelt
    $materialOrBurn = checkBurnOrMaterial($untererHeizwert);

    $dataforCSV = NULL;
    /*Testing*/
    if ($materialOrBurn == "Rohstoff") {
        $paramEvalMain = checkParamMaterialMain($paramJson);
        $paramEval = checkParamMaterial($paramJson);
        buildHTMLForMaterial($offeredPrice, $paramEvalMain, $paramEval, $paramJson, $amount, $closestFactory, $avv, $prodAbf);
    } else {
        $paramEval = checkParamBurn($paramJson);
        buildHTMLForBurn($paramEval, $amount, $offeredPrice, $untererHeizwert, $closestFactory, $avv, $prodAbf);
    }
}

//Funktion zum HTML-Aufbau der der Analyse für Brennstoff
function buildHTMLForBurn($paramEval, $amount, $offeredPrice, $untererHeizwert, $closestFactory, $avv, $prodAbf)
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

    if ($paramEvalAll >= 1) {
        $oneParamTooHight = $paramEvalAll." Parameter zu hoch";
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

    //Werk auf AVV nummer überprüfen
    $avvComment = "";
    $isAVVForFactory = avvChecker($closestFactory, $avv);
    if ($isAVVForFactory[0] == 1) {
        $avvIcon = iconChecker(1);
        $avvComment = "Avv ist im nächstgelegenen Werk Zertifiziert";
    } else {
        if ($isAVVForFactory[1] == 1) {
            $avvIcon = iconChecker(1);
            $avvComment = "Avv ist nicht in nächstgelegenem Werk aber in einem anderen Werk Zertifiziert";
        } else {
            $avvIcon = iconChecker(0);
            $avvComment = "Avv ist in keinem Werk Zertifiziert";
        }
    }


    //überprüfen ob Produktions oder Abfallstatusw
    if ($prodAbf == "Abfall") {
        $isAbfOrProd = 1; //Für ML-Analyse abfall
        $htmlProdAbf = "<div class='row'><div class='col-3'>Nächstgelegenes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'>$avvIcon</div><div class='col-4'>$avvComment</div></div>";
    } else {
        $isAbfOrProd = 0; //Für ML-Analyse Rohstoff
        $htmlProdAbf = "<div class='row'><div class='col-3'>Nächstgelegenes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'></div><div class='col-4'></div></div>";
    }
    //Ml analyse $rohBrenn, $wasser, $asche, $chlor, $schwefel, $queck, $calcium, $silizium, $natrium, $aluminium $kalium, $magnesium, $natrium, $rohMainParam, $menge, $preis, $abfPro,
    $recomendation = getRecomendation(0, $paramEval[1]["check"], $paramEval[2]["check"], $paramEval[3]["check"], $paramEval[4]["check"], $paramEval[0]["check"], 0, 0, 0, 0, 0, 0, 0, 0, $checkIsAmountGood, $isEconomic, $isAbfOrProd, $isAVVForFactory[0]);

    //Ab hier HTML
    echo "<h4>Brennstoff</h4>";
    echo "$htmlProdAbf";
    echo "<div class='row'><div class='col-3'>Heizwert</div><div class='col-3'>$untererHeizwert mj</div><div class='col-1'></div><div class='col-4'>$burnForWhat</div></div>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div>";
    echo "<h5>Beurteilte Parameter</h5>";
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
    echo "<p>" . $recomendation . "</p>";
    echo "<span>Begründung:</span>";
    echo "<ul>";
    echo "<li>" . $oneParamTooHight . "</li>";
    echo "<li>" . $economicHtml . "</li>";
    echo "</ul>";
    echo "<hr>";
}

//Funktion zum HTML-Aufbau der der Analyse für Rohstoff
function buildHTMLForMaterial($offeredPrice, $paramEvalMain, $paramEval, $paramJson, $amount, $closestFactory, $avv, $prodAbf)
{
    $isEconomic = checkEconomicConditionMaterial($offeredPrice, $paramEvalMain[0], $paramEvalMain[1]);
    $preisProTonne = $isEconomic[1];
    $vergleichsPreis = $isEconomic[2];
    $isEconomic = $isEconomic[0];

    switch ($isEconomic) {
        //0 = dann größter Stoff hauptbestandteil aber Wirtschaftlich nicht interessant
        //1 = dann größter Stoff hauptbestandteil und wirtschaftlich interessant
        //2 = kein Hauptbestandteil aber wirtschaftlich interessant
        //3 = kein Hauptbestandteil und wirtschaftlich nicht interessant
        case 0:
            $isHighestParam = 1; //Für ML-Analyse
            $isEconomicHtml = "Stoff ist Hauptbestandteil aber wirtschaftlich nicht interessant, da der $paramEvalMain[0]preis mit " . round(abs($preisProTonne), 2) . "€/Tonne teurer ist als der Vergleichspreis von " . abs($vergleichsPreis) . "€/Tonne";
            $iconCheckParamHigh = iconChecker(1);
            $highestParamComment = "Stoff ist Hauptbestandteil des Klinkers";
            break;
        case 1:
            $isHighestParam = 1; //Für ML-Analyse
            $isEconomicHtml = "Stoff ist Hauptbestandteil und wirtschaftlich interessant, da der $paramEvalMain[0]preis mit " . round(abs($preisProTonne), 2) . "€/Tonne günstiger ist als der Vergleichspreis von " . abs($vergleichsPreis) . "€/Tonne";
            $iconCheckParamHigh = iconChecker(1);
            $highestParamComment = "Stoff ist Hauptbestandteil des Klinkers";
            break;
        case 2:
            $isHighestParam = 0; //Für ML-Analyse
            $isEconomicHtml = "Stoff ist kein Hauptbestandteil aber wirtschaftlich interessant, da die Zuzahlung an Geocycle mit " . abs($offeredPrice) . "€/Tonne über dem Grenzwert von 10€ liegt";
            $iconCheckParamHigh = iconChecker(0);
            $highestParamComment = "Stoff ist kein Hauptbestandteil des Klinkers";
            break;
        case 3:
            $isHighestParam = 0; //Für ML-Analyse
            $iconCheckParamHigh = iconChecker(1);
            $highestParamComment = "Stoff ist kein Hauptbestandteil des Klinkers";
            if ($offeredPrice > 0) {
                $isEconomicHtml = "Stoff ist kein Hauptbestandteil und wirtschaftlich nicht interessant, da die Zuzahlung an Geocycle mit " . abs($offeredPrice) . "€/Tonne zu gering ist";
            } else {
                $isEconomicHtml = "Stoff ist kein Hauptbestandteil und wirtschaftlich nicht interessant, da keine Zuzahlung an Geocycle geboten wird";
            }
            break;
        default:
    };


    //Menge Überprüfen
    $checkIsAmountGood = checkAmount($amount);
    if ($checkIsAmountGood == 1) {
        $htmlAmount = "Die Menge ist in Ordnung";
        $iconCheckAmount = iconChecker(1);
    } else {
        $htmlAmount = "Die Menge ist zu gering";
        $iconCheckAmount = iconChecker(0);
    }

    //paramEval Liste aufrufen und für HTML verarbeiten
    $countParamEval = count($paramEval);
    $paramEvalAll = $paramEval[$countParamEval - 1]["oneValueTooHigh"];

    if ($paramEvalAll >= 1) {
        $oneParamTooHight = $paramEvalAll." Parameter zu hoch";
    } else {
        $oneParamTooHight = "Alle Parameter im guten Bereich";
    }

    //Werk auf AVV nummer überprüfen
    $avvComment = "";
    $isAVVForFactory = avvChecker($closestFactory, $avv);
    if ($isAVVForFactory[0] == 1) {
        $avvIcon = iconChecker(1);
        $avvComment = "Avv ist im nächstgelegenen Werk " . $closestFactory . " Zertifiziert";
    } else {
        if ($isAVVForFactory[1] == 1) {
            $avvIcon = iconChecker(1);
            $avvComment = "Avv ist nicht in nächstgelegenen Werk aber in einem anderen Werk Zertifiziert";
        } else {
            $avvIcon = iconChecker(0);
            $avvComment = "Avv ist in keinem Werk Zertifiziert";
        }
    }

    //überprüfen ob Produktions oder Abfallstatus
    if ($prodAbf == "Abfall") {
        $isAbfOrProd = 1; //Für ML-Analyse
        $htmlProdAbf = "<div class='row'><div class='col-3'>Nächstgelegenes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'></div><div class='col-4'></div></div><div class='row'><div class='col-3'>AVV-Nummer</div><div class='col-3'>$avv</div><div class='col-1'>$avvIcon</div><div class='col-4'>$avvComment</div></div>";
    } else {
        $isAbfOrProd = 0; //Für ML-Analyse
        $htmlProdAbf = "<div class='row'><div class='col-3'>Nächstgelegenes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'></div><div class='col-4'></div></div>";
    }

    $wassergehalt = $paramEval[1]['check'];
    $chlorgehalt = $paramEval[2]['check'];
    $schwefelgehalt = $paramEval[3]['check'];
    $calciumgehalt = $paramEval[4]['check'];
    $siliziumgehalt = $paramEval[5]['check'];
    $eisengehalt = $paramEval[6]['check'];
    $aluminiumgehalt = $paramEval[7]['check'];
    $magnesiumgehalt = $paramEval[8]['check'];
    $kaliumgehalt = $paramEval[9]['check'];
    $natriumgehalt = $paramEval[10]['check'];



    //Ml analyse $rohBrenn, $wasser, $asche, $chlor, $schwefel, $queck, $kalium, $magnesium, $natrium, $rohMainParam, $menge, $preis, $abfPro, $avvZert
    $recomendation = getRecomendation(1, $wassergehalt, 0, $chlorgehalt, $schwefelgehalt, 0, $calciumgehalt, $siliziumgehalt, $eisengehalt, $aluminiumgehalt, $kaliumgehalt,$magnesiumgehalt,$natriumgehalt, $isHighestParam, $checkIsAmountGood, $isEconomic, $isAbfOrProd, $isAVVForFactory[0]);
    echo "<h4>Rohstoff</h4>";
    echo "$htmlProdAbf";
    echo "<div class='row'><div class='col-3'>Hauptbestandteil</div><div class='col-3'>$paramEvalMain[0]</div><div class='col-1'>$iconCheckParamHigh</div><div class='col-4'>$highestParamComment</div></div>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div><br>";
    echo "<h5>Beurteilte Parameter</h5>";
    for ($i = 0; $i < count($paramEval)-1; $i++) {
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
    echo "<p>" . $recomendation . "</p>";
    echo "<span>Begründung:</span>";
    echo "<ul>";
    echo "<li>" . $oneParamTooHight . "</li>";
    echo "<li>" . $isEconomicHtml . "</li>";
    echo "</ul>";
    echo "<hr>";
}

//Funktion zur Evaluierung der Kommentare der Beurteilung sein
function evalComment()
{

}

//Funktion zur Aufbereitung der Parameterlist für Brennstoff
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
        $tmp = array(
            "name" => "Quecksilber",
            "value" => "$quecksilbergehalt",
            "check" => "1",
            "unit" => "mg / kg",
            "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "0", "unit" => "mg / kg", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($wassergehalt <= $paramLimit["wasser"]) {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($aschegehalt <= $paramLimit["asche"]) {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Aschegehalt", "value" => "$aschegehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($chlorgehalt <= $paramLimit["chlor"]) {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($schwefelgehalt <= $paramLimit["schwefel"]) {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "0", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
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

//Funktion zur Aufbereitung der Parameterlist für Brennstoff
function checkParamMaterial($paramJson)
{

    //Variablen für Paramterliste Initialisieren
    $unterHo = "";
    $wassergehalt = "";
    $aschegehalt = "";
    $chlorgehalt = "";
    $schwefelgehalt = "";
    $quecksilbergehalt = "";
    $calciumgehalt = "";
    $siliciumgehalt = "";
    $eisengehalt = "";
    $magnesiumgehalt = "";
    $kaliumgehalt = "";
    $natriumgehalt = "";
    $aluminiumgehalt = "";

    //Paramter aus Json extrahieren und in varibalen speichern
    //ToDo: Werte überprüfen und michis Json Liste optmieren
    $untererHeizwert = str_replace(',', '.', $paramJson[0]->value);
    $wassergehalt = str_replace(',', '.', $paramJson[1]->value);
    $aschegehalt = str_replace(',', '.', $paramJson[2]->value); //Wird für Material nicht beachtet
    $chlorgehalt = str_replace(',', '.', $paramJson[3]->value);
    $schwefelgehalt = str_replace(',', '.', $paramJson[4]->value);
    $quecksilbergehalt = str_replace(',', '.', $paramJson[5]->value);
    $calciumgehalt = str_replace(',', '.', $paramJson[6]->value);
    $siliciumgehalt = str_replace(',', '.', $paramJson[7]->value);
    $eisengehalt = str_replace(',', '.', $paramJson[8]->value);
    $magnesiumgehalt = str_replace(',', '.', $paramJson[9]->value);
    $kaliumgehalt = str_replace(',', '.', $paramJson[10]->value);
    $natriumgehalt = str_replace(',', '.', $paramJson[11]->value);
    $aluminiumgehalt = str_replace(',', '.', $paramJson[12]->value);

    $paramLimit = array(
        "wasser" => "25",
        "chlor" => "1",
        "schwefel" => "1",
        "quecksilber" => "0.5",
        "eisen" => "100",
        "aluminium" => "100",
        "silicium" => "100",
        "calcium" => "100",
        "magnesium" => "100",
        "kalium" => "100",
        "natrium" => "100",
    );

    $paramEval = array();
    $checkIfOneTooHigh = 0;
    if ($quecksilbergehalt <= $paramLimit["quecksilber"]) {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "1", "unit" => "mg / kg", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Quecksilber", "value" => "$quecksilbergehalt", "check" => "0", "unit" => "mg / kg", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($wassergehalt <= $paramLimit["wasser"]) {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Wassergehalt", "value" => "$wassergehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($chlorgehalt <= $paramLimit["chlor"]) {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Chlorgehalt", "value" => "$chlorgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($schwefelgehalt <= $paramLimit["schwefel"]) {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Schwefelgehalt", "value" => "$schwefelgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($calciumgehalt <= $paramLimit["calcium"]) {
        $tmp = array("name" => "Calciumgehalt", "value" => "$calciumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Calciumgehalt", "value" => "$calciumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($siliciumgehalt <= $paramLimit["silicium"]) {
        $tmp = array("name" => "Silliciumgehalt", "value" => "$siliciumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Silliciumgehalt", "value" => "$siliciumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($eisengehalt <= $paramLimit["eisen"]) {
        $tmp = array("name" => "Eisengehalt", "value" => "$eisengehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Eisengehalt", "value" => "$eisengehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($aluminiumgehalt <= $paramLimit["aluminium"]) {
        $tmp = array("name" => "Aluminiumgehalt", "value" => "$aluminiumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Aluminiumgehalt", "value" => "$aluminiumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($magnesiumgehalt <= $paramLimit["magnesium"]) {
        $tmp = array("name" => "Magnesiumgehalt", "value" => "$magnesiumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Magnesiumgehalt", "value" => "$magnesiumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($kaliumgehalt <= $paramLimit["kalium"]) {
        $tmp = array("name" => "Kaliumgehalt", "value" => "$kaliumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Kaliumgehalt", "value" => "$kaliumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
    }
    if ($natriumgehalt <= $paramLimit["natrium"]) {
        $tmp = array("name" => "Natriumgehalt", "value" => "$natriumgehalt", "check" => "1", "unit" => " % ", "comment" => "");
        array_push($paramEval, $tmp);
    } else {
        $tmp = array("name" => "Natriumgehalt", "value" => "$natriumgehalt", "check" => "0", "unit" => " % ", "comment" => "Der Wert überschreitet den Grenzwert");
        array_push($paramEval, $tmp);
        $checkIfOneTooHigh += 1;
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

//Funktion zur Aufbereitung der Parameterlist für Rohstoff
function checkParamMaterialMain($paramJson)
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

//Funktion zur Überprüfung ob Zuzahlung für Geocycle oder Kunden
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

//funktion zur Überprüfung ob sich der Rohstoff wirtschaftlich lohnt
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

//funktion zur Überprüfung ob sich der Brennstoff wirtschaftlich lohnt
function checkEconomicConditionBurn($offeredPrice)
{
    $kohleVergPreis = 4;

    if ($offeredPrice > $kohleVergPreis) {
        $isEconomic = 0;
    } else {
        $isEconomic = 1;
    }
    return $isEconomic;
}

//Funktion zur Überprüfung ob die Menge im gewünschten Bereich
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

//funktion zur überprüfung des Icons (green or Red)
function iconChecker($result)
{
    if ($result == 1) {
        return "<i style='color: green' class=\"far fa-check-circle\"></i>";
    } else {
        return "<i style='color: red' class=\"far fa-times-circle\"></i>";
    }
}

function avvChecker($factoryName, $avv)
{
    //avv Checker für Factory;

    $factCert = 0; // return value 1
    $allCert = 0; // return value 2
    $certAvvs = array(
        "Beckum" => ["020103", "020104", "020107", "020203", "020304", "030101", "030105", "030301", "030302", "030307", "030308", "040209", "040221", "040222", "070213", "070299", "080112", "080114", "080201", "090107", "090108", "120105", "150101", "150102", "150103", "150105", "150106", "150203", "160103", "170201", "170203", "170302", "190501", "191201", "191204", "191207", "191208", "191210", "191212"],
        "Lägerdorf" => ["020202", "020203", "020304", "030101", "030104", "030105", "030301", "030305", "030307", "030308", "030309", "030310", "030311", "040210", "040216", "040221", "050103", "050106", "050113", "050115", "060203", "060502", "061303", "061305", "070104", "070108", "070110", "070111", "070112", "070204", "070208", "070210", "070211", "070212", "070213", "070214", "070304", "070308", "070310", "070311", "070312", "070404", "070408", "070410", "070504", "070508", "070510", "070511", "070512", "070604", "070608", "070610", "070611", "070612", "070704", "070708", "070710", "070711", "070712", "080111", "080113", "080115", "080117", "080119", "080312", "080314", "080409", "080413", "080501", "090101", "090103", "090104", "090105", "100101", "100102", "100103", "100104", "100105", "100113", "100114", "100115", "100116", "100117", "100118", "100119", "100120", "100121", "100122", "100123", "100210", "100211", "100215", "100325", "100326", "100327", "100328", "100409", "100410", "100508", "100509", "100707", "100708", "100819", "100820", "100905", "100906", "100907", "100908", "101005", "101006", "101007", "101008", "120105", "120106", "120107", "120108", "120109", "120110", "120112", "120114", "120116", "120117", "120118", "120120", "130101", "130104", "130105", "130109", "130110", "130111", "130112", "130113", "130204", "130205", "130206", "130207", "130208", "130301", "130306", "130307", "130308", "130309", "130310", "130401", "130402", "130403", "130501", "130502", "130503", "130506", "130507", "130508", "130701", "130702", "130703", "130801", "130802", "140603", "150103", "150105", "150106", "150110", "150202", "150203", "160103", "160708", "160709", "160801", "160802", "160803", "160804", "160805", "160807", "161105", "161106", "170201", "170203", "170204", "170301", "170302", "170303", "170503", "170504", "190203", "190204", "190205", "190206", "190207", "190208", "190209", "190210", "190304", "190305", "190306", "190307", "190805", "190902", "190903", "190904", "190906", "191101", "191206", "191207", "191209", "191210", "191211", "191212", "191301", "191302", "191303", "191304", "191305", "191306", "200108", "200113", "200125", "200126", "200137", "200138", "200139", "200303"],
        "Dotternhausen" => ["020705", "030305", "030310", "060314", "070299", "070599", "100117", "100908", "101008", "120107", "160103", "190204", "190805", "191205", "191210", "191212"],
        "Höver" => ["020204", "020305", "020402", "020403", "020502", "020603", "020701", "020705", "030104", "030302", "030305", "030309", "030310", "030311", "040214", "040216", "040219", "040220", "050103", "050106", "050109", "050110", "060201", "060502", "060503", "061101", "070107", "070108", "070109", "070110", "070111", "070112", "070207", "070208", "070209", "070210", "070211", "070212", "070214", "070307", "070308", "070309", "070310", "070311", "070312", "070408", "070410", "070411", "070412", "070508", "070510", "070511", "070512", "070607", "070608", "070609", "070610", "070611", "070612", "070708", "070710", "070711", "070712", "080111", "080112", "080113", "080114", "080117", "080201", "080207", "080312", "080313", "080314", "080315", "080409", "080410", "080411", "100102", "100103", "100120", "100121", "100210", "101301", "101304", "101306", "101307", "101311", "101312", "101313", "101314", "120102", "120104", "120112", "120118", "130502", "130503", "150110", "150202", "160306", "160708", "160709", "170101", "170204", "170301", "170303", "190204", "190209", "190210", "190305", "190307", "190811", "190812", "190813", "190814", "190901", "190902", "190903", "190905", "191101", "191105", "191106", "191206", "200108", "200125", "200126", "200128", "200137", "020103", "020104", "020107", "020304", "030101", "030105", "030301", "030307", "030308", "040209", "040210", "040215", "040221", "040222", "070213", "090107", "090108", "090110", "120105", "150101", "150102", "150103", "150105", "150106", "150109", "150203", "160119", "170201", "170203", "170604", "170904", "190203", "190210", "190501", "190502", "190503", "190805", "191004", "191201", "191204", "191207", "191208", "191210", "191212", "200101", "200110", "200111", "200138", "200139", "200203", "200301", "200302", "200307", "160103", "020102", "020202", "020203", "061302", "061303", "061305", "080317", "080318", "170303", "190904", "070103", "070104", "070203", "070204", "070303", "070304", "070403", "070404", "070503", "070504", "070603", "070604", "070703", "070704", "080319", "120110", "120119", "130101", "130110", "130111", "130112", "130113", "130204", "130205", "130207", "130208", "130301", "130306", "130307", "130309", "130310", "130401", "130402", "130403", "130506", "130701", "130702", "130703", "140602", "140603", "190207", "190208", "200113", "060203", "070601", "090101", "090102", "090103", "090104", "090105", "161002", "200117"],
    );

    $factoryAVV = array_search($avv, $certAvvs[$factoryName]);  // checkt, ob Avv in nächstgelegenem Werk zertifiziert ist
    if ($factoryAVV !== false) {
        //AVV im nähstgelegendem Werk zertifiziert
        $factCert = 1;
    } else {
        //AVV im nähstgelegendem Werk nicht zertifiziert
    }

    foreach ($certAvvs as $x => $x_value) { // checkt, ob Avv in überhaupt einem Werk zertifiziert ist
        $allAVV = array_search($avv, $certAvvs[$x]);
        if ($allAVV !== false) {
            //Zertifiziert
            $allCert = 1;
        } else {
            //nicht Zertifiziert
        }
    }
    /***
     * Gibt zurück, ob nahegelegenstes Werk die AVV zertifiziert hat (factCert ==> 1 oder 0)
     * und ob überhaupt ein Werk die AVV bereits zertifiziert hat (allCert ==> 1 oder 0)
     ***/
    //print_r([$factCert, $allCert]);
    return [$factCert, $allCert];
}

function getRecomendation($rohBrenn, $wasser, $asche, $chlor, $schwefel, $queck, $calcium, $silizium, $eisen, $aluminium, $kalium, $magnesium, $natrium, $rohMainParam, $menge, $preis, $abfPro, $avvZert)
{
    $param = [$rohBrenn, intval($wasser), intval($asche), intval($chlor), intval($schwefel), intval($queck), intval($calcium), intval($silizium), intval($eisen), intval($aluminium), intval($kalium), intval($magnesium), intval($natrium), intval($rohMainParam), intval($menge), intval($preis), intval($abfPro), intval($avvZert)];

    //Values für CSV zwischenSpeichern
    $_SESSION['valuesForCSV'] = json_encode($param);

    $dataset = new CsvDataset($_SERVER['DOCUMENT_ROOT'] . '/Projekte/geocycle/components/script/analyse/datasetsML/data.csv', 18, true);
    //$samples = [[1, 0, 0, 0, 0,0,1,1,1,1,1], [1, 0, 0, 0, 0,0,0,0,1,1,1], [1, 0, 0, 0, 0,0,0,0,1,0,1]];
    //$labels = ['a', 'b', 'b'];

    $samples = $dataset->getSamples();
    $labels = $dataset->getTargets();

    $classifier = new DecisionTree();
    $classifier->train($samples, $labels);

    $recomendation = $classifier->predict($param);

    echo $recomendation;

    if ($recomendation == "a") {
        $result = "Annehmen";
    } elseif ($recomendation == "b") {
        $result = "Ablehnen";
    } else {
        $result = $recomendation;
    }
    return $result;
}

?>