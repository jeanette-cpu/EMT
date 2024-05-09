<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' limit 1";
$query_run2=mysqli_query($connection,$sql);
$row2 = mysqli_fetch_assoc($query_run2);
$user_id = $row2['USER_ID'];
$query="SELECT * FROM asgn_emp_to_prj WHERE Asgd_Emp_to_Prj_Status=1 and User_ID='$user_id'";
$query_run = mysqli_query($connection, $query);
?>
<div class="col-xl-12 col-lg-12">
    <div class="card-body">
    <!-- <h5 class="mb-4">Projects</h5> -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="d-none"> </th>
                    <th>Projects</th>
                    <th>Daily Report</th>
                    <!-- <th>Generate Report</th> -->
                </thead>
                <tbody>
                <?php
                    if(mysqli_num_rows($query_run)>0)
                    {
                        while($row = mysqli_fetch_assoc($query_run))
                        { 
                            $prj_id= $row['Prj_Id'];
                            $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
                            $run=mysqli_query($connection, $q_prj_name);
                            $row3 = mysqli_fetch_assoc($run);
                            ?>
                    <tr>
                        <td class="d-none"></td>
                        <td><?php echo $row3['Prj_Code'].' - '.$row3['Prj_Name']?></td>
                        <td>
                            <form action="DE_daily_rpt.php" method="POST"> 
                                <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                <button type="submit" name="Daily_Btn" class="btn btn-success "><i class="fa fa-file-text mr-2" aria-hidden="true"></i>Daily Report</button>
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
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>