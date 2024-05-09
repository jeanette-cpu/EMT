<?php
include('security.php');
//IMPORT 
if(isset($_POST["ts_import"])){
    if($_FILES['file']['name'])
    {
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv')
        {
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            while($data = fgetcsv($handle))
            {
                $empno= mysqli_real_escape_string($connection, $data[0]);
                $item2 = mysqli_real_escape_string($connection, $data[1]);
                $item3 = mysqli_real_escape_string($connection, $data[2]);
                $item4 = mysqli_real_escape_string($connection, $data[3]);
                $item5 = mysqli_real_escape_string($connection, $data[4]);
                $item6 = mysqli_real_escape_string($connection, $data[5]);
                $item7 = mysqli_real_escape_string($connection, $data[6]);
                $item8 = mysqli_real_escape_string($connection, $data[7]);
                $item9 = mysqli_real_escape_string($connection, $data[8]);
                $item10 = mysqli_real_escape_string($connection, $data[9]);
                $item11 = mysqli_real_escape_string($connection, $data[10]);
                
                $query ="SELECT EMP_ID FROM employee WHERE EMP_NO='$empno' and EMP_STATUS=1 LIMIT 1";
                // echo $query;
                $query_run = mysqli_query($connection,$query);
                if(mysqli_num_rows($query_run)>0 )
                    {     
                        $row = mysqli_fetch_assoc($query_run);
                        $item1 = $row['EMP_ID']; 
                        $sql= "INSERT INTO time_sheet (EMP_ID,TS_DATE,TS_DAY_STATUS,TS_M_IN,TS_EVE_OUT,TS_RG_HRS,TS_OT_HRS,TS_HOL_OT_HRS,TS_B_HRS,TS_SP_HRS,TS_JB_NAME) VALUES ('$item1','$item2','$item3','$item4','$item5','$item6','$item7','$item8','$item9','$item10','$item11')"; 
                        echo $sql;
                        if ($connection->query($sql) === TRUE) {
                            $count=$count+1;
                            $_SESSION['status'] = "$count. Time Sheet Details Added";
                            $_SESSION['status_code'] = "success";
                            header("location:import.php"); 
                        } else {
                            echo "error";
                            // $_SESSION['status'] = "Import Error";
                            // $_SESSION['status_code'] = "error";
                            // header("location:import.php");
                        }
                    }
            } 
            fclose($handle);
        }
    }
}
//INSERT
if(isset($_POST['addTimesheet'])){
    $tsDate = $_POST['tsDate'];
    $tsStatus = $_POST['tsStatus'];
    $tsTimeIn = $_POST['tsTimeIn'];
    $tsTimeOut = $_POST['tsTimeOut'];
    $tsHrs = $_POST['tsHrs'];
    $tsOtHrs = $_POST['tsOtHrs'];
    $tsHOtHrs = $_POST['tsHOtHrs'];
    $tsBnsHrs = $_POST['tsBnsHrs'];
    $tsSpHrs = $_POST['tsSpHrs'];
    $tsJobName = $_POST['tsJobName'];
    $empId = $_POST['empid'];

    $dateCheck_query = "SELECT * FROM time_sheet WHERE TS_DATE='$tsDate' AND EMP_ID='$empId'";
    $dateCheck_query_run = mysqli_query($connection, $dateCheck_query);

    if(mysqli_num_rows($dateCheck_query_run) > 0){
        $_SESSION['status'] = "Date Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: timesheet.php');
    }
    else{
        $query="INSERT INTO time_sheet (EMP_ID,TS_DATE,TS_DAY_STATUS,TS_M_IN,TS_EVE_OUT,TS_RG_HRS,TS_OT_HRS,TS_HOL_OT_HRS,TS_B_HRS,TS_SP_HRS,TS_JB_NAME) VALUES ('$empId','$tsDate','$tsStatus','$tsTimeIn','$tsTimeOut','$tsHrs','$tsOtHrs','$tsHOtHrs','$tsBnsHrs','$tsSpHrs','$tsJobName')";
        $query_run = mysqli_query($connection, $query);

        if($query_run){
            $_SESSION['status'] = "Timesheet Added";
            $_SESSION['status_code'] = "success";
            header('Location: timesheet.php');
        }
        else{
            $_SESSION['status'] = "Error Inserting Timesheet";
            $_SESSION['status_code'] = "error";
            header('Location: timesheet.php');
        }
    }
}
//EDIT ONE ROW
if(isset($_POST['edit_tsRow'])){
    $ts_id = $_POST['ts_id'];
    $ts_date = $_POST['ts_date'];
    $ts_status = $_POST['ts_status'];
    $ts_tIn = $_POST['ts_tIn'];
    $ts_tOut = $_POST['ts_tOut'];
    $ts_rHrs = $_POST['ts_rHrs'];
    $ts_otHrs = $_POST['ts_otHrs'];
    $ts_holHrs = $_POST['ts_holHrs'];
    $ts_bnsHrs = $_POST['ts_bnsHrs'];
    $ts_spHrs = $_POST['ts_spHrs'];
    $ts_jbName = $_POST['ts_jbName'];

    $query="UPDATE time_sheet SET TS_DATE='$ts_date', TS_DAY_STATUS='$ts_status', TS_M_IN='$ts_tIn', TS_EVE_OUT='$ts_tOut', TS_RG_HRS='$ts_rHrs', TS_OT_HRS='$ts_otHrs', TS_HOL_OT_HRS='$ts_holHrs', TS_B_HRS='$ts_bnsHrs', TS_SP_HRS='$ts_spHrs', TS_JB_NAME='$ts_jbName' WHERE TS_ID='$ts_id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run){
        $_SESSION['status'] = "Timesheet Updated";
        $_SESSION['status_code'] = "success";
        header('Location: timesheet.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Timesheet";
        $_SESSION['status_code'] = "error";
        header('Location: timesheet.php');
    }
}
//DELETE ONE ROW
if(isset($_POST['delete_tsRow'])){
    $ts_id=$_POST['ts_idD'];
    $query="DELETE FROM time_sheet WHERE TS_ID='$ts_id'";
    $query_run = mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Date Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: timesheet.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting Timesheet";
        $_SESSION['status_code'] = "error";
        header('Location: timesheet.php');
    }
}
//DELETE WHOLE MONTH
if(isset($_POST['ts_delete'])){
    $emp_no = $_POST['emp_no'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    $query = "SELECT EMP_ID FROM employee WHERE EMP_NO='$emp_no'";
    $query_run = mysqli_query($connection,$query);
        foreach($query_run as $row)
        {     
            $emp_id = $row['EMP_ID']; 
        }
    
    $sql="DELETE FROM time_sheet WHERE EMP_ID='$emp_id' AND MONTH(TS_DATE)='$month' AND YEAR(TS_DATE)='$year'";
    // echo $sql;
    $query_run = mysqli_query($connection,$sql);

    if($query_run)
    {
        $_SESSION['status']="Time Sheet Deleted";
        $_SESSION['status_code']="success";
        header('Location: timesheet.php');
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: timesheet.php');
    }
}
//bulk delete timesheet
if(isset($_POST['bulk_delete'])){
    $month=$_POST['month'];
    $year=$_POST['year'];
    // search if there is existing records
    $q_chk="SELECT TS_ID FROM time_sheet WHERE YEAR(TS_DATE)='$year' AND MONTH(TS_DATE)='$month'";
    $q_chk_run=mysqli_query($connection,$q_chk);
    if(mysqli_num_rows($q_chk_run)>0){
        $q_del="DELETE FROM time_sheet WHERE YEAR(TS_DATE)='$year' AND MONTH(TS_DATE)='$month'";
        $q_del_run=mysqli_query($connection,$q_del);
        if($q_del_run){
            $_SESSION['status']="Time Sheets Deleted";
            $_SESSION['status_code']="success";
            header('Location: import.php');
        }
        else{
            $_SESSION['status'] = "Error upon deleting";
            $_SESSION['status_code'] = "error";
            header('Location: import.php');
        }
    }
    else{
        $_SESSION['status'] = "No existing records";
        $_SESSION['status_code'] = "info";
        header('Location: import.php');
    }
}
//bulk delete payslip
if(isset($_POST['bulk_payslip'])){
    $month = $_POST['month'];
    $year = $_POST['year'];
    $q="SELECT PAYSLIP_ID FROM payslip WHERE MONTH(P_DATE)='$month' AND YEAR(P_DATE)='$year'";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)>0){
        while($row=mysqli_fetch_assoc($q_run)){
            // $p_arr[]=$row['PAYSLIP_ID'];
            $p_id=$row['PAYSLIP_ID'];
            //delete additional
            $q_add="SELECT ADD_ID FROM additional WHERE PAYSLIP_ID='$p_id'";
            $q_add_run=mysqli_query($connection,$q_add); $add_id=null;
            if(mysqli_num_rows($q_add_run)>0){ // delete add
                $del_add="DELETE FROM additional WHERE PAYSLIP_ID='$p_id'";
                $del_add_run=mysqli_query($connection,$del_add);
                if($del_add_run){
                }
                else{ $error_del_add++; echo 'error_del_additional: '.$p_id;}
            }
            //delete allowance 
            $q_alw="SELECT ALW_ID  FROM allowance WHERE PAYSLIP_ID='$p_id'";
            $q_alw_run=mysqli_query($connection,$q_alw); $alw_id=null;
            if(mysqli_num_rows($q_alw_run)>0){ // delete alw
                $del_alw="DELETE FROM allowance WHERE PAYSLIP_ID='$p_id'";
                $del_alw_run=mysqli_query($connection,$del_alw);
                if($del_alw_run){
                }
                else{ $error_del_alw++; echo 'error_del_allowance: '.$p_id;}
            }
            //delete deduction 
            $q_deduc="SELECT DEDUC_ID FROM deduction WHERE PAYSLIP_ID='$p_id'";
            $q_deduc_run=mysqli_query($connection,$q_deduc); $deduc_id=null;
            if(mysqli_num_rows($q_deduc_run)>0){ // delete deduc
                $del_deduc="DELETE FROM deduction WHERE PAYSLIP_ID='$p_id'";
                $del_deduc_run=mysqli_query($connection,$del_deduc);
                if($del_deduc_run){
                }
                else{ $error_del_deduc++; echo 'error_del_deduction: '.$p_id;}
            }
            //delete falowace
            $q_falw="SELECT FULL_ALW_ID FROM full_allowance WHERE PAYSLIP_ID='$p_id'";
            $q_falw_run=mysqli_query($connection,$q_falw); $falw_id=null;
            if(mysqli_num_rows($q_falw_run)>0){ // delete falw
                $del_falw="DELETE FROM full_allowance WHERE PAYSLIP_ID='$p_id'";
                $del_falw_run=mysqli_query($connection,$del_falw);
                if($del_falw_run){
                }
                else{ $error_del_falw++; echo 'error_del_full_allowance: '.$p_id;}
            }
        }
        if($error_del_add>0 || $error_del_alw>0 || $error_del_deduc>0 || $error_del_falw>0){
            echo 'do not refresh contact admin';
        }
        else{
            $q_del="DELETE FROM payslip WHERE MONTH(P_DATE)='$month' AND YEAR(P_DATE)='$year'";
            $q_del_run=mysqli_query($connection,$q_del);
            if($q_del_run){
                $_SESSION['status']="Payslip Deleted";
                $_SESSION['status_code']="success";
                header('Location: import.php');
            }
            else{
                $_SESSION['status']="Error deleting payslips";
                $_SESSION['status_code']="error";
                header('Location: import.php');
            }
        }
    }
    {
        $_SESSION['status']="Payslip not found";
        $_SESSION['status_code']="warning";
        header('Location: import.php');
    }
}
?>