<?php
include('security.php');
// $connection = mysqli_connect("localhost","root","","emt");

if(isset($_POST['login_btn']))
{
    $username_login = mysqli_real_escape_string($connection,$_POST['USERNAME']);
    $password_login = mysqli_real_escape_string($connection,md5($_POST['USER_PASSWORD']));

    $query = "SELECT * FROM users WHERE USERNAME='$username_login' AND USER_PASSWORD='$password_login'";
    $query_run = mysqli_query($connection, $query);
    $usertypes = mysqli_fetch_array($query_run);

    if($usertypes['USERTYPE'] == "admin")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] = "admin";
        header('Location: index.php');
    }
    else if ($usertypes['USERTYPE'] == "user")
    {
        $_SESSION['USERNAME'] = $username_login;
        $_SESSION['USERTYPE'] == "user";
        header('Location: upayslip.php');
    }
    else
    {
        $_SESSION['status'] = "Email or Password is Invalid";
        $_SESSION['status_code'] = "error";
        header('Location: login.php');
    }
}
?>