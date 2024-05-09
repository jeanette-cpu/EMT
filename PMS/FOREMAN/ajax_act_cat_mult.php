<?php
include('../../security.php');
//activity category
if(isset($_POST["dept_id"])){
    $output = '';
    $query1 = "SELECT * FROM activity_category where Act_Cat_Status=1 AND Dept_Id='".$_POST["dept_id"]."'";
    $query_run1 = mysqli_query($connection, $query1);

    $output .= "<option value='any'>Select Activity Category</option>";
    if(mysqli_num_rows($query_run1)>0){
        while($row1 = mysqli_fetch_array($query_run1)){    
            $output .= '<option value="'.$row1['Act_Cat_Id'].'">'.$row1['Act_Cat_Name'].'</option>';
        }  
    }
    else{}
    echo $output;
}
// dept name
if(isset($_POST["dept_name"])){
    $output = '';
    $query1 = "SELECT * FROM department where Dept_Status=1 AND Dept_Id='".$_POST["dept_name"]."'";
    $query_run1 = mysqli_query($connection, $query1);

    if(mysqli_num_rows($query_run1)>0){
        while($row1 = mysqli_fetch_array($query_run1)){    
            $output =$row1['Dept_Name'];
        }  
    }
    else{}
    echo $output;
}
if(isset($_POST["act_cat_id"])){
    $output = '';
    $query1 = "SELECT * FROM activity where Act_Status=1 AND Act_Cat_Id='".$_POST["act_cat_id"]."'";
    $query_run1 = mysqli_query($connection, $query1);

    $output .= "<option value='any'>Select Activity Category</option>";
    if(mysqli_num_rows($query_run1)>0){
        while($row1 = mysqli_fetch_array($query_run1)){    
            $output .= '<option value="'.$row1['Act_Id'].'">'.$row1['Act_Code'].' - '.$row1['Act_Name'].'</option>';
        }  
    }
    else{}
    echo $output;
}
if(isset($_POST["act_cat"])){
    $output = '';
    $query1 = "SELECT * FROM assigned_activity where Asgd_Act_Status=1 AND Asgd_Pct_Done<=100 AND Act_Cat_Id='".$_POST["act_cat"]."' AND Flat_Id IN ('".$_POST["flt_id"]."') GROUP BY Act_Id";
    $query_run1 = mysqli_query($connection, $query1);
    
    if(mysqli_num_rows($query_run1)>0){
        $output .= "<option value='any'>Select Activity Category</option>";
        while($row1 = mysqli_fetch_array($query_run1))
        {    
            $act_id = $row1['Act_Id'];

            $query = "SELECT * FROM activity WHERE Act_Id='$act_id'";
            $query_run=mysqli_query($connection,$query);
            $row = mysqli_fetch_assoc($query_run);

            $output .= '<option value="'.$row['Act_Id'].'">'.$row['Act_Code'].' - '.$row['Act_Name'].'</option>';
        }  
    }
    else{
        $output .= "<option value=''>No Activity Assigned or Left</option>";
    }
    echo $output;
}
?>