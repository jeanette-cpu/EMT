<?php
include('security.php');
// building name, villa type
if(isset($_POST['plx_id']))
{
    $plx_id=$_POST['plx_id'];
    $query="SELECT * FROM building where Plx_Id='$plx_id' and Blg_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Building</option>';

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Blg_Id'].'">'.$row['Blg_Code'].' - '.$row['Blg_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
}
if(isset($_POST['plex_id']))
{
    $plx_id=$_POST['plex_id'];
    $query="SELECT * FROM building where Plx_Id='$plx_id' and Blg_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Villa</option>';

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Blg_Id'].'">'.$row['Blg_Code'].' - '.$row['Blg_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
}
if(isset($_POST['plex_id_f']))
{
    $plx_id=$_POST['plex_id_f'];
    $query="SELECT * FROM flat as flt
    LEFT JOIN level as l on l.Lvl_Id = flt.Lvl_Id
    LEFT JOIN building as blg on blg.Blg_Id = l.Blg_Id
    where blg.Plx_Id='$plx_id' and blg.Blg_Status=1 and l.Lvl_Status =1 and flt.Flat_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Villa</option>';

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Flat_Id'].'">'.$row['Blg_Code'].' '.$row['Blg_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
}
// building, building type
if(isset($_POST['lvl_prj_id']))
{
    $prj_id=$_POST['lvl_prj_id'];
    $query="SELECT * FROM building where Prj_Id='$prj_id' and Blg_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Building</option>';

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Blg_Id'].'">'.$row['Blg_Code'].' - '.$row['Blg_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
}
// LEVELS
if(isset($_POST['blg_id']))
{
    $blg_id=$_POST['blg_id'];
    $query="SELECT * FROM level where Blg_Id='$blg_id' and Lvl_Status=1";
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Level</option>';

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Lvl_Id'].'">'.$row['Lvl_Code'].' - '.$row['Lvl_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
}
?>