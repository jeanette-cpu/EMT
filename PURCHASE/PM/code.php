<?php
include('../../security.php');
include('../../email.php');
// error_reporting(0);
if(isset($_POST['addJobPost'])){
    $username=$_SESSION['USERNAME']; //purchase department username
    $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
    $query_run_user = mysqli_query($connection, $query_user);
    if(mysqli_num_rows($query_run_user)==1){
        $row=mysqli_fetch_assoc($query_run_user);
        $user_email=$row['USER_EMAIL'];
    }
    $username=ucfirst($username);

    $post_name=$_POST['post_name'];
    $post_desc=$_POST['post_desc'];
    $job_type=$_POST['job_type'];  // manpower or subcon
    $prj_id=$_POST['project'];
    $post_type=$_POST['job_type'];
    $cc=$_POST['ccEmail'];
    $attachment=$_FILES['attachment'];
    if(isset($_POST['emailSend'])){
        $email_send=$_POST['emailSend'];
    }
    else{
        $email_send=0;
    }
    date_default_timezone_set('Asia/Dubai');
    // date today
    $date = date('Y-m-d');
    //INSERT TO POST
    $q_insert="INSERT INTO post (Post_Name,Post_Desc,Post_Type,Post_Date,Post_Status,Prj_Id) VALUES ('$post_name','$post_desc','$post_type','$date',1,'$prj_id')";
    // echo $q_insert;
    if($connection->query($q_insert)===TRUE)
    {          
        $l_id = $connection->insert_id; 
        if(isset($_POST['desc']) !="")
        { 
            // check if manpower/subcon
            if($job_type=='manpower'){
                $data = array( 
                    'dept_id' => $_POST['dept_id'],
                    'desc' => $_POST['desc'],
                    'qty' => $_POST['qty'],
                    'unit_mp'=>$_POST['unit_mp']
                    );
                $count = count($_POST['desc']);
                for ($i=0; $i < $count; $i++) { 
                    $sql ="INSERT INTO manpower_post (Dept_Id,Post_Id,MP_Post_Desc,MP_Post_Qty,MP_Post_Unit,MP_Post_Status) VALUES ('{$_POST['dept_id'][$i]}','$l_id','{$_POST['desc'][$i]}','{$_POST['qty'][$i]}','{$_POST['unit_mp'][$i]}',1)";
                    // echo $sql;
                    $connection->query($sql);
                }
                //NOTIFICATION
                //GET ALL USERID of manpower type
                $q_u="SELECT u.USER_ID,u.USER_EMAIL, u.USERNAME, comp.Comp_Name FROM users as u 
                    LEFT JOIN company as comp on comp.User_Id=u.USER_ID
                    WHERE u.USERTYPE='company' AND comp.Comp_Type!='oem' AND comp.Comp_Type!='distributor' AND comp.Comp_Type!='trading'";
                $q_u_run=mysqli_query($connection,$q_u);
                if(mysqli_num_rows($q_u_run)>0){
                    $subject="Post Alert: $post_name";
                    $post_id_details=$l_id;
                    $post_details=postDetails($post_id_details);
                    while($row_u=mysqli_fetch_assoc($q_u_run)){
                        $user_id=$row_u['USER_ID'];
                        $to[]=$row_u['USERNAME'];
                        $comp_name=$row_u['Comp_Name'];
                        $q_new_post_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Post_Id) VALUES ('$user_id','new_post',1,'$l_id')";
                        $q_new_post_notif_run=mysqli_query($connection,$q_new_post_notif);
                    }
                    $_SESSION['status'] = "Posted!";
                    $_SESSION['status_code'] = "success";
                    header('Location: p_job_post.php');
                }
                else{
                    $_SESSION['status'] = "Error Posting";
                    $_SESSION['status_code'] = "error";
                    header('Location: p_job_post.php');
                }
            }
            elseif($job_type=='subcontractor'){
                $data = array( 
                    'dept_id_sb' => $_POST['dept_id_sb'],
                    'desc_sb' => $_POST['desc_sb'],
                    'unit_sb' => $_POST['unit_sb'],    // sq.f
                    'qty_sb' => $_POST['qty_sb'] // Amount
                    );
                $count = count($_POST['dept_id_sb']);
                for ($i=0; $i < $count; $i++) { 
                    $sql ="INSERT INTO manpower_post (Dept_Id,Post_Id,MP_Post_Desc,MP_Post_Unit,MP_Post_Qty,MP_Post_Status) VALUES ('{$_POST['dept_id_sb'][$i]}','$l_id','{$_POST['desc_sb'][$i]}','{$_POST['unit_sb'][$i]}','{$_POST['qty_sb'][$i]}',1)";
                    // echo $sql; echo $_POST['unit_sb'][$i];
                    $connection->query($sql);
                }
                if($sql){
                    //NOTIFICATION
                    //GET ALL USERID of manpower company
                    $q_u="SELECT u.USER_ID,u.USER_EMAIL, u.USERNAME, comp.Comp_Name FROM users as u 
                        LEFT JOIN company as comp on comp.User_Id=u.USER_ID
                        WHERE u.USERTYPE='company' AND comp.Comp_Type!='oem' AND comp.Comp_Type!='trading' AND comp.Comp_Type!='distributor' AND comp.Comp_Type!='agency' AND comp.Comp_Type!='laborSupply'";
                    $q_u_run=mysqli_query($connection,$q_u);
                    if(mysqli_num_rows($q_u_run)>0){
                        $subject="Post Alert: $post_name";
                        $post_id_details=$l_id;
                        $post_details=postDetails($post_id_details);
                        while($row_u=mysqli_fetch_assoc($q_u_run)){
                            $user_id=$row_u['USER_ID'];
                            $to[]=$row_u['USERNAME'];
                            $comp_name=$row_u['Comp_Name'];
                            $q_new_post_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Post_Id) VALUES ('$user_id','new_post',1,'$l_id')";
                            $q_new_post_notif_run=mysqli_query($connection,$q_new_post_notif);
                        }
                    }
                    $_SESSION['status'] = "Posted!";
                    $_SESSION['status_code'] = "success";
                    header('Location: p_job_post.php');
                }
                $_SESSION['status'] = "Posted!";
                $_SESSION['status_code'] = "success";
                header('Location: p_job_post.php');
            }
            //error
            else{
                $_SESSION['status'] = "Missing Post Details";
                $_SESSION['status_code'] = "warning";
                header('Location: p_job_post.php');
            }
            // SELECT email from the selected grps
            if($email_send==1){
                $post_id_details=$l_id;
                $post_details=postDetails($post_id_details);
                $grpNames=$_POST['emalGrp'];
                foreach ($grpNames as $grp_id){
                    //search emails from the group
                    //check if its grp id or custom email
                    if(is_numeric($grp_id)){
                        $grp_emails="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1 ";
                        $grp_emails_run=mysqli_query($connection,$grp_emails);
                        if(mysqli_num_rows($grp_emails_run)>0){
                            while($row_grp=mysqli_fetch_assoc($grp_emails_run)){
                                $user_id=$row_grp['User_Id'];
                                if($user_id){ //search for username
                                    $q_user="SELECT u.USERNAME, c.Comp_Name
                                            FROM users as u
                                            LEFT JOIN company as c on c.User_Id=u.USER_ID
                                            WHERE u.USER_ID='$user_id'  AND c.Comp_Approval='1' limit 1 ";
                                    $q_user_run=mysqli_query($connection,$q_user);
                                    if(mysqli_num_rows($q_user_run)==1){
                                        $rown=mysqli_fetch_assoc($q_user_run);
                                        $username=$rown['USERNAME'];
                                        // echo $username.'<br>';
                                        $comp_name=$rown['Comp_Name'];
                                        if($username){
                                            $subject="EMT Post Alert: $post_name";
                                            $to[]=$username;
                                        }
                                    }
                                }
                                else{
                                    $email=$row_grp['Email'];
                                    if($email){
                                        $to[]=$email;
                                    }
                                }
                            }
                        }
                    }
                    else{// other email
                        $to[]=$grp_id;
                    }
                    $subject="EMT Post Alert: $post_name";
                    // $body="Dear Sir/Madam,<br><br>Good day! We would like to update you with our new post. Kindly review and apply for quotation.<br><br><br>$post_details <br>Please register through this link to use our online services :  https://emtdubai.ae/EMT/register.php <br>Thank you!<br><br><br>Sincerely,<br>EMT Electromechanical Works LLC<br><br><br><br>This is a system-generated email. Please do not reply.";
                        $body="Dear Sir/Madam,<br><br>Good day! We would like to update you with our new post. Kindly review and apply for quotation.<br><br><br>$post_details <br><span style='color:red; font-weight:bold;'>Your immediate action will be appreciated. <br></span>Thank you for using our services.<br><br>Login or Register through this link:  https://emtdubai.ae/EMT/login.php<br><br><br>Thanks & Regards,<br><br><br><span style='color:red; font-weight:bold;'>$username </span><br>Purchase Officer<br><span style='color:blue; '>Email: $user_email | www.emtdubai.ae </span><br><br>";
                    if(sendmail($to,$subject,$body,$cc,$attachment)){
                        $_SESSION['status'] = "Posted!";
                        $_SESSION['status_code'] = "success";
                        header('Location: p_job_post.php');
                    }
                    else{
                        $_SESSION['status'] = "Error Sending Email";
                        $_SESSION['status_code'] = "error";
                        header('Location: p_job_post.php');
                    }
                }
                $_SESSION['status'] = "Posted!";
                $_SESSION['status_code'] = "success";
                header('Location: p_job_post.php');
            }
        }
        // display error, delete the post
        else{
            //delete unfinished post
            $q_del="DELETE FROM post WHERE Post_Id='$l_id'";
            $q_del_run=mysqli_query($connection,$q_del);
            $_SESSION['status'] = "Missing Post Details";
            $_SESSION['status_code'] = "warning";
            header('Location: p_job_post.php');
        }
    }
}
if(isset($_POST['update_post'])){
    $post_id=$_POST['post_id'];
    $project=$_POST['prj_id'];
    $post_name=$_POST['post_name'];
    $post_desc=$_POST['post_desc'];
    $post_type=$_POST['post_type'];
    
    // check if type was changed
    // previous type
    $q_type="SELECT Post_Type as type FROM post WHERE Post_Id='$post_id'";
    $q_type_run=mysqli_query($connection,$q_type);
    $rt=mysqli_fetch_assoc($q_type_run);
    $prv_type = $rt['type'];
    if($prv_type!=$post_type){
        $del="DELETE  FROM manpower_post WHERE Post_Id='$post_id'";
        $del_query=mysqli_query($connection,$del);
    }

    if($post_name==''||$post_type='' || $project=''){
        // return to edit posts
        $_SESSION['status'] = "Missing Post Details";
        $_SESSION['status_code'] = "warning";
        header('Location: edit_job_post.php?post_id='.$post_id);
    }
    else{
        // update post details
        $post_type=$_POST['post_type'];
        $project=$_POST['prj_id'];

        $update_post="UPDATE post SET Post_Name='$post_name', Post_Desc='$post_desc', Post_Type='$post_type', Prj_Id='$project' WHERE Post_Id='$post_id'";
        // echo $update_post;
        $update_post_run=mysqli_query($connection,$update_post);
        if($update_post_run){
            if($post_type=='manpower')
            {
                if(isset($_POST['mp_post_id'])){
                    $data = array(
                        'mp_post_id' =>$_POST['mp_post_id'],
                        'dept_id' => $_POST['dept_id'],
                        'desc' => $_POST['desc'],
                        'qty'=> $_POST['qty'],
                        'a_dept_id' => $_POST['a_dept_id'],
                        'a_desc' => $_POST['a_desc'],
                        'a_qty'=> $_POST['a_qty'],
                        'unit_mp'=>$_POST['unit_mp'],
                        'a_unit_mp'=>$_POST['a_unit_mp']
                    ); 
                    $count = count($_POST['mp_post_id']);
                    for ($i=0; $i < $count; $i++)
                    {
                        $sql="UPDATE manpower_post SET Dept_Id='{$_POST['dept_id'][$i]}',MP_Post_Desc='{$_POST['desc'][$i]}',MP_Post_Qty='{$_POST['qty'][$i]}',MP_Post_Unit='{$_POST['unit_mp'][$i]}' WHERE MP_Post_Id='{$_POST['mp_post_id'][$i]}'";
                        // echo $sql;
                        $query_run = mysqli_query($connection,$sql);
                        if($query_run)
                        {
                            $_SESSION['status'] = "Post Updated";
                            $_SESSION['status_code'] = "success";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                        else
                        {
                            $_SESSION['status'] = "Error Updating Post";
                            $_SESSION['status_code'] = "error";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                    }
                }
                else{
                    $_SESSION['status'] = "Post Updated";
                    $_SESSION['status_code'] = "success";
                    header('Location: edit_job_post.php?post_id='.$post_id);
                }   
                if(isset($_POST['a_dept_id'])){
                    $count_new = count($_POST['a_dept_id']);
                    if($count_new>0){
                        for ($c=0; $c < $count_new; $c++){
                            $sql="INSERT INTO manpower_post (Dept_Id,Post_Id,MP_Post_Desc,MP_Post_Qty,MP_Post_Unit,MP_Post_Status) VALUES ('{$_POST['a_dept_id'][$c]}','$post_id','{$_POST['a_desc'][$c]}','{$_POST['a_qty'][$c]}','{$_POST['a_unit_mp'][$c]}',1)";
                            // echo $sql;
                            $connection->query($sql);
                            if($sql)
                            {
                                $_SESSION['status'] = "Post Updated";
                                $_SESSION['status_code'] = "success";
                                header('Location: edit_job_post.php?post_id='.$post_id);
                            }
                            else
                            {
                                    $_SESSION['status'] = "Error Updating Post";
                                    $_SESSION['status_code'] = "error";
                                    header('Location: edit_job_post.php?post_id='.$post_id);
                            }
                        }
                    }
                }
                else{
                    $_SESSION['status'] = "Post Updated";
                    $_SESSION['status_code'] = "success";
                    header('Location: edit_job_post.php?post_id='.$post_id);
                }        
            }
            elseif($post_type=='subcontractor'){
                //edit
                if(isset($_POST['mp_post_id_sb'])){
                    $data = array( 
                        'mp_post_id_sb' => $_POST['mp_post_id_sb'],
                        'e_dept_id_sb' => $_POST['e_dept_id_sb'],
                        'e_desc_sb' => $_POST['e_desc_sb'],
                        'e_unit_sb' => $_POST['e_unit_sb'],
                        'e_qty_sb' => $_POST['e_qty_sb']
                    );
                    $count = count($_POST['mp_post_id_sb']); 
                    for ($i=0; $i < $count; $i++) { 
                        $q_update="UPDATE manpower_post SET Dept_Id='{$_POST['e_dept_id_sb'][$i]}',MP_Post_Desc='{$_POST['e_desc_sb'][$i]}',MP_Post_Unit='{$_POST['e_unit_sb'][$i]}',MP_Post_Qty='{$_POST['e_qty_sb'][$i]}' WHERE MP_Post_Id='{$_POST['mp_post_id_sb'][$i]}'";
                        $q_update_run=mysqli_query($connection,$q_update);
                        if($q_update_run){
                            $_SESSION['status'] = "Post Updated!";
                            $_SESSION['status_code'] = "success";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                        else{
                            $_SESSION['status'] = "Error Updating";
                            $_SESSION['status_code'] = "error";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                    }
                }
                else{
                    $_SESSION['status'] = "Post Updated!";
                    $_SESSION['status_code'] = "success";
                    header('Location: edit_job_post.php?post_id='.$post_id);
                }
                //add
                if(isset($_POST['dept_id_sb'])){
                    $data = array( 
                        'dept_id_sb' => $_POST['dept_id_sb'],
                        'desc_sb' => $_POST['desc_sb'],
                        'unit_sb' => $_POST['unit_sb'],    // sq.f
                        'qty_sb' => $_POST['qty_sb'] // Amount
                        );
                    $count = count($_POST['dept_id_sb']); 
                    for ($i=0; $i < $count; $i++) { 
                        $sql ="INSERT INTO manpower_post (Dept_Id,Post_Id,MP_Post_Desc,MP_Post_Unit,MP_Post_Qty,MP_Post_Status) VALUES ('{$_POST['dept_id_sb'][$i]}','$post_id','{$_POST['desc_sb'][$i]}','{$_POST['unit_sb'][$i]}','{$_POST['qty_sb'][$i]}',1)";
                        $connection->query($sql);
                        if($sql){
                            $_SESSION['status'] = "Post Updated!";
                            $_SESSION['status_code'] = "success";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                        else{
                            $_SESSION['status'] = "Error Updating";
                            $_SESSION['status_code'] = "error";
                            header('Location: edit_job_post.php?post_id='.$post_id);
                        }
                    }
                }
                else{
                    $_SESSION['status'] = "Post Updated!";
                    $_SESSION['status_code'] = "success";
                    header('Location: edit_job_post.php?post_id='.$post_id);
                }

            }
            else{
                $_SESSION['status'] = "Please Complete Post Details";
                $_SESSION['status_code'] = "warning";
                header('Location: edit_job_post.php?post_id='.$post_id);
            }
        }
        else{
            $_SESSION['status'] = "Error Updating Post Details";
            $_SESSION['status_code'] = "error";
            header('Location: edit_job_post.php?post_id='.$post_id);
        }
    }
}
if(isset($_POST['removeMP'])){
    $post_id=$_POST['p_id'];
    $mp_id=$_POST['mp_post_del'];
    $del="DELETE  FROM manpower_post WHERE MP_Post_Id='$mp_id'";
    $del_query=mysqli_query($connection,$del);
    if($del_query){
        $_SESSION['status'] = "Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: edit_job_post.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Post";
        $_SESSION['status_code'] = "error";
        header('Location: edit_job_post.php?post_id='.$post_id);
    }
}
if(isset($_POST['delPost'])){
    $post_id=$_POST['post_id'];
    $delete="UPDATE post SET Post_Status=0 WHERE Post_Id='$post_id'";
    $delete_run=mysqli_query($connection,$delete);
    if($delete_run){
        $_SESSION['status'] = "Post Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: p_job_post.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Post";
        $_SESSION['status_code'] = "error";
        header('Location: p_job_post.php');
    }
}
if(isset($_POST['add_mat_post'])){ // ADD material post
    $username=$_SESSION['USERNAME']; //purchase department username
    $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
    $query_run_user = mysqli_query($connection, $query_user);
    if(mysqli_num_rows($query_run_user)==1){
        $row=mysqli_fetch_assoc($query_run_user);
        $user_email=$row['USER_EMAIL'];
    }
    $username=ucfirst($username);
    $post_name=$_POST['post_name'];
    $post_desc=$_POST['post_desc'];
    $prj_id=$_POST['project'];
    $post_type='material'; 
    date_default_timezone_set('Asia/Dubai');
    $date = date('Y-m-d'); // date today
    $grpName=$_POST['grpName'];
    $grpLoc=$_POST['grpLocation'];
    $cc=$_POST['ccEmail'];
    $attachment=$_FILES['attachment'];
    // var_dump($attachment);
    if(isset($_POST['emailSend'])){
        $email_send=$_POST['emailSend'];
    }
    else{
        $email_send=0;
    }
    // INSERT TO POST
    $q_insert="INSERT INTO post (Post_Name,Post_Desc,Post_Type,Post_Date,Post_Status,Prj_Id) VALUES ('$post_name','$post_desc','$post_type','$date',1,'$prj_id')";
    if($connection->query($q_insert)===TRUE)
    {          
        $l_id = $connection->insert_id; // INSERT GROUP  
        $q_grp_insert="INSERT INTO material_post_group (MP_Grp_Name,MP_Grp_Location,Post_Id,MP_Grp_Status) VALUES ('$grpName','$grpLoc','$l_id',1)";
        if($connection->query($q_grp_insert)===TRUE){
            $grp_id=$connection->insert_id; 
            if(isset($_POST['mat_id']))
            { 
                $data = array( 
                    'mat_id' => $_POST['mat_id'],
                    'ref_code' => $_POST['ref_code'],
                    'mat_qty' => $_POST['mat_qty'],
                    'mat_unit' => $_POST['mat_unit'],
                    'mat_pref_brand' => $_POST['mat_pref_brand'],
                    'mat_capacity' => $_POST['mat_capacity'],
                    'mat_esp' => $_POST['mat_esp'],
                    'mat_location' => $_POST['mat_location']
                    );
                $count = count($_POST['mat_id']);
                for ($i=0; $i < $count; $i++) { 
                    if($_POST['mat_id'][$i]!=""AND $_POST['mat_id'][$i]!=NULL AND $_POST['mat_id'][$i]!='Select Material'){
                        if(strlen($_POST['mat_esp'][$i])>0){ 
                            // echo $_POST['mat_esp'][$i];
                        }
                        else{
                            $esp==NULL;
                        }
                        $sql ="INSERT INTO material_post (Mat_Id,Mat_Post_Ref_Code,Mat_Post_Qty,Mat_Post_Brand,Mat_Post_Capacity,Mat_Post_Esp,Mat_Post_Location,Mat_Post_Unit,Post_Id,Mat_Post_Status,MP_Grp_Id) VALUES ('{$_POST['mat_id'][$i]}','{$_POST['ref_code'][$i]}','{$_POST['mat_qty'][$i]}','{$_POST['mat_pref_brand'][$i]}','{$_POST['mat_capacity'][$i]}','$esp','{$_POST['mat_location'][$i]}','{$_POST['mat_unit'][$i]}',$l_id,1,$grp_id)";
                        $connection->query($sql);
                    }
                }
                // get post details
                if($sql){
                    // NOTIFICATION // GET ALL USERID of material company
                    $q_u="SELECT u.USER_ID FROM users as u  
                            LEFT JOIN company as comp on comp.User_Id=u.USER_ID 
                            WHERE u.USERTYPE='company' AND comp.Comp_Type!='agency' 
                            AND comp.Comp_Type!='subcon' AND comp.Comp_Type!='laborSupply' AND u.USER_STATUS=1";
                    $q_u_run=mysqli_query($connection,$q_u);
                    if(mysqli_num_rows($q_u_run)>0){
                        $subject="EMT Post Alert: $post_name";
                        while($row_u=mysqli_fetch_assoc($q_u_run)){
                            $user_id=$row_u['USER_ID'];
                            $q_new_post_notif="INSERT INTO notification (User_Id,Not_Type,Not_Status,Post_Id) VALUES ('$user_id','new_post',1,'$l_id')";
                            $q_new_post_notif_run=mysqli_query($connection,$q_new_post_notif);
                        }
                    }
                }
                else{
                    $_SESSION['status'] = "Error Posting";
                    $_SESSION['status_code'] = "error";
                    header('Location: p_material_post.php');
                }
            }
            else{
                //delete unfinished post
                $q_del="DELETE FROM post WHERE Post_Id='$l_id'";
                $q_del_run=mysqli_query($connection,$q_del);
                $_SESSION['status'] = "Missing Post Details";
                $_SESSION['status_code'] = "warning";
                header('Location: p_material_post.php');
            }
        }
        else{
            $_SESSION['status'] = "Error Posting";
            $_SESSION['status_code'] = "error";
            header('Location: p_material_post.php');
        }
        //2ND GROUP
        if(isset($_POST['grpName1'])){
            $grpName1=$_POST['grpName1'];
            $grpLoc1=$_POST['grpLocation1'];
            //INSERT 2ND GROUP
            $q_grp_insert1="INSERT INTO material_post_group (MP_Grp_Name,MP_Grp_Location,Post_Id,MP_Grp_Status) VALUES ('$grpName1','$grpLoc1','$l_id',1)";
            if($connection->query($q_grp_insert1)===TRUE){
                $grp_id1=$connection->insert_id; 
                if(isset($_POST['mat_id1']))
                { 
                    $data = array( 
                        'mat_id1' => $_POST['mat_id1'],
                        'ref_code1' => $_POST['ref_code1'],
                        'mat_qty1' => $_POST['mat_qty1'],
                        'mat_unit1' => $_POST['mat_unit1'],
                        'mat_pref_brand1' => $_POST['mat_pref_brand1'],
                        'mat_capacity1' => $_POST['mat_capacity1'],
                        'mat_esp1' => $_POST['mat_esp1'],
                        'mat_location1' => $_POST['mat_location1']
                        );
                    $count1 = count($_POST['mat_id1']);
                    for ($i=0; $i < $count1; $i++) { 
                        if($_POST['mat_id1'][$i]!=""AND $_POST['mat_id1'][$i]!=NULL AND $_POST['mat_id1'][$i]!='Select Material'){
                            //esp remove blank
                            if(isset($_POST['mat_esp1'][$i])){ 
                            }
                            else{
                                $_POST['mat_esp1'][$i]==null;
                            }
                            $sql1 ="INSERT INTO material_post (Mat_Id,Mat_Post_Ref_Code,Mat_Post_Qty,Mat_Post_Brand,Mat_Post_Capacity,Mat_Post_Esp,Mat_Post_Location,Mat_Post_Unit,Post_Id,Mat_Post_Status,MP_Grp_Id) VALUES ('{$_POST['mat_id1'][$i]}','{$_POST['ref_code1'][$i]}','{$_POST['mat_qty1'][$i]}','{$_POST['mat_pref_brand1'][$i]}','{$_POST['mat_capacity1'][$i]}','{$_POST['mat_esp1'][$i]}','{$_POST['mat_location1'][$i]}','{$_POST['mat_unit1'][$i]}',$l_id,1,$grp_id1)";
                            $connection->query($sql1);
                        }
                    }
                    if($sql1){
                        $_SESSION['status'] = "Posted!";
                        $_SESSION['status_code'] = "success";
                        header('Location: p_material_post.php');
                    }
                    else{
                        $_SESSION['status'] = "Error Posting";
                        $_SESSION['status_code'] = "error";
                        header('Location: p_job_post.php');
                    }
                }
                else{
                    $_SESSION['status'] = "Missing Post Details";
                    $_SESSION['status_code'] = "warning";
                    header('Location: p_material_post.php');
                }
            }
        }
        // SELECT email from the selected grps
        if($email_send==1){
            $post_id_mdetails=$l_id;
            $post_details=postMatDetails($post_id_mdetails);
            $grpNames=$_POST['emalGrp'];
            $bccGroup=$_POST['bccEmail'];
            foreach ($grpNames as $grp_id){
                //search emails from the group
                //check if its grp id or custom email
                if(is_numeric($grp_id)){
                    $grp_emails="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1 ";
                    $grp_emails_run=mysqli_query($connection,$grp_emails);
                    if(mysqli_num_rows($grp_emails_run)>0){
                        while($row_grp=mysqli_fetch_assoc($grp_emails_run)){
                            $user_id=$row_grp['User_Id'];
                            if($user_id){ //search for username
                                $q_user="SELECT u.USERNAME, c.Comp_Name FROM users as u
                                        LEFT JOIN company as c on c.User_Id=u.USER_ID
                                        WHERE u.USER_ID='$user_id'  AND c.Comp_Approval='1' limit 1 ";
                                $q_user_run=mysqli_query($connection,$q_user);
                                if(mysqli_num_rows($q_user_run)==1){
                                    $rown=mysqli_fetch_assoc($q_user_run);
                                    $username=$rown['USERNAME'];
                                    $comp_name=$rown['Comp_Name'];
                                    if($username){
                                        $to[]=$username;
                                    }
                                }
                            }
                            else{
                                $email=$row_grp['Email'];
                                if($email){
                                    $to[]=$email;
                                }
                            }
                        }
                    }
                }
                else{// other email
                    $to[]=$grp_id;
                }
            }
            foreach ($bccGroup as $bgrp_id){
                //search emails from the group
                //check if its grp id or custom email
                if(is_numeric($bgrp_id)){
                    $bgrp_emails="SELECT * FROM email WHERE Email_Grp_Id='$bgrp_id' AND Email_Status=1 ";
                    $bgrp_emails_run=mysqli_query($connection,$bgrp_emails);
                    if(mysqli_num_rows($bgrp_emails_run)>0){
                        while($row_grpb=mysqli_fetch_assoc($bgrp_emails_run)){
                            $user_idb=$row_grpb['User_Id'];
                            if($user_idb){ //search for username
                                $q_userb="SELECT u.USERNAME, c.Comp_Name FROM users as u
                                        LEFT JOIN company as c on c.User_Id=u.USER_ID
                                        WHERE u.USER_ID='$user_idb'  AND c.Comp_Approval='1' limit 1 ";
                                $q_userb_run=mysqli_query($connection,$q_userb);
                                if(mysqli_num_rows($q_userb_run)==1){
                                    $rownb=mysqli_fetch_assoc($q_userb_run);
                                    $usernameb=$rownb['USERNAME'];
                                    $comp_name=$rownb['Comp_Name'];
                                    if($usernameb){
                                        $bcc[]=$usernameb;
                                    }
                                }
                            }
                            else{
                                $email=$row_grpb['Email'];
                                if($email){
                                    $bcc[]=$email;
                                }
                            }
                        }
                    }
                }
                else{// other email
                    $bcc[]=$bgrp_id;
                }
            }
            $subject="EMT Post Alert: $post_name";
            $body="Dear Sir/Madam,<br><br>Good day! We would like to update you with our new post. Kindly review and apply for quotation.<br> For queries & clarifications please contact the undersigned.<br><br><br>$post_details <br><span style='color:red; font-weight:bold;'>Your immediate action will be appreciated. <br></span>Thank you for using our services.<br><br>Login or Register through this link:  https://emtdubai.ae/EMT/login.php<br><br><br>Thanks & Regards,<br><br><br><span style='color:red; font-weight:bold;'>$username </span><br>Purchase Officer<br><span style='color:blue; '>Email: $user_email | www.emtdubai.ae </span><br><br>";
            if(sendmail($to,$subject,$body,$cc,$bcc,$attachment)){
                $_SESSION['status'] = "Email Send!";
                $_SESSION['status_code'] = "success";
                header('Location: p_material_post.php');
            }
            else{
                $_SESSION['status'] = "Sending Failed";
                $_SESSION['status_code'] = "error";
                header('Location: p_material_post.php');
            }

        }
        $_SESSION['status'] = "Posted!";
        $_SESSION['status_code'] = "success";
        header('Location: p_material_post.php');
    }
    else{
        $_SESSION['status'] = "Error Posting";
        $_SESSION['status_code'] = "error";
        header('Location: p_material_post.php');
    }
}
if(isset($_POST['update_mat_post'])){ //UPDATE MATERIAL POST
    $post_id=$_POST['post_id'];
    $project=$_POST['prj_id'];
    $post_name=$_POST['post_name'];
    $post_desc=$_POST['post_desc'];
    if($post_name=='' || $project=''){
        // return to edit posts
        $_SESSION['status'] = "Missing Post Details";
        $_SESSION['status_code'] = "warning";
        header('Location: edit_mat_post.php?post_id='.$post_id);
    }
    else{ // update post details
        $project=$_POST['prj_id'];
        $update_post="UPDATE post SET Post_Name='$post_name', Post_Desc='$post_desc', Prj_Id='$project' WHERE Post_Id='$post_id'";
        // echo $update_post;
        $update_post_run=mysqli_query($connection,$update_post);
        if($update_post_run){ // edit
           if(isset($_POST['mat_post_id'])){
                $data = array(
                    'mat_post_id' =>$_POST['mat_post_id'],
                    'e_mat_id'=>$_POST['e_mat_id'],
                    'e_ref_code' => $_POST['e_ref_code'],
                    'e_mat_qty'=>$_POST['e_mat_qty'],
                    'e_mat_unit'=>$_POST['e_mat_unit'],
                    'e_loc'=>$_POST['e_loc'],
                    'e_cap'=>$_POST['e_cap'],
                    'e_esp'=>$_POST['e_esp'],
                    'e_pref_b'=>$_POST['e_pref_b']
                ); 
                $count = count($_POST['mat_post_id']);
                for ($i=0; $i < $count; $i++){
                    $sql="UPDATE material_post SET Mat_Id='{$_POST['e_mat_id'][$i]}', Mat_Post_Ref_Code='{$_POST['e_ref_code'][$i]}',Mat_Post_Unit ='{$_POST['e_mat_unit'][$i]}', Mat_Post_Qty='{$_POST['e_mat_qty'][$i]}', Mat_Post_Brand='{$_POST['e_pref_b'][$i]}',Mat_Post_Capacity='{$_POST['e_cap'][$i]}',Mat_Post_Esp='{$_POST['e_esp'][$i]}',Mat_Post_Location ='{$_POST['e_loc'][$i]}'  WHERE Mat_Post_Id='{$_POST['mat_post_id'][$i]}'";
                    // echo $sql;
                    $query_run = mysqli_query($connection,$sql);
                    if($query_run) {
                        $remove_q="UPDATE material_post SET Mat_Post_Esp=null WHERE Mat_Post_Esp='0.00'";
                        $remove_q_run=mysqli_query($connection,$remove_q);
                        $_SESSION['status'] = "Post Updated";
                        $_SESSION['status_code'] = "success";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                    else {
                        $_SESSION['status'] = "Error Updating Post";
                        $_SESSION['status_code'] = "error";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                }
            }
            if(isset($_POST['add_mat_id'])){ //new add
                $d_arr = array(
                    'add_mat_id' =>$_POST['add_mat_id'],
                    'a_mat_ref_code' => $_POST['a_mat_ref_code'],
                    'a_mat_unit' =>$_POST['a_mat_unit'],
                    'a_mat_qty'=>$_POST['a_mat_qty'],
                    'a_mat_loc'=>$_POST['a_mat_loc'],
                    'a_mat_cap'=>$_POST['a_mat_cap'],
                    'a_mat_esp'=>$_POST['a_mat_esp'],
                    'a_mat_grp_id'=>$_POST['a_mat_grp_id'],
                    'a_mat_pref_brand'=>$_POST['a_mat_pref_brand']
                ); 
                $count = count($_POST['add_mat_id']);
                for ($i=0; $i < $count; $i++){
                    $sql ="INSERT INTO material_post (Mat_Id,Mat_Post_Ref_Code,Mat_Post_Unit ,Mat_Post_Qty,Mat_Post_Brand,Mat_Post_Capacity, Mat_Post_Esp,Mat_Post_Location,MP_Grp_Id ,Post_Id,Mat_Post_Status) VALUES ('{$_POST['add_mat_id'][$i]}','{$_POST['a_mat_ref_code'][$i]}','{$_POST['a_mat_unit'][$i]}','{$_POST['a_mat_qty'][$i]}','{$_POST['a_mat_pref_brand'][$i]}','{$_POST['a_mat_cap'][$i]}','{$_POST['a_mat_esp'][$i]}','{$_POST['a_mat_loc'][$i]}','{$_POST['a_mat_grp_id'][$i]}',$post_id,1)";
                    $connection->query($sql);
                    if($query_run){
                        $remove_q="UPDATE material_post SET Mat_Post_Esp=null WHERE Mat_Post_Esp='0.00'";// remove 0.00
                        $remove_q_run=mysqli_query($connection,$remove_q);
                        $_SESSION['status'] = "Post Updated";
                        $_SESSION['status_code'] = "success";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                    else{
                        $_SESSION['status'] = "Error Updating Post";
                        $_SESSION['status_code'] = "error";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                }
            }
            if(isset($_POST['grp_id'])){ // group update details
                $grp_arr = array(
                    'grp_id' =>$_POST['grp_id'],
                    'grp_name' =>$_POST['grp_name'],
                    'grp_loc'=>$_POST['grp_loc']
                ); 
                $c = count($_POST['grp_id']);
                for ($i=0; $i < $c; $i++){
                    $sql="UPDATE material_post_group SET MP_Grp_Name='{$_POST['grp_name'][$i]}',MP_Grp_Location ='{$_POST['grp_loc'][$i]}' WHERE MP_Grp_Id ='{$_POST['grp_id'][$i]}'";
                    $connection->query($sql);
                    if($sql){
                        $_SESSION['status'] = "Post Updated";
                        $_SESSION['status_code'] = "success";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                    else{
                        $_SESSION['status'] = "Error Updating Post";
                        $_SESSION['status_code'] = "error";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                }
            }
        }
        else{
            $_SESSION['status'] = "Error Updating Post";
            $_SESSION['status_code'] = "error";
            header('Location: edit_mat_post.php?post_id='.$post_id);
        }
    }
}
if(isset($_POST['delete_mat_post'])){ //DELETE MATERIAL POSTS
    $post_id=$_POST['post_id'];
    $delete="UPDATE post SET Post_Status=0 WHERE Post_Id='$post_id'";
    $delete_run=mysqli_query($connection,$delete);
    if($delete_run){
        $_SESSION['status'] = "Post Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: p_material_post.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Post";
        $_SESSION['status_code'] = "error";
        header('Location: p_material_post.php');
    }
}
if(isset($_POST['del_mat_post_detail'])){ // DELETE SINGLE MATERIAL
    $mat_post_id=$_POST['mat_post_del'];
    $post_id=$_POST['p_id'];
    $delete="DELETE  FROM material_post WHERE Mat_Post_Id='$mat_post_id'";
    $delete_run=mysqli_query($connection,$delete);
    if($delete_run){
        $_SESSION['status'] = "Post Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: edit_mat_post.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Post";
        $_SESSION['status_code'] = "error";
        header('Location: edit_mat_post.php?post_id='.$post_id);
    }
}
if(isset($_POST['addMat'])){ //ADD MATERIALS
    $dept_id=$_POST['dept_id'];
    $data = array(
        'mat_code' => $_POST['mat_code'], 
        'mat_desc' => $_POST['mat_desc'],
        'mat_unit' => $_POST['mat_unit'],
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat_code']);
    for($i=0; $i < $count; $i++){
        //check if material code exists
        $q_matcode="SELECT * FROM material WHERE Mat_Code='{$_POST['mat_code'][$i]}' AND Mat_Status=1";
        $q_matcode_run=mysqli_query($connection,$q_matcode);
        if(mysqli_num_rows($q_matcode_run)>0)
        {
            $_SESSION['status'] = "Material Code already exists";
            $_SESSION['status_code'] = "warning";
            header('Location: m_material.php');
        }
        else{
            $sql="INSERT INTO material (Mat_Code,Mat_Desc,Mat_Unit,Mat_Qty,Mat_Status,Dept_Id) VALUES ('{$_POST['mat_code'][$i]}','{$_POST['mat_desc'][$i]}','{$_POST['mat_unit'][$i]}','{$_POST['mat_qty'][$i]}',1,'$dept_id')";
            $query_run=mysqli_query($connection,$sql);
            if($query_run)
            {
                $_SESSION['status'] = "Material Added";
                $_SESSION['status_code'] = "success";
                header('Location: m_material.php');
            }
            else{
                $_SESSION['status'] = "Error Adding Material";
                $_SESSION['status_code'] = "warning";
                header('Location: m_material.php');
            }
        }
    }  
}
if(isset($_POST['edit_Mat'])) { //EDIT MATERIALS
    $m_id=$_POST['mat_id'];
    $m_code=$_POST['emat_code'];
    $m_desc=$_POST['emat_desc'];
    $m_unit=$_POST['emat_unit'];
    // $m_qty=$_POST['emat_qty'];
    $dept_id=$_POST['dept_id'];

    //check for duplicate material code
    $q_matcode="SELECT * FROM material WHERE Mat_Code='$m_code' AND Mat_Status=1 AND Mat_Id!=$m_id";
    $q_matcode_run=mysqli_query($connection,$q_matcode);
    if(mysqli_num_rows($q_matcode_run)>0){
        $_SESSION['status'] = "Material Code already exists";
        $_SESSION['status_code'] = "warning";
        header('Location: m_material.php');
    }
    else{
        $query="UPDATE material set Mat_Code='$m_code',Mat_Desc='$m_desc',Mat_Unit='$m_unit',Dept_id='$dept_id' WHERE Mat_Id='$m_id'";
        $query_run=mysqli_query($connection,$query);
        if($query_run){
            $_SESSION['status'] = "Material Updated";
            $_SESSION['status_code'] = "success";
            header('Location: m_material.php');
        }
        else{
            $_SESSION['status'] = "Error Updating Material";
            $_SESSION['status_code'] = "error";
            header('Location: m_material.php');
        }
    }
}
if(isset($_POST['delMat'])){ // DELETE MATERIAL
    $m_id=$_POST['m_id'];
    $query="UPDATE material set Mat_Status=0 where Mat_Id='$m_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }
}
if(isset($_POST["excel_mat_import"])){ //IMPORT 
    $post_id= $_POST['post_id'];
    if($_FILES['file']['name']){
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv'){
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            while($data = fgetcsv($handle)){
                $mat_code= mysqli_real_escape_string($connection, $data[0]);
                $qty = mysqli_real_escape_string($connection, $data[1]);
                $pref_brand = mysqli_real_escape_string($connection, $data[2]);
                //get mat_id
                $query ="SELECT Mat_Id FROM material WHERE Mat_Code='$mat_code' and Mat_Status=1 LIMIT 1";
                // echo $query;
                $query_run = mysqli_query($connection,$query);
                if(mysqli_num_rows($query_run)>0 ){     
                    $row = mysqli_fetch_assoc($query_run);
                    $mat_id = $row['Mat_Id']; 
                    $sql= "INSERT INTO material_post (Mat_Id,Mat_Post_Qty,Mat_Post_Brand,Post_Id,Mat_Post_Status) VALUES ('$mat_id','$qty','$pref_brand','$post_id',1)"; 
                    // echo $sql;
                    if ($connection->query($sql) === TRUE) {
                        $count=$count+1;
                        $_SESSION['status'] = "$count Materials Posted Added";
                        $_SESSION['status_code'] = "success";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    } else {
                        // echo "error";
                        $_SESSION['status'] = "Import Error";
                        $_SESSION['status_code'] = "error";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                }
            } 
            fclose($handle);
        }
    }
}
if(isset($_POST['reject'])){
    $post_id= $_POST['post_id'];
    $type=$_POST['type'];
    $quote_id=$_POST['q_id'];
    $q_update="UPDATE quote SET Quote_Approval=0 WHERE Quote_Id='$quote_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run)
    {
        $_SESSION['status'] = "Quote Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation.php?post_id='.$post_id.'&type='.$type);
    }
    else{
        $_SESSION['status'] = "Error Updating Quote Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation.php?post_id='.$post_id.'&type='.$type);
    }
     
}
if(isset($_POST['reject_m'])){
    $post_id= $_POST['post_id'];
    $quote_id=$_POST['q_id'];
    $q_update="UPDATE quote SET Quote_Approval=0 WHERE Quote_Id='$quote_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run)
    {
        $_SESSION['status'] = "Quote Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Quote Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
}
if(isset($_POST['approve_quote'])){
    $post_id= $_POST['post_id'];
    $quote_id=$_POST['q_id'];
    $comp_id=$_POST['comp_id'];
    //post name
    $q_post="SELECT * FROM post WHERE Post_Id='$post_id'";
    $q_post_run=mysqli_query($connection,$q_post);
    if(mysqli_num_rows($q_post_run)>0){
        $row_p=mysqli_fetch_assoc($q_post_run);
        $post_name=$row_p['Post_Name'];
    }
    $q_update="UPDATE quote SET Quote_Approval=1 WHERE Quote_Id='$quote_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run)
    {
        //Search USER_ID 
        $q_u="SELECT c.User_Id, u.USERNAME, c.Comp_Name FROM company as c LEFT JOIN users as u on u.USER_ID =c.User_Id WHERE c.Comp_Id='$comp_id'";
        $q_u_run=mysqli_query($connection,$q_u);
        if(mysqli_num_rows($q_u_run)>0){
            $row_u=mysqli_fetch_assoc($q_u_run);
            $user_id=$row_u['User_Id'];
            $company_name=$row_u['Comp_Name'];
            $q_notif_approved="INSERT INTO notification (User_Id,Not_Type,Not_Status,Quote_Id) VALUES ('$user_id','quote_approved',1,'$quote_id')";
            $q_notif_approved_run=mysqli_query($connection,$q_notif_approved);
            //EMAIL
            $to[]= $row_u['USERNAME'];
            $subject="Quote Approved: $post_name";
            $body="Dear $company_name,<br><br>
            Your quotation for $post_name, dated $date has been approved by the purchase department. We will soon inform you after we made the required purchase.<br><br>
            Sincerely,<br>EMT Electromechanical Works LLC <br><br><br>
            This is a system-generated email. Please do not reply.";
            sendmail($to,$subject,$body);
        }
        
        $_SESSION['status'] = "Quote Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Quote Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation.php?post_id='.$post_id);
    }
}
if(isset($_POST['approve_quote_m'])){
    $post_id= $_POST['post_id'];
    $quote_id=$_POST['q_id'];
    $comp_id=$_POST['comp_id'];
    //post name
    $q_post="SELECT * FROM post WHERE Post_Id='$post_id'";
    $q_post_run=mysqli_query($connection,$q_post);
    if(mysqli_num_rows($q_post_run)>0){
        $row_p=mysqli_fetch_assoc($q_post_run);
        $post_name=$row_p['Post_Name'];
    }
    date_default_timezone_set('Asia/Dubai');
    // date today
    $date = date('Y-m-d');
    $q_update="UPDATE quote SET Quote_Approval=1 WHERE Quote_Id='$quote_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        //Search USER_ID 
        $q_u="SELECT c.User_Id, u.USERNAME, c.Comp_Name FROM company as c LEFT JOIN users as u on u.USER_ID =c.User_Id WHERE c.Comp_Id='$comp_id'";
        $q_u_run=mysqli_query($connection,$q_u);
        if(mysqli_num_rows($q_u_run)>0){
            $row_u=mysqli_fetch_assoc($q_u_run);
            $user_id=$row_u['User_Id'];
            $company_name=$row_u['Comp_Name'];
            $q_notif_approved="INSERT INTO notification (User_Id,Not_Type,Not_Status,Quote_Id) VALUES ('$user_id','quote_approved',1,'$quote_id')";
            $q_notif_approved_run=mysqli_query($connection,$q_notif_approved);
            $to[]= $row_u['USERNAME'];
            $subject="Quote Approved: $post_name";
            $body="Dear $company_name,<br><br>
            Your quotation for $post_name, dated $date has been approved by the purchase department. We will soon inform you after we made the required purchase.<br><br>
            Sincerely,<br>EMT Electromechanical Works LLC <br><br><br>
            This is a system-generated email. Please do not reply.";
            sendmail($to,$subject,$body);
        }
        $_SESSION['status'] = "Quote Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Quote Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
     
}
if(isset($_POST['post_deact'])){ //DEACTIVATE POST /manpow.subcon
    $post_id=$_POST['post_id'];
    $type=$_POST['type'];
    $q_deact="UPDATE post SET Post_Status=2 WHERE Post_Id='$post_id'";
    $q_deact_run=mysqli_query($connection,$q_deact);
    if($q_deact_run){
        $_SESSION['status'] = "Post Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation.php?post_id='.$post_id.'&type='.$type);
    }
    else{
        $_SESSION['status'] = "Error Updating Post Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation.php?post_id='.$post_id.'&type='.$type);
    }
}
if(isset($_POST['post_deact_m'])){//material
    $post_id=$_POST['post_id'];
    $type=$_POST['type'];
    $q_deact="UPDATE post SET Post_Status=2 WHERE Post_Id='$post_id'";
    $q_deact_run=mysqli_query($connection,$q_deact);
    if($q_deact_run){
        $_SESSION['status'] = "Post Status Updated";
        $_SESSION['status_code'] = "success";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Post Status";
        $_SESSION['status_code'] = "error";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
}
if(isset($_POST['add_mat_grp'])){ // ADDITONAL GROUP - EDIT MATERIAL POSTS
    $post_id=$_POST['post_id'];
    $grpName=$_POST['grpName'];
    $grpLoc=$_POST['grpLocation'];
    // INSERT GROUP 
    $q_grp_insert="INSERT INTO material_post_group (MP_Grp_Name,MP_Grp_Location,Post_Id,MP_Grp_Status) VALUES ('$grpName','$grpLoc','$post_id',1)";
    if($connection->query($q_grp_insert)===TRUE){
        $grp_id=$connection->insert_id; 
        if(isset($_POST['mat_id']))
        { 
            $data = array( 
                'mat_id' => $_POST['mat_id'],
                'mat_ref_code' => $_POST['mat_ref_code'],
                'mat_qty' => $_POST['mat_qty'],
                'mat_unit' => $_POST['mat_unit'],
                'mat_pref_brand' => $_POST['mat_pref_brand'],
                'mat_capacity' => $_POST['mat_capacity'],
                'mat_esp' => $_POST['mat_esp'],
                'mat_location' => $_POST['mat_location']
                );
            $count = count($_POST['mat_id']);
            for ($i=0; $i < $count; $i++) { 
                $sql ="INSERT INTO material_post (Mat_Id,Mat_Post_Ref_Code,Mat_Post_Qty,Mat_Post_Brand,Mat_Post_Capacity,Mat_Post_Esp,Mat_Post_Location,Mat_Post_Unit,Post_Id,Mat_Post_Status,MP_Grp_Id) VALUES ('{$_POST['mat_id'][$i]}','{$_POST['mat_ref_code'][$i]}','{$_POST['mat_qty'][$i]}','{$_POST['mat_pref_brand'][$i]}','{$_POST['mat_capacity'][$i]}','{$_POST['mat_esp'][$i]}','{$_POST['mat_location'][$i]}','{$_POST['mat_unit'][$i]}',$post_id,1,$grp_id)";
                $connection->query($sql);
                if($sql){
                    $_SESSION['status'] = "Group Added!";
                    $_SESSION['status_code'] = "success";
                    header('Location: edit_mat_post.php?post_id='.$post_id);
                }
                else{
                    $_SESSION['status'] = "Error Addign Group";
                    $_SESSION['status_code'] = "error";
                    header('Location: edit_mat_post.php?post_id='.$post_id);
                }
            }
        }
        else{
            $_SESSION['status'] = "Missing Post Details";
            $_SESSION['status_code'] = "warning";
            header('Location: edit_mat_post.php?post_id='.$post_id);
        }
    }
    //2ND GROUP
    if(isset($_POST['grpName1'])){
        $grpName1=$_POST['grpName1'];
        $grpLoc1=$_POST['grpLocation1'];
        //INSERT 2ND GROUP
        $q_grp_insert1="INSERT INTO material_post_group (MP_Grp_Name,MP_Grp_Location,Post_Id,MP_Grp_Status) VALUES ('$grpName1','$grpLoc1','$post_id',1)";
        if($connection->query($q_grp_insert1)===TRUE){
            $grp_id1=$connection->insert_id; 
            if(isset($_POST['mat_id1']))
            { 
                $data = array( 
                    'mat_id1' => $_POST['mat_id1'],
                    'mat_ref_code1' => $_POST['mat_ref_code1'],
                    'mat_qty1' => $_POST['mat_qty1'],
                    'mat_unit1' => $_POST['mat_unit1'],
                    'mat_pref_brand1' => $_POST['mat_pref_brand1'],
                    'mat_capacity1' => $_POST['mat_capacity1'],
                    'mat_esp1' => $_POST['mat_esp1'],
                    'mat_location1' => $_POST['mat_location1']
                    );
                $count1 = count($_POST['mat_id1']);
                for ($i=0; $i < $count1; $i++) { 
                    $sql1 ="INSERT INTO material_post (Mat_Id,Mat_Post_Ref_Code,Mat_Post_Qty,Mat_Post_Brand,Mat_Post_Capacity,Mat_Post_Esp,Mat_Post_Location,Mat_Post_Unit,Post_Id,Mat_Post_Status,MP_Grp_Id) VALUES ('{$_POST['mat_id1'][$i]}','{$_POST['mat_ref_code1'][$i]}','{$_POST['mat_qty1'][$i]}','{$_POST['mat_pref_brand1'][$i]}','{$_POST['mat_capacity1'][$i]}','{$_POST['mat_esp1'][$i]}','{$_POST['mat_location1'][$i]}','{$_POST['mat_unit1'][$i]}',$post_id,1,$grp_id1)";
                    // echo $sql;
                    $connection->query($sql1);
                    if($sql1){
                        $_SESSION['status'] = "Groups Added";
                        $_SESSION['status_code'] = "success";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                    else{
                        $_SESSION['status'] = "Error Adding Groups";
                        $_SESSION['status_code'] = "error";
                        header('Location: edit_mat_post.php?post_id='.$post_id);
                    }
                }
            }
            else{
                $_SESSION['status'] = "Missing Post Details";
                $_SESSION['status_code'] = "warning";
                header('Location: edit_mat_post.php?post_id='.$post_id);
            }
        }
    }
}
if(isset($_POST['del_grp'])){
    $post_id=$_POST['post_id'];
    $grp_id=$_POST['del_grp_id'];

    $del_group="UPDATE material_post_group SET MP_Grp_Status=0 WHERE MP_Grp_Id='$grp_id'";
    $del_group_run=mysqli_query($connection,$del_group);
    if($del_group_run){
        $_SESSION['status'] = "Group Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: edit_mat_post.php?post_id='.$post_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Group";
        $_SESSION['status_code'] = "error";
        header('Location: edit_mat_post.php?post_id='.$post_id);
    }
}
//add new username
if(isset($_POST['addPurchase'])){
    $username = $_POST['username']; //email
    $email=$_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);
    $usertype = $_POST['usertype'];

    $email_query = "SELECT * FROM users WHERE USERNAME='$username' OR USER_EMAIL='$email' AND USER_STATUS=1 AND  USERTYPE='purchase'";
    $email_query_run = mysqli_query($connection, $email_query);

    if(mysqli_num_rows($email_query_run) > 0){
        $_SESSION['status'] = "Username or Email Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: purchase_user.php');
    }
    else
    {
        if($username =="" or $password =="" or $usertype ==""){
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: purchase_user.php');
        }
        else{
            // confirming the password
            if($password === $cpassword){
                // INSERT QUERY
                $query = "INSERT INTO users (USERNAME,USER_EMAIL,USER_PASSWORD,USERTYPE,USER_STATUS) VALUES ('$username','$email','$password','$usertype', 1)";
                //  name declared in USER TABLE
                $query_run = mysqli_query($connection, $query);
               if($query_run){
                    // success
                    $_SESSION['status'] = "Admin Profile Added";
                    $_SESSION['status_code'] = "success";
                    header('Location: purchase_user.php');
                }
                else{
                    //error
                    $_SESSION['status'] = "Admin Profile NOT Added";
                    $_SESSION['status_code'] = "error";
                    header('Location: purchase_user.php');
                }
            }
            else{
                $_SESSION['status'] = "Passord does not match";
                $_SESSION['status_code'] = "warning";
                header('Location: purchase_user.php');
            }
        }
    }
}
// UPDATING USER
if(isset($_POST['updatebtn'])){
    // PASSING VARIABLE
    $id = $_POST['user_update_id'];
    $username = $_POST['edit_username'];
    $email = $_POST['edit_email'];
    $password = md5($_POST['edit_password']);
    // $usertype = $_POST ['update_usertype'];
    $email_query = "SELECT * FROM users WHERE USERNAME='$username' WHERE USERTYPE='purchase' OR USERTYPE='company'";
    $email_query_run = mysqli_query($connection, $email_query);
    if($username =="" or $password ==""){
        $_SESSION['status'] = "Fill out the form";
        $_SESSION['status_code'] = "warning";
        header('Location: purchase_user.php');
    }
    else{
        $query = "UPDATE users SET USERNAME='$username', USER_EMAIL='$email', USER_PASSWORD='$password' WHERE USER_ID='$id'";
        $query_run = mysqli_query($connection, $query);
        if($query_run){
            $_SESSION['status'] = "Your Data is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: purchase_user.php');
        }
        else{
            $_SESSION['status'] = "Your Data is NOT Updated";
            $_SESSION['status_code'] = "error";
            header('Location: purchase_user.php');
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
        header('Location: purchase_user.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: purchase_user.php');
    }
}
// ADD EMAIL GROUP
if(isset($_POST['addGroup'])){
    $grp_name=$_POST['grpName'];
    $grp_desc=$_POST['desc'];
    //
    $q_grp="INSERT INTO email_group (Email_Grp_Name,Email_Grp_Desc,Email_Grp_Status) VALUES ('$grp_name','$grp_desc',1)";
    if($connection->query($q_grp)===TRUE)
    {          
        $l_id = $connection->insert_id; 
        //INSERT EMAILS
        $data = array( 
            'comps' => $_POST['comps']
            );
        $count = count($_POST['comps']);
        for ($i=0; $i < $count; $i++) { 
            if(is_numeric($_POST['comps'][$i])){ // save user_id
                $q_email="INSERT INTO email (User_Id,Email_Status,Email_Grp_Id) VALUES ('{$_POST['comps'][$i]}',1,$l_id)";
                $q_email_run=mysqli_query($connection,$q_email);
            }
            else{ //save email
                if(($_POST['comps'][$i])==''){}
                else{
                    $q_email="INSERT INTO email (Email,Email_Status,Email_Grp_Id) VALUES ('{$_POST['comps'][$i]}',1,$l_id)";
                    $q_email_run=mysqli_query($connection,$q_email);
                }
            }
        }
        if($q_email_run){
            $_SESSION['status'] = "Group Added";
            $_SESSION['status_code'] = "success";
            header('Location: email_grps.php');
        }
        else{
            $_SESSION['status'] = "Error Adding Group";
            $_SESSION['status_code'] = "error";
            header('Location: email_grps.php');
        }
    }
}
// DELETE EMAIL GROUP
if(isset($_POST['del_email_grp'])){
    $grp_id=$_POST['grp_id'];
    // update grp status
    $q_del="UPDATE email_group SET Email_Grp_Status=0 WHERE Email_Grp_Id='$grp_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Group Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: email_grps.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Group";
        $_SESSION['status_code'] = "error";
        header('Location: email_grps.php');
    }
}
if(isset($_POST['update_grp'])){
    $grp_id=$_POST['grp_id'];
    $grp_name=$_POST['grp_name'];
    $grp_desc=$_POST['grp_desc'];
    // update grp status
    $q_update="UPDATE email_group SET Email_Grp_Name='$grp_name', Email_Grp_Desc='$grp_desc' WHERE Email_Grp_Id='$grp_id'";
    $q_update_run=mysqli_query($connection,$q_update);
    if($q_update_run){
        
        if($_POST['other']){
            $data = array( 
                'other' => $_POST['other']
            );
            $count = count($_POST['other']);
            for ($i=0; $i < $count; $i++) { 
                $q_email="INSERT INTO email (Email,Email_Status,Email_Grp_Id) VALUES ('{$_POST['other'][$i]}',1,$grp_id)";
                $connection->query($q_email);
            }
        }
        if($_POST['comp_email']){
            $data = array( 
                'comp_email' => $_POST['comp_email']
            );
            $countc = count($_POST['comp_email']);
            for ($i=0; $i < $countc; $i++) { 
                $q_email="INSERT INTO email (User_Id,Email_Status,Email_Grp_Id) VALUES ('{$_POST['comp_email'][$i]}',1,$grp_id)";
                $connection->query($q_email);
            }
        }
        if($q_email){
            $_SESSION['status'] = "Group Updated";
            $_SESSION['status_code'] = "success";
            header('Location: email_edit_grp.php?grp_id='.$grp_id);
        }
        else{
            $_SESSION['status'] = "Error Updating Group";
            $_SESSION['status_code'] = "error";
            header('Location: email_edit_grp.php?grp_id='.$grp_id);
        }
        $_SESSION['status'] = "Group Updated";
        $_SESSION['status_code'] = "success";
        header('Location: email_edit_grp.php?grp_id='.$grp_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Group";
        $_SESSION['status_code'] = "error";
        header('Location: email_edit_grp.php?grp_id='.$grp_id);
    }
}
// delete single email 
if(isset($_POST['del_email'])){
    $grp_id=$_POST['grp_id_del'];
    $email_id=$_POST['email_id'];
    $q_del="UPDATE email SET Email_Status=0 WHERE Email_Id='$email_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        header('Location: email_edit_grp.php?grp_id='.$grp_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Email";
        $_SESSION['status_code'] = "error";
        header('Location: email_edit_grp.php?grp_id='.$grp_id);
    }
}
//company options ajax
if(isset($_POST['comp'])){
    $q_comp="SELECT Comp_Name, User_Id FROM company WHERE Comp_Status=1 AND Comp_Approval=1";
    $q_comp_run=mysqli_query($connection,$q_comp);
    if(mysqli_num_rows($q_comp_run)>0){
        while($rowc=mysqli_fetch_assoc($q_comp_run)){
            $comp_name=$rowc['Comp_Name'];
            $user_id=$rowc['User_Id'];
            echo '<option value="'.$user_id.'">'.$comp_name.'</option>';
        }
    }
}
//company options ajax w/ grp id
if(isset($_POST['grp_id'])){
    $grp_id=$_POST['grp_id'];
    //select existing added companies
    $q_user_id="SELECT *  FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1";
    $q_user_id_run=mysqli_query($connection,$q_user_id);
    if(mysqli_num_rows($q_user_id_run)>0){
        $user_id_arr=[];
        while($row=mysqli_fetch_assoc($q_user_id_run)){
            $user_id_arr[]=$row['User_Id'];
        }
        $user_ids=implode("', '",$user_id_arr);
    }
    $q_comp="SELECT Comp_Name, User_Id FROM company WHERE Comp_Status=1 AND Comp_Approval=1 AND User_Id NOT IN ('$user_ids')";
    $q_comp_run=mysqli_query($connection,$q_comp);
    if(mysqli_num_rows($q_comp_run)>0){
        while($rowc=mysqli_fetch_assoc($q_comp_run)){
            $comp_name=$rowc['Comp_Name'];
            $user_id=$rowc['User_Id'];
            echo '<option value="'.$user_id.'">'.$comp_name.'</option>';
        }
    }
}
//company email grps
if(isset($_POST['emailGrp'])){
    $q_grps="SELECT * FROM email_group WHERE Email_Grp_Status=1 ORDER BY Email_Grp_Name ASC";
    $q_grps_run=mysqli_query($connection,$q_grps);
    if(mysqli_num_rows($q_grps_run)>0){
        while($row_g=mysqli_fetch_assoc($q_grps_run)){
            $grp_id=$row_g['Email_Grp_Id'];
            $grp_name=$row_g['Email_Grp_Name'];
            echo '<option value="'.$grp_id.'">'.$grp_name.'</option>';
        }   
    } 
}
if(isset($_POST['sendEmail'])){
    $username=$_SESSION['USERNAME']; //purchase department username
    $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
    $query_run_user = mysqli_query($connection, $query_user);
    if(mysqli_num_rows($query_run_user)==1){
        $row=mysqli_fetch_assoc($query_run_user);
        $user_email=$row['USER_EMAIL'];
    }
    $username=ucfirst($username);
    $post_id=$_POST['post_id'];
    $post_id_mdetails=$post_id;
    $post_details=postMatDetails($post_id_mdetails);
    $post_name=$_POST['post_name'];
    $grpNames=$_POST['emalGrp'];
    $cc=$_POST['ccEmail'];
    $attachment=$_FILES['attachment'];
    $bccGroup=$_POST['bccEmail'];
    foreach ($grpNames as $grp_id){
        //search emails from the group
        //check if its grp id or custom email
        if(is_numeric($grp_id)){
            $grp_emails="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1 ";
            $grp_emails_run=mysqli_query($connection,$grp_emails);
            if(mysqli_num_rows($grp_emails_run)>0){
                while($row_grp=mysqli_fetch_assoc($grp_emails_run)){
                    $user_id=$row_grp['User_Id'];
                    if($user_id){ //search for username
                        $q_user="SELECT u.USERNAME, c.Comp_Name  FROM users as u
                                LEFT JOIN company as c on c.User_Id=u.USER_ID
                                WHERE u.USER_ID='$user_id'  AND c.Comp_Approval='1' limit 1 ";
                        $q_user_run=mysqli_query($connection,$q_user);
                        if(mysqli_num_rows($q_user_run)==1){
                            $rown=mysqli_fetch_assoc($q_user_run);
                            $username=$rown['USERNAME'];
                            if($username){
                                $to[]=$username;
                            }
                        }
                    }
                    else{
                        $email=$row_grp['Email'];
                        if($email){
                            $to[]=$email;
                        }
                    }
                }
            }
        }
        else{// other email
            $to[]=$grp_id;
        }
    }
    foreach ($bccGroup as $bgrp_id){
        //search emails from the group
        //check if its grp id or custom email
        if(is_numeric($bgrp_id)){
            $bgrp_emails="SELECT * FROM email WHERE Email_Grp_Id='$bgrp_id' AND Email_Status=1 ";
            $bgrp_emails_run=mysqli_query($connection,$bgrp_emails);
            if(mysqli_num_rows($bgrp_emails_run)>0){
                while($row_grpb=mysqli_fetch_assoc($bgrp_emails_run)){
                    $user_idb=$row_grpb['User_Id'];
                    if($user_idb){ //search for username
                        $q_userb="SELECT u.USERNAME, c.Comp_Name FROM users as u
                                LEFT JOIN company as c on c.User_Id=u.USER_ID
                                WHERE u.USER_ID='$user_idb'  AND c.Comp_Approval='1' limit 1 ";
                        $q_userb_run=mysqli_query($connection,$q_userb);
                        if(mysqli_num_rows($q_userb_run)==1){
                            $rownb=mysqli_fetch_assoc($q_userb_run);
                            $usernameb=$rownb['USERNAME'];
                            $comp_name=$rownb['Comp_Name'];
                            if($usernameb){
                                $bcc[]=$usernameb;
                            }
                        }
                    }
                    else{
                        $email=$row_grpb['Email'];
                        if($email){
                            $bcc[]=$email;
                        }
                    }
                }
            }
        }
        else{// other email
            $bcc[]=$bgrp_id;
        }
    }
    // var_dump($to);
    $subject="EMT Post Alert: $post_name";
    $body="Dear Sir/Madam,<br><br>Good day! We would like to update you with our new post. Kindly review and apply for quotation.<br> For queries & clarifications please contact the undersigned.<br><br><br>$post_details <br><span style='color:red; font-weight:bold;'>Your immediate action will be appreciated. <br></span>Thank you for using our services.<br><br>Login or Register through this link:  https://emtdubai.ae/EMT/login.php<br><br><br>Thanks & Regards,<br><br><br><span style='color:red; font-weight:bold;'>$username </span><br>Purchase Officer<br><span style='color:blue; '>Email: $user_email | www.emtdubai.ae </span><br><br>";
    if(sendmail($to,$subject,$body,$cc,$bcc,$attachment)){
        $_SESSION['status'] = "Email Send!";
        $_SESSION['status_code'] = "success";
        header('Location: p_material_post.php');
    }
    else{
        $_SESSION['status'] = "Sending Failed";
        $_SESSION['status_code'] = "error";
        header('Location: p_material_post.php');
    }
}
if(isset($_POST['sendEmailJP'])){
    $username=$_SESSION['USERNAME']; //purchase department username
    $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
    $query_run_user = mysqli_query($connection, $query_user);
    if(mysqli_num_rows($query_run_user)==1){
        $row=mysqli_fetch_assoc($query_run_user);
        $user_email=$row['USER_EMAIL'];
    }
    $username=ucfirst($username);
    $post_id=$_POST['post_id'];
    $post_id_details=$post_id;
    $post_details=postDetails($post_id_details);
    $grpNames=$_POST['emalGrp'];
    $cc=$_POST['ccEmail'];
    $attachment=$_FILES['attachment'];
    foreach ($grpNames as $grp_id){
        // echo $grp_id.'<br>';
        // search emails from the group
        // check if its grp id or custom email
        if(is_numeric($grp_id)){
            $grp_emails="SELECT * FROM email WHERE Email_Grp_Id='$grp_id' AND Email_Status=1 ";
            $grp_emails_run=mysqli_query($connection,$grp_emails);
            if(mysqli_num_rows($grp_emails_run)>0){
                while($row_grp=mysqli_fetch_assoc($grp_emails_run)){
                    $user_id=$row_grp['User_Id'];
                    if($user_id){ //search for username
                        $q_user="SELECT u.USERNAME, c.Comp_Name
                                FROM users as u
                                LEFT JOIN company as c on c.User_Id=u.USER_ID
                                WHERE u.USER_ID='$user_id'  AND c.Comp_Approval='1' limit 1 ";
                        $q_user_run=mysqli_query($connection,$q_user);
                        if(mysqli_num_rows($q_user_run)==1){
                            $rown=mysqli_fetch_assoc($q_user_run);
                            $username=$rown['USERNAME'];
                            // echo $username.'<br>';
                            $comp_name=$rown['Comp_Name'];
                            if($username){
                                $to[]=$username;
                            }
                        }
                    }
                    else{
                        $email=$row_grp['Email'];
                        if($email){
                            $to[]=$email;
                        }
                    }
                }
            }
        }
        else{// other email
            $to[]=$grp_id;
        }
        $subject="EMT Post Alert: $post_name";
        $body="Dear Sir/Madam,<br><br>Good day! We would like to update you with our new post. Kindly review and apply for quotation.<br><br><br>$post_details <br><span style='color:red; font-weight:bold;'>Your immediate action will be appreciated. <br></span>Thank you for using our services.<br><br>Login or Register through this link:  https://emtdubai.ae/EMT/login.php<br><br><br>Thanks & Regards,<br><br><br><span style='color:red; font-weight:bold;'>$username </span><br>Purchase Officer<br><span style='color:blue; '>Email: $user_email | www.emtdubai.ae </span><br><br>";
        if(sendmail($to,$subject,$body,$cc,$attachment)){
            $_SESSION['status'] = "Email Send!";
            $_SESSION['status_code'] = "success";
            header('Location: p_job_post.php');
        }
        else{
            $_SESSION['status'] = "Sending Failed";
            $_SESSION['status_code'] = "error";
            header('Location: p_job_post.php');
        }
    }
}
if(isset($_POST['appMatQ'])){
    // echo ':v';
    $post_id=$_POST['post_id'];
    date_default_timezone_set('Asia/Dubai');
    // date today
    $date = date('Y-m-d');
    if($_POST['qd_id']){
        $data = array( 
            'comp_id' => $_POST['comp_id'],
            'qd_id' => $_POST['qd_id']
        );
        $count = count($_POST['qd_id']);
        if($count>=1){
            for ($i=0; $i < $count; $i++) { //update approval status
                $c_arr[]=$_POST['comp_id'][$i];
                $qd_arr[]=$_POST['qd_id'][$i];
                $sql ="UPDATE quote_detail SET Quote_Detail_Approval=1 WHERE Quote_Detail_Id='{$_POST['qd_id'][$i]}'";
                $connection->query($sql);
            }
            //post name
            $q_post="SELECT * FROM post WHERE Post_Id='$post_id'";
            $q_post_run=mysqli_query($connection,$q_post);
            if(mysqli_num_rows($q_post_run)>0){
                $row_p=mysqli_fetch_assoc($q_post_run);
                $post_name=$row_p['Post_Name'];
            }
            $result = array_unique($c_arr);
            $qd_ids=implode("', '",$qd_arr);
            foreach ($result as $ccomp_id) {
                //get company name && user iddd             /COMPANY DETAILS
                $q_comp="SELECT * FROM company as c 
                        LEFT JOIN users as u on u.USER_ID=c.User_Id
                        WHERE c.Comp_Id='$ccomp_id'";
                $q_comp_run=mysqli_query($connection,$q_comp);
                if(mysqli_num_rows($q_comp_run)>0){
                    $row_c=mysqli_fetch_assoc($q_comp_run);
                    $ccomp_name=$row_c['Comp_Name'];
                    $to[]=$row_c['USERNAME'];//email
                    $user_id=$row_c['USER_ID'];
                }
                //MESSAGE CONTENT
                $q_qd="SELECT * FROM quote_detail as qd
                        LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                        LEFT JOIN material_post as mp on mp.Mat_Post_Id = qd.Mat_Post_Id
                        WHERE Quote_Detail_Id IN ('$qd_ids') AND q.Comp_Id='$ccomp_id'";
                $q_qd_run=mysqli_query($connection,$q_qd);
                if(mysqli_num_rows($q_qd_run)>0){
                    $tbl='';
                    $tbl.='<table border=1>
                        <tr class="font-weight-bold">
                            <td width="65%" >Material Description</td>
                            <td>Quantity</td>
                        </tr>';
                    while($row_qd=mysqli_fetch_assoc($q_qd_run)){
                        $quote_id=$row_qd['Quote_Id'];
                        $qqd_id=$row_qd['Quote_Detail_Id'];
                        $qty=$row_qd['Mat_Post_Qty'];
                        $mat_id=$row_qd['Mat_Id'];//material name
                        $mat_ref_code=$row_qd['Mat_Post_Ref_Code'];
                        if(is_numeric($mat_id)){
                            $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                            $mat_q_run=mysqli_query($connection,$mat_q);
                            if(mysqli_num_rows($mat_q_run)>0){
                                $row_m=mysqli_fetch_assoc($mat_q_run);
                                $mat_desc=$row_m['Mat_Desc'];
                                $mat_unit=$row_m['Mat_Unit'];
                            }
                            else{
                                $mat_desc=$row_p['Mat_Id'];
                                $mat_unit=$row_p['Mat_Post_Unit'];
                            }
                        }
                        else{
                            $mat_desc=$mat_id;
                        }
                        $tbl.='<tr>
                                <td>'.$mat_desc.' '.$mat_unit.'</td>
                                <td>'.$qty.'</td>
                            </tr>';
                    }
                    $tbl.='</table>';
                }
                
                $subject="Quote Approved: $post_name";
                $body="Dear $ccomp_name,<br><br>
                        Your quotation for $post_name, has been approved by the purchase department. The following materials approved listed below.<br>
                        Materials:<br>$tbl
                        <br><br>
                        We will soon inform you after we made the required purchase.<br><br>
                        Sincerely,<br>EMT Electromechanical Works LLC <br><br><br>
                        This is a system-generated email. Please do not reply.";
                if(sendmail($to,$subject,$body)){
                    $_SESSION['status'] = "Quote Status Updated";
                    $_SESSION['status_code'] = "success";
                    header('Location: quotation_mat.php?post_id='.$post_id);
                }
                else{
                    $_SESSION['status'] = "Error Updating Quote Status";
                    $_SESSION['status_code'] = "error";
                    header('Location: quotation_mat.php?post_id='.$post_id);
                }
            }
        }
        else{
            $_SESSION['status'] = "Error Updating Quote Status";
            $_SESSION['status_code'] = "error";
            header('Location: quotation_mat.php?post_id='.$post_id);
        }
    }
    else{
        $_SESSION['status'] = "No Materials Selected";
        $_SESSION['status_code'] = "warning";
        header('Location: quotation_mat.php?post_id='.$post_id);
    }
}
if(isset($_POST['removeComp'])){// remove from group
    $email_id=$_POST['email_id'];
    $q_del="UPDATE email SET Email_Status=0 WHERE Email_Id='$email_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Category Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: approved_company.php');
    }
    else{
        $_SESSION['status'] = "Error Removing Category";
        $_SESSION['status_code'] = "error";
        header('Location: approved_company.php');
        
    }
}
if(isset($_POST['removeCompListed'])){// remove from group
    $email_id=$_POST['email_id'];
    $q_del="UPDATE email SET Email_Status=0 WHERE Email_Id='$email_id'";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Category Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: a_comp_approval.php');
    }
    else{
        $_SESSION['status'] = "Error Removing Category";
        $_SESSION['status_code'] = "error";
        header('Location: a_comp_approval.php');
        
    }
}
if(isset($_POST['addGroupc'])){// add to group
    $user_id=$_POST['user_id'];
    $data = array(
        'group_id' =>$_POST['group_id']
    );
    $count = count($_POST['group_id']);
    for ($i=0; $i < $count; $i++) { 
        $q_email="INSERT INTO email (User_Id,Email_Status,Email_Grp_Id) VALUES ($user_id,1,'{$_POST['group_id'][$i]}')";
        $q_email_run=mysqli_query($connection,$q_email);
    }
    if($q_email_run){
        $_SESSION['status'] = "Group Added";
        $_SESSION['status_code'] = "success";
        header('Location: approved_company.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Group";
        $_SESSION['status_code'] = "error";
        header('Location: approved_company.php');
    }
}
if(isset($_POST['addDept'])){// add to group
    $location=$_POST['page'];
    $comp_id=$_POST['comp_id'];
    $data = array(
        'dept_id' =>$_POST['dept_id']
    );
    $count = count($_POST['dept_id']);
    for ($i=0; $i < $count; $i++) { 
        $dept="INSERT INTO comp_department (Comp_Id,Dept_Id,Comp_Dept_Status) VALUES ('$comp_id','{$_POST['dept_id'][$i]}',1)";
        $dept_run=mysqli_query($connection,$dept);
    }
    if($dept_run){
        $_SESSION['status'] = "Department Added";
        $_SESSION['status_code'] = "success";
        header('Location: '.$location.'.php');
    }
    else{
        $_SESSION['status'] = "Error Department Group";
        $_SESSION['status_code'] = "error";
        header('Location: '.$location.'.php');
    }
}
if(isset($_POST['removeCd'])){
    $cd_ids=$_POST['chkIds'];
    $location=$_POST['location'];
    $q_del="UPDATE comp_department SET Comp_Dept_Status=0 WHERE Comp_Dept_Id IN ($cd_ids)";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Department Removed";
        $_SESSION['status_code'] = "success";
        header('Location: '.$location.'.php');
    }
    else{
        $_SESSION['status'] = "Error Removing Department";
        $_SESSION['status_code'] = "error";
        header('Location: '.$location.'.php');
    }
}
if(isset($_POST['removeGrps'])){
    $cd_ids=$_POST['chkIds'];
    $location=$_POST['location'];
    $q_del="UPDATE email SET Email_Status=0 WHERE Email_Id IN ($cd_ids)";
    $q_del_run=mysqli_query($connection,$q_del);
    if($q_del_run){
        $_SESSION['status'] = "Group Removed";
        $_SESSION['status_code'] = "success";
        header('Location: '.$location.'.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Group";
        $_SESSION['status_code'] = "error";
        header('Location: '.$location.'.php');
    }
}
if(isset($_POST['approve'])){
    $comp_id=$_POST['comp_id'];
    $user_id=$_POST['user_id'];
    $today=date('Y-m-d');
    $sql="UPDATE company SET Comp_Approval=1, Comp_Approved_Date='$today' WHERE Comp_Id='$comp_id'";
    $sql_run=mysqli_query($connection,$sql);
    if($sql_run){
        $data1 = array(
            'dept_id' =>$_POST['dept_id']
        );
        $count1 = count($_POST['dept_id']);
        for ($i1=0; $i1 < $count1; $i1++) { 
            $dept="INSERT INTO comp_department (Comp_Id,Dept_Id,Comp_Dept_Status) VALUES ('$comp_id','{$_POST['dept_id'][$i1]}',1)";
            $dept_run=mysqli_query($connection,$dept);
        }
        $q_update="UPDATE users SET USER_STATUS=1 WHERE USER_ID='$user_id'";
        $q_update_run=mysqli_query($connection,$q_update);
        if($q_update_run){
            //SEND email to company
            $q_comp_details="SELECT Comp_Name, Comp_Contact_Email, Comp_Manager_Email 
                            FROM company WHERE Comp_Id='$comp_id' LIMIT 1";
            $q_comp_details_run=mysqli_query($connection,$q_comp_details);
            if(mysqli_num_rows($q_comp_details_run)>0){
                $q_user="SELECT users.USER_ID, users.USERNAME, c.Comp_Name
                        FROM users 
                        LEFT JOIN company as c ON c.User_Id=users.USER_ID
                        where c.Comp_Id='$comp_id'";
                $q_user_run=mysqli_query($connection,$q_user);
                if(mysqli_num_rows($q_user_run)>0){
                    $row_u=mysqli_fetch_assoc($q_user_run);
                    $username=$row_u['USERNAME'];
                    $user_id = $row_u['USER_ID'];
                }
                $row_c=mysqli_fetch_assoc($q_comp_details_run);
                $company_name=$row_c['Comp_Name'];
                $cMail=$row_c['Comp_Contact_Email'];
                $mMail=$row_c['Comp_Manager_Email'];
                $subject="Company Profile Application Approved : EMT Online Registration";
                $body="<b>Hello $company_name,</b><br><br>
                Your Company Profile was approved!<br><br>
                You can now login with your enrolled username and password. Login through this link:https://emtdubai.ae/EMT/login.php <br><br>
                Sincerely,<br>
                EMT Registration System<br><br><br><br>
                This is a system-generated email. Please do not reply.";
                if($username){
                    $to[]=$username;
                    $subject="Company Profile Application Approved : EMT Online Registration";
                    $body="<b>Hello $company_name,</b><br><br>Your Company Profile was approved!<br><br> You can now login with your enrolled username and password. Login through this link:https://emtdubai.ae/EMT/login.php <br><br>Sincerely,<br> EMT Registration System<br><br><br><br>This is a system-generated email. Please do not reply.";
                    if(sendmail($to,$subject,$body)){
                        $_SESSION['status'] = "Company Approved";
                        $_SESSION['status_code'] = "success";
                        header('Location: a_comp_approval.php');
                    }
                    else{
                        $_SESSION['status'] = "Error Approving Company";
                        $_SESSION['status_code'] = "error";
                        header('Location: a_comp_approval.php');
                    }
                }
            }
            $data = array(
                'group_id' =>$_POST['group_id']
            );
            $count = count($_POST['group_id']);
            if($count>0){
                for ($i=0; $i < $count; $i++){ 
                    $q_email="INSERT INTO email (User_Id,Email_Status,Email_Grp_Id) VALUES ($user_id,1,'{$_POST['group_id'][$i]}')";
                    $q_email_run=mysqli_query($connection,$q_email);
                    if($q_email_run){
                        $_SESSION['status'] = "Company Approved";
                        $_SESSION['status_code'] = "success";
                        header('Location: a_comp_approval.php');
                    }
                    else{
                        $_SESSION['status'] = "Error Approving Company";
                        $_SESSION['status_code'] = "error";
                        header('Location: a_comp_approval.php');
                    }
                }
            }
            // success
            $_SESSION['status'] = "Company Approved";
            $_SESSION['status_code'] = "success";
            header('Location: a_comp_approval.php');
        }
        else{
            //error
            $_SESSION['status'] = "Error Approving Company";
            $_SESSION['status_code'] = "error";
            header('Location: a_comp_approval.php');
        }
    }
    else{
        //error
        $_SESSION['status'] = "Error Approving Company";
        $_SESSION['status_code'] = "error";
        header('Location: a_comp_approval.php');
    }
}
?>