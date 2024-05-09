$($document).ready(function(){
    $.ajax({
      url:"http://localhost/emt/data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var month =[];
        var count =[];
  
        for(var i in data){
          month.push(data[i].month);
          count.push(data[i].count);
        }
  
        var chartdata = {
          labels: month,
          datasets: [
            {
              label: 'Month',
              backgroundColor: 'rgba(200,200,200,0.75)',
              borderColor: 'rgba(200,200,200,0.75)',
              hoverBackgroundColor: 'rgba(200,200,200,1)',
              hoverBorderColor: 'rgba(200,200,200,1)',
              data: count
            }
          ]
        };
  
        var ctx = $("#myChart");
  
        var barGraph = new Chart(ctx, {
          type: 'bar',
          data: chartdata
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  });