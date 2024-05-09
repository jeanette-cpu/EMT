<?php
include('../../security.php');
// INSERT DAILY ACTIVITY
if(isset($_POST['addDE']))
{
    date_default_timezone_set('Asia/Dubai');
    // date today
    $Date = date('Y-m-d');
    $act_id = $_POST['activity_id'];
    $flt_id= $_POST['flat_id'];
    $prj_id=$_POST['prj_id'];
    $user_id = $_POST['user_id'];
    $plx_id=$_POST['plx_id'];

    //search for Assigned ID
    $q_asgn_act_id = "SELECT * FROM assigned_activity where Act_Id='$act_id' and Flat_Id='$flt_id' limit 1";
    $query_run1 = mysqli_query($connection, $q_asgn_act_id);
    $row1 = mysqli_fetch_assoc($query_run1);
    $asgd_act_id = $row1['Asgd_Act_Id'];
    //the percentage
    $last_prct = $row1['Asgd_Pct_Done'];
    
    $check_1 = "SELECT * FROM daily_entry WHERE DE_Date_Entry='$Date' AND Asgd_Act_Id='$asgd_act_id' AND DE_Status=1";
    $c1_run = mysqli_query($connection, $check_1);

    //check activity already assigned today
    if(mysqli_num_rows($c1_run)>0)
    {
        $_SESSION['status'] = "Activity Already Assigned Today";
        $_SESSION['status_code'] = "warning";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else
    {
        $query="INSERT INTO daily_entry (DE_Date_Entry,DE_Status,DE_Pct_Done,Asgd_Act_Id,User_Id) VALUES ('$Date','1','$last_prct','$asgd_act_id','$user_id')";
        
        if($connection->query($query)===TRUE)
        {  
            $DE_Id = $connection->insert_id;        
            //EMT labour
            if(isset($_POST['employee'])){ 
                // $emp = $_POST['employee'];
                $data = array(
                    'emp' => $_POST['employee']
                ); 
                $first_value = reset($data);
                $s_mp = implode(",", $first_value);
                if($s_mp != 'Select Employee')
                {
                    $count = count($_POST['employee']);
                    for ($i=0; $i < $count; $i++)
                    {
                        $query1="INSERT INTO asgn_worker (Emp_Id, DE_Id,Asgd_Worker_Status) VALUES ('{$_POST['employee'][$i]}','$DE_Id','1')";
                        $query_run1 = mysqli_query($connection,$query1);
                        if($query_run1)
                        {
                            $_SESSION['status'] = "Activity Recorded";
                            $_SESSION['status_code'] = "success";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                        else
                        {
                            $_SESSION['status'] = "Error Adding Activity";
                            $_SESSION['status_code'] = "error";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                    }
                }
            }
            //MANPOWER
            if(isset($_POST['manpower'])){
                $data = array(
                    'manpower' => $_POST['manpower'],
                    'mp_qty' => $_POST['mp_qty']
                );
                $first_value = reset($data);
                $s_mp = implode(",", $first_value);
                if($s_mp != 'Select Manpower')
                {
                    $mpCount = count($_POST['manpower']);
                    for($mpc=0; $mpc < $mpCount; $mpc++){
                        $qMP="INSERT INTO asgn_mp (DE_Id,MP_Id,Asgn_MP_Qty,Asgn_MP_Status) VALUES ('$DE_Id','{$_POST['manpower'][$mpc]}','{$_POST['mp_qty'][$mpc]}','1')";
                        $qMP_run = mysqli_query($connection,$qMP);
                        if($qMP_run)
                        {
                            $_SESSION['status'] = "Activity Recorded";
                            $_SESSION['status_code'] = "success";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                        else
                        {
                            $_SESSION['status'] = "Error Adding Activity";
                            $_SESSION['status_code'] = "error";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                    }
                }
            }
            //SUBCONTRACTOR
            if(isset($_POST['subcontractor'])){
                $data = array(
                    'subcontractor' => $_POST['subcontractor'],
                    'sb_qty' => $_POST['sb_qty']
                );
                $first_value = reset($data);
                $s_mp = implode(",", $first_value);
                if($s_mp != 'Select Subcontractor')
                {
                    $sbCount = count($_POST['subcontractor']);
                    for($sbc=0; $sbc < $sbCount; $sbc++){
                        $qSB="INSERT INTO asgn_subcon (DE_Id,SB_Id,Asgn_SB_Qty,Asgn_SB_Status) VALUES ('$DE_Id','{$_POST['subcontractor'][$sbc]}','{$_POST['sb_qty'][$sbc]}','1')";
                        $qSB_run = mysqli_query($connection,$qSB);
                        if($qSB_run)
                        {
                            $_SESSION['status'] = "Activity Recorded";
                            $_SESSION['status_code'] = "success";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                        else
                        {
                            $_SESSION['status'] = "Error Adding Activity";
                            $_SESSION['status_code'] = "error";
                            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                        }
                    }
                }
            }
        }
        else
        {
            $_SESSION['status'] = "Error Adding Activity";
            $_SESSION['status_code'] = "error";
            header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
        }
    }
}
// EDIT DAILY ACTIVITY
if(isset($_POST['editDE']))
{
    $DE_Id = $_POST['DE_Id'];
    $DE_Progress = $_POST['DE_Pct'];
    $asgd_act_id = $_POST['asgd_act_id'];
    $prj_id= $_POST['prj_id'];
    $flt_id=$_POST['flat_id'];
    $plx_id=$_POST['plx_id'];

    // get previous value
    $check_1 = "SELECT * FROM assigned_activity where Asgd_Act_Id='$asgd_act_id'";
    $c1_run = mysqli_query($connection, $check_1);
    $row=mysqli_fetch_assoc($c1_run);
    $percent = $row['Asgd_Pct_Done'];

    date_default_timezone_set('Asia/Dubai');
    // date today
    $Date = date('Y-m-d');

    if($DE_Progress>100)
    {
        $_SESSION['status'] = "Please insert progress 100 and below";
        $_SESSION['status_code'] = "warning";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    elseif($percent<$DE_Progress)
    {
        if($DE_Progress==100)
        {
            // update asgd_activity table
            $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress', Asgd_Act_Date_Completed='$Date' where Asgd_Act_Id='$asgd_act_id'";
            $q2_run = mysqli_query($connection, $q2);
            if($q2_run)
            {
                $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id='$DE_Id'";
                // echo $query;
                $query_run = mysqli_query($connection,$query);

                if($query_run)
                {
                    $_SESSION['status'] = "Activity Updated";
                    $_SESSION['status_code'] = "success";
                    header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                }
                else{
                    $_SESSION['status'] = "Error Updating Progress";
                    $_SESSION['status_code'] = "error";
                    header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
                }
            }
            else{
                $_SESSION['status'] = "Enter Higher Progress Value";
                $_SESSION['status_code'] = "warning";
                header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
            }
        }
        else
        {
            // update asgd_activity table
            $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress' where Asgd_Act_Id='$asgd_act_id'";
            $q2_run = mysqli_query($connection, $q2);
            
            if($q2_run)
            {
                $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id='$DE_Id'";
                // echo $query;
                $query_run = mysqli_query($connection,$query);

                $_SESSION['status'] = "Activity Updated";
                $_SESSION['status_code'] = "success";
                header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
            }
            else{
                $_SESSION['status'] = "Enter Higher Progress Value";
                $_SESSION['status_code'] = "warning";
                header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
            }
        }
    }
    else{
        $_SESSION['status'] = "Enter Higher Progress Value";
        $_SESSION['status_code'] = "warning";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
//DELETE ACTIVITY
if(isset($_POST['delDE']))
{
    $prj_id=$_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $DE_Id=$_POST['DE_Id'];
    // delete asgn EMT labout
    $q_del="SELECT * FROM asgn_worker WHERE DE_Id='$DE_Id'";
    $q_del_run =mysqli_query($connection,$q_del);
    if(mysqli_num_rows($q_del_run)>0){
       $q_delM="DELETE FROM asgn_worker WHERE DE_Id='$DE_Id'";
       $q_delM_run= mysqli_query($connection,$q_delM);
    }
    // delete asgn MANPOWER
    $q_delmp="SELECT * FROM asgn_mp WHERE DE_Id='$DE_Id'";
    $q_delmp_run =mysqli_query($connection,$q_delmp);
    $row_chk_mp=mysqli_fetch_assoc($q_delmp_run);
    if(mysqli_num_rows($q_delmp_run)>0){
        $total =$row_chk_mp['Asgn_MP_Total'];
        if($total!=NULL)
        {
            // check if there are other MP today
            date_default_timezone_set('Asia/Dubai');
            $Date = date('Y-m-d');// date today
            $mp_id = $row_chk_mp['MP_Id'];
            $c_mp="SELECT Asgn_MP_Id FROM asgn_mp as asmp 
                    LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id 
                    where de.DE_Date_Entry='$Date' AND asmp.MP_Id='$mp_id' 
                    and asmp.Asgn_MP_Status=1 and de.DE_Status=1  
                    and asmp.Asgn_MP_Total is NULL LIMIT 1";
            $c_mp_run = mysqli_query($connection,$c_mp);
            $row_mp = mysqli_fetch_assoc($c_mp_run);
            if(mysqli_num_rows($c_mp_run)>0)
            {
                //UPDATE THE mp total value
                $u_id =$row_mp['Asgn_MP_Id'];
                $mp_update ="UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$u_id'";
                $mp_update_run=mysqli_query($connection,$mp_update);
            }
        }
       $q_del_mp="DELETE FROM asgn_mp WHERE DE_Id='$DE_Id'";
       $q_del_mp_run= mysqli_query($connection,$q_del_mp);
    }
    // delete asgn SUBCONTRACTOR
    $q_delsb="SELECT * FROM asgn_subcon WHERE DE_Id='$DE_Id'";
    $q_delsb_run =mysqli_query($connection,$q_delsb);
    $sb_row = mysqli_fetch_assoc($q_delsb_run);
    if(mysqli_num_rows($q_delsb_run)>0){
        $total=$sb_row['Asgn_SB_Total'];
        if($total!=NULL)
        {
            // check if there are other MP today
            date_default_timezone_set('Asia/Dubai');
            $Date = date('Y-m-d');
            $sb_id = $sb_row['SB_Id'];
            $c_mp="SELECT Asgn_SB_Id FROM asgn_subcon as asmp LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id where de.DE_Date_Entry='$Date' AND asmp.SB_Id='$sb_id' and asmp.Asgn_SB_Status=1 and de.DE_Status=1  and asmp.Asgn_SB_Total is NULL LIMIT 1";
            // echo $c_mp;
            $c_mp_run = mysqli_query($connection,$c_mp);
            $row_sb = mysqli_fetch_assoc($c_mp_run);
            if(mysqli_num_rows($c_mp_run)>0)
            {
                //UPDATE THE mp total value
                $u_id =$row_sb['Asgn_SB_Id'];
                // echo $total;
                $mp_update ="UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$u_id'";
                // echo $mp_update;
                $mp_update_run=mysqli_query($connection,$mp_update);
            }
        }

       $q_del_sb="DELETE FROM asgn_subcon WHERE DE_Id='$DE_Id'";
       $q_del_sb_run= mysqli_query($connection,$q_del_sb);
    }
    // $query="UPDATE daily_entry SET DE_Status=0 WHERE DE_Id='$DE_Id'";
    $query="DELETE FROM daily_entry WHERE DE_Id='$DE_Id'";
    $query_run = mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Record Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
// ADD MANPOWER
if(isset($_POST['addWorker'])){

    $prj_id=$_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $DE_Id=$_POST['DE_Id'];

    if(isset($_POST['employee'])){
        $data = array(
            'emp_id' => $_POST['employee']
        );
        $count = count($_POST['employee']);
        for ($i=0; $i < $count; $i++){
            $query="INSERT INTO asgn_worker (Emp_Id, DE_Id,Asgd_Worker_Status) VALUES ('{$_POST['employee'][$i]}','$DE_Id','1')";
            $query_run = mysqli_query($connection,$query);
        }
    }
    if(isset($_POST['manpower'])){
        $data = array(
            'manpower' => $_POST['manpower'],
            'mp_qty' => $_POST['mp_qty']
        ); 
        $mpCount = count($_POST['manpower']);
        for($mpc=0; $mpc < $mpCount; $mpc++){
            $qMP="INSERT INTO asgn_mp (DE_Id,MP_Id,Asgn_MP_Qty,Asgn_MP_Status) VALUES ('$DE_Id','{$_POST['manpower'][$mpc]}','{$_POST['mp_qty'][$mpc]}','1')";
            $qMP_run = mysqli_query($connection,$qMP);
        }
    }
    //SUBCONTRACTOR
    if(isset($_POST['subcontractor'])){
        $data = array(
            'subcontractor' => $_POST['subcontractor'],
            'sb_qty' => $_POST['sb_qty']
        ); 
        $sbCount = count($_POST['subcontractor']);
        for($sbc=0; $sbc < $sbCount; $sbc++){
            $qSB="INSERT INTO asgn_subcon (DE_Id,SB_Id,Asgn_SB_Qty,Asgn_SB_Status) VALUES ('$DE_Id','{$_POST['subcontractor'][$sbc]}','{$_POST['sb_qty'][$sbc]}','1')";
            $qSB_run = mysqli_query($connection,$qSB);
        }
    }
    if($query_run || $qMP_run || $qSB_run)
    {
        $_SESSION['status'] = "Manpower Added";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Adding Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
if(isset($_POST['search']))
{
    $id = $_POST['prj_id'];
    $flt_id=$_POST['flt_id'];

    header('Location: activity.php?id='.$id.'&flt_id='.$flt_id);
}
if(isset($_POST['villa_search']))
{
    $id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];

    header('Location: activity.php?id='.$id.'&plx_id='.$plx_id);
}
if(isset($_POST['EditBtn']))
{
    $prj_id= $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $flt_id= $_POST['flt_id'];
    //MANPOWER SUPPLY
    if(isset($_POST['mp_perf'])){
        $data = array(
            'mp_perf' => $_POST['mp_perf'],
            'mp_qty' => $_POST['mp_qty'],
            'asgn_mp_id' => $_POST['asgn_mp_id']
        );
        $count = count($_POST['mp_perf']);
        if($count >=1)
        {
            $count = count($_POST['mp_perf']);
            for ($i=0; $i < $count; $i++){
            $query="UPDATE asgn_mp SET Asgn_MP_Performance='{$_POST['mp_perf'][$i]}', Asgn_MP_Qty='{$_POST['mp_qty'][$i]}' WHERE Asgn_MP_Id='{$_POST['asgn_mp_id'][$i]}'";
            $query_run = mysqli_query($connection,$query);
            }
        }
    }
    //SUBCONTRACTOR
    if(isset($_POST['sb_perf'])){
        $data = array(
            'sb_perf' => $_POST['sb_perf'],
            'sb_qty' => $_POST['sb_qty'],
            'asgn_sb_id' => $_POST['asgn_sb_id']
        );
        $count = count($_POST['sb_perf']);
        if($count >=1)
        {
            $count = count($_POST['sb_perf']);
            for ($i=0; $i < $count; $i++){
            $query="UPDATE asgn_subcon SET Asgn_SB_Performance='{$_POST['sb_perf'][$i]}', Asgn_SB_Qty='{$_POST['sb_qty'][$i]}' WHERE Asgn_SB_Id='{$_POST['asgn_sb_id'][$i]}'";
            $query_run = mysqli_query($connection,$query);
            }
        }
    }
    // EMT
    if(isset($_POST['emp_perf']))
    {
        $data = array(
            'emp_perf' => $_POST['emp_perf'],
            'asgn_worker_id' => $_POST['asgn_worker_id']
        );
        $count = count($_POST['emp_perf']);
        for ($i=0; $i < $count; $i++){
        $query="UPDATE asgn_worker SET Asgd_Worker_Performace='{$_POST['emp_perf'][$i]}' WHERE Asgd_Worker_Id='{$_POST['asgn_worker_id'][$i]}'";
        $query_run = mysqli_query($connection,$query);
        }
    }
    if($query_run)
    {
        $_SESSION['status'] = "Manpower Updated";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Adding Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
if(isset($_POST['delEmp']))
{
    $ids = $_POST['id'];
    $prj_id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $q_delete="UPDATE asgn_worker SET Asgd_Worker_Status=0 where Asgd_Worker_Id='$ids' ";
    $query_run4 = mysqli_query($connection,$q_delete);
    if($query_run4)
    {
        $_SESSION['status'] = "Employee Removed";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Removing Employee";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
// delete MP
if(isset($_POST['delMP']))
{
    $ids = $_POST['id']; //mp id
    $prj_id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $user_id =$_POST['user_id'];
    // Check if Asgn total have value
    $c_mp_total ="SELECT Asgn_MP_Total as t, MP_Id FROM asgn_mp WHERE Asgn_MP_Id='$ids'";
    $c_mp_total_run = mysqli_query($connection,$c_mp_total);
    $mp_row = mysqli_fetch_assoc($c_mp_total_run);
    if($mp_row['t'] !=NULL){
        // check if there are other MP today
        date_default_timezone_set('Asia/Dubai');
        // date today
        $Date = date('Y-m-d');
        $mp_id = $mp_row['MP_Id'];
        $c_mp="SELECT Asgn_MP_Id FROM asgn_mp as asmp LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id where de.DE_Date_Entry='$Date' AND asmp.MP_Id='$mp_id' and asmp.Asgn_MP_Status=1 and de.DE_Status=1  and asmp.Asgn_MP_Total is NULL and DE.User_Id='$user_id' LIMIT 1";
        // echo $c_mp;
        $c_mp_run = mysqli_query($connection,$c_mp);
        $row_mp = mysqli_fetch_assoc($c_mp_run);
        if(mysqli_num_rows($c_mp_run)>0)
        {
            //UPDATE THE mp total value
            $total=$mp_row['t']; $u_id =$row_mp['Asgn_MP_Id'];
            // echo $total;
            $mp_update ="UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$u_id'";
            // echo $mp_update;
            $mp_update_run=mysqli_query($connection,$mp_update);
        }
        
    }

    $q_delete="UPDATE asgn_mp SET Asgn_MP_Status=0 where Asgn_MP_Id='$ids' ";
    $query_run4 = mysqli_query($connection,$q_delete);
    if($query_run4)
    {
        $_SESSION['status'] = "Manpower Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
// delete SB
if(isset($_POST['delSB']))
{
    $ids = $_POST['id'];
    $prj_id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $user_id = $_POST['user_id'];
    
    // Check if Asgn total have value
    $c_mp_total ="SELECT Asgn_SB_Total as t, SB_Id FROM asgn_subcon WHERE Asgn_SB_Id='$ids'";
    $c_mp_total_run = mysqli_query($connection,$c_mp_total);
    $sb_row = mysqli_fetch_assoc($c_mp_total_run);
    if($sb_row['t'] !=NULL){
        // check if there are other MP today
        date_default_timezone_set('Asia/Dubai');
        $Date = date('Y-m-d');
        $sb_id = $sb_row['SB_Id'];
        $c_mp="SELECT Asgn_SB_Id FROM asgn_subcon as asmp LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id where de.DE_Date_Entry='$Date' AND asmp.SB_Id='$sb_id' and asmp.Asgn_SB_Status=1 and de.DE_Status=1  and asmp.Asgn_SB_Total is NULL and DE.User_Id='$user_id' LIMIT 1";
        $c_mp_run = mysqli_query($connection,$c_mp);
        $row_sb = mysqli_fetch_assoc($c_mp_run);
        if(mysqli_num_rows($c_mp_run)>0)
        {
            //UPDATE THE mp total value
            $total=$sb_row['t']; $u_id =$row_sb['Asgn_SB_Id'];
            // echo $total;
            $mp_update ="UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$u_id'";
            // echo $mp_update;
            $mp_update_run=mysqli_query($connection,$mp_update);
        }
        
    }
    
    $q_delete="UPDATE asgn_subcon SET Asgn_SB_Status=0 where Asgn_SB_Id='$ids' ";
    $query_run4 = mysqli_query($connection,$q_delete);
    if($query_run4)
    {
        $_SESSION['status'] = "Sub Contractor Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Sub Contractor";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
if(isset($_POST['mp_total']))
{
    $asgn_id = $_POST['asgn_id'];
    $total = $_POST['total_mp'];
    $prj_id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];

    $q_update = "UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$asgn_id'";
    $q_update_run = mysqli_query($connection,$q_update);
    if($q_update_run)
    {
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else
    {
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
if(isset($_POST['sb_total']))
{
    $asgn_id = $_POST['asgn_id'];
    $total = $_POST['total_sb'];
    $prj_id = $_POST['prj_id'];
    $plx_id=$_POST['plx_id'];

    $q_update = "UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$asgn_id'";
    // echo $q_update;
    $q_update_run = mysqli_query($connection,$q_update);
    if($q_update_run)
    {
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
    else
    {
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: activity.php?id='.$prj_id.'&plx_id='.$plx_id);
    }
}
?>