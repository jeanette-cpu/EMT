<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/store_mgr_navbar.php');
//GET PRJ ID ASSIGNED TO STR MGR
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
$query_run2=mysqli_query($connection,$sql);
$row_uu = mysqli_fetch_assoc($query_run2);
$user_id = $row_uu['USER_ID'];

$query = "SELECT * FROM project as p LEFT JOIN asgn_emp_to_prj as ass_e on ass_e.Prj_Id = p.Prj_Id WHERE p.Prj_Status =1 and ass_e.User_Id='$user_id' LIMIT 1";
$query_run_p = mysqli_query($connection, $query);
if(mysqli_num_rows($query_run_p)>0)
{ 
    $row_p = mysqli_fetch_assoc($query_run_p);
    $prj_id = $row_p['Prj_Id'];
    $prj_name = $row_p['Prj_Code'].' - '.$row_p['Prj_Name'];
    $query = "SELECT * FROM mat_qty WHERE Prj_Id='$prj_id' and Mat_Qty_Status=1";
    $query_run = mysqli_query($connection, $query);
}
else
{
    $prj_name=NULL;
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-6">
                    <h5 class="m-0 font-weight-bold text-primary">Inventory</h5>
                    <h4 class="ml-2 mt-1 b"><?php echo $prj_name?></h4>
                </div>
                <div class="col-6">
                    <!-- ASGN ALL ACTIVITY BUTTON -->
                    <form action="code.php" method="POST">
                        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                        <button type="submit" name="AsgnAllMat" class="btn btn-primary float-right mt-2 ml-2" >
                            <i class="fa fa-plus mr-2" aria-hidden="true"></i>Assign All Materials
                        </button>
                    </form>
                    <!-- SINGLE ASSIGN -->
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addMaterial">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Material
                    </button>
                </div>
            </div>
        </div>
        <div class="row float-left mt-2">
            <div class="col-6">
            </div>
            <div class="col-6">
             <!-- BUTTON -->
                <button type="button" class="btn btn-success float-right ml-2 mr-4" data-toggle="modal" data-target="#receive_m">
                        Receive
                    <i class="fa fa-cubes" aria-hidden="true"></i>
                </button></h5>
                 <!-- BUTTON -->
                 <button type="button" class="btn btn-warning float-right" data-toggle="modal" data-target="#release_m">
                        Release
                        <i class="fa fa-truck" aria-hidden="true"></i>
                </button></h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mat_tbl" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Material Code</th>
                        <th>Material Desc</th>
                        <th>Department</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $mat_id = $row['Mat_Id'];
                                $m_q ="SELECT * FROM material where mat_id='$mat_id'";
                                $m_q_run=mysqli_query($connection,$m_q);
                                $m_row = mysqli_fetch_assoc($m_q_run);
                                // dept name
                                $dept_id = $m_row['Dept_Id'];
                                $query2 = "SELECT Dept_Name from department where Dept_Id='$dept_id'";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Mat_Qty_Id']?></td>
                            <td><?php echo $m_row['Mat_Code']?></td>
                            <td><?php echo $m_row['Mat_Desc']?></td>
                            <td><?php echo $row2['Dept_Name']?></td>
                            <td><?php echo $m_row['Mat_Unit']?></td>
                            <td><?php echo $row['Mat_Q_Qty']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="mat_id" value="<?php echo $row['Mat_Qty_Id']?>">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                    <button type="submit" name="delQty" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
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

<!-- Modal Add Material -->
<div class="modal fade bd-example-modal-lg" id="addMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive" aria-hidden="true"></i> Add Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- FORM -->
            <div class="table-responsive">
                <table class="table table-bordered" id="materialTbl">
                    <tr>
                        <th >Material </th>
                        <th width="15%">Qty</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><select name="mat_id[]" id="mat_opt" class="form-control selectpicker" data-live-search="true" required></select></td>
                        <td><input name="mat_qty[]" class="form-control no-border" type="number" required></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addPrjMat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Material -->

<!-- Release Modal -->
<div class="modal fade bd-example-modal-lg" id="release_m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-truck" aria-hidden="true"></i> Release</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table table-bordered" id="relTbl">
                        <tr>
                            <th width="60%">Material</th>
                            <th>Qty Release -</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td><select name="mat[]" class="form-control no-border selectpicker mat_opt" data-live-search='true' type="text"></select></td>
                            <td><input name="mat_qty[]" class="form-control no-border" type="number"></td>
                            <td></td>
                        </tr>
                    </table>
                    <div align="right">
                        <button type="button" name="add" id="addBtnRel" class="btn btn-success btn-xs">+</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="pm_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="releaseBtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Release Modal -->

<!-- Receive Modal -->
<div class="modal fade bd-example-modal-lg" id="receive_m" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cubes" aria-hidden="true"></i> Receive</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="POST">
      <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table table-bordered" id="recTbl">
                        <tr>
                            <th width="60%">Material</th>
                            <th>Qty Receive +</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td><select name="mat[]" class="form-control no-border selectpicker mat_opt" data-live-search='true' type="text"></select></td>
                            <td><input name="mat_qty[]" class="form-control no-border" type="number"></td>
                            <td></td>
                        </tr>
                    </table>
                    <div align="right">
                        <button type="button" name="add" id="addBtnRec" class="btn btn-success btn-xs">+</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="pm_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="receiveBtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Receive Modal -->

<!-- Modal Edit Material QTY-->
<div class="modal fade bd-example-modal-lg" id="editMat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive" aria-hidden="true"></i> Edit Material Qty</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
            <input type="hidden" name="mat_id" id="mat_id">
            <input type="hidden" id="prj_id" name="prj_id" value="<?php echo $prj_id?>">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label>Material Code</label>
                        <input type="text" name="emat_code" id="mat_code" class="form-control" readonly>
                    </div>
                    <div class="col-8">
                        <label>Material Desc</label>
                        <input type="text" name="emat_desc" id="mat_desc" class="form-control" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-6">
                        <label>Material Unit</label>
                        <input type="text" id="mat_unit" class="form-control" readonly>
                    </div>
                    <div class="col-6">
                        <label>Quantity</label>
                        <input type="number" id="mat_qty" name="mat_qty" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="edit_MQty" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Material -->
<script>
$(document).ready(function(){
    var count = 1;
    $('#addBtn').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
    html_code +="<td><select name='mat_id[]' id='material_opt' class='form-control no-border selectpicker' data-live-search='true' required></select></td>";
    html_code += "<td><input name='mat_qty[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#materialTbl').append(html_code);
        var mat_id =1;
        $.ajax({
            url:'../P_ADMIN/ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id':mat_id},
            success:function(data){
                $(document).find('#row'+count+' #material_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
// release select material
$(document).ready(function(){
    var c = 1;
    $('#addBtnRel').click(function(){
    c = c + 1;
    var html_code = "<tr id='row"+c+"'>";
    html_code +="<td><select name='mat[]' id='material_opt' class='form-control no-border selectpicker' data-live-search='true' required></select></td>";
    html_code += "<td><input name='mat_qty[]' class='form-control no-border selectpicker' data-live-search='true' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+c+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#relTbl').append(html_code);

        var prj_id=$("#prj_id").val();
        $.ajax({
            url:'../P_ADMIN/ajax_mat_opt.php',
            method: 'POST',
            data:{'prj_id':prj_id},
            success:function(data){
                $(document).find('#row'+c+' #material_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});

// receive select material
$(document).ready(function(){
    var cc = 1;
    $('#addBtnRec').click(function(){
    cc = cc + 1;
    var html_code = "<tr id='row"+cc+"'>";
    html_code +="<td><select name='mat[]' id='materials_opt' class='form-control no-border selectpicker' data-live-search='true' required></select></td>";
    html_code += "<td><input name='mat_qty[]' class='form-control no-border selectpicker' data-live-search='true' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+cc+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#recTbl').append(html_code);

        var prj_id=$("#prj_id").val();
        $.ajax({
            url:'../P_ADMIN/ajax_mat_opt.php',
            method: 'POST',
            data:{'prj_id':prj_id},
            success:function(data){
                $(document).find('#row'+cc+' #materials_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
$.ajax({
        url:'../P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#dept_name').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'../P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#edept_name').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
$(document).ready(function () {
    $(document).on('click', '.editBtn', function() {
        $('#editMat').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       console.log(data);
       $('#edept_name').val(data[6]);
       $('#mat_id').val(data[0]);
       $('#mat_code').val(data[1]);
       $('#mat_desc').val(data[2]);
       $('#mat_unit').val(data[4]);
       $('#mat_qty').val(data[5]);
    });
});
//data table
$(document).ready(function() {
    $('#mat_tbl').DataTable({
        pageLength: 10,
        filter: true,
        "searching": true,
    });
});

var mat_id =1;
$.ajax({
    url:'../P_ADMIN/ajax_mat_opt.php',
    method: 'POST',
    data:{'mat_id':mat_id},
    success:function(data){
        $('#mat_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
var prj_id=$("#prj_id").val();
$.ajax({
    url:'../P_ADMIN/ajax_mat_opt.php',
    method: 'POST',
    data:{'prj_id':prj_id},
    success:function(data){
        $('.mat_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});

</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>