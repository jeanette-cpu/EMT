<?php
session_start();
include('security.php');

if(isset($_POST['logout_btn']) || time()-$_SESSION['logCheck']>600) 
{
    $usertype=$_SESSION['USERTYPE'];
    if($usertype=='planning_eng' ||$usertype=='proj_mgr' ||$usertype=='str_mgr' ||$usertype=='foreman' || $usertype=='admin')
    {
        $login=$_SESSION['loginTime'];
        $user_id=$_SESSION['user_id'];
        date_default_timezone_set('Asia/Dubai');
        $logout = date('Y-m-d H:i:s');
        $query_log="INSERT INTO userlog (`Login_Time`, `Logout_Time`, `User_Id`) VALUES ('$login','$logout','$user_id')";
        $query_log_run=mysqli_query($connection,$query_log);
    }
    
    session_destroy();
    unset($_SESSION['user_id']);
    unset($_SESSION['loginTime']);
    unset($_SESSION['USERNAME']);
    unset($_SESSION['USERTYPE']);
    header('Location: login.php');
}

?>