<?php
include('../../security.php');
if(isset($_POST['mat_id']))
{
    $output = '';
    $query1 = "SELECT * FROM material where Mat_Status=1";
    $query_run1 = mysqli_query($connection, $query1);
    $output .= '<option>Select Material</option>';
    if(mysqli_num_rows($query_run1)>0)
    {
        while($row1 = mysqli_fetch_array($query_run1))
        {    
            $output .= '<option value="'.$row1['Mat_Id'].'">'.$row1['Mat_Code'].' - '.$row1['Mat_Desc'].'</option>';
        }  
    }
    
    echo $output;
}
if(isset($_POST['prj_id']))
{
    $output = ''; $prj_id = $_POST['prj_id'];
    $query="SELECT * FROM mat_qty where Prj_id='$prj_id' and Mat_Qty_Status=1";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0)
    {
        $output .= '<option>Select Material</option>';
        while($row = mysqli_fetch_array($query_run))
        {
            $mat_id = $row['Mat_Id'];
            $query1 = "SELECT * FROM material where Mat_Status=1 and Mat_Id='$mat_id'";
            $query_run1 = mysqli_query($connection, $query1);
            
            if(mysqli_num_rows($query_run1)>0)
            {
                while($row1 = mysqli_fetch_array($query_run1))
                {    
                    $output .= '<option value="'.$row1['Mat_Id'].'">'.$row1['Mat_Code'].' - '.$row1['Mat_Desc'].'</option>';
                }  
            }
        } 
    }
    echo $output;
}
?>