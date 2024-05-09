<?php
session_start();

if(isset($_POST['logout_btn']))
{
    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['usertype']);
    header('Location: ../../login.php');
}

?>