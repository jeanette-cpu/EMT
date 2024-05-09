<?php
include('../../security.php');

if(isset($_POST["usertype"]))  
{
    // $output = "<select name= name='emp_id' id='emp_id' class='form-control selectpicker' data-live-search='true' required>";
    $usertype = $_POST['usertype'];
    if($usertype=='str_mgr')
    {
        $q_user_id="select * from (SELECT USER_ID FROM users WHERE USERTYPE='str_mgr' AND USER_STATUS=1) as query2 except select * from (SELECT u.USER_ID FROM users as u LEFT JOIN asgn_emp_to_prj as as_emp on u.USER_ID=as_emp.User_Id WHERE u.USERTYPE='str_mgr' AND u.USER_STATUS=1 and as_emp.Asgd_Emp_to_Prj_Status=1 ) as query1";
        $q_user_id_run = mysqli_query($connection, $q_user_id);
        if(mysqli_num_rows($q_user_id_run)>0)
        {
            while($row_u = mysqli_fetch_assoc($q_user_id_run))
            {
                $user_id_arr[] = $row_u['USER_ID'];
            }
            $user_ids=implode("', '",$user_id_arr);
        }
        $query = "SELECT * FROM users WHERE USERTYPE='$usertype' AND USER_STATUS=1 and USER_ID IN ('$user_ids')";
        // echo $query;
        $query_run = mysqli_query($connection, $query);
        $output='';
        $output .= '<option>Select Username</option>';
        if(mysqli_num_rows($query_run)>0)
        {
            while($row = mysqli_fetch_assoc($query_run))
            {
                $output .= '<option value="'.$row['USER_ID'].'">'.$row['USERNAME'].'</option>';
            }
        }
        else{}
        $output .='</select>';
        echo $output;
    }
    else
    {
        $query = "SELECT * FROM users WHERE USERTYPE='$usertype' AND USER_STATUS=1";
        // echo $query;
        $query_run = mysqli_query($connection, $query);
        $output='';
        $output .= '<option>Select Username</option>';
        if(mysqli_num_rows($query_run)>0)
        {
            while($row = mysqli_fetch_assoc($query_run))
            {
                $output .= '<option value="'.$row['USER_ID'].'">'.$row['USERNAME'].'</option>';
            }
        }
        else{}
        $output .='</select>';
        echo $output;
    }
}
?>