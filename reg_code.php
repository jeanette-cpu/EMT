<?php
include('security.php');
include('email.php');
if(isset($_POST['submit'])){
    $trn = $_POST['trn'];
    $comp_name=$_POST['comp_name'];
    //check if TRN exists
    if($trn!=''){
        $q_trn_chk="SELECT * FROM company where Comp_TRN='$trn'";
        $q_trn_chk_run=mysqli_query($connection,$q_trn_chk);
        if(mysqli_num_rows($q_trn_chk_run)>0){
            $_SESSION['status'] = "TRN already exists";
            $_SESSION['status_code'] = "error";
            header('Location: register.php');
        }
    }
    //check if company name exists
    $q_name_chk="SELECT * FROM company where Comp_Name='$comp_name'";
    $q_name_chk_run=mysqli_query($connection,$q_name_chk);
    if(mysqli_num_rows($q_name_chk_run)>0){
        $_SESSION['status'] = "Company Name already exists";
        $_SESSION['status_code'] = "error";
        header('Location: login.php');
    }
    else{
        //SAVE company details
        if(isset($_POST['scopeB']) !=""){
            $data = array( 
                'scopeB' => $_POST['scopeB']
                ); 
            $count=count($_POST['scopeB']);
            for($i=0;$i<$count;$i++){
                $arr[]=$_POST['scopeB'][$i];
                $scopeB= implode(", ", $arr);
            }
        }
    }
    $bTtype= $_POST['bType'];
    $website = $_POST['website'];
    $cName = $_POST['cName'];
    $cPos = $_POST['cPos'];
    $cMobile = $_POST['cMobile'];
    $cLand = $_POST['cLand'];
    $cMail = $_POST['cMail']; ///
    $mName = $_POST['mName'];
    $mMobile = $_POST['mMobile'];
    $mLand = $_POST['mLand'];
    $mMail = $_POST['mMail']; ///
    $emirateInTL = $_POST['emirateInTL'];
    $s1_name=$_POST['s1_name'];
    $s2_name=$_POST['s2_name'];
    $s3_name=$_POST['s3_name'];
    $license_exp=$_POST['date_exp'];

    $username=$_POST['username'];
    $pw=$_POST['password']; //username pw
    $q_user="INSERT INTO users (USERNAME,USER_PASSWORD,USERTYPE,Dept_Id,USER_STATUS) VALUES ('$username','$pw','company',NULL,0)";
    if($connection->query($q_user)===TRUE)
    {          
        $user_id = $connection->insert_id; 
        $q_company="INSERT INTO company (Comp_Name,Comp_Type,Comp_Scope_Auth,Company_Website,Comp_Contact_Person,Comp_Contact_Position,Comp_Contact_Mobile,Comp_Contact_Landline,Comp_Contact_Email,Comp_Manager_Name,Comp_Manager_Mobile,Comp_Manager_Landline,Comp_Manager_Email,Comp_TRN,Comp_Emirate_TRL,Comp_Sig_Name1,Comp_Sig_Name2 ,Comp_Sig_Name3 ,Comp_Reg_End_Date ,User_Id,Comp_Status,Comp_Approval) VALUES ('$comp_name','$bTtype','$scopeB','$website','$cName','$cPos','$cMobile','$cLand','$cMail','$mName','$mMobile','$mLand','$mMail','$trn','$emirateInTL','$s1_name','$s2_name','$s3_name','$license_exp','$user_id',1,'2')";
        if($connection->query($q_company)===TRUE){          
            $id = $connection->insert_id; 
            //check if isset company profile/trn file
            $targetfolderTRN = "uploads/TRN".$id.".pdf";
            $targetfolderCProf = "uploads/cProf".$id.".pdf"; $ok=1;
            $file_type=$_FILES['file']['type'];
            $comp_prof_type=$_FILES['comp_profile']['type'];
            if(isset($_POST['group']) !=""){
                $data = array( 
                    'group' => $_POST['group']
                    ); 
                $countG=count($_POST['group']);
                for($i=0;$i<$countG;$i++){
                    $grp_id=$_POST['group'][$i];
                    $add_grp="INSERT INTO email (User_Id,Email_Status,Email_Grp_Id) VALUES ($user_id,1,$grp_id)";
                    $add_grp_run=mysqli_query($connection,$add_grp);
                }
            }
            if($_FILES['file']['size'] == 0){// trn file is optional, no action if no upload
            }
            else{
                if($file_type=="application/pdf"){
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolderTRN)){
                        $compStamp_ext=pathinfo($_FILES['compStamp']['name'], PATHINFO_EXTENSION);
                        $compStamp=$id.".".$compStamp_ext; // cstamp1.png = cstamp+comp_id+.png
                        $targetfolder_comp_stamp = "uploads/comp_stamp/cstamp".$compStamp;
                        $ok=1;
                        if($_FILES['compStamp']['size'] != 0){//  COMPANY STAMP
                            if(move_uploaded_file($_FILES['compStamp']['tmp_name'], $targetfolder_comp_stamp)){
                                $targetfolder_sig = "uploads/signitures/".$id."_csig";
                                $s1_1=pathinfo($_FILES['s1_1']['name'], PATHINFO_EXTENSION); //file extentions
                                $s1_2=pathinfo($_FILES['s1_2']['name'], PATHINFO_EXTENSION);
                                $s1_3=pathinfo($_FILES['s1_3']['name'], PATHINFO_EXTENSION);
                                $s2_1=pathinfo($_FILES['s2_1']['name'], PATHINFO_EXTENSION);
                                $s2_2=pathinfo($_FILES['s2_2']['name'], PATHINFO_EXTENSION);
                                $s2_3=pathinfo($_FILES['s2_3']['name'], PATHINFO_EXTENSION);
                                $s3_1=pathinfo($_FILES['s3_1']['name'], PATHINFO_EXTENSION);
                                $s3_2=pathinfo($_FILES['s3_2']['name'], PATHINFO_EXTENSION);
                                $s3_3=pathinfo($_FILES['s3_3']['name'], PATHINFO_EXTENSION);
                                //1   1_csig1-1.png = csig+1-1+comp_id+file ext;
                                $fold1_1=$targetfolder_sig.'1-1.'.$s1_1;  
                                $fold1_2=$targetfolder_sig.'1-2.'.$s1_2;
                                $fold1_3=$targetfolder_sig.'1-3.'.$s1_3; 
                                //2
                                $fold2_1=$targetfolder_sig.'2-1.'.$s2_1;   
                                $fold2_2=$targetfolder_sig.'2-2.'.$s2_2;  
                                $fold2_3=$targetfolder_sig.'2-3.'.$s2_3;  
                                //3
                                $fold3_1=$targetfolder_sig.'3-1.'.$s3_1; 
                                $fold3_2=$targetfolder_sig.'3-2.'.$s3_2; 
                                $fold3_3=$targetfolder_sig.'3-3.'.$s3_3;
                    
                                if(move_uploaded_file($_FILES['s1_1']['tmp_name'], $fold1_1) && move_uploaded_file($_FILES['s1_2']['tmp_name'], $fold1_2) && move_uploaded_file($_FILES['s1_3']['tmp_name'], $fold1_3)){
                                    if(move_uploaded_file($_FILES['s2_1']['tmp_name'], $fold2_1) && move_uploaded_file($_FILES['s2_2']['tmp_name'], $fold2_2) && move_uploaded_file($_FILES['s2_3']['tmp_name'], $fold2_3)){
                                        if(move_uploaded_file($_FILES['s3_1']['tmp_name'], $fold3_1) && move_uploaded_file($_FILES['s3_2']['tmp_name'], $fold3_2) && move_uploaded_file($_FILES['s3_3']['tmp_name'], $fold3_3)){
                                            $update_comp="UPDATE company SET s1_1='$s1_1', s1_2='$s1_2', s1_3='$s1_3', s2_1='$s2_1', s2_2='$s2_2', s2_3='$s2_3', s3_1='$s3_1', s3_2='$s3_2', s3_3='$s3_3', Comp_Stamp='$compStamp_ext' WHERE Comp_Id='$id'";
                                            $update_comp_run=mysqli_query($connection,$update_comp);
                                            if($update_comp_run){
                                                if($_FILES['comp_profile']['size'] == 0){// company profil file is optional, no action if no upload
                                                }
                                                else{
                                                    if($comp_prof_type=="application/pdf"){
                                                        if(move_uploaded_file($_FILES['comp_profile']['tmp_name'], $targetfolderCProf)){
                                                        }
                                                    }
                                                    else{
                                                        //remove last saved company , and user details
                                                        $q_del_user="DELETE * FROM users WHERE USER_ID='$user_id'";
                                                        $q_del_user_run=mysqli_query($connection,$q_del_user);
                                                        $q_del_comp="DELETE * FROM company WHERE Comp_Id='$id'";
                                                        $q_del_comp_run=mysqli_query($connection,$q_del_comp);
                                                        $_SESSION['status'] = "Please use PDF format";
                                                        $_SESSION['status_code'] = "error";
                                                        header('Location: register.php');
                                                    }
                                                }
                                                if($bTtype=='subcon' || $bTtype=='laborSupply' || $bTtype=='agency')// save to service table
                                                {
                                                    $data_serv = array( 
                                                        'serv_desc' => $_POST['serv_desc'],
                                                        'serv_unit' => $_POST['serv_unit'],
                                                        'serv_rate' => $_POST['serv_rate'],
                                                        'sdept_id' => $_POST['sdept_id'],
                                                        );
                                                    $countS= count($_POST['serv_desc']);
                                                    for ($k=0; $k < $countS; $k++) { 
                                                        $sql ="INSERT INTO service (Serve_Desc,Serve_Unit,Serve_Rate,Dept_Id,Comp_Id,Serve_Status) VALUES ('{$_POST['serv_desc'][$k]}','{$_POST['serv_unit'][$k]}','{$_POST['serv_rate'][$k]}','{$_POST['sdept_id'][$k]}','$id','1')";
                                                        // echo $sql;
                                                        $connection->query($sql);
                                                    }
                                                }//save to products
                                                else{
                                                    $data_prod = array( 
                                                        'prod_desc' => $_POST['prod_desc'],
                                                        'prod_Brand' => $_POST['prod_Brand'],
                                                        'country' => $_POST['country'],
                                                        'dept_id' => $_POST['dept_id'],
                                                        );
                                                    $countP= count($_POST['prod_desc']);
                                                    for ($j=0; $j < $countP; $j++) { 
                                                        $sql ="INSERT INTO product (Prod_Desc,Prod_Brand,Prod_Country,Prod_Status,Dept_Id,Comp_Id) VALUES ('{$_POST['prod_desc'][$j]}','{$_POST['prod_Brand'][$j]}','{$_POST['country'][$j]}','1','{$_POST['dept_id'][$j]}','$id')";
                                                        $connection->query($sql);
                                                        // echo $sql;
                                                    }
                                                }
                                                if($sql){
                                                    //EMAIL FOR PURCHASE DEPT
                                                    // NOTIFICATIONS // select all purchase dept users
                                                    $q_not="SELECT * FROM users WHERE USERTYPE='purchase' AND USER_STATUS=1";
                                                    $q_not_run=mysqli_query($connection,$q_not);
                                                    if(mysqli_num_rows($q_not_run)>0){
                                                        $subject="New Company Register";
                                                        while($row_u=mysqli_fetch_assoc($q_not_run)){
                                                            $u_id=$row_u['USER_ID'];
                                                            $p_username=$row_u['USERNAME'];
                                                            $q_new_reg_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Comp_Id) VALUES('$u_id','new_reg',1,'$id')";
                                                            $q_new_reg_notif_run=mysqli_query($connection,$q_new_reg_notif);
                                                            $to[]=$row_u['USER_EMAIL'];
                                                            $email_add=$row_u['USER_EMAIL'];
                                                            if($email_add){
                                                                $body ="Dear $p_username,<br><br>
                                                                $comp_name, recently registered. Please evaluate their profile for approval. Thank you. <br><br>
                                                                Sincerely,<br>
                                                                EMT Registration System<br><br><br>
                                                                This is a system-generated email. Please do not reply.";
                                                                sendmail($to,$subject,$body);
                                                            }
                                                        }
                                                    }
                                                    //EMAIL FOR COMPANY - new register
                                                    $subject="EMT Portal - User Registration";
                                                    $body="<b>Hello $comp_name,</b></br><br>
                                                            Thank you for registering!</br>
                                                            Please wait for approval from the purchase department.
                                                            <br><br><br><br>
                                                            Sincerely,<br>
                                                            EMT Registration System<br><br>
                                                            This is a system-generated email. Please do not reply.";
                                                    if($username){
                                                        $subject="EMT Portal - User Registration";
                                                        $body="<b>Hello $comp_name,</b><br><br><br>Thank you for registering!</br>Please wait for approval from the purchase department.<br><br><br>Sincerely,<br>EMT Registration System<br><br>This is a system-generated email. Please do not reply.";
                                                        $to[]=$username;
                                                        if(sendmail($to,$subject,$body)){
                                                            $_SESSION['status'] = "Your registration is complete! Please wait for Company Approval";
                                                            $_SESSION['status_code'] = "success";
                                                            header('Location: login.php');
                                                        }
                                                        else{// delete prev details
                                                            deleteComp($user_id,$id);
                                                        }
                                                    }
                                                }
                                                else{// delete prev details
                                                    deleteComp($user_id,$id);
                                                }
                                            }
                                            else{
                                                deleteComp($user_id,$id);
                                            }
                                        }
                                        else{// error 3rd group
                                            deleteComp($user_id,$id);
                                        }
                                    }
                                    else{ // error 2nd grp of sig
                                        deleteComp($user_id,$id);
                                    }
                                }
                                else{// error 1st group of signitures
                                    // delete prev details
                                    deleteComp($user_id,$id);
                                }
                            }
                            else{
                                deleteComp($user_id,$id);
                            }
                        } 
                    }
                }
                else{//remove last saved company , and user details
                    deleteComp($user_id,$id);
                }
            }
        }
        else{//error on saving company profile, delete last saved user_id
            $q_del_user="DELETE * FROM users WHERE USER_ID='$user_id'";
            $q_del_user_run=mysqli_query($connection,$q_del_user);
            $_SESSION['status'] = "Error Saving Company Details";
            $_SESSION['status_code'] = "error";
            header('Location: register.php');
        }
    }
    else{
        // error on saving user, name pw
        $_SESSION['status'] = "Error Saving Company Details";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
    }
}
if(isset($_POST['login_btn']))
{
    // declaring variables
    $username = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);

    $email_query = "SELECT * FROM users WHERE USERNAME='$username'";
    $email_query_run = mysqli_query($connection, $email_query);
    if(mysqli_num_rows($email_query_run) > 0){
        $_SESSION['status'] = "Username Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
    }
    else
    {
        if($username =="" or $password =="" ){
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: register.php');
        }
        else{
            // confirming the password
            if($password === $cpassword){
              header('Location: reg_form.php?username='.$username.'&password='.$password);
            }
            else{
                $_SESSION['status'] = "Passord does not match";
                $_SESSION['status_code'] = "warning";
                header('Location: register.php');
            }
        }
    }
}
function deleteComp($user_id, $id){
    include('security.php');
    $q_del_user="DELETE * FROM users WHERE USER_ID='$user_id'";
    $q_del_user_run=mysqli_query($connection,$q_del_user);
    $q_del_comp="DELETE * FROM company WHERE Comp_Id='$id'";
    $q_del_comp_run=mysqli_query($connection,$q_del_comp);
    if($q_del_user_run && $q_del_comp_run){
        $_SESSION['status'] = "Error Saving Company Details";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
    }
    else{
        $_SESSION['status'] = "Error Saving Company Details";
        $_SESSION['status_code'] = "error";
        header('Location: register.php');
    }
}
?>