<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
if(isset($_POST['plexBtn']) or isset($_GET['id']))
{
    if(isset($_POST['plexBtn'])){
        $villa_id=$_POST['villa_id'];
        $prj_name=$_POST['prj_name'];
    }
    else{
        $villa_id = $_GET['id'];
        $prj_name = $_GET['prj_name'];
    }
    $query="SELECT * FROM plex where Villa_Id='$villa_id' AND Plx_Status=1";
    $query_run = mysqli_query($connection, $query);
}
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary">Manage Plex</h5>
                    <h4 class="ml-2 mt-2"><?php echo $prj_name?></h4>
                </div>
                <!-- BUTTON -->
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addPlex">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Plex
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Plex Code</th>
                        <th>Plex Name</th>
                        <th>No. of Villas</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                // $prj_name=$prj_name.' '.$row['Plx_Name'];
                                $plxId = $row['Plx_Id'];
                                $query2 = "SELECT COUNT(Blg_Id) as count FROM building WHERE Plx_Id='$plxId' AND Blg_Status=1";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Plx_Id']?></td>
                            <td><?php echo $row['Plx_Code']?></td>
                            <td><?php echo $row['Plx_Name']?></td>
                            <td><?php echo $row2['count']?></td>
                            <td class="btn-group text-center">
                                <!-- MANAGE -->
                                <form action="p_building_plx.php" method="POST">
                                    <input type="hidden" name="plx_id" value="<?php echo $row['Plx_Id']?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name.' '.$row['Plx_Code']?>">
                                    <button type="submit" name="blgBtn" class="btn btn-info">
                                        <i class="fa fa-cog" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBtn">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="plx_id" value="<?php echo $row['Plx_Id']?>">
                                    <input type="hidden" name="villa_id" value="<?php echo $villa_id?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name.' '.$row['Plx_Code']?>">
                                    <button type="submit" name="delPlx" class="btn btn-danger">
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

<!-- Modal Add Plex -->
<div class="modal fade bd-example-modal-lg" id="addPlex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-th-large" aria-hidden="true"></i> Add Plex</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" name="villa_id" value="<?php echo $villa_id?>">
            <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
            <div class="table-responsive">
                <table class="table table-bordered" id="plexTbl">
                    <tr>
                        <th>Plex Code</th>
                        <th>Plex Name</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="plx_code[]" class="form-control no-border" type="text" required></td>
                        <td><input name="plx_name[]" class="form-control no-border" type="text" required></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="plex_status" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addPlx" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Plex -->
<!-- Modal Edit Plex -->
<div class="modal fade bd-example-modal-lg" id="editPlex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-th-large" aria-hidden="true"></i> Edit Plex</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="plex_id" id="plx_id">
        <div class="form-group">
              <label> Plex Code: </label>
              <input type="text" id="plx_code" name="plx_code" class="form-control" maxlength="30" required>
        </div>
        <div class="form-group">
              <label> Plex Name: </label>
              <input type="text" id="plx_name" name="plx_name" class="form-control" maxlength="40" required>
        </div>
            <input type="hidden" name="villa_id" id="villa_id" value="<?php echo $villa_id?>">
            <input type="hidden" name="prj_name" id="prj_name" value="<?php echo $prj_name?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editPlx" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT plex -->
<script>

$(document).ready(function(){
var count = 1;
    $('#addBtn').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
    html_code += "<td><input name='plx_code[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><input name='plx_name[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#plexTbl').append(html_code);
    });
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
}); 
$(document).on('click','.editBtn', function(){
    $('#editPlex').modal("show");
    $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
    $('#plx_id').val(data[0]);
    $('#plx_code').val(data[1]);
    $('#plx_name').val(data[2]);
}) ;
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>