<?php
include('security.php');
error_reporting(0);
// villa selection
if(isset($_POST['prj_id']))
{
    $prj_id = $_POST['prj_id'];
    $query = "SELECT * FROM villa where Villa_Status=1 and Prj_Id='$prj_id'";
    $query_run = mysqli_query($connection,$query);

    $output='<option>Select Area</option>';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output.='<option value="'.$row['Villa_Id'].'">'.$row['Villa_Code'].' '.$row['Villa_Name'].'</option>';
        }  
    }
    else{}
    echo $output;
}
//villa name
if(isset($_POST['villa_id'])){
    $villa_id= $_POST['villa_id'];
        $query3="SELECT * From villa WHERE Villa_Id='$villa_id' and Villa_Status=1";
        $query_run3 = mysqli_query($connection,$query3);
        $row3= mysqli_fetch_assoc($query_run3);                               
        echo $row3['Villa_Code'].': '.$row3['Villa_Name'];
}
// project name
if(isset($_POST['project_name'])){
    $prj_id=$_POST['project_name'];
    $query="SELECT Prj_Code, Prj_Name FROM project WHERE Prj_Id='$prj_id'";
    $query_run = mysqli_query($connection,$query);
    $row= mysqli_fetch_assoc($query_run);                               
    echo $row['Prj_Code'].' - '.$row['Prj_Name'];
}
// plex name
if(isset($_POST['plx_id'])){
    $plx_id=$_POST['plx_id'];
    $query="SELECT * FROM plex WHERE Plx_Id='$plx_id'";
    $query_run = mysqli_query($connection,$query);
    $row= mysqli_fetch_assoc($query_run);                               
    echo $row['Plx_Code'].' '.$row['Plx_Name'];

}
// building name
if(isset($_POST['blg_id'])){
    $blg_id=$_POST['blg_id'];
    $query="SELECT Blg_Name FROM building WHERE Blg_Id='$blg_id'";
    $query_run = mysqli_query($connection,$query);
    $row= mysqli_fetch_assoc($query_run);                               
    echo $row['Blg_Code'].' '.$row['Blg_Name'];
}
// level name
if(isset($_POST['lvl_id'])){
    $lvl_id=$_POST['lvl_id'];
    $query="SELECT * FROM level WHERE Lvl_Id='$lvl_id'";
    $query_run = mysqli_query($connection,$query);
    $row= mysqli_fetch_assoc($query_run);                               
    echo $row['Lvl_Code'].' '.$row['Lvl_Name'];
}
// flat name
if(isset($_POST['flt_id'])){
    $flt_id=$_POST['flt_id'];
    $query="SELECT * FROM flat WHERE Flat_Id='$flt_id'";
    $query_run = mysqli_query($connection,$query);
    $row= mysqli_fetch_assoc($query_run);                               
    echo $row['Flat_Code'].' '.$row['Flat_Name'];
}
?>