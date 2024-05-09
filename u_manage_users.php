<?php
include('security.php');
include('includes/header.php'); 
include('includes/prj_admin_navbar.php'); 
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
                <label> Password </label>
                <input type="password" pattern=".{5,10}" name="password" id="addpassword" class="form-control" placeholder="Enter Password" required>
            </div>
            <div class="form-group">
                <label> Confirm Password </label>
                <input type="password" pattern=".{5,10}" name="confirmpassword" id="addconfirmpass"class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
                <label>Usertype</label>
                    <select name="usertype" id="usertype" class="form-control" required>
                        <option>- select usertype -</option>
                        <option value="planning_eng"> Project Administrator </option>
                        <option value="proj_mgr"> Project Manager </option>
                        <option value="str_mgr"> Store Manager </option>
                        <option value="foreman"> Foreman </option>
                    </select>
            </div>
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
<div class="modal fade" id="EditUsersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="text" name="user_username" id="user_username" class="form-control" maxlength="15" required>
                    </div>
                    <div class="form-group">
                        <label> Usertype </label>
                            <select name="update_usertype" id="user_usertype" class="form-control" required>
                                <option value="planning_eng"> Project Administrator </option>
                                <option value="prj_mgr"> Project Manager </option>
                                <option value="store_mgr"> Store Manager </option>
                                <option value="foreman"> Foreman </option>
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
                $query = "SELECT * FROM users WHERE USERTYPE='planning_eng' or USERTYPE='proj_mgr' or USERTYPE='str_mgr' or USERTYPE='foreman' AND USER_STATUS = 1";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Username</th>
                        <th>Type</th>
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
                            <td><?php 
                                $usertype=$row['USERTYPE'];
                                if($usertype=="planning_eng")
                                { echo 'Project Administrator';}
                                else if($usertype=="proj_mgr")
                                { echo 'Project Manager';}
                                else if($usertype=="str_mgr")
                                { echo 'Store Manager';}
                                else if($usertype=="foreman")
                                { echo 'Foreman';}
                                ?>
                            </td>
                            <td>
                                <!-- EDIT -->
                                <button type="button" class="btn btn-success editUsersbtn" data-toggle="modal" data-target="#EditUsersModal">
                                    <i class="fa fa-edit" area-hidden="true"></i>
                                </button>
                                <!-- DELETE -->
                                <button type="button" class="btn btn-danger">
                                    <i class="fa fa-trash" area-hidden="true"></i>
                                </button>
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
                            <th class="d-none"></th>
                            <th>Username</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>