<?php
include('security.php');

if(isset($_POST['lvl_id']))
{
    $output = '';
    $l_id=$_POST['lvl_id'];
    $query1 = "SELECT * FROM flat where Lvl_Id='$l_id' and Flat_Status=1";
    $query_run1 = mysqli_query($connection, $query1);
    $output .= '<option>Select Flat</option>';
    if(mysqli_num_rows($query_run1)>0)
    {
        while($row1 = mysqli_fetch_array($query_run1))
        {    
            $output .= '<option value="'.$row1['Flat_Id'].'">'.$row1['Flat_Code'].' - '.$row1['Flat_Name'].'</option>';
        }  
    }
    echo $output;
}
?>