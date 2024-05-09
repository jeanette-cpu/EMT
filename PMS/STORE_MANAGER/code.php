<?php
include('../../security.php');
// ASSIGN ALL MATERIALS TO PROJECT
if(isset($_POST['AsgnAllMat']))
{
    $prj_id=$_POST['prj_id'];

    $query="select * from (SELECT Mat_Id FROM material WHERE Mat_Status=1) as query2 except select * from (SELECT Mat_Id FROM mat_qty WHERE Mat_Qty_Status=1 AND Prj_Id='$prj_id' ) as query1";
    $query_run=mysqli_query($connection,$query);

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $mat_id = $row['Mat_Id'];
            $mat_query = "INSERT INTO mat_qty(Mat_Q_Qty, Mat_Id, Prj_Id,Mat_Qty_Status) values(0,'$mat_id','$prj_id',1)";
            $query_run1=mysqli_query($connection,$mat_query);
            if($query_run1)
            {
                $_SESSION['status'] = "Materials Added";
                $_SESSION['status_code'] = "success";
                header('Location: m_material.php');
            }
            else{
                $_SESSION['status'] = "Error Adding Materials";
                $_SESSION['status_code'] = "error";
                header('Location: m_material.php');
            }
        }
    }
    else
    {
        $_SESSION['status'] = "Materials Already Added";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php?id='.$prj_id);
    }
}
// ASGN MATERIAL
if(isset($_POST['addPrjMat']))
{
    $prj_id = $_POST['prj_id'];
    $data = array(
        'mat_id' => $_POST['mat_id'],
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat_id']);
    for($i=0; $i < $count; $i++){
        $m_query = "SELECT * FROM mat_qty WHERE Mat_Id='{$_POST['mat_id'][$i]}' AND Prj_Id='$prj_id' and Mat_Qty_Status=1";
        $m_query_run = mysqli_query($connection, $m_query);

        if(mysqli_num_rows($m_query_run) > 0)
        {
            $_SESSION['status'] = "Material Already Added";
            $_SESSION['status_code'] = "error";
            header('Location: m_material.php?id='.$prj_id);
        }
        else
        {
            $query="INSERT INTO mat_qty(Mat_Q_Qty, Mat_Id, Prj_Id,Mat_Qty_Status) values('{$_POST['mat_qty'][$i]}','{$_POST['mat_id'][$i]}','$prj_id',1)";
            $query_run=mysqli_query($connection,$query);
            // echo $query;
            if($query_run)
            {
                $_SESSION['status'] = "Material Added";
                $_SESSION['status_code'] = "success";
                header('Location: m_material.php?id='.$prj_id);
            }
            else{
                $_SESSION['status'] = "Error Adding Material";
                $_SESSION['status_code'] = "error";
                header('Location: m_material.php?id='.$prj_id);
            } 
        }
       
    }
    
}
// QTY UPDATE
if(isset($_POST['edit_MQty']))
{
    $prj_id=$_POST['prj_id'];
    $qty = $_POST['mat_qty'];
    $mat_id = $_POST['mat_id'];

    $query="UPDATE mat_qty set Mat_Q_Qty='$qty' WHERE Mat_Qty_Id='$mat_id'";
    // echo $query;
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Updated";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php?id='.$prj_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php?id='.$prj_id);
    }
}
// DELETE ASGN MAT
if(isset($_POST['delQty']))
{
    $prj_id=$_POST['prj_id'];
    $mat_id = $_POST['mat_id'];

    $query="UPDATE mat_qty set Mat_Qty_Status=0 WHERE Mat_Qty_Id='$mat_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php?id='.$prj_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php?id='.$prj_id);
    }
}
//EDIT MATERIALS
if(isset($_POST['edit_Mat']))
{
    $m_id=$_POST['mat_id'];
    $m_code=$_POST['emat_code'];
    $m_desc=$_POST['emat_desc'];
    $m_unit=$_POST['emat_unit'];
    $m_qty=$_POST['emat_qty'];
    $dept_id=$_POST['dept_id'];

    $query="UPDATE material set Mat_Code='$m_code',Mat_Desc='$m_desc',Mat_Unit='$m_unit',Mat_Qty='$m_qty',Dept_id='$dept_id' WHERE Mat_Id='$m_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Updated";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }
}
//ADD MATERIALS
if(isset($_POST['addMat'])){
    $dept_id=$_POST['dept_id'];
    $data = array(
        'mat_code' => $_POST['mat_code'], 
        'mat_desc' => $_POST['mat_desc'],
        'mat_unit' => $_POST['mat_unit'],
        'mat_qty' => $_POST['mat_qty']

    );
    $count = count($_POST['mat_code']);
    for($i=0; $i < $count; $i++){
        $sql="INSERT INTO material (Mat_Code,Mat_Desc,Mat_Unit,Mat_Qty,Mat_Status,Dept_Id) VALUES ('{$_POST['mat_code'][$i]}','{$_POST['mat_desc'][$i]}','{$_POST['mat_unit'][$i]}','{$_POST['mat_qty'][$i]}',1,'$dept_id')";
        $query_run=mysqli_query($connection,$sql);
        // echo $sql;
    }  
    if($query_run)
    {
        $_SESSION['status'] = "Material Added";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }
}
// DELETE MATERIAL
if(isset($_POST['delMat']))
{
    $m_id=$_POST['m_id'];
    $query="UPDATE material set Mat_Status=0 where Mat_Id='$m_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }
}
// Release
if(isset($_POST['releaseBtn']))
{
    $prj_id = $_POST['prj_id'];
    $data = array(
        'mat' => $_POST['mat'], 
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat']);
    for($i=0; $i < $count; $i++){
        $sql="SELECT Mat_Q_Qty from mat_qty WHERE Mat_Id ='{$_POST['mat'][$i]}' and Mat_Qty_Status=1 and Prj_Id='$prj_id'";
        // echo $sql;
        $query_run=mysqli_query($connection,$sql);
        $row = mysqli_fetch_assoc($query_run);

        $current_qty = $row['Mat_Q_Qty'];
        // echo $current_qty;
        $updated_qty = $current_qty-$_POST['mat_qty'][$i];
        $add_q ="UPDATE mat_qty set Mat_Q_Qty='$updated_qty' WHERE Mat_Id='{$_POST['mat'][$i]}'";
        $add_q_run=mysqli_query($connection,$add_q);
        // echo $add_q;
    }
    if($add_q_run)
    {
        $_SESSION['status'] = "Material Released";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Releasing Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }  
}
// Receive
if(isset($_POST['receiveBtn']))
{
    $prj_id = $_POST['prj_id'];
    $data = array(
        'mat' => $_POST['mat'], 
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat']);
    for($i=0; $i < $count; $i++){
        $sql="SELECT Mat_Q_Qty from mat_qty WHERE Mat_Id ='{$_POST['mat'][$i]}' and Mat_Qty_Status=1 and Prj_Id='$prj_id'";
        $query_run=mysqli_query($connection,$sql);
        $row = mysqli_fetch_assoc($query_run);

        $current_qty = $row['Mat_Q_Qty'];
        $updated_qty = $current_qty+$_POST['mat_qty'][$i];
        $add_q ="UPDATE mat_qty set Mat_Q_Qty='$updated_qty' WHERE Mat_Id='{$_POST['mat'][$i]}'";
        $add_q_run=mysqli_query($connection,$add_q);
        // echo $add_q;
    }
    if($add_q_run)
    {
        $_SESSION['status'] = "Material Received";
        $_SESSION['status_code'] = "success";
        header('Location: m_material.php');
    }
    else{
        $_SESSION['status'] = "Error Receiving Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_material.php');
    }  
}
// IMPORT MATERIALS
if(isset($_POST["import"]))
{
    if($_FILES['file']['name'])
    {
        //GET PRJ ID ASSIGNED TO STR MGR
        $username = $_SESSION['USERNAME'];
        $sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
        $query_run2=mysqli_query($connection,$sql);
        $row_uu = mysqli_fetch_assoc($query_run2);
        $user_id = $row_uu['USER_ID'];

        $query_p = "SELECT * FROM project as p LEFT JOIN asgn_emp_to_prj as ass_e on ass_e.Prj_Id = p.Prj_Id WHERE p.Prj_Status =1 and ass_e.User_Id='$user_id' LIMIT 1";
        $query_p_run = mysqli_query($connection, $query_p);
        $row_p = mysqli_fetch_assoc($query_p_run);
        $prj_id = $row_p['Prj_Id'];
        
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv')
        {
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            $success=0; $err_name[]=null; $ctn=0;

            while($data = fgetcsv($handle))
            {
                $mat_code= mysqli_real_escape_string($connection, $data[0]);
                $mat_qty = mysqli_real_escape_string($connection, $data[1]);
                // if material code exists
                $query ="SELECT Mat_Id FROM material WHERE Mat_Code='$mat_code' AND Mat_Status=1 LIMIT 1";
                // echo $query;
                $query_run = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($query_run);
                $mat_id = $row['Mat_Id'];
                if(mysqli_num_rows($query_run)>0)
                {
                    // check if already assigned
                    $q_mCheck= "SELECT Mat_Qty_Id FROM mat_qty WHERE Mat_Id='$mat_id' AND Mat_Qty_Status=1 AND Prj_Id='$prj_id' LIMIT 1";
                    // echo $q_mCheck;
                    $q_mCheck_run = mysqli_query($connection,$q_mCheck);
                    $row_am = mysqli_fetch_assoc($q_mCheck_run);
                    $asgn_mat_id = $row_am['Mat_Qty_Id'];
                    // if true update material
                    if(mysqli_num_rows($q_mCheck_run)>0)
                    {
                        $q_update = "UPDATE mat_qty SET Mat_Q_Qty ='$mat_qty' WHERE Mat_Qty_Id='$asgn_mat_id'";
                        // echo $q_update;
                        if($connection->query($q_update) === TRUE){
                            $success++;
                        }
                        else{
                            $error++;
                            $err_name[]=$mat_code;
                        }
                    }
                    // else insert
                    else{
                        $q_insert = "INSERT INTO mat_qty (Mat_Q_Qty,Prj_Id,Mat_Id,Mat_Qty_Status) VALUES ('$mat_qty','$prj_id','$mat_id',1)";
                        if($connection->query($q_insert) === TRUE){
                            $success++;
                        }
                        else{
                            $error++;
                            $err_name[]=$mat_code;
                        }
                    }
                }
                else{
                    $error++;
                    $err_name[]=$mat_code;
                }
                $err_names = implode(", ", $err_name);

                // $message = "Materials Updated: ".$success;
                // $_SESSION['statu'] = $message;
                // $_SESSION['import'] = " Error: ".$error;
                // $_SESSION['status_code'] = "info";
                header('location:m_import.php?success='.$success.'&error='.$error.'&err_names='.$err_names); 
            } 
            fclose($handle);
        }
    }
}
?>