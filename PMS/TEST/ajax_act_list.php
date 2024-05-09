<?php
include('security.php');
error_reporting(0);

if(isset($_POST['flt_id']))
{
    $flt_id = $_POST['flt_id'];
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th>Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center ">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
if(isset($_POST['dept_id']))
{
    $dept_id = $_POST['dept_id'];
    if(isset($_POST['blg_id'])){
        $blg_id=$_POST['blg_id'];
        $q_flat="SELECT f.Flat_Id FROM flat as f 
        LEFT JOIN level as l on l.Lvl_Id = f.Lvl_Id
        LEFT JOIN building as blg on blg.Blg_Id = l.Blg_Id
        WHERE blg.Blg_Id='$blg_id'";
        $blg_id_run=mysqli_query($connection,$q_flat);
        $row_f = mysqli_fetch_assoc($blg_id_run);
        $flt_id=$row_f['Flat_Id'];
    }
    else{
    $flt_id = $_POST['flatt_id'];
    }
     // get all activity category
     $cat_id_q = "SELECT Act_Cat_Id FROM activity_category WHERE Act_Cat_Status=1 AND Dept_Id='$dept_id'";
     $cat_id_q_run = mysqli_query($connection, $cat_id_q);
     if(mysqli_num_rows($cat_id_q_run)>0)
     {
         while($row_id = mysqli_fetch_assoc($cat_id_q_run))
         {
             $id_arr[] = $row_id['Act_Cat_Id'];
         }
         $act_cat_id=implode("', '", $id_arr);
     }
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1 and Act_Cat_Id in ('$act_cat_id')";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th >Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
if(isset($_POST['act_cat_id']))
{
    $act_cat = $_POST['act_cat_id'];

    $flt_id = $_POST['flat_id'];
    $query = "SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1 and Act_Cat_Id ='$act_cat'";
    $query_run = mysqli_query($connection, $query);

    $qf = "SELECT * FROM flat where Flat_Id='$flt_id'";
    $q_runf=mysqli_query($connection, $qf);
    $row_f = mysqli_fetch_assoc($q_runf);
    $flt_name = $row_f['Flat_Code'].' - '.$row_f['Flat_Name'];
    $table = '
    <h5 class="text-primary">'.$flt_name.'</h5>
    <table class="table table-bordered table-striped" id="actTable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th class="w-30">Activity </th>
            <th>Activity Category</th>
            <th>Department</th>
            <th>Progress</th>
            <th>Status</th>
            <th>Date Done</th>
            <th >Record</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id= $row['Act_Id'];

            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
            $q_run1=mysqli_query($connection, $q1);
            $row1 = mysqli_fetch_assoc($q_run1);

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            
            $act_cat_id = $row2['Act_Cat_Id'];
            $q3="SELECT * from activity_category where Act_Cat_Id='$act_cat_id'";
            $q_run3=mysqli_query($connection, $q3);
            $row3 = mysqli_fetch_assoc($q_run3);

            $dept_id=$row3['Dept_Id'];
            $q4="SELECT * from department where Dept_Id='$dept_id'";
            $q_run4=mysqli_query($connection, $q4);
            $row4 = mysqli_fetch_assoc($q_run4);

            $pct =$row['Asgd_Pct_Done'];
            if($pct==0){
                $pct='To Do';
            }
            elseif($pct<100){
                $pct='Ongoing';
            }
            else{$pct='Done';}
    $table .= '
    <tr>
        <td class="d-none">'.$row['Asgd_Act_Id'].'</td>
        <td>'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</td>
        <td>'.$row3['Act_Cat_Name'].'</td>
        <td>'.$row4['Dept_Name'].'</td>
        <td>'.$row['Asgd_Pct_Done'].'</td>
        <td>'.$pct.'</td>
        <td>'.$row['Asgd_Act_Date_Completed'].'</td>
        <td class="btn-group text-center ">
            <!-- manage -->
            <button type="button" class="btn btn-info record">
                Record <i class="fa fa-history" area-hidden="true"> </i> 
            </button>
        </td>
    </tr>';
        }
    }
    else
    {
    echo "No Record Found";
    }
    $table .='
    </tbody>
                </table>
    ';
    echo $table;
}
?>