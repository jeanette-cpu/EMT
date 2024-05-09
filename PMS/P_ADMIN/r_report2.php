<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
error_reporting(0);
date_default_timezone_set('Asia/Dubai');
function decFormat($value) {
    $pattern = '/\.\d{3,}/'; // Regular expression pattern
  
    if (preg_match($pattern, $value)) {
      $value=round($value, 2);
      return $value; // More than two decimal points found
    }
  
    return $value; // Less than or equal to two decimal points
}
if(isset($_POST['reportBtn']))
{
    $prj_id=$_POST['prj_id'];
    $q_prj = "SELECT * FROM project WHERE Prj_Id='$prj_id' ";
    $q_prj_run = mysqli_query($connection,$q_prj);
    $row = mysqli_fetch_assoc($q_prj_run);
    $q_bcount=0;
    $category = $row['Prj_Category'];
    if($category=='Building'){
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
    $fcount=$q_fcount;
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
                            if($days<0){
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
    <hr>
    <div class="col-xl-12 col-lg-12">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th>Department</th>
                    <th>EMT Manpower</th>
                    <th>Subcon</th>
                    <th>Manpower</th>
                    <th>Total (B)</th>
                    <th>Total Budget Manpower</th>
                    <th>Remaining Budget</th>
                    <th class="d-none">Average Progress</th>
                    <th>CUMMULATIVE BUDGETED MANPOWER AGAINST CURRENT PROGRESS (A)</th>
                    <th>VARIANCE (A-B)</th>
                </thead>
                <tbody>
    <?php
        $dept_q="SELECT * FROM department WHERE `Dept_Name` LIKE '%Electrical%' OR `Dept_Name` LIKE '%Plumbing%' OR `Dept_Name` LIKE '%HVAC%'";
        $dept_q_run=mysqli_query($connection,$dept_q);
        $date_filter=''; $total_cum=0; $ttotal_bgt=0;
        // if($post){
        //     $from='2023-05-29';
        //     $to='2023-05-29';
        //     $date_filter="DE_Date_Entry BETWEEN '".$from."' AND '".$to."' AND";
        // }
        if(mysqli_num_rows($dept_q_run)>0){ $emt_all=0;$sb_all=0;$mp_all=0; $total_bgt=0; $rm_bgt_tot=0;
            while($row_d=mysqli_fetch_assoc($dept_q_run)){
                $dept_id=$row_d['Dept_Id'];
                $dept_name=$row_d['Dept_Name'];
                $total_per_dept=0;
                $total_per_villa=0;
                //get Act_Ids that have standard
                if($category=='Villa'){ // computation for total budgetted mp
                    $bgt_mp="SELECT act_s.Act_Standard_Emp_Ratio as sum, act.Act_Id FROM activity_standard as act_s
                            LEFT JOIN activity as act on act.Act_Id = act_s.Act_Id
                            WHERE act_s.Prj_Id='$prj_id' AND act_s.Act_Standard_Status=1 AND act.Dept_Id='$dept_id'";
                    $bgt_mp_run=mysqli_query($connection,$bgt_mp);
                    if(mysqli_num_rows($bgt_mp_run)>0){    
                        while($row_b=mysqli_fetch_assoc($bgt_mp_run)){
                            $total_per_villa=$total_per_villa+$row_b['sum'];
                            $act_arr[]=$row_b['Act_Id'];
                        }
                        $act_ids = implode("', '", $act_arr);
                        $total_per_dept=$total_per_villa*$q_bcount; // total dept budget
                    }
                }
                elseif($category=='Building'){

                }
                $total_bgt=$total_bgt+$total_per_dept;
                $emt_tot=0; $emp_nos=0; $dept_total=0; $rm_bgt=0;
                $mp_tot=0;$mp_no=0; 
                $sb_tot=0; $sb_no=0;
                //emt
                $q_emt="SELECT count(DISTINCT(Emp_Id)) as emt, de.DE_Date_Entry  FROM daily_entry as de
                        LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                        LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                        LEFT JOIN asgn_worker as as_w on as_w.DE_Id=de.DE_Id
                        WHERE $date_filter de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                        AND as_act.Flat_Id IN ('$flt_ids') AND as_act.Act_Id IN ('$act_ids') AND act_cat.Dept_Id='$dept_id' AND as_w.Asgd_Worker_Status=1
                        GROUP BY de.DE_Date_Entry";
                $q_emt_run=mysqli_query($connection,$q_emt);
                if(mysqli_num_rows($q_emt_run)>0){  
                    while($row_e=mysqli_fetch_assoc($q_emt_run)){
                        $emp_nos=$row_e['emt'];
                        $emt_tot=$emt_tot+$emp_nos;
                    }
                }
                //subcon
                $q_sb="SELECT SUM(as_sb.Asgn_SB_Total) as sb_tot
                        FROM daily_entry as de
                        LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                        LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                        LEFT JOIN asgn_subcon as as_sb on as_sb.DE_Id=de.DE_Id
                        WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                        AND as_act.Flat_Id IN ('$flt_ids') AND as_act.Act_Id IN ('$act_ids') AND act_cat.Dept_Id='$dept_id' AND as_sb.Asgn_SB_Status=1";
                $q_sb_run=mysqli_query($connection,$q_sb);
                if(mysqli_num_rows($q_sb_run)>0){
                    $row_sb=mysqli_fetch_assoc($q_sb_run);
                    $sb_tot=$row_sb['sb_tot'];
                }
                //manpower
                $q_mp="SELECT SUM(as_mp.Asgn_MP_Total) as mp_tot
                        FROM daily_entry as de
                        LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                        LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                        LEFT JOIN asgn_mp as as_mp on as_mp.DE_Id=de.DE_Id
                        WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                        AND as_act.Flat_Id IN ('$flt_ids') AND as_act.Act_Id IN ('$act_ids') AND act_cat.Dept_Id='$dept_id' AND as_mp.Asgn_MP_Status=1";
                $q_mp_run=mysqli_query($connection,$q_mp);
                if(mysqli_num_rows($q_mp_run)>0){
                    $row_mp=mysqli_fetch_assoc($q_mp_run);
                    $mp_tot=$row_mp['mp_tot'];
                }
                $dept_total=$emt_tot+$sb_tot+$mp_tot;
                $rm_bgt=$total_per_dept-$dept_total;
                //cummulative budgeted manpower against current progress
                if($category=='Villa'){
                    $dept_act="SELECT * FROM activity_standard as act_s
                            LEFT JOIN activity as act on act.Act_Id=act_s.Act_Id
                            WHERE act_s.Prj_Id='$prj_id' AND act_s.Act_Standard_Status=1 AND act.Dept_Id='$dept_id' AND act.Act_Status=1 AND act_s.Act_Standard_Emp_Ratio!=0";
                    $dept_act_run=mysqli_query($connection,$dept_act);
                }
                elseif($category=='Building'){
                    $emp_bgt_per_villa=''; //update this variable
                }
                $tot_mp=0;$tot_sb=0;$tot_emt=0;
                $tot_bgt_per_villa=0;$tot_flt_in_progress=0;$tot_ave=0; $ave_cnt=0; $ave_prog=0;$tot_cum_bgt=0;
                if(mysqli_num_rows($dept_act_run)>0){
                    while($row_act=mysqli_fetch_assoc($dept_act_run)){
                        $ave_progress_act=''; $flat_in_progress=0;  $pf=''; $cum_bgt=''; $variance='';
                        $act_id=$row_act['Act_Id'];
                        $act_code=$row_act['Act_Code'];
                        $act_name=$row_act['Act_Name'];
                        $emp_bgt_per_villa=$row_act['Act_Standard_Emp_Ratio'];
                        $asgn_act="SELECT * FROM  assigned_activity 
                                    WHERE Asgd_Act_Status=1 AND Act_Id='$act_id' AND Flat_Id IN ('$flt_ids')";
                        $asgn_act_run=mysqli_query($connection,$asgn_act);
                        $total_emp=0; $total_sb=0;$total_mp=0;$act_tot=0;$sum_percentage=0;
                        
                        if(mysqli_num_rows($asgn_act_run)>0){
                            while($row_asc=mysqli_fetch_assoc($asgn_act_run)){
                                $asgd_act_id=$row_asc['Asgd_Act_Id'];
                                $flat_id=$row_asc['Flat_Id'];
                                $pct_done=$row_asc['Asgd_Pct_Done'];//current activity progress/villa
                                //search from daily entry days activity updated
                                $q_de="SELECT * FROM daily_entry WHERE DE_Status=1 AND Asgd_Act_Id='$asgd_act_id'";
                                $q_de_run=mysqli_query($connection,$q_de);
                                if(mysqli_num_rows($q_de_run)>0){
                                    $flat_in_progress++;
                                    $sum_percentage=$sum_percentage+$pct_done;
                                    while($row_de=mysqli_fetch_assoc($q_de_run)){
                                        $de_id=$row_de['DE_Id'];
                                        $de_date=$row_de['DE_Date_Entry'];
                                        $q_emp="SELECT * FROM asgn_worker as as_w
                                                LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id
                                                LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                                                WHERE as_w.Asgd_Worker_Status=1 AND DE_Date_Entry='$de_date' AND as_act.Act_Id='$act_id' AND de.DE_Status=1";
                                        $q_emp_run=mysqli_query($connection,$q_emp);  
                                        if(mysqli_num_rows($q_emp_run)>0){
                                            while($row_1=mysqli_fetch_assoc($q_emp_run)){
                                                $emp_id=$row_1['Emp_Id'];
                                                //check/count if an employee code worked on the "SAME" activity or "OTHER" on a single day on other villa
                                                $q_chk_emp="SELECT COUNT(as_w.Emp_Id) as dup FROM asgn_worker as as_w
                                                            LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id
                                                            WHERE as_w.Asgd_Worker_Status=1 AND DE_Date_Entry='$de_date' AND de.DE_Status=1 AND as_w.Emp_Id='$emp_id'";
                                                $q_chk_emp_run=mysqli_query($connection,$q_chk_emp);
                                                if(mysqli_num_rows($q_chk_emp_run)>0){
                                                    $row_chk=mysqli_fetch_assoc($q_chk_emp_run);
                                                    $count=$row_chk['dup'];
                                                    if($count==1){
                                                        $total_emp++;
                                                    }
                                                    else{
                                                        // echo $count.'<br>';
                                                        $div=1/$count;
                                                        $total_emp=$total_emp+ $div;//total employee worked in this activity
                                                    }
                                                }
                                                //total 
                                            }
                                        }
                                        //sb
                                        $q_sb="SELECT * FROM asgn_subcon as as_sb 
                                                LEFT JOIN daily_entry as de on de.DE_Id=as_sb.DE_Id
                                                LEFT JOIN subcontractor as sb on sb.SB_Id=as_sb.SB_Id
                                                WHERE as_sb.Asgn_SB_Status=1 and de.DE_Id='$de_id'";
                                        $q_sb_run=mysqli_query($connection,$q_sb);
                                        $sb_name=''; $sb_count='';
                                        if(mysqli_num_rows($q_sb_run)>0){
                                            while($row_sb=mysqli_fetch_assoc($q_sb_run)){
                                                $sb_id=$row_sb['SB_Id'];
                                                $sb_name.=$row_sb['SB_Name'].'<br>';
                                                //check how many total 
                                                $sb_chk_tot="SELECT COUNT(as_sb.SB_Id) as cnt, SUM(as_sb.Asgn_SB_Total) as sb_tot
                                                            FROM asgn_subcon as as_sb
                                                            LEFT JOIN daily_entry as de on as_sb.DE_Id=de.DE_Id
                                                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                                                            LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                                                            WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                                                            AND as_act.Flat_Id IN ('$flt_ids') AND as_sb.Asgn_SB_Status=1 
                                                            AND de.DE_Date_Entry='$de_date' AND as_sb.SB_Id='$sb_id' AND act_cat.Dept_Id='$dept_id'";
                                                // echo $sb_chk_tot.'<br>';
                                                $sb_chk_tot_run=mysqli_query($connection,$sb_chk_tot); 
                                                if(mysqli_num_rows($sb_chk_tot_run)>1){
                                                    $row_sb_chk=mysqli_fetch_assoc($sb_chk_tot_run);
                                                    $count=$row_sb_chk['cnt'];
                                                    $total=$row_sb_chk['sb_tot'];
                                                    $sb_cnt_per_act=$count/$total;
                                                    $total_sb=$total_sb+$sb_cnt_per_act;  
                                                    decFormat($sb_cnt_per_act);
                                                }
                                                else{// show what is entered  
                                                    $total_sb=$total_sb+$row_sb['Asgn_SB_Qty'];  
                                                }
                                            }
                                        }
                                        //mp
                                        $q_mp="SELECT * FROM asgn_mp as as_mp 
                                                LEFT JOIN daily_entry as de on de.DE_Id=as_mp.DE_Id
                                                LEFT JOIN manpower as mp on mp.MP_Id=as_mp.MP_Id
                                                WHERE as_mp.Asgn_MP_Status=1 and de.DE_Id='$de_id'";
                                        $q_mp_run=mysqli_query($connection,$q_mp); $mp_count='';
                                        if(mysqli_num_rows($q_mp_run)>0){
                                            while($row_mp=mysqli_fetch_assoc($q_mp_run)){
                                                $MP_Id=$row_mp['MP_Id'];
                                                //check how many total 
                                                $mp_chk_tot="SELECT COUNT(as_mp.MP_Id) as cnt, SUM(as_mp.Asgn_MP_Total) as mp_tot
                                                            FROM asgn_mp as as_mp
                                                            LEFT JOIN daily_entry as de on as_mp.DE_Id=de.DE_Id
                                                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                                                            LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                                                            WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                                                            AND as_act.Flat_Id IN ('$flt_ids') AND as_mp.Asgn_MP_Status=1 
                                                            AND de.DE_Date_Entry='$de_date' AND as_mp.MP_Id='$MP_Id' AND act_cat.Dept_Id='$dept_id'";
                                                // echo $mp_chk_tot.'<br>';
                                                $mp_chk_tot_run=mysqli_query($connection,$mp_chk_tot); 
                                                if(mysqli_num_rows($mp_chk_tot_run)>1){
                                                    $row_mp_chk=mysqli_fetch_assoc($mp_chk_tot_run);
                                                    $count=$row_mp_chk['cnt'];
                                                    $total=$row_mp_chk['mp_tot'];
                                                    $mp_cnt_per_act=$count/$total;
                                                    $total_mp=$total_mp+$mp_cnt_per_act;  
                                                    decFormat($mp_cnt_per_act);
                                                }
                                                else{// show what is entered  
                                                    $total_mp=$total_mp+$row_mp['Asgn_MP_Qty'];  
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $act_tot=$total_emp+$total_mp+$total_sb;
                        $bgt_per_act=$fcount*$emp_bgt_per_villa;
                        if($flat_in_progress){
                            $ave_progress_act=$sum_percentage/$flat_in_progress;

                            $pf=null;
                            $total_bgt=$flat_in_progress*$emp_bgt_per_villa;
                            $ave_eq=$ave_progress_act*.01;
                            $cum_bgt=$total_bgt*$ave_eq;
                            if($act_tot){
                                $pf=$cum_bgt/$act_tot;
                                $pf=round($pf,2);
                                $variance=$cum_bgt-$act_tot;
                                decFormat($variance);
                            }
                            else{
                                $variance=NULL;
                            }
                        }
                        else{
                            $ave_progress_act='';
                        }
                        $sum_percentage=0;
                        if($ave_progress_act){
                            $tot_ave=$tot_ave+$ave_progress_act;
                            $ave_cnt++;
                        }
                        if($cum_bgt){
                            $tot_cum_bgt=$tot_cum_bgt+$cum_bgt;
                        }
                        $tot_emt=$tot_emt+$total_emp;
                        $tot_sb=$tot_sb+$total_sb;
                        $tot_mp=$tot_mp+$total_mp;
                        $tot_flt_in_progress=$tot_flt_in_progress+$flat_in_progress;
                        $total_emp=0;$total_sb=0;$total_mp=0;$act_tot=0;$flat_in_progress=0; $pf=''; $cum_bgt='';$variance='';
                        $tot_bgt_per_villa=$tot_bgt_per_villa+$emp_bgt_per_villa; //total budget per villa per department
                    }
                }  
                $total_bgt_dept=$fcount*$tot_bgt_per_villa;
                if($ave_cnt){
                    $ave_prog=$tot_ave/$ave_cnt;
                }
                $total_manpower=$tot_emt+$tot_sb+$tot_mp;
                $fvariance=$tot_cum_bgt-$dept_total;
                //computing pf
                $pf=null;
                $f_ave_eq=$ave_prog*.01;
                if($dept_total){
                    $pf=$tot_cum_bgt/$dept_total;
                }
            ?>
                <tr>
                    <td>
                        <form action="byDept.php" method="post">
                            <input type="hidden" name="fcount" value="<?php echo $q_fcount ;?>">
                            <input type="hidden" name="dept_id" value='<?php echo $dept_id;?>'>
                            <input type="hidden" name="dept_name" value='<?php echo $dept_name;?>'>
                            <input type="hidden" name="flat_ids" value="<?php echo $flt_ids;?>">
                            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>";>
                            <input type="hidden" name="prj_cat" value="<?php echo $category?>";>
                            <input type="hidden" name="prj_name" value="<?php echo $row['Prj_Code'].' - '.$row['Prj_Name']; ?>">
                            <button type="submit" name="byDept" class="btn btn-link" onclick="this.form.target='_blank';return true;"><?php echo $dept_name;?></button>
                        </form>
                    </td>
                    <td class="text-right"><?php echo number_format($emt_tot);?></td> <!--EMT-->
                    <td class="text-right"><?php echo number_format($sb_tot); ?></td> <!--Subcon-->
                    <td class="text-right"><?php echo number_format($mp_tot);?></td> <!--MP-->
                    <td class="text-right"><?php echo number_format($dept_total);?></td> <!--Total A-->
                    <td class="text-right"><?php echo number_format($total_per_dept);?></td> <!--Total Budget-->
                    <td class="text-right"><?php echo number_format($rm_bgt);?></td> <!--Remaining Budget-->
                    <td class="text-right d-none"><?php echo round($dept_avg, 2).'%';?></td> <!--Progress-->
                    <td class="text-right"><?php echo number_format($tot_cum_bgt);?></td> <!--cummulative budget mp against current progress B-->
                    <td class="text-right"><?php echo abs($fvariance);?></td> <!--variance-->
                </tr>
            <?php
            $emt_all=$emt_all+$emt_tot;
            $sb_all=$sb_all+$sb_tot;
            $mp_all=$mp_all+$mp_tot;
            $rm_bgt_tot=$rm_bgt_tot+$rm_bgt;
            $ttotal_bgt=$ttotal_bgt+ $total_per_dept;
             $emt_tot=0; $sb_tot=0; 
             if($dept_id==1){//electrical
                $elect_pf=$pf;
                $e_data=$dept_total.', '.$tot_cum_bgt;
                if($elect_pf>=1){
                    $elect_arrow='<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>';
                }
                elseif($elect_pf==0){
                    $elect_arrow='';
                }
                else{
                    $elect_arrow='<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i>';
                }
             }
             elseif($dept_id==2){
                $plumb_data=$dept_total.', '.$tot_cum_bgt;
                $plumb_pf=$pf;
                if($plumb_pf>=1){
                    $plumb_arrow='<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>';
                }
                elseif($plumb_pf==0){
                    $plumb_arrow='';
                }
                else{
                    $plumb_arrow='<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i>';
                }
             }
             elseif($dept_id==3){
                $hvac_data=$dept_total.', '.$tot_cum_bgt;
                $hvac_pf=$pf;
                if($hvac_pf>=1){
                    $hvac_arrow='<i class="fa fa-arrow-up text-success" aria-hidden="true"></i>';
                }
                elseif($hvac_pf==0){
                    $hvac_arrow='';
                }
                else{
                    $hvac_arrow='<i class="fa fa-arrow-down text-danger" aria-hidden="true"></i>';
                }
             }
             $total_cum=$total_cum+ $tot_cum_bgt;
             $mp_tot=0; $dept_total=0; $tot_cum_bgt=0; 
            }
        }
        $total_mp=$emt_all+$sb_all+$mp_all;
        $f_pf=$total_cum/$total_mp;
        $f_pf=decFormat($f_pf);
    ?>
    <script>
        var electDataset =[<?php echo $e_data;?>];
        var plumbDataset =[<?php echo $plumb_data;?>];
        var hvacDataset =[<?php echo $hvac_data;?>];
    </script>
                    <tfoot>    
                        <tr>
                            <td></td>
                            <td class="text-right"><?php echo number_format($emt_all);?></td>
                            <td class="text-right"><?php echo number_format($sb_all);?></td>
                            <td class="text-right"><?php echo number_format($mp_all);?></td>
                            <td class="text-right"><?php echo number_format($total_mp);?></td> <!-- B-->
                            <td class="text-right"><?php echo number_format($ttotal_bgt);?></td>
                            <td class="text-right"><?php echo number_format($rm_bgt_tot);?></td>
                            <td class="text-right"><?php echo number_format($total_cum);?></td> <!-- Total Cummulative A-->
                            <td class="text-right"><?php echo $f_pf;?></td><!--Variance -->
                        </tr>
                    </tfoot>
                </tbody>
            </table>
            </div>
            <div align="right">
                <button name="" id="btnExcel" class="btn btn-success mt-2 mb-2">
                    <i class="fa fa-download" aria-hidden="true"></i> Download
                </button>  
            </div>
            <!-- CHARTS -->
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Electrical Productivity</h6>
                        </div>
                        <div  class="card-body ml-4">
                            <div>
                                <h4><span class="font-weight-bold text-dark">PF:</span> <?php echo $elect_arrow.' '.decFormat($elect_pf); ?></h4>
                            </div>
                            <div class="chart-bar">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-left small">
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #f6853e"></i> Accumulated Budgeted Manpower Against Current Progress
                                </span>
                                <br>
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #f6c23e"></i>  Actual Manpower
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Plumbing Productivity</h6>
                        </div>
                        <div  class="card-body ml-4">
                            <div>
                                <h4><span class="font-weight-bold text-dark">PF:</span> <?php echo $plumb_arrow.' '.decFormat($plumb_pf)?></h4>
                            </div>
                            <div class="chart-bar">
                                <canvas id="myPiePlumb"></canvas>
                            </div>
                            <div class="mt-4 text-left small">
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #4ebcdf"></i>Accumulated Budgeted Manpower Against Current Progress
                                </span>
                                <br>
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #4e73df"></i>Actual Manpower
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">HVAC Productivity

                            </h6>
                        </div>
                        <div  class="card-body ml-4">
                            <div>
                                <h4><span class="font-weight-bold text-dark">PF:</span> <?php echo $hvac_arrow.' '.decFormat($hvac_pf)?></h4>
                            </div>
                            <div class="chart-bar">
                                <canvas id="myPieHVAC"></canvas>
                            </div>
                            <div class="mt-4 text-left small">
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #1cc88a"></i>Accumulated Budgeted Manpower Against Current Progress
                                </span>
                                <br>
                                <span class="mr-2">
                                    <i class="fas fa-circle" style="color: #159466"></i>Actual Manpower
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="js/demo/chart-pie-demo.js"></script>
<script>
 $(document).ready(function(){
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#dataTable'));
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>