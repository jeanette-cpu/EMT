<?php 
include('../security.php');
include('est_queries.php');

//project
if(isset($_POST['addPE'])){
    $code =serialCode();
    $name =$_POST['name'];
    $category =$_POST['category']; // building villa
    $type =$_POST['type']; // prj type (residential)
    $date =$_POST['date'];
    $emirate_loc =$_POST['location'];
    $client_id =$_POST['client_id'];
    $mc_id =$_POST['mc_id'];
    $cons_id =$_POST['cons_id'];
    if($client_id){
        $client_id="'$client_id'";
    }else{$client_id='NULL';}
    if($mc_id){
        $mc_id="'$mc_id'";
    }else{$mc_id='NULL';}
    if($cons_id){
        $cons_id="'$cons_id'";
    }else{$cons_id='NULL';}
    $q_insert="INSERT INTO project_estimation (PE_Code,PE_Name,PE_Category,PE_Type,PE_Date,PE_Emirate_Location,Client_Id, Main_Contractor_Id,Consultant_Id,PE_Status) VALUES ('$code','$name','$category','$type','$date','$emirate_loc',$client_id,$mc_id,$cons_id,1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Consultant Added";
        $_SESSION['status_code'] = "success";
        header('Location: s_project.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_project.php');
    }
}
if(isset($_POST['editPE'])){
    $prj_id=$_POST['prj_id'];
    $name =$_POST['name'];
    $category =$_POST['category']; // building villa
    $type =$_POST['type']; // prj type (residential)
    $date =$_POST['date'];
    $emirate_loc =$_POST['location'];
    $client_id =$_POST['client_id'];
    $mc_id =$_POST['mc_id'];
    $cons_id =$_POST['cons_id'];
    $q_update="UPDATE project_estimation SET PE_Name='$name', PE_Category='$category', PE_Type='$type', PE_Date='$date', PE_Emirate_Location='$emirate_loc', Client_Id='$client_id', Main_Contractor_Id='$mc_id', Consultant_Id='$cons_id'  WHERE Prj_Est_Id='$prj_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Consultant Updated";
        $_SESSION['status_code'] = "success";
        header('Location: s_project.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_project.php');
    }
}
if(isset($_POST['delPE'])){
    $prj_id=$_POST['prj_id'];
    $q_update="UPDATE project_estimation SET PE_Status=0 WHERE Prj_Est_Id='$prj_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Consultant Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_project.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_project.php');
    }
}
// project system
if(isset($_POST['addPs'])){
    $desc=$_POST['desc'];
    $dept_id =$_POST['dept_id'];
    $q_insert="INSERT INTO project_system (Prj_Sys_Desc,Dept_Id,Prj_Sys_Status) VALUES ('$desc','$dept_id',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "System Inserted";
        $_SESSION['status_code'] = "success";
        header('Location: s_project_sys.php');
    }
    else{
        $_SESSION['status'] = "Error Inserting System";
        $_SESSION['status_code'] = "error";
        header('Location: s_project_sys.php');
    }
}
if(isset($_POST['editPs'])){
    $ps_id=$_POST['ps_id'];
    $desc=$_POST['desc'];
    $dept_id =$_POST['dept_id'];
    $q_update="UPDATE project_system SET Prj_Sys_Desc='$desc',Dept_Id='$dept_id' WHERE Prj_Sys_Id='$ps_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "System Updated";
        $_SESSION['status_code'] = "success";
        header('Location: s_project_sys.php');
    }
    else{
        $_SESSION['status'] = "Error Updating System";
        $_SESSION['status_code'] = "error";
        header('Location: s_project_sys.php');
    }
}
if(isset($_POST['delPs'])){
    $ps_id=$_POST['ps_id'];
    $q_update="UPDATE project_system SET Prj_Sys_Status=0 WHERE Prj_Sys_Id='$ps_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "System Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_project_sys.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting System";
        $_SESSION['status_code'] = "error";
        header('Location: s_project_sys.php');
    }
}
//clients 
if(isset($_POST['addClient'])){
    $client=$_POST['client'];
    $q_insert="INSERT INTO client (Client_Name,Client_Status) VALUES ('$client',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Client Added";
        $_SESSION['status_code'] = "success";
        header('Location: s_client.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Client";
        $_SESSION['status_code'] = "error";
        header('Location: s_client.php');
    }
}
if(isset($_POST['editClient'])){
    $c_id=$_POST['c_id'];
    $client=$_POST['client'];
    $q_update="UPDATE client SET Client_Name='$client' WHERE Client_Id='$c_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Client Details Updated";
        $_SESSION['status_code'] = "success";
        header('Location: s_client.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Client Details";
        $_SESSION['status_code'] = "error";
        header('Location: s_client.php');
    }
}
if(isset($_POST['delClient'])){
    $c_id=$_POST['c_id'];
    $q_update="UPDATE client SET Client_Status=0 WHERE Client_Id='$c_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Client Details Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_client.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Client Details";
        $_SESSION['status_code'] = "error";
        header('Location: s_client.php');
    }
}
//main contractor
if(isset($_POST['addMc'])){
    $mc=$_POST['mc'];
    $q_insert="INSERT INTO main_contractor (Main_Contractor_Name,Main_Contractor_Status) VALUES ('$mc',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Main Contractor Added";
        $_SESSION['status_code'] = "success";
        header('Location: s_main_contractor.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Main Contractor";
        $_SESSION['status_code'] = "error";
        header('Location: s_main_contractor.php');
    }
}
if(isset($_POST['editMc'])){
    $mc_id=$_POST['mc_id'];
    $mc=$_POST['mc'];
    $q_update="UPDATE main_contractor SET Main_Contractor_Name='$mc' WHERE Main_Contractor_Id='$mc_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Main Contractor Updated";
        $_SESSION['status_code'] = "success";
        header('Location: s_main_contractor.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Main Contractor";
        $_SESSION['status_code'] = "error";
        header('Location: s_main_contractor.php');
    }
}
if(isset($_POST['delMc'])){
    $mc_id=$_POST['mc_id'];
    $q_update="UPDATE main_contractor SET Main_Contractor_Status=0 WHERE Main_Contractor_Id='$mc_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Main Contractor Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_main_contractor.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Main Contractor";
        $_SESSION['status_code'] = "error";
        header('Location: s_main_contractor.php');
    }
}
//consultant
if(isset($_POST['addCons'])){
    $cons=$_POST['cons'];
    $q_insert="INSERT INTO consultant (Consultant_Name ,Consultant_Status ) VALUES ('$cons',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Consultant Added";
        $_SESSION['status_code'] = "success";
        header('Location: s_consultant.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_consultant.php');
    }
}
if(isset($_POST['editCons'])){
    $cons_id=$_POST['cons_id'];
    $cons=$_POST['cons'];
    $q_update="UPDATE consultant SET Consultant_Name='$cons' WHERE Consultant_Id='$cons_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Consultant Updated";
        $_SESSION['status_code'] = "success";
        header('Location: s_consultant.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_consultant.php');
    }
}
if(isset($_POST['delCons'])){
    $cons_id=$_POST['cons_id'];
    $q_update="UPDATE consultant SET Consultant_Status=0 WHERE Consultant_Id='$cons_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Consultant Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_consultant.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Consultant";
        $_SESSION['status_code'] = "error";
        header('Location: s_consultant.php');
    }
}
//estimation 
if(isset($_POST['addEs'])){
    $prj_id=$_POST['prj_id'];
    $sys_id=$_POST['sys_id'];
    $no_apartment=$_POST['no_apt'];
    $no_bathr=$_POST['no_br'];
    $con_load=$_POST['con_load'];
    $tot_ton=$_POST['tot_ton'];
    $ave_bua=$_POST['ave_bua'];
    $ave_bua = str_replace(',', '', $ave_bua);
    $tot_bua=$_POST['tot_bua'];
    $tot_bua = str_replace(',', '', $tot_bua);
    $sys_price=$_POST['tot_sysP'];
    $sys_price = str_replace(',', '', $sys_price);
    $stat_id=$_POST['status_id'];
    $q_insert="INSERT INTO estimate (Prj_Est_Id,Prj_Sys_Id,Est_No_Appartment,Est_No_Bathroom,Est_Connected_Load,Est_Total_Tonnage,Est_Ave_BUA,Est_Total_BUA,Est_Total_Price,Estimate_Status_Id,Est_Status) VALUES ('$prj_id','$sys_id','$no_apartment','$no_bathr','$con_load','$tot_ton','$ave_bua','$tot_bua','$sys_price','$stat_id',1)";
    $q_insert_run=mysqli_query($connection,$q_insert);
    if($q_insert_run){
        $_SESSION['status'] = "Estimation Created";
        $_SESSION['status_code'] = "success";
        header('Location: est_project.php');
    }
    else{
        $_SESSION['status'] = "Error Creating Estimation";
        $_SESSION['status_code'] = "error";
        header('Location: est_project.php');
    }
}
if(isset($_POST['editEs'])){
    $es_id=$_POST['est_id'];
    $prj_id=$_POST['prj_id'];
    $sys_id=$_POST['sys_id'];
    $no_apartment=$_POST['no_apt'];
    $no_bathr=$_POST['no_br'];
    $con_load=$_POST['con_load'];
    $con_load = str_replace(',', '', $con_load);
    $tot_ton=$_POST['tot_ton'];
    $tot_ton = str_replace(',', '', $tot_ton);
    $ave_bua=$_POST['ave_bua'];
    $ave_bua = str_replace(',', '', $ave_bua);
    $tot_bua=$_POST['tot_bua'];
    $tot_bua = str_replace(',', '', $tot_bua);
    $sys_price=$_POST['tot_sysP'];
    $sys_price = str_replace(',', '', $sys_price);
    $stat_id=$_POST['status_id'];
    $q="UPDATE estimate SET Prj_Est_Id='$prj_id', Prj_Sys_Id='$sys_id', Est_No_Appartment='$no_apartment', Est_No_Bathroom='$no_bathr', Est_Connected_Load='$con_load', Est_Total_Tonnage='$tot_ton', Est_Ave_BUA='$ave_bua', Est_Total_BUA='$tot_bua', Est_Total_Price='$sys_price', Estimate_Status_Id='$stat_id' WHERE Estimate_Id='$es_id'";
    $q_run=mysqli_query($connection,$q);
    if($q_run){
        $_SESSION['status'] = "Estimation Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: est_project.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Estimation";
        $_SESSION['status_code'] = "error";
        header('Location: est_project.php');
    }
}
if(isset($_POST['delEs'])){
    $es_id=$_POST['est_id'];
    $q="UPDATE estimate SET Est_Status=0 WHERE Estimate_Id='$es_id'";
    $q_run=mysqli_query($connection,$q);
    if($q_run){
        $_SESSION['status'] = "Estimation Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: est_project.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Estimation";
        $_SESSION['status_code'] = "error";
        header('Location: est_project.php');
    }
}
?>