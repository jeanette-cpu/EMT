<?php
include('security.php');

if(isset($_POST['villa_id']))
{
    $villa_id=$_POST['villa_id'];
    $query="SELECT * FROM plex WHERE Villa_Id='$villa_id' and Plx_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output .= '<option>Select Plex</option>';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Plx_Id'].'">'.$row['Plx_Code'].' - '.$row['Plx_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;

}
if(isset($_POST['v_id']))
{
    $villa_id=$_POST['v_id'];
    $query="SELECT * FROM plex WHERE Villa_Id='$villa_id' and Plx_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output .= '<option>Select Plex</option>
                <option value="All">All Plexes</option>
            ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Plx_Id'].'">'.$row['Plx_Code'].' - '.$row['Plx_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;

}
?>