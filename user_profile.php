<?php
include('security.php');
include('includes/header.php');
include('includes/user_navbar.php');
?>

<?php
// $connection = mysqli_connect("localhost","root","","emt");    
$username=$_SESSION['USERNAME'];
$mail=$username;
$query ="SELECT * 
        FROM employee
        LEFT JOIN users on employee.USER_ID = users.USER_ID 
        WHERE USERNAME='$mail' ";
$query_run = mysqli_query($connection, $query);
        
foreach($query_run as $row)
{
    ?>         

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Profile</h5>
        </div>
        <div class="card-body">
            <h3><?php echo ucfirst($row['EMP_FNAME']); ?><?php echo ' '.ucfirst($row ['EMP_MNAME']); ?><?php echo ' '.ucfirst($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?></h3>
            <h5 class="mt-4">Employee Number: <?php echo $row['EMP_NO']; ?></h5>
            <hr>

            <div class="row">
                <div class="col-sm-3">
                    <b><i class="fa fa-envelope-o mr-3" aria-hidden="true"></i>EMAIL :</b>       
                </div>
                <div class="col-sm-3">
                    <?php echo $row['USER_EMAIL']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <b><i class="fa fa-calendar mr-3" aria-hidden="true"></i>DATE OF JOIN :</b>
                </div>
                <div class="col-sm-3">
                    <?php echo $row['EMP_DOJ']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <b><i class="fa fa-id-card-o mr-2" aria-hidden="true"></i>DESIGNATION :</b>
                </div>
                <div class="col-sm-3">
                    <?php echo $row['EMP_DESIGNATION']; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <i class="fa fa-folder-o mr-3" aria-hidden="true"></i><b>PROJECT/LOCATION:</b>
                </div>
                <div class="col-sm-3">
                    <?php echo $row['EMP_LOCATION']; ?>
                </div>
            </div>         

        </div>
    </div>
<div>

<?php
        }
?>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>