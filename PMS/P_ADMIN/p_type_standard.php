<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
include('../FOREMAN/function.php');
if(isset($_POST['manageType']) or isset($_GET['type_id'])){
    if(isset($_POST['manageType'])){
        $type_id=$_POST['ftype_id'];
    }
    else{
        $type_id=$_GET['type_id'];
    }
    $q_type="SELECT * FROM flat_type WHERE Flat_Type_Id='$type_id'";
    $q_type_run=mysqli_query($connection,$q_type);
    if(mysqli_num_rows($q_type_run)>0){
        $row_t=mysqli_fetch_assoc($q_type_run);
        $type_name=$row_t['Flat_Type_Code'].' '.$row_t['Flat_Type_Name'];
        $prj_id=$row_t['Prj_Id'];
        $flat_ids=getFlatIds($prj_id);
    }
    // query project details
    $q_p="SELECT * FROM project WHERE Prj_Id='$prj_id' ";
    $q_p_run=mysqli_query($connection,$q_p);
    if(mysqli_num_rows($q_p_run)>0){
        while($row_p=mysqli_fetch_assoc($q_p_run)){
            $prj_name=$row_p['Prj_Code'].' '.$row_p['Prj_Name'];
            $prj_type=$row_p['Prj_Category'];
        }
    }
    if($prj_type=='Villa'){
        $name="Villa"; $type="villa";
    }
    else{
        $name="Flat"; $type="building";
    }
}
function decFormat($value) {
    $pattern = '/\.\d{3,}/'; // Regular expression pattern
  
    if (preg_match($pattern, $value)) {
      $value=round($value, 2);
      return $value; // More than two decimal points found
    }
  
    return $value; // Less than or equal to two decimal points
}
?>
<input type="hidden" id="ftype_id" value="<?PHP echo $type_id;?>">
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="m-0 font-weight-bold text-primary"><?php echo $type_name;?></h5>
                        <h4 class="ml-2 mt-2"><?php  echo "<a class='text-secondary' href='p_flt_type.php?prj_id=".$prj_id."'>".$prj_name."</a><br>";?></h4>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <?php 
                        $q_types="SELECT * FROM flat_type WHERE Prj_Id='$prj_id' AND Flat_Type_Id!='$type_id' AND Flat_Type_Status=1";
                        $q_types_run=mysqli_query($connection,$q_types);
                        if(mysqli_num_rows($q_types_run)>0){
                            while($row_=mysqli_fetch_assoc($q_types_run)){
                                $ltype_id=$row_['Flat_Type_Id'];
                                $fname=$row_['Flat_Type_Name'];
                                echo "<a href='p_type_standard.php?type_id=".$ltype_id."'>".$fname."</a><br>";
                            }
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
    // count activities assigned to the type
    $q_activities="SELECT * FROM flat_type_asgn_act WHERE Flat_Type_Id='$type_id' AND Flt_Asgn_Act_Status=1";
    $q_activities_run=mysqli_query($connection,$q_activities);
    $act_cnt=mysqli_num_rows($q_activities_run);
    //count flats in type
    $q_ctn_f="SELECT * FROM flat_asgn_to_type WHERE Flat_Asgd_Status=1 AND Flat_Type_Id='$type_id'";
    $q_ctn_f_run=mysqli_query($connection,$q_ctn_f);
    $flat_cnt=mysqli_num_rows($q_ctn_f_run);
    // echo $act_cnt.' '.$flat_cnt;
    $supposed_tot=$act_cnt*$flat_cnt;
    //select count assigned activities per flat
    $q_flats="SELECT * FROM flat_asgn_to_type as flt_asgn
                LEFT JOIN flat as flt on flt.Flat_Id=flt_asgn.Flat_Id
                LEFT JOIN assigned_activity as as_act on flt.Flat_Id =as_act.Flat_Id
                WHERE flt_asgn.Flat_Type_Id='$type_id' AND flt_asgn.Flat_Asgd_Status=1 AND flt.Flat_Status=1
                    AND as_act.Asgd_Act_Status=1";
    $q_flats_run=mysqli_query($connection,$q_flats);
    $tot_asg=mysqli_num_rows($q_flats_run);
    // echo $tot_asg;
    if($supposed_tot==$tot_asg){
        $r_btn="d-none";
    }
    elseif($supposed_tot>$tot_asg){ // show refresh btn
        $r_btn="";
    }
    elseif($supposed_tot<$tot_asg){ // contact Sys Admin
        echo "System Error ";
        $r_btn="d-none";
    }
?>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="mt-2 text-primary"><?php echo  $name.'s'?></h4>
                            </div>

                            <div class="col-4">
                                <form action="code.php" method="post">
                                    <input type="hidden" name="type_id" value="<?php echo $type_id;?>">
                                    <input type="hidden" name="prj_type" value="<?php echo $type;?>" >
                                    <button type="submit" name="refresh" class="btn btn-success <?php echo $r_btn;?>">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                        Refresh
                                    </button>
                                </form>
                                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addFlt">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Add <?php echo $name;?>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" >
                                    <thead>
                                        <th class="d-none"></th>
                                        <th>#</th>
                                        <th><?php echo $name;?> </th>
                                        <th>No. Activity Assigned</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $q_flat="SELECT * FROM flat_asgn_to_type as ft
                                                LEFT JOIN  flat as f on f.Flat_Id=ft.Flat_Id
                                                WHERE ft.Flat_Type_Id='$type_id' AND ft.Flat_Asgd_Status=1 AND f.Flat_Status=1";
                                        $q_flat_run=mysqli_query($connection,$q_flat);
                                        if(mysqli_num_rows($q_flat_run)>0){
                                            $no=1;
                                            while($row_f=mysqli_fetch_assoc($q_flat_run)){
                                                $fa_id=$row_f['Flat_Assigned_Id'];
                                                $flt_id=$row_f['Flat_Id'];
                                                $flat_name=$row_f['Flat_Code'].' '.$row_f['Flat_Name'];
                                                //assgined activity
                                                
                                                $q_asgn="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1";
                                                $q_asgn_run=mysqli_query($connection,$q_asgn);
                                                $act_no=mysqli_num_rows($q_asgn_run)
                                    ?>
                                        <tr>
                                            <td class="d-none"></td>
                                            <td><?php echo $no;?></td>
                                            <td><?php echo $flat_name;?></td>
                                            <td><?php echo $act_no;?></td>
                                            <td class="">
                                                <!-- <form action="code.php" method="post">
                                                    <input type="hidden" name="ftype_id" value="< ?php echo $type_id;?>">
                                                    <input type="hidden" name="fa_id" value="< ?php echo $fa_id;?>">
                                                    <input type="hidden" name="flt_id[]" value="< ?php echo $flt_id;?>">
                                                    <button type="submit" name="refresh" class="btn btn-success mr-2">
                                                        <i class="fa fa-refresh" area-hidden="true"></i>
                                                    </button>
                                                </form> -->
                                                    <input type="hidden" name="ftype_id" value="<?php echo $type_id;?>">
                                                    <input type="hidden" name="fa_id" value="<?php echo $fa_id;?>">
                                                    <button type="submit" name="delFltAsgn" id="" class="btn btn-danger warningPrompt">
                                                        <i class="fa fa-trash" area-hidden="true"></i>
                                                    </button>
                                                    <input type="hidden" name="flt_id" value="<?php echo $flt_id;?>">
                                            </td>
                                        </tr>
                                    <?php 
                                            $no++;
                                            }
                                        }
                                    ?>
                                        <tfoot>
                                            <tr>
                                                <td class="d-none"></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="mt-2 text-primary">Activities</h4>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addAct">
                                    <i class="fa fa-plus" aria-hidden="true"></i>Add Activties
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <form action="code.php" method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th class="d-none">Act Id</th>
                                        <th>#</th>
                                        <th>Act Name</th>
                                        <th>Budget</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $q_act="SELECT * FROM flat_type_asgn_act as fa
                                                LEFT JOIN activity as act on act.Act_Id=fa.Act_Id
                                                WHERE fa.Flat_Type_Id='$type_id' AND fa.Flt_Asgn_Act_Status=1 AND act.Act_Status=1";
                                        $q_act_run=mysqli_query($connection,$q_act);
                                        if(mysqli_num_rows($q_act_run)>0){
                                            $no=1;  $total_bgt=0;
                                            while($row_act=mysqli_fetch_assoc($q_act_run)){
                                                $act_asgn_id=$row_act['Flt_Asgn_Act_Id'];
                                                $act_id=$row_act['Act_Id'];
                                                $act_name=$row_act['Act_Code'].' '.$row_act['Act_Name'];
                                                $act_bgt=$row_act['Flat_Bgt_Manpower'];//bgt
                                                $act_bgt=decFormat($act_bgt);
                                                $total_bgt=$total_bgt+$act_bgt;
                                        ?>
                                            <tr>
                                                <td class="d-none"></td>
                                                <td><?php echo $no;?></td>
                                                <td><?php echo $act_name;?></td>
                                                <td>
                                                    <input type="text" name="act_bgt[]" class="form-control" value="<?php echo $act_bgt;?>">
                                                    <input type="hidden" name="act_asgn_id_u[]" value="<?php echo $act_asgn_id;?>">
                                                </td>
                                                <td>
                                                    <form action="code.php" method="post">
                                                        <input type="hidden" name="ftype_id" value="<?php echo $type_id;?>">
                                                        <input type="hidden" name="act_asgn_id[]" value="<?php echo $act_asgn_id;?>">
                                                        <button type="button" name="delAct" class="btn btn-danger delAct">
                                                            <i class="fa fa-trash" area-hidden="true"></i>
                                                        </button>
                                                        <input type="hidden" name="act_id[]" value="<?php echo $act_id;?>">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php 
                                            $no++;
                                            }
                                        }
                                    ?>
                                        <tfoot>
                                            <tr>
                                                <td class="d-none"></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $total_bgt;?></td> 
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </tbody>
                                </table>
                                <button class="btn btn-success float-right" name="updateBgt" type="submit">Save</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Project -->
<div class="modal fade bd-example-modal-lg" id="addFlt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building mr-2" aria-hidden="true"></i>Add <?php echo $name;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="post">
      <div class="modal-body">
            <div class="form-group">
                <label class="font-weight-bold" for=""><?php echo $name;?></label>
                <select name="flat_id[]" id="" class="form-control selectpicker mb-4 pb-4" data-live-search="true" multiple required>
                    <?php 
                     $q_flat_asgn="SELECT * FROM flat_asgn_to_type 
                                    WHERE Flat_Asgd_Status=1 AND Flat_Id IN ('$flat_ids')";
                    $q_flat_asgn_run=mysqli_query($connection,$q_flat_asgn);
                    if(mysqli_num_rows($q_flat_asgn_run)>0){
                        $flt_cnt=0;
                        while($row_f=mysqli_fetch_assoc($q_flat_asgn_run)){
                            $flat_arr[]=$row_f['Flat_Id']; //
                            $flt_cnt++;
                        }
                        $asgn_ids = implode("', '", $flat_arr); 
                        $f1="SELECT * FROM flat WHERE Flat_Id IN ('$flat_ids') AND Flat_Id NOT IN ('$asgn_ids')";
                        $f1_run=mysqli_query($connection,$f1);
                        if(mysqli_num_rows($f1_run)>0){
                            echo "<option value='all'>Add All Remaining $name</option>";
                            while($row_f1=mysqli_fetch_assoc($f1_run)){
                                $flat_id=$row_f1['Flat_Id'];
                                $flat_name=$row_f1['Flat_Code'].' '.$row_f1['Flat_Name'];
                                echo "<option value='$flat_id'>$flat_name</option>";
                            }
                        }
                        else{
                            $btn_class="d-none";
                            echo "<option value=''>All $name were Assigned</option>";
                        }
                    } 
                    ?>
                </select>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <input type="hidden" name="type_id" value="<?php echo $type_id;?>">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="add_flt" class="btn btn-success <?php echo $btn_class;?>"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->

<!-- Modal Add Act -->
<div class="modal fade bd-example-modal-lg" id="addAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building mr-2" aria-hidden="true"></i>Add Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post">
      <div class="modal-body">
            <div class="form-group">
                <label class="font-weight-bold" for=""><?php echo $name;?></label>
                <select name="act_id[]" id="" class="form-control selectpicker mb-4 pb-4" data-live-search="true" multiple required>
                    <option value="all">All <?php echo $prj_type.' Activities'; ?></option>
                    <?php 
                        //assigned activities
                        $q_act="SELECT * FROM flat_type_asgn_act WHERE Flt_Asgn_Act_Status=1 AND Flat_Type_Id='$type_id'";
                        $q_act_run=mysqli_query($connection,$q_act);
                        if(mysqli_num_rows($q_act_run)>0){
                            while($row_as_act=mysqli_fetch_array($q_act_run)){
                                $act_arr[]=$row_as_act['Act_Id'];
                            }
                            $asgn_act=implode("',' ",$act_arr);
                        }
                        $q_options="SELECT * FROM activity WHERE Act_Category='$type' AND Act_Status=1 AND Act_Id NOT IN ('$asgn_act')";
                        $q_options_run=mysqli_query($connection,$q_options);
                        if(mysqli_num_rows($q_options_run)>0){
                            while($row_opt=mysqli_fetch_assoc($q_options_run)){
                                $act_id=$row_opt['Act_Id'];
                                $act_name=$row_opt['Act_Code'].' '.$row_opt['Act_Name'];
                                ?>
                                    <option value='<?php echo $act_id;?>'><?php echo $act_name;?></option>";
                                <?php
                            }
                        }
                    ?>
                </select>
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <input type="hidden" name="type_id" value="<?php echo $type_id;?>">
            <input type="hidden" name="prj_type" value="<?php echo $type;?>" >
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="add_act" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Act -->
<!-- Delete Modal Act -->
<div class="modal fade bd-example-modal-lg" id="delFlt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Are you sure?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post">
      <div class="modal-body">
        <div id="delFltBody">
            
        </div>
        </div>
            <input type="hidden" name="act_asgn_id" id="act_asgn_id">
            <input type="hidden" name="act_id" id="act_id">
            <input type="hidden" name="fa_id" id="fa_id">
            <input type="hidden" name="flt_id" id="flat_id">
            <input type="hidden" name="ftype_id" value="<?php echo $type_id;?>">
        <div class="modal-footer">
            <input type="hidden" name="prj_type" value="<?php echo $type;?>" >
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
            <button type="submit" name="delFltAsgn" id="delFltAsgn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
            <button type="submit" name="delAct" id="delAct" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function (){
    $('.warningPrompt').on('click', function() {
        var flt_id=$(this).next().val();
        var type_id=$('#ftype_id').val();
        var fa_id= $(this).prev().val();
        $('#delAct').addClass('d-none');
        $('#delFltAsgn').removeClass('d-none');

        $.ajax({
            url: 'code.php',
            method: 'POST',
            data:{
                'flt_id': flt_id,
                'type_id':type_id,
                'fa_id': fa_id,
            },
            success:function(data){
                if(data=='success'){
                    alert('record deleted');
                    location.reload();
                }
                else if(data=='error'){
                    alert('error deleting');
                    location.reload();
                }
                else{
                    if(data>1){
                        var message ='There are '+data+' existing records in this Flat.'; 
                    }
                    else{
                        var message ='There is 1 existing record in this Flat.';
                    }
                    $('#flat_id').val(flt_id);
                    $('#fa_id').val(fa_id);
                    $('#delFltBody').html(message).change();
                    $('#delFlt').modal('show');
                }
            }
        });
    });
});
$(document).ready(function (){
    $('.delAct').on('click', function() {
        var act_id=$(this).next().val();
        var act_asgn_id=$(this).prev().val();
        var type_id=$('#ftype_id').val();
        $('#delFltAsgn').addClass('d-none');
        $('#delAct').removeClass('d-none');
        $.ajax({
            url: 'code.php',
            method: 'POST',
            data:{
                'act_id': act_id,
                'ttype_id':type_id,
                'act_asgn_id': act_asgn_id,
            },
            success:function(data){
                if(data=='success'){
                    alert('record deleted');
                    location.reload();
                }
                else if(data=='error'){
                    alert('error deleting');
                    location.reload();
                }
                else{
                    if(data>1){
                        var message ='There are '+data+' existing records in this Flat.'; 
                    }
                    else{
                        var message ='There is 1 existing record in this Flat.';
                    }
                    $('#act_asgn_id').val(act_asgn_id);
                    $('#act_id').val(act_id);
                    $('#delFltBody').html(message).change();
                    $('#delFlt').modal('show');
                }
            }
        });
    });
});
</script>
<?php 
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>