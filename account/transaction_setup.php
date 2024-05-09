<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
include('accQuery.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-exchange mr-1" aria-hidden="true"></i>Transaction Type
                    <!-- BUTTON -->
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTransType">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Transaction Type
                    </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none">Transaction Id</th>
                                <th>Transaction Code</th>
                                <th>Trasaction Desc</th>
                                <th class="d-none">Sign</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                        <?php 
                        if(mysqli_num_rows($q_transac_type_run)>0){
                            while($row=mysqli_fetch_assoc($q_transac_type_run)){
                                $trans_type_id=$row['Transaction_Type_Id'];
                                $trans_code=$row['Transaction_Type_Code'];
                                $trans_desc=$row['Transaction_Type_Name'];
                                $trans_sign=$row['Transaction_Sign'];
                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $trans_type_id;?></td>
                                    <td><?php echo $trans_code;?></td>
                                    <td><?php echo $trans_desc?></td>
                                    <td class="d-none"><?php echo $trans_sign;?></td>
                                    <td class="btn-group text-center">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editTransType">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="trans_type_id" value="<?php echo $trans_type_id?>">  
                                            <button type="submit" name ="delTransType" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php 
                            }
                        }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-circle-o mr-1" aria-hidden="true"></i>Transaction Status
                    <!-- BUTTON -->
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTransStatus">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Transaction Status
                    </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="statTbl" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none">Status Id</th>
                                <th>Status Description</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                        <?php
                            if(mysqli_num_rows($q_transact_status_run)>0){
                                while($row1=mysqli_fetch_assoc($q_transact_status_run)){
                                    $trans_stat_id=$row1['Transaction_Status_Id'];
                                    $trans_stat_desc=$row1['Transaction_Status_Description'];
                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $trans_stat_id;?></td>
                                    <td><?php echo $trans_stat_desc?></td>
                                    <td class="btn-group text-center">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editTransStatus">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="trans_stat_id" value="<?php echo $trans_stat_id;?>">  
                                            <button type="submit" name ="delTransStatus" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php 
                                }
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-folder mr-1" aria-hidden="true"></i>Transaction Category
                    <!-- BUTTON -->
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCategory">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Category
                    </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="catTbl" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none">Category Id</th>
                                <th>Category Code</th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                        <?php
                            if(mysqli_num_rows($q_cat_run)>0){
                                while($row_cat=mysqli_fetch_assoc($q_cat_run)){
                                    $cat_id=$row_cat['Transaction_Category_Id'];
                                    $cat_code=$row_cat['Transaction_Cat_Code'];
                                    $cat_name=$row_cat['Transaction_Category_Description'];
                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $cat_id;?></td>
                                    <td><?php echo $cat_code?></td>
                                    <td><?php echo $cat_name?></td>
                                    <td class="btn-group text-center">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editCat">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">  
                                            <button type="submit" name ="delCat" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php 
                                }
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-key mr-1" aria-hidden="true"></i>Cheque Codes
                    <!-- BUTTON -->
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addCode">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                            Add Code
                    </button></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="chqTbl" width="100%" cellspacing="0">
                            <thead>
                                <th class="d-none">Code Id</th>
                                <th>Cheque Code</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                        <?php
                            if(mysqli_num_rows($q_codes_run)>0){
                                while($row2=mysqli_fetch_assoc($q_codes_run)){
                                    $code_id=$row2['Chq_Code_Id'];
                                    $code=$row2['Chq_Code'];
                        ?>
                                <tr>
                                    <td class="d-none"><?php echo $code_id;?></td>
                                    <td><?php echo $code?></td>
                                    <td class="btn-group text-center">
                                        <!-- EDIT -->
                                        <button type="button" class="btn btn-success editCode">
                                            <i class="fa fa-edit" area-hidden="true"></i>
                                        </button>
                                        <!-- DELETE -->
                                        <form action="code.php" method="POST">  
                                            <input type="hidden" name="chqCode_id" value="<?php echo $code_id;?>">  
                                            <button type="submit" name ="delCode" class="btn btn-danger">
                                                <i class="fa fa-trash" area-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php 
                                }
                            }
                        ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Transaction Type -->
<div class="modal fade bd-example-modal-md" id="addTransType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exchange" aria-hidden="true"></i> Add Transaction Type</h5>
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
                        <label for="">Transaction Code</label>
                        <input type="text" name="trans_type_code" class="form-control" required>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Transaction Name</label>
                        <input type="text" name="trans_type_name" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="form-row d-none">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Sign</label>
                        <select name="trans_type_sign" id="" class="form-control" require> 
                            <option value="Positive">Positive (+)</option>
                            <option value="Negative">Negative (-)</option>
                            <option value="Cancelled">Cancel</option>
                        </select>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addTransType" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Transaction Type -->
<!-- Modal Add Transaction Status -->
<div class="modal fade bd-example-modal-md" id="addTransStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exchange" aria-hidden="true"></i> Add Transaction Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Transaction Description</label>
                        <input type="text" name="trans_desc" class="form-control" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addTransacStatus" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Transaction Status -->
<!-- Modal EDIT Transaction Type -->
<div class="modal fade bd-example-modal-md" id="editTransType" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-exchange" aria-hidden="true"></i> Edit Transaction Type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="trans_type_id" id="trans_type_id" value="">
            <div class="form-group">
                <label for="">Transaction Type Code</label>
                <input type="text" id="trans_type_code" name="trans_type_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="">Transaction Type Name</label>
                <input type="text" id="trans_type_name" name="trans_type_name" class="form-control" required>
            </div>
            <div class="form-group d-none">
                <label for="">Sign</label>
                <select name="trans_type_sign" id="trans_type_sign" class="form-control">
                    <option value="Positive">Positive (+)</option>
                    <option value="Negative">Negative (-)</option>
                    <option value="Cancelled">Cancel</option>
                </select>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editTransType" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Transaction Type -->
<!-- Modal EDIT Transaction Status -->
<div class="modal fade bd-example-modal-md" id="editTransStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-circle-o" aria-hidden="true"></i> Edit Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="trans_stat_id" id="trans_stat_id" value="">
            <div class="form-group">
                <label for="">Status Description</label>
                <input type="text" id="trans_desc" name="trans_desc" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editTransStatus" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Transaction Type -->
<!-- Modal Add Code -->
<div class="modal fade bd-example-modal-md" id="addCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-key" aria-hidden="true"></i> Add Code</h5>
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
                        <label for="">Code</label>
                        <input type="text" name="code" class="form-control" maxlength="6" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addCode" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Code -->
<!-- Modal EDIT code -->
<div class="modal fade bd-example-modal-md" id="editCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-key" aria-hidden="true"></i> Edit Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="code_id" id="code_id" value="">
            <div class="form-group">
                <label for="">Status Description</label>
                <input type="text" id="code" name="code" class="form-control" required>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editCode" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT code -->
<!-- Modal Add Cat -->
<div class="modal fade bd-example-modal-md" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Add Category</h5>
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
                        <label for="">Category Code</label>
                        <input type="text" name="cat_code" class="form-control" maxlength="6" required>
                    </div>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label for="">Category Name</label>
                        <input type="text" name="cat_name" class="form-control" maxlength="50" required>
                    </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addCat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Cat -->
<!-- Modal EDIT Cat -->
<div class="modal fade bd-example-modal-md" id="editCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-folder" aria-hidden="true"></i> Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
        <input type="hidden" name="cat_id" id="cat_id" value="">
            <div class="row">
                <div class="col-4">
                  <div class="form-group">
                      <label for="">Category Code</label>
                      <input type="text" id="cat_code" name="cat_code" class="form-control" required>
                  </div>
                </div>
                <div class="col-8">
                  <div class="form-group">
                      <label for="">Category Name</label>
                      <input type="text" id="cat_name" name="cat_name" class="form-control" required>
                  </div>
                </div>
            </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editCat" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal EDIT Cat -->
<script>
$(document).ready(function () {
    $(document).on('click', '.editTransType', function() {
        $('#editTransType').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#trans_type_id').val(data[0]);
       $('#trans_type_code').val(data[1]);
       $('#trans_type_name').val(data[2]);
       $('#trans_type_sign').val(data[3]);
    });
});
$(document).ready(function () {
    $(document).on('click', '.editTransStatus', function() {
        $('#editTransStatus').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#trans_stat_id').val(data[0]);
       $('#trans_desc').val(data[1]);
    });
});
$(document).ready(function () {
    $(document).on('click', '.editCode', function() {
        $('#editCode').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#code_id').val(data[0]);
       $('#code').val(data[1]);
    });
});
$(document).ready(function () {
    $(document).on('click', '.editCat', function() {
        $('#editCat').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       $('#cat_id').val(data[0]);
       $('#cat_code').val(data[1]);
       $('#cat_name').val(data[2]);
    });
});

$(document).ready(function () {
    $('#statTbl').DataTable({
        "searching": true,
        "bPaginate": true
    });
});
$(document).ready(function () {
    $('#chqTbl').DataTable({
        "searching": true,
        "bPaginate": true
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>