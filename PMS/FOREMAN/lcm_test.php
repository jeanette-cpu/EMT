<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<div>
  <canvas id="myChart"></canvas>
</div>
 
</body>
</html>
<?php 
  $green_arr[]=1;
  $green_arr[]=2;
  $green_arr[]=3;
  $green_imp='12, 19, 3';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {   //dates
      labels: ['November','December','Red'], 
      datasets: [
        {
        label: 'Standard', //green
        data: [<?php echo $green_imp;?>], 
        borderWidth: 1,
        backgroundColor: '#1cc88a'
        },
        {
        label: 'Almost',  //yellow
        data: [15, 20, 1], 
        borderWidth: 1,
        backgroundColor: '#f6c23e'
        },
        {
        label: 'Shortfall', //red
        data: [15, 20, 6],
        borderWidth: 1,
        backgroundColor: '#e74a3b'
        },
        {
        label: 'Manpower', //total mp
        data: [10, 7, 13],
        borderWidth: 1,
        backgroundColor: '#5a5c69'
        }
      ]
    },
    options: {
      plugins: {
        title: {
          display: true,
          // text: 'Chart.js Bar Chart - Stacked'
        },
      },
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
      
    }
  });
</script>



