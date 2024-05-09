<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$message='';$e_message='';
if (isset($_GET['success'])){
    $success = $_GET['success'];
    $error = $_GET['error'];
    $err_names = $_GET['err_names'];
    $prj_id = $_GET['id'];

    $message = "Materials Updated: ".$success;
    if($error>=1){
        $message .= ("\n Error Uploads: ".$error);
        $e_message = ("\n On Material Code(s):");
        $e_message .= $err_names = str_replace(","," \t\n",$err_names);
    }
}
if(isset($_POST['prjMatBtn'])  or isset($_GET['id']) or  isset($_GET['success']))
{
    if(isset($_POST['prjMatBtn']))
    {
        $prj_id = $_POST['prj_id'];
    }
    elseif(isset($_GET['id'])){
        $prj_id = $_GET['id'];
    }
    
    $query = "SELECT * FROM mat_qty WHERE Prj_Id='$prj_id' and Mat_Qty_Status=1";
    $query_run = mysqli_query($connection, $query);
    // prj name
    $q_prj_name = "SELECT Prj_Code, Prj_Name from project where Prj_Id='$prj_id'";
    $q_prj_name_run = mysqli_query($connection, $q_prj_name);
    $row_p = mysqli_fetch_assoc($q_prj_name_run);
}
?>
<div class="container-fluid">
    <div class="card shadow mb-5">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Import</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="code1.php" method="POST" enctype="multipart/form-data" class="mb-3">                     
                        <h6 class="text-primary">Material Quantity</h6>
                        <input type="file" name="file" required/>
                        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                        <input class="mt-1" type="submit" name="import" value="Import"/>
                </form>
                <div id="message" class="mt-3">
                    <?php echo nl2br ($message)?>
                </div>
                <div class="ml-3">
                    <?php echo nl2br ($e_message)?>
                </div>
            <label class="mt-3"><b>NOTE : </b>Upload CSV File Only </label>
            </div> 
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary">Available Materials</h5>
                    <h4 class="ml-2 mt-2"><?php echo $row_p['Prj_Code'].' '.$row_p['Prj_Name']?></h4>
                </div>
                <div class="col-md-6">
                    <!-- ASGN ALL ACTIVITY BUTTON -->
                    <form action="code1.php" method="POST">
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
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
                                // department name
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
                            <td class="btn-group">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-pencil" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
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

    <form action="code1.php" method="POST">

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

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
            <input type="hidden" name="mat_id" id="mat_id">
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
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
            url:'ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id':mat_id},
            success:function(data){
                $(document).find('#row'+count+' #material_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
// POPULATE MATERIAL OPTIONS
var mat_id =1;
$.ajax({
    url:'ajax_mat_opt.php',
    method: 'POST',
    data:{'mat_id':mat_id},
    success:function(data){
        $('#mat_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
// EDIT MATERIAL QTY
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
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>