var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: catLabel,
    datasets: [{
      label: 'Actual',
      data: actualData,
      backgroundColor: "rgb(78,115,223)"
    }, {
      label: 'Expected',
      data: expData,
      backgroundColor: "rgb(28,200,138)"
    }]
  }
});
var ctx1 = document.getElementById("expReport").getContext('2d');
var expReport = new Chart(ctx1, {
  type: 'bar',
  data: {
    labels: catLabel1,
    datasets: [{
      label: 'Actual',
      data: actualData1,
      backgroundColor: "rgb(78,115,223)"
    }, {
      label: 'Expected',
      data: expData1,
      backgroundColor: "rgb(28,200,138)"
    }]
  }
});
{/* <script> */}
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example  ELECTRICAL
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: earning_label,
    datasets: [{
      data: newDataset_earn,
      backgroundColor: colors,
    //   hoverBackgroundColor: ['#f4b30d', '#f57626'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 75,
  },
});
var idk = document.getElementById("expenseChart");
var expenseChart = new Chart(idk, {
  type: 'doughnut',
  data: {
    labels: newLabel_cat,
    datasets: [{
      data: newDataset_cat,
      backgroundColor: colors,
    //   hoverBackgroundColor: ['#f4b30d', '#f57626'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 75,
  },
});
// </script>