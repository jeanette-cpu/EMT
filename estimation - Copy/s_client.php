<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-users mr-2" aria-hidden="true"></i> Clients
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addClient">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Client
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Client</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($client_run)>0){
                            while($row = mysqli_fetch_assoc($client_run)){
                                $client_id=$row['Client_Id'];
                                $client_desc=$row['Client_Name'];
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $client_id; ?></td>
                            <td><?php echo $client_desc;?></td>
                            <td class="btn-group text-center">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editClient">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">  
                                <input type="hidden" name="c_id" value="<?php echo $client_id;?>">  
                                    <button type="submit" name ="delClient" class="btn btn-danger">
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
<!-- Modal Add Client -->
<div class="modal fade bd-example-modal-lg" id="addClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-users mr-2" aria-hidden="true"></i> Add Client</h5>
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
                        <label for="">Client Name</label>
                        <input type="text" name="client" id="" class="form-control">
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addClient" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Client -->
<!-- Modal EDIT Client -->
<div class="modal fade bd-example-modal-md" id="editClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Client</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="c_id" id="c_id">
            <div class="form-row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="">System Description</label>
                        <input type="text" name="client" id="client" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editClient" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Client -->
<script>
$(document).ready(function () {
    $('.editClient').on('click', function() {
        $('#editClient').modal('show');
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            // console.log(data);
            // alert(data);
            $('#c_id').val(data[0]);
            $('#client').val(data[1]);
        });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>