<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Line Chart with Scroll and Zoom</title>
</head>
<body>
<style>
#chartdiv {
  width	: 100%;
  height: 500px;
}																	
</style>
<!-- partial:index.partial.html -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<!-- partial -->
<script>
var chartData = generateChartData();
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "marginRight": 80,
    "autoMarginOffset": 20,
    "marginTop": 7,
    "dataProvider": chartData,
    "valueAxes": [{
        "axisAlpha": 0.2,
        "dashLength": 1,
        "position": "left"
    }],
    "mouseWheelZoomEnabled": true,
    "graphs": [{
        "id": "g1",
        "balloonText": "[[value]]",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "hideBulletsCount": 50,
        "title": "red line",
        "valueField": "visits",
        "useLineColorForBulletBorder": true,
        "balloon":{
            "drop":true
        }
    }],
    "chartScrollbar": {
        "autoGridCount": true,
        "graph": "g1",
        "scrollbarHeight": 40
    },
    "chartCursor": {
       "limitToGraph":"g1"
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "axisColor": "#DADADA",
        "dashLength": 1,
        "minorGridEnabled": true
    },
    "export": {
        "enabled": true
    }
});

chart.addListener("rendered", zoomChart);
zoomChart();

// this method is called when chart is first inited as we listen for "rendered" event
function zoomChart() {
    // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
    chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
}

function generateChartData() {
    var chartData = [<?php
                    $connection = mysqli_connect("localhost","root","","emt");
                    $sql2 = "SELECT CAST((P_DATE) AS DATE), COUNT(*) AS count 
                    FROM payslip 
                    LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID
                    WHERE YEAR(P_DATE)='2020' AND P_STATUS=1 AND EMP_STATUS=1
                    GROUP BY MONTH(P_DATE)";
                    $result2 = $connection->query($sql2);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while($row2 = $result2->fetch_assoc()) {
                           $count = $row2["count"]; 
                           $year = substr($row2["CAST((P_DATE) AS DATE)"],0,4);
                            $month = substr($row2["CAST((P_DATE) AS DATE)"],5,2);
                            $day = substr($row2["CAST((P_DATE) AS DATE)"],8,2);
                            $dateObj  = DateTime::createFromFormat('!m', $month);
                            $monthName = $dateObj->format('M');
                           echo"
                           {
                            date: '".$monthName." ".$day." ".$year."',
                            visits: ".$count."
                          },";
                         }
                        } else {
                          echo'
                          {
                           date: February 28,
                           visits: 0
                         },';
                        }
               
                  ?>];
    
    return chartData;
}
</script>

<!-- HTML -->
<div id="chartdiv"></div>

</body>
</html>
