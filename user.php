<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
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
            <div class="form-group">
                <label> Username </label>
                <input type="text" pattern="[A-Za-z0-9_].{3,10}"name="username" id="addusername" class="form-control" placeholder="Enter Username" required>
            </div>
            <div class="form-group">
                <label> Email </label>
                <input type="email" name="email" id="addemail" class="form-control" placeholder="Enter Email">
            </div>
            <div class="form-group">
                <label> Password </label>
                <input type="password" pattern=".{5,10}" name="password" id="addpassword" class="form-control" placeholder="Enter Password" required>
            </div>
            <div class="form-group">
                <label> Confirm Password </label>
                <input type="password" pattern=".{5,10}" name="confirmpassword" id="addconfirmpass"class="form-control" placeholder="Confirm Password" required>
            </div>
            <input type="hidden" name="usertype" value="user">
            <input type="hidden" name="userstatus" value="1">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="registerbtn" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD User -->

<!-- EDIT Modal User -->
<div class="modal fade" id="EditUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                        <input type="hidden" name="edit_id" id="update_id" class="form-control" >
                    
                    <div class="form-group">
                        <label> Username </label>
                        <input type="text" name="edit_username" id="username" class="form-control" maxlength="15" required>
                    </div>
                    <div class="form-group">
                        <label> Email </label>
                        <input type="email" name="edit_email" id="email" class="form-control" placeholder="Enter Email" maxlength="15">
                    </div>
                    <div class="form-group">
                        <label> Usertype </label>
                            <select name="update_usertype" id="usertype" class="form-control" required>
                                <option value="admin"> Admin </option>
                                <option value="user"> User </option>
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

<div class="container-fluid">

<!-- Data Table Example -->
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

    <!-- TABLE -->
      <div class="table-responsive">
          <!-- CONNECTION TO DISPLAY DATA ON TABLE -->
        <?php
            // $connection = mysqli_connect("localhost","root","","emt");
            $query = "SELECT * FROM users WHERE USER_STATUS = 1";
            $query_run = mysqli_query($connection, $query);
        ?>
        <table class="table table-bordered table-striped" id="userTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none">ID</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Email</th>
                <!-- <th>Password</th> -->
                <th>Action</th>
            </thead>
            <tbody>
            <?php
                if(mysqli_num_rows($query_run)>0)
                {
                    while($row = mysqli_fetch_assoc($query_run))
                    {
                        ?>
                <tr>
                    <td class="d-none"><?php echo $row['USER_ID']; ?></td>                   
                    <td><?php echo $row['USERNAME']; ?></td>
                    <td><?php echo $row['USERTYPE']; ?></td>
                    <td><?php echo $row['USER_EMAIL']; ?></td>
                    
                    <td class="btn-group ">
                        <!-- EDIT button -->         
                            <button type="button" class="btn btn-success editbtn" data-toggle="modal" data-target="#EditUserModal">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                        <!-- DELETE button PERMANENT -->
                        <form action="code.php" method="POST">
                            <input type="hidden" name="delete_id" value="<?php echo $row['USER_ID'];?>">
                                <button type="submit" name ="delete_btn" class="btn btn-danger d-inline">
                                    <i class="fa fa-trash"></i>
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
            <tfoot>
                <tr>
                    <th class="d-none">ID</th>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Email</th>
                    <!-- <th>Password</th> -->
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
            </div>
        </div>
    </div>
</div>
<!--End container Fluid-->
<script>
$(document).ready(function(){
    $('#userTable').DataTable({
        pageLength: 10,
        "searching": true
    });
});
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>