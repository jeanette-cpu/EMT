<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
error_reporting(0);
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
$query_run2=mysqli_query($connection,$sql);
$row2 = mysqli_fetch_assoc($query_run2);
$user_id = $row2['USER_ID'];
$query="SELECT * FROM asgn_emp_to_prj WHERE Asgd_Emp_to_Prj_Status=1 and User_ID='$user_id'";
$query_run = mysqli_query($connection, $query);
?>
<div class="container-fluid">
    <div class="card-body">
        <h5 class="mb-4">Projects Assigned</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="" width="100%" cellspacing="0">
                    <thead>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Update</th>
                        <!-- <th>Standard Activity</th> -->
                    </thead>
                    <tbody>
    <?php
        if(mysqli_num_rows($query_run)>0)
        {
            // $prj_id_arr[]=null;
            while($row = mysqli_fetch_assoc($query_run))
            { 
                $prj_id= $row['Prj_Id'];
                $prj_id_arr[]=$prj_id;
                $q_prj_name="SELECT Prj_Name,Prj_Code,Prj_Category from project where Prj_Id='$prj_id'";
                $run=mysqli_query($connection, $q_prj_name);
                $row3 = mysqli_fetch_assoc($run);
                $prj_name=$row3['Prj_Code'].' - '.$row3['Prj_Name'];
                $category=$row3['Prj_Category'];
                ?>
                    <tr>
                        <td><?php echo $prj_name;?></td>
                        <td><?php echo $category?></td>
                        <td>
                        <?php
                            // DAILY UPDATE
                            if($category=='Villa'){
                            ?>
                                <form action="activity.php"  method="POST">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                    
                                    <button type="submit" name="actBtn" class="btn d-none btn-primary mb-2 btn-block">Daily Update</button><br>
                                </form>
                            <?php
                            }
                            else{
                            ?>
                                <form action="activity_b.php" method="POST">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                    <button type="submit" name="actBtn" class="btn d-none btn-primary mb-2 btn-block">Daily Update</button><br>
                                </form>
                                <?php 
                            }
                            // MULTIPLE UPDATE
                            if($category=='Villa'){
                                ?>
                                    <form action="activity_mult.php" method="POST">
                                        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                        
                                        <button type="submit" name="actBtn" class="btn btn-primary mb-2 btn-block">Update</button><br>
                                    </form>
                                <?php
                                }
                                else{
                                ?>
                                    <form action="activity_b_mult.php" method="POST">
                                        <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                        <button type="submit" name="actBtn" class="btn btn-primary mb-2 btn-block">Update</button><br>
                                    </form>
                                    <?php 
                                }
                            ?>
                            
                        </td>
                        <!-- <td>
                            <form action="activity2.php" method="POST">
                                <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                <button type="submit" name="actBtn" class="btn btn-primary mb-2 btn-block">Update</button><br>
                            </form>
                            <form action="edit_prev.php" method="POST">
                                <input type="hidden" name="prj_id" value="<?php echo $prj_id?>">
                                <button type="submit" name="reportBtn" class="btn btn-secondary mb-2 btn-block">Previous Activity</button><br>
                            </form>
                        </td> -->
                    </tr>
                <?php
            }
            $prj_ids=implode("', '",$prj_id_arr);
        }
        else{
            echo "<font color='red'>Warning: No projects assigned to this user.</font><br>";
        }       
    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
$q_prj1="SELECT Prj_Id, Prj_Name,Prj_Code,Prj_Category from project where Prj_Id IN ('$prj_ids')";
$q_prj1_run=mysqli_query($connection,$q_prj1);
$q_prj2="SELECT Prj_Id, Prj_Name,Prj_Code,Prj_Category from project where Prj_Id IN ('$prj_ids')";
$q_prj2_run=mysqli_query($connection,$q_prj2);
$query1 = "SELECT * FROM activity WHERE Act_Status='1'";
$query_run1 = mysqli_query($connection, $query1);
?>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4 mt-2">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="font-weight-bold text-primary">Activity List</h5>
                        </div>
                        <div class="col-6">
                            <select name="" id="prj_optAct" class="form-control  prj_optAct">
                                <option value="">Select Project</option>
                            <?php
                                if(mysqli_num_rows($q_prj1_run)>0){
                                    while($row1=mysqli_fetch_assoc($q_prj1_run)){
                                        $prj_id1=$row1['Prj_Id'];
                                        $prj_name=$row1['Prj_Code'].' - '.$row1['Prj_Name'];
                                        ?>
                                <option value="<?php echo $prj_id1;?>"><?php echo $prj_name?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm" id="actTable" width="100%" cellspacing="0">
                            <thead>
                                <th>Activity Code</th>
                                <th>Activity Name</th>
                                <!-- <th>Department</th> -->
                                <th>Activity Category</th>
                                <th>Standard</th>
                                <th>Prj Standard</th>
                            </thead>
                            <tbody>
                            <?php 
                            if(mysqli_num_rows($query_run1)>0)
                            {
                                while($row = mysqli_fetch_assoc($query_run1))
                                { 
                                    $dept_id = $row['Dept_Id'];
                                    $query2 = "SELECT Dept_Name from department WHERE Dept_Id='$dept_id'";
                                    $query_run2=mysqli_query($connection,$query2);
                                    $row2 = mysqli_fetch_assoc($query_run2);
    
                                    $act_cat_id = $row['Act_Cat_Id'];
                                    $query3 = "SELECT Act_Cat_Name from activity_category WHERE Act_Cat_Id='$act_cat_id'";
                                    $query_run3=mysqli_query($connection,$query3);
                                    $row3 = mysqli_fetch_assoc($query_run3);
                                    $emp_r=$row['Act_Emp_Ratio'];
                                    $output_r=$row['Act_Output_Ratio'];
    
                                    if($emp_r!=null && $output_r!=null){
                                        $emp_r = floor($emp_r);
                                        $output_r =floor($output_r);
                                        $ratio_s=$emp_r.':'.$output_r;
                                    }
                                    else{
                                        $ratio_s='not set';
                                    }
                                    $q_prj_std="SELECT * FROM activity_standard WHERE Prj_Id=''";
                                    ?>
                                    <tr>
                                        <td><?php echo $row['Act_Code']?></td>
                                        <td><?php echo $row['Act_Name']?></td>
                                        <!-- <td>< ?php echo $row2['Dept_Name'] ?></td> -->
                                        <td><?php echo $row3['Act_Cat_Name']?></td>
                                        <td><?php echo $ratio_s;?></td>
                                        <td><?php echo 'select prj';?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-4 mt-2">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="font-weight-bold text-primary">Levels/Plexes List</h5>
                        </div>
                        <div class="col-6">
                            <select name="" id="prj_optArea" class="form-control selectpicker prj_optArea">
                                <option value="">Select Project</option>
                            <?php
                                if(mysqli_num_rows($q_prj2_run)>0){
                                    while($row4=mysqli_fetch_assoc($q_prj2_run)){
                                        $prj_id2=$row4['Prj_Id'];
                                        $prj_name=$row4['Prj_Code'].' - '.$row4['Prj_Name'];
                                        ?>
                                            <option value="<?php echo $prj_id2;?>"><?php echo $prj_name?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm areaTable" id="areaTable" width="100%" cellspacing="0">
                            <thead>
                                <th>Code</th>
                                <th>Name</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#actTable').DataTable({
        "searching": true,
        "bPaginate": false
    });
}); 
$(document).ready(function () {
    $('#areaTable').DataTable({
        "searching": true,
        "bPaginate": true
    });
});
$(document).ready(function () {
    $(document).on('change','#prj_optArea', function(){
        var prj_id=$(this).val();
        $.ajax({
            type:'POST',
            url: 'ajax.php',
            data:{ 'area_tbl' : prj_id},
            success:function(data1){
                $(document).find('#areaTable').html(data1).change();
            }
        });
    });
});
$(document).ready(function () {
    $(document).on('change','.prj_optAct', function(){
        var prj_id=$(this).val();
        $.ajax({
            type:'POST',
            url: 'ajax.php',
            data:{ 'act_tbl' : prj_id},
            success:function(data){
                $(document).find('#actTable').html(data).change();
            }
        });
    });
});

</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>