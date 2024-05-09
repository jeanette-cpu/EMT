<?php 
include('../security.php');
include('est_queries.php');

//project
if(isset($_POST['addPE'])){
    $code=$_POST['prj_code'];
    $name =$_POST['name'];
    $category =$_POST['category']; // building villa
    $type =$_POST['prj_type']; // prj type (residential)
    $date =$_POST['date'];
    $emirate_loc =$_POST['location'];
    $client_id =$_POST['client_id'];
    $mc_id =$_POST['mc_id'];
    $cons_id =$_POST['cons_id'];
    //count characters
    if(strlen($client_id)>1){
        //add to clients
        $q_insert_client="INSERT INTO client (Client_Name,Client_Status) VALUES ('$client_id',1)";
        if($connection->query($q_insert_client)===TRUE){  
            $client_id = $connection->insert_id;
        }
        else{
            $_SESSION['status'] = "Error Adding Client";
            $_SESSION['status_code'] = "error";
            header('Location: s_client.php');
        }
    }
    if(strlen($mc_id)>1){
        //add to main contractor
        $q_insert_mc="INSERT INTO main_contractor (Main_Contractor_Name,Main_Contractor_Status) VALUES ('$mc_id',1)";
        if($connection->query($q_insert_mc)===TRUE){ 
            $mc_id= $connection->insert_id;
        }
        else{
            $_SESSION['status'] = "Error Adding Main Contractor";
            $_SESSION['status_code'] = "error";
            header('Location: s_main_contractor.php');
        }
    }
    if(strlen($cons_id)>1){
        //add to cons 
        $q_insert_cons="INSERT INTO consultant (Consultant_Name ,Consultant_Status ) VALUES ('$cons_id',1)";
        if($connection->query($q_insert_cons)===TRUE){ 
            $cons_id= $connection->insert_id;
        }
        else{
            $_SESSION['status'] = "Error Adding Consultant";
            $_SESSION['status_code'] = "error";
            header('Location: s_consultant.php');
        }
    }
    // optional client/mc/consultant
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

    // detaild system price per department
    $hvac_sp=$_POST['hvac_sp']; $hvac_sp=str_replace(',', '', $hvac_sp);
    $elec_sp=$_POST['elec_sp']; $elec_sp=str_replace(',', '', $elec_sp);
    $plumb_sp=$_POST['plumb_sp']; $plumb_sp=str_replace(',', '', $plumb_sp);
    $ff_sp=$_POST['ff_sp']; $ff_sp=str_replace(',', '', $ff_sp);
    $fa_sp=$_POST['fa_sp']; $fa_sp=str_replace(',', '', $fa_sp);
    $lpg_sp=$_POST['lpg_sp']; $lpg_sp=str_replace(',', '', $lpg_sp);
    
    $q="UPDATE estimate SET Prj_Est_Id='$prj_id', Prj_Sys_Id='$sys_id', Est_No_Appartment='$no_apartment', Est_No_Bathroom='$no_bathr', Est_Connected_Load='$con_load', Est_Total_Tonnage='$tot_ton', Est_Ave_BUA='$ave_bua', Est_Total_BUA='$tot_bua', Est_Total_Price='$sys_price', Estimate_Status_Id='$stat_id', HVAC_sp='$hvac_sp', Electric_sp='$elec_sp', Plumbing_sp='$plumb_sp', FF_sp='$ff_sp', FA_sp='$fa_sp', LPG_sp='$lpg_sp' WHERE Estimate_Id='$es_id'";
    $q_run=mysqli_query($connection,$q);
    if($q_run){
        $_SESSION['status'] = "Estimation Updated";
        $_SESSION['status_code'] = "success";
        header('Location: est_project.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Estimation";
        $_SESSION['status_code'] = "error";
        header('Location: est_project.php');
    }
}
if(isset($_POST['delEs'])){
    $es_id=$_POST['est_id'];
    $prj_id=$_POST['prj_id'];
    $q_update="UPDATE project_estimation SET PE_Status=0 WHERE Prj_Est_Id='$prj_id'";
    $q_update_run=mysqli_query($connection,$q_update);
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
// revised
if(isset($_POST['addEstimation'])){
    // $code=$_POST['prj_code'];
    // $name =$_POST['name'];
    // $category =$_POST['category']; // building villa
    // $type =$_POST['prj_type']; // prj type (residential)
    // $date =$_POST['date'];
    // $emirate_loc =$_POST['location'];
    // $client_id =$_POST['client_id'];
    // $mc_id =$_POST['mc_id'];
    // $cons_id =$_POST['cons_id'];
    // // optional client/mc/consultant
    // if($client_id){
    //     $client_id="'$client_id'";
    // }else{$client_id='NULL';}
    // if($mc_id){
    //     $mc_id="'$mc_id'";
    // }else{$mc_id='NULL';}
    // if($cons_id){
    //     $cons_id="'$cons_id'";
    // }else{$cons_id='NULL';}
    // $q_insert="INSERT INTO project_estimation (PE_Code,PE_Name,PE_Category,PE_Type,PE_Date,PE_Emirate_Location,Client_Id, Main_Contractor_Id,Consultant_Id,PE_Status) VALUES ('$code','$name','$category','$type','$date','$emirate_loc',$client_id,$mc_id,$cons_id,1)";
    // if($connection->query($q_insert)===TRUE){          
        // $prj_id = $connection->insert_id;
        $prj_id = $_POST['prj_id'];
        $sys_id=$_POST['sys_id'];
        $no_apartment=$_POST['no_apt'];
        $no_bathr=$_POST['no_br'];
        $con_load=$_POST['con_load'];
        $tot_ton=$_POST['tot_ton'];
        
        // detaild system price per department
        $hvac_sp=$_POST['hvac_sp']; $hvac_sp=str_replace(',', '', $hvac_sp);
        $elec_sp=$_POST['elec_sp']; $elec_sp=str_replace(',', '', $elec_sp);
        $plumb_sp=$_POST['plumb_sp']; $plumb_sp=str_replace(',', '', $plumb_sp);
        $ff_sp=$_POST['ff_sp']; $ff_sp=str_replace(',', '', $ff_sp);
        $fa_sp=$_POST['fa_sp']; $fa_sp=str_replace(',', '', $fa_sp);
        $lpg_sp=$_POST['lpg_sp']; $lpg_sp=str_replace(',', '', $lpg_sp);

        $ave_bua=$_POST['ave_bua'];
        $ave_bua = str_replace(',', '', $ave_bua);
        $tot_bua=$_POST['tot_bua'];
        $tot_bua = str_replace(',', '', $tot_bua);
        $sys_price=$_POST['tot_sp'];
        $sys_price = str_replace(',', '', $sys_price);
        $stat_id=draftId();
        $q="INSERT INTO estimate (Prj_Est_Id,Prj_Sys_Id,Est_No_Appartment,Est_No_Bathroom,Est_Connected_Load,Est_Total_Tonnage,Est_Ave_BUA,Est_Total_BUA,Est_Total_Price,Estimate_Status_Id,HVAC_sp,Electric_sp,Plumbing_sp,FF_sp,FA_sp,LPG_sp,Est_Status) VALUES ('$prj_id','$sys_id','$no_apartment','$no_bathr','$con_load','$tot_ton','$ave_bua','$tot_bua','$sys_price','$stat_id','$hvac_sp','$elec_sp','$plumb_sp','$ff_sp','$fa_sp','$lpg_sp',1)";
        $q_run=mysqli_query($connection,$q);
        if($q_run){
            $_SESSION['status'] = "Estimation Created";
            $_SESSION['status_code'] = "success";
            header('Location: est_project.php');
        }
        else{
            //remove project 
            $remove_prj="DELETE FROM project_estimation WHERE Prj_Est_Id='$prj_id'";
            $remove_prj_run=mysqli_query($connection,$remove_prj);
            if($remove_prj_run){
                $_SESSION['status'] = "Error Creating Estimation";
                $_SESSION['status_code'] = "error";
                header('Location: est_project.php');
            }
            else{
                echo'error';
            }
        }
    // }
}
// if(isset($_POST['editEstimation'])){

// }
// USERS
if(isset($_POST['addUser'])){
    $username = $_POST['username']; 
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);
    $usertype = $_POST['usertype'];

    $email_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_STATUS=1";
    $email_query_run = mysqli_query($connection, $email_query);

    if(mysqli_num_rows($email_query_run) > 0){
        $_SESSION['status'] = "Username Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: s_user_setup.php');
    }
    else{
        if($username =="" or $password =="" or $usertype ==""){
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: s_user_setup.php');
        }
        else{
            if($password === $cpassword){
                $query = "INSERT INTO users (USERNAME,USER_PASSWORD,USERTYPE,USER_STATUS) VALUES ('$username','$password','$usertype', 1)";
                $query_run = mysqli_query($connection, $query);
               if($query_run){
                    // success
                    $_SESSION['status'] = "Success creating user";
                    $_SESSION['status_code'] = "success";
                    header('Location: s_user_setup.php');
                }
                else{
                    //error
                    $_SESSION['status'] = "Error creating user";
                    $_SESSION['status_code'] = "error";
                    header('Location: s_user_setup.php');
                }
            }
            else{
                $_SESSION['status'] = "Passord does not match";
                $_SESSION['status_code'] = "warning";
                header('Location: s_user_setup.php');
            }
        }
    }
}
// UPDATING USER
if(isset($_POST['updatebtn'])){
    // PASSING VARIABLE
    $id = $_POST['user_update_id'];
    $username = $_POST['edit_username'];
    $usertype=$_POST['edit_type'];
    $password = md5($_POST['edit_password']);
    // $usertype = $_POST ['update_usertype'];
    $email_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_STATUS=1";
    $email_query_run = mysqli_query($connection, $email_query);
    if($username =="" or $password ==""){
        $_SESSION['status'] = "Fill out the form";
        $_SESSION['status_code'] = "warning";
        header('Location: s_user_setup.php');
    }
    else{
        $query = "UPDATE users SET USERNAME='$username', USERTYPE='$usertype', USER_PASSWORD='$password' WHERE USER_ID='$id'";
        $query_run = mysqli_query($connection, $query);
        if($query_run){
            $_SESSION['status'] = "User is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: s_user_setup.php');
        }
        else{
            $_SESSION['status'] = "Error updating user";
            $_SESSION['status_code'] = "error";
            header('Location: s_user_setup.php');
        }
    }
}
// DELETING USER
if(isset($_POST['delete_btn'])){
    $id = $_POST['delete_id'];
    $query="UPDATE users SET USER_STATUS=0 WHERE USER_ID='$id'";
    $query_run = mysqli_query($connection, $query);
    if($query_run){
        $_SESSION['status'] = "User Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_user_setup.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: s_user_setup.php');
    }
}
//TARGET 
//clients 
if(isset($_POST['addTarget'])){
    $no=$_POST['target'];
    $year=$_POST['year'];
    $date=$year.'-01-01';
    //search if year already exist 
    $q_yr="SELECT * FROM target WHERE YEAR(Target_Date)='$year' AND Target_Status=1";
    $q_yr_run=mysqli_query($connection,$q_yr);
    if(mysqli_num_rows($q_yr_run)>0){
        $_SESSION['status'] = "Number of Target fot this year already exists";
        $_SESSION['status_code'] = "warning";
        header('Location: s_target.php');
    }
    else{
        $q_insert="INSERT INTO target (Target_Prj_No ,Target_Date ,Target_Status) VALUES ('$no','$date',1)";
        $q_insert_run=mysqli_query($connection,$q_insert);
        if($q_insert_run){
            $_SESSION['status'] = "Target Added";
            $_SESSION['status_code'] = "success";
            header('Location: s_target.php');
        }
        else{
            $_SESSION['status'] = "Error Adding Target";
            $_SESSION['status_code'] = "error";
            header('Location: s_target.php');
        }
    }
}
if(isset($_POST['editTarget'])){
    $id=$_POST['id'];
    $target=$_POST['target'];
    $year=$_POST['year'];
    $date=$year.'-01-01';
    //search if year already exist 
    $q_yr="SELECT * FROM target WHERE YEAR(Target_Date)='$year' AND Target_Status=1 AND Target_Id!='$id'";
    $q_yr_run=mysqli_query($connection,$q_yr);
    if(mysqli_num_rows($q_yr_run)>0){
        $_SESSION['status'] = "Number of Target fot this year already exists";
        $_SESSION['status_code'] = "warning";
        header('Location: s_target.php');
    }
    else{
        $q_update="UPDATE target SET Target_Prj_No='$target',Target_Date='$date'  WHERE Target_Id='$id'";
        $q_update_run=mysqli_query($connection,$q_update);
        if($q_update_run){
            $_SESSION['status'] = "Target Details Updated";
            $_SESSION['status_code'] = "success";
            header('Location: s_target.php');
        }
        else{
            $_SESSION['status'] = "Error Updating Target Details";
            $_SESSION['status_code'] = "error";
            header('Location: s_target.php');
        }
    }
}
if(isset($_POST['delTarget'])){
    $id=$_POST['id'];
    $q_update="UPDATE target SET Target_Status=0 WHERE Target_Id='$id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Target Details Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: s_target.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Target Details";
        $_SESSION['status_code'] = "error";
        header('Location: s_target.php');
    }
}
?>