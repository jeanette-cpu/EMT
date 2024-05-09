<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
error_reporting(0);
$add_form=null;
if(isset($_POST['prj_id']) or isset($_GET['id'])){
    if(isset($_POST['prj_id'])){
        $prj_id = $_POST['prj_id'];
    }
    else{
        $prj_id = $_GET['id'];
    }
    $q_prj="SELECT * FROM project WHERE Prj_Id='$prj_id'"; //project name
    $q_prj_run=mysqli_query($connection,$q_prj);
    if(mysqli_num_rows($q_prj_run)>0){
        $row_p=mysqli_fetch_assoc($q_prj_run);
        $prj_code=$row_p['Prj_Code'];
        $prj_name=$row_p['Prj_Name'];
        $prj_fname=$prj_code.' '.$prj_name;
    }
    $already_set_act="SELECT * FROM activity_standard WHERE Prj_Id='$prj_id' AND Act_Standard_Status=1";
    $already_set_act_run=mysqli_query($connection,$already_set_act); $asgn_ids[]=[];
    if(mysqli_num_rows($already_set_act_run)>0){
        $add_form.="
            <label class='font-weight-bold text-primary'>Assigned Activity</label>
        <div class='table-responsive'>
            <table class='table table-bordered table-striped' width='100%' cellspacing='0' id='asgnAct'>
                <thead>
                    <th width='8%'>Code</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Category</th>
                    <th>Employee</th>
                    <th>Output</th>
                </thead>
                <tbody>
        ";
        while($row_a=mysqli_fetch_assoc($already_set_act_run)){
            $standard_id=$row_a['Act_Standard_Id'];
            $act_id=$row_a['Act_Id'];
            $asgn_ids[]=$act_id;
            // $a_emp_r=floor($row_a['Act_Standard_Emp_Ratio']);//already saved emp ratio
            // $a_output_r=floor($row_a['Act_Standard_Output_Ratio']);
            $a_emp_r=$row_a['Act_Standard_Emp_Ratio'];//already saved emp ratio
            $a_output_r=$row_a['Act_Standard_Output_Ratio'];
            $q_act="SELECT * FROM activity 
                    WHERE Act_Id='$act_id'";
            $q_act_run=mysqli_query($connection,$q_act);
            if(mysqli_num_rows($q_act_run)>0){
                $row_act=mysqli_fetch_assoc($q_act_run);
                $act_code=$row_act['Act_Code'];
                $act_name=$row_act['Act_Name'];
                // $act_fname=$act_code.' '.$act_name;

                $dept_id = $row_act['Dept_Id'];
                $query2 = "SELECT Dept_Name from department WHERE Dept_Id='$dept_id'";
                $query_run2=mysqli_query($connection,$query2);
                $row2 = mysqli_fetch_assoc($query_run2);
                $dept_name=$row2['Dept_Name'];

                $act_cat_id = $row_act['Act_Cat_Id'];
                $query3 = "SELECT Act_Cat_Name from activity_category WHERE Act_Cat_Id='$act_cat_id'";
                $query_run3=mysqli_query($connection,$query3);
                $row3 = mysqli_fetch_assoc($query_run3);
                $cat_name=$row3['Act_Cat_Name'];
            }
            
            $add_form.="
                <tr>
                    <td>$act_code</td>
                    <td>
                        <input name='e_act_id[]' class='form-control' type='hidden' value='$standard_id' required>
                        $act_name
                    </td>
                    <td>$dept_name</td>
                    <td>$cat_name</td>
                    <td>
                        <input name='e_emp_r[]' class='form-control' type='decimal' value='$a_emp_r' required>
                    </td>
                    <td>
                        <input name='e_output_r[]' class='form-control' type='decimal' value='$a_output_r' required>
                    </td>
                </tr>";
        }
        $add_form.="</tbody>
                    </table>
                </div>";
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
                    <input id='asgnAll' type='checkbox' class='useDefault  mt-1' name='' value='1'>
                    <label class='ml-4 mr-2'>Clear All</label>
                    <input id='clearAll' type='checkbox' class='useDefault  mt-1 ' name='' value='1'>
                </div>
            </div>
        <div class='table-responsive'>
            <table class='table table-bordered table-striped' width='100%' cellspacing='0' id='actToAsgn'>
                <thead>
                    <th width='8%'>Code</th>
                    <th width='45%'>Name</th>
                    <th>Department</th>
                    <th>Category</th>
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

                    $dept_id = $row_act['Dept_Id'];
                    $query2 = "SELECT Dept_Name from department WHERE Dept_Id='$dept_id'";
                    $query_run2=mysqli_query($connection,$query2);
                    $row2 = mysqli_fetch_assoc($query_run2);
                    $dept_name=$row2['Dept_Name'];

                    $act_cat_id = $row_act['Act_Cat_Id'];
                    $query3 = "SELECT Act_Cat_Name from activity_category WHERE Act_Cat_Id='$act_cat_id'";
                    $query_run3=mysqli_query($connection,$query3);
                    $row3 = mysqli_fetch_assoc($query_run3);
                    $cat_name=$row3['Act_Cat_Name'];
                }
                $add_form.='
                <tr>
                    <td>'.$act_code.'</td>
                    <td>'.$act_name.'</td>
                    <td>'.$dept_name.'</td>
                    <td>'.$cat_name.'</td>
                    <td>
                        <input id="'.$act_id.'emp_rc" name="emp_r[]" class="form-control clear_asgn" type="decimal" required>
                    </td>
                    <td>
                        <input id="'.$act_id.'output_rc" name="output_r[]" class="form-control clear_asgn" type="decimal" required>
                    </td>
                    <td>
                        <input id="'.$act_id.'emp_r" name="def_emp" value="'.$s_emp_r.'" type="hidden">
                        <input id="'.$act_id.'output_r" name="def_output" value="'.$s_output_r.'" type="hidden">
                        '.$label_output.'
                    </td>
                    <td>
                        <input name="act_id[]" class="form-control act_ids" type="hidden" value="'.$act_id.'" required>
                        <input type="checkbox" id="'.$act_id.'" class="useDefaultS '.$chk_box_visb.'" name="" value="">
                    </td>
                </tr>';
            }
            $add_form.="</tbody>
                    </table>
                </div>";
       }
    }
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold "><span class="text-primary">Activity Standard:</span> <span><?php echo $prj_fname;?></span> </h5>
        </div>
        <div class="card-body">
            <form action="code.php" method="post">
                <input type="hidden" name="prj_id" value="<?php echo $prj_id; ?>">
                <?php 
                    echo $add_form;
                ?>
                <button type="submit" name="asgn_standard_after" class="btn btn-success mt-2 float-right"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $(document).find('#actToAsgn').DataTable({
        "searching": true,
        "bPaginate": false
    });
    $(document).find('#asgnAct').DataTable({
        "searching": true,
        "bPaginate": false
    });
});
$(document).ready(function () {
    $('#asgnAll').on('click', function() {
        $('.act_ids').each(function(){
            act_id=$(this).val();
            var emp_r_id=act_id+'emp_r';
            var output_r_id=act_id+'output_r';
            var def_empr=$(document).find('#'+emp_r_id).val();
            var def_outputr=$('#'+output_r_id).val();

            var c_emp=emp_r_id+'c';
            var c_output=output_r_id+'c';

            $(document).find('#'+c_emp).val(def_empr).change();
            $('#'+c_output).val(def_outputr).change();

        });
        document.getElementById('asgnAll').setAttribute('checked', 'checked');
    });
});
$(document).ready(function () {
    $('.useDefaultS').on('change', function() {
    // set default
    // find the nearest input 
    var act_id = $(this).prevAll('input').val();

    var emp_r_id=act_id+'emp_r';
    var output_r_id=act_id+'output_r';
    var def_empr=$(document).find('#'+emp_r_id).val();
    var def_outputr=$('#'+output_r_id).val();
    var c_emp=emp_r_id+'c';
    var c_output=output_r_id+'c';

    $(document).find('#'+c_emp).val(def_empr).change();
    $('#'+c_output).val(def_outputr).change();
    });
});
$(document).ready(function () {
    $('#clearAll').on('change', function() {
        var elements = document.getElementsByClassName("clear_asgn");
        for (var i = 0; i < elements.length; i++) {
            elements[i].value = ""
        }
    });
});

</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>