<?php
include('../../security.php');
if(isset($_POST['act_id']))
{
    $act_id = $_POST['act_id'];
    $query = "SELECT * FROM assigned_material as am LEFT JOIN material as mat on mat.Mat_Id = am.Mat_Id WHERE am.Asgd_Mat_Status=1 and am.Act_Id='$act_id' and mat.Mat_Status=1";
    $query_run = mysqli_query($connection,$query);
    $table = '
    
    <div class="table-responsive">
    <table class="table table-bordered " id="mat_tbl" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th>Code</th>
            <th>Material Description</th>
            <th>Action</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while ($row = mysqli_fetch_assoc($query_run))
        {
            $table .='
            <tr>
                <td class="d-none">'.$row['Asgd_Mat_Id'].'</td>
                <td>'.$row['Mat_Code'].'</td>
                <td>'.$row['Mat_Desc'].'</td>
                <td>
                    <!-- DELETE -->
                    <form action="code1.php" method="POST">
                        <input type="hidden" name="mat_id" value="'.$row['Asgd_Mat_Id'].'">
                        <button type="submit" name="delMatBtn" class="btn btn-danger">
                            <i class="fa fa-trash" area-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>
            ';
        }
    }
    $table .='
        </tbody>
    </table>
    </div>
    ';

    echo $table;
    ?>
    <script>
        var act_id = <?php echo $act_id?>;
        $('#act_id_m').val(act_id);
    </script>
    <?php
}
?>