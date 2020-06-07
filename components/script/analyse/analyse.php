<?php

function showAnalysis($requestId, $conn)
{
    //Todo:Temp Preisvaribale Ändern!
    $preis = 2;
    $kohleVergPreis = 4;

    $sql = "SELECT * FROM userdata WHERE id = $requestId";
    $stmt = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($stmt);

    $amount = $row['JaTo'];

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

    //Paramter aus Json extrahieren und in varibalen speichern
    $unterHo = str_replace(',', '.', $paramJson[0]->value);
    $wassergehalt = str_replace(',', '.', $paramJson[1]->value);
    $aschegehalt = str_replace(',', '.', $paramJson[2]->value);
    $chlor = str_replace(',', '.', $paramJson[3]->value);
    $schwefel = str_replace(',', '.', $paramJson[4]->value);
    $quecksilber = str_replace(',', '.', $paramJson[5]->value);

    //Menge Überprüfen
    if($amount > 5000){
        echo "Menge in Ordnung";
    }else {
        echo "Menge zu gering";
    }

    if ($unterHo < 10) {
        //heizwert unter 10 dann Rohstoff
        echo "Rohstoff<br>";

    } else {
        //heizwert über 10 dann Brennstoff
        echo "Brennstoff<br>";
        if ($unterHo < 20) {
            //Brennstoff Ofeneinlauf
            echo "Brennstoff für Offeneinlauf<br>";
            checkParam($paramLimitOfen, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber);
        } else {
            //Brennstoff für Hauptbrenner
            echo "Brennstoff für Hauptbrenner<br>";
            checkParam($paramLimitHaupt, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber);
        }//parameterübrüfung end
        //preiskalkulation
        $zuProKilo = $preis/1000;
        if($zuProKilo <= $kohleVergPreis){
            echo"Wirtschaftlich Interessant";
        }elseif($zuProKilo > $kohleVergPreis && $unterHo > 30){
            //ToDo: was heißt brennt gut, wenn heizwert > 30 dann gut?
            echo "Wirtschaftlich nicht interessant, aber Stoff brennt heftig";
        }else{
            echo "Wirtschaftlich und stofflich nicht Interessant";
        }
    }
}


//funktion zur Überprüfung der Parameter für den Ofeneinlauf
function checkParam($paramLimit, $wassergehalt, $aschegehalt, $chlor, $schwefel, $quecksilber)
{
    $queck = "$quecksilber".$paramLimit['quecksilberParamLimit'];
    $wasser = "$wassergehalt".$paramLimit['wasserParamLimit'];
    $asche = "$aschegehalt".$paramLimit['ascheParamLimit'];
    $chlor = "$chlor".$paramLimit['chlorParamLimit'];
    $schwefel = "$schwefel".$paramLimit['schwefelParamLimit'];

    if ($queck) {
        echo "<br>Quecksilber im guten Bereich<br>";
    } else {
        echo "<br>Queckilber zu hoch<br>";
    }
    if ($wasser) {
        echo "Wassergehalt im guten Bereich<br>";
    } else {
        echo "Wassergehalt zu hoch<br>";
    }
    if ($asche) {
        echo "Aschegehalt im guten Bereich<br>";
    } else {
        echo "Aschegehalt zu hoch<br>";
    }
    if ($chlor < 1) {
        echo "Chlorgehalt im guten Bereich<br>";
    } else {
        echo "Chlorgehalt zu hoch<br>";
    }
    if ($schwefel < 1) {
        echo "Schwefel im guten Bereich<br>";
    } else {
        echo "Schwefel zu hoch<br>";
    }

}

?>

