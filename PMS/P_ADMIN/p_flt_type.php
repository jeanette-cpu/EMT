<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
include('../FOREMAN/function.php');
if(isset($_POST['prj_id']) or isset($_GET['prj_id'])){
    if(isset($_POST['prj_id'])){
        $prj_id=$_POST['prj_id'];
    }
    else{
        $prj_id=$_GET['prj_id'];
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
    $flat_ids=getFlatIds($prj_id);
    if($prj_type=='Villa'){
        $name="Villa"; $type="villa";
    }
    else{
        $name="Flat"; $type="building";
    }
    $query="SELECT * FROM flat_type where Prj_Id='$prj_id' AND Flat_Type_Status=1";
    $query_run = mysqli_query($connection, $query);
    $flat_ids=getFlatIds($prj_id);
    $q_cnt_flts="SELECT * FROM flat WHERE Flat_Id IN ('$flat_ids')";
    $q_cnt_flts_run=mysqli_query($connection,$q_cnt_flts);
    if(mysqli_num_rows($q_cnt_flts_run)>0){
        $flt_nos=mysqli_num_rows($q_cnt_flts_run); 
    }
    else{
        $flt_nos=$name." were not yet Inserted";
    }
    // compute total flat assigned
    $cnt_asgn_flat="SELECT count(ft_asgn.Flat_Assigned_Id) as flat_no  FROM flat_asgn_to_type as ft_asgn
                    LEFT JOIN flat_type as ft on ft.Flat_Type_Id = ft_asgn.Flat_Type_Id 
                            WHERE Flat_Asgd_Status='1' AND Flat_Type_Status='1' AND Prj_Id='$prj_id'";
    $cnt_asgn_flat_run=mysqli_query($connection,$cnt_asgn_flat);
    if(mysqli_num_rows($cnt_asgn_flat_run)>0){
        $row_cnt=mysqli_fetch_assoc($cnt_asgn_flat_run);
        $asgn_flt_nos=$row_cnt['flat_no']; 
    }
    $remain_flt=$flt_nos-$asgn_flt_nos;
    // echo $asgn_flt_nos;
    // compute remaining flat
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary"><?php echo $name;?> Types</h5>
                    <h4 class="ml-2 mt-2"><b><?php echo $prj_name?></b></h4>
                </div>
                <!-- BUTTON -->
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addPlex">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Type
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="card  shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?php echo "Total ".$name." No: "?></div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <h2><?php echo $flt_nos;?></h2>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-fw fa-cube fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card  shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assigned:</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <h2><?php echo $asgn_flt_nos;?></h2>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-fw fa-check-square fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card  shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Remaining:</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <h2><?php echo $remain_flt;?></h2>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-fw fa-square fa-2x text-black-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Type Code</th>
                        <th>Type Name</th>
                        <th>No. of <?php echo $name;?></th>
                        <th>Assigned Activities</th>
                        <!-- <th>Assigned </th> -->
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                    $total_act=0; $total_flat=0;
                        if(mysqli_num_rows($query_run)>0){
                            while($row = mysqli_fetch_assoc($query_run)){
                                $typeId = $row['Flat_Type_Id'];
                                //no. of assigned flats
                                $f_no="SELECT * FROM flat_asgn_to_type WHERE Flat_Type_Id ='$typeId' AND Flat_Asgd_Status='1'";
                                $f_no_run=mysqli_query($connection,$f_no);
                                $flat_no=mysqli_num_rows($f_no_run);
                                $total_flat=$total_flat+$flat_no;
                                //no of assigned activities
                                $act_no="SELECT * FROM flat_type_asgn_act WHERE Flat_Type_Id ='$typeId' AND Flt_Asgn_Act_Status='1'";
                                $act_no_run=mysqli_query($connection,$act_no);
                                $acts_no=mysqli_num_rows($act_no_run);
                                $total_act=$acts_no+$total_act;
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $typeId?></td>
                            <td><?php echo $row['Flat_Type_Code']?></td>
                            <td><?php echo $row['Flat_Type_Name']?></td>
                            <td><?php echo $flat_no;?></td> <!-- flat no-->
                            <td><?php echo $acts_no; ?></td> <!-- acts no-->
                            <td class="btn-group text-center ">
                                <!-- MANAGE -->
                                <form action="p_type_standard.php" method="POST">
                                    <input type="hidden" name="ftype_id" value="<?php echo $typeId;?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name;?>">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                                    <button type="submit" name="manageType" class="btn btn-info mr-2">
                                        <i class="fa fa-cog" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn mr-2" data-toggle="modal" data-target="#editType">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <input type="hidden" name="prj_name" value="<?php echo $prj_name.' '.$row['Plx_Code']?>">
                                <button type="button" name="delType" class="btn btn-danger delType">
                                    <i class="fa fa-trash" area-hidden="true"></i>
                                </button>
                                <input type="hidden" name="ftype_id" value="<?php echo $typeId;?>">

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
                    <tfoot>
                        <tr>
                            <td class="d-none"></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $total_flat;?></td>
                            <td><?php echo $total_act;?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Type -->
<div class="modal fade bd-example-modal-lg" id="addPlex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-th-large" aria-hidden="true"></i> Add <?php echo $name;?> Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <div class="form-group form-row">
            <div class="col-5">
                <label for=""><?php echo $name;?> Type Code</label>
                <input type="text" class="form-control" name="type_code" required>
            </div>
            <div class="col-7">
                <label for=""><?php echo $name;?> Type Name</label>
                <input type="text" class="form-control" name="type_name" required>
            </div>
        </div>
        <div class="form-row">
            <label for="">Assign Activities</label>
            <select name="act_id[]" id="act_id" class="form-control selectpicker " multiple data-live-search="true" required>
                <option value="all">All <?php echo $prj_type.' Activities'; ?></option>
                <?php 
                    $q_options="SELECT * FROM activity WHERE Act_Category='$type' AND Act_Status=1";
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
        <div class="form-row">
            <label for="">Assign <?php echo $name;?></label>
            <select name="flat_id[]" id="" class="form-control selectpicker" multiple data-live-search="true" required>
                <?php 
                    $q_flat_asgn="SELECT * FROM flat_asgn_to_type 
                                WHERE Flat_Asgd_Status=1 AND Flat_Id IN ('$flat_ids')";
                    $q_flat_asgn_run=mysqli_query($connection,$q_flat_asgn);
                    if(mysqli_num_rows($q_flat_asgn_run)>0){
                        
                        while($row_f=mysqli_fetch_assoc($q_flat_asgn_run)){
                            $flat_arr[]=$row_f['Flat_Id']; //
                        }
                        $asgn_ids = implode("', '", $flat_arr); //assigned
                        //removed assigned flat
                        $f1="SELECT * FROM flat WHERE Flat_Id IN ('$flat_ids') AND Flat_Id NOT IN ('$asgn_ids')";
                        $f1_run=mysqli_query($connection,$f1);
                        if(mysqli_num_rows($f1_run)>0){
                            echo "<option value='all'>All Remaining $name</option>";
                            while($row_f1=mysqli_fetch_assoc($f1_run)){
                                $flat_id=$row_f1['Flat_Id'];
                                $flat_name=$row_f1['Flat_Code'].' '.$row_f1['Flat_Name'];
                                echo "<option value='$flat_id'>$flat_name</option>";
                            }
                        }
                        else{
                            echo "<option value=''>All $name were Assigned</option>";
                            $btn_class="d-none";
                        }
                    }
                    else{
                        $q_flat="SELECT * FROM flat WHERE Flat_Status=1 AND Flat_Id IN ('$flat_ids')";
                        $q_flat_run=mysqli_query($connection,$q_flat);
                        if(mysqli_num_rows($q_flat_run)>0){
                            echo "<option value='all'>All $name</option>";
                            while($row_fo=mysqli_fetch_assoc($q_flat_run)){
                                $flat_id=$row_fo['Flat_Id'];
                                $flat_name=$row_fo['Flat_Code'].' '.$row_fo['Flat_Name'];
                                echo "<option value='$flat_id'>$flat_name</option>";
                            }
                        }
                        else{
                            echo "<option value=''>No $name Exist</option>";
                        }
                    }
                    
                ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
        <input type="hidden" name="prj_type" value="<?php echo $type;?>">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addType" class="btn btn-success <?php echo $btn_class;?>"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Type -->
<!-- Modal Edit Plex -->
<div class="modal fade bd-example-modal-lg" id="editType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-th-large" aria-hidden="true"></i> Edit Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="type_id" id="type_id">
        <div class="form-group">
              <label>Type Code:</label>
              <input type="text" id="type_code" name="type_code" class="form-control" maxlength="30" required>
        </div>
        <div class="form-group">
              <label>Type Name:</label>
              <input type="text" id="type_name" name="type_name" class="form-control" maxlength="40" required>
        </div>
            <input type="hidden" name="villa_id" id="villa_id" value="<?php echo $villa_id?>">
            <input type="hidden" name="prj_id" id="prj_id" value="<?php echo $prj_id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editType" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT plex -->
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
            <input type="hidden" name="prj_id" id="" value="<?php echo $prj_id;?>">
            <input type="hidden" name="ftype_id" id="typeId" value="">
        <div class="modal-footer">
            <input type="hidden" name="prj_type" value="<?php echo $type;?>" >
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
            <button type="submit" name="delType" id="delFltAsgn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
    $('.editBtn').on('click', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#type_id').val(data[0]);
        $('#type_code').val(data[1]);
        $('#type_name').val(data[2]);
    });
});
$(document).ready(function () {
    $('.delType').on('click', function() {
        var prj_id = $('#prj_id').val();
        var typeRec = $(this).next().val();// type id
        $.ajax({
            url: 'code.php',
            method: 'POST',
            data:{
                'typeRec': typeRec,
                'prj_id':prj_id,
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
                    $('#typeId').val(typeRec);
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