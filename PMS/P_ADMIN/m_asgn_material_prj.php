<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
$query = "SELECT * FROM project WHERE Prj_Status =1";
$query_run = mysqli_query($connection, $query);
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Assign Material Quantity</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                    if(mysqli_num_rows($query_run)>0)
                    {
                        while($row = mysqli_fetch_assoc($query_run))
                        {
                    ?>
                        <tr>
                            <td><?php echo $row['Prj_Code']?></td>
                            <td><?php echo $row['Prj_Name']?></td>
                            <td>
                                <!-- VIEW -->
                                <form action="m_asgn_material.php" method="POST">
                                    <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id'];?>">
                                    <button type="submit" name="prjMatBtn" class="btn btn-info">
                                        <i class="fa fa-cog" area-hidden="true"></i>
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


<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>