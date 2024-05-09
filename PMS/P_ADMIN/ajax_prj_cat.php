<?php
include('../../security.php');

if(isset($_POST['proj_id']))
{
    $prj_id = $_POST['proj_id'];
    $query = "SELECT Prj_Category FROM project WHERE Prj_Id ='$prj_id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run); 
    echo $row['Prj_Category'];
}
if(isset($_POST['total_mp']))
{
    $total=$_POST['total_mp'];
    $asgn_id = $_POST['asgn_id'];

    $q_update = "UPDATE asgn_mp SET Asgn_MP_Total='$total' WHERE Asgn_MP_Id='$asgn_id'";
    $q_update_run = mysqli_query($connection,$q_update);

}
if(isset($_POST['total_sb']))
{
    $total=$_POST['total_sb'];
    $asgn_id = $_POST['asgn_id'];

    $q_update = "UPDATE asgn_subcon SET Asgn_SB_Total='$total' WHERE Asgn_SB_Id='$asgn_id'";
    $q_update_run = mysqli_query($connection,$q_update);
}
?>