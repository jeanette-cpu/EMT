<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-bullseye mr-2" aria-hidden="true"></i> Target
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTarget">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Target
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Year</th>
                        <th>No. of Projects</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($target_run)>0){
                            while($row = mysqli_fetch_assoc($target_run)){
                                $target_id=$row['Target_Id'];
                                $target_no=$row['Target_Prj_No'];
                                $year=$row['year'];
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $target_id; ?></td>
                            <td><?php echo $year;?></td>
                            <td><?php echo $target_no;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editTarget">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">  
                                <input type="hidden" name="id" value="<?php echo $target_id;?>">  
                                    <button type="submit" name ="delTarget" class="btn btn-danger">
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
<!-- Modal Add Target -->
<div class="modal fade bd-example-modal-lg" id="addTarget" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bullseye mr-2" aria-hidden="true"></i> Add Target</h5>
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
                        <label for="">Target no. of Projects</label>
                        <input type="number" name="target" id="" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Year</label>
                        <select name="year" id="yr_opt" class="form-control" required></select>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addTarget" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Target -->
<!-- Modal EDIT Target -->
<div class="modal fade bd-example-modal-md" id="editTarget" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Target</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="id" id="id">
            <div class="form-row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">System Description</label>
                        <input type="text" name="target" id="target" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Year</label>
                        <select name="year" id="yr_opt1" class="form-control" required></select>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editTarget" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Target -->
<script>
$(document).ready(function () {
    $('.editTarget').on('click', function() {
        $('#editTarget').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            $('#id').val(data[0]);
            $('#yr_opt1').val(data[1]);
            $('#target').val(data[2]);
        });
});
$(document).ready(function(){
    var client="q";
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'yr_opt': client},
        success:function(data){
            $(document).find('#yr_opt').html(data).change();
            $(document).find('#yr_opt1').html(data).change();
        }
    });  
}); 
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>