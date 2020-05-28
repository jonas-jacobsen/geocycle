//Anfragen mit ajax call zuweisen und zugewiesene Zeile verschwinden lassen
$(document).on("click", ".buttonChangeCategory", function () {
    var id = $(this).attr("id");
    var value = $(this).attr("value");
    var requestId;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        async: true,
        url: 'components/script/updateAllocationAjax.php',
        data: {
            id: id,
            value: value,
        },
        success: function (data) {
            requestId = data.requestNb;
            $('#rowWithId' + requestId).hide();
            $('#allocationValue' + requestId).html(value);
        },
    });
});


$(document).ready(function () {
    //Überprüfen ob Anfrage bearbeitet wurde und sich der Status geändert hat (adminDashboard)
    function updateProcessingStatus() {
        var backgroundColor;
        $.ajax({
            url: 'components/script/updateProcessingStatus.php',
            method: "POST",
            dataType: "json",
            success: function (responseData) {
                for(var i = 0; i < responseData.length; i++) {
                    var obj = responseData[i];
                    if(obj.status == 1){
                        backgroundColor = "";
                    }else if (obj.status == 2){
                        backgroundColor = "#00800030";
                    }else if (obj.status == 3){
                        backgroundColor = "#f4433652";
                    }
                    $('.rowId'+obj.requestId).css("background-color", backgroundColor);
                }
            }
        })
    }
    setInterval(function () {
        updateProcessingStatus();
    }, 1000);
})