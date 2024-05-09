<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    
  </div>
  <!-- Content Row -->
  <div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Projects</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php 
                $query = "SELECT Prj_Id FROM project WHERE Prj_Status=1";
                $query_run = mysqli_query($connection, $query);
                $row1= mysqli_num_rows($query_run);
                echo'<h2>'.$row1.'</h2>';
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-fw fa-building fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php

$query = "SELECT * FROM project WHERE Prj_Status =1";
$query_run = mysqli_query($connection, $query);
$ongoing=0; $complete=0;  $done_project=0;

if(mysqli_num_rows($query_run)>0)
{
  while($row = mysqli_fetch_assoc($query_run))
    {
      $category = $row['Prj_Category'];
      $prj_id = $row['Prj_Id'];
      if($category=='Building')
      {
      // get building assigned
      $q_building = "SELECT Blg_Id FROM Building where Blg_Status='1' AND Prj_Id='$prj_id'";
      $q_building_run = mysqli_query($connection, $q_building);
      $b_id_arr=null; $b_ids= null;
      if(mysqli_num_rows($q_building_run)>0)
      {
          while($row_b = mysqli_fetch_assoc($q_building_run))
          {
              $b_id_arr[] = $row_b['Blg_Id'];
          }
          $b_ids = implode("', '", $b_id_arr);
          // get levels
          $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
          // echo $q_levels;
          $q_level_run = mysqli_query($connection, $q_levels);
          $lvl_id_arr=null; $lvl_ids = null;
          if(mysqli_num_rows($q_level_run)>0)
          {
              while($row_l = mysqli_fetch_assoc($q_level_run))
              {
                  $lvl_id_arr[] = $row_l['Lvl_Id'];
              }
              $lvl_ids = implode("', '", $lvl_id_arr);
              // get flat id
              $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
              $q_flat_run = mysqli_query($connection, $q_flat);
              $flt_ids =null; $flat_id_arr=null;
              if(mysqli_num_rows($q_flat_run)>0)
              {
                  while($row_f = mysqli_fetch_assoc($q_flat_run))
                  {
                      $flat_id_arr[] = $row_f['Flat_Id'];
                  }
                  $flt_ids = implode("', '", $flat_id_arr);
                  // total count of activities
                  $q_count_act ="SELECT COUNT(Asgd_Act_Id) AS tot_act FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                  $q_count_act_run = mysqli_query($connection, $q_count_act);
                  $row_tot_act = mysqli_fetch_assoc($q_count_act_run);
                  $total = $row_tot_act['tot_act'];
                  // get assigned activities - ONGOING
                    // $q_act = "SELECT COUNT(Asgd_Act_Id) AS 'ongoing' FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed = '0000-00-00'";
                    // $q_act_run = mysqli_query($connection, $q_act);
                    // $row3 = mysqli_fetch_assoc($q_act_run);
                    // echo 'ongoing -'.$row3['ongoing'];
                  // get complete activities -  DONE
                  $q_act_complete ="SELECT COUNT(Asgd_Act_Id) AS done FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed <> '0000-00-00'";
                  $q_act_run1 = mysqli_query($connection, $q_act_complete);
                  $row4 = mysqli_fetch_assoc($q_act_run1);
                  $done = $row4['done'];
                  if($total == $done)
                  {
                    $done_project ++;
                  }
                  else
                  {
                    $ongoing++;
                  }
              }
              else{
                $ongoing++;}
          }
          else{
            $ongoing++;}
      }
      else{
        $ongoing++;}
  }
  elseif($category=='Villa'){
      // get villas assigned
      $q_villa = "SELECT Villa_Id FROM Villa where Villa_Status='1' AND Prj_Id='$prj_id'";
      $q_villa_run = mysqli_query($connection, $q_villa);
      if(mysqli_num_rows($q_villa_run)>0)
      {
          $villa_id_arr= null; $villa_ids= null;
          while($row_v = mysqli_fetch_assoc($q_villa_run))
          {
              $villa_id_arr[] = $row_v['Villa_Id'];
          }
          $villa_ids = implode("', '", $villa_id_arr);
          // get plex
          $q_plex = "SELECT Plx_Id from plex where Plx_Status='1' and Villa_Id in ('$villa_ids')";
          $q_plex_run = mysqli_query($connection, $q_plex);
          $plex_id_arr=null; $plex_ids=null;
          if(mysqli_num_rows($q_plex_run)>0)
          {
              while($row_p = mysqli_fetch_assoc($q_plex_run))
              {
                  $plex_id_arr[] = $row_p['Plx_Id'];
              }
              $plex_ids = implode("', '", $plex_id_arr);
              // get building
              $q_building = "SELECT Blg_Id FROM Building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
              $q_building_run = mysqli_query($connection, $q_building);
              $b_id_arr = null; $b_ids = null;
              if(mysqli_num_rows($q_building_run)>0)
              {
                  while($row_b = mysqli_fetch_assoc($q_building_run))
                  {
                      $b_id_arr[] = $row_b['Blg_Id'];
                  }
                  $b_ids = implode("', '", $b_id_arr);
                  // get levels
                  $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                  $q_level_run = mysqli_query($connection, $q_levels);
                  $lvl_id_arr=null;$lvl_ids=null;
                  if(mysqli_num_rows($q_level_run)>0)
                  {
                      while($row_l = mysqli_fetch_assoc($q_level_run))
                      {
                          $lvl_id_arr[] = $row_l['Lvl_Id'];
                      }
                      $lvl_ids = implode("', '", $lvl_id_arr);
                      // get flat id
                      $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                      $q_flat_run = mysqli_query($connection, $q_flat);
                      $flat_id_arr=null;$flt_ids=null;
                      if(mysqli_num_rows($q_flat_run)>0)
                      {
                          while($row_f = mysqli_fetch_assoc($q_flat_run))
                          {
                              $flat_id_arr[] = $row_f['Flat_Id'];
                          }
                          $flt_ids = implode("', '", $flat_id_arr);
                          // total count of activities
                          $q_count_act ="SELECT COUNT(Asgd_Act_Id) AS tot_act FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                          $q_count_act_run = mysqli_query($connection, $q_count_act);
                          $row_tot_act = mysqli_fetch_assoc($q_count_act_run);
                          $total = $row_tot_act['tot_act'];
                          // get complete activities -  DONE
                          $q_act_complete ="SELECT COUNT(Asgd_Act_Id) AS done FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed <> '0000-00-00'";
                          $q_act_run1 = mysqli_query($connection, $q_act_complete);
                          $row4 = mysqli_fetch_assoc($q_act_run1);
                          $done = $row4['done'];
                          if($total == $done)
                          {
                            $done_project ++;
                          }
                          else{
                            $ongoing++;}
                      }
                      else{
                        $ongoing++;}
                  }
                  else{
                    $ongoing++;}
              }
              else{
                $ongoing++;}
          }
          else{
            $ongoing++;}
      }
      else{
        $ongoing++;}
  }
}
}
?>
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ongoing</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                <h2> <?php  echo $ongoing; ?> </h2>
            </div>
            <div class="col-auto">
              <i class="fas fa-cubes fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4 ">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Done</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800"></div><h2>
                   <?php echo $done_project?>
                  </h2>
                </div>
              <div class="col-auto">
                <i class="fas fa-check fa-2x text-gray-300"></i>
              </div>
              </div>
          </div>
        </div>
      </div>
    </div>


<div class="col-xl-12 col-lg-12">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
    <h5 class="m-0 font-weight-bold text-primary">Projects</h5>
    </div>

  <div class="card-body">
  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <th class="d-none"></th>
        <th>Project Code</th>
        <th>Project Name</th>
        <th>Type</th>
        <th>Project Manager</th>
        <th>Status</th>
        <th>Progress</th>
        <th>Productivity</th>
      </thead>
      <tbody>
      <?php
          $query = "SELECT * FROM project WHERE Prj_Status =1";
          $query_run = mysqli_query($connection, $query);
          if(mysqli_num_rows($query_run)>0)
          {
              while($row = mysqli_fetch_assoc($query_run))
              {
                $category = $row['Prj_Category'];
                $prj_id = $row['Prj_Id'];
                if($category=='Building')
                {
                // get building assigned
                $q_building = "SELECT Blg_Id FROM Building where Blg_Status='1' AND Prj_Id='$prj_id'";
                $q_building_run = mysqli_query($connection, $q_building);
                $b_id_arr=null; $b_ids= null;
                if(mysqli_num_rows($q_building_run)>0)
                {
                    while($row_b = mysqli_fetch_assoc($q_building_run))
                    {
                        $b_id_arr[] = $row_b['Blg_Id'];
                    }
                    $b_ids = implode("', '", $b_id_arr);
                    // get levels
                    $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                    $q_level_run = mysqli_query($connection, $q_levels);
                    $lvl_id_arr=null; $lvl_ids = null;
                    if(mysqli_num_rows($q_level_run)>0)
                    {
                        while($row_l = mysqli_fetch_assoc($q_level_run))
                        {
                            $lvl_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $lvl_ids = implode("', '", $lvl_id_arr);
                        // get flat id
                        $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                        $q_flat_run = mysqli_query($connection, $q_flat);
                        $flt_ids =null; $flat_id_arr=null;
                        if(mysqli_num_rows($q_flat_run)>0)
                        {
                            while($row_f = mysqli_fetch_assoc($q_flat_run))
                            {
                                $flat_id_arr[] = $row_f['Flat_Id'];
                            }
                            $flt_ids = implode("', '", $flat_id_arr);
                            // get assigned activities
                            // total count of activities
                            $q_count_act ="SELECT COUNT(Asgd_Act_Id) AS tot_act FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                            $q_count_act_run = mysqli_query($connection, $q_count_act);
                            $row_tot_act = mysqli_fetch_assoc($q_count_act_run);
                            $total = $row_tot_act['tot_act'];
                            // get complete activities -  DONE
                            $q_act_complete ="SELECT COUNT(Asgd_Act_Id) AS done FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed <> '0000-00-00'";
                            $q_act_run1 = mysqli_query($connection, $q_act_complete);
                            $row4 = mysqli_fetch_assoc($q_act_run1);
                            $done = $row4['done'];
                            if($total == $done)
                            {
                              $status = 'Done';
                            }
                            else
                            {
                              $status = 'Ongoing';
                            }

                        }
                        else{
                          $status= 'Nothing assigned yet';}
                    }
                    else{
                      $status= 'Nothing assigned yet';}
                }
                else{
                  $status= 'Nothing assigned yet';}
            }
            elseif($category=='Villa'){
                // get villas assigned
                $q_villa = "SELECT Villa_Id FROM Villa where Villa_Status='1' AND Prj_Id='$prj_id'";
                $q_villa_run = mysqli_query($connection, $q_villa);
                $villa_id_arr= null; $villa_ids= null;
                if(mysqli_num_rows($q_villa_run)>0)
                {
                    while($row_v = mysqli_fetch_assoc($q_villa_run))
                    {
                        $villa_id_arr[] = $row_v['Villa_Id'];
                    }
                    $villa_ids = implode("', '", $villa_id_arr);
                    // get plex
                    $q_plex = "SELECT Plx_Id from plex where Plx_Status='1' and Villa_Id in ('$villa_ids')";
                    $q_plex_run = mysqli_query($connection, $q_plex);
                    if(mysqli_num_rows($q_plex_run)>0)
                    {
                        while($row_p = mysqli_fetch_assoc($q_plex_run))
                        {
                            $plex_id_arr[] = $row_p['Plx_Id'];
                        }
                        $plex_ids = implode("', '", $plex_id_arr);
                        // get building
                        $q_building = "SELECT Blg_Id FROM Building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
                        $q_building_run = mysqli_query($connection, $q_building);
                        if(mysqli_num_rows($q_building_run)>0)
                        {
                            while($row_b = mysqli_fetch_assoc($q_building_run))
                            {
                                $b_id_arr[] = $row_b['Blg_Id'];
                            }
                            $b_ids = implode("', '", $b_id_arr);
                            // get levels
                            $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                            $q_level_run = mysqli_query($connection, $q_levels);
                            if(mysqli_num_rows($q_level_run)>0)
                            {
                                while($row_l = mysqli_fetch_assoc($q_level_run))
                                {
                                    $lvl_id_arr[] = $row_l['Lvl_Id'];
                                }
                                $lvl_ids = implode("', '", $lvl_id_arr);
                                // get flat id
                                $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                                $q_flat_run = mysqli_query($connection, $q_flat);
                                if(mysqli_num_rows($q_flat_run)>0)
                                {
                                    while($row_f = mysqli_fetch_assoc($q_flat_run))
                                    {
                                        $flat_id_arr[] = $row_f['Flat_Id'];
                                    }
                                    $flt_ids = implode("', '", $flat_id_arr);
                                    // get assigned activities
                                    $q_act = "SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed == '0000-00-00'";
                                    $q_act_run = mysqli_query($connection, $q_flat);
                                    if(mysqli_num_rows($q_flat_run)>0)
                                    {
                                      $status="Ongoing";
                                    }
                                    else{
                                      $status= 'Nothing assigned yet';}
                                }
                                else{
                                  $status= 'Nothing assigned yet';}
                            }
                            else{
                              $status= 'Nothing assigned yet';}
                        }
                        else{
                          $status= 'Nothing assigned yet';}
                    }
                    else{
                      $status= 'Nothing assigned yet';}
                }
                else{
                  $status= 'Nothing assigned yet';}
            }
       
      ?>
        
        <tr>
          <td class="d-none"><?php echo $row['Prj_Id']; ?></td>
          <td><?php echo $row['Prj_Code']; ?></td>
          <td><?php echo $row['Prj_Name']; ?></td>
          <td><?php echo $row['Prj_Category']; ?></td>
<?php
          // project manager
          $q_pm ="SELECT * FROM `asgn_emp_to_prj` LEFT JOIN employee on employee.EMP_ID = asgn_emp_to_prj.Emp_Id LEFT JOIN users on users.USER_ID = asgn_emp_to_prj.User_Id WHERE asgn_emp_to_prj.Asgd_Emp_to_Prj_Status = 1 AND employee.EMP_STATUS = 1 AND users.USER_STATUS= 1 AND users.USERTYPE='proj_mgr' and asgn_emp_to_prj.Prj_Id='$prj_id'";
          // echo $q_pm;
          $q_pm_run=mysqli_query($connection, $q_pm);
          $name=null; $names=null;
          if(mysqli_num_rows($q_pm_run)>0){
            while($row_pm = mysqli_fetch_assoc($q_pm_run)){
                $name[] = $row_pm['EMP_FNAME'].' '.$row_pm['EMP_MNAME'].' '.$row_pm['EMP_LNAME'].' '.$row_pm['EMP_SNAME'];
            }
          $names = implode(", ", $name);
          }
?>

          <td><?php echo $names?></td>
          <td><?php echo $status?></td>
          <td class="btn btn-group">
            <form action="r_chart.php" method="POST">
              <!-- project chart -->
              <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id'];?>">
              <button type="submit" name ="reportBtn" class="btn btn-primary">
                <i class="fa fa-line-chart" aria-hidden="true"></i>  Progress
              </button>
            </form>
            <!-- inventory -->
            <!-- <button type="button" class="btn btn-success">
              <i class="fa fa-cube" aria-hidden="true"></i>
            </button> -->
          </td>
          <td>
              <?php 
                if($row['Prj_Category']=='Villa'){
                  ?>
                    <!-- report 2 -->
                    <form action="r_report2.php" method="POST">
                      <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id'];?>">
                      <button type="submit" name="reportBtn" class="btn btn-success">
                        Productivity <i class="fa fa-line-chart" aria-hidden="true"></i>
                      </button>
                    </form>
                  <?php
                }
                else{

                }
              ?>
          </td>
        </tr>
        <?php
            }
        }
        else
        {
            echo "No Record Found";
        }
    ?> 
      </tbody>
    </table>
  </div>
  </div>
</div>

</div>
<!-- <style>
  #chartdiv {
    width	: 100%;
    height: 500px;
}																	
</style> -->

<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

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
                    $sql2 = "SELECT CAST((P_DATE) AS DATE), COUNT(*) AS count FROM payslip LEFT JOIN employee on payslip.EMP_ID = employee.EMP_ID WHERE P_STATUS=1 AND EMP_STATUS=1 GROUP BY MONTH(P_DATE)";
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
// </div>

</script>
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>