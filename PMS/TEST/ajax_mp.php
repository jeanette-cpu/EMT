<?php
include('security.php');
    if(isset($_POST['mp'])){
        $output = '';
        $query1 = "SELECT * FROM manpower where MP_Status=1";
        $query_run1 = mysqli_query($connection, $query1);

        $output .= '
        <option >Select Manpower</option>';
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
    if(isset($_POST['sb'])){
        $output = '';
        $query1 = "SELECT * FROM subcontractor where SB_Status=1";
        $query_run1 = mysqli_query($connection, $query1);

        $output .= '<option value="">Select Subcontractor</option>';
        if(mysqli_num_rows($query_run1)>0)
        {
            while($row1 = mysqli_fetch_array($query_run1))
            {    
                $output .= '<option value="'.$row1['SB_Id'].'">'.$row1['SB_Name'].'</option>';
            }  
        }
        else{}
        echo $output;
    }
?>