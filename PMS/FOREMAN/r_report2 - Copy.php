<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
// error_reporting(0);
date_default_timezone_set('Asia/Dubai');
if(isset($_POST['reportBtn']))
{
    $prj_id=$_POST['prj_id'];
    $q_prj = "SELECT * FROM project WHERE Prj_Id='$prj_id' ";
    $q_prj_run = mysqli_query($connection,$q_prj);
    $row = mysqli_fetch_assoc($q_prj_run);
    $q_bcount=0;
    $category = $row['Prj_Category'];
    if($category=='Building')
    {
        echo'<script>
        $(document).ready(function() {
            $("#villa").addClass("d-none");
            $("#villa_card").addClass("d-none");
            $("#villa1").addClass("d-none");
            $("#villa2").addClass("d-none");
            $("#a_villa").addClass("d-none");
        });
        </script>';
        // get building assigned
        $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
        $q_building_run = mysqli_query($connection, $q_building);
        $q_bcount = mysqli_num_rows($q_building_run);
        if(mysqli_num_rows($q_building_run)>0)
        {
            while($row_b = mysqli_fetch_assoc($q_building_run))
            {
                $b_id_arr[] = $row_b['Blg_Id'];
            }
            $b_ids = implode("', '", $b_id_arr); $q_lcount=0;
            // get levels 
            $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
            $q_level_run = mysqli_query($connection, $q_levels);
            $q_lcount = mysqli_num_rows($q_level_run);
          if(mysqli_num_rows($q_level_run)>0)
          {
              while($row_l = mysqli_fetch_assoc($q_level_run))
              {
                  $lvl_id_arr[] = $row_l['Lvl_Id'];
              }
              $lvl_ids = implode("', '", $lvl_id_arr); $q_fcount=0;
              // get flat id
              $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
              $q_flat_run = mysqli_query($connection, $q_flat);
              $q_fcount = mysqli_num_rows($q_flat_run);
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
                    $q_act = "SELECT COUNT(Asgd_Act_Id) AS 'ongoing' FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed = '0000-00-00' AND Asgd_Pct_Done<100 and Asgd_Pct_Done != 0";
                    $q_act_run = mysqli_query($connection, $q_act);
                    $row3 = mysqli_fetch_assoc($q_act_run);
                    $ongoing = $row3['ongoing'];
                // TO DO
                    $q_act_todo = "SELECT COUNT(Asgd_Act_Id) AS 'todo' FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed = '0000-00-00' AND Asgd_Pct_Done = 0";
                    $q_act_todo_run = mysqli_query($connection, $q_act_todo);
                    $row5 = mysqli_fetch_assoc($q_act_todo_run);
                    $todo = $row5['todo'];
                  // get complete activities -  DONE
                  $q_act_complete ="SELECT COUNT(Asgd_Act_Id) AS done FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed <> '0000-00-00'";
                  $q_act_run1 = mysqli_query($connection, $q_act_complete);
                  $row4 = mysqli_fetch_assoc($q_act_run1);
                  $done = $row4['done'];
                }
            }
        }
    }
    elseif($category=='Villa')
    {
        echo'<script>
        $(document).ready(function() {
            $("#building").addClass("d-none");
            $("#blg_card").addClass("d-none");
            $("#a_building").addClass("d-none");
        });
        </script>';
        // get villas assigned
      $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
      $q_villa_run = mysqli_query($connection, $q_villa);
      $q_vcount = mysqli_num_rows($q_villa_run);
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
          $q_pcount = mysqli_num_rows($q_plex_run);
          $plex_id_arr=null; $plex_ids=null;
          if(mysqli_num_rows($q_plex_run)>0)
          {
              while($row_p = mysqli_fetch_assoc($q_plex_run))
              {
                  $plex_id_arr[] = $row_p['Plx_Id'];
              }
              $plex_ids = implode("', '", $plex_id_arr);
              // get building
              $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
              $q_building_run = mysqli_query($connection, $q_building);
              $q_bcount = mysqli_num_rows($q_building_run);
                if(mysqli_num_rows($q_building_run)>0)
                {
                    while($row_b = mysqli_fetch_assoc($q_building_run))
                    {
                        $b_id_arr[] = $row_b['Blg_Id'];
                    }
                    $b_ids = implode("', '", $b_id_arr); $q_lcount=0;
                    // get levels 
                    $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                    $q_level_run = mysqli_query($connection, $q_levels);
                    $q_lcount = mysqli_num_rows($q_level_run);
                    if(mysqli_num_rows($q_level_run)>0)
                    {
                        while($row_l = mysqli_fetch_assoc($q_level_run))
                        {
                            $lvl_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $lvl_ids = implode("', '", $lvl_id_arr); $q_fcount=0;
                        // get flat id
                        $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                        $q_flat_run = mysqli_query($connection, $q_flat);
                        $q_fcount = mysqli_num_rows($q_flat_run);
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
                            $q_act = "SELECT COUNT(Asgd_Act_Id) AS 'ongoing' FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed = '0000-00-00' AND Asgd_Pct_Done<100 and Asgd_Pct_Done != 0";
                            $q_act_run = mysqli_query($connection, $q_act);
                            $row3 = mysqli_fetch_assoc($q_act_run);
                            $ongoing = $row3['ongoing'];
                            // TO DO
                            $q_act_todo = "SELECT COUNT(Asgd_Act_Id) AS 'todo' FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed = '0000-00-00' AND Asgd_Pct_Done = 0";
                            $q_act_todo_run = mysqli_query($connection, $q_act_todo);
                            $row5 = mysqli_fetch_assoc($q_act_todo_run);
                            $todo = $row5['todo'];
                            // get complete activities -  DONE
                            $q_act_complete ="SELECT COUNT(Asgd_Act_Id) AS done FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids') and Asgd_Act_Date_Completed <> '0000-00-00'";
                            $q_act_run1 = mysqli_query($connection, $q_act_complete);
                            $row4 = mysqli_fetch_assoc($q_act_run1);
                            $done = $row4['done'];
                        }
                    }
                }
            }
        }
    }
}
?>
<script src="table2excel.js"> </script>
<input type="hidden" id="prj_id" value="<?php echo $prj_id?>">
<input type="hidden" id="prj_category" value="<?php echo $category?>">
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-1">
            <i class="fas fa-fw fa-building fa-3x text-danger-500" style="color:#bc0203"></i>
        </div>
        <div class="col-6" style="color:#bc0203">
            <h3 class="float-left"><?php echo $row['Prj_Code'].' - '.$row['Prj_Name']?></h3>
        </div>
        <div class="col-2">
            <div class="float-right">
                <p class="mb-0">Date Started:</p>
                <p>Due Date: </p>
            </div>
        </div>
        <div class="col-2 ">
            <div class="float-left">
                <p class="mb-0"><?php echo $row['Prj_Start_Date']?></p>
                <p><?php echo $row['Prj_End_Date']?></p>
            </div>
        </div>
    </div>
    <!-- Content Row 1st row-->
    <!-- BUILDING TYPE -->
    <div class="row" id="blg_card"> 
        <!-- No. of Buildings-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card  shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Buildings</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo'<h2>'.$q_bcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-building fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- Levels -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Levels</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo'<h2>'.$q_lcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-bars fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card  shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Flats</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$q_fcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-cube fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Days before deadline -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Days Before Deadline</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  
                            date_default_timezone_set('Asia/Dubai');
                            $now = time();
                            $due_date = strtotime($row['Prj_End_Date']);
                            $days = $due_date-$now;
                            if($days<0)
                            {
                                echo '<h4 class="mt-2">Date Ended</h4>';
                            }
                            else{
                                $days = round($days/(60*60*24));
                                if($days > 30) {
                                    $month = $days/30;
                                    $month= floor($month);
                                    $days = $days % 30;
                                    echo '<h3>'.$month.' month(s) and '.$days.' days</h3>';
                                }
                                else{
                                    echo'<h2>'.$days.'</h2>';
                                }
                            }
                             ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-calendar fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- VILLA TYPE -->
    <div class="row" id="villa_card"> 
        <!-- No. of Buildings-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card  shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total No. of Area</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo'<h2>'.$q_vcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-thumb-tack fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <!-- Levels -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Plexes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo'<h2>'.$q_pcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-bars fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card  shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Villa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$q_bcount.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-cube fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Days before deadline -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Days Before Deadline</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  
                            date_default_timezone_set('Asia/Dubai');
                            $now = time();
                            $due_date = strtotime($row['Prj_End_Date']);
                            $days = $due_date-$now;
                            if($days<0)
                            {
                                echo '<h4 class="mt-2">Date Ended</h4>';
                            }
                            else{
                                $days = round($days/(60*60*24));
                                if($days > 30) {
                                    $month = $days/30;
                                    $month= floor($month);
                                    $days = $days % 30;
                                    echo '<h3>'.$month.' month(s) and '.$days.' days</h3>';
                                }
                                else{
                                    echo'<h2>'.$days.'</h2>';
                                }
                            }
                             ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-calendar fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Row 2nd row -->
    <div class="row ">
        <!-- total no task-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total No. of Tasks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php   $Date=NULL; $to =NULL; $from=null; 
                                function lcm($output,$output_std){
                                    if ($output > $output_std) {
                                        $temp = $output;
                                        $output = $output_std;
                                        $output_std = $temp;
                                      }
                                      
                                      for($i = 1; $i < ($output+1); $i++) {
                                        if ($output%$i == 0 && $output_std%$i == 0)
                                          $gcd = $i;
                                      }
                                      
                                      $lcm = ($output*$output_std)/$gcd;
                                      return $lcm;
                                }
                                $q_chart_month="SELECT * FROM daily_entry2 as de WHERE de.DE_Status2=3";
                                $q_chart_month_run=mysqli_query($connection,$q_chart_month);
                                // $q_chart_day_run=mysqli_query($connection,$q_chart_month);
                                if(isset($_POST['from']) && isset($_POST['to'])){
                                    $from = $_POST['from'];
                                    $to = $_POST['to'];
                                    $q_cnt="SELECT * FROM daily_entry2 as de
                                            LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                            WHERE de.Prj_Id='$prj_id' AND de.DE_Date2 BETWEEN '".$from."' AND '".$to."' AND de.DE_Status2=1";
                                    $q_chart_month="SELECT * FROM daily_entry2 as de
                                                    LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                                    WHERE de.Prj_Id='$prj_id' AND de.DE_Date2 BETWEEN '".$from."' AND '".$to."' AND de.DE_Status2=1
                                                    GROUP by MONTH(de.DE_Date2)";
                                    $q_chart_month_run=mysqli_query($connection,$q_chart_month);
                                    $q_chart_day="SELECT * FROM daily_entry2 as de
                                                    LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                                    WHERE de.Prj_Id='$prj_id' AND de.DE_Date2 BETWEEN '".$from."' AND '".$to."' AND de.DE_Status2=1
                                                    GROUP by day(de.DE_Date2)";
                                    // $q_chart_day_run=mysqli_query($connection,$q_chart_day);
                                    $date_arrowl=$from; $date_arrow=NULL;
                                    $date_arrowr=$to;
                                }
                                elseif(isset($_POST['from'])){
                                    $from = $_POST['from'];
                                    $q_cnt="SELECT * FROM daily_entry2 as de
                                    LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                    WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$from' AND de.DE_Status2=1";
                                    $q_chart_day="SELECT * FROM daily_entry2 as de
                                                LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                                WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$from' AND de.DE_Status2=1
                                                GROUP by day(de.DE_Date2)";
                                    $date_arrow=$from;
                                }
                                elseif (isset($_POST['to'])) {
                                    $to = $_POST['to'];
                                    $q_cnt="SELECT * FROM daily_entry2 as de
                                            LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                            WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$to' AND de.DE_Status2=1";
                                    $q_chart_day="SELECT * FROM daily_entry2 as de
                                            LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                            WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$to' AND de.DE_Status2=1
                                            GROUP by day(de.DE_Date2)";
                                    $date_arrow=$to;
                                }
                                else{
                                    date_default_timezone_set('Asia/Dubai');
                                    $Date = date('Y-m-d');
                                    $q_cnt="SELECT * FROM daily_entry2 as de
                                    LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                    WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$Date' AND de.DE_Status2=1";
                                    $date_arrow=$Date;
                                    $q_chart_day="SELECT * FROM daily_entry2 as de
                                                LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                                WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$Date' AND de.DE_Status2=1
                                                GROUP by day(de.DE_Date2)";
                                }
                                // echo 'from-'.$from.'<br>to='.$to;
                                $q_chart_day_run=mysqli_query($connection,$q_chart_day);
                                $green=null; $yellow=null; $blue=null;
                                $q_cnt_run=mysqli_query($connection,$q_cnt);
                                if(mysqli_num_rows($q_cnt_run)>0){
                                    $act_cnt=mysqli_num_rows($q_cnt_run);
                                    while($row=mysqli_fetch_assoc($q_cnt_run)){
                                       //Retrieve Standard
                                       $act_id=$row['Act_Id'];
                                       $emp_no=$row['DE_Emp_No'];
                                        $sb_no=$row['DE_SB_No'];
                                        $mp_no=$row['DE_MP_No'];
                                        $total_emp=$emp_no+$sb_no+$mp_no; //emp + subcon + mp
                                        $output=$row['DE_Output_No'];//output
                                        $q_standard="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Id='$act_id' AND Act_Standard_Status=1";
                                        $q_standard_run=mysqli_query($connection,$q_standard);
                                        if(mysqli_num_rows($q_standard_run)>0){ 
                                            $row_act_s=mysqli_fetch_assoc($q_standard_run);
                                            $mp_std=floor($row_act_s['Act_Standard_Emp_Ratio']);
                                            $output_std=floor($row_act_s['Act_Standard_Output_Ratio']);
                                            if($mp_std==NULL || $mp_std==0){
                                                $eval_text='not set';
                                            }
                                            elseif($output_std==NULL || $output_std==0){
                                                $eval_text='not set';
                                            }
                                            else{
                                                $lcm = lcm($output,$output_std);
                                                $eval_text='';
                                                $standard=($output_std/$lcm)*$mp_std;
                                                $output_comp=($output/$lcm)*$total_emp;
                                                $eval=$output_comp-$standard;
                                                //blue (more than standard)
                                                if($standard<$output_comp){ 
                                                    $blue++;
                                                }
                                                //green (standard)
                                                if($standard==$output){ 
                                                    $green++;
                                                }
                                                //yellow (below standard)
                                                elseif($standard>$output){ 
                                                    $yellow++;
                                                }

                                            }
                                        }
                                        else{$eval_text='not set';
                                        }
                                    }
                                }
                                else{
                                    $act_cnt=0;
                                }
                                echo'<h2>'.$act_cnt.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-list fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Standard Pass -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Above Standard</div>
                        <div class="h5 mb-0 font-weight-bold text-info-800">
                            <?php 
                            echo'<h2>'.$blue.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-check-square fa-2x text-info-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ongoing -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Standard</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            echo'<h2>'.$green.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <!-- <i class="fas fa-fw fa-pause fa-2x text-black-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- To Do -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Below Standard</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            echo'<h2>'.$yellow.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <!-- <i class="fas fa-fw fa-ellipsis-h fa-2x text-black-300"></i> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-lg-12">
        <div class="row d-none">
            <div id="villa" class="villa col-3">
                <div class="">
                    <label for="">Area</label>
                    <select name="building" id="villa_opt" class="form-control">
                    </select>
                </div>
            </div>
            <div id="villa1" class="villa col-3">
                <div class="">
                    <label for="">Plex</label>
                    <select name="building" id="plex_opt" class="form-control">
                    </select>
                </div>
            </div>
            <div id="building" class=" col-3">
                <div class="">
                    <label for=""> Building</label>
                    <select name="building" id="blg_opt" class="form-control tbl"></select>
                </div>
            </div>
            <div class="col-3">
                <label for=""> Department</label>
                    <select name="building" id="dept_opt" class="form-control tbl"></select>
            </div>
            <div class="col-3">
                <label for=""> Category</label>
                <select name="building" id="cat_opt" class="form-control tbl"></select>
            </div>
        </div>
        <hr>
        <div class="form-row">
            <div class="col-2"  align="left">
                <form name="frmSearch" action="r_report2.php" method="POST">
                    <!-- <button class="btn btn-circle btn-lg" type="button" id="btnleft">
                        <i class="fa fa-arrow-left fa-3 text-secondary" aria-hidden="true"></i>
                    </button> -->
                    <?php 
                        if($date_arrow){ // plus 1
                            $left=$date_arrow;
                        }
                        elseif($date_arrowl){
                            $left=$date_arrowl;
                        }
                        else{
                            $left=$Date;
                        }
                        $left=date('Y-m-d',strtotime($left.' -1 day'));
                    ?>

                    <input type="date" name="from" id="post_at" value="<?php echo $left;?>" class="form-control d-none">
                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">

                    <button class="btn btn-circle btn-lg" type="submit" name="reportBtn" id="" title="previous">
                        <i class="fa fa-arrow-left fa-3 text-secondary" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
            <div class="col-8">
                <form name="frmSearch" action="r_report2.php" method="POST">
                  <p class="search_input">
                    <div class="form-row">
                      <div class="form-group col-md-4 mt-n3">
                        <label>From</label>
                          <input type="date" placeholder="From Date" name="from" id="post_at" class="form-control">
                      </div>
                      <div class="form-group col-md-4 mt-n3">
                        <label>To</label>
                          <input type="date" placeholder="To Date" name="to" id="post_at_to_date" class="form-control">
                      </div>
                      <div class="form-group col-md-4 pt-3">
                        <label> </label>
                        <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                        <input class="btn btn-warning" type="submit" name="reportBtn" value="Filter">
                      </div>
                    </div>
                  </p>
                </form>
            </div>
            <div class="col-2"  align="right">
                <form name="frmSearch" action="r_report2.php" method="POST">
                    <?php 
                        if($date_arrow){ // plus 1
                            $right=$date_arrow;
                        }
                        elseif($date_arrowr){
                            $right=$date_arrowr;
                        }
                        else{
                            $right=$Date;
                        }
                        $right=date('Y-m-d',strtotime($right.' +1 day'));
                    ?>
                    <input type="date" placeholder="From Date" name="from" id="post_at" value="<?php echo $right;?>" class="form-control d-none">
                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">

                    <button class="btn btn-circle btn-lg" type="submit" name="reportBtn" id="btnright" title="next day">
                        <i class="fa fa-arrow-right fa-3 text-secondary" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="d-none"><input type="date" id="current_date" class="form-control d-none"></div>
        
        <div class="form-row">
            <div class="col-8">
                <h5 class="m-0 font-weight-bold text-primary">Actitivty Lists</h5> 
            </div>
            <div class="col-4">
                <span id="dateSpan"> 
                <?php 
                    if($Date){
                        echo date("F j, Y",strtotime($Date));
                    }
                    elseif($from!=NULL && $to!=NULL){
                        $from_text= date("F j, Y",strtotime($from));
                        $to_text= date("F j, Y",strtotime($to));
                        echo $from_text.' to '.$to_text;
                    }
                    elseif($from){
                        echo date("F j, Y",strtotime($from));
                    }
                    elseif($to){
                        echo date("F j, Y",strtotime($to));
                    }
                    else{
                        echo date("F j, Y");
                    }
                ?> </span>
            </div>
        </div>
        <!-- ACTIVITY LIST TBL -->
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none">Id</th>
                        <th class="d-none">Id</th>
                        <th class="d-none">Id</th>
                        <th class="align-middle" width="25%">Activity </th>
                        <th class="align-middle">Area</th> <!-- Area Code-->
                        <th class="align-middle">Emp</th>
                        <th class="align-middle" >MP</th>
                        <th class="align-middle" >SB </th>
                        <th>Tot Worker</th>
                        <th>Items Fixed</th>
                        <th>Type</th>
                        <th class="d-none"></th>
                        <th>Evaluation</th>
                    </thead>
                    <tbody>
                    <?php
                    //query all inserted activity today
                    if(isset($_POST['from']) && isset($_POST['to'])){
                        $from = $_POST['from'];
                        $to = $_POST['to'];
                        $q_act="SELECT * FROM daily_entry2 as de
                        LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                        WHERE de.Prj_Id='$prj_id' AND de.DE_Date2 BETWEEN '".$from."' AND '".$to."' AND de.DE_Status2=1";
                    }
                    elseif(isset($_POST['from'])){
                        $from = $_POST['from'];
                        $q_act="SELECT * FROM daily_entry2 as de
                                LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$from' AND de.DE_Status2=1";
                    }
                    elseif (isset($_POST['to'])) {
                        $to = $_POST['to'];
                        $q_act="SELECT * FROM daily_entry2 as de
                        LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                        WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$to' AND de.DE_Status2=1";
                    }
                    else{
                        $q_act="SELECT * FROM daily_entry2 as de
                        LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                        WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$Date' AND de.DE_Status2=1";
                    }
                    $q_act_run=mysqli_query($connection,$q_act);
                    if(mysqli_num_rows($q_act_run)>0){ $emp_tot=0; $sb_tot=0; $mp_tot=0; $all_tot=0;
                        while($row=mysqli_fetch_assoc($q_act_run)){
                            $DE_Id=$row['DE_Id2'];
                            $act_id=$row['Act_Id'];
                            $act_code=$row['Act_Code'];
                            $act_name=$row['Act_Name'];
                            $act_fname=$act_code.' '.$act_name;
                            $emp_no=$row['DE_Emp_No'];
                            $sb_no=$row['DE_SB_No'];
                            $mp_no=$row['DE_MP_No'];
                            $total_emp=$emp_no+$sb_no+$mp_no; //emp + subcon + mp
                            $output=$row['DE_Output_No'];//output
                            $day_type=$row['DE_Day_Type']; //FD=FULL DAY, HD=HALF DAY, QD=QUARTER DAY
                            if($day_type=='FD'){
                                $day_type1='Full Day';
                            }
                            elseif($day_type=='HD'){
                                $day_type1='Half Day';
                            }
                            elseif($day_type=='QD'){
                                $day_type1='Quarter Day';
                            }
                            else{ $day_type1=''; }
                            $area_id=$row['Area_Id'];

                            //totals
                            $emp_tot=$emp_tot+$emp_no;
                            $mp_tot=$mp_tot+$mp_no;
                            $sb_tot=$sb_tot+$sb_no;
                            if($emp_no==0 OR $emp_no==NULL){ $emp_no='';}
                            if($sb_no==0 OR $sb_no==NULL){ $sb_no='';}
                            if($mp_no==0 OR $mp_no==NULL){ $mp_no='';}

                            if($category=='Villa'){ // search for plex name (Villa_Id)
                                $q_area="SELECT * FROM plex WHERE Plx_Id='$area_id'";
                                $q_area_run=mysqli_query($connection,$q_area);
                                if($q_area_run){
                                    $row_a=mysqli_fetch_assoc($q_area_run);
                                    $area=$row_a['Plx_Code'].' '.$row_a['Plx_Name'];
                                }
                            }
                            else{ //building id
                                $q_area2="SELECT * FROM level WHERE Lvl_Id='$area_id'";
                                $q_area_run2=mysqli_query($connection,$q_area2);
                                if($q_area_run2){
                                    $row_a2=mysqli_fetch_assoc($q_area_run2);
                                    $area=$row_a2['Lvl_Code'];
                                }
                            }
                            //Retrieve Standard
                            $q_standard="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Id='$act_id' AND Act_Standard_Status=1";
                            $q_standard_run=mysqli_query($connection,$q_standard);
                            $eval_text=''; $btn_class='';
                            if(mysqli_num_rows($q_standard_run)>0){ 
                                $row_act_s=mysqli_fetch_assoc($q_standard_run);
                                $mp_std=floor($row_act_s['Act_Standard_Emp_Ratio']);
                                $output_std=floor($row_act_s['Act_Standard_Output_Ratio']);
                                if($mp_std==NULL || $mp_std==0){
                                    $eval_text='not set';
                                }
                                elseif($output_std==NULL || $output_std==0){
                                    $eval_text='not set';
                                }
                                else{
                                    $lcm = lcm($output,$output_std);
                                    $eval_text='';
                                    $standard=($output_std/$lcm)*$mp_std;
                                    $output_comp=($output/$lcm)*$total_emp;
                                    $eval=$output_comp-$standard;
                                    //blue (more than standard) 
                                    if($standard<$output_comp){ 
                                        $btn_class='btn-info';
                                        $title_hover="exceed by ".$eval.' items';
                                    }
                                    //green (standard)
                                    elseif($standard==$output){
                                        $btn_class='btn-success';
                                        $title_hover="standard met";
                                    }
                                    //yellow (below standard)
                                    elseif($standard>$output){
                                        $btn_class='btn-warning';
                                        $title_hover="short by ".$eval.' items';
                                    }
                                }
                            }
                            else{$eval_text='not set';
                            }
                            
                            $all_tot=$all_tot+$total_emp;
                            ?>
                            <tr>
                            <td class="d-none"><?php echo $DE_Id;?></td>
                            <td class="d-none"><?php echo $act_id;?></td>
                            <td class="d-none"><?php echo $area_id;?></td>

                                <td><?php echo $act_fname;?></td><!-- Activity -->
                                <td><?php echo $area;?></td><!-- Area Code-->
                                <td><?php echo $emp_no;?></td><!-- EMP-->
                                <td><?php echo $mp_no;?></td><!-- MP-->
                                <td><?php echo $sb_no;?></td><!-- SB-->
                                <td><?php echo $total_emp;?></td><!-- TOT W-->
                                <td><?php echo $output;?></td><!-- OUTPUT-->
                                <td><?php echo $day_type1;?></td><!-- TYPE-->
                                <td class="d-none"><?php echo $day_type;?></td><!-- TYPE-->
                                <td class="text-center">
                                    <?php echo $eval_text;?>
                                    <a href="#" class="btn <?PHP echo $btn_class;?> btn-circle btn-sm" title="<?php echo $title_hover;?>"></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr class="font-weight-bold">
                            <td></td>
                            <td></td>
                            <td><?php echo $emp_tot;?></td>
                            <td><?php echo $mp_tot;?></td>
                            <td><?php echo $sb_tot;?></td>
                            <td class="text-primary"><?php echo $all_tot;?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    else{
                        echo "Data not found";
                    }
                        ?>
                    </tbody>
                </table>
                <div id="act_tbl">
                </div>
            </div>
            <div align="right">
                <button name="" id="btn_act" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
            <?php 
                $date_m_arr[]=NULL;   $date_d_arr[]=NULL; 
                if(mysqli_num_rows($q_chart_month_run)>1){ // chart/month
                    while($row_m=mysqli_fetch_assoc($q_chart_month_run)){
                        $date_m_arr[]=$row_m['DE_Date2'];
                    }
                }
                if(mysqli_num_rows($q_chart_day_run)>0){ // chart/day
                    while($row_d=mysqli_fetch_assoc($q_chart_day_run)){
                        $date_d_arr[]=$row_d['DE_Date2'];
                    }
                }
                $green=0; $yellow=0; $blue=0;
                // $chart_arr[]=[];
                ////// CHART 
                if($date_m_arr){
                    foreach($date_m_arr as $key => $date){
                        $month= date("m", strtotime($date));
                        $month_text = date("M", strtotime($date));
                        $year= date("Y", strtotime($date));
                        $q_chart="SELECT * FROM daily_entry2 as de
                                LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                WHERE de.Prj_Id='$prj_id' AND MONTH(de.DE_Date2)='$month' AND YEAR(de.DE_Date2)='$year' AND de.DE_Status2=1";
                        $q_chart_run=mysqli_query($connection,$q_chart);
                        if(mysqli_num_rows($q_chart_run)>0){ $total_emp_ar=0;
                            while($row_c=mysqli_fetch_assoc($q_chart_run)){
                                //Retrieve Standard
                                $act_id=$row_c['Act_Id'];
                                $emp_no=$row_c['DE_Emp_No'];
                                $sb_no=$row_c['DE_SB_No'];
                                $mp_no=$row_c['DE_MP_No'];
                                $total_emp=$emp_no+$sb_no+$mp_no; //emp + subcon + mp
                                $total_emp_ar=$total_emp_ar+$total_emp;
                                $output=$row_c['DE_Output_No'];//output
                                $q_standard="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Id='$act_id' AND Act_Standard_Status=1";
                                $q_standard_run=mysqli_query($connection,$q_standard);
                                if(mysqli_num_rows($q_standard_run)>0){ 
                                    $row_m_act_s=mysqli_fetch_assoc($q_standard_run);
                                    $mp_std=floor($row_m_act_s['Act_Standard_Emp_Ratio']);
                                    $output_std=floor($row_m_act_s['Act_Standard_Output_Ratio']);
                                    if($mp_std==NULL || $mp_std==0){
                                        $eval_text='not set';
                                    }
                                    elseif($output_std==NULL || $output_std==0){
                                        $eval_text='not set';
                                    }
                                    else{
                                        $lcm = lcm($output,$output_std);
                                        $eval_text='';
                                        $standard=($output_std/$lcm)*$mp_std;
                                        $output_comp=($output/$lcm)*$total_emp;
                                        $eval=$output_comp-$standard;
                                        //blue (more than standard)
                                        if($standard<$output_comp){ 
                                            $blue++;
                                        }
                                        //green (standard)
                                        if($standard==$output){ 
                                            $green++;
                                        }
                                        //yellow (below standard)
                                        elseif($standard>$output){ 
                                            $yellow++;
                                        }
                                    }
                                }
                            }
                            $date_d_arr=NULL;
                            $date_name=$month_text.' '.$year;
                            $chart_arr[]=array(array("$date_name","$blue","$green","$yellow"));
                            $date_name_arr[]=$date_name;
                            $green_arr[]=$green;
                            $yellow_arr[]=$yellow;
                            $blue_arr[]=$blue;
                            $tot_emp_arr[]=$total_emp_ar;
                            $green=0;
                            $yellow=0;
                            $blue=0;
                        }
                    }
                }
                if($date_d_arr){
                    foreach($date_d_arr as $key1 => $date1){
                        $Date_day=$date1; $total_emp_ar=0;
                        $q_chart="SELECT * FROM daily_entry2 as de
                                LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                                WHERE de.Prj_Id='$prj_id' AND de.DE_Date2='$Date_day' AND de.DE_Status2=1";
                        $q_chart_run=mysqli_query($connection,$q_chart);
                        if(mysqli_num_rows($q_chart_run)>0){
                            while($row_d=mysqli_fetch_assoc($q_chart_run)){
                                //Retrieve Standard
                                $act_id=$row_d['Act_Id'];
                                $emp_no=$row_d['DE_Emp_No'];
                                $sb_no=$row_d['DE_SB_No'];
                                $mp_no=$row_d['DE_MP_No'];
                                $total_emp=$emp_no+$sb_no+$mp_no; //emp + subcon + mp
                                $total_emp_ar=$total_emp_ar+$total_emp;
                                $output=$row_d['DE_Output_No'];//output
                                $q_standard="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Id='$act_id' AND Act_Standard_Status=1";
                                $q_standard_run=mysqli_query($connection,$q_standard);
                                if(mysqli_num_rows($q_standard_run)>0){ 
                                    $row_d_act_s=mysqli_fetch_assoc($q_standard_run);
                                    $mp_std=floor($row_d_act_s['Act_Standard_Emp_Ratio']);
                                    $output_std=floor($row_d_act_s['Act_Standard_Output_Ratio']);
                                    if($mp_std==NULL || $mp_std==0){
                                        $eval_text='not set';
                                    }
                                    elseif($output_std==NULL || $output_std==0){
                                        $eval_text='not set';
                                    }
                                    else{
                                        $lcm = lcm($output,$output_std);
                                        $eval_text='';
                                        $standard=($output_std/$lcm)*$mp_std;
                                        $output_comp=($output/$lcm)*$total_emp;
                                        $eval=$output_comp-$standard;
                                        //blue
                                        if($standard<$output_comp){ 
                                            $blue++;
                                        }
                                        //green (standard)
                                        if($standard==$output){ 
                                            $green++;
                                        }
                                        //yellow (below standard)
                                        elseif($standard>$output){ 
                                            $yellow++;
                                        }
                                    }
                                }
                            }
                            $month_text = date("M", strtotime($Date_day));
                            $day_text=date("d", strtotime($Date_day));
                            $year= date("Y", strtotime($Date_day));
                            $date_name= $month_text.' '.$day_text.' '.$year;
                            $chart_arr[]=array(array("$date_name","$blue","$green","$yellow"));
                            $date_name_arr[]=$date_name;
                            $green_arr[]=$green;
                            $yellow_arr[]=$yellow;
                            $blue_arr[]=$blue;
                            $tot_emp_arr[]=$total_emp_ar;
                            $green=0; $total_emp_ar=0;
                            $yellow=0;
                            $blue=0;
                        }
                        
                    }
                    
                }
                if(count($chart_arr)>0){
                    for ($i=0; $i < count($chart_arr); $i++) { 
                        for ($j=0; $j < count($chart_arr[$i]); $j++) { 
                            for ($k=0; $k < count($chart_arr[$i][$j]); $k++) { 
                                // for($l=0; $l < count($chart_arr[$i][$k]); $l++){
                                    // echo $chart_arr[$i][$j][$k];
                                    // echo '<br>';
                                // }
                            }
                        }
                    }
                }
                if($date_name_arr){
                    $date_name_arr=implode("', '",$date_name_arr);
                }
                if($green_arr){
                    $green_arr=implode("', '",$green_arr);
                }
                if($yellow_arr){
                    $yellow_arr=implode("', '",$yellow_arr);
                }
                if($blue_arr){
                    $blue_arr=implode("', '",$blue_arr);
                }
                if($tot_emp_arr){
                    $tot_emp_arr=implode("', '",$tot_emp_arr);
                }
            ?>
            <div>
                <canvas id="act_chart"></canvas>
            </div>
            <div>
                <canvas id="emp_no_chart"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function () {
    $('#cat_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var prj_cat = $('#prj_category').val();
            var vblg = $('#bblg_opt').val(); //villa
            var bblg = $('#blg_opt').val(); //building
            var dept = $('#dept_opt').val();
            var cat = $('#cat_opt').val();
            // building
            if ( prj_cat=='Building' ){
                var vblg = $('#bblg_opt').val();
                $.ajax({
                url: '../P_ADMIN/ajax_r_table.php',
                method: 'POST',
                data:{'ddept_id':dept,
                    'blg_id': bblg,
                    'cat_id': cat,
                },
                success:function(data){
                    $('#rr_table').html(data).change();
                    $('#r_table').addClass('d-none');
                    $('#rr_table').removeClass('d-none');
                    $('#btn_dept').addClass('d-none');
                    $('#btn_cat').removeClass('d-none');
                    }
                });
            }
            // villa
            else ( prj_cat=='Villa' )
            {
                var plx_id = $('#plex_opt').val();
                
                if(plx_id=='All')
                {
                    console.log('all');
                    var plx_id = $('#plex_opt').val();
                    var villa_id = $('#villa_opt').val();
                    var cat = $('#cat_opt').val();
                    var dept = $('#dept_opt').val();

                    $.ajax({
                    url: '../P_ADMIN/ajax_villa_rpt.php',
                    method: 'POST',
                    data:{'ddept_id':dept,
                        'villa_id': villa_id,
                        'ccat_id': cat,
                        'all': plx_id,
                    },
                    success:function(data){
                        // console.log(data);
                        $('#r_table').html(data).change();
                        // $('#r_table').addClass('d-none');
                        // $('#rr_table').removeClass('d-none');
                        $('#btn_dept').addClass('d-none');
                        $('#btn_cat').removeClass('d-none');
                        }
                    });
                }
                else
                {
                    var plx_id = $('#plex_opt').val();
                    var villa_id = $('#villa_opt').val();
                    var cat = $('#cat_opt').val();
                    var dept = $('#dept_opt').val();
                    $.ajax({
                    url: '../P_ADMIN/ajax_villa_rpt.php',
                    method: 'POST',
                    data:{'ddept_id':dept,
                        'cat_id': cat,
                        'villa_id': villa_id,
                        'plx_id': plx_id,
                    },
                    success:function(data){
                        $('#rr_table').html(data).change();
                        $('#r_table').addClass('d-none');
                        $('#rr_table').removeClass('d-none');
                        $('#btn_dept').addClass('d-none');
                        $('#btn_cat').removeClass('d-none');
                        }
                    });
                }
            }
        }
    });
});
    $(document).ready(function(){
        $("#btn_act").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#dataTable"));
    });
    $(document).on('click', '.record', function() {
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       var asgd_id = data[0];
       $.ajax({
            url: '../P_ADMIN/ajax_act_record.php',
            method: 'POST',
            data:{'asgd_id':asgd_id},
            success:function(data){
                $('#history').html(data).change();
            }
        });
        $('#editProg').modal('show');
    });
    $(document).ready(function(){
        $(document).on('click', '.toAct', function(){
            var blg_id = $(this).find("#td_f").val();
            var d_id = $('#dept_opt').val();
            
            var prj_cat = $('#prj_category').val();
            if(prj_cat=='Building'){
                var vblg = $('#bblg_opt').val();
                $.ajax({
                url: 'ajax_r_table.php',
                method: 'POST',
                data:{'ddept_id':dept,
                    'blg_id': bblg,
                    'cat_id': cat,
                },
                success:function(data){
                    $('#rr_table').html(data).change();
                    $('#r_table').addClass('d-none');
                    $('#rr_table').removeClass('d-none');
                    $('#btn_dept').addClass('d-none');
                    $('#btn_cat').removeClass('d-none');
                    }
                });
            }
            else{
                var blg_id = $(this).find("#td_f").val();
                var d_id = $('#dept_opt').val();
                $.ajax({
                url: '../P_ADMIN/ajax_act_list.php',
                method: 'POST',
                data:{'blg_id':blg_id,
                        'dept_id': d_id,
                },
                success:function(data){
                    $('#act_tbl').html(data).change();
                    $('.selectpicker').selectpicker('refresh');
                    $('#actTable').DataTable({
                            pageLength: 100,
                            "searching": true,
                        });
                    }
                });
            }
        });  
    });
    $(document).ready(function(){
        $(document).on('click', '#btnleft', function(){
            $current_date=$('#current_date').val();
        });
    });
    $(document).ready(function(){
        $(document).on('click', '#btnright', function(){
            $current_date=$('#current_date').val();
        });
    });
});
const ctx = document.getElementById('act_chart');
  new Chart(ctx, {
    type: 'bar',
    data: {   //dates
      labels: [<?php echo "'".$date_name_arr."'";?>], 
      datasets: [
        {
            label: 'Above Standard', //blue
            data: [<?php echo "'".$blue_arr."'";?>], 
            borderWidth: 1,
            backgroundColor: '#36b9cc'
        },
        {
            label: 'Standard', //green
            data: [<?php echo "'".$green_arr."'";?>], 
            borderWidth: 1,
            backgroundColor: '#1cc88a'
        },
        {
            label: 'Almost',  //yellow
            data: [<?php echo "'".$yellow_arr."'";?>], 
            borderWidth: 1,
            backgroundColor: '#f6c23e'
        },
        // {
        // label: 'Manpower', //total mp
        // data: [<?php echo "'".$tot_emp_arr."'";?>],
        // borderWidth: 1,
        // backgroundColor: '#5a5c69'
        // }
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
  const emp_c = document.getElementById('emp_no_chart');
  new Chart(emp_c, {
    type: 'bar',
    data: {   //dates
      labels: [<?php echo "'".$date_name_arr."'";?>], 
      datasets: [
        {
        label: 'No. of Manpower', //green
        data: [<?php echo "'".$tot_emp_arr."'";?>], 
        borderWidth: 1,
        backgroundColor: '#36b9cc'
        }
      ]
    },
    options: {
      plugins: {
        title: {
          display: true,
        //   text: 'Chart.js Bar Chart - Stacked'
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
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>