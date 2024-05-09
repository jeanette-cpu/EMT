<?php
include('security.php');

$query= "SELECT * from department WHERE Dept_Status=1";

$query_run = mysqli_query($connection, $query);
$output='';
$output .= '<option>Select Department</option>';
if(mysqli_num_rows($query_run)>0)
{
    while($row = mysqli_fetch_assoc($query_run))
    {
        $output .= '<option value="'.$row['Dept_Id'].'">'.$row['Dept_Name'].'</option>';
    }
}
echo $output;

?>