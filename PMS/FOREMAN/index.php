<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
error_reporting(0);
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
$query_run2=mysqli_query($connection,$sql);
$row2 = mysqli_fetch_assoc($query_run2);
$user_id = $row2['USER_ID'];
$query="SELECT * FROM asgn_emp_to_prj WHERE Asgd_Emp_to_Prj_Status=1 and User_ID='$user_id'";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card-body">
        <h5 class="mb-4">Projects Assigned</h5>
        <div class="col-4">
        <?php
            if(mysqli_num_rows($query_run)>0)
            {
                while($row = mysqli_fetch_assoc($query_run))
                { 
                    $prj_id= $row['Prj_Id'];
                    $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
                    $run=mysqli_query($connection, $q_prj_name);
                    $row3 = mysqli_fetch_assoc($run);

                    if($row3['Prj_Category']=='Villa'){
                      ?>
                      <form action="activity.php" method="POST">
                          <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                          <button type="submit" name="actBtn" class="btn btn-primary mb-2 btn-block"><?php echo $row3['Prj_Code'].' - '.$row3['Prj_Name']?></button><br>
                      </form>
                    <?php
                    }
                    else{
                      ?>
                      <form action="activity_b.php" method="POST">
                          <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                          <button type="submit" name="actBtn" class="btn btn-primary mb-2 btn-block"><?php echo $row3['Prj_Code'].' - '.$row3['Prj_Name']?></button><br>
                      </form>
                    <?php
                    }
                    
                }
            }
            else
            {
                echo "No Record Found";
            }       
        ?>
        </div>
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
             $query = "SELECT * FROM project as p LEFT JOIN asgn_emp_to_prj as ass_e on ass_e.Prj_Id = p.Prj_Id WHERE p.Prj_Status =1 and ass_e.User_Id='$user_id' GROUP BY p.Prj_Id";
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
                 $q_pm ="SELECT * FROM `asgn_emp_to_prj` LEFT JOIN employee on employee.EMP_ID = asgn_emp_to_prj.Emp_Id LEFT JOIN users on users.USER_ID = asgn_emp_to_prj.User_Id WHERE asgn_emp_to_prj.Asgd_Emp_to_Prj_Status = 1 AND employee.EMP_STATUS = 1 AND users.USER_STATUS= 1 AND users.USERTYPE='proj_mgr' and asgn_emp_to_prj.Prj_Id='$prj_id' GROUP BY asgn_emp_to_prj.Prj_Id";
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
                 <td><?php echo $names?></td>
                 <td><?php echo $status?></td>
                 <td class="">
                   <form action="r_chart.php" method="POST">
                     <!-- project chart -->
                     <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id'];?>">
                     <button type="submit" name ="reportBtn" class="btn btn-primary mr-2">
                       Progress <i class="fa fa-line-chart" aria-hidden="true"></i>
                     </button>
                   </form>
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
                   echo "<font color='red'>Warning: No projects assigned to this user.</font><br>";
               }
           ?> 
             </tbody>
           </table>
         </div>
    </div>
</div>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>