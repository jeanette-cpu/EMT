<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$query="SELECT * FROM material WHERE Mat_Status=1";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Materials
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addMaterial">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Material
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Material Code</th>
                        <th>Material Name</th>
                        <th>Department</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th class="d-none"></th>
                        <th></th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $dept_id = $row['Dept_Id'];
                                // DEPARTMENT NAME
                                $query2 = "SELECT Dept_Name from department where Dept_Id='$dept_id'";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                // OVERALL QTY
                                $mat_id=$row['Mat_Id'];
                                $qty_total = "SELECT SUM(Mat_Q_Qty) as qty from mat_qty LEFT JOIN project as p on p.Prj_Id=mat_qty.Prj_Id where Mat_Id='$mat_id' and Mat_Qty_Status=1 and p.Prj_Status=1";
                                $qty_total_run=mysqli_query($connection,$qty_total);
                                $row_q = mysqli_fetch_assoc($qty_total_run);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $mat_id?></td>
                            <td><?php echo $row['Mat_Code']?></td>
                            <td><?php echo $row['Mat_Desc']?></td>
                            <td><?php echo $row2['Dept_Name']?></td>
                            <td><?php echo $row['Mat_Unit']?></td>
                            <td><?php echo $row_q['qty']?></td>
                            <td class="d-none"><?php echo $row['Dept_Id']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="m_id" value="<?php echo $row['Mat_Id']?>">
                                    <button type="submit" name="delMat" class="btn btn-danger">
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

    <form action="code1.php" method="POST">

      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <label for="">Department Name</label>
                    <select name="dept_id" id="dept_name" class="form-control">
                    </select>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="materialTbl">
                    <tr>
                        <th width="20%">Material Code</th>
                        <th>Material Desc</th>
                        <th width="20%">Unit</th>
                        <!-- <th width="15%">Qty</th> -->
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="mat_code[]" class="form-control no-border" type="text"></td>
                        <td><input name="mat_desc[]" class="form-control no-border" type="text"></td>
                        <td>
                            <select name="mat_unit[]" id="" class="form-control" required>
                                <option value="">Select Unit</option>
                                <option value="m">m</option>
                                <option value="No">No</option>
                                <option value="Roll">Roll</option>
                                <option value="Item">Item</option>
                            </select>
                        </td>
                        <!-- <td><input name="mat_qty[]" class="form-control no-border" type="text"></td> -->
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
        <button type="submit" name="addMat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Material -->

<!-- Modal Edit Material -->
<div class="modal fade bd-example-modal-lg" id="editMat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive" aria-hidden="true"></i> Edit Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
            <input type="hidden" name="mat_id" id="mat_id">
                
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label>Material Code</label>
                        <input type="text" name="emat_code" id="mat_code" class="form-control">
                    </div>
                    <div class="col-8">
                        <label>Material Desc</label>
                        <input type="text" name="emat_desc" id="mat_desc" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-6">
                        <label>Material Unit</label>
                        <select name="emat_unit" id="mat_unit" class="form-control">
                            <option value="">Select Unit</option>
                                <option value="m">m</option>
                                <option value="No">No</option>
                                <option value="Roll">Roll</option>
                                <option value="Item">Item</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label>Department</label>
                        <select name="dept_id" id="edept_name" class="form-control">
                        </select>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="edit_Mat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
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
    html_code +="<td><input name='mat_code[]' class='form-control no-border' type='text' required> </td>";
    html_code += "<td><input name='mat_desc[]' class='form-control no-border' type='text' required></td>";
    html_code +="<td><select name='mat_unit[]' class='form-control no-border' required><option value=''>Select Unit</option><option value='m'>m</option><option value='No'>No</option><option value='Roll'>Roll</option><option value='Item'>Item</option></select></td>";
    // html_code += "<td><input name='mat_qty[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#materialTbl').append(html_code);
    });
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
$.ajax({
        url:'ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#dept_name').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'ajax_dept.php',
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
    //    $('#mat_qty').val(data[5]);
    });
});

// release select material
$(document).ready(function(){
    var c = 1;
    $('#addBtnRel').click(function(){
    c = c + 1;
    var html_code = "<tr id='row"+c+"'>";
    html_code +="<td><select name='mat[]' id='material_opt' class='form-control no-border selectpicker' data-live-search='true' required></select></td>";
    // html_code += "<td><input name='mat_qty[]' class='form-control no-border selectpicker' data-live-search='true' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+c+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#relTbl').append(html_code);

        var mat_id =1;
        $.ajax({
            url:'ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id':mat_id},
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

        var mat_id =1;
        $.ajax({
            url:'ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id':mat_id},
            success:function(data){
                $(document).find('#row'+cc+' #materials_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
var mat_id =1;
$.ajax({
    url:'ajax_mat_opt.php',
    method: 'POST',
    data:{'mat_id':mat_id},
    success:function(data){
        $('#mat_opt').html(data).change();
        $('#mmat_opt').html(data).change()
        $('.selectpicker').selectpicker('refresh');
    }
});
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>