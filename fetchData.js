src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"

window.onload = function(){
    getBuildingData();
    getTwelveMonthData();
    getTimeOfDayEnergyUsage();
    getMonthtoDateUsage();
    getUnreadMeterData();
}

function getBuildingData() {
    $.ajax({
        type: 'GET',
        url: 'compareBuildings.php',
        data: {'propertyId': '1'},
        dataType: 'json',
        success: function (response) {
            var ctx = document.getElementById("interBuildingComparison").getContext('2d');
            var arr =[response[0], response[1], response[2], response[3]];
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    barValueSpacing: 0,
                    labels: ["August","September", "October", "November"],
                    datasets: [
                        {
                            label: 'Your Building',
                            data: [arr[0].property_totals[0], arr[1].property_totals[0], arr[2].property_totals[0], arr[3].property_totals[0]],
                            backgroundColor: ['rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)']
                        },
                        {
                            label: 'First Comparison',
                            data: [arr[0].property_totals[1], arr[1].property_totals[1], arr[2].property_totals[1], arr[3].property_totals[1]],
                            backgroundColor: ['rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)']
                        },
                        {
                            label: 'Second Comparison',
                            data: [arr[0].property_totals[2], arr[1].property_totals[2], arr[2].property_totals[2], arr[3].property_totals[2]],
                            backgroundColor: ['rgba(75, 192, 192, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(75, 192, 192, 0.8)']
                        }
                        ]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                autoSkip: false,
                                beginAtZero:true,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Energy Consumption(kwh)',
                                fontSize: 13
                            },
                            labelFontWeight: "bold"

                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                beginAtZero:true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Month',
                                fontSize: 13
                            }
                        }
                        ]
                    },
                    legend: {
                        position: 'top'
                    }
                }
            });
        }
    }
    )};

function getTwelveMonthData() {
    $.ajax({
        type: 'GET',
        url: 'twelveMonthAverage.php',
        data: {'propertyId': '1'},
        dataType: 'json',
        success: function(response) {
            var ctx = document.getElementById("twelveMonthDataGraph").getContext('2d');
            var arr = [response[0], response[1], response[2], response[3]];
            var myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: ["August", "September", "October", "November"],
                    datasets: [{
						label: 'Energy Consumption(kwh)',
                        data: arr,
                        backgroundColor: [
                            'rgba(75, 192, 192, 1.0)',
                            'rgba(75, 192, 192, 1.0)',
                            'rgba(75, 192, 192, 1.0)',
                            'rgba(75, 192, 192, 1.0)'
                        ]
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                autoSkip: false,
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Month',
                                fontSize: 13

                            }
                        }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false,
                            beginAtZero:true
                            //minRotation: 90
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Energy Consumption(kwh)',
                            fontSize: 13
                        }}]
                    },
                    legend: {
                        position: 'top'
                    }
                }
            });
        }
    });

}

function getTimeOfDayEnergyUsage() {
    $.ajax({
        type: 'GET',
        url: 'timeOfDayEnergyUse.php',
        data: {'propertyId': '1'},
        dataType: 'json',
        success: function (response) {
            var ctx = document.getElementById("timeOfDayEnergyUse").getContext('2d');
            var arr =[response[0], response[1], response[2]];
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["August","September", "October", "November"],
                    datasets: [{
                        label: 'Low',
                        data: [arr[2][0], arr[2][1], arr[2][2], arr[2][3]],
                             backgroundColor: [
                                 'rgba(75, 192, 192, 1.0)',
                                 'rgba(75, 192, 192, 1.0)',
                                 'rgba(75, 192, 192, 1.0)',
                                 'rgba(75, 192, 192, 1.0)'
                             ]
                        },
                        {
                            label: 'Mid',
                            data: [arr[1][0], arr[1][1], arr[1][2], arr[1][3]],
                            backgroundColor: [
                                'rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)',
                                'rgba(31, 152, 59, 0.8)'
                            ]
                        },
                        {
                            label: 'High',
                            data: [arr[0][0], arr[0][1], arr[0][2], arr[0][3]],
                            backgroundColor: [
                                'rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)',
                                'rgba(198, 43, 43, 0.8)'
                            ]
                        }
                    ]
                },
                options: {
                    legend: {
                        position: 'top'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                autoSkip: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Cost ($)',
                                fontSize: 13
                            },
                            stacked:true
                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Month',
                                fontSize: 13
                            },
                            stacked:true
                        }]
                    }
                }
            });
        }
    })
}

function getMonthtoDateUsage() {
    $.ajax({
        type: 'GET',
        url: 'monthToDate.php',
        data: {'propertyId': '1'},
        dataType: 'json',
        success: function (response) {
            var ctx = document.getElementById("monthToDate").getContext('2d');
            var arr = [];
            var days = [];
            var current_day = 1;
            response.forEach(function (value) {
                arr.push(value);
                days.push(current_day);
                current_day++;
            });
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: 'Energy Consumption(kwh)',
                        data: arr,
                        backgroundColor: [
                            'rgba(75, 192, 192, 1.0)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                autoSkip: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Energy Consumption(kwh)',
                                fontSize: 13
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                beginAtZero: true
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Day of Month',
                                fontSize: 13
                            }
                        }]
                    },
                    legend: {
                        position: 'top'
                    }
                }

            });
        }
      })
    }



function getUnreadMeterData() {
    $.ajax({
         type: 'GET',
         url: 'missingMeterData.php',
         data: {'clientId': '3'},
         dataType: 'json',
         success: function(response){
                 $('#missingReadsTable').bootstrapTable({data: response});
         }
    });

}

