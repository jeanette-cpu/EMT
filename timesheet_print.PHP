<?php
    include('security.php');

    $tot_RegHrs=0; $tot_otHrs=0;$tot_hOtHrs=0;$tot_bHrs=0;$tot_spHrs=0;
    function toDecimal($val) { // Convert any exponential sign to proper decimals
        $val = sprintf("%lf", $val); 
        if (strpos($val, '.') !== false) {
            $val = rtrim(rtrim($val, '0'), '.');
        }
        return $val;
    }
    if(isset($_POST['ts_print']))
    {
        $emp_id = $_POST['emp_id'];
        $emp_no = $_POST['emp_no'];
        $month = $_POST['month'];
        $year = $_POST['year'];

        $query="SELECT * FROM time_sheet LEFT JOIN employee on employee.EMP_ID = time_sheet.emp_id WHERE EMP_STATUS=1 AND MONTH(TS_DATE)='$month' AND YEAR(TS_DATE)='$year' AND time_sheet.EMP_ID='$emp_id' ORDER BY time_sheet.TS_DATE ASC ";
        $query_run = mysqli_query($connection,$query);

        $dateObt = DateTime::createFromFormat('!m',$month);
        $monthName = $dateObt->format('F');

        $query1="SELECT EMP_NO, EMP_FNAME, EMP_LNAME, EMP_MNAME, EMP_SNAME, EMP_DESIGNATION, EMP_LOCATION FROM employee WHERE emp_no ='$emp_no'";
        $query_run1=mysqli_query($connection,$query1);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
    <title>Print Time Sheet</title>
</head>
<style>
.table2{
    border: 1px solid black;
}
.th {
    border: 1px solid black;
  }
.timesheet{
font-size:105%;
visibility: hidden;
}
.timesheety{
    font-size:105%;
    visibility: hidden !important; 
}
@media print {
    html
    {
        zoom:85%;
        margin: 5mm 5mm 5mm 5mm;
    }
    .timesheet {visibility:visible;}
    @media screen {
    .timesheet {display: none}
    }
}
</style>
<div class="timesheet">
<!-- <body> -->
<body onload="window.print()">
<h5 align="center" style="font-family: calibri; font-size:15px">
    EMT Electromechanical Works L.L.C.<br>
    <?php echo ' '.strtoupper($monthName).' '.$year?> TIME SHEET
</h5>
<h6 style="font-family: calibri; font-size:13.5px">
    <table style="width:100%; border-spacing: 0px; cellspacing:0px; border-collapse: collapse;">
    <?php
    foreach($query_run1 as $row1)
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
    ?>
    </table>

<table class="table2" style="width:100%; border-spacing 0px; cellspacing:0px; border-collapse: collapse;">
    <thead>
        <th width=10% class="th">Date</th>
        <th width=9% class="th">Status</th>
        <th width=9% class="th">Morning In</th>
        <th width=9% class="th">Evening Out</th>
        <th width=8% class="th">Hours</th>
        <th width=7% class="th">OT (Hrs)</th>
        <th width=7% class="th">H. OT(Hrs)</th>
        <th width=7% class="th">Bonus (Hrs)</th>
        <th width=7% class="th">Special (Hrs)</th>
        <th class="th">Job Name</th>
    </thead>
    <tbody>
    <?php

    $days=cal_days_in_month(CAL_GREGORIAN,$month,$year);
    if($query_run)
    {
        $rows = mysqli_num_rows($query_run);
    }
    $ab_days=$days-$rows;

    if(mysqli_num_rows($query_run)>0)
    {
        while($row=mysqli_fetch_assoc($query_run))
        {
            $timein= strtotime($row['TS_M_IN']);
            $timeout= strtotime($row['TS_EVE_OUT']);
            $tot_RegHrs= $tot_RegHrs + $row['TS_RG_HRS'];
            $tot_otHrs = $tot_otHrs + $row['TS_OT_HRS'];
            $tot_hOtHrs = $tot_hOtHrs + $row['TS_HOL_OT_HRS'];
            $tot_bHrs = $tot_bHrs + $row['TS_B_HRS'];
            $tot_spHrs = $tot_spHrs + $row['TS_SP_HRS'];
        ?>
        <tr>
            <td class="th"><?php echo date('d/m/Y', strtotime($row['TS_DATE'])) ?></td>
            <td class="th"><?php echo $row['TS_DAY_STATUS'] ?></td>
            <td class="th"><?php echo date('H:i',$timein) ?></td>
            <td class="th"><?php echo date('H:i',$timeout) ?></td>
            <td class="th" align="right"><?php echo toDecimal($row['TS_RG_HRS']) ?></td>
            <td class="th" align="right"><?php echo toDecimal($row['TS_OT_HRS']) ?></td>
            <td class="th" align="right"><?php echo toDecimal($row['TS_HOL_OT_HRS']) ?></td>
            <td class="th" align="right"><?php echo toDecimal($row['TS_B_HRS']) ?></td>
            <td class="th" align="right"><?php echo toDecimal($row['TS_SP_HRS']) ?></td>
            <td class="th"><?php echo $row['TS_JB_NAME'] ?></td>
        </tr>
        <?php
        }
    }
    else
    {
        echo "No Record Found";
    }
    ?>
    <tr>
        <td class="th"></td>
        <td class="th"></td>
        <td class="th">Absent Days:</td>
        <td class="th"><?php echo $ab_days?></td>
        <td class="th" align="right"><?php echo number_format($tot_RegHrs,2,'.','') ?></td>
        <td class="th" align="right"><?php echo number_format($tot_otHrs,2,'.','') ?></td>
        <td class="th" align="right"><?php echo number_format($tot_hOtHrs,2,'.','') ?></td>
        <td class="th" align="right"><?php echo number_format($tot_bHrs,2,'.','') ?></td>
        <td class="th" align="right"><?php echo number_format($tot_spHrs,2,'.','')?></td>
        <td></td>
    </tr>
    </tbody>
</table>
</h6>
</div>
</body>
</html>