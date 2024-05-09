<?php
include('../../security.php');

// ADD LEVEL
if(isset($_POST['addLvl']))
{
    $blg_id = $_POST['blg_id'];
    $prj_name = $_POST['prj_name'];
    $data = array(
        'lvl_no' => $_POST['lvl_no'],
        'lvl_code' => $_POST['lvl_code'],
        'lvl_name' => $_POST['lvl_name']
    );
    $count = count($_POST['lvl_code']);
    for ($i=0; $i < $count; $i++){
        $sql="INSERT INTO level (Lvl_No,Lvl_Code,Lvl_Name,Lvl_Status,Blg_Id) VALUES ('{$_POST['lvl_no'][$i]}','{$_POST['lvl_code'][$i]}','{$_POST['lvl_name'][$i]}','1','$blg_id')";
    
        $query_run = mysqli_query($connection,$sql);
        if($query_run)
        {
            $_SESSION['status'] = "Level Added";
            $_SESSION['status_code'] = "success";
            header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
        }
        else
        {
            $_SESSION['status'] = "Error Adding Level";
            $_SESSION['status_code'] = "error";
            header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
        }
    }
}
// EDIT LEVEL
if(isset($_POST['editLvl']))
{
    $l_id = $_POST['e_lId'];
    $blg_id = $_POST['blg_id'];
    $lvl_no = $_POST['e_lno'];
    $lvl_code = $_POST['e_lcode'];
    $lvl_name = $_POST['e_lname'];
    $prj_name = $_POST['prj_name'];

    $query="UPDATE level SET Lvl_Code='$lvl_code', Lvl_Name='$lvl_name', Lvl_No='$lvl_no' WHERE Lvl_Id='$l_id'";
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Updated Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
    }
}
// DELETE LEVEL
if(isset($_POST['delLvl']))
{
    $l_id = $_POST['lvl_id'];
    $blg_id = $_POST['blg_id'];
    $prj_name = $_POST['prj_name'];

    $query="UPDATE level SET Lvl_Status=0 WHERE Lvl_Id='$l_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Deleted Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_level.php?id='.$blg_id.'&prj_name='.$prj_name);
    }
}

// ADD FLAT
if(isset($_POST['addFlat']))
{
    $lvl_id = $_POST['lvl_id'];
    $prj_name = $_POST['prj_name'];
    $data = array(
        'flat_code' => $_POST['flat_code'],
        'flat_name' => $_POST['flat_name']
    );
    $count = count($_POST['flat_code']);
    for ($i=0; $i < $count; $i++){
        $sql="INSERT INTO flat (Flat_Code,Flat_Name,Flat_Status,Lvl_Id) VALUES ('{$_POST['flat_code'][$i]}','{$_POST['flat_name'][$i]}','1','$lvl_id')";
        // echo $sql;
        $query_run = mysqli_query($connection,$sql);
        if($query_run)
        {
            $_SESSION['status'] = "Flat Added";
            $_SESSION['status_code'] = "success";
            header('Location: p_flat.php?id='.$lvl_id.'&prj_name='.$prj_name);
        }
        else
        {
            $_SESSION['status'] = "Error Adding Flat";
            $_SESSION['status_code'] = "error";
            header('Location: p_flat.php?id='.$lvl_id.'&prj_name='.$prj_name);
        }
    }
}

// EDIT FLAT
if(isset($_POST['editFlat']))
{
    $l_id = $_POST['l_id'];
    $flat_id = $_POST['e_fId'];
    $flat_code = $_POST['e_fcode'];
    $flat_name = $_POST['e_fname'];
    $prj_name = $_POST['prj_name'];

    $query="UPDATE flat SET Flat_Code='$flat_code', Flat_Name='$flat_name' WHERE Flat_Id='$flat_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Updated Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_flat.php?id='.$l_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: p_flat.php?id='.$l_id.'&prj_name='.$prj_name);
    }
}
// DELETE FLAT
if(isset($_POST['delFlat']))
{
    $f_id = $_POST['flat_id'];
    $lvl_id = $_POST['lvl_id'];
    $prj_name = $_POST['prj_name'];

    $query="UPDATE flat SET Flat_Status=0 WHERE Flat_Id='$f_id'";
    // echo $query;
    $query_run= mysqli_query($connection,$query);

    if($query_run)
    {
        $_SESSION['status'] = "Deleted Successfully";
        $_SESSION['status_code'] = "success";
        header('Location: p_flat.php?id='.$lvl_id.'&prj_name='.$prj_name);
    }
    else
    {
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: p_flat.php?id='.$lvl_id.'&prj_name='.$prj_name);
    }
}

//ADD Department
if(isset($_POST['addDept']))
{
    $dept_name=$_POST['dept_name'];
    $sql="INSERT INTO department (Dept_Name,Dept_Status) VALUES('$dept_name','1')";
    $query_run = mysqli_query($connection,$sql);
    if($query_run)
    {
        $_SESSION['status'] = "Department Added";
        $_SESSION['status_code'] = "success";
        header('Location: d_department.php');
    }
    else
    {
        $_SESSION['status'] = "Error Adding Department";
        $_SESSION['status_code'] = "error";
        header('Location: d_department.php');
    }
}
//EDIT Department
if(isset($_POST['editDept']))
{
    $dept_id=$_POST['Dept_Id'];
    $dept_name=$_POST['dept_name'];
    $sql="UPDATE department SET Dept_Name='$dept_name' WHERE Dept_Id='$dept_id'";
    $query_run=mysqli_query($connection,$sql);

    if($query_run)
    {
        $_SESSION['status'] = "Department Updated";
        $_SESSION['status_code'] = "success";
        header('Location: d_department.php');
    }
    else
    {
        $_SESSION['status'] = "Error Updating Department";
        $_SESSION['status_code'] = "error";
        header('Location: d_department.php');
    }
}
//DELETE Department
if(isset($_POST['delDept']))
{
    $dept_id = $_POST['deptId'];
    $sql="UPDATE department SET Dept_Status=0 WHERE Dept_Id='$dept_id'";
    $query_run=mysqli_query($connection,$sql);

    if($query_run)
    {
        $_SESSION['status'] = "Department Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: d_department.php');
    }
    else
    {
        $_SESSION['status'] = "Error Deleting Department";
        $_SESSION['status_code'] = "error";
        header('Location: d_department.php');
    }
}

//ADD Activity Category
if(isset($_POST['addActCat']))
{
    $act_cat_name= $_POST['ActCat_name'];
    $dept_id = $_POST['dept_name'];
    $query="INSERT INTO activity_category (Act_Cat_Name, Act_Cat_Status, Dept_Id) VALUES ('$act_cat_name',1,'$dept_id')";
    $query_run=mysqli_query($connection,$query);

    if($query_run){
        $_SESSION['status'] = "Category Added";
        $_SESSION['status_code'] = "success";
        header('Location: d_activity_category.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Category";
        $_SESSION['status_code'] = "error";
        header('Location: d_activity_category.php');
    }
}
//EDIT Activity Category
if(isset($_POST['editActCat']))
{
    $act_cat_id = $_POST['act_cat_id'];
    $act_cat_name = $_POST['act_cat_name'];
    $dept_id = $_POST['dept_name'];

    $query="UPDATE activity_category SET Act_Cat_Name='$act_cat_name', Dept_Id='$dept_id' WHERE Act_Cat_Id='$act_cat_id'";
    // echo $query;
    $query_run=mysqli_query($connection,$query);
    if($query_run){
        $_SESSION['status'] = "Category Updated";
        $_SESSION['status_code'] = "success";
        header('Location: d_activity_category.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Category";
        $_SESSION['status_code'] = "error";
        header('Location: d_activity_category.php');
    }
}
//DELETE Activity Category
if(isset($_POST['delActCat']))
{
    $act_cat_id=$_POST['del_ActCat'];
    $query="UPDATE activity_category SET Act_Cat_Status=0 WHERE Act_Cat_Id='$act_cat_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run){
        $_SESSION['status'] = "Category Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: d_activity_category.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Category";
        $_SESSION['status_code'] = "error";
        header('Location: d_activity_category.php');
    }
}
// ADD ACTIVITIES
if(isset($_POST['addAct']))
{
    $dept_id=$_POST['dept_id'];
    $act_cat_id = $_POST['act_cat'];
    $act_type=$_POST['act_type'];
    if($act_type=='V'){
        $categ='villa';
    }
    elseif($act_type=='B'){
        $categ='building';
    }
    else{
        $categ=null;
    }
    $data = array(
        'act_name' => $_POST['act_name'],
        'act_emp_r' => $_POST['act_emp_r'],
        'act_output_r' => $_POST['act_output_r']
    );
    $count = count($_POST['act_name']);
    for($i=0; $i < $count; $i++){
        // $dept_id=;
        if($dept_id==1){//electrical
            $dept_prename='E';
        }
        else if($dept_id==2){//plumbing
            $dept_prename='P';
        }
        else if($dept_id==3){//hvac
            $dept_prename='H';
        }
        else if($dept_id==4){//fire fighting
            $dept_prename='FF';
        }
        else if($dept_id==5){//fire alarm
            $dept_prename='FA';
        }
        else if($dept_id==6){//low current system
            $dept_prename='LC';
        }
        else{
            $dept_prename='';
        }
        if($act_cat_id){
            $act_query="SELECT * FROM activity_category WHERE Act_Cat_Id='$act_cat_id'";
            $act_query_run=mysqli_query($connection,$act_query);
            if(mysqli_num_rows($act_query_run)>0){
                while($row=mysqli_fetch_assoc($act_query_run)){
                    $act_cat_name =$row['Act_Cat_Name'];
                    if($act_cat_name=='1st Fix'){
                        $cat_prename='1';
                    }
                    else if($act_cat_name=='2nd Fix'){
                        $cat_prename='2';
                    }
                    else if($act_cat_name=='3rd Fix'){
                        $cat_prename='3';
                    }
                    else if($act_cat_name=='Testing & Commissioning'){
                        $cat_prename='TC';
                    }
                    else if($act_cat_name=='Handover'){
                        $cat_prename='H';
                    }
                }
            }
        }
        $search_lastnum = $dept_prename.$act_type.'-'.$cat_prename.'-';
        $q_search="SELECT * FROM activity WHERE Act_Code LIKE '%$search_lastnum%'";
        $q_search_run=mysqli_query($connection,$q_search);
        if(mysqli_num_rows($q_search_run)>0){ $last_num=0;
            while($row1=mysqli_fetch_assoc($q_search_run)){
                $act_code=$row1['Act_Code'];
                $number=str_replace($search_lastnum,'',$act_code);
                $numbers[]=$number;
            }
            //get the highest number
            $last_num= max($numbers)+1;
        }
        $act_code_=$search_lastnum.$last_num;
        $sql="INSERT INTO activity (Act_Code,Act_Name,Act_Status,Act_Cat_Id,Dept_Id,Act_Emp_Ratio,Act_Output_Ratio,Act_Category) VALUES ('$act_code_','{$_POST['act_name'][$i]}',1,'$act_cat_id','$dept_id','{$_POST['act_emp_r'][$i]}','{$_POST['act_output_r'][$i]}','$categ')";
        // echo $sql;
        $query_run=mysqli_query($connection,$sql);
        if($query_run)
        {
            $_SESSION['status'] = "Activity Added";
            $_SESSION['status_code'] = "success";
            header('Location: d_activities.php');
        }
        else{
            $_SESSION['status'] = "Error Adding Activity";
            $_SESSION['status_code'] = "error";
            header('Location: d_activities.php');
        }
    }
}
// EDIT Activity
if(isset($_POST['editAct']))
{
    $act_id=$_POST['act_id'];
    $act_code=$_POST['act_code'];
    $act_name=$_POST['act_name'];
    $dept_id=$_POST['dept_id'];
    $act_cat_id=$_POST['act_cat'];
    $emp_ratio=$_POST['act_emp_r'];
    $output_ratio=$_POST['act_output_r'];

    $query="UPDATE activity SET Act_Name='$act_name', Act_Code='$act_code', Dept_Id='$dept_id', Act_Cat_Id='$act_cat_id', Act_Emp_Ratio='$emp_ratio',Act_Output_Ratio='$output_ratio' WHERE Act_Id='$act_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run){
        $_SESSION['status'] = "Activity Updated";
        $_SESSION['status_code'] = "success";
        header('Location: d_activities.php');
    }
    else{
        $_SESSION['status'] = "Error Updating Activity";
        $_SESSION['status_code'] = "error";
        header('Location: d_activities.php');
    }
}
// DELETE ACTIVITIES
if(isset($_POST['delAct']))
{
    $act_id = $_POST['act_id'];
    $query="UPDATE activity SET Act_Status=0 WHERE Act_Id='$act_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run){
        $_SESSION['status'] = "Activity Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: d_activities.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Activity";
        $_SESSION['status_code'] = "error";
        header('Location: d_activities.php');
    }
}
//ADD PLEX
if(isset($_POST['addPlx']))
{
    $villa_id=$_POST['villa_id'];
    $prj_name = $_POST['prj_name'];
    $data = array(
        'plx_code' => $_POST['plx_code'], 
        'plx_name' => $_POST['plx_name']
    );
    $count = count($_POST['plx_code']);
    for($i=0; $i < $count; $i++){
        $sql="INSERT INTO plex (Plx_Code,Plx_Name,Plx_Status,Villa_Id) VALUES ('{$_POST['plx_code'][$i]}','{$_POST['plx_name'][$i]}',1,'$villa_id')";
        $query_run=mysqli_query($connection,$sql);
        // echo $sql;
    }  
    if($query_run)
    {
        $_SESSION['status'] = "Plex Added";
        $_SESSION['status_code'] = "success";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
    }
    else{
        $_SESSION['status'] = "Error Adding Plex";
        $_SESSION['status_code'] = "error";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
    }
}
//EDIT PLEX
if(isset($_POST['editPlx']))
{
    $plx_id=$_POST['plex_id'];
    $plx_name=$_POST['plx_name'];
    $plx_code=$_POST['plx_code'];
    $villa_id=$_POST['villa_id'];
    $prj_name=$_POST['prj_name'];

    $query="UPDATE plex SET Plx_Code='$plx_code', Plx_Name='$plx_name' WHERE Plx_Id='$plx_id'";
    // echo $query;
    $query_run=mysqli_query($connection, $query);

    if($query_run)
    {
        $_SESSION['status'] = "Plex Updated";
        $_SESSION['status_code'] = "success";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
    }
    else{
        $_SESSION['status'] = "Error Updating Plex";
        $_SESSION['status_code'] = "error";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
    }
}
//DELETE PLEX
if(isset($_POST['delPlx']))
{
    $plx_id=$_POST['plx_id'];
    $villa_id=$_POST['villa_id'];
    $prj_name=$_POST['prj_name'];

    $query="UPDATE plex SET Plx_Status=0 WHERE Plx_Id='$plx_id'";
    $query_run=mysqli_query($connection,$query);
    // echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Plex Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
    }
    else{
        $_SESSION['status'] = "Error Deleting Plex";
        $_SESSION['status_code'] = "error";
        header('Location: p_plex.php?id='.$villa_id.'&prj_name='.$prj_name);
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
        //check if material code exists
        $q_matcode="SELECT * FROM material WHERE Mat_Code='{$_POST['mat_code'][$i]}' AND Mat_Status=1";
        // echo $q_matcode;
        $q_matcode_run=mysqli_query($connection,$q_matcode);
        if(mysqli_num_rows($q_matcode_run)>0)
        {
            $_SESSION['status'] = "Material Code already exists";
            $_SESSION['status_code'] = "error";
            header('Location: m_material.php');
        }
        else{
            $query_run=mysqli_query($connection,$sql);
        }
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
//EDIT MATERIALS
if(isset($_POST['edit_Mat']))
{
    $m_id=$_POST['mat_id'];
    $m_code=$_POST['emat_code'];
    $m_desc=$_POST['emat_desc'];
    $m_unit=$_POST['emat_unit'];
    // $m_qty=$_POST['emat_qty'];
    $dept_id=$_POST['dept_id'];

    //check for duplicate material code
    $q_matcode="SELECT * FROM material WHERE Mat_Code='$m_code' AND Mat_Status=1 AND Mat_Id!=$m_id";
    $q_matcode_run=mysqli_query($connection,$q_matcode);
    if(mysqli_num_rows($q_matcode_run)>0){
        $_SESSION['status'] = "Material Code already exists";
        $_SESSION['status_code'] = "warning";
        header('Location: m_material.php');
    }
    else{
        $query="UPDATE material set Mat_Code='$m_code',Mat_Desc='$m_desc',Mat_Unit='$m_unit',Dept_id='$dept_id' WHERE Mat_Id='$m_id'";
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
// ADD ASGN ACTIVITY
if(isset($_POST['asgnAct']))
{
    $flt_id=$_POST['flat_id'];
    $prj_name=$_POST['prj_name'];
    $act_id=$_POST['act_id'];
    $act_cat_id=$_POST['category_id'];
    
    $check_duplicate = "SELECT * FROM assigned_activity where Flat_Id='$flt_id' and Act_Id='$act_id' and Asgd_Act_Status=1";
    $qrun= mysqli_query($connection,$check_duplicate);

    if(mysqli_num_rows($qrun) > 0)
    {
        $_SESSION['status'] = "Activity Already Assigned";
        $_SESSION['status_code'] = "error";
        header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
    }
    else
    {
        $query="INSERT INTO assigned_activity (Flat_Id,Act_Id,Asgd_Pct_Done,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flt_id','$act_id','0','1','$act_cat_id')";
        $query_run=mysqli_query($connection,$query);

        if($query_run)
        {
            $_SESSION['status'] = "Activity Assigned";
            $_SESSION['status_code'] = "success";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
        else{
            $_SESSION['status'] = "Error Assigning Activity";
            $_SESSION['status_code'] = "error";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
    }
}
// EDIT ASGN ACT PROGRESS
if(isset($_POST['editProg']))
{
    $asgn_act_id = $_POST['asgn_id'];
    $progress = $_POST['e_prog'];
    $prj_name = $_POST['prj_name'];
    $flt_id=$_POST['flat_id'];
    date_default_timezone_set('Asia/Dubai');
    // date today
    $Date = date('Y-m-d');

    if($progress<100)
    {
        $query="UPDATE assigned_activity SET Asgd_Pct_Done='$progress', Asgd_Act_Date_Completed= NULL WHERE Asgd_Act_Id='$asgn_act_id'";
        // echo $query;
        $query_run=mysqli_query($connection,$query);

        if($query_run)
        {
            $_SESSION['status'] = "Progress Updated";
            $_SESSION['status_code'] = "success";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
        else{
            $_SESSION['status'] = "Error Updating Progress";
            $_SESSION['status_code'] = "error";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
    }
    elseif($progress>100)
    {
        $_SESSION['status'] = "Please Enter Valid Progress Value";
        $_SESSION['status_code'] = "warning";
        header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
    }
    elseif($progress==100)
    {
        $query="UPDATE assigned_activity SET Asgd_Pct_Done='$progress', Asgd_Act_Date_Completed='$Date' WHERE Asgd_Act_Id='$asgn_act_id'";
        $query_run=mysqli_query($connection,$query);
        if($query_run)
        {
            $_SESSION['status'] = "Progress Updated";
            $_SESSION['status_code'] = "success";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
        else{
            $_SESSION['status'] = "Error Updating Progress";
            $_SESSION['status_code'] = "error";
            header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
        }
    }
    else
    {
        $_SESSION['status'] = "Error Updating Progress";
        $_SESSION['status_code'] = "error";
        header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
    }

}
// DELETE ASGN ACTIVITY
if(isset($_POST['delAsgnBtn']))
{
    $prj_name=$_POST['prj_name'];
    $asgn_act=$_POST['asgn_act_id'];
    $flt_id=$_POST['flat_id'];

    $query="UPDATE assigned_activity SET Asgd_Act_Status=0 WHERE Asgd_Act_Id='$asgn_act'";
    $query_run=mysqli_query($connection,$query);
// echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Assigned Activity Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
    }
    else{
        $_SESSION['status'] = "Error Deleting Assigned Activity";
        $_SESSION['status_code'] = "error";
        header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
    }
}
// ASSIGN ALL ACTIVITY
if(isset($_POST['AsgnAllAct']))
{
    $prj_name=$_POST['prj_name'];
    $flat_id=$_POST['flat_id'];

    // $query="select * from (SELECT Act_Id FROM activity WHERE Act_Status=1) as query2 except select * from (SELECT Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 AND Flat_Id='$flat_id' ) as query1";
    $query="SELECT Act_Id FROM activity WHERE Act_Status=1 AND Act_Id NOT IN (SELECT Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 AND Flat_Id='$flat_id')";
    $query_run=mysqli_query($connection,$query);

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id=$row['Act_Id'];

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            $act_cat_id = $row2['Act_Cat_Id'];

            $query1="INSERT INTO assigned_activity (Flat_Id,Act_Id,Asgd_Pct_Done,Asgd_Act_Status,Act_Cat_Id) VALUES ($flat_id ,$act_id,'0','1','$act_cat_id')";
            // echo $query1;
            $query_run1=mysqli_query($connection,$query1);
            if($query_run1)
            {
                $_SESSION['status'] = "Activity Assigned";
                $_SESSION['status_code'] = "success";
                header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
            }
            else{
                $_SESSION['status'] = "Error Assigning Activity";
                $_SESSION['status_code'] = "error";
                header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
            }
        }
    }
    else
    {
        $_SESSION['status'] = "Activities Already Assigned";
        $_SESSION['status_code'] = "info";
        header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
    }
}
if(isset($_POST['AsgnActDept']))
{
    $prj_name=$_POST['prj_name'];
    $dept_id=$_POST['dept_id'];
    $flat_id=$_POST['flat_id'];

    // $q_act_ids= "SELECT Act_Id";
    $query="select * from (SELECT Act_Id FROM activity WHERE Act_Status=1 AND Dept_Id='$dept_id') as query2 except select * from (SELECT Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 AND Flat_Id='$flat_id' ) as query1";
    $query_run=mysqli_query($connection,$query);

    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $act_id=$row['Act_Id'];

            $act_query = "SELECT Act_Cat_Id FROM activity WHERE Act_Id='$act_id' LIMIT 1";
            $q_run2=mysqli_query($connection, $act_query);
            $row2 = mysqli_fetch_assoc($q_run2);
            $act_cat_id = $row2['Act_Cat_Id'];

            $query1="INSERT INTO assigned_activity (Flat_Id,Act_Id,Asgd_Pct_Done,Asgd_Act_Status,Act_Cat_Id) VALUES ($flat_id ,$act_id,'0','1','$act_cat_id')";
            // echo $query1;
            $query_run1=mysqli_query($connection,$query1);
            if($query_run1)
            {
                $_SESSION['status'] = "Activity Assigned";
                $_SESSION['status_code'] = "success";
                header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
            }
            else{
                $_SESSION['status'] = "Error Assigning Activity";
                $_SESSION['status_code'] = "error";
                header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
            }
        }
    }
    else
    {
        $_SESSION['status'] = "Activities Already Assigned";
        $_SESSION['status_code'] = "info";
        header('Location: p_asgn_activity.php?id='.$flat_id.'&prj_name='.$prj_name);
    }
}
if(isset($_POST['addMatBtn']))
{
    $act_id = $_POST['act_id'];
    $data = array(
        'mat' => $_POST['mat']
    );
    $count = count($_POST['mat']);
    for($i=0; $i < $count; $i++){
        $query="INSERT INTO assigned_material(Act_Id,Mat_Id,Asgd_Mat_Status) values('$act_id','{$_POST['mat'][$i]}',1)";
        $query_run=mysqli_query($connection,$query);
        // echo $query;
    }
    if($query_run)
    {
        $_SESSION['status'] = "Material Added";
        $_SESSION['status_code'] = "success";
        header('Location: d_activities.php');
    }
    else{
        $_SESSION['status'] = "Error Adding Material";
        $_SESSION['status_code'] = "error";
        header('Location: d_activities.php');
    } 
}
if(isset($_POST['delMatBtn']))
{
    $mat_id= $_POST['mat_id'];
    $query="UPDATE assigned_material SET Asgd_Mat_Status=0 WHERE Asgd_Mat_Id='$mat_id'";
    $query_run=mysqli_query($connection,$query);
    // echo $query;
    if($query_run)
    {
        $_SESSION['status'] = "Material Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: d_activities.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting Material";
        $_SESSION['status_code'] = "error";
        header('Location: d_activities.php');
    }
}
if(isset($_POST['EditQty']))
{
    $prj_name = $_POST['prj_name'];
    $flt_id = $_POST['flt_id'];
    $data = array(
        'asgd_mat_id' => $_POST['asgd_mat_id'],
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['asgd_mat_id']);
    if($count >=1)
    {
        $count = count($_POST['asgd_mat_id']);
        for ($i=0; $i < $count; $i++){
        $query="UPDATE asgn_mat_to_act SET Asgd_Mat_to_Act_Qty ='{$_POST['mat_qty'][$i]}' WHERE Asgd_Mat_to_Act_Id ='{$_POST['asgd_mat_id'][$i]}'";
        $query_run = mysqli_query($connection,$query);
        // echo $query;
            if($query_run)
            {
                $_SESSION['status'] = "Quantity Updated";
                $_SESSION['status_code'] = "success";
                header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
            }
            else{
                $_SESSION['status'] = "Error Updating Quantity";
                $_SESSION['status_code'] = "error";
                header('Location: p_asgn_activity.php?id='.$flt_id.'&prj_name='.$prj_name);
            }
        }
    }
}
// Release
if(isset($_POST['releaseBtn']))
{
    $data = array(
        'mat' => $_POST['mat'], 
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat']);
    for($i=0; $i < $count; $i++){
        $sql="SELECT Mat_Qty from material WHERE Mat_Id ='{$_POST['mat'][$i]}'";
        $query_run=mysqli_query($connection,$sql);
        $row = mysqli_fetch_assoc($query_run);

        $current_qty = $row['Mat_Qty'];
        $updated_qty = $current_qty-$_POST['mat_qty'][$i];
        $add_q ="UPDATE material set Mat_Qty='$updated_qty' WHERE Mat_Id='{$_POST['mat'][$i]}'";
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
    $data = array(
        'mat' => $_POST['mat'], 
        'mat_qty' => $_POST['mat_qty']
    );
    $count = count($_POST['mat']);
    for($i=0; $i < $count; $i++){
        $sql="SELECT Mat_Qty from material WHERE Mat_Id ='{$_POST['mat'][$i]}'";
        $query_run=mysqli_query($connection,$sql);
        $row = mysqli_fetch_assoc($query_run);

        $current_qty = $row['Mat_Qty'];
        $updated_qty = $current_qty+$_POST['mat_qty'][$i];
        $add_q ="UPDATE material set Mat_Qty='$updated_qty' WHERE Mat_Id='{$_POST['mat'][$i]}'";
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
            header('Location: m_asgn_material.php?id='.$prj_id);
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
                header('Location: m_asgn_material.php?id='.$prj_id);
            }
            else{
                $_SESSION['status'] = "Error Adding Material";
                $_SESSION['status_code'] = "error";
                header('Location: m_asgn_material.php?id='.$prj_id);
            } 
        }
       
    }
    
}
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
                header('Location: m_asgn_material.php?id='.$prj_id);
            }
            else{
                $_SESSION['status'] = "Error Adding Materials";
                $_SESSION['status_code'] = "error";
                header('Location: m_asgn_material.php?id='.$prj_id);
            }
        }
    }
    else
    {
        $_SESSION['status'] = "Materials Already Added";
        $_SESSION['status_code'] = "error";
        header('Location: m_asgn_material.php?id='.$prj_id);
    }
}
// QTY UPDATE
if(isset($_POST['edit_MQty']))
{
    $prj_id=$_POST['prj_id'];
    $qty = $_POST['mat_qty'];
    $mat_id = $_POST['mat_id'];

    $query="UPDATE mat_qty set Mat_Q_Qty='$qty' WHERE Mat_Qty_Id='$mat_id'";
    $query_run=mysqli_query($connection,$query);
    if($query_run)
    {
        $_SESSION['status'] = "Material Updated";
        $_SESSION['status_code'] = "success";
        header('Location: m_asgn_material.php?id='.$prj_id);
    }
    else{
        $_SESSION['status'] = "Error Updating Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_asgn_material.php?id='.$prj_id);
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
        header('Location: m_asgn_material.php?id='.$prj_id);
    }
    else{
        $_SESSION['status'] = "Error Deleting Material";
        $_SESSION['status_code'] = "error";
        header('Location: m_asgn_material.php?id='.$prj_id);
    }
}
// IMPORT MATERIALS
if(isset($_POST["import"]))
{
    if($_FILES['file']['name'])
    {
        $prj_id = $_POST['prj_id'];
        
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
                header('location:m_asgn_material.php?success='.$success.'&error='.$error.'&err_names='.$err_names.'&id='.$prj_id); 
            } 
            fclose($handle);
        }
    }
}
if(isset($_POST["addMP"])){
    $mp_name = $_POST['mp_name'];

    $add_mp="INSERT INTO manpower (MP_Name,MP_Status) VALUES ('$mp_name',1)";
    $add_mp_run=mysqli_query($connection,$add_mp);
    if($add_mp_run){
        $_SESSION['status'] = "Manpower Added";
        $_SESSION['status_code'] = "success";
        header('Location: l_manpower.php');
    }
    else{
        $_SESSION['status'] = "Error Adding";
        $_SESSION['status_code'] = "error";
        header('Location: l_manpower.php');
    }
}
if(isset($_POST["editMP"])){
    $mp_name = $_POST['mp_name'];
    $id= $_POST['mp_id'];

    $edit_mp="UPDATE manpower SET MP_Name='$mp_name' where MP_Id='$id'";
    $edit_mp_run=mysqli_query($connection,$edit_mp);
    if($edit_mp_run){
        $_SESSION['status'] = "Manpower Updated";
        $_SESSION['status_code'] = "success";
        header('Location: l_manpower.php');
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: l_manpower.php');
    }
}
if(isset($_POST["delMP"])){
    $id= $_POST['mp_id'];

    $del_mp="UPDATE manpower SET MP_Status=0 where MP_Id='$id'";
    $del_mp_run=mysqli_query($connection,$del_mp);
    if($del_mp_run){
        $_SESSION['status'] = "Manpower Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: l_manpower.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: l_manpower.php');
    }
}
if(isset($_POST["addSB"])){
    $sb_name = $_POST['sb_name'];

    $add_sb="INSERT INTO subcontractor (SB_Name,SB_Status) VALUES ('$sb_name',1)";
    $add_sb_run=mysqli_query($connection,$add_sb);
    if($add_sb_run){
        $_SESSION['status'] = "Subcontractor Added";
        $_SESSION['status_code'] = "success";
        header('Location: l_subcon.php');
    }
    else{
        $_SESSION['status'] = "Error Adding";
        $_SESSION['status_code'] = "error";
        header('Location: l_subcon.php');
    }
}
if(isset($_POST["editSB"])){
    $sb_name = $_POST['sb_name'];
    $id= $_POST['sb_id'];

    $edit_sb="UPDATE subcontractor SET SB_Name='$sb_name' where SB_Id='$id'";
    $edit_sb_run=mysqli_query($connection,$edit_sb);
    if($edit_sb_run){
        $_SESSION['status'] = "Manpower Updated";
        $_SESSION['status_code'] = "success";
        header('Location: l_subcon.php');
    }
    else{
        $_SESSION['status'] = "Error Updating";
        $_SESSION['status_code'] = "error";
        header('Location: l_subcon.php');
    }
}
if(isset($_POST["delSB"])){
    $id= $_POST['sb_id'];

    $del_sb="UPDATE subcontractor SET SB_Status=0 where SB_Id='$id'";
    $del_sb_run=mysqli_query($connection,$del_sb);
    if($del_sb_run){
        $_SESSION['status'] = "Manpower Delelted";
        $_SESSION['status_code'] = "success";
        header('Location: l_subcon.php');
    }
    else{
        $_SESSION['status'] = "Error Deleting";
        $_SESSION['status_code'] = "error";
        header('Location: l_subcon.php');
    }
}
?>