<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$sql="SELECT * FROM activity_category WHERE Act_Cat_Status=1";
$query_run = mysqli_query($connection, $sql);
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Activity Category
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addActCategory">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Category
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Activity Category</th>
                        <th>Department</th>
                        <th class="d-none"></th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Act_Cat_Id']?></td>
                            <td><?php echo $row['Act_Cat_Name']?></td>
                            <td><?php $dept_id= $row['Dept_Id'];
                                    $query1= "SELECT Dept_Name FROM department WHERE Dept_Id='$dept_id' LIMIT 1";
                                    $query_run1=mysqli_query($connection,$query1);
                                    $row1 = mysqli_fetch_assoc($query_run1);
                                    echo $row1['Dept_Name'];
                                ?>
                            </td>
                            <td class="d-none"><?php echo $row['Dept_Id']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="del_ActCat" value="<?php echo $row['Act_Cat_Id']?>">
                                    <button type="submit" name="delActCat" class="btn btn-danger">
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

<!-- Modal Add Activity Category -->
<div class="modal fade bd-example-modal-lg" id="addActCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Add Activity Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Activity Category</label>
                <input type="text" name="ActCat_name" class="form-control" required>
            </div>
            <?php
                $query="SELECT * FROM department WHERE Dept_Status=1";
                $query_run = mysqli_query($connection,$query);
            ?>
            <div class="form-group col-md-6">
                <label>Department Name</label>
                <select name="dept_name" id="dept_name" class="form-control" required>
                    <option> Select Department</option>
                    <?php
                        while($row = mysqli_fetch_array($query_run))
                        {
                        ?>
                            <option value="<?php echo $row['Dept_Id']?>"><?php echo $row['Dept_Name']?> </option>
                        <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addActCat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Department -->

<!-- Modal Edit Activity Category -->
<div class="modal fade bd-example-modal-lg" id="editActCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Activity Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="act_cat_id" id="act_cat_id">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Activity Category</label>
                <input type="text" name="act_cat_name" id="act_cat_name" class="form-control" required>
            </div>
            <?php
                $query="SELECT * FROM department WHERE Dept_Status=1";
                $query_run = mysqli_query($connection,$query);
            ?>
            <div class="form-group col-md-6">
                <label>Department Name</label>
                <select name="dept_name" id="dept_id" class="form-control" required>
                    <option> Select Department</option>
                    <?php
                        while($row = mysqli_fetch_array($query_run))
                        {
                        ?>
                            <option value="<?php echo $row['Dept_Id']?>"><?php echo $row['Dept_Name']?> </option>
                        <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editActCat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Department -->
<script>
$(document).ready(function () {
    $(document).on('click', '.editBtn', function() {
        $('#editActCat').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       console.log(data);
       $('#act_cat_id').val(data[0]);
       $('#act_cat_name').val(data[1]);
       $('#dept_id').val(data[3]);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>