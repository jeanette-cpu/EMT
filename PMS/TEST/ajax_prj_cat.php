<?php
include('security.php');

if(isset($_POST['proj_id']))
{
    $prj_id = $_POST['proj_id'];
    $query = "SELECT Prj_Category FROM project WHERE Prj_Id ='$prj_id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run); 
    echo $row['Prj_Category'];
}
?>