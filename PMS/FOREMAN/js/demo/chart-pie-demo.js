// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example  ELECTRICAL
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Actual Manpower", "Accumulated Budgeted Manpower"],
    datasets: [{
      data: electDataset,
      backgroundColor: ['#f6c23e', '#f6853e'],
      hoverBackgroundColor: ['#f4b30d', '#f57626'],
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

// Pie Chart Example  PLUMBING
var piePlumb = document.getElementById("myPiePlumb");
var myPiePlumb = new Chart(piePlumb, {
  type: 'doughnut',
  data: {
    labels: ["Actual Manpower", "Accumulated Budgeted Manpower"],
    datasets: [{
      data: plumbDataset,
      backgroundColor: ['#4e73df', '#4ebcdf'],
      hoverBackgroundColor: ['#2e59d9', '#38b4db'],
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

// Pie Chart Example  PLUMBING
var pieHvac = document.getElementById("myPieHVAC");
var myPieHVAC = new Chart(pieHvac, {
  type: 'doughnut',
  data: {
    labels: ["Actual Manpower", "Accumulated Budgeted Manpower"],
    datasets: [{
      data: hvacDataset,
      backgroundColor: ['#159466', '#1cc88a'],
      hoverBackgroundColor: ['#12835b', '#17a673'],
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