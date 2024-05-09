<?php 
include('dbconfig.php');
$q_act="SELECT PAYSLIP_ID from payslip WHERE month(P_DATE)=1 and year(P_DATE)=2024";
$q_act_run=mysqli_query($connection,$q_act); $p_ids=null;
if(mysqli_num_rows($q_act_run)>0){
    while($row_a=mysqli_fetch_assoc($q_act_run)){
        $p_id=$row_a['PAYSLIP_ID'];
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
    $p_ids=implode("', '",$p_id_arr);
    echo $p_ids;
}
?>