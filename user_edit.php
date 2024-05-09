<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="content-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> Edit User Details</h6>
    </div>
    <div class="card-body">
<?php

$connection = mysqli_connect("localhost","root","","emt");

if(isset($_GET['edit_btn']))
    {
        $id = $_GET['edit_id'];
                        
        $query ="SELECT * FROM users WHERE USER_ID='$id' ";
        $query_run = mysqli_query($connection, $query);

        foreach($query_run as $row)
        {
            ?>

            <form action="code.php" method="POST">
                <!-- THE FORM -->

                <input type="hidden" name="edit_id" value="<?php echo $row['USER_ID'] ?>">

                <div class="form-group">
                    <label> Username </label>
                    <input type="text" name="edit_username" value="<?php echo $row['USERNAME'] ?>" class="form-control" placeholder="Enter Username">
                </div>
                <div class="form-group">
                    <label> Email </label>
                    <input type="email" name="edit_email" value="<?php echo $row['USER_EMAIL'] ?>" class="form-control" placeholder="Enter Email">
                </div>
                <!-- USERTYPE -->
                <div class="form-group">
                    <label> Usertype </label>
                    <select name="update_usertype" class="form-control">
                        <option value="admin"> Admin </option>
                        <option value="user"> User </option>
                    </select>
                </div>
                <!-- PASSWORD -->
                <div class="form-group">
                    <label> Password </label>
                    <input type="password" name="edit_password" value="<?php echo $row['USER_PASSWORD'] ?>" class="form-control" placeholder="">
                </div>

                <a href="user.php" class="btn btn-danger">Cancel</a>
                <button type="submit" name="updatebtn" class="btn btn-primary"> Update </button>
            </form>
            <?php
        }
    }
?>
    </div>
        <!-- END FORM -->
    </div>
</div>

<!-- </div> -->
<!-- container fluid -->
</div>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>