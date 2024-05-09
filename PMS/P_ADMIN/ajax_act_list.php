<?php
include('../../security.php');
error_reporting(0);
if(isset($_POST['flt_id']))
{
    $flt_id = $_POST['flt_id'];
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th>Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center ">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
if(isset($_POST['dept_id']))
{
    $dept_id = $_POST['dept_id'];
    if(isset($_POST['blg_id'])){
        $blg_id=$_POST['blg_id'];
        $q_flat="SELECT f.Flat_Id FROM flat as f 
        LEFT JOIN level as l on l.Lvl_Id = f.Lvl_Id
        LEFT JOIN building as blg on blg.Blg_Id = l.Blg_Id
        WHERE blg.Blg_Id='$blg_id'";
        $blg_id_run=mysqli_query($connection,$q_flat);
        $row_f = mysqli_fetch_assoc($blg_id_run);
        $flt_id=$row_f['Flat_Id'];
    }
    else{
    $flt_id = $_POST['flatt_id'];
    }
     // get all activity category
     $cat_id_q = "SELECT Act_Cat_Id FROM activity_category WHERE Act_Cat_Status=1 AND Dept_Id='$dept_id'";
     $cat_id_q_run = mysqli_query($connection, $cat_id_q);
     if(mysqli_num_rows($cat_id_q_run)>0)
     {
         while($row_id = mysqli_fetch_assoc($cat_id_q_run))
         {
             $id_arr[] = $row_id['Act_Cat_Id'];
         }
         $act_cat_id=implode("', '", $id_arr);
     }
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1 and Act_Cat_Id in ('$act_cat_id')";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th >Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
if(isset($_POST['act_cat_id']))
{
    $act_cat = $_POST['act_cat_id'];

    $flt_id = $_POST['flat_id'];
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1 and Act_Cat_Id ='$act_cat'";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th >Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center ">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
if(isset($_POST['prj_id'])){
    $prj_id=$_POST['prj_id'];
    $q_prj="SELECT * FROM project WHERE Prj_Id='$prj_id'"; //project name
    $q_prj_run=mysqli_query($connection,$q_prj);
    if(mysqli_num_rows($q_prj_run)>0){
        $row_p=mysqli_fetch_assoc($q_prj_run);
        $prj_code=$row_p['Prj_Code'];
        $prj_name=$row_p['Prj_Name'];
        $prj_fname=$prj_code.' '.$prj_name;
        $add_form.='
        <div class="form-group">
            <input name="prj_id" value="'.$prj_id.'" type="hidden">
            <label class="font-weight-bold">Project:</label> ' .$prj_fname.'
        </div>
        ';
    }
    $already_set_act="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Standard_Status=1";
    $already_set_act_run=mysqli_query($connection,$already_set_act);
    if(mysqli_num_rows($already_set_act_run)>0){
        $add_form.="
            <label class='font-weight-bold text-primary'>Assigned Activity</label>
            <table class='table table-bordered'>
                <thead>
                    <th width='65%'>Activity</th>
                    <th>Employee</th>
                    <th>Output</th>
                </thead>
                <tbody>
        ";
        while($row_a=mysqli_fetch_assoc($already_set_act_run)){
            $standard_id=$row_a['Act_Standard_Id'];
            $act_id=$row_a['Act_Id'];
            $asgn_ids[]=$act_id;
            $a_emp_r=floor($row_a['Act_Standard_Emp_Ratio']);//already saved emp ratio
            $a_output_r=floor($row_a['Act_Standard_Output_Ratio']);
            $q_act="SELECT * FROM activity 
                    WHERE Act_Id='$act_id'";
            $q_act_run=mysqli_query($connection,$q_act);
            if(mysqli_num_rows($q_act_run)>0){
                $row_act=mysqli_fetch_assoc($q_act_run);
                $act_code=$row_act['Act_Code'];
                $act_name=$row_act['Act_Name'];
                $act_fname=$act_code.' '.$act_name;
            }
            $add_form.="
                <tr>
                    <td>
                        <input name='e_act_id[]' class='form-control' type='hidden' value='$standard_id' required>
                        $act_fname
                    </td>
                    <td>
                        <input name='e_emp_r[]' class='form-control' type='number' value='$a_emp_r' required>
                    </td>
                    <td>
                        <input name='e_output_r[]' class='form-control' type='number' value='$a_output_r' required>
                    </td>
                </tr>";
        }
        $add_form.="</tbody>
                    </table>";
    }
   //TO BE ASSIGNED
    if($asgn_ids){
       $asgn_ids=implode("', '", $asgn_ids);
       $q_unassigned_act="SELECT * FROM activity WHERE Act_Id NOT IN ('$asgn_ids') AND Act_Status=1";
       
    }
    elseif($asgn_ids==NULL){
        $q_unassigned_act="SELECT * FROM activity WHERE Act_Status=1";
    }
    else{ // NOTHING ASSIGNED AT ALL
        $q_unassigned_act=NULL;
    }
    if($q_unassigned_act){
        $q_unassigned_act_run=mysqli_query($connection,$q_unassigned_act);
       if(mysqli_num_rows($q_unassigned_act_run)>0){
            $add_form.="
            <div class='form-row'>
                <div class='col-6'>
                    <label class='font-weight-bold text-primary'>Activities To Assign</label>
                </div>
                <div class='col-6'>
                    <label class='mr-2'>Assign default value for all</label>
                    <input id='asgnAll' type='checkbox' class='useDefault  mt-1' name='' value='0'>
                </div>
            </div>
            <table class='table table-bordered'>
                <thead>
                    <th width='65%'>Activity</th>
                    <th>Employee</th>
                    <th>Output</th>
                    <th>Default</th>
                    <th>Use Default</th>
                </thead>
                <tbody>";
            while($row_un=mysqli_fetch_assoc($q_unassigned_act_run)){
                $act_id=$row_un['Act_Id'];
                $s_emp_r=$row_un['Act_Emp_Ratio'];// standard for this act
                $s_output_r=$row_un['Act_Output_Ratio'];
                if($s_emp_r!=NULL && $s_output_r!=NULL){
                    $s_emp_r=floor($s_emp_r);
                    $s_output_r=floor($s_output_r);
                    $label_output=$s_emp_r.':'.$s_output_r;
                    $chk_box_visb='';
                }
                else{
                    $label_output=null;
                    $chk_box_visb='d-none';
                }
                $q_act="SELECT * FROM activity WHERE Act_Id='$act_id'";
                $q_act_run=mysqli_query($connection,$q_act);
                if(mysqli_num_rows($q_act_run)>0){
                    $row_act=mysqli_fetch_assoc($q_act_run);
                    $act_code=$row_act['Act_Code'];
                    $act_name=$row_act['Act_Name'];
                    $act_fname=$act_code.' '.$act_name;
                }
                $add_form.="
                <tr>
                    <td> $act_fname</td>
                    <td>
                        <input id='$act_id.emp_rc' name='emp_r[]' class='form-control' type='number' required>
                    </td>
                    <td>
                        <input id='$act_id.output_rc' name='output_r[]' class='form-control' type='number' required>
                    </td>
                    <td>
                        <input id='$act_id.emp_r' name='def_emp' value='$s_emp_r' type='hidden'>
                        <input id='$act_id.output_r' name='def_output' value='$s_output_r' type='hidden'>
                        $label_output
                    </td>
                    <td>
                        <input name='act_id[]' class='form-control act_ids' type='hidden' value='$act_id' required>
                        <input type='checkbox' id='$act_id' class='useDefaultS $chk_box_visb' name='' value='' onclick='useDefaultS()'>
                    </td>
                </tr>";
            }
            $add_form.="</tbody>
                    </table>";
       }
    }
    echo $add_form;
}

?>
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
    
    function useDefault(){ //assign all default
    //find all ids
    //loop ids
    console.log('emp_r_id');
    // $('.comp_ids').each(function(){
    //          comp_id=$(this).val();
    //          mat_pid= $(this).nextAll('input').val();
    //          mat_post_id.push([comp_id,mat_pid]);
    //     });

}
</script>