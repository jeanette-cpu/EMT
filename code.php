<?php
include('security.php');

// INSERTING USERS
if(isset($_POST['registerbtn']))
{
    // declaring variables
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['confirmpassword']);
    $usertype = $_POST['usertype'];
    $userstatus = $_POST['userstatus'];

    $email_query = "SELECT * FROM users WHERE USERNAME='$username'";
    $email_query_run = mysqli_query($connection, $email_query);

    if(mysqli_num_rows($email_query_run) > 0)
    {
        $_SESSION['status'] = "Username Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: user.php');
    }
    else
    {
        if($username =="" or $password =="" or $usertype =="")
        {
            $_SESSION['status'] = "Fill out the form";
            $_SESSION['status_code'] = "warning";
            header('Location: user.php');
        }
        else
        {
            // confirming the password
            if($password === $cpassword)
            {
                // INSERT QUERY
                $query = "INSERT INTO users (USERNAME,USER_EMAIL,USER_PASSWORD,USERTYPE,USER_STATUS) VALUES ('$username','$email','$password','$usertype', '$userstatus')";
                //  name declared in USER TABLE
                $query_run = mysqli_query($connection, $query);
               if($query_run)
                   {
                       // success
                       $_SESSION['status'] = "Admin Profile Added";
                       $_SESSION['status_code'] = "success";
                       header('Location: user.php');
                   }
                   else
                   {
                       //error
                       $_SESSION['status'] = "Admin Profile NOT Added";
                       $_SESSION['status_code'] = "error";
                       header('Location: user.php');
                   }
            }
               else
               {
                   $_SESSION['status'] = "Passord does not match";
                   $_SESSION['status_code'] = "warning";
                   header('Location: user.php');
               }
        }
    }
}
// UPDATING USER 
if(isset($_POST['updatebtn'])){
    // PASSING VARIABLE
    $id = $_POST['edit_id'];
    $username = $_POST['edit_username'];
    $email = $_POST['edit_email'];
    $password = md5($_POST['edit_password']);
    $usertype = $_POST ['update_usertype'];
    $email_query = "SELECT * FROM users WHERE USERNAME='$username'";
    $email_query_run = mysqli_query($connection, $email_query);
    if($username =="" or $password =="" or $usertype =="")
    {
        $_SESSION['status'] = "Fill out the form";
        $_SESSION['status_code'] = "warning";
        header('Location: user.php');
    }
    else
    {
        $query = "UPDATE users SET USERNAME='$username', USER_EMAIL='$email', USER_PASSWORD='$password', USERTYPE='$usertype' WHERE USER_ID='$id'";
        $query_run = mysqli_query($connection, $query);

        if($query_run)
        {
            $_SESSION['status'] = "Your Data is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: user.php');
        }
        else
        {
            $_SESSION['status'] = "Your Data is NOT Updated";
            $_SESSION['status_code'] = "error";
            header('Location: user.php');
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
        $_SESSION['status'] = "Your Data is Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: user.php');
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: user.php');
    }
}

// INSERTING EMPLOYEE
if(isset($_POST['addemp']))
{
    $userid = $_POST['userid'];
    $empno = $_POST['empno'];
    $empfname = $_POST['empfname'];
    $emplname = $_POST['emplname'];
    $empmname = $_POST['empmname'];
    $empsname = $_POST['empsname'];
    $pmode = $_POST['pmode'];
    $proloc = $_POST['proloc'];
    $designation = $_POST['designation'];
    $doj = $_POST['doj'];
    $bankname = $_POST['bankname'];
    $accno = $_POST['accno'];
    $ibanno = $_POST['ibanno'];
    $empstatus = $_POST['empstatus'];

    $empno_query = "SELECT * FROM employee WHERE EMP_NO='$empno' AND EMP_STATUS=1";
    $empno_query_run = mysqli_query($connection, $empno_query);

    if(mysqli_num_rows($empno_query_run) > 0)
    {
        $_SESSION['status'] = "Employee Number Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: employee.php');
    }
    else
    {
        $query = "INSERT INTO employee (USER_ID,EMP_NO,EMP_FNAME,EMP_LNAME,EMP_MNAME,EMP_SNAME,EMP_PAYMODE,EMP_LOCATION,EMP_DESIGNATION,EMP_DOJ,EMP_BANK,EMP_ACCNO,EMP_IBANNO,EMP_STATUS) VALUES ('$userid','$empno','$empfname','$emplname','$empmname','$empsname','$pmode','$proloc','$designation','$doj','$bankname','$accno','$ibanno','$empstatus')";
        if($connection->query($query) === TRUE)
        {
            $_SESSION['status'] = "Employee Record Added";
            $_SESSION['status_code'] = "success";
            header('Location: employee.php');
        }
        else
        {
            $_SESSION['status'] = "Employee Details Add";
            $_SESSION['status_code'] = "error";
            header('Location: employee.php');
        }
    }
}

// EDIT EMPLOYEE
if(isset($_POST['update_emp']))
{
    // PASSING VALUES
    $empid = $_POST['edit_empid'];
    $e_empno = $_POST['edit_empno'];
    $e_empfname = $_POST['edit_empfname'];
    $e_emplname = $_POST['edit_emplname'];
    $e_empmname = $_POST['edit_empmname'];
    $e_empsname = $_POST['edit_empsname'];
    $e_pmode = $_POST['edit_pmode'];
    $e_proloc = $_POST['edit_proloc'];
    $e_desig = $_POST['edit_desig'];
    $e_doj = $_POST['edit_doj'];
    $e_bankname = $_POST['edit_bankname'];
    $e_accno = $_POST['edit_accno'];
    $e_ibanno = $_POST['edit_ibanno'];

    $query = "UPDATE employee SET EMP_NO='$e_empno', EMP_FNAME='$e_empfname', EMP_LNAME='$e_emplname', EMP_MNAME='$e_empmname', EMP_SNAME='$e_empsname', EMP_PAYMODE='$e_pmode', EMP_LOCATION='$e_proloc', EMP_DESIGNATION='$e_desig', EMP_DOJ='$e_doj', EMP_BANK='$e_bankname', EMP_ACCNO='$e_accno', EMP_IBANNO='$e_ibanno' WHERE EMP_ID='$empid'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Your Data is Updated";
        $_SESSION['status_code'] = "success";
        header('Location: employee.php');
    }
    else
    {
        $_SESSION['status'] = "Your Data is NOT Updated";
        $_SESSION['status_code'] = "warning";
        header('Location: employee.php');
    }
}

// DELETE EMPLOYEE
if(isset($_POST['del_emp']))
{
    $id = $_POST['del_empid'];
    $query="UPDATE employee SET EMP_STATUS=0 WHERE EMP_ID='$id'";
    $query_run = mysqli_query($connection, $query);

    $email_query = "SELECT USER_ID FROM employee WHERE EMP_ID='$id'";
    $email_query_run = mysqli_query($connection, $email_query);
    if(mysqli_num_rows($email_query_run)>0){
        $row=mysqli_fetch_assoc($email_query_run);
        $user_id=$row['USER_ID'];
        $query1 = "UPDATE users SET USER_STATUS =0 WHERE USER_ID='$id'";
        $query_run1 = mysqli_query($connection, $query1);
    }
    if($query_run)
    {
        $_SESSION['status'] = "Your Employee Record Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: employee.php');
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        header('Location: employee.php');
    }
}

// DELETE EMPLOYEE ALLOWANCE
if(isset($_POST['delete_emp_alw']))
{
    $a_id=$_POST['d_alw_id'];
    $emp_id=$_POST['e_empid'];
    $query="DELETE FROM allowance WHERE ALW_ID='$a_id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        header('Location: edit_employee.php?id='.$emp_id);
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: edit_register.php?id='.$emp_id);
    }
}

// INSERT PAYSLIP
if(isset($_POST['addp']))
{
    // PASSING VALUES
    $empidp = $_POST['empidp'];
    $date = $_POST['date'];
    $fbasic = $_POST['full_basic'];
    $basic = $_POST['basic'];
    $norm_ot_hrs = $_POST['norm_ot_hrs'];
    $norm_ot_amt = $_POST['norm_ot_amt'];
    $norm_hol_hrs = $_POST['norm_hol_hrs'];
    $norm_hol_amt = $_POST['norm_hol_amt'];
    $sp_hrs = $_POST['sp_hrs'];
    $sp_amt = $_POST['sp_amt'];
    $bns_hrs = $_POST['bns_hrs'];
    $bns_amt = $_POST['bns_amt'];
    $ab_days = $_POST['ab_days'];
    $l_days = $_POST['l_days'];
    $pstatus = $_POST['pstatus'];
    $month = date("m",strtotime($date));
    $year = date("Y",strtotime($date));

    $pdate_query = "SELECT * FROM payslip WHERE EMP_ID='$empidp' AND MONTH(P_DATE)='$month' AND YEAR(P_DATE)='$year' AND P_STATUS=1";
    $pdate_query_run = mysqli_query($connection, $pdate_query);

    if(mysqli_num_rows($pdate_query_run) > 0)
    {
        $_SESSION['status'] = "Payslip for the Month Already Exist";
        $_SESSION['status_code'] = "error";
        header('Location: payslip.php');
    }      
        else
        {           
            if($empidp=="" or $date=="" or $basic=="" or $pstatus=="")
            {
                $_SESSION['status'] = "Fill out Required fields";
                $_SESSION['status_code'] = "warning";
                header('Location: payslip.php');
            }
                else
                {
                    $query = "INSERT INTO payslip (EMP_ID,P_DATE,P_BASIC_SALARY,P_NORM_OTHRS,P_NORM_OTAMT,P_HOL_OTHRS,P_HOL_OTAMT,P_SP_HRS,P_SP_AMT,P_BNS_HR,P_BNS_AMT,P_ABDAYS,P_LDAYS,P_STATUS,P_FULL_BASIC) VALUES ('$empidp','$date','$basic','$norm_ot_hrs','$norm_ot_amt','$norm_hol_hrs','$norm_hol_amt','$sp_hrs','$sp_amt','$bns_hrs','$bns_amt','$ab_days','$l_days','$pstatus','$fbasic')";    

                    if($connection->query($query)===TRUE)
                    {          
                        $l_pid = $connection->insert_id;           

                            if(isset($_POST['falw_name']) !="")
                            { 
                                $data = array( 
                                    'falw_name' => $_POST['falw_name'],
                                    'aflw_amt' => $_POST['falw_amt']
                                    );
                                 $count4 = count($_POST['falw_name']);
                                 for ($k=0; $k < $count4; $k++) { 
                                    $sql ="INSERT INTO full_allowance (FULL_ALW_NAME,FULL_ALW_AMT,PAYSLIP_ID) VALUES ('{$_POST['falw_name'][$k]}','{$_POST['falw_amt'][$k]}','$l_pid')";
                                    $connection->query($sql);
                                    }
                                $sql_="DELETE FROM full_allowance WHERE FULL_ALW_AMT='0'"; 
                                $connection->query($sql_); 
                            } else {}
                            
                            if(isset($_POST['alw_name']) !="")
                            { 
                                $data = array( 
                                    'alw_name' => $_POST['alw_name'],
                                    'alw_amt' => $_POST['alw_amt']
                                    );
                                 $count = count($_POST['alw_name']);
                                 for ($i=0; $i < $count ; $i++) { 
                                    $sql ="INSERT INTO allowance (ALW_NAME,ALW_AMT,PAYSLIP_ID) VALUES ('{$_POST['alw_name'][$i]}','{$_POST['alw_amt'][$i]}','$l_pid')";
                                    $connection->query($sql);
                                    }
                                $sql_="DELETE FROM allowance WHERE ALW_AMT='0'";
                                $connection->query($sql_); 
                            } else {}

                            if(isset($_POST['deduc_name']) != "")
                            {
                                $data = array(
                                    'deduc_name' => $_POST['deduc_name'],
                                    'deduc_amt' => $_POST['deduc_amt']
                                    );
                                $count1 = count($_POST['deduc_name']);
                                for ($ii=0; $ii < $count1 ; $ii++) { 
                                    $sql1 ="INSERT INTO deduction (DEDUC_NAME,DEDUC_AMT,PAYSLIP_ID) VALUES ('{$_POST['deduc_name'][$ii]}','{$_POST['deduc_amt'][$ii]}','$l_pid')";
                                    $connection->query($sql1);
                                    }
                                $sql_="DELETE FROM deduction WHERE DEDUC_AMT='0';";
                                $connection->query($sql_);
                            } else{}
                            if(isset($_POST['add_name']) != "")
                            {
                                $data = array(
                                    'add_name' => $_POST['add_name'],
                                    'add_amt' => $_POST['add_amt']
                                    );
                                $count2 = count($_POST['add_name']);
                                for ($iii=0; $iii < $count2 ; $iii++) { 
                                    $sql2 ="INSERT INTO additional (ADD_NAME,ADD_AMT,PAYSLIP_ID) VALUES ('{$_POST['add_name'][$iii]}','{$_POST['add_amt'][$iii]}','$l_pid')";
                                    $connection->query($sql2);
                                    }
                                $sql_="DELETE FROM additional WHERE ADD_AMT='0'";
                                $connection->query($sql_);
                            } else{}
                        $_SESSION['status'] = "Payslip Details Added";
                        $_SESSION['status_code'] = "success";
                        header('Location: payslip.php');
                    }
                    else
                    {
                        $_SESSION['status'] = "Payslip Details NOT Added";
                        $_SESSION['status_code'] = "error";
                        header('Location: payslip.php');
                    }
            }
        }
}

// EDIT PAYSLIP
if(isset($_POST['update_p']))
{
    // PASSING VALUES
    $edit_pid = $_POST['edit_pid'];
    $e_date = $_POST['e_date'];
    $e_basic = $_POST['e_basic'];
    $e_full_basic = $_POST['fe_basic'];
    $e_norm_ot_hrs = $_POST['e_norm_ot_hrs'];
    $e_norm_ot_amt = $_POST['e_norm_ot_amt'];
    $e_norm_hol_hrs = $_POST['e_norm_hol_hrs'];
    $e_norm_hol_amt = $_POST['e_norm_hol_amt'];
    $e_sp_hrs = $_POST['e_sp_hrs'];
    $e_sp_amt = $_POST['e_sp_amt'];
    $e_bns_hrs = $_POST['e_bns_hrs'];
    $e_bns_amt = $_POST['e_bns_amt'];
    $e_ab_days = $_POST['e_ab_days'];
    $e_l_days = $_POST['e_l_days'];
              
    if($e_date =="" or $e_basic =="")
    {
        $_SESSION['status'] = "Fill out Required fields";
        $_SESSION['status_code'] = "warning";
        header('Location: payslip.php');
    }
    else
    {
        $query = "UPDATE payslip SET P_DATE='$e_date', P_FULL_BASIC='$e_full_basic', P_BASIC_SALARY='$e_basic',P_NORM_OTHRS='$e_norm_ot_hrs',P_NORM_OTAMT='$e_norm_ot_amt',P_HOL_OTHRS='$e_norm_hol_hrs',P_HOL_OTAMT='$e_norm_hol_amt',P_SP_HRS='$e_sp_hrs',P_SP_AMT='$e_sp_amt',P_BNS_HR='$e_bns_hrs',P_BNS_AMT='$e_bns_amt',P_ABDAYS='$e_ab_days',P_LDAYS='$e_l_days' WHERE PAYSLIP_ID='$edit_pid'";
        $query_run = mysqli_query($connection, $query);
        
        if($query_run)
        {
            // EDIT FUNCTION
            if(isset($_POST['fe_alw_id']))
            {
                $data = array(
                    'fe_alw_id' => $_POST['fe_alw_id'],
                    'fe_alw_name' => $_POST['fe_alw_name'],
                    'fe_alw_amt' => $_POST['fe_alw_amt']
                    );
                $count = count($_POST['fe_alw_id']);
                for ($i=0; $i < $count ; $i++) { 
                    $sql ="UPDATE full_allowance SET FULL_ALW_NAME='{$_POST['fe_alw_name'][$i]}', FULL_ALW_AMT='{$_POST['fe_alw_amt'][$i]}' WHERE FULL_ALW_ID='{$_POST['fe_alw_id'][$i]}'";
                    $connection->query($sql);
                    }
            }
            if(isset($_POST['e_alw_id']))
            {
                $data = array(
                    'e_alw_id' => $_POST['e_alw_id'],
                    'e_alw_name' => $_POST['e_alw_name'],
                    'e_alw_amt' => $_POST['e_alw_amt']
                    );
                $count = count($_POST['e_alw_id']);
                for ($i=0; $i < $count ; $i++) { 
                    $sql ="UPDATE allowance SET ALW_NAME='{$_POST['e_alw_name'][$i]}', ALW_AMT='{$_POST['e_alw_amt'][$i]}' WHERE ALW_ID='{$_POST['e_alw_id'][$i]}'";
                    $connection->query($sql);
                    }
            }
            if(isset($_POST['e_d_id']))
            {
                $data = array(
                'e_d_id' => $_POST['e_d_id'],
                'e_d_name' => $_POST['e_d_name'],
                'e_d_amt' => $_POST['e_d_amt']
                );
                $count1 = count($_POST['e_d_id']);
                for ($ii=0; $ii < $count1 ; $ii++) { 
                    $sql1 ="UPDATE deduction SET DEDUC_NAME='{$_POST['e_d_name'][$ii]}', DEDUC_AMT='{$_POST['e_d_amt'][$ii]}' WHERE DEDUC_ID='{$_POST['e_d_id'][$ii]}'";
                    $connection->query($sql1);
                    }
            }
            if(isset($_POST['e_ad_id']))
            {
                $data = array(
                'e_ad_id' => $_POST['e_ad_id'],
                'e_add_name' => $_POST['e_add_name'],
                'e_add_amt' => $_POST['e_add_amt']
                );
                $count1 = count($_POST['e_ad_id']);
                for ($ii=0; $ii < $count1 ; $ii++) { 
                    $sql1 ="UPDATE additional SET ADD_NAME='{$_POST['e_add_name'][$ii]}', ADD_AMT='{$_POST['e_add_amt'][$ii]}' WHERE ADD_ID='{$_POST['e_ad_id'][$ii]}'";
                    $connection->query($sql1);
                    }
            }
            // ADDING
            if(isset($_POST['falw_name']) !="")
                { 
                    $data = array( 
                        'falw_name' => $_POST['falw_name'],
                        'falw_amt' => $_POST['falw_amt']
                        );
                        $count = count($_POST['falw_name']);
                        for ($i=0; $i < $count ; $i++) { 
                        $sql ="INSERT INTO full_allowance (FULL_ALW_NAME,FULL_ALW_AMT,PAYSLIP_ID) VALUES ('{$_POST['falw_name'][$i]}','{$_POST['falw_amt'][$i]}','$edit_pid')";
                        $connection->query($sql);
                        }
                    $sql_="DELETE FROM full_allowance WHERE FULL_ALW_AMT='0'";
                    $connection->query($sql_); 
                } else {}
            if(isset($_POST['alw_name']) !="")
                { 
                    $data = array( 
                        'alw_name' => $_POST['alw_name'],
                        'alw_amt' => $_POST['alw_amt']
                        );
                        $count = count($_POST['alw_name']);
                        for ($i=0; $i < $count ; $i++) { 
                        $sql ="INSERT INTO allowance (ALW_NAME,ALW_AMT,PAYSLIP_ID) VALUES ('{$_POST['alw_name'][$i]}','{$_POST['alw_amt'][$i]}','$edit_pid')";
                        $connection->query($sql);
                        }
                    $sql_="DELETE FROM allowance WHERE ALW_AMT='0'";
                    $connection->query($sql_); 
                } else {}
                if(isset($_POST['deduc_name']) != "")
                {
                    $data = array(
                        'deduc_name' => $_POST['deduc_name'],
                        'deduc_amt' => $_POST['deduc_amt']
                        );
                    $count1 = count($_POST['deduc_name']);
                    for ($ii=0; $ii < $count1 ; $ii++) { 
                        $sql1 ="INSERT INTO deduction (DEDUC_NAME,DEDUC_AMT,PAYSLIP_ID) VALUES ('{$_POST['deduc_name'][$ii]}','{$_POST['deduc_amt'][$ii]}','$edit_pid')";
                        $connection->query($sql1);
                        }
                    $sql_="DELETE FROM deduction WHERE DEDUC_AMT='0';";
                    $connection->query($sql_); 
                } else{}
                if(isset($_POST['add_name']) != "")
                {
                    $data = array(
                        'add_name' => $_POST['add_name'],
                        'add_amt' => $_POST['add_amt']
                        );
                    $count2 = count($_POST['add_name']);
                    for ($iii=0; $iii < $count2 ; $iii++) { 
                        $sql2 ="INSERT INTO additional (ADD_NAME,ADD_AMT,PAYSLIP_ID) VALUES ('{$_POST['add_name'][$iii]}','{$_POST['add_amt'][$iii]}','$edit_pid')";
                        $connection->query($sql2);
                        }
                    $sql_="DELETE FROM additional WHERE ADD_AMT='0'";
                    $connection->query($sql_); 
                } else{}

            $_SESSION['status'] = "Payslip Details Updated";
            $_SESSION['status_code'] = "success";
            header('Location: payslip.php');
        }
        else
        {
            $_SESSION['status'] = "Payslip Details NOT Added";
            $_SESSION['status_code'] = "error";
            header('Location: payslip.php');
        }
    }   
}

// DELETING PAYSLIP
if(isset($_POST['delete_payslip']))
{
    $id = $_POST['del_pid'];
    $query="UPDATE payslip SET P_STATUS=0 WHERE PAYSLIP_ID='$id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Payslip Record Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: payslip.php');
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: payslip.php');
    }
}

if(isset($_POST['changepass']))
{
    // PASSING VARIABLE
    $username = $_POST['username'];
    $oldp = md5($_POST['oldp']);
    $newp = md5($_POST['newp']);
    $confirmp = md5($_POST['confirmp']);

    $pass_query = "SELECT * FROM users WHERE USERNAME='$username' AND USER_PASSWORD='$oldp'"; // 1 true same password

    $pass_query_run = mysqli_query($connection, $pass_query);

    if(mysqli_num_rows($pass_query_run) === 1)
    {
        // confirming the password
        if($newp === $confirmp)
        {
            // UPDATE QUERY
            $query="UPDATE users SET USER_PASSWORD='$newp' WHERE USERNAME='$username'";
            $query_run = mysqli_query($connection, $query);

           if($query_run)
               {
                   // success
                   $_SESSION['status'] = "Password Changed";
                   $_SESSION['status_code'] = "success";
                   header('Location: settings.php');
               }
               else
               {
                   //error
                   $_SESSION['status'] = "Error upon changing";
                   $_SESSION['status_code'] = "error";
                   header('Location: settings.php');
               }
        }
           else
           {
               $_SESSION['status'] = "Passord does not match";
               $_SESSION['status_code'] = "warning";
               header('Location: settings.php');
           }
    }
    else
    {
        $_SESSION['status'] = "Old Password Incorrect";
        $_SESSION['status_code'] = "error";
        header('Location: settings.php');
    }
}

// DELETE ALLOWANCE
if(isset($_POST['delete_alw']))
{
    $a_id=$_POST['d_alw_id'];
    $p_id=$_POST['e_pid'];
    $query="DELETE FROM allowance WHERE ALW_ID='$a_id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        header('Location: edit_payslip.php?id='.$p_id);
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: edit_payslip.php?id='.$p_id);
    }
}
// DELETE DEDUCTION
if(isset($_POST['delete_d']))
{
    $d_id=$_POST['d_deduc_id'];
    $p_id=$_POST['e_pid'];
    $query="DELETE FROM deduction WHERE DEDUC_ID='$d_id'";
    $query_run = mysqli_query($connection,$query);
    if($query_run)
    {
        header('Location: edit_payslip.php?id='.$p_id);
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: edit_payslip.php?id='.$p_id);
    }
}
// DELETE ADDITIONAL
if(isset($_POST['delete_add']))
{
    $add_id=$_POST['d_add_id'];
    $p_id=$_POST['e_pid'];
    $query="DELETE FROM additional WHERE ADD_ID='$add_id'";
    $query_run = mysqli_query($connection,$query);
    if($query_run)
    {
        header('Location: edit_payslip.php?id='.$p_id);
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: edit_payslip.php?id='.$p_id);
    }
}
// DELETE FULL ALLOWANCE
if(isset($_POST['delete_falw']))
{
    $fa_id=$_POST['fd_alw_id'];
    $p_id=$_POST['e_pid'];
    $query="DELETE FROM full_allowance WHERE FULL_ALW_ID='$fa_id'";
    $query_run = mysqli_query($connection, $query);

    if($query_run)
    {
        header('Location: edit_payslip.php?id='.$p_id);
    }
    else
    {
        $_SESSION['status'] = "Error upon deleting";
        $_SESSION['status_code'] = "error";
        header('Location: edit_payslip.php?id='.$p_id);
    }
}
// IMPORT PAYSLIP
if(isset($_POST["submit"]))
{
    if($_FILES['file']['name'])
    {
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv')
        {
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            while($data = fgetcsv($handle))
            {
                $payslipID=NULL;
                $empno= mysqli_real_escape_string($connection, $data[0]); // emp no
                $item2 = mysqli_real_escape_string($connection, $data[1]); // date
                $item3 = mysqli_real_escape_string($connection, $data[2]); // desc
                $item4 = mysqli_real_escape_string($connection, $data[3]); // amount
                $item5 = mysqli_real_escape_string($connection, $data[4]); // identifier

                $query_pID="SELECT PAYSLIP_ID FROM payslip LEFT JOIN employee on payslip.EMP_ID=employee.EMP_ID WHERE EMP_NO='$empno' AND P_DATE='$item2' LIMIT 1";
                $query_run = mysqli_query($connection,$query_pID);
                if($emp_no==01277){
                    if(mysqli_num_rows($query_run)>0){
                        while($row_p=mysqli_fetch_assoc($query_run)){
                            $payslipID= $row_p['PAYSLIP_ID'];
                            if($item5=="AL"){
                                $sqlAl="INSERT INTO allowance (ALW_NAME,ALW_AMT,PAYSLIP_ID) VALUES ('$item3','$item4','$payslipID')";
                                $query_run = mysqli_query ($connection, $sqlAl);
                            }
                            elseif($item5=="D"){
                                $sqlAl="INSERT INTO deduction (DEDUC_NAME,DEDUC_AMT,PAYSLIP_ID) VALUES ('$item3','$item4','$payslipID')";
                                $query_run = mysqli_query ($connection, $sqlAl);
                            }
                            elseif($item5=="AD"){
                                $sqlAl="INSERT INTO additional (ADD_NAME,ADD_AMT,PAYSLIP_ID) VALUES ('$item3','$item4','$payslipID')";
                                $query_run = mysqli_query ($connection, $sqlAl);
                            }
                            elseif($item5=="FAL"){
                                echo $sqlAl="INSERT INTO full_allowance (FULL_ALW_NAME,FULL_ALW_AMT,PAYSLIP_ID) VALUES ('$item3','$item4','$payslipID')";
                                // $query_run = mysqli_query ($connection, $sqlAl);
                            }
                        }
                    }    
                    else{
                        if(is_numeric($item5)){
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
                            $item12 = mysqli_real_escape_string($connection, $data[11]);
                            $item13 = mysqli_real_escape_string($connection, $data[12]);
                            $item14 = mysqli_real_escape_string($connection, $data[13]);
        
                            $query ="SELECT EMP_ID FROM employee WHERE EMP_NO='$empno' AND EMP_STATUS=1";
                            $query_run = mysqli_query($connection,$query);
                            $item15 = 1;
                            foreach($query_run as $row)
                            {     
                                $item1 = $row['EMP_ID'];     
                            }
                                $sql= "INSERT INTO payslip (EMP_ID,P_DATE,P_FULL_BASIC,P_BASIC_SALARY,P_NORM_OTHRS,P_NORM_OTAMT,P_HOL_OTHRS,P_HOL_OTAMT,P_SP_HRS,P_SP_AMT,P_BNS_HR,P_BNS_AMT,P_ABDAYS,P_LDAYS,P_STATUS) VALUES ('$item1','$item2','$item3','$item4','$item5','$item6','$item7','$item8','$item9','$item10','$item11','$item12','$item13','$item14','$item15')";
                                         
                                if ($connection->query($sql) === TRUE) {
                                    $_SESSION['status'] = "Payslip Details Added";
                                    $_SESSION['status_code'] = "success";
                                    header("location:import.php"); 
                                } else {
                                    $_SESSION['status'] = "Import Error";
                                    $_SESSION['status_code'] = "error";
                                    header("location:import.php");
                                }
                        }
                    }
                }
                
                    
            } 
            fclose($handle);
            // if($query_run)
            // {
            //     $_SESSION['status'] = "Payslip Details Added";
            //     $_SESSION['status_code'] = "success";
            //     header("location:import.php");
            // }
            // else
            // {
            //     $_SESSION['status'] = "Import Error";
            //     $_SESSION['status_code'] = "error";
            //     header("location:import.php");
            // }
        }
    }
}
if(isset($_POST['delFile'])){
    $file_id=$_POST['file_id'];
    $filename=$_POST['filename'];
    $filename='empFiles/'.$filename.'.pdf';
    // remove from file table
    $q_del="DELETE FROM files WHERE File_Id='$file_id'";
    $q_del_run=mysqli_query($connection, $q_del);
    if($q_del_run){
        if(unlink($filename)){
            $_SESSION['status'] = "File Deleted";
            $_SESSION['status_code'] = "success";
            header("location:emp_files.php"); 
        }
        else{
            $_SESSION['status'] = "Delete Error";
            $_SESSION['status_code'] = "error";
            header("location:emp_files.php");
        }
    }
    else{
        $_SESSION['status'] = "Delete Error";
        $_SESSION['status_code'] = "error";
        header("location:emp_files.php");
    }
    
}
if(isset($_POST['addFile'])){
    $emp_id=$_POST['emp_id'];
    //search emp code
    $q_emp="SELECT EMP_NO FROM employee WHERE EMP_ID=$emp_id";
    $q_emp_run=mysqli_query($connection,$q_emp);
    if(mysqli_num_rows($q_emp_run)>0){
        $row_emp=mysqli_fetch_assoc($q_emp_run);
        $emp_no=$row_emp['EMP_NO'];
        $file_desc=$_POST['file_desc'];
        $filename=$file_desc.$emp_no;
        $targetfolder = "empFiles/".$filename.".pdf";
        $ok=1;
        $file_type=$_FILES['file']['type'];
        if($file_type=="application/pdf"){
            //check if there is existing filename
            $check="SELECT * FROM files WHERE File_Desc='$filename'";
            $check_run=mysqli_query($connection,$check);
            if(mysqli_num_rows($check_run)>0){
                $filename=$filename.'1';
            }
            if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder)){
                //update table files
                 $query="INSERT INTO files (File_Desc,File_Type,Emp_Id  ) VALUES ('$filename','$file_desc','$emp_id')";
                $query_run=mysqli_query($connection,$query);
                if($query_run){
                    $_SESSION['status'] = "Upload Success";
                    $_SESSION['status_code'] = "success";
                    header("location:emp_files.php");
                }
                else{
                    $_SESSION['status'] = "Error Uploading";
                    $_SESSION['status_code'] = "error";
                    header("location:emp_files.php");
                }
            }
        }
        else{
            $_SESSION['status'] = "Invalid File Type";
            $_SESSION['status_code'] = "warning";
            header("location:emp_files.php");
        }
    }
    else{
        $_SESSION['status'] = "Error Uploading";
        $_SESSION['status_code'] = "error";
        header("location:emp_files.php");
    }
    
}
?>