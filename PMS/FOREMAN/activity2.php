<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
error_reporting(0);
date_default_timezone_set('Asia/Dubai');
$Date = date('Y-m-d');

$username = $_SESSION['USERNAME'];
$query = "SELECT * FROM users WHERE USERNAME='$username'";
$query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id = $row['USER_ID'];

if(isset($_POST['prj_id']) or isset($_GET['prj_id'])){
    if(isset($_POST['prj_id'])){
        $prj_id=$_POST['prj_id'];
    }
    elseif(isset($_GET['prj_id'])){
        $prj_id=$_GET['prj_id'];
    }
    else{
        echo 'Project Id not found';
    }
    if($prj_id){
        $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
        $run=mysqli_query($connection, $q_prj_name);
        $row3 = mysqli_fetch_assoc($run);

        $prj_name= $row3['Prj_Code'].' - '.$row3['Prj_Name'];
        $prj_cat = $row3['Prj_Category'];
        if($prj_cat=='Villa'){
            $q_v="SELECT * FROM Villa WHERE Prj_Id='$prj_id'";
            $q_v_run=mysqli_query($connection,$q_v);
            if(mysqli_num_rows($q_v_run)>0){
                while($row_v=mysqli_fetch_assoc($q_v_run)){
                    $v_arr[]=$row_v['Villa_Id'];
                }
            }
            $v_ids=implode("', '", $v_arr);
        }
        elseif($prj_cat=='Building'){
            $q_blg="SELECT * FROM building WHERE Prj_Id='$prj_id'";
            $q_blg_run=mysqli_query($connection,$q_blg);
            if(mysqli_num_rows($q_blg_run)>0){
                while($row_b=mysqli_fetch_assoc($q_blg_run)){
                    $blg_arr[]=$row_b['Blg_Id'];
                }
            }
            $blg_ids=implode("', '", $blg_arr);
        }
    }
}   
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
?>
<div class="col-xl-12 col-lg-12">
<form action="code.php" method="post" enctype="multipart/form-data">
    <div class="form-row">
        <div class="col-8">
            <h4><?php echo $prj_name?></h4>
        </div>
        <div class="col-3">
            <label for="">Upload Multiple</label>
            <input type="file" class="" name="file"  required>
        </div>
        <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
        <input type="hidden" name="prj_cat" value="<?php echo $prj_cat;?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id?>">
        <div class="col-1"><br>
            <button class="btn btn-success mt-2" name="submit" type="submit">Upload</button>
        </div>
    </div>
</form>
        <div class="card shadow mb-4 mt-2">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-6">
                        <h5 class="font-weight-bold text-primary">Activities</h5>
                    </div>
                    <div class="col-6">
                        <!-- BUTTON -->
                        <button type="button" name="addB" id="addActBtn" class="btn btn-primary float-right" data-toggle="modal" data-target="#addAct">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                            Add New
                        </button>
                    </div>
                </div>
            </div>
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
                        <th>Villa Fixed</th>
                        <th>Type</th>
                        <th class="d-none"></th>
                        <th>Evaluation</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    <?php
                    //query all inserted activity today
                    $q_act="SELECT * FROM daily_entry2 as de
                            LEFT JOIN activity as act on act.Act_Id=de.Act_Id
                            WHERE de.Prj_Id='$prj_id' AND de.DE_Date_Inserted='$Date' AND de.DE_Status2=1";
                    $q_act_run=mysqli_query($connection,$q_act);
                    if(mysqli_num_rows($q_act_run)>0){
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
                            $output=$row['DE_Output_No'];//output //villa now
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

                            if($emp_no==0 OR $emp_no==NULL){ $emp_no='';}
                            if($sb_no==0 OR $sb_no==NULL){ $sb_no='';}
                            if($mp_no==0 OR $mp_no==NULL){ $mp_no='';}

                            if($prj_cat=='Villa'){ // search for plex name (Villa_Id)
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
                            if(mysqli_num_rows($q_standard_run)>0){ 
                                $row_act_s=mysqli_fetch_assoc($q_standard_run);
                                $eval_text='';
                                // $mp_std=floor($row_act_s['Act_Standard_Emp_Ratio']);
                                // $output_std=floor($row_act_s['Act_Standard_Output_Ratio']); //villa
                                $mp_std=$row_act_s['Act_Standard_Emp_Ratio'];
                                $output_std=$row_act_s['Act_Standard_Output_Ratio']; //villa
                                if($mp_std==NULL || $mp_std==0){
                                    $eval_text='not set';
                                }
                                elseif($output_std==NULL || $output_std==0){
                                    $eval_text='not set';
                                }
                                else{
                                    $output_std = $output_std * $output;
                                    $mp_std = $mp_std * $total_emp;
                                    $lcm = lcm($output,$output_std);
                                    $eval_text='';
                                    $standard=($output_std/$lcm)*$mp_std;
                                    $output_comp=($output/$lcm)*$mp_std;
                                    // echo $standard;
                                    $eval=$output-$standard;

                                    $btn_class='';
                                    //blue (more than standard)
                                    if($standard<$output){ 
                                        $btn_class='btn-info';
                                        $title_hover="exceed by ".$eval.' villa';
                                        if($eval==0){
                                            $title_hover="more than standard";
                                        }
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
                            // else{$eval_text='not set';
                            // }
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
                                </td><!-- EVALUATION-->
                                <td class="btn-group text-center">
                                    <!-- edit -->
                                    <button type="button" class="btn btn-success editBtn">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>
                                    <!-- delete -->
                                    <form action="code.php" method="POST">
                                        <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                                        <input type="hidden" name="DE_Id" id="Del_DE_Id" value="<?php echo $DE_Id;?>">
                                        <button type="submit" name="delDE2" class="btn btn-primary">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    else{
                        echo "Data not found";
                    }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Daily Entry -->
<div class="modal fade bd-example-modal-lg" id="addAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
    <input type="hidden" id="prj_id"  value="<?php echo $prj_id;?>">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Add Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" >
        <!-- THE FORM -->
        <form action="code.php" method="POST" id="addmodal">
        <div class="table-responsive">
            <table class="table pb-3 table-bordered table-striped" width="100%" id="addActTbl" cellspacing="0">
                <thead>
                    <th>Activity</th>
                    <th>Area</th>
                    <th>Emp</th>
                    <th>MP</th>
                    <th>SB</th>
                    <th>Villa Fixed</th>
                    <th>Day Type</th>
                    <th></th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="activity_id[]" id="act_opt" class="form-control selectpicker" data-live-search="true" required></select>
                        </td>
                        <td>
                            <select name="area_id[]" id="area_opt" class="form-control selectpicker" data-live-search="true" required>
                            <?php
                                if($prj_cat=='Building'){
                                    $q_area="SELECT * FROM level WHERE Blg_Id IN ('$blg_ids')";
                                    $q_area_run=mysqli_query($connection,$q_area);
                                    if(mysqli_num_rows($q_area_run)>0){
                                        while($row_a=mysqli_fetch_assoc($q_area_run)){
                                            $lvl_id=$row_a['Lvl_Id'];
                                            $lvl_code=$row_a['Lvl_Code'];
                                            ?>
                                                <option value="<?php echo $lvl_id;?>"><?php echo $lvl_code;?></option>
                                            <?php
                                        }
                                    }
                                }
                                elseif($prj_cat=='Villa'){
                                    $q_area2="SELECT Plx_Code, Plx_Name, Plx_Id FROM plex WHERE Villa_Id in ('$v_ids')";
                                    $q_area_run2=mysqli_query($connection,$q_area2);
                                    if(mysqli_num_rows($q_area_run2)>0){
                                        while($row_a2=mysqli_fetch_assoc($q_area_run2)){
                                            $plx_id=$row_a2['Plx_Id'];
                                            $plx_code=$row_a2['Plx_Code'];
                                            $plx_name=$row_a2['Plx_Name'];
                                            ?>
                                                <option value="<?php echo $plx_id;?>"><?php echo $plx_code.' '.$plx_name;?></option>
                                            <?php
                                        }
                                    }
                                }
                            ?>
                            </select>
                        </td>
                        <td> <input type="decimal" name="emp_no[]" class="form-control"> </td>
                        <td> <input type="decimal" name="mp_no[]" class="form-control"> </td>
                        <td> <input type="decimal" name="sb_no[]" class="form-control"> </td>
                        <td> <input type="decimal" name="output_no[]" class="form-control" required> </td>
                        <td> 
                            <select name="day_type[]" id="" class="form-control " required>
                                <option value="FD">Full Day</option>
                                <option value="HD">Half Day</option>
                                <option value="QD">Quarter Day</option>
                            </select> 
                        </td>
                        <td></td>
                    </tr>

                </tbody>
            </table>
            <div align="right">
                <button type="button" name="add" id="addRow" class="btn btn-success btn-xs">+</button>
            </div>
        </div>            
        <!-- END FORM -->
      </div>
        <div class="modal-footer">
            <input type="hidden" id="" name="prj_id" value="<?php echo $prj_id;?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addDE2" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Daily Entry -->

<!-- Modal Edit Entry -->
<div class="modal fade bd-example-modal-sm" id="editAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Edit Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="code.php" method="POST">
            <input type="hidden" name="de_id" id="de_id" >
            <div class="form-group">
                <div class="form-row">
                    <div class="col-6">
                        <label>Activity</label>
                        <select name="act_id" id="act_id" class="form-control" data-live-search="true" required></select>
                    </div>
                    <div class="col-6">
                          <label>Area</label>
                          <select name="area_id" id="area_id"  class="form-control" data-live-search="true" required></select>
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="col-3">
                        <label>Employee</label>
                        <input type="decimal" name="emp_no" id="emp_no" class="form-control" >
                    </div>
                    <div class="col-3">
                          <label>Manpower</label>
                          <input type="decimal" name="mp_no" id="mp_no" class="form-control" >
                    </div>
                    <div class="col-3">
                          <label>Subcontractor</label>
                          <input type="decimal" name="sb_no" id="sb_no" class="form-control" >
                    </div>
                    <div class="col-3">
                          <label>Villa</label>
                          <input type="decimal" name="output_no" id="output_no" class="form-control" required>
                    </div>
                </div>
                <div class="form-group form-row  mt-2">
                    <div class="col-6">
                        <label>Day Type</label>
                        <select name="day_type" id="day_type" class="form-control" required>
                            <option value="FD">Full Day</option>
                            <option value="HD">Half Day</option>
                            <option value="QD">Quarter Day</option>
                        </select>
                    </div>
                </div>
            </div>
      </div>
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editDE2" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Entry -->

<script>
    //EDIT MODAL PROGRESS
    $('.editBtn').click(function(){
        $('#editAct').modal('show');
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();
            // console.log(data);
            // alert(data);
            $('#de_id').val(data[0]); //de id
            $('#act_id').val(data[1]);//act id
            $('#area_id').val(data[2]);//plex id/ area id
            $('#emp_no').val(data[5]);//emp
            $('#mp_no').val(data[6]);//mp
            $('#sb_no').val(data[7]);//sb
            $('#output_no').val(data[9]);//output
            $('#day_type').val(data[11]);//daytype

    });
$(document).ready(function(){
    var count = 1;
    $('#addRow').click(function(){
        count ++;var dept_id = '';
        var  prj_id=$('#prj_id').val();
        $.ajax({
            url:'ajax.php',
            method: 'POST',
            data:{'dept_id2':dept_id},
            success:function(data){
                var html_code = "<tr id='row"+count+"'>";
                html_code +="<td><select name='activity_id[]' id='act_opt"+count+"' class='form-control selectpicker' data-live-search='true' required></select></td>";
                html_code +="<td><select name='area_id[]' id='area_opt"+count+"' class='form-control selectpicker' data-live-search='true' required></select></td>";
                html_code +="<td> <input type='decimal' name='emp_no[]' class='form-control'> </td>";
                html_code +="<td> <input type='decimal' name='mp_no[]' class='form-control'>";
                html_code +="<td> <input type='decimal' name='sb_no[]' class='form-control'>";
                html_code +="<td> <input type='decimal' name='output_no[]' class='form-control' required> </td>";
                html_code +="<td> <select name='day_type[]' id='' class='form-control' required><option value='FD'>Full Day</option><option value='HD'>Half Day</option><option value='QD'>Quarter Day</option></select> </td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#addActTbl').append(html_code);
                $(document).find('#row'+count+' #act_opt'+count).html(data).change();
                $('.selectpicker').selectpicker('refresh');
                $.ajax({
                    url: 'ajax.php',
                    method: 'POST',
                    data: { 'prj_id': prj_id},
                    success:function(data1){
                        $(document).find('#row'+count+' #area_opt'+count).html(data1).change();
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        });
       
    });
    // delete row
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });
});
//activity options
$(document).ready(function () {   
    var dept_id='';
    $.ajax({
        url:'ajax.php',
        method: 'POST',
        data:{'dept_id2':dept_id},
        success:function(data){
            $('#act_opt').html(data).change();
            $('#act_id').html(data).change();
            $('.selectpicker').selectpicker('refresh');  
        }
    });
});
$(document).ready(function () {
    $('#dataTable').DataTable({
        "searching": true,
        "bPaginate": false
    });
});
$(document).ready(function () {
    var  prj_id=$('#prj_id').val();
    $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: { 'prj_id': prj_id},
        success:function(data1){
            $(document).find('#area_id').html(data1).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>