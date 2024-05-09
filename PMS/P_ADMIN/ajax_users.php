<?php
include('../../security.php');


    // $output = "<select name= name='emp_id' id='emp_id' class='form-control selectpicker' data-live-search='true' required>";
    $query = "SELECT * FROM users WHERE USER_STATUS=1 and USERTYPE <> 'user' and USERTYPE <> 'admin'";
    // echo $query;
    $query_run = mysqli_query($connection, $query);
    $output='';
    // $output .= '<option>Select Username</option>';
    
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $usertype = $row['USERTYPE'];
            if($usertype=="planning_eng")
                { $user= 'Project Administrator';}
                else if($usertype=="proj_mgr")
                { $user= 'Project Manager';}
                else if($usertype=="str_mgr")
                { $user= 'Store Manager';}
                else if($usertype=="foreman")
                { $user= 'Foreman';}
            $output .= '<option value="'.$row['USER_ID'].'">'.$row['USERNAME'].' - '.$user.'</option>';
        }
    }
    else{}
    // $output .='</select>';
    echo $output;
