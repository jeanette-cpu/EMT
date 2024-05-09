<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php');

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
            $("#blg_opt").addClass("d-none");
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
<h3 class="float-left ml-2 text-primary"><?php echo $row['Prj_Code'].' - '.$row['Prj_Name']?></h3><br><br>
<input type="hidden" id="prj_id" value="<?php echo $prj_id?>">
<input type="hidden" id="dept_id" value="">
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
<?php
include('de_script.php');
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>