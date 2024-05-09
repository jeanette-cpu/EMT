<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>
<?php
    if(isset($_POST['flatBtn']) or isset($_GET['id']))
    {
        if(isset($_POST['flatBtn']))
        {
            $Lvl_Id = $_POST['lvl_id'];
            $prj_name = $_POST['prj_name'];
        }
        else{
            $Lvl_Id = $_GET['id'];
            $prj_name = $_GET['prj_name'];
        }
        $query = "SELECT * FROM flat WHERE Lvl_Id='$Lvl_Id' AND Flat_Status='1'";
        $query_run = mysqli_query($connection, $query);

    }
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-0 font-weight-bold text-primary">Manage Flats</h5>
                    
                </div>
                <!-- BUTTON -->
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addRoom">
                        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Add Flat
                    </button>
                </div>
                <h5 class="ml-2 mt-2"><?php echo $prj_name?></h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Flat Code</th>
                        <th>Flat Name</th>
                        <th>No. of Activities</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $flatId = $row['Flat_Id'];
                                $query2 = "SELECT COUNT(Asgd_Act_Id) as count FROM assigned_activity WHERE Flat_Id='$flatId' AND Asgd_Act_Status=1";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Flat_Id']?></td>
                            <td><?php echo $row['Flat_Code']?></td>
                            <td><?php echo $row['Flat_Name']?></td>
                            <td><?php echo $row2['count'] ?></td>
                            <td class="btn-group text-center">
                                <!-- MANAGE -->
                                <form action="p_asgn_activity.php" method="POST">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name.' - '.$row['Flat_Code'].' '.$row['Flat_Name']?>">
                                    <input type="hidden" name="flat_id" value="<?php echo $row['Flat_Id']?>">
                                    <button type="submit" name="actBtn" class="btn btn-info">
                                        <i class="fa fa-cog" area-hidden="true"></i>
                                    </button>
                                </form>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editFlat">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code1.php" method="POST">
                                    <input type="hidden" name="flat_id" value="<?php echo $row['Flat_Id']?>">
                                    <input type="hidden" name="lvl_id" value="<?php echo $Lvl_Id?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
                                    <button type="submit" name="delFlat" class="btn btn-danger">
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

<!-- Modal Add Flat -->
<div class="modal fade bd-example-modal-lg" id="addRoom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cube" aria-hidden="true"></i> Add Flat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            
    <form action="code1.php" method="POST">
      <div class="modal-body">
        <div class="form-row">
            <div class="col-1">
                <input type="text" id="row_val" class="form-control form-control-sm ml-2">
            </div>
            <div class="col-3">
                <button type="button" id="addRow" class="btn btn-success btn-sm ml-1">+ Rows</button>
            </div>
        </div>
        <!-- THE FORM --> 
            
            <div class="table-responsive mt-2  ">
                <table class="table table-bordered" id="buildingTbl">
                    <tr>
                        <th>Flat Code</th>
                        <th>Flat Name</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><input name="flat_code[]" class="form-control no-border" type="text"></td>
                        <td><input name="flat_name[]" class="form-control no-border" type="text"></td>
                        <td></td>
                    </tr>
                </table>
                <div align="right">
                    <button type="button" name="add" id="addBtn" class="btn btn-success btn-xs">+</button>
                </div>
            </div>
            <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
            <input type="hidden" name="lvl_id" value="<?php echo $Lvl_Id?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addFlat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD User -->

<!-- Modal Edit Flat -->
<div class="modal fade bd-example-modal-lg" id="editFlat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cube" aria-hidden="true"></i> Edit Flat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

    <form action="code1.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <div class="form-group">
              <label> Flat Code: </label>
              <input type="text" id="fcode" name="e_fcode" class="form-control" maxlength="30">
        </div>
        <div class="form-group">
              <label> Flat Name: </label>
              <input type="text" id="fname" name="e_fname" class="form-control" maxlength="40">
        </div>
            <input type="hidden" name="e_fId" id="fId" value="">
            <input type="hidden" name="l_id" value="<?php echo $Lvl_Id?>">
            <input type="hidden" name="prj_name" value="<?php echo $prj_name?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editFlat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
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
    html_code += "<td><input name='flat_code[]' class='form-control no-border' type='text'></td>";
    html_code += "<td><input name='flat_name[]' class='form-control no-border' type='text'></td>";
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
    $(document).on('click', '.editFlat', function() {
        $('#editFlat').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       $('#fId').val(data[0]);
       $('#fcode').val(data[1]);
       $('#fname').val(data[2]);
    });
});
$(document).ready(function(){//1st group n 2nd
    var d=1;
    $('#addRow').click(function(){
        var row = $('#row_val').val();
        if(row==0){
            var row_html='<tr id="rrow'+d+'">';
                row_html+='<td><input name="flat_code[]" class="form-control no-border" type="text"></td>';
                row_html+='<td><input name="flat_name[]" class="form-control no-border" type="text"></td>';
                row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button></td></tr>';
            $('#buildingTbl').append(row_html);
            d++;
        }
        if(row!=0 || row != null){
            for(i=0;i<row;i++){
                var row_html='<tr id="rrow'+d+'">';
                row_html+='<td><input name="flat_code[]" class="form-control no-border" type="text"></td>';
                row_html+='<td><input name="flat_name[]" class="form-control no-border" type="text"></td>';
                row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button></td></tr>';
                $('#buildingTbl').append(row_html);
                d++;
            }
        }
    });
});
$(document).ready(function(){
    $(document).on('click', '.rremove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    }); 
}); 
</script>

<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>