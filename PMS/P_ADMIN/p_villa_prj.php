<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Villa Projects</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
                $query = "SELECT * FROM project WHERE Prj_Status ='1' and Prj_Category='Villa'";
                $query_run = mysqli_query($connection, $query);
            ?>
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th>Project Code</th>
                        <th>Project Name</th>
                        <th>No. of Villa</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                    <?php
                        if(mysqli_num_rows($query_run)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                $prjId=$row['Prj_Id'];
                                $query2 = "SELECT Count(Villa_Id) as count FROM villa WHERE Prj_Id='$prjId' AND Villa_Status=1";
                                $query_run2=mysqli_query($connection,$query2);
                                $row2 = mysqli_fetch_assoc($query_run2);
                                ?>
                        <tr>
                            <td class="d-none"><?php echo $row['Prj_Id']?></td>
                            <td><?php echo $row['Prj_Code']?></td>
                            <td><?php echo $row['Prj_Name']?></td>
                            <td><?php echo $row2['count']?></td>
                            <td>
                                <!-- EDIT -->
                                <form action="p_villa.php" method="POST">
                                    <input type="hidden" name="prj_id" value="<?php echo $row['Prj_Id']?>">
                                    <button type="submit" name="villa_prj" class="btn btn-info">
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