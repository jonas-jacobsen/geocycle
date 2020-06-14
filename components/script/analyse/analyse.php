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

    /*Testing*/
    if ($materialOrBurn == "Rohstoff") {
        $paramEval = checkParamMaterialMain($paramJson);
        buildHTMLForMaterial($offeredPrice, $paramEval, $paramJson, $amount, $closestFactory, $avv, $prodAbf);
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

    //Werk auf AVV nummer überprüfen
    $avvComment = "";
    $isAVVForFactory = avvChecker($closestFactory, $avv);
    if($isAVVForFactory[0] == 1){
        $avvIcon = iconChecker(1);
        $avvComment = "Avv ist im nähstgelegenden Werk Zertifiziert";
    }else{
        if($isAVVForFactory[1] == 1){
            $avvIcon = iconChecker(1);
            $avvComment = "Avv ist nicht in nähstgelegendem Werk aber in einem anderen Werk Zertifiziert";
        }else{
            $avvIcon = iconChecker(0);
            $avvComment = "Avv ist in keinem Werk Zertifiziert";
        }
    }

    //Ab hier HTML
    echo "<h4>Brennstoff</h4>";
    if($prodAbf=="Abfall"){
        echo "<div class='row'><div class='col-3'>Nähstgelegendes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'>$avvIcon</div><div class='col-4'>$avvComment</div></div>";
    }   else{
        echo "<div class='row'><div class='col-3'>Nähstgelegendes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'></div><div class='col-4'></div></div>";
    }
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

//Funktion zum HTML-Aufbau der der Analyse für Rohstoff
function buildHTMLForMaterial($offeredPrice, $paramEval, $paramJson, $amount, $closestFactory, $avv, $prodAbf)
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
            $isEconomicHtml = "Stoff ist Hauptbestandteil aber wirtschaftlich nicht interessant, da der $paramEval[0]preis mit " . round(abs($preisProTonne), 2) . "€/Tonne teurer ist als der Vergleichspreis von " . abs($vergleichsPreis) . "€/Tonne";
            $iconCheckParamHigh = iconChecker(1);
            $highestParamComment = "Stoff ist Hauptbestandteil des Klinkers";
            break;
        case 1:
            $isEconomicHtml = "Stoff ist Hauptbestandteil und wirtschaftlich interessant, da der $paramEval[0]preis mit " . round(abs($preisProTonne), 2) . "€/Tonne günstiger ist als der Vergleichspreis von " . abs($vergleichsPreis) . "€/Tonne";
            $iconCheckParamHigh = iconChecker(1);
            $highestParamComment = "Stoff ist Hauptbestandteil des Klinkers";
            break;
        case 2:
            $isEconomicHtml = "Stoff ist kein Hauptbestandteil aber wirtschaftlich interessant, da die Zuzahlung an Geocycle mit " . abs($offeredPrice) . "€/Tonne über dem Grenzwert von 10€ liegt";
            $iconCheckParamHigh = iconChecker(0);
            $highestParamComment = "Stoff ist kein Hauptbestandteil des Klinkers";
            break;
        case 3:
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

    //Werk auf AVV nummer überprüfen
    $avvComment = "";
    $isAVVForFactory = avvChecker($closestFactory, $avv);
    if($isAVVForFactory[0] == 1){
        $avvIcon = iconChecker(1);
        $avvComment = "Avv ist im nähstgelegenden Werk Zertifiziert";
    }else{
        if($isAVVForFactory[1] == 1){
            $avvIcon = iconChecker(1);
            $avvComment = "Avv ist nicht in nähstgelegendem Werk aber in einem anderen Werk Zertifiziert";
        }else{
            $avvIcon = iconChecker(0);
            $avvComment = "Avv ist in keinem Werk Zertifiziert";
        }
    }

    echo "<h4>Rohstoff</h4>";
    if($prodAbf=="Abfall"){
        echo "<div class='row'><div class='col-3'>Nähstgelegendes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'>$avvIcon</div><div class='col-4'>$avvComment</div></div>";
    }   else{
        echo "<div class='row'><div class='col-3'>Nähstgelegendes Werk</div><div class='col-3'>$closestFactory</div><div class='col-1'></div><div class='col-4'></div></div>";
    }
    echo "<div class='row'><div class='col-3'>Hauptbestandteil</div><div class='col-3'>$paramEval[0]</div><div class='col-1'>$iconCheckParamHigh</div><div class='col-4'>$highestParamComment</div></div>";
    echo "<div class='row'><div class='col-3'>Menge</div><div class='col-3'>$amount JaTo</div><div class='col-1'>$iconCheckAmount</div><div class='col-4'>$htmlAmount</div></div><br>";
    for ($i = 0; $i < $countJson; $i++) {
        $name = $paramJson[$i]->param;
        $value = $paramJson[$i]->value;
        $unit = $paramJson[$i]->units;
        $comment = "";
        if ($value == 0) {
        } else {
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
    $isEconomic = 0;
    //ToDo: Brennwert vergleichen mit Kohlepreis;
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
        "Beckum" => ["020103","020104","020107","020203","020304","030101","030105","030301","030302","030307","030308","040209","040221","040222","070213","070299","080112","080114","080201","090107","090108","120105","150101","150102","150103","150105","150106","150203","160103","170201","170203","170302","190501","191201","191204","191207","191208","191210","191212"],
        "Lägerdorf" => ["2","0202","020202","020203","0203","020304","3","0301","030101","030104","030105","0303","030301","030305","030307","030308","030309","030310","030311","4","0402","040210","040216","040221","5","0501","050103","050106","050113","050115","6","0602","060203","0605","060502","0613","061303","061305","7","0701","070104","070108","070110","070111","070112","0702","070204","070208","070210","070211","070212","070213","070214","0703","070304","070308","070310","070311","070312","0704","070404","070408","070410","0705","070504","070508","070510","070511","070512","0706","070604","070608","070610","070611","070612","0707","070704","070708","070710","070711","070712","8","080111","080113","080115","080117","080119","0803","080312","080314","0804","080409","080413","0805","080501","9","0901","090101","090103","090104","090105","10","1001","100101","100102","100103","100104","100105","100113","100114","100115","100116","100117","100118","100119","100120","100121","100122","100123","1002","100210","100211","100215","1003","100325","100326","100327","100328","1004","100409","100410","1005","100508","100509","1007","100707","100708","1008","100819","100820","1009","100905","100906","100907","100908","1010","101005","101006","101007","101008","12","1201","120105","120106","120107","120108","120109","120110","120112","120114","120116","120117","120118","120120","13","1301","130101","130104","130105","130109","130110","130111","130112","130113","1302","130204","130205","130206","130207","130208","1303","130301","130306","130307","130308","130309","130310","1304","130401","130402","130403","1305","130501","130502","130503","130506","130507","130508","1307","130701","130702","130703","1308","130801","130802","14","1406","140603","15","1501","150103","150105","150106","150110","1502","150202","150203","16","1601","160103","1607","160708","160709","1608","160801","160802","160803","160804","160805","160807","1611","161105","161106","17","1702","170201","170203","170204","1703","170301","170302","170303","1705","170503","170504","19","1902","190203","190204","190205","190206","190207","190208","190209","190210","1903","190304","190305","190306","190307","190805","1909","190902","190903","190904","190906","1911","191101","1912","191206","191207","191209","191210","191211","191212","1913","191301","191302","191303","191304","191305","191306","20","2001","200108","200113","200125","200126","200137","200138","200139","2003","200303"],
        "Dotternhausen" => ["020705","030305","030310","060314","070299","070599","100117","100908","101008","120107","160103","190204","190805","191205","191210","191212"],
        "Höver" => ["0202","020204","0203","020305","0204","020402","020403","0205","020502","0206","020603","0207","020701","020705","0301","030104","0303","030302","030305","030309","030310","030311","0402","040214","040216","040219","040220","0501","050103","050106","050109","050110","0602","060201","0605","060502","060503","0611","061101","0701","070107","070108","070109","070110","070111","070112","0702","070207","070208","070209","070210","070211","070212","070214","0703","070307","070308","070309","070310","070311","070312","0704","070408","070410","070411","070412","0705","070508","070510","070511","070512","0706","070607","070608","070609","070610","070611","070612","0707","070708","070710","070711","070712","0801","080111","080112","080113","080114","080117","0802","080201","0803","080207","080312","080313","080314","080315","0804","080409","080410","080411","1001","100102","100103","100120","100121","1002","100210","1013","101301","101304","101306","101307","101311","101312","101313","101314","1201","120102","120104","120112","120118","1305","130502","130503","1501","150110","1502","150202","1603","160306","1607","160708","160709","1701","170101","1702","170204","1703","170301","170303","1902","190204","190209","190210","1903","190305","190307","1908","190811","190812","190813","190814","1909","190901","190902","190903","190905","1911","191101","191105","191106","1912","191206","2001","200108","200125","200126","200128","200137","0201","020103","020104","020107","0203","020304","0301","030101","030105","0303","030301","030307","030308","0402","040209","040210","040215","040221","040222","0702","070213","0901","090107","090108","090110","1201","120105","1501","150101","150102","150103","150105","150106","150109","1502","150203","1601","160119","1702","170201","170203","1706","170604","1709","170904","1902","190203","190210","1905","190501","190502","190503","1908","190805","1910","191004","1912","191201","191204","191207","191208","191210","191212","2001","200101","200110","200111","200138","200139","2002","200203","2003","200301","200302","200307","1601","160103","0201","020102","0202","020202","020203","0613","061302","061303","061305","0803","080317","080318","1703","170303","1909","190904","0701","070103","070104","0702","070203","070204","0703","070303","070304","0704","070403","070404","0705","070503","070504","0706","070603","070604","070703","070704","0803","080319","1201","120107","120110","120119","1301","130101","130109","130110","130111","130112","130113","1302","130204","130205","130206","130207","130208","1303","130301","130306","130307","130308","130309","130310","1304","130401","130402","130403","1305","130506","1307","130701","130702","130703","1406","140602","140603","1902","190207","190208","2001","200113","0602","060203","0706","070601","0901","090101","090102","090103","090104","090105","1610","161002","2001","200117"],
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

?>