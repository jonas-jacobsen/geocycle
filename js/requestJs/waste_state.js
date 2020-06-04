/**
 * Produktssatus vs. Abfallstatus [Buttons]
 **/
let productState = document.getElementById("produkt");
let wasteState = document.getElementById("abfall");
let productSec = document.getElementById("productSec");
let wasteSec = document.getElementById("wasteSec");

function choosteState(state) {
    // product state
    if (state == 0) {
        wasteState.style.backgroundColor = "#fff";
        productState.style.backgroundColor = "green";
        wasteSec.style.display = "none";
        productSec.style.display = "block";

        // waste state
    } else if (state == 1) {
        productState.style.backgroundColor = "#fff";
        wasteState.style.backgroundColor = "green";
        productSec.style.display = "none";
        wasteSec.style.display = "block";
    }
}

productState.addEventListener('change', function() {choosteState(0);}, );
wasteState.addEventListener('change', function() {choosteState(1);}, );


/**
 * Name der Erzeugerfirma abfragen, wenn radio button gewÃ¤hlt
 **/
let trader = document.getElementById("trader");
let producer = document.getElementById("producer");
let namefield = document.getElementById("namefield");

function showNamefield(state) {
    // producer
    if (state == 0) {
        namefield.style.display = "none";

        // trader
    } else if (state == 1) {
        namefield.style.display = "block";
    }
}

producer.addEventListener('change', function() {showNamefield(0);}, );
trader.addEventListener('change', function() {showNamefield(1);}, );