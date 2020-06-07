$(document).ready(function () {
    //Überprüfen ob neue Anfrage zugeordnet wurde (adminTeamDashboard)
    function showNewAllocatedRequest() {
        var htmlRow = "";
        $.ajax({
            url: 'components/script/isNewAllocated.php',
            method: "POST",
            dataType: "json",
            success: function (dataRequest) {
                for (var i = 0; i < dataRequest.length; i++) {
                    dataChange = i;
                    var obj = dataRequest[i];
                    htmlRow += "<tr><td>" + obj.requestId + "</td><td>" + obj.name + "</td><td>" + obj.town + "</td><td>" + obj.weight + "</td><td>" + obj.avv + "</td><td>" + obj.deliveryForm + "</td><td>" + obj.producer + "</td><td><form id=\"shoeAll" + obj.requestId + "\" method=\"get\" action=\"selectedRequestTeam.php\"><input type=\"hidden\" name=\"selectedRequest\" value=\"" + obj.requestId + "\"><button type=\"submit\" id=\"btnShowAll" + obj.requestId + "\" class=\"btn btn-light-green\">Anzeigen</button></form></td></tr>";
                }
                    //$('#tbodyNewRows').html(htmlRow);
            }
        })
    }
    //setInterval(function () {
    //showNewAllocatedRequest();
    //}, 1000);
})
