<?php
function binarySort($array, $element, $posMiddle, $lastValue){
    if($element == $array[$posMiddle]){
        echo "gefunden an Stelle ".round($posMiddle);
    }elseif ($element < $array[$posMiddle]){
        $lastValue = $posMiddle;
        $posMiddle = $posMiddle/2;
        binarySort($array,$element, $posMiddle, $lastValue);
    }
    elseif ($element > $array[$posMiddle]){
        $posMiddle = $posMiddle+$posMiddle/2-1;
       binarySort($array,$element, $posMiddle, $lastValue);
    }else{
        echo "nicht gefunden";
    }
}


echo "binarySortTest: <br>";
echo "array: 1,2,3,4,5,6,7,8,9,10,11,12 <br>";
$search = 11;
echo "suche: ".$search;
echo "<br><br>";
$array = [1,2,3,4,5,6,7,8,9,10,11,12];
$countArray = count($array)-1;
$half = (int)$countArray/2;
binarySort($array,$search, $half, $countArray);
?>