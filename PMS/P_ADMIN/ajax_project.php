<?php
include('../../security.php');

    $query = "SELECT Prj_Id, Prj_Code, Prj_Name FROM project WHERE Prj_Status = 1";
    // echo $query;
    $query_run = mysqli_query($connection, $query);
    $output='';
    $output .= '<option>Select Project</option>';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $output .= '<option value="'.$row['Prj_Id'].'">'.$row['Prj_Code'].' - '.$row['Prj_Name'].'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;

   