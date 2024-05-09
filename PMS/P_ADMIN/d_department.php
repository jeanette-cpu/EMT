<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php');
$query = "SELECT * FROM department WHERE Dept_Status='1'";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Department
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addDepartment">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Department
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped p-4" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Department</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0){
                            while($row = mysqli_fetch_assoc($query_run)){
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Dept_Id']?></td>
                            <td><?php echo $row['Dept_Name']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editDept">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">  
                                <input type="hidden" name="deptId" value="<?php echo $row['Dept_Id']?>">  
                                    <button type="submit" name ="delDept" class="btn btn-danger">
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

<!-- Modal Add Department -->
<div class="modal fade bd-example-modal-md" id="addDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Add Department</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">

      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-group">
                <label for="">Department Name</label>
                <input type="text" name="dept_name" class="form-control" required>
            </div>
            <input type="hidden" name="dept_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addDept" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Department -->

<!-- Modal EDIT Department -->
<div class="modal fade bd-example-modal-md" id="editDept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Department</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="Dept_Id" id="Dept_Id" value="">
            <div class="form-group">
                <label for="">Department Name</label>
                <input type="text" id ="dept_name" name="dept_name" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editDept" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Department -->
<script>
$(document).ready(function () {
    $(document).on('click', '.editDept', function() {
        $('#editDept').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       $('#Dept_Id').val(data[0]);
       $('#dept_name').val(data[1]);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>