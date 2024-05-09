<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>
<?php
    if(isset($_POST['building_prj']) or isset($_GET['id']))
    {
        if(isset($_POST['building_prj']))
        {
            $prj_id = $_POST['prj_id'];
        }
        else{
            $prj_id = $_GET['id'];
        }
        $query = "SELECT * FROM building WHERE Prj_Id='$prj_id' AND Blg_Status='1'";
        $query_run = mysqli_query($connection, $query);

        $query1 = "SELECT Prj_Code, Prj_Name FROM project WHERE Prj_Id='$prj_id' LIMIT 1";
        $query_run1 = mysqli_query($connection, $query1);
        $row1 = mysqli_fetch_assoc($query_run1);
        $prj_name = $row1['Prj_Code'].' - '.$row1['Prj_Name'];
    }
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary">Manage Building</h5>
                    <h4 class="ml-2 mt-2"><?php echo $prj_name?></h4>
                </div>
                <!-- BUTTON -->
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addPlex">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Building
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Building Code</th>
                        <th>Building Name</th>
                        <th>No. of Levels</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $blgId = $row['Blg_Id'];
                                $query2 = "SELECT COUNT(Lvl_Id) as count FROM level WHERE Blg_Id='$blgId' AND Lvl_Status=1";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                $prj_name = $prj_name.' '.$row['Blg_Name'];
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Blg_Id']?></td>
                            <td><?php echo $row['Blg_Code']?></td>
                            <td><?php echo $row['Blg_Name']?></td>
                            <td><?php echo $row2['count'] ?></td>
                            <td class="btn-group text-center">
                                <!-- MANAGE -->
                                <form action="p_level.php" method="POST">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
                                    <input type="hidden" name="blg_id" value="<?php echo $row['Blg_Id']?>">
                                    <button type="submit" name="lvlBtn" class="btn btn-info">
                                        <i class="fa fa-cog" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editBlg">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="blg_id" value="<?php echo $row['Blg_Id']?>">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                    <button type="submit" name="delBlg" class="btn btn-danger">
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

<!-- Modal Add Building -->
<div class="modal fade bd-example-modal-lg" id="addPlex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building" aria-hidden="true"></i> Add Building</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        
        <!-- THE FORM -->
            <div class="table-responsive">
                <table class="table table-bordered" id="buildingTbl">
                    <tr>
                        <th>Building Code</th>
                        <th>Building Name</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="blgCode[]" class="form-control no-border" type="text" required></td>
                        <td><input name="blgName[]" class="form-control no-border" type="text" required></td>
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
        <button type="submit" name="addBlg" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Building -->

<!-- Modal Edit Building -->
<div class="modal fade bd-example-modal-lg" id="editBuilding" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-building" aria-hidden="true"></i> Edit Building</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-group">
              <label> Building Code: </label>
              <input type="text" id="bcode" name="e_bcode" class="form-control" maxlength="30">
        </div>
        <div class="form-group">
              <label> Building Name: </label>
              <input type="text" id="bname" name="e_bname" class="form-control" maxlength="40">
        </div>
            <input type="hidden" name="e_bId" id="bId" value="">
            <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editBuilding" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Building -->

<script>

$(document).ready(function(){
var count = 1;
    $('#addBtn').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
    html_code += "<td><input name='blgCode[]' class='form-control no-border' type='text'></td>";
    html_code += "<td><input name='blgName[]' class='form-control no-border' type='text'></td>";
    html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
    html_code += "</tr>";
    $('#buildingTbl').append(html_code);
    });
});

$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});  

$(document).ready(function () {
    $(document).on('click', '.editBlg', function() {
        $('#editBuilding').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       console.log(data);
       $('#bId').val(data[0]);
       $('#bcode').val(data[1]);
       $('#bname').val(data[2]);
    });
});
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>