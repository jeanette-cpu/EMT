<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>
<?php
    if(isset($_POST['lvlBtn']) or isset($_GET['id']))
    {
        if(isset($_POST['lvlBtn']))
        {
            $Blg_id = $_POST['blg_id'];
            $prj_name = $_POST['prj_name'];
        }
        else{
            $Blg_id = $_GET['id'];
            $prj_name = $_GET['prj_name'];
        }
        $query = "SELECT * FROM level WHERE Blg_Id='$Blg_id' AND Lvl_Status='1'";
        $query_run = mysqli_query($connection, $query);

    }
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary">Manage Level</h5>
                    <h4 class="ml-2 mt-2"><?php echo $prj_name?></h4>
                </div>
                <!-- BUTTON -->
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#addPlex">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Level
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>No</th>
                        <th>Level Code</th>
                        <th>Level Name</th>
                        <th>No. of Flats</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $lvlId = $row['Lvl_Id'];
                                $query2 = "SELECT COUNT(Flat_Id) as count FROM flat WHERE Lvl_Id='$lvlId' AND Flat_Status=1";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);    
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Lvl_Id']?></td>
                            <td><?php echo $row['Lvl_No']?></td>
                            <td><?php echo $row['Lvl_Code']?></td>
                            <td><?php echo $row['Lvl_Name']?></td>
                            <td><?php echo $row2['count'] ?></td>
                            <td class="btn-group text-center">
                                <!-- MANAGE -->
                                <form action="p_flat.php" method="POST">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name.': '.$row['Lvl_Code'].'-'.$row['Lvl_Name'];?>">
                                    <input type="hidden" name="lvl_id" value="<?php echo $row['Lvl_Id']?>">
                                    <button type="submit" name="flatBtn" class="btn btn-info">
                                        <i class="fa fa-cog" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editLevel">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="lvl_id" value="<?php echo $row['Lvl_Id']?>">
                                    <input type="hidden" name="blg_id" value="<?php echo $Blg_id?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
                                    <button type="submit" name="delLvl" class="btn btn-danger">
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

<!-- Modal Add Level -->
<div class="modal fade bd-example-modal-lg" id="addPlex" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bars" aria-hidden="true"></i> Add Level</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">

      <div class="modal-body">
        <!-- THE FORM -->
            <div class="table-responsive">
                <table class="table table-bordered" id="buildingTbl">
                    <tr>
                        <th>Level No</th>
                        <th>Level Code</th>
                        <th>Level Name</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td width="15%"><input name="lvl_no[]" class="form-control no-border" type="text" required></td>         
                        <td><input name="lvl_code[]" class="form-control no-border" type="text" required></td>
                        <td><input name="lvl_name[]" class="form-control no-border" type="text" required></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
            <input type="hidden" name="blg_id" value="<?php echo $Blg_id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addLvl" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Add Level -->

<!-- Modal Edit Level -->
<div class="modal fade bd-example-modal-lg" id="editLevel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-bars" aria-hidden="true"></i> Edit Level</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-group">
              <label> Level No: </label>
              <input type="text" id="lno" name="e_lno" class="form-control" maxlength="30">
        </div>
        <div class="form-group">
              <label> Level Code: </label>
              <input type="text" id="lcode" name="e_lcode" class="form-control" maxlength="30">
        </div>
        <div class="form-group">
              <label> Level Name: </label>
              <input type="text" id="lname" name="e_lname" class="form-control" maxlength="40">
        </div>
            <input type="hidden" name="e_lId" id="lId" value="">
            <input type="hidden" name="blg_id" value="<?php echo $Blg_id?>">
            <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editLvl" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Level -->

<script>

$(document).ready(function(){
var count = 1;
    $('#addBtn').click(function(){
    count = count + 1;
    var html_code = "<tr id='row"+count+"'>";
    html_code += "<td><input name='lvl_no[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><input name='lvl_code[]' class='form-control no-border' type='text' required></td>";
    html_code += "<td><input name='lvl_name[]' class='form-control no-border' type='text' required></td>";
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
    $(document).on('click', '.editLevel', function() {
        $('#editLevel').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       $('#lId').val(data[0]);
       $('#lno').val(data[1]);
       $('#lcode').val(data[2]);
       $('#lname').val(data[3]);
    });
});
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>