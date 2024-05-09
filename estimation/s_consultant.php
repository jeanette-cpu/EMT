<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-pencil mr-2" aria-hidden="true"></i> Consultants
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addConsultant">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Consultant
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Consultant</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($consultant_run)>0){
                            while($row = mysqli_fetch_assoc($consultant_run)){
                                $consultant_id=$row['Consultant_Id'];
                                $consultant_desc=$row['Consultant_Name'];
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $consultant_id; ?></td>
                            <td><?php echo $consultant_desc;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editConsultant">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">  
                                <input type="hidden" name="cons_id" value="<?php echo $consultant_id;?>">  
                                    <button type="submit" name ="delCons" class="btn btn-danger">
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
<!-- Modal Add Consultant -->
<div class="modal fade bd-example-modal-lg" id="addConsultant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users mr-2" aria-hidden="true"></i> Add Consultant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Consultant Name</label>
                        <input type="text" name="cons" id="" class="form-control">
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addCons" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Consultant -->
<!-- Modal EDIT Consultant -->
<div class="modal fade bd-example-modal-md" id="editConsultant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Consultant</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="cons_id" id="c_id">
            <div class="form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">System Description</label>
                        <input type="text" name="cons" id="cons" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editCons" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Consultant -->
<script>
$(document).ready(function () {
    $('.editConsultant').on('click', function() {
        $('#editConsultant').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            // console.log(data);
            // alert(data);
            $('#c_id').val(data[0]);
            $('#cons').val(data[1]);
        });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>