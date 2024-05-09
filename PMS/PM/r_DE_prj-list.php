<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/pm_navbar.php'); 
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' limit 1";
$query_run2=mysqli_query($connection,$sql);
$row_u = mysqli_fetch_assoc($query_run2);
$user_id = $row_u['USER_ID']; 
$query="SELECT * FROM project as p LEFT JOIN asgn_emp_to_prj as ass_e on ass_e.Prj_Id = p.Prj_Id WHERE p.Prj_Status =1 and ass_e.User_Id='$user_id'";
$query_run = mysqli_query($connection, $query);
?>
<div class="col-xl-12 col-lg-12">
    <div class="card-body">
    <h4 class="mb-4 text-primary">Report</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="d-none"> </th>
                    <th>Projects</th>
                    <th>Daily Report</th>
                    <th>Generate Report</th>
                </thead>
                <tbody>
                <?php
                    if(mysqli_num_rows($query_run)>0)
                    {
                        while($row = mysqli_fetch_assoc($query_run))
                        { 
                            ?>
                    <tr>
                        <td class="d-none"></td>
                        <td><?php echo $row['Prj_Code'].' - '.$row['Prj_Name']?></td>
                        <td>
                            <form action="r_DE_daily_rpt.php" method="POST"> 
                                <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id']?>">
                                <button type="submit" name="Daily_Btn" class="btn btn-success "><i class="fa fa-file-text mr-2" aria-hidden="true"></i>Daily Report</button>
                            </form>
                        </td>
                        <td>
                            <form action="r_DE_gen_rpt.php" method="POST">
                                <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id']?>">
                                <button type="submit" name="Gen_Btn" class="btn btn-info "><i class="fa fa-cogs mr-2" aria-hidden="true"></i>Generate Report</button>
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