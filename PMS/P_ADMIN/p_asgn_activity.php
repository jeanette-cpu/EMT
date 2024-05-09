<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php');
if(isset($_POST['actBtn']) or isset($_GET['id']))
{
    if(isset($_POST['actBtn'])){
        $Flt_Id = $_POST['flat_id'];
        $prj_name = $_POST['prj_name'];
    }
    else{
        $Flt_Id = $_GET['id'];
        $prj_name = $_GET['prj_name'];
    }  
    $query = "SELECT * FROM assigned_activity as ass_act
    LEFT JOIN activity as act on act.Act_Id= ass_act.Act_Id
    WHERE ass_act.Flat_Id='$Flt_Id' AND ass_act.Asgd_Act_Status=1 AND act.Act_Status=1";
    $query_run = mysqli_query($connection, $query);
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mt-2 font-weight-bold text-primary">Assign Activity</h4>
                </div>
                <!-- BUTTON -->
                <div class="col-md-6 d-none">
                    
                    <!-- ALL ACTIVITY -->
                    <form action="code1.php" method="POST">
                        <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
                        <input type="hidden" name="flat_id" value="<?php echo $Flt_Id?>">
                        <button type="submit" name="AsgnAllAct" class="btn btn-primary float-right mt-2 mr-2" >
                            <i class="fa fa-plus mr-2" aria-hidden="true"></i> All Depertment
                        </button>
                    </form>
                    <!-- BY DEPARTMENT -->
                    <button type="button" class="btn btn-primary float-right mt-2 mr-2 ml-2" data-toggle="modal" data-target="#addActDept">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i> Per Depertment
                    </button>
                    <!-- SINGLE ACTIVITY -->
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addAct">
                        <i class="fa fa-plus ml-2" aria-hidden="true"></i>Assign Activity
                    </button>
                </div>
            </div>
            <h5 class="ml-2 mt-2"><?php echo $prj_name?></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th class="w-30">Activity </th>
                        <th>Activity Category</th>
                        <th>Department</th>
                        <th>Done (%)</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th class="d-none"></th>
                    </thead>
                    <tbody>
                    <?php
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
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Asgd_Act_Id']?></td>
                            <td><?php echo $row1['Act_Code'].' - '.$row1['Act_Name']?></td>
                            <td><?php echo $row3['Act_Cat_Name']?></td>
                            <td><?php echo $row4['Dept_Name']?></td>
                            <td><?php echo $row['Asgd_Pct_Done']?></td>
                            <td><?php echo $pct?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
                                    <input type="hidden" name="flat_id" value="<?php echo $Flt_Id?>">
                                    <input type="hidden" name="asgn_act_id" value="<?php echo $row['Asgd_Act_Id']?>">
                                    <button type="submit" name="delAsgnBtn" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- MATERIAL -->
                                <button type="button" class="btn btn-warning addMaterial">
                                    <i class="fa fa-cube" area-hidden="true"></i>
                                </button>
                            </td>
                            <td class="d-none"><?php echo $act_id?></td>
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

<!-- Modal Materials -->
<div class="modal fade bd-example-modal-lg" id="mat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
            <div class="col-9">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cube" aria-hidden="true"></i>  Material</h5>
            </div>
            <div class="col-3">
                <!-- <button type="button" id="add_m" class="btn btn-primary" data-toggle="modal" data-target="#asgn_mat_modal">
                    <i class="fa fa-plus mr-2" aria-hidden="true"></i> Assign Quantity
                </button> -->
            </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_content">
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Materials -->

<!-- Modal Edit Progress -->
<div class="modal fade bd-example-modal-lg" id="editProg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-percent" aria-hidden="true"></i> Edit Percentage Done</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-group">
              <label> Edit Progress: </label>
              <input type="number" id="lno" name="e_prog" class="form-control" maxlength="30">
        </div>
            <input type="hidden" name="asgn_id" id="lId" value="">
            <input type="hidden" id="prj_name" name="prj_name" value="<?php echo $prj_name?>">
            <input type="hidden" id="flt_id" name="flat_id" value="<?php echo $Flt_Id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editProg" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Level -->

<!-- Modal Assign Act -->
<div class="modal fade bd-example-modal-lg" id="addAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Assign Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="row">
            <div class="col-6">
                <label>Department</label>
                <select name="dept_id" id="dept" class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Department</option>
                </select>
            </div>
            <div class="col-6">
                <label>Category</label>
                <select name="category_id" id="category" class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Department First</option>
                </select>
            </div>
        </div>
        <label class="mt-2 ">Activity</label>
        <select name="act_id" id="activity" class="form-control selectpicker mb-4" data-live-search="true" required>
            <option value="">Select Activity</option>
        </select>
        <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
        <input type="hidden" name="flat_id" value="<?php echo $Flt_Id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="asgnAct" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Assign Act -->

<!-- Modal Assign Act DEPT-->
<div class="modal fade bd-example-modal-lg" id="addActDept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Assign Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="row">
            <div class="col-6">
                <label>Department</label>
                <select name="dept_id" id="dept_opt" class="form-control "  required>
                        <option value="">Select Department</option>
                </select>
            </div>
            
        </div>
       
        <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
        <input type="hidden" name="flat_id" value="<?php echo $Flt_Id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="AsgnActDept" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Assign Act Dept-->
<script>
$.ajax({
    url:'ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $('#dept').html(data).change();
        $('#dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
$(document).on('change','#dept', function(){
    var dept_id = $(this).val();
    $.ajax({
        url:'ajax_act_cat.php',
        method: 'POST',
        data:{'dept_id': dept_id},
        success:function(data){
            $('#category').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).on('change','#category', function(){
    var cat_id = $(this).val();
    $.ajax({
        url:'ajax_act_cat.php',
        method: 'POST',
        data:{'act_cat_id': cat_id},
        success:function(data){
            $('#activity').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '.editBtn', function() {
        $('#editProg').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       $('#lId').val(data[0]);
       $('#lno').val(data[4]);
    });
});
// MATERIALS
$(document).on('click','.addMaterial', function(){
    $('#mat_modal').modal('show');
    $tr = $(this).closest('tr');
    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();
    var act_id = data[7];
    var asgd_id = data[0];
    var prj_name = $('#prj_name').val();
    var flt_id =  $('#flt_id').val();

    $.ajax({
        url: 'ajax_asgn_mat_tbl.php',
        method:'POST',
        data: {'act_id':act_id,
                'asgd_id': asgd_id,
                'prj_name': prj_name,
                'flt_id': flt_id
        },
        success:function(data){
            $(document).find('#modal_content').html(data).change();
            // console.log(data);
        }
    });

}); 
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>