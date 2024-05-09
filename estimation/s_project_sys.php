<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-cogs" aria-hidden="true"></i> Project System
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addProject">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add System
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Project System Description</th>
                        <th>Department</th>
                        <th class="d-none"></th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($prj_sys_run)>0){
                            while($row = mysqli_fetch_assoc($prj_sys_run)){
                                $ps_id=$row['Prj_Sys_Id'];
                                $ps_desc=$row['Prj_Sys_Desc'];
                                $dept_id=$row['Dept_Id'];
                                $dept_name=deptName($dept_id);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $ps_id; ?></td>
                            <td><?php echo $ps_desc;?></td>
                            <td><?php echo $dept_name;?></td>
                            <td class="d-none"><?php echo $dept_id;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editProject">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">  
                                <input type="hidden" name="ps_id" value="<?php echo $ps_id;?>">  
                                    <button type="submit" name ="delPs" class="btn btn-danger">
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
<!-- Modal Add Project system -->
<div class="modal fade bd-example-modal-lg" id="addProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-project" aria-hidden="true"></i> Add Project System</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Department</label>
                        <select name="dept_id" id="dept_opt" class="form-control"></select>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="">System Description</label>
                        <input type="text" name="desc" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addPs" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->
<!-- Modal EDIT Project -->
<div class="modal fade bd-example-modal-md" id="editProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="ps_id" id="ps_id">
            <div class="form-row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Department</label>
                        <select name="dept_id" id="ps_dept" class="form-control"></select>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="">System Description</label>
                        <input type="text" name="desc" id="ps_desc" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editPs" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Project -->
<script>
$(document).ready(function(){
    var bank_opt="";
    $.ajax({
        url:'../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{'dept_opt': bank_opt},
        success:function(data){
            $(document).find('#dept_opt').html(data).change();
            $(document).find('#ps_dept').html(data).change();
        }
    });
});
$(document).ready(function () {
    $('.editProject').on('click', function() {
        $('#editProject').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            // console.log(data);
            // alert(data);
            $('#ps_id').val(data[0]);
            $('#ps_desc').val(data[1]);
            $('#ps_dept').val(data[3]);

        });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>