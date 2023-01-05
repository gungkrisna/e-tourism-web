$(document).ready(function () {

    "use strict";

    var options = {
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        series: [{
            name: "Kunjungan",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Kunjungan Website dalam Bulan',
            align: 'center'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            borderColor: 'rgba(94, 96, 110, .5)',
            strokeDashArray: 4
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep'],
            labels: {
                style: {
                    colors: 'rgba(94, 96, 110, .5)'
                }
            }
        }
    }

    var viewStats = new ApexCharts(
        document.querySelector("#apex1"),
        options
    );

    var options2 = {
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        series: [{
            name: "Ulasan",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Ulasan Pengguna dalam Bulan',
            align: 'center'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
            borderColor: 'rgba(94, 96, 110, .5)',
            strokeDashArray: 4
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep'],
            labels: {
                style: {
                    colors: 'rgba(94, 96, 110, .5)'
                }
            }
        }
    }

    var rankStats = new ApexCharts(
        document.querySelector("#apex2"),
        options2
    );

    viewStats.render();
    rankStats.render();
});