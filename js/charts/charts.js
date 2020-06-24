var ctx = document.getElementById("lineChart").getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
        datasets: [{
            label: 'Gesamt Anzahl',
            data: [jan, feb, mae, apr, mai, jun, jul, aug, sep, okt, nov, dec],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
                'rgba(255,99,132,1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

//doughnut
var ctxD = document.getElementById("pieChart").getContext('2d');
var myLineChart = new Chart(ctxD, {
    type: 'doughnut',
    data: {
        labels: ["Produktstatus", "Abfallstatus"],
        datasets: [{
            data: prodAbfData,
            backgroundColor: ["#F7464A", "#46BFBD"],
            hoverBackgroundColor: ["#FF5A5E", "#5AD3D1"]
        }]
    },
    options: {
        responsive: true
    }
});

//doughnut AVV
var ctxDAVV = document.getElementById("pieChartAVV").getContext('2d');
var myLineChartAVV = new Chart(ctxDAVV, {
    type: 'doughnut',
    data: {
        labels: avvLables,
        datasets: [{
            data: avvData,
            backgroundColor: ["#F7464A", "#46bfbd","#4699bf","#4676bf","#6646bf","#5935bf","#4b25b8","#a211ac", "#bb2c82", "#c63e69"],
            hoverBackgroundColor: ["#FF5A5E", "#5AD3D1","#4e9ec2", "#5786ce","#795ad2","#6d47d9","#5931cb","#b32cbb", "#c63e90", "#d0527a"]
        }]
    },
    options: {
        responsive: true
    }
});