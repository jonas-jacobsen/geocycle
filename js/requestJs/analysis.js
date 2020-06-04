/**
 * Checks if Hu is lower of higher than 10
 * for determining between "Brennstoff" and "Rohstoff"
 **/
let huInput = document.querySelector('#row0 > input[name="value"]');
let huResult = document.getElementById('huResult');
function checkHu() {
    let huValue = huInput.value;
    huValue = huValue.replace(/,/gi, '.');
    huValue = huValue.replace(/[<>]/gi, '');
    if (huValue >= 10) {
        huResult.innerHTML = "BRENNSTOFF";
        console.log(huValue);
    } else if (huValue < 10) {
        huResult.innerHTML = "ROHSTOFF";
        console.log(huValue);
    } else {
        huResult.innerHTML = "Hu Undefiniert";
        console.log(huValue);
    }
}
huInput.addEventListener('change', checkHu);


/**
 * Add new Row with custom parameter to analyse-Form
 **/
let addRowButton = document.getElementById("addRowButton");
let id = 5; // index id for new row, to keep track of row numbers --> number of required fields
let rowContent = '<input type="text" name="param" placeholder="Parameter"/><input type="text" name="value" placeholder="Messwert" autocomplete="off" required pattern="[0-9<>,]{1,}" title="Nur \'1-9\', \',\' und \'< >\'"/><select name="unit"><option selected="">mg/kg</option><option>ng/kg</option><option>Âµg/g</option><option>mj/kg</option><option>% TS</option><option>% OS</option></select>'; // inner HTML of blank row
let analyseForm = document.getElementById('param-div'); // Form to add row to

function addRow() {
    id++;
    let newRow = document.createElement('div');
    newRow.className = "ing-row";
    newRow.id = "row" + id;
    newRow.innerHTML = rowContent;
    analyseForm.appendChild(newRow);
    let lastEntry = analyseForm.lastChild.querySelector("[name='param']") // gets Param-Input for created entry
    autocomplete(lastEntry, parameterSuggs); // adds autocomplete for Parameter-Input
    lastEntry.focus();
}
addRowButton.addEventListener('click', addRow);



/**
 * Save all typed-in parameter as json string
 **/
let saveListButton = document.getElementById("saveListButton");
let analysisString = document.getElementById("analysisString");
function saveList() {
    let ingValues = []; // empty array to save list in
    let params = analyseForm.querySelectorAll("[name='param']"); // all params of added ingedients
    let values = analyseForm.querySelectorAll("[name='value']");// all values of added ingedients
    let units = analyseForm.querySelectorAll("[name='unit']"); // all units of added ingedients
    let ingRow = document.getElementsByClassName("ing-row"); // all rows
    for (var i = 0; i < ingRow.length; i++) {
        let row = { //create associative array for each row with keys
            "param" : params[i].value,
            "value" : values[i].value,
            "units" : units[i].value
        }
        ingValues.push(row);
    }
    var listJson = JSON.stringify(ingValues);

    console.log(ingValues);
    console.log(listJson);
    analysisString.value = listJson;

}
saveListButton.addEventListener('click', saveList);





