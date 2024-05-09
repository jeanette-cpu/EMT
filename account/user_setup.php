<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
include('accQuery.php'); 
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Users
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add Users
                </button></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
                $query = "SELECT * FROM users WHERE USER_STATUS=1 AND USERTYPE='account' OR USERTYPE='account_asst'";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Username</th>
                        <th>Type</th>
                        <th class="d-none"></th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0){
                            while($row = mysqli_fetch_assoc($query_run)){
                        ?>
                        <tr>
                            <td class="d-none"><?php echo $row['USER_ID']; ?></td>
                            <td><?php echo $row['USERNAME']; ?></td>
                            <td><?php 
                                $usertype=$row['USERTYPE'];
                                if($usertype=="account")
                                { echo 'Account Admin';}
                                else if($usertype=="account_asst")
                                { echo 'Account Assistant';}
                                ?>
                            </td>
                            <td class="d-none">
                                <?php echo $usertype;?>
                            </td>
                            <td class="btn btn-group">
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success edUsers" data-toggle="modal" data-target="#EditUsersModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <form action="code.php" method="post">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['USER_ID']?>">
                                    <button type="submit" name="delete_btn" class="btn btn-danger">
                                        <i class="fa fa-trash" area-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                else{ echo "No Record Found";}
            ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add User -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-user-plus" aria-hidden="true"></i> Add User</h5>
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
                    <label> Username </label>
                    <input type="text" pattern="[A-Za-z0-9_].{3,20}" name="username" id="addusername" class="form-control" placeholder="Enter Username" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Usertype</label>
                    <select name="usertype" id="usertype" class="form-control" required>
                        <option value="">Select Usertype</option>
                        <option value="account">Account Admin</option>
                        <option value="account_asst">Account Assistant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label> Password </label>
            <input type="password" name="password" id="addpassword" class="form-control" placeholder="Enter Password" required>
        </div>
        <div class="form-group">
            <label> Confirm Password </label>
            <input type="password"  name="confirmpassword" id="addconfirmpass"class="form-control" placeholder="Confirm Password" required>
        </div>
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addUser" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD User -->
<!-- EDIT Modal User -->
<div class="modal fade" id="EditUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel">Edit User Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
      </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <!-- THE FORM -->
                    <input type="hidden" name="user_update_id" id="user_update_id" class="form-control" >
                    <div class="form-group">
                        <label> Username </label>
                        <input type="text" name="edit_username" id="user_username" class="form-control" maxlength="40" required>
                    </div>
                    <div class="form-group">
                        <label>User Type</label>
                        <select name="edit_type" id="edit_type" class="form-control" required>
                            <option value="account">Account Admin</option>
                            <option value="account_asst">Account Assistant</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label> Password </label>
                        <input type="password" name="edit_password" id="password" class="form-control" placeholder="Enter Password" maxlength="15" required>
                    </div>                   
                    <input type="hidden" name="userstatus" value="1">
                <!-- END FORM -->
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
                        <button type="submit" name="updatebtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Update</button>
                    </div>

    </div>
  </div>
</div>
<!-- End EDIT Modal User -->

<script>
$(document).ready(function () {
    $(document).on('click', '.edUsers', function() {
        $('#EditUser').modal('show');
        $tr = $(this).closest('tr');
        // var usertype = "";
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#user_update_id').val(data[0]);
        $('#user_username').val(data[1]);
        // alert(val(data[3]));
        // $('#edit_type select').val(data[3]);
        
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>