/**
 * Script to decide which facility is closesed to company
 **/

console.log("Initalize faciDecision");

const werkHH = "[9.993682,53.551086]"; //Hamburg
const werkLU = "[10.409343,53.245938]"; //LÃ¼neburg
const werkBLN = "[13.409657,52.517632]"; //Berlin
// var custAdress = "[10.372797,53.427942]"; //Geesthacht
let addresses;
var custAdress;

let distances;

function calcDistances() {
    console.log("calculate closest facility");
    let request = new XMLHttpRequest();

    request.open('POST', "https://api.openrouteservice.org/v2/matrix/driving-car");

    request.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');
    request.setRequestHeader('Content-Type', 'application/json');
    request.setRequestHeader('Authorization', '5b3ce3597851110001cf62480b008bd0ed3e434ca180a642aa491b11');

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            // console.log('Status:', this.status);
            // console.log('Headers:', this.getAllResponseHeaders());
            // console.log('Body:', this.responseText);
            distances = JSON.parse(this.responseText);
            console.log(distances);
            getClosestFacility(distances.distances[0]);
        }
    };

    // let adresses = [custAdress, werk1, werk2, werk3];
    addresses = [
        {name: "customAdress", coords: custAdress},
        {name: "werkHH", coords: werkHH},
        {name: "werkLU", coords: werkLU},
        {name: "werkBLN", coords: werkBLN},
    ]

    addressCoords = addresses.map(function(elem){
        return elem.coords;
    }).join(","); // join array of all addresses to one string
    console.log(addresses);
    const body = '{"locations":[' + addressCoords + '],"metrics":["distance"]}';

    request.send(body);
}



function getClosestFacility(array) {
    console.log(array);
    array.shift();
    console.log("---------------");
    var i = array.indexOf(Math.min.apply(Math, array));
    console.log("Smallest distance: " + array[i]/ 1000 + " km");
    console.log("Chosen facility: " + addresses[i+1].name); // cropped 0 from results, so addresses array is shifted by one position
    console.log("---------------");
    //document.getElementById("result").innerText = "Smallest distance: " + Math.round(array[i]/1000) + " km --- Closest facility: " +  addresses[i].name;
    document.getElementById("facility").value = addresses[i+1].name;
}



// get lat-long to adress
function getLatLong() {
    console.log("getLatLong");
    let street = document.getElementById("street").value;
    let zip = document.getElementById("zipcode").value;
    let town = document.getElementById("town").value;
    custAdress = [street, zip, town].join(",")

    var req = new XMLHttpRequest();

    url = 'https://api.openrouteservice.org/geocode/search?api_key=5b3ce3597851110001cf62480b008bd0ed3e434ca180a642aa491b11&text=' + custAdress;
    req.open('GET', url);

    req.setRequestHeader('Accept', 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8');

    req.onreadystatechange = function () {
        if (this.readyState === 4) {
            // console.log('Status:', this.status);
            // console.log('Headers:', this.getAllResponseHeaders());
            // console.log('Body:', this.responseText);
            // console.log(JSON.parse(this.responseText));
            var temp = JSON.parse(this.responseText);
            var long = temp.bbox[0];
            var lat = temp.bbox[1];
            // console.log(lat, long);
            custAdress = '[' + long + ',' + lat + ']';
            calcDistances();
        }
    };

    req.send();
}

/* Eventlistener */
let townField = document.getElementById('town');
townField.addEventListener('change', getLatLong, false);

let submitAnsprechButton = document.getElementById('submitAnsprech');
submitAnsprechButton.addEventListener('click', getLatLong, false);


