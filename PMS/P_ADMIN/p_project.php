<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
if(isset($_GET['prj_id'])) {
    // show standard form
    $q_activities="SELECT * FROM activity WHERE Act_Status=1";
    $q_act_run=mysqli_query($connection,$q_activities);
    $prj_id=$_GET['prj_id'];
    if(mysqli_num_rows($q_act_run)>0){
        $add_form='';
        while($row_act=mysqli_fetch_assoc($q_act_run)){
            $act_id=$row_act['Act_Id'];
            $act_code=$row_act['Act_Code'];
            $act_name=$row_act['Act_Name'];
            $act_fname = $act_code.' '.$act_name;
            $add_form.="<tr>
                            <td>
                                <input type='hidden' name='act_id[]' value='$act_id' required> 
                                $act_fname
                            </td>
                            <td>
                                <input type='number' name='emp_r[]' class='form-control' required>
                            </td>
                            <td>
                                <input type='number' name='output_r[]' class='form-control' required>
                            </td>
                        </tr>";
        }
        $add_form.="<input type='hidden' name='prj_id' value='$prj_id' required>";
    }
    echo "<script>
            $(document).ready(function() {
                $('#AsgnActModal').modal('show');
            });
        </script>";
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Projects
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Add Project
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
                $query = "SELECT * FROM project WHERE Prj_Status =1 order by Prj_Code";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Category</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>Project Manager</th>
                        <th>Status</th>
                        <th>Standard</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $prj_id=$row['Prj_Id'];
                                $category = $row['Prj_Category'];
                                
                                if($category=='Building')
                                {
                                // get building assigned
                                $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
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
                                $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
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
                                        $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
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
                            if(mysqli_num_rows($q_pm_run)>0)
                            {
                                while($row_pm = mysqli_fetch_assoc($q_pm_run))
                                {
                                    $name[] = $row_pm['EMP_FNAME'].' '.$row_pm['EMP_MNAME'].' '.$row_pm['EMP_LNAME'].' '.$row_pm['EMP_SNAME'];
                                }
                                $names = implode(", ", $name);
                            }
                            ?>
                            <td class="d-none"><?php echo $row['Prj_Type']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Start_Date']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_End_Date']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Emirate_Location']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Location_Desc']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Client_Name']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Main_Contractor']; ?></td>
                            <td class="d-none"><?php echo $row['Prj_Consultant']; ?></td>
                            <td><?php echo $names?></td>
                            <td><?php echo $status?></td>
                            <td>
                                <form action="p_flt_type.php" method="post">
                                    <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id']; ?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $row['Prj_Code'].' '.$row['Prj_Name']?>">
                                    <input type="hidden" name="prj_type" value="<?php echo $row['Prj_Category'];?>">
                                    <button type="submit" class="btn btn-info btn-sm" data-toggle="modal" data-target="#">
                                        Standard
                                    </button>
                                </form>
                            </td>
                            <td class="btn-group text-center">
                                <!-- STANDARD -->
                                <form action="p_act_standard.php" class="d-none" method="post">
                                    <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id']; ?>">
                                    <button type="submit" class="btn btn-warning editStandard" data-toggle="modal" data-target="#">
                                        <i class="fa fa-check-square-o" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject" data-toggle="modal" data-target="#EditProjectModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>

                                <!-- DELETE button PERMANENT -->
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['Prj_Id'];?>">
                                        <button type="submit" name ="delete_prj" class="btn btn-danger d-inline">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                </form>
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

<!-- Modal Add Project -->
<div class="modal fade bd-example-modal-lg" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building mr-2" aria-hidden="true"></i>Add Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="post">
      <div class="modal-body">
            <div class="form-group">
                <label class="font-weight-bold" for="">Project Code</label>
                <input type="text" name="prj_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="">Project Name</label>
                <input type="text" name="prj_name" class="form-control" required>
            </div>
            <div>
                <label class="font-weight-bold" for="">Category</label>
                <div class="form-group">
                    <label class="radio mr-3 ml-4">
                        <input type="radio" name="prj_category" class="mr-1" value="Building" required>Building
                    </label>
                    <label class="radio mr-3">
                        <input type="radio" name="prj_category" class="mr-1" value="Villa" required>Villa
                    </label> 
                </div>
            </div>
            <div>
                <label class="font-weight-bold" for="">Project Type</label>
                <div class="form-group">
                    <label class="radio mr-3 ml-4">
                        <input type="radio" name="prj_type" value ="Residential" class="mr-1" required>Residential
                    </label>
                    <label class="radio mr-3">
                        <input type="radio" name="prj_type" value ="Hotel" class="mr-1" required>Hotel
                    </label>
                    <label class="radio">
                        <input type="radio" name="prj_type" value ="Car Parking" class="mr-1" required>Car Parking
                    </label>   
                
                    <label class="radio mr-3 ml-4">
                        <input type="radio" name="prj_type"  value ="Mosque" class="mr-1" required>Mosque
                    </label>
                    <label class="radio mr-3">
                        <input type="radio" name="prj_type" value ="Labour Camp" class="mr-1" required>Labour Camp
                    </label>
                    <label class="radio">
                        <input type="radio" name="prj_type" value ="Commercial" class="mr-1" required>Commercial
                    </label>   
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold" >Date Start</label>
                    <input type="date" name="prj_date_start" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold" for="">Due Date</label>
                    <input type="date" name="prj_due_date" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="">Emirate Location</label>
                    <div class="form-group"> 
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="prj_location" value="Abu Dhabi" class="mr-1" required>Abu Dhabi
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="prj_location" value="Ajman" class="mr-1" required>Ajman
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="prj_location" value="Dubai" class="mr-1" required>Dubai
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="prj_location" value="Fujairah" class="mr-1" required>Fujairah
                            </label>          
                        </div>
                    </div>     
                    <div class="form-group">
                        <div class="form-group col-md-12">
                            <label class="radio mr-3 ml-4">
                                <input type="radio" name="prj_location" value="Ras al Khaimah" class="mr-1" required>Ras al Khaimah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="prj_location" value="Sharjah" class="mr-1" required>Sharjah
                            </label>
                            <label class="radio mr-3">
                                <input type="radio" name="prj_location" value="Umm al Quwain" class="mr-1" required>Umm al Quwain
                            </label>
                        </div>
                        
                    </div> 
                <div class="form-group">
                    <label class="font-weight-bold" >Location Description</label>
                    <input type="text" name="prj_loc_desc" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Client Name</label>
                    <input type="text" name="prj_client_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Main Contractor Name</label>
                    <input type="text" name="prj_main_contractor" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Consultant Name</label>
                    <input type="text" name="prj_consultant" class="form-control" required>
                </div>  
                <div class="form-group d-none">
                    <label for="" class="font-weight-bold">Use default activity output standard?</label>
                    <input type="radio" name="actStandard" value="1" class="mr-1 check-fields1 ml-2" > Yes
                    <input type="radio" name="actStandard" value="0" class="mr-1 check-fields1" > No
                </div>
            </div>
            <input type="hidden" name="prj_status" value="1">
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="add_prj" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->

<!-- Assign Standard Act Modal -->
<div class="modal fade bd-example-modal-lg" id="AsgnActModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-check-square-o" aria-hidden="true"></i> Assign Activity Standards</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                    
                <div class="form-group">
                    <label class="font-weight-bold" for="">Project:</label>
                </div>
                <div class="form-group">
                    <table class="table table-bordered">
                        <thead>
                            <th width="65%">Activity</th>
                            <th>Employee</th>
                            <th>Output</th>
                        </thead>
                        <tbody>
                            <?php echo $add_form;?>
                        </tbody>
                    </table>
                </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal"> Assign Later</button>
            <button type="submit" name="asgn_standard" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
            </form>
    </div>
  </div>
</div>
<!-- End assign Project Modal -->

<!-- Assign Standard Act Modal -->
<div class="modal fade bd-example-modal-lg" id="EditStandardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-check-square-o" aria-hidden="true"></i> Assign Activity Standards</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                <div id="additonal_form">
                </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal"> Assign Later</button>
            <button type="submit" name="asgn_standard_after" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
            </form>
    </div>
  </div>
</div>
<!-- End assign Project Modal -->

<!-- End EDIT Project Modal -->
<div class="modal fade bd-example-modal-lg" id="EditProjectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel">Edit Project Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                        <input type="hidden" name="prj_id" id="update_id" class="form-control" >
                    
                <div class="form-group">
                    <label class="font-weight-bold" for="">Project Code</label>
                    <input type="text" name="e_prj_code" id="prj_code" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Project Name</label>
                    <input type="text" name="e_prj_name" id="prj_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Category</label><br>
                    <input type="radio" name="e_prj_category" id="Building" class="radio mr-4 ml-4" value="Building" required>Building
                    <input type="radio" name="e_prj_category" id="Villa" class="radio mr-4 ml-3" value="Villa" required>Villa
                </div>
                <div>
                    <label class="font-weight-bold" for="">Project Type</label>
                    <div class="form-group">
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="e_prj_type" id="Residential" value ="Residential" class="mr-1" required>Residential
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="e_prj_type" id="Hotel" value ="Hotel" class="mr-1" required>Hotel
                        </label>
                        <label class="radio">
                            <input type="radio" name="e_prj_type" id="CP" value ="Car Parking" class="mr-1" required>Car Parking
                        </label>    
                        <label class="radio mr-3 ml-4">
                            <input type="radio" name="e_prj_type" id="Mosque"  value ="Mosque" class="mr-1" required>Mosque
                        </label>
                        <label class="radio mr-3">
                            <input type="radio" name="e_prj_type" id="LC" value ="Labour Camp" class="mr-1" required>Labour Camp
                        </label>
                        <label class="radio">
                            <input type="radio" name="e_prj_type" id="Commercial" value ="Commercial" class="mr-1" required>Commercial
                        </label>   
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold" >Date Start</label>
                        <input type="date" name="e_prj_date_start" id="prj_date_start" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold" for="">Due Date</label>
                        <input type="date" name="e_prj_due_date" id="prj_due_date" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold" for="">Emirate Location</label>
                        <div class="form-group"> 
                            <div class="form-group col-md-12">
                                <label class="radio mr-3 ml-4">
                                    <input type="radio" name="e_prj_location" id="AD" value="Abu Dhabi" class="mr-1" required>Abu Dhabi
                                </label>
                                <label class="radio mr-3">
                                    <input type="radio" name="e_prj_location" id="Ajman" value="Ajman" class="mr-1" required>Ajman
                                </label>
                                <label class="radio mr-3">
                                    <input type="radio" name="e_prj_location" id="Dubai" value="Dubai" class="mr-1" required>Dubai
                                </label>
                                <label class="radio mr-3">
                                    <input type="radio" name="e_prj_location" id="Fujairah" value="Fujairah" class="mr-1" required>Fujairah
                                </label>          
                            </div>
                        </div>     
                        <div class="form-group">
                            <div class="form-group col-md-12">
                                <label class="radio mr-3 ml-4">
                                    <input type="radio" name="e_prj_location" id="rak" value="Ras al Khaimah" class="mr-1" required>Ras al Khaimah
                                </label>
                                <label class="radio mr-3">
                                    <input type="radio" name="e_prj_location" id="Sharjah" value="Sharjah" class="mr-1" required>Sharjah
                                </label>
                                <label class="radio mr-3">
                                    <input type="radio" name="e_prj_location" id="uaq" value="Umm al Quwain" class="mr-1" required>Umm al Quwain
                                </label>
                            </div>
                        </div> 
                    <div class="form-group">
                        <label class="font-weight-bold" >Location Description</label>
                        <input type="text" name="e_prj_loc_desc" id="prj_loc_desc" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Client Name</label>
                        <input type="text" name="e_prj_client_name" id="prj_client_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Main Contractor Name</label>
                        <input type="text" name="e_prj_main_contractor" id="prj_main_contractor" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold" for="">Consultant Name</label>
                        <input type="text" name="e_prj_consultant" id="prj_consultant" class="form-control" required>
                    </div>  
                </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="edit_prj" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
        </form>
    </div>
  </div>
</div>
<!-- End EDIT Project Modal -->
<script>
$(document).ready(function () {
    $('.editProject').on('click', function() {
        $('#EditProjectModal').modal('show');
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            // console.log(data);
            // alert(data);
            $('#update_id').val(data[0]);
            $('#prj_code').val(data[1]);
            $('#prj_name').val(data[2]);
            var item = data[3];
            $('#'+item).val(data[3]).prop('checked', true);

            var type = data[4];
            var prj_type = "";
            if(type==='Car Parking')
            {prj_type='CP';}
            else if(type==='Labour Camp')
            {prj_type='LC';}
            else{prj_type=type;}
            $('#'+prj_type).val(prj_type).prop('checked', true);
            $('#prj_date_start').val(data[5]);
            $('#prj_due_date').val(data[6]);

            var location = data[7];
            var prj_location="";
            if(location ==='Abu Dhabi')
            {prj_location='AD';}
            else if (location ==='Ras al Khaimah')
            {prj_location='rak';}
            else if (location ==='Umm al Quwain')
            {prj_location='uaq';}
            else{prj_location=location;}

            $('#'+prj_location).val(prj_location).prop('checked', true);
            $('#prj_loc_desc').val(data[8]);
            $('#prj_client_name').val(data[9]);
            $('#prj_main_contractor').val(data[10]);
            $('#prj_consultant').val(data[11]);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>