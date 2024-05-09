<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>
<!-- Resources -->
<script src="http://localhost/emt/lib/core.js"></script>
<script src="http://localhost/emt/lib/charts.js"></script>
<script src="http://localhost/emt/lib/animated.js"></script>
<!-- <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script> -->
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />

<!-- Chart code -->
<script>
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end
// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart);

// Add data
chart.data = [{
  <?php 
  $connection = mysqli_connect("localhost","root","","emt");
  $query = "SELECT CAST((P_DATE) AS DATE), COUNT(*) AS count 
            FROM payslip 
            LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID
            WHERE YEAR(P_DATE)='2020' AND P_STATUS=1 AND EMP_STATUS=1
            GROUP BY MONTH(P_DATE);";
  $result = $connection->query($query);
  if ($result->num_rows > 0) 
  {
      // output data of each row
      while($row = $result->fetch_assoc()) 
      {
        $count = $row["count"];
        $year = substr($row2["CAST((month) AS DATE)"],0,4);
          $month = substr($row2["CAST((month) AS DATE)"],5,2);
          $day = substr($row2["CAST((month) AS DATE)"],8,2);
          $dateObj  = DateTime::createFromFormat('!m', $month);
          $monthName = $dateObj->format('M');
        echo" {
                date: '".$monthName." ".$day." ".$year."',
                price: ".$count."
              },";
      }
  }
  ?>
}];

// Create axes
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;
dateAxis.renderer.minGridDistance = 50;
dateAxis.dateFormats.setKey("day", "MMM");
dateAxis.periodChangeDateFormats.setKey("month","[bold]yyyy[/]"); 

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.logarithmic = true;
valueAxis.renderer.minGridDistance = 20;

// Create series
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.valueY = "price";
series.dataFields.dateX = "date";
series.tensionX = 0.8;
series.strokeWidth = 3;

var bullet = series.bullets.push(new am4charts.CircleBullet());
bullet.circle.fill = am4core.color("#fff");
bullet.circle.strokeWidth = 3;

// Add cursor
chart.cursor = new am4charts.XYCursor();
chart.cursor.fullWidthLineX = true;
chart.cursor.xAxis = dateAxis;
chart.cursor.lineX.strokeWidth = 0;
chart.cursor.lineX.fill = am4core.color("#000");
chart.cursor.lineX.fillOpacity = 0.1;

// Add scrollbar
chart.scrollbarX = new am4core.Scrollbar();

// Add a guide
let range = valueAxis.axisRanges.create();
range.value = 90.4;
range.grid.stroke = am4core.color("#396478");
range.grid.strokeWidth = 1;
range.grid.strokeOpacity = 1;
range.grid.strokeDasharray = "3,3";
range.label.inside = true;
range.label.text = "Average";
range.label.fill = range.grid.stroke;
range.label.verticalCenter = "bottom";

}); // end am4core.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>