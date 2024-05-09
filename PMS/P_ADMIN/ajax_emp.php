<?php
include('../../security.php');

    $output = '';
    $query1 = "SELECT * FROM employee where EMP_STATUS=1";
    $query_run1 = mysqli_query($connection, $query1);

    $output .= '<option value="">Select Employee</option>';
    if(mysqli_num_rows($query_run1)>0)
    {
        while($row1 = mysqli_fetch_array($query_run1))
        {    
            $output .= '<option value="'.$row1['EMP_ID'].'">'.$row1['EMP_NO'].' - '.$row1['EMP_FNAME'].' '.$row1['EMP_LNAME'].' '.$row1['EMP_MNAME'].' '.$row1['EMP_SNAME'].'</option>';
        }  
    }
    else{}
    echo $output;

    if(isset($_POST['mp'])){
        $output = '';
        $query1 = "SELECT * FROM manpower where MP_Status=1";
        $query_run1 = mysqli_query($connection, $query1);

        $output .= '<option value="">Select Manpower</option>';
        if(mysqli_num_rows($query_run1)>0)
        {
            while($row1 = mysqli_fetch_array($query_run1))
            {    
                $output .= '<option value="'.$row1['MP_Id'].'">'.$row1['MP_Name'].'</option>';
            }  
        }
        else{}
        echo $output;
    }
?>