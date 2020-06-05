$(document).ready(function () {
    //prüfung welcher Radio button ausgewählt ist: Prd/Abfall
    $("#produkt").ready(function () {
        if ($("#produkt:checked").val() == "Produktstatus") {
            $(".produktstatus").show();
            $(".abfallstatus").hide();
            //checkliste Dokument zeigen
            $("#checkIsHidden").show();
        }
    });
    $("#abfall").ready(function () {
        if ($("#abfall:checked").val() == "Abfall") {
            $(".abfallstatus").show();
            $(".produktstatus").hide();
            //checkliste Dokument verstecken
            $("#checkIsHidden").hide();
        }
    });
    $("#produkt").click(function () {
        if ($("#produkt:checked").val() == "Produktstatus") {
            $(".produktstatus").show();
            $(".abfallstatus").hide();
            //checkliste Dokument zeigen
            $("#checkIsHidden").show();
        }
    });
    $("#abfall").click(function () {
        if ($("#abfall:checked").val() == "Abfall") {
            $(".abfallstatus").show();
            $(".produktstatus").hide();
            //checkliste Dokument verstecken
            $("#checkIsHidden").hide();
        }
    });
    //prüfung welcher Radio button ausgewählt ist: Erz/Händl
    $("#erzeuger").ready(function () {
        if ($("#erzeuger:checked").val() == "Erzeuger") {
            $(".haendler").show();
            $(".erzeuger").hide();
        }
    });
    $("#haendler").ready(function () {
        if ($("#haendler:checked").val() == "Händler") {
            $(".erzeuger").show();
            $(".haendler").hide();
        }
    });
    $("#erzeuger").click(function () {
        if ($("#erzeuger:checked").val() == "Erzeuger") {
            $(".haendler").show();
            $(".erzeuger").hide();
        }
    });
    $("#haendler").click(function () {
        if ($("#haendler:checked").val() == "Händler") {
            $(".erzeuger").show();
            $(".haendler").hide();
        }
    });

});