<?php
include('../../security.php');
include('../../email.php');
if(isset($_POST['update'])){
    $comp_id=$_POST['comp_id'];
    $comp_name=$_POST['comp_name'];
    $type=$_POST['bType']; 
    $website=$_POST['Company_Website'];
    $CP_name=$_POST['cName'];
    $CP_position=$_POST['cPos'];
    $CP_mobile=$_POST['cMobile'];
    $CP_landline=$_POST['cLand'];
    $CP_email=$_POST['cMail'];
    $TRN=$_POST['Comp_TRN'];
    $x_date=$_POST['x_date'];
    $emirateTL=$_POST['emirateInTL'];///
    $mg_name=$_POST['mName'];
    $mg_mobile=$_POST['mMobile'];
    $mg_landline=$_POST['mLand'];
    $mg_email=$_POST['mMail'];
    $sig1=$_POST['s1_name'];
    $sig2=$_POST['s2_name'];
    $sig3=$_POST['s3_name'];

    $targetfolder = $_SERVER['DOCUMENT_ROOT']."/EMT/uploads/";
    $ok=1;
    $file_type=$_FILES['file']['type'];
    $comp_prof_type=$_FILES['profile']['type'];
    // select previous file type
    $comp="SELECT * FROM company WHERE Comp_Id='$comp_id'";
    $comp_run=mysqli_query($connection,$comp);
    $remove_s='/EMT/uploads/signitures/';
    if(mysqli_num_rows($comp_run)>0){
        while($row_cd=mysqli_fetch_assoc($comp_run)){
            $stamp_ext=$row_cd['Comp_Stamp'];
            $s1_1_ext=$row_cd['s1_1'];
            $s1_2_ext=$row_cd['s1_2'];
            $s1_3_ext=$row_cd['s1_3'];
            $s2_1_ext=$row_cd['s2_1'];
            $s2_2_ext=$row_cd['s2_2'];
            $s2_3_ext=$row_cd['s2_3'];
            $s3_1_ext=$row_cd['s3_1'];
            $s3_2_ext=$row_cd['s3_2'];
            $s3_3_ext=$row_cd['s3_3'];
        }
    }
    if ($_FILES['file']['size'] != 0){
        // echo $file_type;
        if ($file_type=="application/pdf" ) { //check if file is pdf.
            $remove='/EMT/uploads/TRN'.$comp_id.'.pdf'; //remove old file
            unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
            $targetfolderTRN = $targetfolder."TRN".$comp_id.".pdf";
            if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolderTRN)){
            }
        }
        else{
            $_SESSION['status'] = "Please upload a PDF file";
            $_SESSION['status_code'] = "error";
            header('Location: profile.php');
        }
    }
    if($_FILES['profile']['size'] != 0){
        if ($comp_prof_type=="application/pdf" ) {//check if file is pdf.
            //find previous file type.
            $remove='/EMT/uploads/cProf'.$comp_id.'.pdf';//remove old file
            unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
            $targetfolderCProf = $targetfolder."cProf".$comp_id.".pdf";
            if(move_uploaded_file($_FILES['profile']['tmp_name'], $targetfolderCProf)){
            }
        }
        else{
            $_SESSION['status'] = "Please upload a PDF file";
            $_SESSION['status_code'] = "error";
            header('Location: profile.php');
        }
    }
    // ////////// FILE ATTACHMENT SIG N STAMP 
    if($_FILES['compStamp']['size'] != 0){
        $remove=$comp_id.$stamp_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $compStamp_ext=pathinfo($_FILES['compStamp']['name'], PATHINFO_EXTENSION);
        $targetfolder_comp_stamp = $targetfolder."comp_stamp/cstamp".$comp_id.'.'.$compStamp_ext;
        if(move_uploaded_file($_FILES['compStamp']['tmp_name'], $targetfolder_comp_stamp)){
        }
        $updat_stamp="UPDATE company SET Comp_Stamp='$compStamp_ext' WHERE Comp_Id='$comp_id'";
        $updat_stamp_run=mysqli_query($connection,$updat_stamp);
    }
    if($_FILES['s1_1']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig1-1'.$s1_1_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s1_1=pathinfo($_FILES['s1_1']['name'], PATHINFO_EXTENSION);
        $targetfolder_s1_1 = $targetfolder."signitures/".$comp_id.'_csig1-1.'.$ext_s1_1;
        if(move_uploaded_file($_FILES['s1_1']['tmp_name'], $targetfolder_s1_1)){
        }
        $updat_sig="UPDATE company SET s1_1='$ext_s1_1' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s1_2']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig1-2'.$s1_2_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s1_2=pathinfo($_FILES['s1_2']['name'], PATHINFO_EXTENSION);
        $targetfolder_s1_2 = $targetfolder."signitures/".$comp_id.'_csig1-2.'.$ext_s1_2;
        if(move_uploaded_file($_FILES['s1_2']['tmp_name'], $targetfolder_s1_2)){
        }
        $updat_sig="UPDATE company SET s1_2='$ext_s1_2' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s1_3']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig1-3'.$s1_3_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s1_3=pathinfo($_FILES['s1_3']['name'], PATHINFO_EXTENSION);
        $targetfolder_s1_3 = $targetfolder."signitures/".$comp_id.'_csig1-3.'.$ext_s1_3;
        if(move_uploaded_file($_FILES['s1_3']['tmp_name'], $targetfolder_s1_3)){
        }
        $updat_sig="UPDATE company SET s1_3='$ext_s1_3' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s2_1']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig2-1'.$s2_1_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s2_1=pathinfo($_FILES['s2_1']['name'], PATHINFO_EXTENSION);
        $targetfolder_s2_1 = $targetfolder."signitures/".$comp_id.'_csig2-1.'.$ext_s2_1;
        if(move_uploaded_file($_FILES['s2_1']['tmp_name'], $targetfolder_s2_1)){
        }
        $updat_sig="UPDATE company SET s2_1='$ext_s2_1' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s2_2']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig2-2'.$s2_2_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s2_2=pathinfo($_FILES['s2_2']['name'], PATHINFO_EXTENSION);
        $targetfolder_s2_2 = $targetfolder."signitures/".$comp_id.'_csig2-2.'.$ext_s2_2;
        if(move_uploaded_file($_FILES['s2_2']['tmp_name'], $targetfolder_s2_2)){
        }
        $updat_sig="UPDATE company SET s2_2='$ext_s2_2' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s2_3']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig2-3'.$s2_3_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s2_3=pathinfo($_FILES['s2_3']['name'], PATHINFO_EXTENSION);
        $targetfolder_s2_3 = $targetfolder."signitures/".$comp_id.'_csig2-3.'.$ext_s2_3;
        if(move_uploaded_file($_FILES['s2_3']['tmp_name'], $targetfolder_s2_3)){
        }
        $updat_sig="UPDATE company SET s2_3='$ext_s2_3' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s3_1']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig3-1'.$s3_1_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s3_1=pathinfo($_FILES['s3_1']['name'], PATHINFO_EXTENSION);
        $targetfolder_s3_1 = $targetfolder."signitures/".$comp_id.'_csig3-1.'.$ext_s3_1;
        if(move_uploaded_file($_FILES['s3_1']['tmp_name'], $targetfolder_s3_1)){
        }
        $updat_sig="UPDATE company SET s3_1='$ext_s3_1' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s3_2']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig3-2'.$s3_2_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s3_2=pathinfo($_FILES['s3_2']['name'], PATHINFO_EXTENSION);
        $targetfolder_s3_2 = $targetfolder."signitures/".$comp_id.'_csig3-2.'.$ext_s3_2;
        if(move_uploaded_file($_FILES['s3_2']['tmp_name'], $targetfolder_s3_2)){
        }
        $updat_sig="UPDATE company SET s3_2='$ext_s3_2' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    if($_FILES['s3_3']['size'] != 0){
        $remove=$remove_s.$comp_id.'_csig3-3'.$s3_3_ext;//remove old file
        unlink($_SERVER['DOCUMENT_ROOT'] .$remove);
        $ext_s3_3=pathinfo($_FILES['s3_3']['name'], PATHINFO_EXTENSION);
        $targetfolder_s3_3 = $targetfolder."signitures/".$comp_id.'_csig3-3.'.$ext_s3_3;
        if(move_uploaded_file($_FILES['s3_3']['tmp_name'], $targetfolder_s3_3)){
        }
        $updat_sig="UPDATE company SET s3_3='$ext_s3_3' WHERE Comp_Id='$comp_id'";
        $updat_sig_run=mysqli_query($connection,$updat_sig);
    }
    // //////////
    if(isset($_POST['scopeB']) !="")
    {
        $data = array( 
            'scopeB' => $_POST['scopeB']
            ); 
        $count=count($_POST['scopeB']);
        for($i=0;$i<$count;$i++)
        {
            $arr[]=$_POST['scopeB'][$i];
            $scopeB= implode(", ", $arr);
        }
    }
    $compUpdate="UPDATE company SET Comp_Name='$comp_name', Comp_Type='$type', Comp_Scope_Auth='$scopeB', Company_Website='$website', Comp_Contact_Person='$CP_name', Comp_Contact_Position='$CP_position', Comp_Contact_Mobile='$CP_mobile', Comp_Contact_Landline='$CP_landline', Comp_Contact_Email='$CP_email', Comp_Manager_Name='$mg_name', Comp_Manager_Mobile='$mg_mobile', Comp_Manager_Landline='$mg_landline', Comp_Manager_Email='$mg_email', Comp_TRN='$TRN', Comp_Emirate_TRL='$emirateTL',Comp_Sig_Name1='$sig1',Comp_Sig_Name2='$sig2',Comp_Sig_Name3='$sig3',Comp_Reg_End_Date='$x_date'  WHERE Comp_Id='$comp_id'";
    // echo $compUpdate;
    $compUpdate_run=mysqli_query($connection,$compUpdate);
    if($compUpdate_run){
        $_SESSION['status'] = "Company Details Updated";
        $_SESSION['status_code'] = "success";
        header('Location: profile.php');
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: profile.php');
    }
}
if(isset($_POST["addProd"])){
    $id=$_POST['comp_id'];
    $data_prod = array( 
        'prod_desc' => $_POST['prod_desc'],
        'prod_Brand' => $_POST['prod_Brand'],
        'country' => $_POST['country'],
        'dept_id' => $_POST['dept_id'],
        );
    $countP= count($_POST['prod_desc']);
    for ($j=0; $j < $countP; $j++){ 
        //check if the product desc already exists
        $sql ="INSERT INTO product (Prod_Desc,Prod_Brand,Prod_Country,Prod_Status,Dept_Id,Comp_Id) VALUES ('{$_POST['prod_desc'][$j]}','{$_POST['prod_Brand'][$j]}','{$_POST['country'][$j]}','1','{$_POST['dept_id'][$j]}','$id')";
        $connection->query($sql);
        // echo $sql;
        if($sql){
            $_SESSION['status'] = "Products Updated!";
            $_SESSION['status_code'] = "success";
            header('Location: prodServe.php');
        }
        else{
            $_SESSION['status'] = "Error Updating Products";
            $_SESSION['status_code'] = "error";
            header('Location: prodServe.php');
        }
    }
}
if(isset($_POST['addServe'])){
    $id=$_POST['comp_id'];
    $data_serv = array( 
        'serv_desc' => $_POST['serv_desc'],
        'serv_unit' => $_POST['serv_unit'],
        'serv_rate' => $_POST['serv_rate'],
        'sdept_id' => $_POST['sdept_id'],
        );
    $countS= count($_POST['serv_desc']);
    for($k=0; $k < $countS; $k++){ 
        $sql ="INSERT INTO service (Serve_Desc,Serve_Unit,Serve_Rate,Dept_Id,Comp_Id,Serve_Status) VALUES ('{$_POST['serv_desc'][$k]}','{$_POST['serv_unit'][$k]}','{$_POST['serv_rate'][$k]}','{$_POST['sdept_id'][$k]}','$id','1')";
        // echo $sql;
        $connection->query($sql);
        if($sql){
            $_SESSION['status'] = "Products Updated!";
            $_SESSION['status_code'] = "success";
            header('Location: prodServe.php');
        }
        else{
            $_SESSION['status'] = "Error Updating Products";
            $_SESSION['status_code'] = "error";
            header('Location: prodServe.php');
        }
    }
}
if(isset($_POST['delProd'])) 
{
    $prod_Id=$_POST['prodId'];
    $q_delete="UPDATE product SET Prod_Status=0 WHERE Product_Id='$prod_Id'";
    $q_delete_run=mysqli_query($connection,$q_delete);
    if($q_delete_run){
        $_SESSION['status'] = "Product Deleted!";
        $_SESSION['status_code'] = "success";
        header('Location: prodServe.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Product";
        $_SESSION['status_code'] = "error";
        header('Location: prodServe.php');
    }
}
if(isset($_POST['delServe']))
{
    $serve_Id=$_POST['serveId'];
    $q_delete="UPDATE service SET Serve_Status=0 WHERE Service_Id='$serve_Id'";
    $q_delete_run=mysqli_query($connection,$q_delete);
    if($q_delete_run){
        $_SESSION['status'] = "Service Deleted!";
        $_SESSION['status_code'] = "success";
        header('Location: prodServe.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Service";
        $_SESSION['status_code'] = "error";
        header('Location: prodServe.php');
    }
}
if(isset($_POST['editProd'])){
    $prod_id =$_POST['prod_id'];
    $prod_des =$_POST['prod_des'];
    $brand =$_POST['brand'];
    $country =$_POST['country'];
    $dept =$_POST['dept'];
    // $dept_id=$_POST[''];
    $q_update="UPDATE product SET Prod_Desc='$prod_des', Prod_Brand='$brand', Prod_Country='$country', Dept_Id='$dept' WHERE Product_Id='$prod_id'";
    // echo $q_update;
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Products Updated!";
        $_SESSION['status_code'] = "success";
        header('Location: prodServe.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Products";
        $_SESSION['status_code'] = "error";
        header('Location: prodServe.php');
    }
}
if(isset($_POST['editServe'])){
    $serve_id=$_POST['serve_id'];
    $dept = $_POST['dept'];
    $serve_desc = $_POST['serv_desc'];
    $unit = $_POST['unit'];
    $rate = $_POST['rate'];
    $q_update="UPDATE service SET Serve_Desc='$serve_desc', Serve_Unit='$unit', Serve_Rate='$rate', Dept_Id='$dept' Where Service_Id='$serve_id'";
    // echo $q_update;
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        $_SESSION['status'] = "Service Updated!";
        $_SESSION['status_code'] = "success";
        header('Location: prodServe.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Service";
        $_SESSION['status_code'] = "error";
        header('Location: prodServe.php');
    }
}
if(isset($_POST['sendQuote'])){
    $message=$_POST['message'];
    $tc=$_POST['tc'];
    $quote_app=2;
    $post_id=$_POST['post_id'];
    $comp_id=$_POST['comp_id'];
    $q_status=1;
    $post_type=$_POST['post_type'];
    // check if there is already quote sent by the company
    $q_search="SELECT Quote_Id FROM quote WHERE Post_Id='$post_id' AND Comp_Id='$comp_id' AND Quote_Status=1 AND Quote_Approval!=0";
    $q_search_run=mysqli_query($connection,$q_search);
    if(mysqli_num_rows($q_search_run)>0){
        $_SESSION['status'] = "Quote already sent on this post";
        $_SESSION['status_code'] = "warning";
        header('Location: post.php');
    }
    else{
        $add_quote="INSERT INTO quote (Quote_Message,`Quote_T&C`,Quote_Approval,Post_Id,Comp_Id,Quote_Status) VALUES ('$message','$tc','$quote_app','$post_id','$comp_id','$q_status')";
        if($connection->query($add_quote)===TRUE)
        {          
            $l_id = $connection->insert_id; 
            if(isset($_POST['q_qty'])){
                $data = array( 
                    'q_qty' => $_POST['q_qty'],
                    'post_desc_id' => $_POST['post_desc_id'],
                    'remarks' => $_POST['remarks']
                );
                $count = count($_POST['q_qty']);
                if($post_type=='manpower' || $post_type=='subcontractor'){
                    for ($i=0; $i < $count ; $i++) { 
                        $add_q_details="INSERT INTO quote_detail (Quote_Detail_Status,Quote_Detail_Value,MP_Post_Id,Quote_Remarks,Quote_Id,Quote_Detail_Approval) VALUES (1,'{$_POST['q_qty'][$i]}','{$_POST['post_desc_id'][$i]}','{$_POST['remarks'][$i]}',$l_id,2)";
                        $connection->query($add_q_details);
                    }
                }
                elseif($post_type=='material'){
                    for ($i=0; $i < $count ; $i++) { 
                        $add_q_details="INSERT INTO quote_detail (Quote_Detail_Status,Quote_Detail_Value,Mat_Post_Id,Quote_Id,Quote_Remarks,Quote_Detail_Approval) VALUES (1,'{$_POST['q_qty'][$i]}','{$_POST['post_desc_id'][$i]}',$l_id,'{$_POST['remarks'][$i]}',2)";
                        $connection->query($add_q_details);
                    }
                }
                if($add_q_details){
                    //NOTIFICATION
                    $q_not="SELECT * FROM users WHERE USERTYPE='purchase' AND USER_STATUS=1";
                    $q_not_run=mysqli_query($connection,$q_not);
                    if($q_not_run){
                        while($row_u=mysqli_fetch_assoc($q_not_run)){
                            $u_id=$row_u['USER_ID'];
                            $username=$row_u['USERNAME'];
                            $q_new_reg_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Comp_Id,Post_Id) VALUES('$u_id','new_quote',1,'$comp_id','$post_id')";
                            $q_new_reg_notif_run=mysqli_query($connection,$q_new_reg_notif);
                            $q_comp="SELECT * FROM company WHERE Comp_Id='$comp_id'";
                            $q_comp_run=mysqli_query($connection,$q_comp);
                            if(mysqli_num_rows($q_comp_run)>0){
                                while($row_cname=mysqli_fetch_assoc($q_comp_run)){
                                    $company_name=$row_cname['Comp_Name'];
                                }
                            }
                            $to=$row_u['USER_EMAIL'];
                            $subject="$company_name Sent Quote";
                            $q_id=$l_id;
                            $quote_details=quoteDetails($q_id);
                            $body="Dear $username,<br><br>$quote_details<br><br>
                            <br><br>This is a system-generated email. Please do not reply.";
                            sendmail($to,$subject,$body);
                        }
                    }
                    $_SESSION['status'] = "Quotation Send";
                    $_SESSION['status_code'] = "success";
                    header('Location: post.php');
                }
                else{
                    $del_q="DELETE * FROM quote WHERE Quote_Id='$l_id'";
                    $del_q_run=mysqli_query($connection,$del_q);
                    $_SESSION['status'] = "Quotation Failed to Send";
                    $_SESSION['status_code'] = "error";
                    header('Location: post.php');
                }
                // GROUP DISCOUNT
                if(isset($_POST['grpIds'])){
                    $data1 = array(
                        'grpIds'=>$_POST['grpIds'],
                        'grp_disc'=>$_POST['grp_disc'],
                    );
                    $countg = count($_POST['grpIds']);
                    for ($i=0; $i < $countg ; $i++) {  //every grp_disc
                        //SELECT THE NEWLY ADDED quote detail $l_od
                        //SELECT MAT POST IDS
                        $mat_post_ids="SELECT * FROM material_post WHERE MP_Grp_Id='{$_POST['grpIds'][$i]}'";
                        $mat_post_ids_run=mysqli_query($connection,$mat_post_ids);
                        if(mysqli_num_rows($mat_post_ids_run)){
                            while($row_mp=mysqli_fetch_assoc($mat_post_ids_run)){
                                $mp_post_id=$row_mp['Mat_Post_Id'];
                                //search on quote details 
                                if($_POST['grp_disc'][$i]!=0 || $_POST['grp_disc'][$i]!=NULL){
                                    echo $qouteDetails="UPDATE quote_detail SET Quote_Detail_Disc='{$_POST['grp_disc'][$i]}' WHERE Mat_Post_Id='$mp_post_id' AND Quote_Id='$l_id' AND Quote_Detail_Status=1";
                                    $update_grpDisc=mysqli_query($connection,$qouteDetails);
                                }
                                
                            }
                        }
                    }
                }
            }
            else{
                $del_q="DELETE * FROM quote WHERE Quote_Id='$l_id'";
                $del_q_run=mysqli_query($connection,$del_q);
                $_SESSION['status'] = "Missing Details";
                $_SESSION['status_code'] = "error";
                header('Location: post.php');
            }
            if($add_q_details){
                //NOTIFICATION
                $q_not="SELECT * FROM users WHERE USERTYPE='purchase' AND USER_STATUS=1";
                $q_not_run=mysqli_query($connection,$q_not);
                if($q_not_run){
                    while($row_u=mysqli_fetch_assoc($q_not_run)){
                        $u_id=$row_u['USER_ID'];
                        $username=$row_u['USERNAME'];
                        $q_new_reg_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Comp_Id,Post_Id) VALUES('$u_id','new_quote',1,'$comp_id','$post_id')";
                        $q_new_reg_notif_run=mysqli_query($connection,$q_new_reg_notif);
                        $q_comp="SELECT * FROM company WHERE Comp_Id='$comp_id'";
                        $q_comp_run=mysqli_query($connection,$q_comp);
                        if(mysqli_num_rows($q_comp_run)>0){
                            while($row_cname=mysqli_fetch_assoc($q_comp_run)){
                                $company_name=$row_cname['Comp_Name'];
                            }
                        }
                        $to=$row_u['USER_EMAIL'];
                        $subject="$company_name Sent Quote";
                        $q_id=$l_id;
                        $quote_details=quoteDetails($q_id);
                        $body="Dear $username,<br><br>$quote_details<br><br>
                        <br><br>This is a system-generated email. Please do not reply.";
                        sendmail($to,$subject,$body);
                    }
                }
                $_SESSION['status'] = "Quotation Send";
                $_SESSION['status_code'] = "success";
                header('Location: post.php');
            }
            else{
                $del_q="DELETE * FROM quote WHERE Quote_Id='$l_id'";
                $del_q_run=mysqli_query($connection,$del_q);
                $_SESSION['status'] = "Quotation Failed to Send";
                $_SESSION['status_code'] = "error";
                header('Location: post.php');
            }  
        }
        else{
            $_SESSION['status'] = "Quotation Failed to Send";
            $_SESSION['status_code'] = "error";
            header('Location: post.php');
        }
    }
    
}
if(isset($_POST['changepass']))
{   // PASSING VARIABLE
    $username = $_POST['username'];
    $oldp = md5($_POST['oldp']);
    $newp = md5($_POST['newp']);
    $confirmp = md5($_POST['confirmp']);

    $pass_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_PASSWORD='$oldp'"; // 1 true same password
    $pass_query_run = mysqli_query($connection, $pass_query);
    if(mysqli_num_rows($pass_query_run) === 1){
        if($newp === $confirmp){ // confirming the password
            // UPDATE QUERY
            $query="UPDATE users SET USER_PASSWORD='$newp' WHERE USERNAME='$username'";
            $query_run = mysqli_query($connection, $query);
           if($query_run) {
                   // success
                   $_SESSION['status'] = "Password Changed";
                   $_SESSION['status_code'] = "success";
                   header('Location: change_pw.php');
               }
               else{
                   //error
                   $_SESSION['status'] = "Error upon changing";
                   $_SESSION['status_code'] = "error";
                   header('Location: change_pw.php');
               }
        }
           else{
               $_SESSION['status'] = "Passord does not match";
               $_SESSION['status_code'] = "warning";
               header('Location: change_pw.php');
           }
    }
    else{
        $_SESSION['status'] = "Old Password Incorrect";
        $_SESSION['status_code'] = "error";
        header('Location: change_pw.php');
    }
}
if(isset($_POST['delQuote'])){
    $quote_id=$_POST['quoteId'];
    $delQuote="UPDATE quote SET Quote_Status=0 WHERE Quote_Id='$quote_id'";
    $delQuoterun=mysqli_query($connection,$delQuote);
    if($delQuoterun){
        $_SESSION['status'] = "Quote Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: quotation.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Quote";
        $_SESSION['status_code'] = "error";
        header('Location: quotation.php');
    }
}
if(isset($_POST['updateQuote'])){
    $quote_id=$_POST['quote_id'];
    $post_id=$_POST['post_id'];
    $message=$_POST['message'];
    $tc=$_POST['tc'];
    //update quote generail details
    $q_update="UPDATE quote SET Quote_Message='$message', `Quote_T&C`='$tc' WHERE Quote_Id='$quote_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        // check if material or manpower
        if( $_POST['grp_disc']){
            $data = array(
                'grp_disc' => $_POST['grp_disc'], 
                'grpIds' => $_POST['grpIds']
            );
            $count = count($_POST['grp_disc']);
            if($count>0){
                for($i=0; $i < $count; $i++){
                    //update group discount
                    //search qd 
                    $q_quoteDetail="SELECT Mat_Post_Id FROM material_post WHERE MP_Grp_Id='{$_POST['grpIds'][$i]}' AND Mat_Post_Status=1";
                    $q_quoteDetail_run=mysqli_query($connection,$q_quoteDetail);
                    if(mysqli_num_rows($q_quoteDetail_run)>0){
                        while($row_g=mysqli_fetch_assoc($q_quoteDetail_run)){
                            $mat_post_id=$row_g['Mat_Post_Id'];
                            //search/update quote detail discount
                            $q_update_disc="UPDATE quote_detail SET Quote_Detail_Disc='{$_POST['grp_disc'][$i]}' WHERE Mat_Post_Id='$mat_post_id' and Quote_Id='$quote_id'";
                            $q_update_disc_run=mysqli_query($connection,$q_update_disc);
                        }
                    }
                }
            }
        }
        // update unit price
        $data = array(
            'qd_detail_id' => $_POST['qd_detail_id'], 
            'e_unitP' => $_POST['e_unitP'],
            'remarks' => $_POST['remarks']
        );
        $countd = count($_POST['qd_detail_id']);
        if($countd>0){
            for($j=0; $j < $countd; $j++){
                //update unit price
                $q_update_unitp="UPDATE quote_detail SET Quote_Detail_Value='{$_POST['e_unitP'][$j]}', Quote_Remarks='{$_POST['remarks'][$j]}' WHERE Quote_Detail_Id='{$_POST['qd_detail_id'][$j]}' and Quote_Id='$quote_id'";
                $q_update_unitp_run=mysqli_query($connection,$q_update_unitp);
            }
            if($q_update_unitp){
                $_SESSION['status'] = "Quote Updated";
                $_SESSION['status_code'] = "success";
                header('Location: quotation.php');
            }
            else{
                $_SESSION['status'] = "Error Updating Quote";
                $_SESSION['status_code'] = "error";
                header('Location: quotation.php');
            }
        }
        
    }
    else{
        $_SESSION['status'] = "Error Updating Quote";
        $_SESSION['status_code'] = "error";
        header('Location: quotation.php');
    }
}
?>