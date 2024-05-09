<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/pm_navbar.php'); 
error_reporting(0);
if(isset($_POST['reportBtn']) )
{
    $prj_id = $_POST['prj_id'];
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
    <div class="row">
        <!-- total no task-->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total No. of Tasks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            echo'<h2>'.$total.'</h2>';
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
        <!-- Done -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Done</div>
                        <div class="h5 mb-0 font-weight-bold text-success-800">
                            <?php 
                            echo'<h2>'.$done.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-check-square fa-2x text-success-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ongoing -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ongoing</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            echo'<h2>'.$ongoing.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-pause fa-2x text-black-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">To Do</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php 
                            echo'<h2>'.$todo.'</h2>';
                            ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-ellipsis-h fa-2x text-black-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-lg-12">
        <div class="row">
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
        <!-- AJAX TABLE  -->
        <div class="card-body">
            <div id="r_table">
            </div>
            <div id="rr_table">
            </div>
            <div align="right">
                <button name="" id="btn_cat" class="btn btn-success mt-2 d-none">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
            <div align="right">
                <button name="" id="btn_dept" class="btn btn-success mt-2 d-none">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
        <!-- ///////////////////////////////////////////////////////// -->
        <hr>
        <h5 class="m-0 font-weight-bold text-primary">Actitivty Lists</h5>
            <div id="a_building">
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-3">
                        <label>Building</label>
                        <select name="" id="a_bblg_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Building</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Level</label>
                        <select name="" id="a_blvl_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Level</option>
                        </select>
                    </div>
                    <div class="col-3">
                        
                    </div>
                </div>
            </div>
            <div id="a_villa">
                <div class="row pb-3 pl-3 pt-2">
                    <div class="col-3">
                        <label>Area</label>
                        <select name="" id="a_villa_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Villa</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Plex</label>
                        <select name="" id="a_plex_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Plex</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Villa</label>
                        <select name="" id="a_vblg_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Building</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <label>Level</label>
                        <select name="" id="a_vlvl_opt" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Level</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pb-3 pl-3 pt-2">
                <div class="col-3">
                    <label>Flat</label>
                    <select name="flt_id" id="a_bflt_opt" class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Flat</option>
                    </select>
                </div>
                <div class="col-3">
                    <label>Department</label>
                    <select name="" id="a_dept_opt" class="form-control" required>
                        <option value="">Select Building</option>
                    </select>
                </div>
                <div class="col-3 d-none">
                    <label>Category</label>
                    <select name="" id="a_cat_opt" class="form-control" required>
                        <option value="">Select Building</option>
                    </select>
                </div>
            </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="act_tbl">
                </div>
            </div>
            <div align="right">
                <button name="" id="btn_act" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
    </div>
</div>
<!-- Modal Record -->
<div class="modal fade bd-example-modal-lg" id="editProg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-history" aria-hidden="true"></i> Records</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- THE FORM -->
        <div id="history">
        </div>
        <!-- END FORM -->
      </div>
    </div>
  </div>
</div>
<!-- End Modal Record -->
<!-- Modal Manage Emp --> 
<div class="modal fade bd-example-modal-xl" id="EmployeeAsgn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      <div class="modal-body" id="manage_emp">
        <!-- <div class="modal-footer">  
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
        </div>   -->
    </div>
  </div>
</div>
<script>
//BUILDING POPULATING OPTIONS
$(document).ready(function () {
    // Department Change - for table layout
    $(document).on('change','#r_table', function(){
        var number = $('#count').val(); //number - value to add to single cell
        document.getElementById("rf").colSpan = number;
        document.getElementById("rh").colSpan = number;
        $(".td_no").attr("colspan",number); // changing single cell span value
        var i; 
        var h_no = +number+3; // h_no - highest no per tr
        var ids= document.querySelectorAll("*[id^='tr_'] "); // get_all tr ids
        for (i = 0; i < ids.length; i++) {
            // colspan length - loop counting of colspan
            var idd = ids[i];
            $(document).find(ids[i]).find("td,th").each(function(){
                var colspan = $(this).attr("colspan");
                if(typeof colspan !== "undefined" && colspan > 0){
                    length += parseInt(colspan);
                }else{
                        length += 1;
                }
            });
            if(length< h_no)
            {
                var Toadd = h_no-length +1; // computation of value to add to 1st cell 
                // count no of flats
                var tr_id = (ids[i]).id;
                var chop = tr_id.substring(2); // num _1, _2, _13
                var search = '#tr'+chop+' td'; // td_3
                var id_td = 'td'+chop;
                var flat_ids = $(search).length; flat_ids= flat_ids-3;
                if(Toadd>3 && flat_ids !== 1)
                {
                    var dist = number/flat_ids; var no=1;
                    var decimal = number % flat_ids;
                    // loop of all flats
                    for(c=0; c < flat_ids; c++){
                        // get id of flats
                        var cell_id = id_td+'_'+no; //
                        no++;
                        document.getElementById(cell_id).colSpan = dist;
                    }
                    if(decimal>10)
                    {
                        flr = decimal %2;
                        decimal = decimal/2;
                        var td_num = tr_id.substring(2);
                        if (flr===0)
                        {
                            var td_id = 'td' + td_num+'_1'; // get id of 1st column
                            var td_id2 = 'td' + td_num+'_2'; // get id of 2 column
                            document.getElementById(td_id).colSpan = decimal+1; // td_id - 1st column
                            document.getElementById(td_id2).colSpan = decimal+1; // td_id - 2 column
                        }
                        else
                        {
                            var td_id = 'td' + td_num+'_1'; // get id of 1st column
                            var td_id2 = 'td' + td_num+'_2'; // get id of 2 column
                            document.getElementById(td_id).colSpan = decimal; // td_id - 1st column
                            document.getElementById(td_id2).colSpan = decimal; // td_id - 1st column
                        }
                        
                    }
                    else{
                        var last = dist+ decimal; 
                        var ttr_id = (ids[i]).id;
                        var td_num = ttr_id.substring(2);
                        var td_id = 'td' + td_num+'_1'; // get id of 1st column
                        document.getElementById(td_id).colSpan = last; // td_id - 1st column
                    }
                }
                // for single flat
                else{
                    var ttr_id = (ids[i]).id;
                    var td_num = ttr_id.substring(2);
                    var td_id = 'td' + td_num+'_1'; // get id of 1st column
                    document.getElementById(td_id).colSpan = Toadd; // td_id - 1st column
                }
            }
            length=0; 
        }
    });
    //villa options
    $(document).on('change','#rr_table', function(){
        var numbers = $('#count').val();
        document.getElementById("r_header").colSpan = numbers;
        document.getElementById("r_footer").colSpan = numbers;
        $(document).find(".tdd_no").attr("colspan",numbers);
        var i; 
        var h_no = +numbers+3;
        console.log(h_no);
        var ids= document.querySelectorAll("*[id^='ttr_'] ");
        for (i = 0; i < ids.length; i++) { // length ng levels
            // colspan length
            var idd = ids[i];
            $(document).find(ids[i]).find("td,th").each(function(){
                var colspan = $(this).attr("colspan");
                if(typeof colspan !== "undefined" && colspan > 0){
                    length += parseInt(colspan);
                }else{
                        length += 1;
                }
            });
            if(length< h_no)
            {
                var Toadd = h_no-length +1; // computation of value to add to 1st cell 
                // count no of flats
                var tr_id = (ids[i]).id;
                var chop = tr_id.substring(3); // num _1, _2, _13
                var search = '#ttr'+chop+' td'; // td_3
                var id_td = 'ttd'+chop;
                var flat_ids = $(search).length; flat_ids= flat_ids-3;
                if(Toadd>3 && flat_ids !== 1)
                {
                    var dist = numbers/flat_ids; var no=1;
                    var decimal = number % flat_ids;
                    // loop of all flats
                    for(c=0; c < flat_ids; c++){
                        // get id of flats
                        var cell_id = id_td+'_'+no;
                        no++;
                        document.getElementById(cell_id).colSpan = dist;
                    }
                    if(decimal>10)
                    {
                        flr = decimal %2;
                        decimal = decimal/2;
                        var td_num = tr_id.substring(3);
                        if (flr===0)
                        {
                            var td_id = 'ttd' + td_num+'_1'; // get id of 1st column
                            var td_id2 = 'ttd' + td_num+'_2'; // get id of 2 column
                            document.getElementById(td_id).colSpan = decimal+1; // td_id - 1st column
                            document.getElementById(td_id2).colSpan = decimal+1; // td_id - 2 column
                        }
                        else
                        {
                            var td_id = 'ttd' + td_num+'_1'; // get id of 1st column
                            var td_id2 = 'ttd' + td_num+'_2'; // get id of 2 column
                            document.getElementById(td_id).colSpan = decimal; // td_id - 1st column
                            document.getElementById(td_id2).colSpan = decimal; // td_id - 1st column
                        }
                        
                    }
                }
                else{
                    var ttr_id = (ids[i]).id;
                    var td_num = ttr_id.substring(3);
                    var td_id = 'ttd' + td_num+'_1'; // get id of 1st column
                    document.getElementById(td_id).colSpan = Toadd; // td_id - 1st column
                }
            }
            length=0;
        }
    });  
    // Area Options Populate
    var prj_id = $('#prj_id').val();
    $.ajax({
        url:'../P_ADMIN/ajax_villa.php',
        method:'POST',
        data: {'prj_id':prj_id},
        success:function(data){
            $('#villa_opt').html(data).change();
            $('#a_villa_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }   
    });
    //Plex Options Populate
    $(document).on('change','#villa_opt', function(){
        var v_id = $(this).val();
            $.ajax({
            url:'../P_ADMIN/ajax_plex.php',
            method:'POST',
            data: {'v_id':v_id},
            success:function(data){
                $('#plex_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }   
        });
    });
    // Area/Villa Options 2nd table
    $(document).on('change','#a_villa_opt', function(){
        var v_id = $(this).val();
            $.ajax({
            url:'../P_ADMIN/ajax_plex.php',
            method:'POST',
            data: {'villa_id':v_id},
            success:function(data){
                $('#a_plex_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }   
        });
    });
    // Building Options - Villa 
    $(document).on('change','#plex_opt', function(){
        var plx_id = $(this).val();
        $.ajax({
            url:'../P_ADMIN/ajax_blg.php',
            method:'POST',
            data: {'plx_id':plx_id},
            success:function(data){
                $('#bblg_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }   
        });
    });
    // Plex Options - Villa 2 table
    $(document).on('change','#a_plex_opt', function(){
        var plx_id = $(this).val();
        $.ajax({
            url:'../P_ADMIN/ajax_blg.php',
            method:'POST',
            data: {'plex_id':plx_id},
            success:function(data){
                $('#a_vblg_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }   
        });
    });
    // Level Options - Villa 2 table
    $(document).on('change','#a_vblg_opt', function(){
    var blg_id = $(this).val();
    $.ajax({
        url: '../P_ADMIN/ajax_blg.php',
        method: 'POST',
        data:{'blg_id':blg_id},
        success:function(data){
            $('#a_vlvl_opt').html(data).change();
            console.log(data);
            $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    $(document).on('change','#a_vlvl_opt', function(){
        // populate flat options - building
        var lvl_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_flat.php',
            method: 'POST',
            data:{'lvl_id':lvl_id},
            success:function(data){
                $(document).find('#a_bflt_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // --------------------
    // populate building options - building
    var prj_id = $('#prj_id').val();
    $.ajax({
        url:'../P_ADMIN/ajax_blg.php',
        method:'POST',
        data: {'lvl_prj_id':prj_id},
        success:function(data){
            $(document).find('#a_bblg_opt').html(data).change();
            $('#blg_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }   
    });
    // department options
    $.ajax({
        url:'../P_ADMIN/ajax_dept.php',
        method:'POST',
        data: {},
        success:function(data){
            $('#dept_opt').html(data).change();
            $('#a_dept_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }   
    });
    // department category options
    $(document).on('change','#dept_opt', function(){
        var d_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_act_cat.php',
            method: 'POST',
            data:{'dept_id':d_id},
            success:function(data){
                $('#cat_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    $(document).on('change','#a_dept_opt', function(){
        var d_id = $(this).val();
        var flt_id = $('#a_bflt_opt').val();
        $.ajax({
            url: '../P_ADMIN/ajax_act_cat.php',
            method: 'POST',
            data:{'dept_id':d_id},
            success:function(data){
                $('#a_cat_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
        $.ajax({
        url: '../P_ADMIN/ajax_act_list.php',
        method: 'POST',
        data:{'flatt_id':flt_id,
                'dept_id': d_id,
        },
        success:function(data){
            $('#act_tbl').html(data).change();
            $('.selectpicker').selectpicker('refresh');
            $('#actTable').DataTable({
                    pageLength: 100,
                    "searching": true,
                });
            console.log(data);
            }
        });
    });
});
// 1st table report Department Change
$(document).ready(function () {
    $('#dept_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var prj_cat = $('#prj_category').val();
            var bblg = $('#blg_opt').val(); //building
            var dept = $('#dept_opt').val();
            var cat = $('#cat_opt').val();
            // villa
            if ( prj_cat=='Villa' )
            {
                var plx_id = $('#plex_opt').val();
                var villa_id = $('#villa_opt').val();
                $.ajax({
                url: '../P_ADMIN/ajax_villa_rpt.php',
                method: 'POST',
                data:{'dept_id':dept,
                    'plx_id': plx_id,
                    'villa_id': villa_id
                },
                success:function(data){
                    $('#r_table').html(data).change();
                    $('#rr_table').addClass('d-none');
                    $('#r_table').removeClass('d-none');
                    $('#btn_dept').removeClass('d-none');
                    $('#btn_cat').addClass('d-none');
                    }
                });
            }
            //building
            if ( prj_cat=='Building' )
            {
                var bblg = $('#blg_opt').val();
                $.ajax({
                url: '../P_ADMIN/ajax_r_table.php',
                method: 'POST',
                data:{'dept_id':dept,
                    'blg_id': bblg,
                },
                success:function(data){
                    $('#r_table').html(data).change();
                    $('#rr_table').addClass('d-none');
                    $('#r_table').removeClass('d-none');
                    $('#btn_dept').removeClass('d-none');
                    $('#btn_cat').addClass('d-none');
                    }
                });
            }
        }
        else {}
    });
});
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
            if ( prj_cat=='Building' )
            {
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
            if ( prj_cat=='Villa' )
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
    $("#btn_dept").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#dept_tbl"));
    });
});
$(document).ready(function(){
    $("#btn_cat").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#cat_tbl"));
    });
});
// ///////////////////// ACTIVITY
    // BUILDING
    $(document).on('change','#a_bblg_opt', function(){
    // populate level options - building
    var blg_id = $(this).val();
    $.ajax({
        url: '../P_ADMIN/ajax_blg.php',
        method: 'POST',
        data:{'blg_id':blg_id},
        success:function(data){
            $('#a_blvl_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $(document).on('change','#a_blvl_opt', function(){
        // populate flat options - building
        var lvl_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_flat.php',
            method: 'POST',
            data:{'lvl_id':lvl_id},
            success:function(data){
                $(document).find('#a_bflt_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    
    // ACT LIST
    $(document).on('change','#a_bflt_opt', function(){
        var id = $(this).val();
        $.ajax({
        url: '../P_ADMIN/ajax_act_list.php',
        method: 'POST',
        data:{'flt_id':id},
        success:function(data){
            $('#act_tbl').html(data).change();
            $('.selectpicker').selectpicker('refresh');
            $('#actTable').DataTable({
                    pageLength: 100,
                    "searching": true,
                });
            }
        });
    });
    $('#a_cat_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var id = $(this).val();
            var flt_id = $('#a_bflt_opt').val();
            $.ajax({
            url: '../P_ADMIN/ajax_act_list.php',
            method: 'POST',
            data:{'flat_id':flt_id,
                    'act_cat_id': id
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
        else{}
        
    });
});
    $(document).ready(function(){
        $("#btn_act").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#actTable"));
    });
    $(document).on('click', '.record', function() {
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       console.log(data);
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
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>