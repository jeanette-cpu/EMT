<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php');
$query1 = "SELECT * FROM activity WHERE Act_Status='1'";
$query_run1 = mysqli_query($connection, $query1);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Activities
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActivity">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Activity
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Activity Code</th>
                        <th>Activity Name</th>
                        <th>Department</th>
                        <th>Activity Category</th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>Default Standard <span class="font-weight-normal">Employee:Villa </span></th>
                        <th class="d-none"></th>
                        <th class="d-none"></th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run1)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run1))
                            { 
                                $dept_id = $row['Dept_Id'];
                                $query2 = "SELECT Dept_Name from department WHERE Dept_Id='$dept_id'";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);

                                $act_cat_id = $row['Act_Cat_Id'];
                                $query3 = "SELECT Act_Cat_Name from activity_category WHERE Act_Cat_Id='$act_cat_id'";
                                $query_run3=mysqli_query($connection,$query3);
                                $row3 = mysqli_fetch_assoc($query_run3);
                                $emp_r=$row['Act_Emp_Ratio'];
                                $output_r=$row['Act_Output_Ratio'];

                                if($emp_r!=null && $output_r!=null){
                                    $emp_r = floor($emp_r);
                                    $output_r =floor($output_r);
                                    $ratio_s=$emp_r.':'.$output_r;
                                }
                                else{
                                    $ratio_s='not set';
                                }
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Act_Id']?></td>
                            <td><?php echo $row['Act_Code']?></td>
                            <td><?php echo $row['Act_Name']?></td>
                            <td><?php echo $row2['Dept_Name'] ?></td>
                            <td><?php echo $row3['Act_Cat_Name']?></td>
                            <td class="d-none"><?php echo $row['Dept_Id']?></td>
                            <td class="d-none"><?php echo $row['Act_Cat_Id']?></td>
                            <td><?php echo $ratio_s;?></td>
                            <td class="d-none"><?php echo $emp_r;?></td>
                            <td class="d-none"><?php echo $output_r;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editActivity">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="act_id" value="<?php echo $row['Act_Id']?>">
                                    <button type="submit" name="delAct" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- MATERIALS -->
                                <button type="button" class="btn btn-warning addMaterial">
                                    <i class="fa fa-cube" area-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else
                {
                    echo "No Record Found";
                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Activity -->
<div class="modal fade bd-example-modal-lg" id="addActivity" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i> Add Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="row">
                <?php
                    $query="SELECT * FROM department WHERE Dept_Status=1";
                    $query_run=mysqli_query($connection, $query);
                ?>
                <div class="col-md-4">
                    <label>Department</label>
                        <select name="dept_id" id="dept_id" class="form-control" required>
                            <option>Select Department</option>
                            <?php
                            while($row = mysqli_fetch_array($query_run))
                            {
                            ?>
                                <option value="<?php echo $row['Dept_Id']?>"><?php echo $row['Dept_Name']?></option>
                            <?php
                            }
                        ?>
                        </select>
                </div>
                <div class="col-md-4">
                    <label>Activity Category</label>
                        <select name="act_cat" id="act_cat" class="form-control" required>
                            <option>Select Department</option>
                        </select>
                </div>
                <div class="col-md-4">
                    <label>Type</label>
                        <select name="act_type" id="act_type" class="form-control" required>
                            <option>Select Type</option>
                            <option value="V">Villa</option>
                            <option value="B">Building</option>
                        </select>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered" id="actTbl">
                    <tr>
                        <!-- <th>Activity Code</th> -->
                        <th>Activity Name</th>
                        <th>Standard<th>
                    </tr>
                    <tr>
                        <!-- <td>
                            <input name="act_code[]" class="form-control no-border" type="text" required>
                        </td> -->
                        <td><input name="act_name[]" class="form-control no-border" type="text" required>
                        </td>
                        <td>
                            <div  class="row">
                                <div class="col-5">
                                    <input type="decimal" name="act_emp_r[]" class="form-control no-border" placeholder="Employee">
                                </div>
                                <div class="col-1">
                                    :
                                </div>
                                <div class="col-5">
                                    <input type="number" name="act_output_r[]" class="form-control no-border" placeholder="Output">
                                </div>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="pm_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addAct" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Activity -->

<!-- Modal Edit Activity -->
<div class="modal fade bd-example-modal-lg" id="editAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cogs" aria-hidden="true"></i>  Edit Activity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" id="act_id" name="act_id">
            <div class="row">
                <div class="col-4 d-none">
                    <div class="form-group">
                        <label>Activity Code</label>
                        <input type="text" id="act_code" name="act_code"class="form-control" required>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label>Activity Name</label>
                        <input type="text" id="act_name" name="act_name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                    $query="SELECT * FROM department WHERE Dept_Status=1";
                    $query_run=mysqli_query($connection, $query);
                ?>
                <div class="col-md-6">
                    <label>Department</label>
                        <select name="dept_id" id="e_dept_id" class="form-control" required>
                            <option>Select Department</option>
                            <?php
                            while($row = mysqli_fetch_array($query_run))
                            {
                            ?>
                                <option value="<?php echo $row['Dept_Id']?>"><?php echo $row['Dept_Name']?></option>
                            <?php
                            }
                        ?>
                        </select>
                </div>
                <?php
                    $query3="SELECT * FROM activity_category WHERE Act_Cat_Status=1";
                    $query_run3=mysqli_query($connection, $query3);
                ?>
                <div class="col-md-6">
                    <label>Activity Category</label>
                        <select name="act_cat" id="e_act_cat" class="form-control">
                            <option value=''>Select Activity Category</option>
                                <?php
                                while($row3 = mysqli_fetch_array($query_run3))
                                {
                                ?>
                                    <option value="<?php echo $row3['Act_Cat_Id']?>"><?php echo $row3['Act_Cat_Name']?></option>
                                <?php
                                }
                            ?>
                        </select>
                </div>
            </div>
            <div class="row mt-3">
                <label for="" class="ml-2">Standard Output</label>
            </div>
            <div class="row ">
                <div class="col-5">
                    <label for="">Employee</label>
                    <input type="number" name="act_emp_r" id="e_act_emp" class="form-control no-border" placeholder="" required>
                </div>
                <div class="col-1">
                    <label for="" class="invisible mt-1">1</label><br>:
                </div>
                <div class="col-5">
                    <label for="" class="">Output</label>
                    <input type="number" name="act_output_r" id="e_act_out" class="form-control no-border" placeholder="" required>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editAct" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Activity -->

<!-- Modal Materials -->
<div class="modal fade bd-example-modal-lg" id="mat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <div class="col-8">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cube" aria-hidden="true"></i> Materials</h5>
        </div>
        <div class="col-4">
            <button type="button" id="add_m" class="btn btn-primary float-right" data-toggle="modal" data-target="#addM">
                <i class="fa fa-plus mr-2" aria-hidden="true"></i> Assign Material
            </button>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_content"></div>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Materials -->

<!-- Modal Add Materials -->
<div class="modal fade bd-example-modal-lg" id="addM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cube" aria-hidden="true"></i> Add Material</h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code1.php" method="POST">
        <div class="modal-body">
            <div class="table-reponsive">
                <table class="table table-bordered" id="buildingTbl">
                    <tr>
                        <th>Material</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <select id="material_opt" name="mat[]" class="form-control selectpicker mb-2" data-live-search="true">
                            </select>
                        </td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtnn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="act_id_m" name="act_id">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
                <button type="submit" name="addMatBtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
            </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Materials -->

<script>
$(document).ready(function(){
var count = 1;
    $('#addBtn').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
    // html_code +="<td><input name='act_code[]' class='form-control' data-width='100%' required></td>";
    html_code +="<td><input name='act_name[]' class='form-control' data-width='100%' required></td>";
    html_code +="<td><div  class='row'><div class='col-5'><input type='decimal' name='act_emp_r[]' class='form-control no-border' placeholder='Employee'></div><div class='col-1'>:</div><div class='col-5'><input type='number' name='act_output_r[]' class='form-control no-border' placeholder='Output'></div></div></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#actTbl').append(html_code);
    });
});
$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
$(document).on('change','#dept_id', function() {    
    var dept_id = $(this).val();
    // var dept_name = dept_id;
    $.ajax({
        url: 'ajax_act_cat.php',
        method:'POST',
        data: {dept_id:dept_id},
        success:function(data){
            $(document).find('#act_cat').html(data).change();
            // console.log(data);
        }
    });
});
// $(document).on('change','#act_cat', function() {
//     var act_cat = $(this).val();
//     alert(act_cat);
// });
$(document).on('change','#e_dept_id', function() {    
    var dept_id = $(this).val();
    $.ajax({
        url: 'ajax_act_cat.php',
        method:'POST',
        data: {dept_id:dept_id},
        success:function(data){
            $(document).find('#e_act_cat').html(data).change();
            // console.log(data);
        }
    });
});
$(document).on('click','.editActivity', function(){
    $('#editAct').modal('show');
    $tr = $(this).closest('tr');
    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();
    $('#act_id').val(data[0]);
    $('#act_code').val(data[1]);
    $('#act_name').val(data[2]);
    $('#e_dept_id').val(data[5]);
    $('#e_act_emp').val(data[8]);
    $('#e_act_out').val(data[9]);

    // console.log(data);
    var e_dept_id = data[5];
    $.ajax({
        url:'ajax_act_cat.php',
        method: 'POST',
        data:{'dept_id': e_dept_id},
        success:function(data){
        $(document).find('#e_act_cat').html(data).change();
        },
        complete: function(){
            $('#e_act_cat').val(data[6]).change();
        }
    });
});
// MATERIALS
$(document).on('click','.addMaterial', function(){
    $('#mat_modal').modal('show');
    $tr = $(this).closest('tr');
    var data = $tr.children("td").map(function(){
        return $(this).text();
    }).get();
    var act_id = data[0];
    $.ajax({
        url: 'ajax_asgn_mat.php',
        method:'POST',
        data: {'act_id':act_id},
        success:function(data){
            $(document).find('#modal_content').html(data).change();
            // console.log(data);
        }
    });

});  
$(document).on('click','#add_m', function(){
    var mat = 2;
    $.ajax({
        url: 'ajax_mat_opt.php',
        method:'POST',
        data: {'mat_id': mat},
        success:function(data){
            $('#material_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
});
var cnt = 1;
$('#addBtnn').click(function(){
    cnt ++;
    var html_code = "<tr id='row"+cnt+"'>";
        html_code += "<td><select name='mat[]' id='material_options' class='form-control no-border selectpicker' data-live-search='true'></select></td>";
        html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
        html_code += "</tr>";
    var material = 2;
    $.ajax({
        url: 'ajax_mat_opt.php',
        method:'POST',
        data: {'mat_id': material},
        success:function(data){
            $(document).find('#row'+cnt+' #material_options').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $('#buildingTbl').append(html_code);
    $('.selectpicker').selectpicker('refresh');
});

</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>