<?php
include('../../security.php');
include('../FOREMAN/function.php');
$_SESSION['message'] = "";
// INSERTING USERS
if(isset($_POST['registerbtn']))
{
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);
    $usertype = $_POST['usertype'];
    $userstatus = $_POST['userstatus'];
    $dept_id = $_POST['Dept_Id'];

    $username_query = "SELECT * FROM users WHERE USERNAME='$username' and USER_STATUS=1";
    $username_query_run = mysqli_query($connection, $username_query);

    if(mysqli_num_rows($username_query_run) > 0)
    {
        $_SESSION['status'] = "Username Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: PMS/P_ADMIN/u_manage_users.php');
    }
    else
    {
        if($username =="" or $password =="" or $usertype =="")
        {
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: u_manage_users.php');
        }
        else
        {
            // confirming the password
            if($password === $cpassword)
            {
                if($dept_id=='Select Department')
                {
                    $query = "INSERT INTO users (USERNAME,USER_EMAIL,USER_PASSWORD,USERTYPE,Dept_Id,USER_STATUS) VALUES ('$username','$email','$password','$usertype',NULL,'$userstatus')";
                }
                else
                {
                    $query = "INSERT INTO users (USERNAME,USER_EMAIL,USER_PASSWORD,USERTYPE,Dept_Id,USER_STATUS) VALUES ('$username','$email','$password','$usertype','$dept_id','$userstatus')";
                }
                // echo $query;
                $query_run = mysqli_query($connection, $query);
                if($query_run)
                {
                    // success
                    $_SESSION['status'] = "User Added";
                    $_SESSION['status_code'] = "success";
                    header('Location: u_manage_users.php');
                }
                else
                {
                    //error
                    $_SESSION['status'] = "Error Adding User";
                    $_SESSION['status_code'] = "error";
                    header('Location: u_manage_users.php');
                }
            }
               else
               {
                   $_SESSION['status'] = "Passord does not match";
                   $_SESSION['status_code'] = "warning";
                   header('Location: u_manage_users.php');
               }
        }
        
    }
}

// UPDATING USER
if(isset($_POST['updatebtn']))
{
    // PASSING VARIABLE
    $id = $_POST['user_update_id'];
    $username = $_POST['user_username'];
    $password = md5($_POST['edit_password']);
    $usertype = $_POST ['usertype'];
    $dept_id = $_POST['dept_id'];
    $email_query = "SELECT * FROM users WHERE USERNAME='$username'";
    $email_query_run = mysqli_query($connection, $email_query);
    
    if($username =="" or $password =="" or $usertype =="")
    {
        $_SESSION['status'] = "Fill out the form";
        $_SESSION['status_code'] = "warning";
        header('Location: u_manage_users.php');
    }
    else
    {
        if($dept_id=='Select Department')
        {
            $query = "UPDATE users SET USERNAME='$username', USER_EMAIL='$email', USER_PASSWORD='$password', Dept_Id=NULL,USERTYPE='$usertype' WHERE USER_ID='$id'";
        }
        else{
            $query = "UPDATE users SET USERNAME='$username', USER_EMAIL='$email', USER_PASSWORD='$password', Dept_Id='$dept_id',USERTYPE='$usertype' WHERE USER_ID='$id'";
        }
        $query_run = mysqli_query($connection, $query);
        if($query_run)
        {
            $_SESSION['status'] = "Your Data is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: u_manage_users.php');
        }
        else
        {
            $_SESSION['status'] = "Your Data is NOT Updated";
            $_SESSION['status_code'] = "error";
            header('Location: u_manage_users.php');
        }
    }
}

// DELETING USER
if(isset($_POST['delete_btn']))
{
    $id = $_POST['delete_id'];
    $query="UPDATE users SET USER_STATUS=0 WHERE USER_ID='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "User Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: u_manage_users.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: u_manage_users.php');
    }
}

// INSERT PROJECTS
if(isset($_POST['add_prj']))
{
    $prj_code = $_POST['prj_code'];
    $prj_name = $_POST['prj_name'];
    $prj_category = $_POST['prj_category'];
    $prj_type = $_POST['prj_type'];
    $prj_date_start = $_POST['prj_date_start'];
    $prj_due_date = $_POST['prj_due_date'];
    $prj_location = $_POST['prj_location'];
    $prj_loc_desc = $_POST['prj_loc_desc'];
    $prj_client_name = $_POST['prj_client_name'];
    $prj_main_contractor = $_POST['prj_main_contractor'];
    $prj_consultant = $_POST['prj_consultant'];
    $prj_status = $_POST['prj_status'];
    // $default=$_POST['actStandard'];

    $prj_code_query = "SELECT * FROM project WHERE Prj_Code='$prj_code'";
    $prj_code_query_run = mysqli_query($connection, $prj_code_query);

    if(mysqli_num_rows($prj_code_query_run) > 0){
        $_SESSION['status'] = "Project Code Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: p_project.php');
    }
    else{
        // INSERT QUERY
        $query = "INSERT INTO project (Prj_Code,Prj_Name,Prj_Category,Prj_Type,Prj_Start_Date,Prj_End_Date,Prj_Emirate_Location,Prj_Location_Desc,Prj_Client_Name,Prj_Main_Contractor,Prj_Consultant,Prj_Status) VALUES ('$prj_code','$prj_name','$prj_category','$prj_type', '$prj_date_start','$prj_due_date','$prj_location','$prj_loc_desc','$prj_client_name','$prj_main_contractor','$prj_consultant','$prj_status')";
        if($connection->query($query)===TRUE){
            $_SESSION['status'] = "Project Details Added";
            $_SESSION['status_code'] = "success";
            header('Location: p_project.php');
        }
        else {
            //error
            $_SESSION['status'] = "Project Details Not Added";
            $_SESSION['status_code'] = "error";
            header('Location: p_project.php');
        }
    }
}

// UPDATE PROJECTS
if(isset($_POST['edit_prj']))
{
    $prj_id = $_POST['prj_id'];
    $e_prj_code = $_POST['e_prj_code'];
    $e_prj_name = $_POST['e_prj_name'];
    $e_prj_category = $_POST['e_prj_category'];
    $e_prj_type = $_POST['e_prj_type'];
    $e_prj_date_start = $_POST['e_prj_date_start'];
    $e_prj_due_date = $_POST['e_prj_due_date'];
    $e_prj_location = $_POST['e_prj_location'];
    $e_prj_loc_desc = $_POST['e_prj_loc_desc'];
    $e_prj_client_name = $_POST['e_prj_client_name'];
    $e_prj_main_contractor = $_POST['e_prj_main_contractor'];
    $e_prj_consultant = $_POST['e_prj_consultant'];

    $query = "UPDATE project SET Prj_Code='$e_prj_code', Prj_Name='$e_prj_name', Prj_Category='$e_prj_category', Prj_Type='$e_prj_type', Prj_Start_Date='$e_prj_date_start', Prj_End_Date='$e_prj_due_date', Prj_Emirate_Location='$e_prj_location', 	Prj_Location_Desc='$e_prj_loc_desc', 	Prj_Client_Name='$e_prj_client_name', Prj_Main_Contractor='$e_prj_main_contractor', Prj_Consultant='$e_prj_consultant' WHERE Prj_Id='$prj_id'";
    // echo $query;
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Project Details Updated";
        $_SESSION['status_code'] = "success";
        header('Location: p_project.php');
    }
    else
    {
        $_SESSION['status'] = "Project Details Not Updated";
        $_SESSION['status_code'] = "error";
        header('Location: p_project.php');
    }
}

// DELETING PROJECT
if(isset($_POST['delete_prj']))
{
    $id = $_POST['delete_id'];
    $query="UPDATE project SET Prj_Status=0 WHERE Prj_Id='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Project Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: p_project.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_project.php');
    }
}

// INSERT ASSIGN TO PRJ
if(isset($_POST['asgnBtn']))
{
   $prj_id = $_POST['prj_id'];
    $data = array(
        'emp_id' => $_POST['emp_id'],
        'username' => $_POST['username']
   ); 
   $count = count($_POST['emp_id']);
   for ($i=0; $i < $count; $i++){
    // check if exists
    $query="SELECT * FROM asgn_emp_to_prj WHERE Asgd_Emp_to_Prj_Status =1 and User_Id='{$_POST['username'][$i]}' and Prj_Id='$prj_id'";
    $query_run1 =mysqli_query($connection,$query);
        if(mysqli_num_rows($query_run1)>0)
        {
            $_SESSION['status'] = "User Already Assign to Project";
            $_SESSION['status_code'] = "error";
            header('Location: u_asgn_to_prj.php');
        }
        else
        {
            $sql="INSERT INTO asgn_emp_to_prj (Prj_Id,User_Id,Emp_Id,Asgd_Emp_to_Prj_Status) VALUES ('$prj_id','{$_POST['username'][$i]}','{$_POST['emp_id'][$i]}','1')";
            // echo $sql;
            $query_run = mysqli_query($connection,$sql);
            if($query_run)
            {
                $_SESSION['status'] = "Users Successfuly Assigned";
                $_SESSION['status_code'] = "success";
                    header('Location: u_asgn_to_prj.php');
            }
            else
            {
                $_SESSION['status'] = "Error Assigning Users";
                $_SESSION['status_code'] = "error";
                header('Location: u_asgn_to_prj.php');
            }
        }
   }
}

// EDIT ASSIGN USER TO PRJ
if(isset($_POST['eAsgnPrj']))
{
    $asgn_id = $_POST['eAsgnId'];
    $user_id = $_POST['eAsgnUsername'];
    $emp_id = $_POST['eAsgnEmp'];
    $prj_id = $_POST['eAsgnPrj'];

    $query = "UPDATE asgn_emp_to_prj SET Prj_id='$prj_id', User_Id='$user_id', Emp_id='$emp_id' WHERE Asgd_Emp_to_Prj='$asgn_id'";
    // echo $query;
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Assignation Updated";
        $_SESSION['status_code'] = "success";
        header('Location: u_asgn_to_prj.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: u_asgn_to_prj.php');
    }

}

// DELETE ASSIGNED USER FROM PROJECT
if(isset($_POST['delete_asgn_user']))
{
    $id = $_POST['delete_id'];
    $query="UPDATE asgn_emp_to_prj SET Asgd_Emp_to_Prj_Status=0 WHERE Asgd_Emp_to_Prj='$id'";
    // echo $query;
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Assignation Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: u_asgn_to_prj.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: u_asgn_to_prj.php');
    }
}

// ADD VILLA
if(isset($_POST['addVilla']))
{
    $prj_id = $_POST['prj_id'];
    $data = array(
        'villa_code' => $_POST['villa_code'],
        'villa_name' => $_POST['villa_name']
   ); 
   $count = count($_POST['villa_code']);
   for ($i=0; $i < $count; $i++){
       $sql="INSERT INTO villa (Villa_Code,Villa_Name,Villa_Status,Prj_Id) VALUES ('{$_POST['villa_code'][$i]}','{$_POST['villa_name'][$i]}','1','$prj_id')";
   
        $query_run = mysqli_query($connection,$sql);
       if($query_run)
       {
           $_SESSION['status'] = "Villa Added";
           $_SESSION['status_code'] = "success";
            header('Location: p_villa.php?id='.$prj_id);
       }
       else
       {
            $_SESSION['status'] = "Error Adding Villa";
            $_SESSION['status_code'] = "error";
            header('Location: p_villa.php?id='.$prj_id);
       }
    }
}

//EDIT VILLA
if(isset($_POST['editVilla']))
{
    $v_id = $_POST['e_vId'];
    $prj_id = $_POST['prj_id'];
    $v_code = $_POST['e_vcode'];
    $v_name = $_POST['e_vname'];

    $query="UPDATE villa SET villa_code='$v_code', villa_name='$v_name' WHERE Villa_Id='$v_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Updated Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_villa.php?id='.$prj_id);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_villa.php?id='.$prj_id);
    }
}

//DELETE VILLA
if(isset($_POST['delVilla']))
{
    $v_id = $_POST['villa_id'];
    $prj_id = $_POST['prj_id'];
    $query="UPDATE villa SET Villa_Status=0 WHERE Villa_Id='$v_id'";
    $query_run=mysqli_query($connection,$query);
    // echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Deleted Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_villa.php?id='.$prj_id);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_villa.php?id='.$prj_id);
    }
}

// ADD BUILDING
if(isset($_POST['addBlg']))
{
    $prj_id = $_POST['prj_id'];
    $data = array(
        'blgCode' => $_POST['blgCode'],
        'blgName' => $_POST['blgName']
   ); 
   $count = count($_POST['blgCode']);
   for ($i=0; $i < $count; $i++){
       $sql="INSERT INTO building (Blg_Code,Blg_Name,Blg_Status,Prj_Id) VALUES ('{$_POST['blgCode'][$i]}','{$_POST['blgName'][$i]}','1','$prj_id')";
        $query_run = mysqli_query($connection,$sql);
       if($query_run)
       {
           $_SESSION['status'] = "Building Added";
           $_SESSION['status_code'] = "success";
            header('Location: p_building.php?id='.$prj_id);
       }
       else
       {
            $_SESSION['status'] = "Error Adding Villa";
            $_SESSION['status_code'] = "error";
            header('Location: p_building.php?id='.$prj_id);
       }
    }
}

// EDIT BUILDING
if(isset($_POST['editBuilding']))
{
    $b_id = $_POST['e_bId'];
    $prj_id = $_POST['prj_id'];
    $b_code = $_POST['e_bcode'];
    $b_name = $_POST['e_bname'];

    $query="UPDATE building SET blg_code='$b_code', blg_name='$b_name' WHERE Blg_Id='$b_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Updated Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_building.php?id='.$prj_id);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_building.php?id='.$prj_id);
    }
}

// DELETE BUILDING
if(isset($_POST['delBlg']))
{
    $b_id = $_POST['blg_id'];
    $prj_id = $_POST['prj_id'];
    $query="UPDATE building SET Blg_Status=0 WHERE Blg_Id='$b_id'";
    $query_run=mysqli_query($connection,$query);
    // echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Deleted Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_building.php?id='.$prj_id);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_building.php?id='.$prj_id);
    } 
}

// ADD BUILDING
if(isset($_POST['addBlg_plx']))
{
    $prj_name = $_POST['prj_name'];
    $plx_id = $_POST['plx_id'];
    $data = array(
        'blgCode' => $_POST['blgCode'],
        'blgName' => $_POST['blgName']
   ); 
   $count = count($_POST['blgCode']);
   for ($i=0; $i < $count; $i++){
       $sql="INSERT INTO building (Blg_Code,Blg_Name,Blg_Status,Plx_Id) VALUES ('{$_POST['blgCode'][$i]}','{$_POST['blgName'][$i]}','1','$plx_id')";
        // echo $sql;
        $query_run = mysqli_query($connection,$sql);
       if($query_run)
       {
           $_SESSION['status'] = "Building Added";
           $_SESSION['status_code'] = "success";
            header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
       }
       else
       {
            $_SESSION['status'] = "Error Adding Villa";
            $_SESSION['status_code'] = "error";
            header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
       }
    }
}

// EDIT BUILDING
if(isset($_POST['editBuilding_plx'])){
    $plx_id = $_POST['plx_id'];
    $prj_name = $_POST['prj_name'];

    $b_id = $_POST['e_bId'];
    $b_code = $_POST['e_bcode'];
    $b_name = $_POST['e_bname'];

    $query="UPDATE building SET blg_code='$b_code', blg_name='$b_name' WHERE Blg_Id='$b_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Updated Successfully";
        $_SESSION['status_code'] = "success";
            header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
    }
}

// DELETE BUILDING
if(isset($_POST['delBlg_plx']))
{
    $b_id = $_POST['blg_id'];
    $plx_id = $_POST['plx_id'];
    $prj_name=$_POST['prj_name'];
    $query="UPDATE building SET Blg_Status=0 WHERE Blg_Id='$b_id'";
    $query_run=mysqli_query($connection,$query);
    // echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Deleted Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_building_plx.php?id='.$plx_id.'&prj_name='.$prj_name);
    } 
}
if(isset($_POST['mpTotal']))
{
    $asgn_mp_id = $_POST['Asgn_MP_Id'];
    $total = $_POST['Asgn_MP_Total'];
    $mp= $_POST['mp_id'];
    $from=$_POST['from'];
    $to=$_POST['to'];
    //update
    $q_update="UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$asgn_mp_id'";
    $q_update_run=mysqli_query($connection, $q_update);

    if($q_update_run)
    {
        $_SESSION['status'] = "Total Updated";
        $_SESSION['status_code'] = "success";
        header('Location: m_mp.php?id='.$mp.'&from='.$from.'&to='.$to);
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: m_mp.php?id='.$mp.'&from='.$from.'&to='.$to);
    }
}
if(isset($_POST['sbTotal']))
{
    $asgn_sb_id = $_POST['Asgn_SB_Id'];
    $total = $_POST['Asgn_SB_Total'];
    $sb= $_POST['sb_id'];
    $from=$_POST['from'];
    $to=$_POST['to'];
    //update
    $q_update="UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$asgn_sb_id'";
    $q_update_run=mysqli_query($connection, $q_update);

    if($q_update_run)
    {
        $_SESSION['status'] = "Total Updated";
        $_SESSION['status_code'] = "success";
        header('Location: m_sb.php?id='.$sb.'&from='.$from.'&to='.$to);
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: m_sb.php?id='.$sb.'&from='.$from.'&to='.$to);
    }
}
if(isset($_POST['asgn_standard'])){
    $prj_id=$_POST['prj_id'];
    $data = array(
        'act_id' => $_POST['act_id'],
        'emp_r' => $_POST['emp_r'],
        'output_r' => $_POST['output_r']
   ); 
   $count = count($_POST['act_id']);
   if($count>0){
    for ($i=0; $i < $count; $i++){
        //search if its already assigned
        $q_search="SELECT * FROM activity_standard WHERE Act_Id='{$_POST['act_id'][$i]}' AND Prj_Id='$prj_id' AND Act_Standard_Status=1";
        $q_search_run=mysqli_query($connection,$q_search);
        if(mysqli_num_rows($q_search_run)>0){ // error alreardy assigned
            $row_s=mysqli_fetch_assoc($q_search_run);
            $act_std_id=$row_s['Act_Standard_Id '];
            $emp_r=$row_s['Act_Standard_Emp_Ratio'];
            // if($emp_r){ // existing -> update
                $q_update="UPDATE activity_standard SET Act_Standard_Emp_Ratio='{$_POST['emp_r'][$i]}', Act_Standard_Output_Ratio='{$_POST['output_r'][$i]}', WHERE Act_Standard_Id='$act_std_id'";
                $q_update_run=mysqli_query($connection,$q_update);
            // }
            // else{//
            // }
        }
        else{
            $q_assign="INSERT INTO activity_standard (Act_Standard_Emp_Ratio,Act_Standard_Output_Ratio,Act_Id,Prj_Id,Act_Standard_Status) VALUES ('{$_POST['emp_r'][$i]}','{$_POST['output_r'][$i]}','{$_POST['act_id'][$i]}','$prj_id',1)";
            $q_assign_run=mysqli_query($connection,$q_assign);
            if($q_assign_run){
                $success++;
            }
        }
        
    }
    if($success>0){
        // 
        $_SESSION['status'] = "Done Setting Activity Standards $success Set";
        $_SESSION['status_code'] = "success";
        header('Location: p_project.php');
    }
   }
   else{//error
        $_SESSION['status'] = "Error Activity Setting Standard";
        $_SESSION['status_code'] = "error";
        header('Location: p_project.php');
   }
}
if(isset($_POST['asgn_standard_after'])){
    $prj_id=$_POST['prj_id'];
    if($_POST['e_act_id']){
        $data = array(
            'e_act_id' => $_POST['e_act_id'],
            'e_emp_r' => $_POST['e_emp_r'],
            'e_output_r' => $_POST['e_output_r']
       ); 
       $count = count($_POST['e_act_id']);
    }
    if($_POST['act_id']){
        $data1 = array(
            'act_id' => $_POST['act_id'],
            'emp_r' => $_POST['emp_r'],
            'output_r' => $_POST['output_r']
        ); 
        $count1 = count($_POST['act_id']);
    }
   if($count>0){
    for ($i=0; $i < $count; $i++){
        $q_update="UPDATE activity_standard SET Act_Standard_Emp_Ratio='{$_POST['e_emp_r'][$i]}', Act_Standard_Output_Ratio='{$_POST['e_output_r'][$i]}' WHERE Act_Standard_Id='{$_POST['e_act_id'][$i]}'";
        $q_update_run=mysqli_query($connection,$q_update);
    }
   }
   if($count1>0){
    for ($k=0; $k < $count1; $k++){
        $q_assign="INSERT INTO activity_standard (Act_Standard_Emp_Ratio,Act_Standard_Output_Ratio,Act_Id,Prj_Id,Act_Standard_Status) VALUES ('{$_POST['emp_r'][$k]}','{$_POST['output_r'][$k]}','{$_POST['act_id'][$k]}','$prj_id',1)";
        $q_assign_run=mysqli_query($connection,$q_assign);
    }
   }
   if($q_update_run || $q_assign_run){
        $_SESSION['status'] = "Done Setting Activity Standard";
        $_SESSION['status_code'] = "success";
        header('Location: p_act_standard.php?id='.$prj_id);
   }
   else{
        $_SESSION['status'] = "Error Activity Setting Standard";
        $_SESSION['status_code'] = "error";
        header('Location: p_act_standard.php?id='.$prj_id);
   }
}
if(isset($_POST['addType'])){
    $type_code=$_POST['type_code'];
    $type_name=$_POST['type_name'];
    $prj_id=$_POST['prj_id'];
    $prj_type=$_POST['prj_type'];
    //INSERT FLAT TYPE
    $q_insert="INSERT INTO flat_type (Flat_Type_Code, Flat_Type_Name, Flat_Type_Status, Prj_Id) VALUES ('$type_code','$type_name','1','$prj_id')";
    if($connection->query($q_insert)===TRUE){
        $last_id = $connection->insert_id;
        //INSERT FLAT/VILLA flat_asgn_to_type
        if($_POST['flat_id']){
            if(in_array("all",$_POST['flat_id'])){
                $flat_ids=getFlatIds($prj_id);
            }
            else{
                $flat_ids=implode("', '", $_POST['flat_id']);
            }
            $q_flat="SELECT * FROM flat WHERE Flat_Id IN ('$flat_ids')";
            $q_flat_run=mysqli_query($connection,$q_flat);
            if(mysqli_num_rows($q_flat_run)>0){
                while($row_f=mysqli_fetch_assoc($q_flat_run)){
                    $flt_id=$row_f['Flat_Id'];
                    $flat_code=$row_f['Flat_Code'];
                    $flat_name=$row_f['Flat_Name'];
                    // check if flat already belong to type
                    $q_chk_f="SELECT * FROM flat_asgn_to_type as f_asgn
                            LEFT JOIN flat_type as ft on ft.Flat_Type_Id = f_asgn.Flat_Type_Id
                            WHERE f_asgn.Flat_Id='$flt_id' AND f_asgn.Flat_Asgd_Status=1";
                    $q_chk_f_run=mysqli_query($connection,$q_chk_f);
                    if(mysqli_num_rows($q_chk_f_run)>0){
                        $row_cf=mysqli_fetch_assoc($q_chk_f_run);
                        $type_name=$row_cf['Flat_Type_Code'].' '.$row_cf['Flat_Type_Name'];
                        $message.="flat: ".$flat_name." already assigned to type ".$type_name."<br>";
                        $session .='warning';
                    }
                    else{
                        $asgn_flat="INSERT INTO flat_asgn_to_type (Flat_Asgd_Status, Flat_Id, Flat_Type_Id ) VALUES ('1','$flt_id','$last_id')";
                        $asgn_flat_run=mysqli_query($connection,$asgn_flat);
                        if($asgn_flat_run){
                        }
                        else{
                            $message .="Error assigning flat ".$flat_name.'<br>';
                            $session .='warning';
                        }
                    }
                }
            }
        }
        //INSERT ACTIVITY flat_type_asgn_act
        if($_POST['act_id']){
            if(in_array("all",$_POST['act_id'])){
                $q_activities="SELECT * FROM activity WHERE Act_Category='$prj_type' AND Act_Status=1";
            }
            else{
                $act_ids=implode("', '", $_POST['act_id']);
                $q_activities="SELECT * FROM activity WHERE Act_Id IN ('$act_ids')";
            }
            // echo $q_activities;
            $q_activities_run=mysqli_query($connection,$q_activities);
            if(mysqli_num_rows($q_activities_run)>0){
                while($row_c=mysqli_fetch_assoc($q_activities_run)){
                    $act_id = $row_c['Act_Id'];
                    $act_name=$row_c['Act_Code'].' '.$row_c['Act_Name'];
                    //check if activity is already assigned to type
                    $q_chk_act="SELECT * FROM flat_type_asgn_act WHERE Act_Id='$act_id' AND Flt_Asgn_Act_Status='1' AND Flat_Type_Id='$last_id'";
                    $q_chk_act_run=mysqli_query($connection,$q_chk_act);
                    if(mysqli_num_rows($q_chk_act_run)>0){
                        $message .="Activity: ".$act_name." already assigned to ".$type_name.'<br>';
                        $session .='warning';
                    }
                    else{
                        $insert_asgn_act="INSERT INTO flat_type_asgn_act (Flt_Asgn_Act_Status, Act_Id, Flat_Type_Id ) VALUES ('1','$act_id','$last_id')";
                        $insert_asgn_act_run=mysqli_query($connection,$insert_asgn_act);
                        if($insert_asgn_act_run){
                        }
                        else{
                            $message .="Error assigning activity ".$act_name.'<br>';
                            $session .='warning';
                        } 
                    }
                }
            }
        }
        //ASSIGN THE ACTIVITIES
        //select the flats per type
        $q_flats="SELECT * FROM flat_asgn_to_type as flt_asgn
                LEFT JOIN flat as flt on flt.Flat_Id=flt_asgn.Flat_Id
                WHERE flt_asgn.Flat_Type_Id='$last_id' AND flt_asgn.Flat_Asgd_Status=1 AND flt.Flat_Status=1";
        $q_flats_run=mysqli_query($connection,$q_flats);
        if(mysqli_num_rows($q_flats_run)>0){
            while($row_fa=mysqli_fetch_assoc($q_flats_run)){
                $flat_id=$row_fa['Flat_Id'];
                $flat_name=$row_fa['Flat_Code'].' '.$row_fa['Flat_Name'];
                // select activities assigned to flat type
                $act_query="SELECT * FROM flat_type_asgn_act as ft_act
                            LEFT JOIN activity as act on act.Act_Id=ft_act.Act_Id
                            WHERE ft_act.Flat_Type_Id='$last_id' AND ft_act.Flt_Asgn_Act_Status='1' AND act.Act_Status='1'";
                $act_query_run=mysqli_query($connection,$act_query);
                if(mysqli_num_rows($act_query_run)>0){
                    while($row_asgn=mysqli_fetch_assoc($act_query_run)){
                        $act_id_=$row_asgn['Act_Id'];
                        $act_cat_id=$row_asgn['Act_Cat_Id'];
                        $act_name=$row_asgn['Act_Code'].' '.$row_asgn['Act_Name'];
                        //CHECK IF ACTIVITY ALREADY ASSIGNED
                        $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flat_id' AND Act_Id='$act_id_' AND Asgd_Act_Status=1";
                        $q_search_run=mysqli_query($connection,$q_search);
                        if(mysqli_num_rows($q_search_run)>0){
                            $message .='already exists: flat '.$flat_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                            $session .='warning';
                        }
                        else{
                            $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ('$flat_id','$act_id_',1,'$act_cat_id',0)";
                            $q_insert_run=mysqli_query($connection,$q_insert);
                            if($q_insert_run){
                                //inserted
                            }
                            else{
                                $message .='error insert activity on: flat '.$flt_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                $session .='error';
                            }
                        }
                    }
                }
            }
        }
    }
    else{
        $_SESSION['status'] = "Error Creating Type";
        $_SESSION['status_code'] = "error";
        header('Location: p_flt_type.php?prj_id='.$prj_id);
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
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: p_flt_type.php?prj_id='.$prj_id);
}
if(isset($_POST['editType'])){
    $type_id=$_POST['type_id'];
    $type_code=$_POST['type_code'];
    $type_name=$_POST['type_name'];
    $prj_id=$_POST['prj_id'];
    $update_type="UPDATE flat_type SET Flat_Type_Code='$type_code', Flat_Type_Name='$type_name' WHERE Flat_Type_Id='$type_id'";
    $update_type_run=mysqli_query($connection,$update_type);
    if($update_type_run){
        $_SESSION['status'] = "Type Updated";
        $_SESSION['status_code'] = "success";
        header('Location: p_flt_type.php?prj_id='.$prj_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Type";
        $_SESSION['status_code'] = "error";
        header('Location: p_flt_type.php?prj_id='.$prj_id);
    }
}
if(isset($_POST['add_flt'])){
    $type_id=$_POST['type_id'];
    if($_POST['flat_id']){
        $q_flat_asgn="SELECT * FROM flat_asgn_to_type 
                        WHERE Flat_Asgd_Status=1 AND Flat_Id IN ('$flat_ids')";
        $q_flat_asgn_run=mysqli_query($connection,$q_flat_asgn);
        if(mysqli_num_rows($q_flat_asgn_run)>0){
            while($row_f=mysqli_fetch_assoc($q_flat_asgn_run)){
                $flat_arr[]=$row_f['Flat_Id']; //
            }
            $asgn_ids = implode("', '", $flat_arr); 
        }
        if(in_array("all",$_POST['flat_id'])){ // ALL flats in the 
            $flat_ids=getFlatIds($prj_id);
        }
        else{
            $flat_ids=implode("', '", $_POST['flat_id']);
        }
        $q_flat="SELECT * FROM flat WHERE Flat_Id IN ('$flat_ids') AND Flat_Id NOT IN ('$asgn_ids')";
        $q_flat_run=mysqli_query($connection,$q_flat);
        if(mysqli_num_rows($q_flat_run)>0){
            while($row_f=mysqli_fetch_assoc($q_flat_run)){
                $flt_id=$row_f['Flat_Id'];
                $flat_code=$row_f['Flat_Code'];
                $flat_name=$row_f['Flat_Name'];
                // check if flat already belong to type
                $q_chk_f="SELECT * FROM flat_asgn_to_type as f_asgn
                        LEFT JOIN flat_type as ft
                        WHERE f_asgn.Flat_Id='$flt_id' AND f_asgn.Flat_Asgd_Status=1";
                $q_chk_f_run=mysqli_query($connection,$q_chk_f);
                if(mysqli_num_rows($q_chk_f_run)>0){
                    $row_cf=mysqli_fetch_assoc($q_chk_f_run);
                    $type_name=$row_cf['Flat_Type_Code'].' '.$row_cf['Flat_Type_Name'];
                    $message.="flat: ".$flat_name." already assigned to type ".$type_name."<br>";
                    $session .='warning';
                }
                else{
                    $asgn_flat="INSERT INTO flat_asgn_to_type (Flat_Asgd_Status, Flat_Id, Flat_Type_Id ) VALUES ('1','$flt_id','$type_id')";
                    $asgn_flat_run=mysqli_query($connection,$asgn_flat);
                    if($asgn_flat_run){
                        //assigned the activities
                        // select activities assigned to flat type
                        $act_query="SELECT * FROM flat_type_asgn_act as ft_act
                                    LEFT JOIN activity as act on act.Act_Id=ft_act.Act_Id
                                    WHERE ft_act.Flat_Type_Id='$type_id' AND ft_act.Flt_Asgn_Act_Status='1' AND act.Act_Status='1'";
                        $act_query_run=mysqli_query($connection,$act_query);
                        if(mysqli_num_rows($act_query_run)>0){
                            while($row_asgn=mysqli_fetch_assoc($act_query_run)){
                                $act_id_=$row_asgn['Act_Id'];
                                $act_cat_id=$row_asgn['Act_Cat_Id'];
                                $act_name=$row_asgn['Act_Code'].' '.$row_asgn['Act_Name'];
                                //CHECK IF ACTIVITY ALREADY ASSIGNED
                                $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Act_Id='$act_id_' AND Asgd_Act_Status=1";
                                $q_search_run=mysqli_query($connection,$q_search);
                                if(mysqli_num_rows($q_search_run)>0){
                                    $message .='already exists: flat '.$flt_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                    $session .='warning';
                                }
                                else{
                                    $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ('$flt_id','$act_id_',1,'$act_cat_id',0)";
                                    $q_insert_run=mysqli_query($connection,$q_insert);
                                    if($q_insert_run){
                                        //inserted
                                    }
                                    else{
                                        $message .='error insert activity on: flat '.$flt_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                        $session .='error';
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $message .="Error assigning flat ".$flat_name.'<br>';
                        $session .='warning';
                    }
                }
            }
        }
        else{
            $status='info';
            $message='All Flat/Villa are Assigned';
        }
    }
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
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: p_type_standard.php?type_id='.$type_id);
}
if(isset($_POST['add_act'])){
    $type_id=$_POST['type_id'];
    $prj_type=$_POST['prj_type'];
    //INSERT ACTIVITY flat_type_asgn_act
    if($_POST['act_id']){
        if(in_array("all",$_POST['act_id'])){
            $q_activities="SELECT * FROM activity WHERE Act_Category='$prj_type' AND Act_Status=1";
        }
        else{
            $act_ids=implode("', '", $_POST['act_id']);
            $q_activities="SELECT * FROM activity WHERE Act_Id IN ('$act_ids')";
        }
        // echo $q_activities;
        $q_activities_run=mysqli_query($connection,$q_activities);
        if(mysqli_num_rows($q_activities_run)>0){
            while($row_c=mysqli_fetch_assoc($q_activities_run)){
                $act_id = $row_c['Act_Id'];
                $act_name=$row_c['Act_Code'].' '.$row_c['Act_Name'];
                //check if activity is already assigned to type
                $q_chk_act="SELECT * FROM flat_type_asgn_act WHERE Act_Id='$act_id' AND Flt_Asgn_Act_Status='1' AND Flat_Type_Id='$type_id'";
                $q_chk_act_run=mysqli_query($connection,$q_chk_act);
                if(mysqli_num_rows($q_chk_act_run)>0){
                    $message .="Activity: ".$act_name." already assigned <br>";
                    $session .='warning';
                }
                else{
                    $insert_asgn_act="INSERT INTO flat_type_asgn_act (Flt_Asgn_Act_Status, Act_Id, Flat_Type_Id ) VALUES ('1','$act_id','$type_id')";
                    $insert_asgn_act_run=mysqli_query($connection,$insert_asgn_act);
                    if($insert_asgn_act_run){
                        //ASSIGN to flats
                        //select the flats per type
                        $q_flats="SELECT * FROM flat_asgn_to_type as flt_asgn
                            LEFT JOIN flat as flt on flt.Flat_Id=flt_asgn.Flat_Id
                            WHERE flt_asgn.Flat_Type_Id='$type_id' AND flt_asgn.Flat_Asgd_Status=1 AND flt.Flat_Status=1";
                        $q_flats_run=mysqli_query($connection,$q_flats);
                        if(mysqli_num_rows($q_flats_run)>0){
                            while($row_fa=mysqli_fetch_assoc($q_flats_run)){
                                $flat_id=$row_fa['Flat_Id'];
                                $flat_name=$row_fa['Flat_Code'].' '.$row_fa['Flat_Name'];
                                // select activities assigned to flat type
                                $act_query="SELECT * FROM flat_type_asgn_act as ft_act
                                            LEFT JOIN activity as act on act.Act_Id=ft_act.Act_Id
                                            WHERE ft_act.Flat_Type_Id='$type_id' AND ft_act.Flt_Asgn_Act_Status='1' AND act.Act_Status='1'";
                                $act_query_run=mysqli_query($connection,$act_query);
                                if(mysqli_num_rows($act_query_run)>0){
                                    while($row_asgn=mysqli_fetch_assoc($act_query_run)){
                                        $act_id_=$row_asgn['Act_Id'];
                                        $act_cat_id=$row_asgn['Act_Cat_Id'];
                                        $act_name=$row_asgn['Act_Code'].' '.$row_asgn['Act_Name'];
                                        //CHECK IF ACTIVITY ALREADY ASSIGNED
                                        $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flat_id' AND Act_Id='$act_id_' AND Asgd_Act_Status=1";
                                        $q_search_run=mysqli_query($connection,$q_search);
                                        if(mysqli_num_rows($q_search_run)>0){
                                            $message .='already exists: flat '.$flat_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                            $session .='warning';
                                        }
                                        else{
                                            $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ('$flat_id','$act_id_',1,'$act_cat_id',0)";
                                            $q_insert_run=mysqli_query($connection,$q_insert);
                                            if($q_insert_run){
                                                //inserted
                                            }
                                            else{
                                                $message .='error insert activity on: flat '.$flt_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                                $session .='error';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $message .="Error assigning activity ".$act_name.'<br>';
                        $session .='warning';
                    } 
                }
            }
        }
    }
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
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: p_type_standard.php?type_id='.$type_id);
}
//delete villa assigned to type
if(isset($_POST['delFltAsgn'])){
    $flt_id=$_POST['flt_id'];
    $type_id=$_POST['ftype_id'];
    $fa_id=$_POST['fa_id'];
    //update assigned activities to 0
    $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id='$flt_id'";
    $del_asgn_run=mysqli_query($connection,$del_asgn);
    if($del_asgn_run){
        $q_del="UPDATE flat_asgn_to_type SET Flat_Asgd_Status=0 WHERE Flat_Assigned_Id='$fa_id'";
        $q_del_run=mysqli_query($connection,$q_del);
        if($q_del_run){
            $_SESSION['status'] = "Flat Removed";
            $_SESSION['status_code'] = "success";
            header('Location: p_type_standard.php?type_id='.$type_id);
        }
        else{
            $_SESSION['status'] = "Error Deleting";
            $_SESSION['status_code'] = "error";
            header('Location: p_type_standard.php?type_id='.$type_id);
        }
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_type_standard.php?type_id='.$type_id);
    }
}
if(isset($_POST['type_id'])){
    $flt_id=$_POST['flt_id'];
    $type_id=$_POST['type_id'];
    $fa_id=$_POST['fa_id'];
    // check if there are daily entry record
    $q_asgn="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Asgd_Act_Status=1";
    $q_asgn_run=mysqli_query($connection,$q_asgn);
    if(mysqli_num_rows($q_asgn_run)>0){
        while($row_asgn=mysqli_fetch_assoc($q_asgn_run)){
            $asgn_id_arr[]=$row_asgn['Asgd_Act_Id'];
        }
        $asgn_ids=implode("',' ",$asgn_id_arr);
        $q_de="SELECT * FROM daily_entry WHERE Asgd_Act_Id IN ('$asgn_ids') AND DE_Status=1";
        $q_de_run=mysqli_query($connection,$q_de);
        $de_rows=mysqli_num_rows($q_de_run);
        if($de_rows>0){
            echo $de_rows;
        }
    }
    else{
        $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id='$flt_id'";
        $del_asgn_run=mysqli_query($connection,$del_asgn);
        if($del_asgn_run){
            $q_del="UPDATE flat_asgn_to_type SET Flat_Asgd_Status=0 WHERE Flat_Assigned_Id='$fa_id'";
            $q_del_run=mysqli_query($connection,$q_del);
            if($q_del_run){
                echo 'success';
            }
            else{
                echo 'error';
            }
        }
        else{
            echo 'error';
        }
    }
}
//del act chk records
if(isset($_POST['ttype_id'])){
    $act_id=$_POST['act_id'];
    $type_id=$_POST['ttype_id'];
    $act_asgn_id=$_POST['act_asgn_id'];
    // select all flats in the type
    $q_flats="SELECT * FROM flat_asgn_to_type WHERE Flat_Type_Id='$type_id' AND Flat_Asgd_Status=1";
    $q_flats_run=mysqli_query($connection,$q_flats);
    if(mysqli_num_rows($q_flats_run)>0){
        while($row_f=mysqli_fetch_assoc($q_flats_run)){
            $flt_arr[]=$row_f['Flat_Id'];
        }
        $flt_ids=implode("',' ",$flt_arr);
        $q_asgn="SELECT * FROM assigned_activity as as_act
                LEFT JOIN daily_entry as de on de.Asgd_Act_Id=as_act.Asgd_Act_Id
                 WHERE as_act.Flat_Id IN ('$flt_ids') AND as_act.Asgd_Act_Status=1 AND de.DE_Status=1";
        $q_asgn_run=mysqli_query($connection,$q_asgn);
        $records=mysqli_num_rows($q_asgn_run);
        if($records>0){
            echo $records;
        }
        else{ // proceed to delete
            $q_del_act="UPDATE flat_type_asgn_act SET Flt_Asgn_Act_Status=0 WHERE Flt_Asgn_Act_Id='$act_asgn_id' ";
            $q_del_act_run=mysqli_query($connection,$q_del_act);
            if($q_del_act_run){
                $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id IN ('$flt_ids') AND Act_Id='$act_id'";
                $del_asgn_run=mysqli_query($connection,$del_asgn);
                if($del_asgn_run){
                    echo 'success';
                }
                else{
                    echo 'error';
                } 
            }
            else{
               echo 'error';
            } 
        }
    }
}
//delete activity assigned to type
if(isset($_POST['delAct'])){
    $act_id=$_POST['act_id'];
    $type_id=$_POST['ftype_id'];
    $act_asgn_id=$_POST['act_asgn_id'];
    //set assigned Flt_Asgn_Act_Status =0
    $q_del_act="UPDATE flat_type_asgn_act SET Flt_Asgn_Act_Status=0 WHERE Flt_Asgn_Act_Id='$act_asgn_id' ";
    $q_del_act_run=mysqli_query($connection,$q_del_act);
    if($q_del_act_run){
        // select all flats in the type
        $q_flats="SELECT * FROM flat_asgn_to_type WHERE Flat_Type_Id='$type_id' AND Flat_Asgd_Status=1";
        $q_flats_run=mysqli_query($connection,$q_flats);
        if(mysqli_num_rows($q_flats_run)>0){
            while($row_f=mysqli_fetch_assoc($q_flats_run)){
                $flt_arr[]=$row_f['Flat_Id'];
            }
            $flt_ids=implode("',' ",$flt_arr);
            $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id IN ('$flt_ids') AND Act_Id='$act_id'";
            $del_asgn_run=mysqli_query($connection,$del_asgn);
            if($del_asgn_run){
                $_SESSION['status'] = "Activity Unassigned";
                $_SESSION['status_code'] = "success";
                header('Location: p_type_standard.php?type_id='.$type_id);
            }
            else{
                $_SESSION['status'] = "Error Deleting";
                $_SESSION['status_code'] = "error";
                header('Location: p_type_standard.php?type_id='.$type_id);
            } 
        }
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_type_standard.php?type_id='.$type_id);
    } 
}
if(isset($_POST['typeRec'])){
    $type_id=$_POST['typeRec'];
    // select all flats in the type
    $q_flats="SELECT * FROM flat_asgn_to_type WHERE Flat_Type_Id='$type_id' AND Flat_Asgd_Status=1";
    $q_flats_run=mysqli_query($connection,$q_flats);
    if(mysqli_num_rows($q_flats_run)>0){
        while($row_f=mysqli_fetch_assoc($q_flats_run)){
            $flt_arr[]=$row_f['Flat_Id'];
        }
        $flt_ids=implode("',' ",$flt_arr);
        $q_asgn="SELECT * FROM assigned_activity as as_act
                LEFT JOIN daily_entry as de on de.Asgd_Act_Id=as_act.Asgd_Act_Id
                 WHERE as_act.Flat_Id IN ('$flt_ids') AND as_act.Asgd_Act_Status=1 AND de.DE_Status=1";
        $q_asgn_run=mysqli_query($connection,$q_asgn);
        $records=mysqli_num_rows($q_asgn_run);
        if($records>0){
            echo $records;
        }
        else{ // proceed to delete
            $q_del_act="UPDATE flat_type_asgn_act SET Flt_Asgn_Act_Status=0 WHERE Flat_Type_Id ='$type_id' ";
            $q_del_act_run=mysqli_query($connection,$q_del_act);
            if($q_del_act_run){
                $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id IN ('$flt_ids')";
                $del_asgn_run=mysqli_query($connection,$del_asgn);
                if($del_asgn_run){
                    $q_del="UPDATE flat_asgn_to_type SET Flat_Asgd_Status=0 WHERE Flat_Type_Id ='$type_id'";
                    $q_del_run=mysqli_query($connection,$q_del);
                    if($q_del_run){
                        echo 'success';
                    }
                    else{
                        echo 'error';
                    }
                }
                else{
                    echo 'error';
                } 
            }
            else{
               echo 'error';
            } 
        }
    }
    else{
        $q_del_type="UPDATE flat_type SET Flat_Type_Status=0 WHERE Flat_Type_Id='$type_id'";
        $q_del_type_run=mysqli_query($connection,$q_del_type);
        if($q_del_type_run){
            echo 'success';
        }
        else{
            echo 'error';
        } 
    }
}
if(isset($_POST['delType'])){
    $type_id=$_POST['ftype_id'];
    $prj_id=$_POST['prj_id'];
     // select all flats in the type
     $q_flats="SELECT * FROM flat_asgn_to_type WHERE Flat_Type_Id='$type_id' AND Flat_Asgd_Status=1";
     $q_flats_run=mysqli_query($connection,$q_flats);
     if(mysqli_num_rows($q_flats_run)>0){
         while($row_f=mysqli_fetch_assoc($q_flats_run)){
             $flt_arr[]=$row_f['Flat_Id'];
         }
         $flt_ids=implode("',' ",$flt_arr);
    }
    $q_del_act="UPDATE flat_type_asgn_act SET Flt_Asgn_Act_Status=0 WHERE Flat_Type_Id ='$type_id' ";
    $q_del_act_run=mysqli_query($connection,$q_del_act);
    if($q_del_act_run){
        $del_asgn="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Flat_Id IN ('$flt_ids')";
        $del_asgn_run=mysqli_query($connection,$del_asgn);
        if($del_asgn_run){
            $q_del="UPDATE flat_asgn_to_type SET Flat_Asgd_Status=0 WHERE Flat_Type_Id ='$type_id'";
            $q_del_run=mysqli_query($connection,$q_del);
            if($q_del_run){
                $q_del_type="UPDATE flat_type SET Flat_Type_Status=0 WHERE Flat_Type_Id='$type_id'";
                $q_del_type_run=mysqli_query($connection,$q_del_type);
                if($q_del_type_run){
                    $_SESSION['status'] = "Type Deleted";
                    $_SESSION['status_code'] = "success";
                    header('Location: p_flt_type.php?prj_id='.$prj_id);
                }
                else{
                    $_SESSION['status'] = "Error Deleting";
                    $_SESSION['status_code'] = "error";
                    header('Location: p_flt_type.php?prj_id='.$prj_id);
                }
            }
            else{
                $_SESSION['status'] = "Error Deleting";
                $_SESSION['status_code'] = "error";
                header('Location: p_flt_type.php?prj_id='.$prj_id);
            }
        }
        else{
            $_SESSION['status'] = "Error Deleting";
            $_SESSION['status_code'] = "error";
            header('Location: p_flt_type.php?prj_id='.$prj_id);
        } 
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_flt_type.php?prj_id='.$prj_id);
    } 
}
if(isset($_POST['updateBgt'])){
    $type_id=$_POST['ftype_id'];
    $data = array(
        'act_asgn_id_u' => $_POST['act_asgn_id_u'],
        'act_bgt' => $_POST['act_bgt']
   ); 
   $count = count($_POST['act_asgn_id_u']);
    for ($i=0; $i < $count; $i++){
        $q_update="UPDATE flat_type_asgn_act SET Flat_Bgt_Manpower='{$_POST['act_bgt'][$i]}' WHERE Flt_Asgn_Act_Id='{$_POST['act_asgn_id_u'][$i]}'";
        $q_update_run=mysqli_query($connection,$q_update);
        if($q_update_run){
        }
        else{
            $err_ctn++;
        }
    }
    if($err_ctn==0){
        $_SESSION['status'] = "Updated";
        $_SESSION['status_code'] = "success";
        header('Location: p_type_standard.php?type_id='.$type_id);
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: p_type_standard.php?type_id='.$type_id);
    }
}
if(isset($_POST['refresh'])){
    $type_id=$_POST['ftype_id'];
    $flt_id=$_POST['flt_id'];
   //INSERT ACTIVITY flat_type_asgn_act
    $q_activities="SELECT * FROM flat_type_asgn_act WHERE Flat_Type_Id ='$type_id' AND Flat_Asgd_Status =1";
    $q_activities_run=mysqli_query($connection,$q_activities);
    if(mysqli_num_rows($q_activities_run)>0){
        while($row_c=mysqli_fetch_assoc($q_activities_run)){
            $act_id = $row_c['Act_Id'];
            $insert_asgn_act="INSERT INTO flat_type_asgn_act (Flt_Asgn_Act_Status, Act_Id, Flat_Type_Id ) VALUES ('1','$act_id','$type_id')";
            $insert_asgn_act_run=mysqli_query($connection,$insert_asgn_act);
            if($insert_asgn_act_run){
                //select the flats per type
                $q_flats="SELECT * FROM flat_asgn_to_type as flt_asgn
                    LEFT JOIN flat as flt on flt.Flat_Id=flt_asgn.Flat_Id
                    WHERE flt_asgn.Flat_Type_Id='$type_id' AND flt_asgn.Flat_Asgd_Status=1 AND flt.Flat_Status=1";
                $q_flats_run=mysqli_query($connection,$q_flats);
                if(mysqli_num_rows($q_flats_run)>0){
                    while($row_fa=mysqli_fetch_assoc($q_flats_run)){
                        $flat_id=$row_fa['Flat_Id'];
                        $flat_name=$row_fa['Flat_Code'].' '.$row_fa['Flat_Name'];
                        // select activities assigned to flat type
                        $act_query="SELECT * FROM flat_type_asgn_act as ft_act
                                    LEFT JOIN activity as act on act.Act_Id=ft_act.Act_Id
                                    WHERE ft_act.Flat_Type_Id='$type_id' AND ft_act.Flt_Asgn_Act_Status='1' AND act.Act_Status='1'";
                        $act_query_run=mysqli_query($connection,$act_query);
                        if(mysqli_num_rows($act_query_run)>0){
                            while($row_asgn=mysqli_fetch_assoc($act_query_run)){
                                $act_id_=$row_asgn['Act_Id'];
                                $act_cat_id=$row_asgn['Act_Cat_Id'];
                                $act_name=$row_asgn['Act_Code'].' '.$row_asgn['Act_Name'];
                                //CHECK IF ACTIVITY ALREADY ASSIGNED
                                $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flat_id' AND Act_Id='$act_id_' AND Asgd_Act_Status=1";
                                $q_search_run=mysqli_query($connection,$q_search);
                                if(mysqli_num_rows($q_search_run)>0){
                                    $message .='already exists: flat '.$flat_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                    $session .='warning';
                                }
                                else{
                                    $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ('$flat_id','$act_id_',1,'$act_cat_id',0)";
                                    $q_insert_run=mysqli_query($connection,$q_insert);
                                    if($q_insert_run){
                                        //inserted
                                    }
                                    else{
                                        $message .='error insert activity on: flat '.$flt_id.' '.$flat_name.' activity: '.$act_id.' '.$act_name.'<br>';
                                        $session .='error';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
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
    $_SESSION['status']=" ";
    $_SESSION['message']=$message;
    $_SESSION['status_code']=$status;
    header('Location: p_type_standard.php?type_id='.$type_id);
}