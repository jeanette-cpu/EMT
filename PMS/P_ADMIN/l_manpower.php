<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$query = "SELECT * FROM manpower WHERE MP_Status='1'";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Manpower Supply
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addManpower">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Manpower
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Manpower Name</th>
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
                            <td class="d-none"><?php echo $row['MP_Id']?></td>
                            <td><?php echo $row['MP_Name']?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editMP">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="mp_id" value="<?php echo $row['MP_Id']?>">
                                    <button type="submit" name="delMP" class="btn btn-danger">
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
                } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Manpower -->
<div class="modal fade bd-example-modal-lg" id="addManpower" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users" aria-hidden="true"></i> Add Manpower</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <form action="code1.php" method="POST">
        <div class="modal-body">
            <label >Manpower Name</label>
            <input type="text" class="form-control" name="mp_name" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addMP" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
        </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Manpower -->

<!-- Modal Edit Manpower -->
<div class="modal fade bd-example-modal-lg" id="editManpower" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users" aria-hidden="true"></i>  Edit Manpower</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- FORM -->
            <input type="hidden" id="mp_id" name="mp_id">
            <div class="form-group">
                <label>Manpower Name</label>
                <input type="text" id="mp_name" name="mp_name"class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editMP" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Manpower -->
<script>
    $(document).ready(function(){
        $('.editMP').click(function(){
            $('#editManpower').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            $('#mp_id').val(data[0]);
            $('#mp_name').val(data[1]);
        });
    });
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>