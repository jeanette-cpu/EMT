<?php
include('security.php');

if(isset($_POST['login_btn']))
{
    $username_login = mysqli_real_escape_string($connection,$_POST['USERNAME']);
    $password_login = mysqli_real_escape_string($connection,md5($_POST['USER_PASSWORD']));

    $query = "SELECT * FROM users WHERE USERNAME='$username_login' AND USER_PASSWORD='$password_login' AND USER_STATUS=1";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $usertypes = mysqli_fetch_assoc($query_run);
        $_SESSION['user_id']=$usertypes['USER_ID'];
        date_default_timezone_set('Asia/Dubai');
        $date = date('Y-m-d H:i:s');
        $_SESSION['loginTime']=$date;
        $_SESSION['logCheck']=time();
    }
    else{
        $_SESSION['status'] = "Email or Password is Invalid";
        $_SESSION['status_code'] = "error";
        header('Location: login.php');
    }
    if($usertypes['USERTYPE'] == "admin")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "admin";
        header('Location: index.php');
    }
    else if ($usertypes['USERTYPE'] == "user")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "user";
        header('Location: user_sum.php');
    }
    else if ($usertypes['USERTYPE'] == "planning_eng")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "planning_eng";
        header('Location: ../../EMT/PMS/P_ADMIN/index.php');
    }
    else if ($usertypes['USERTYPE'] == "proj_mgr")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "proj_mgr";
        header('Location: ../../EMT/PMS/PM/index.php');
    }
    else if ($usertypes['USERTYPE'] == "str_mgr")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "str_mgr";
        header('Location: ../../EMT/PMS/STORE_MANAGER/index.php');
    }
    else if ($usertypes['USERTYPE'] == "foreman"){
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "foreman";
        header('Location: ../../EMT/PMS/FOREMAN/index.php');
    }
    else if ($usertypes['USERTYPE'] == "company"){
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "company";
        header('Location: ../../EMT/PURCHASE/COMPANY/index.php');
    }
    else if ($usertypes['USERTYPE'] == "purchase")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "purchase";
        header('Location: ../../EMT/PURCHASE/PM/index.php');
    }
    else if ($usertypes['USERTYPE'] == "account")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "account";
        header('Location: ../../EMT/account/index.php');
    }
    else if ($usertypes['USERTYPE'] == "estimation")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "estimation";
        header('Location: ../../EMT/estimation/index.php');
    }
    else
    {
        $_SESSION['status'] = "Email or Password is Invalid";
        $_SESSION['status_code'] = "error";
        header('Location: login.php');
    }
}

?>