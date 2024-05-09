<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
?>
<div class="container-fluid">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"> Edit Timesheet Details</h6>
    </div>
<?php
if(isset($_POST['edit_ts']))
{
    $emp_id = $_POST['emp_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $query="SELECT TS_ID, TS_DATE as dsort ,TS_DATE, TS_DAY_STATUS,TS_M_IN,TS_EVE_OUT,TS_RG_HRS, TS_OT_HRS,TS_HOL_OT_HRS,TS_B_HRS,TS_SP_HRS,TS_JB_NAME FROM time_sheet WHERE emp_id='$emp_id' AND MONTH(TS_DATE)='$month' AND YEAR(TS_DATE)='$year' order by dsort asc";
    $query_run = mysqli_query($connection,$query);
    $query_emp="SELECT EMP_NO, EMP_FNAME,EMP_LNAME,EMP_MNAME,EMP_SNAME,EMP_DESIGNATION,EMP_LOCATION FROM employee WHERE EMP_ID='$emp_id'";
    $query_run_emp = mysqli_query($connection, $query_emp);
}
?>
    <div class="card-body">

    
    <table style="width:100%; border-spacing: 0px; cellspacing:0px; border-collapse: collapse;">
    <h6>
    <?php
    foreach($query_run_emp as $row1)
    {
        ?>
        <tr align="left">
            <td width=10%><?php echo $row1["EMP_NO"]?></td>
            <td width=35%><?php echo $row1['EMP_FNAME'] ?><?php echo ' '.$row1['EMP_LNAME'] ?><?php echo ' '.$row1['EMP_MNAME'] ?><?php echo ' '.$row1['EMP_SNAME'] ?></td>
            <td width=8%></td>
            <td></td> 
            <!-- EMP_DEPARTMENT -->
        <tr>
        <tr>
            <td>Working As: </td>
            <td><?php echo $row1['EMP_DESIGNATION']?></td> 
            <!--EMP_DESIGNATION -->
            <td>Location: </td>
            <td><?php echo $row1['EMP_LOCATION']?></td> 
            <!-- EMP_LOCATION -->
        </tr>
      <?php  
    }
    ?></h6>
    </table>
        <div class="table-responsive pt-3 text-nowrap">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <th class="d-none">ID</th>
                    <th>Date</th>
                    <th>Status<b class="invisible">oooo</b></th>
                    <th>Morning In</th>
                    <th>Evening Out</th>
                    <th>Hours</th>
                    <th>OT (Hrs)</th>
                    <th>H. OT(Hrs)</th>
                    <th>Bonus (Hrs)</th>
                    <th>Special (Hrs)</th>
                    <th>Job Name<b class="invisible">ooooooooooo</b></th>
                    <th width="10%" class="th"></th>
                </thead>
                <tbody>
    <?php
    if(mysqli_num_rows($query_run)>0)
    {
        while($row=mysqli_fetch_assoc($query_run))
        {
            $timein= strtotime($row['TS_M_IN']);
            $timeout= strtotime($row['TS_EVE_OUT']);
        ?>
            <tr>
                    <form action="timesheet_queries.php" method="POST">
                <td class="d-none"><input type="hidden" name="ts_id" value="<?php echo $row['TS_ID']; ?>"></td>
                <td><input type="date" name="ts_date" class="form-control no-border" value="<?php echo $row['TS_DATE']?>"></td>
                <td><input name="ts_status" class="form-control no-border" type="text" value="<?php echo $row['TS_DAY_STATUS']?>"></td>
                <!-- <td>
                    <select class="form-control no-border" name="ts_status" value="">
                        <option>Present</option>
                        <option>Week Off</option>
                        <option>Leave</option>
                    </select>
                </td> -->
                <td><input name="ts_tIn" type="time" class="form-control no-border" value="<?php echo date('H:i',$timein) ?>"></td>
                <td><input name="ts_tOut" type="time" class="form-control no-border" value="<?php echo date('H:i',$timeout) ?>"></td>
                <td align="right"><input name="ts_rHrs" class="form-control no-border" type="number" step=".01" value="<?php echo ($row['TS_RG_HRS'])?>"></td>
                <td align="right"><input name="ts_otHrs" class="form-control no-border" type="number" step=".01" value="<?php echo ($row['TS_OT_HRS'])?>"></td>
                <td align="right"><input name="ts_holHrs" class="form-control no-border" type="number" step=".01" value="<?php echo ($row['TS_HOL_OT_HRS'])?>"></td>
                <td align="right"><input name="ts_bnsHrs" class="form-control no-border" type="number" step=".01" value="<?php echo ($row['TS_B_HRS'])?>"></td>
                <td align="right"><input name="ts_spHrs" class="form-control no-border" type="number" step=".01" value="<?php echo ($row['TS_SP_HRS'])?>"></td>
                <td><input name="ts_jbName" class="form-control no-border" type="text" value="<?php echo $row['TS_JB_NAME']?>"></td>
                <td class="btn-group">         
                        <button type="submit" name="edit_tsRow" class="btn btn-success">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </button>
                    </form>
                    <form action="timesheet_queries.php" method="POST">
                        <input type="hidden" name="ts_idD" value="<?php echo $row['TS_ID']; ?>">
                        <button type="submit" name="delete_tsRow" class="btn btn-danger">
                            <i class="fa fa-trash" aria-hidden="true"></i>
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
include('includes/scripts.php');
include('includes/footer.php');
?>