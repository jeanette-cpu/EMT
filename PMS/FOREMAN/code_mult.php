<?php
include('../../security.php');
$_SESSION['message'] = "";
// INSERT DAILY ACTIVITY
if(isset($_POST['addDE'])){
    date_default_timezone_set('Asia/Dubai');
    // date today
    $Date = $_POST['date'];
    // $act_id = $_POST['activity_id'];
    $flt_id= $_POST['flat_id'];
    $id=$_POST['prj_id'];
    $user_id = $_POST['user_id'];
    $pct=$_POST['percent'];
    $message =''; $session='';
    //search if activity is assigned
    $data = array(
        'activity_id' => $_POST['activity_id'],
    ); 
    $act_cnt = count($_POST['activity_id']);
    for ($i=0; $i < $act_cnt; $i++){
        $act_arr[]=$_POST['activity_id'][$i];
        // echo $act_id.'<br>';
    }
    $act_ids=implode("', '",$act_arr);
    $q_act_ids="SELECT * FROM activity WHERE Act_Id IN ('$act_ids')";
    $q_act_ids_run=mysqli_query($connection,$q_act_ids);
    if(mysqli_num_rows($q_act_ids_run)>0){
        while($row_act=mysqli_fetch_assoc($q_act_ids_run)){
            $act_id=$row_act['Act_Id'];
            $q_act="SELECT * FROM flat WHERE Flat_Id IN ('$flt_id')";
            $q_act_run=mysqli_query($connection,$q_act);
            if(mysqli_num_rows($q_act_run)>0){
                while($row_f=mysqli_fetch_assoc($q_act_run)){
                    $flat_id = $row_f['Flat_Id'];
                    $flat_code =$row_f['Flat_Code'];
                    $flat_name =$row_f['Flat_Name'];
                    $ff=$flat_code.' '.$flat_name;
                    $q_s="SELECT * FROM assigned_activity as as_act
                            LEFT JOIN flat as flt on flt.Flat_Id=as_act.Flat_Id
                            where as_act.Act_Id='$act_id' and as_act.Flat_Id ='$flat_id'";
                    $q_s_run=mysqli_query($connection,$q_s);
                    if(mysqli_num_rows($q_s_run)>0){
                    }
                    else{
                        $session.='warning';
                        $message .='Activity not assigned to '.$ff;
                    }
                }
            }
            $q_asgn_act_id = "SELECT * FROM assigned_activity as as_act
                                LEFT JOIN flat as flt on flt.Flat_Id=as_act.Flat_Id
                                where as_act.Act_Id='$act_id' and as_act.Flat_Id IN ('$flt_id')";
            $query_run1 = mysqli_query($connection, $q_asgn_act_id);
            if(mysqli_num_rows($query_run1)>0){
                //update pct
                if($pct>100){
                    $pct=100;
                    $update_pct="UPDATE assigned_activity SET Asgd_Pct_Done='$pct' WHERE Act_Id='$act_id' and Flat_Id IN ('$flt_id')";
                    $update_pct_run=mysqli_query($connection,$update_pct);
                    if($update_pct_run){
                    }
                    else{
                        $session.='warning';
                        $message .='error updating percentage<br>';
                    }
                }
                elseif($pct==100){
                    $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$pct', Asgd_Act_Date_Completed='$Date' where Act_Id='$act_id' and Flat_Id IN ('$flt_id')";
                    $q2_run = mysqli_query($connection, $q2);
                    if($q2_run){
                    }
                    else{
                        $session.='warning';
                        $message .='error updating percentage<br>';
                    }
                }
                else{
                    $update_pct="UPDATE assigned_activity SET Asgd_Pct_Done='$pct' WHERE Act_Id='$act_id' and Flat_Id IN ('$flt_id')";
                    $update_pct_run=mysqli_query($connection,$update_pct);
                    if($update_pct_run){
                    }
                    else{
                        $session.='warning';
                        $message .='error updating percentage<br>';
                    }
                }
                while($row1 = mysqli_fetch_assoc($query_run1)){
                    $asgd_act_id = $row1['Asgd_Act_Id'];
                    $flat_id=$row1['Flat_Id'];
                    $flat_name=$row1['Flat_Code'].' '.$row1['Flat_Name'];
                    $check_1 = "SELECT * FROM daily_entry WHERE DE_Date_Entry='$Date' AND Asgd_Act_Id='$asgd_act_id' AND DE_Status=1";
                    $c1_run = mysqli_query($connection, $check_1);
                    //check activity already assigned today
                    if(mysqli_num_rows($c1_run)>0){
                        $message .= "Activity already assigned to ".$flat_name.'<br>';
                        $session .='warning';
                    }
                    else{
                        $query="INSERT INTO daily_entry (DE_Date_Entry, DE_Pct_Done,DE_Status,Asgd_Act_Id,User_Id) VALUES ('$Date','$pct','1','$asgd_act_id','$user_id')";
                        if($connection->query($query)===TRUE){  
                            $DE_Id = $connection->insert_id;        
                            //EMT labour
                            if(isset($_POST['employee'])){ 
                                // $emp = $_POST['employee'];
                                $data1 = array(
                                    'emp' => $_POST['employee']
                                ); 
                                $first_value = reset($data1);
                                $e_mp = implode(",", $first_value);
                                if($e_mp!=NULL AND $e_mp!='' AND $e_mp!='Select Employee'){
                                    $count = count($_POST['employee']);
                                    for ($i=0; $i < $count; $i++) {
                                        $query1="INSERT INTO asgn_worker (Emp_Id, DE_Id,Asgd_Worker_Status) VALUES ('{$_POST['employee'][$i]}','$DE_Id','1')";
                                        $query1_run=mysqli_query($connection,$query1);
                                        if($query1_run){  
                                        }
                                        else{
                                            $message ."Error adding employee in ".$flat_name.'<br>';
                                            $session .='error';
                                        }
                                    }
                                }
                            }
                            //MANPOWER
                            if(isset($_POST['manpower'])){
                                $data2 = array(
                                    'manpower' => $_POST['manpower'],
                                    'mp_qty' => $_POST['mp_qty']
                                );
                                $first_value = reset($data2);
                                $m_mp = implode(",", $first_value);
                                if($m_mp!=NULL AND $m_mp!='' AND $m_mp!='Select Manpower'){
                                    $mpCount = count($_POST['manpower']);
                                    for($mpc=0; $mpc < $mpCount; $mpc++){
                                        $qMP="INSERT INTO asgn_mp (DE_Id,MP_Id,Asgn_MP_Qty,Asgn_MP_Status) VALUES ('$DE_Id','{$_POST['manpower'][$mpc]}','{$_POST['mp_qty'][$mpc]}','1')";
                                        $qMP_run = mysqli_query($connection,$qMP);
                                        if($qMP_run){  
                                        }
                                        else{
                                            $message .="Error adding manpower in ".$flat_name.'<br>';
                                            $session .='error';
                                        }
                                    }
                                }
                            }
                            //SUBCONTRACTOR
                            if(isset($_POST['subcontractor'])){
                                $data3 = array(
                                    'subcontractor' => $_POST['subcontractor'],
                                    'sb_qty' => $_POST['sb_qty']
                                );
                                $first_value = reset($data3);
                                $s_mp = implode(",", $first_value);
                                if($s_mp!=NULL AND $s_mp!='' AND $s_mp!='Select Subcontractor') {
                                    $sbCount = count($_POST['subcontractor']);
                                    for($sbc=0; $sbc < $sbCount; $sbc++){
                                        $qSB="INSERT INTO asgn_subcon (DE_Id,SB_Id,Asgn_SB_Qty,Asgn_SB_Status) VALUES ('$DE_Id','{$_POST['subcontractor'][$sbc]}','{$_POST['sb_qty'][$sbc]}','1')";
                                        $qSB_run = mysqli_query($connection,$qSB);
                                        if($qSB_run){  
                                        }
                                        else{
                                            $message .="Error adding subcontractor in ".$flat_name.'<br>';
                                            $session .='error';
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            $message .=  "Error Adding Activity".$flat_name.'<br>';
                            $session .= "error";
                        }
                    }
                }
            }
        }
    }
    
    $error_count = substr_count($session, 'error');
    $warning_count = substr_count($session, 'warning');
    if($warning_count>0 OR $error_count>0){
        if($warning_count> $error_count){
            $status='warning';
        }
        else{
            $status='error';
        }
    }
    else{
        $message ='Success Adding Activity';
        $status='success';
    }
    // echo $message;
    // echo $status;
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
}
// EDIT DAILY ACTIVITY
if(isset($_POST['editDE'])){
    $DE_Id = $_POST['DE_Id'];
    $DE_Progress = $_POST['DE_Pct'];
    $asgd_act_id = $_POST['asgd_act_id'];
    $id=$_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flat_id'];
    $plx_id=$_POST['plx_id'];

    // get previous value
    $check_1 = "SELECT * FROM assigned_activity where Asgd_Act_Id='$asgd_act_id'";
    $c1_run = mysqli_query($connection, $check_1);
    $row=mysqli_fetch_assoc($c1_run);
    $percent = $row['Asgd_Pct_Done'];

    if($DE_Progress>100){
        $_SESSION['status'] = "Please insert progress 100 and below";
        $_SESSION['status_code'] = "warning";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    elseif($DE_Progress==100){
        // update asgd_activity table with date of completion
        $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress', Asgd_Act_Date_Completed='$Date' where Asgd_Act_Id='$asgd_act_id'";
        $q2_run = mysqli_query($connection, $q2);
        if($q2_run){
            $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id='$DE_Id'";
            $query_run = mysqli_query($connection,$query);
            if($query_run){
                $_SESSION['status'] = "Activity Updated";
                $_SESSION['status_code'] = "success";
                header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
            }
            else{
                $_SESSION['status'] = "Error Updating Progress";
                $_SESSION['status_code'] = "error";
                header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date); 
            }
        }
        else{
            $_SESSION['status'] = "Error Updating Progress";
            $_SESSION['status_code'] = "error";
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date); 
        }
    }
    else{ // update asgd_activity table
        // check if higher from prev progress 95<80
        if($prev_percent<$DE_Progress){
            $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress' where Asgd_Act_Id='$asgd_act_id'";
            $q2_run = mysqli_query($connection, $q2);
            if($q2_run){
                $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id='$DE_Id'";
                $query_run = mysqli_query($connection,$query);
                if($query_run){
                    $_SESSION['status'] = "Activity Updated";
                    $_SESSION['status_code'] = "success";
                    header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
                }
                else{
                    $_SESSION['status'] = "Error Updating Progress";
                    $_SESSION['status_code'] = "error";
                    header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
                }
            }
            else{
                $_SESSION['status'] = "Error Updating Progress";
                $_SESSION['status_code'] = "error";
                header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
            }
        }
        else{
            $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id='$DE_Id'";
            $query_run = mysqli_query($connection,$query);
            if($query_run){
                $_SESSION['status'] = "Activity Updated";
                $_SESSION['status_code'] = "success";
                header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
            }
            else{
                $_SESSION['status'] = "Error Updating Progress";
                $_SESSION['status_code'] = "error";
                header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
            }
        }
    }
}
//DELETE ACTIVITY
if(isset($_POST['delDE'])){
    $id=$_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $DE_Id=$_POST['DE_Id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flat_id'];
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
            // date today
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
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
// ADD MANPOWER
if(isset($_POST['addWorker'])){

    $id=$_POST['prj_id'];
    $plx_id=$_POST['plx_id'];
    $DE_Id=$_POST['DE_Id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flat_id'];
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
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Adding Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
if(isset($_POST['villa_search']))
{
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    if(isset($_POST['flt_id'])){
        $data = array(
        'flt_id' => $_POST['flt_id']
        );
    }
    $count=count($_POST['flt_id']);
    for($c=0; $c < $count; $c++){
        $flt_arr[]=$_POST['flt_id'][$c];
    }
    $flt_id=implode("', '", $flt_arr);
    header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
}
if(isset($_POST['EditBtn']))
{
    $id=$_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];
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
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Adding Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
if(isset($_POST['delEmp'])){
    $ids = $_POST['id'];
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];
    $q_delete="UPDATE asgn_worker SET Asgd_Worker_Status=0 where Asgd_Worker_Id='$ids' ";
    $query_run4 = mysqli_query($connection,$q_delete);
    if($query_run4)
    {
        $_SESSION['status'] = "Employee Removed";
        $_SESSION['status_code'] = "success";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Removing Employee";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
// delete MP
if(isset($_POST['delMP'])){
    $ids = $_POST['id']; //mp id
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];
    $user_id =$_POST['user_id'];
    // Check if Asgn total have value
    $c_mp_total ="SELECT Asgn_MP_Total as t, MP_Id FROM asgn_mp WHERE Asgn_MP_Id='$ids'";
    $c_mp_total_run = mysqli_query($connection,$c_mp_total);
    $mp_row = mysqli_fetch_assoc($c_mp_total_run);
    if($mp_row['t'] !=NULL){
        // check if there are other MP today
        date_default_timezone_set('Asia/Dubai');
        // date today
        
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
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Deleting Manpower";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
// delete SB
if(isset($_POST['delSB'])){
    $ids = $_POST['id'];
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];
    $user_id = $_POST['user_id'];
    
    // Check if Asgn total have value
    $c_mp_total ="SELECT Asgn_SB_Total as t, SB_Id FROM asgn_subcon WHERE Asgn_SB_Id='$ids'";
    $c_mp_total_run = mysqli_query($connection,$c_mp_total);
    $sb_row = mysqli_fetch_assoc($c_mp_total_run);
    if($sb_row['t'] !=NULL){
        // check if there are other MP today
        date_default_timezone_set('Asia/Dubai');
        
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
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    else{
        $_SESSION['status'] = "Error Deleting Sub Contractor";
        $_SESSION['status_code'] = "error";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
}
// if(isset($_POST['mp_total'])){
//     $asgn_id = $_POST['asgn_id'];
//     $total = $_POST['total_mp'];
//     $id = $_POST['prj_id'];
//     $Date=$_POST['date'];
//     $flt_id = $_POST['flt_id'];
//     $q_update = "UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$asgn_id'";
//     $q_update_run = mysqli_query($connection,$q_update);
//     if($q_update_run)
//     {
//         header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
//     }
//     else
//     {
//         $_SESSION['status'] = "Error Updating";
//         $_SESSION['status_code'] = "error";
//         header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
//     }
// }
if(isset($_POST['mp_total'])){
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];
    $data = array(
        'asgn_id' => $_POST['asgn_id'],
        'total_mp' => $_POST['total_mp']
   ); 
   $count = count($_POST['asgn_id']);
   for ($i=0; $i < $count; $i++){
        $q_update = "UPDATE asgn_mp SET Asgn_MP_Total='{$_POST['total_mp'][$i]}' WHERE Asgn_MP_Id='{$_POST['asgn_id'][$i]}'";
        $q_update_run = mysqli_query($connection,$q_update);
        if($q_update_run)
        {
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
        }
        else
        {
            $_SESSION['status'] = "Error Updating";
            $_SESSION['status_code'] = "error";
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
        }
   }
    
}
// if(isset($_POST['sb_total'])){
//     $asgn_id = $_POST['asgn_id'];
//     $total = $_POST['total_sb'];
//     $id = $_POST['prj_id'];
//     $Date=$_POST['date'];
//     $flt_id = $_POST['flt_id'];
//     $q_update = "UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$asgn_id'";
//     $q_update_run = mysqli_query($connection,$q_update);
//     if($q_update_run){
//         header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
//     }
//     else{
//         $_SESSION['status'] = "Error Updating";
//         $_SESSION['status_code'] = "error";
//         header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
//     }
// }
if(isset($_POST['sb_total'])){
    $id = $_POST['prj_id'];
    $Date=$_POST['date'];
    $flt_id = $_POST['flt_id'];

    $data = array(
        'asgn_id' => $_POST['asgn_id'],
        'total_sb' => $_POST['total_sb']
   ); 
   $count = count($_POST['asgn_id']);
   for ($i=0; $i < $count; $i++){
        $q_update = "UPDATE asgn_subcon SET Asgn_SB_Total='{$_POST['total_sb'][$i]}' WHERE Asgn_SB_Id='{$_POST['asgn_id'][$i]}'";
        $q_update_run = mysqli_query($connection,$q_update);
        if($q_update_run){
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
        }
        else{
            $_SESSION['status'] = "Error Updating";
            $_SESSION['status_code'] = "error";
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
        }
   }
}
if(isset($_POST['update_all_pct'])){
    $id = $_POST['prj_id'];
    $flt_id = $_POST['flt_id'];
    $Date = $_POST['date'];
    $de_ids= $_POST['de_ids'];
    $DE_Progress = $_POST['percent'];
    $asgd_act_ids = $_POST['asgn_ids'];
    $act_id=$_POST['act_id'];
    if($act_id=='all'){
        $q_add="";
    }
    else{
        $q_add="AND Act_Id='$act_id'";
    }
    $q_de_id="SELECT DE_Id FROM daily_entry as de
                LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = de.Asgd_Act_Id
                WHERE DE_Id in ('$de_ids') $q_add";
    $q_de_id_run=mysqli_query($connection,$q_de_id);
    if(mysqli_num_rows($q_de_id_run)>0){
        while($row_de=mysqli_fetch_assoc($q_de_id_run)){
            $de_id_arr[]=$row_de['DE_Id'];
        }
        $de_ids = implode("', '", $de_id_arr);
    }
    
    if($DE_Progress>100){
        $_SESSION['status'] = "Please insert progress 100 and below";
        $_SESSION['status_code'] = "warning";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    if($de_ids==null){
        $_SESSION['status'] = "No Records to Update";
        $_SESSION['status_code'] = "warning";
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    // get previous value
    $check_1 = "SELECT * FROM assigned_activity where Asgd_Act_Id IN ('$asgd_act_ids')";
    $c1_run = mysqli_query($connection, $check_1);
    if(mysqli_num_rows($c1_run)>0){
        while($row=mysqli_fetch_assoc($c1_run)){
            $prev_percent = $row['Asgd_Pct_Done'];
            $asgd_act_id=$row['Asgd_Act_Id'];
            if($DE_Progress==100){
                // update asgd_activity table with date of completion
                $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress', Asgd_Act_Date_Completed='$Date' where Asgd_Act_Id='$asgd_act_id'";
                $q2_run = mysqli_query($connection, $q2);
                if($q2_run){
                    $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id IN ('$de_ids')";
                    if($connection->query($query)==FALSE){  
                        $message ="Updating Progress";
                        $session ='error';
                    }
                }
                else{
                    $message ="Updating Progress";
                    $session ='error';
                }
            }
            else{ // update asgd_activity table
                // check if higher from prev progress 95<80
                if($prev_percent<$DE_Progress){
                    $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$DE_Progress' where Asgd_Act_Id='$asgd_act_id'";
                    $q2_run = mysqli_query($connection, $q2);
                    if($q2_run){
                        $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id IN ('$de_ids')";
                        if($connection->query($query)==FALSE){  
                            $message ="Updating Progress";
                            $session ='error';
                        }
                    }
                    else{
                        $message ="Updating Progress";
                        $session ='error';
                    }
                }
                else{
                    $query="UPDATE daily_entry SET DE_Pct_Done='$DE_Progress' WHERE DE_Id IN ('$de_ids')";
                    if($connection->query($query)==FALSE){  
                        $message ="Updating Progress";
                        $session ='error';
                    }
                }
            }
        }
        if($session=='error'){
            $_SESSION['status'] = "Error Updating";
            $_SESSION['status_code'] = "error";
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    
        }
        else{
            header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
            $_SESSION['status'] = "Progress Updated";
            $_SESSION['status_code'] = "success";
        }
    }
}
// ADD MANPOWER
if(isset($_POST['addtoAll'])){
    $id=$_POST['prj_id'];
    $flt_id=$_POST['flat_id'];
    $DE_Ids=$_POST['DE_Ids'];
    $Date=$_POST['date'];
    $status ='';
    $message=''; 
    $act_id=$_POST['act_id'];
    if($act_id=='all'){
        $q_de="SELECT * FROM daily_entry WHERE DE_Id in ('$DE_Ids')";
    }
    else{
        $q_de="SELECT * FROM daily_entry as de
            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = de.Asgd_Act_Id
            WHERE de.DE_Id in ('$DE_Ids') AND as_act.Act_Id='$act_id'";
    }
    $q_de_run=mysqli_query($connection,$q_de);
    if(mysqli_num_rows($q_de_run)>0){
        while($row_de=mysqli_fetch_assoc($q_de_run)){
            $DE_Id=$row_de['DE_Id'];
            if(isset($_POST['employee'])){
                $data = array(
                    'emp_id' => $_POST['employee']
                );
                $count = count($_POST['employee']);
                for ($i=0; $i < $count; $i++){
                    // search if employee is already assigned to activty
                    $q_s="SELECT Emp_Id FROM asgn_worker WHERE Emp_Id='{$_POST['employee'][$i]}' AND DE_Id='$DE_Id' AND Asgd_Worker_Status=1";
                    $q_s_run=mysqli_query($connection,$q_s);
                    if(mysqli_num_rows($q_s_run)>0){
                        $status.='warning';
                        $message .='Employee already assigned to Activity <br>'; 
                    }
                    else{
                        if($_POST['employee'][$i]!='Select Employee'){
                            $query="INSERT INTO asgn_worker (Emp_Id, DE_Id,Asgd_Worker_Status) VALUES ('{$_POST['employee'][$i]}','$DE_Id',1)";
                            if($connection->query($query)==TRUE){
                            }
                            else{
                                $status .='error';
                                $message .='Error Inserting Employee <br>';
                            }
                        }
                    }
                }
            }
            if(isset($_POST['manpower'])){
                $data = array(
                    'manpower' => $_POST['manpower'],
                    'mp_qty' => $_POST['mp_qty']
                ); 
                $mpCount = count($_POST['manpower']);
                for($mpc=0; $mpc < $mpCount; $mpc++){
                    //search if manpower already assigned
                    $s_mp="SELECT MP_Id FROM asgn_mp WHERE MP_Id='{$_POST['manpower'][$mpc]}' AND DE_Id='$DE_Id' AND Asgn_MP_Status=1";
                    $s_mp_run=mysqli_query($connection,$s_mp);
                    if(mysqli_num_rows($s_mp_run)>0){
                        $status.='warning';
                        $message .='Manpower already assigned to Activity <br>'; 
                    }
                    else{
                        if($_POST['manpower'][$mpc]!='Select Manpower'){
                            $qMP="INSERT INTO asgn_mp (DE_Id,MP_Id,Asgn_MP_Qty,Asgn_MP_Status) VALUES ('$DE_Id','{$_POST['manpower'][$mpc]}','{$_POST['mp_qty'][$mpc]}','1')";
                            $qMP_run = mysqli_query($connection,$qMP);
                            if($qMP_run){
                            }
                            else{
                                $status .='error';
                                $message .='Error Inserting Manpower <br>';
                            }
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
                $sbCount = count($_POST['subcontractor']);
                for($sbc=0; $sbc < $sbCount; $sbc++){
                    // check if subcon already assigned
                    $s_sb="SELECT SB_Id FROM asgn_subcon WHERE SB_Id='{$_POST['subcontractor'][$sbc]}' AND DE_Id='$DE_Id' AND Asgn_SB_Status=1";
                    $s_sb_run=mysqli_query($connection,$s_sb);
                    if(mysqli_num_rows($s_sb_run)>0){
                        $status.='warning';
                        $message .='Subcontractor already assigned to Activity <br>'; 
                    }
                    else{
                        if($_POST['subcontractor'][$sbc]!='Select Subcontractor'){
                            $qSB="INSERT INTO asgn_subcon (DE_Id,SB_Id,Asgn_SB_Qty,Asgn_SB_Status) VALUES ('$DE_Id','{$_POST['subcontractor'][$sbc]}','{$_POST['sb_qty'][$sbc]}','1')";
                            $qSB_run = mysqli_query($connection,$qSB);
                            if($qSB_run){
                            }
                            else{
                                $status .='error';
                                $message .='Error Inserting Subcontractor <br>';
                            }
                        }
                    }
                }
            }
        }
    }
    else{
        $_SESSION['status']="Error Adding Labours";
        $_SESSION['status_code']='error';
        header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
    }
    $error_count = substr_count($status, 'error');
    $warning_count = substr_count($status, 'warning');
    if($warning_count>0 OR $error_count>0){
        if($warning_count> $error_count){
            $status='warning';
        }
        else{
            $status='error';
        }
    }
    else{
        $message ='Success Adding Workers';
        $status='success';
    }
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: activity_mult.php?id='.$id.'&flt_id='.$flt_id.'&date='.$Date);
}
?>