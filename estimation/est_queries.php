<?php 
include('../dbconfig.php');
$prj="SELECT * FROM project_estimation WHERE PE_Status=1";
$prj_run=mysqli_query($connection,$prj);
$no_est_prj="SELECT prj.Prj_Est_Id, prj.PE_Code, prj.PE_Name FROM project_estimation AS prj
                LEFT JOIN estimate as est ON est.Prj_Est_Id=prj.Prj_Est_Id
                WHERE est.Est_Status is null AND prj.PE_Status=1 AND prj.PE_Code!='' ORDER BY prj.PE_Date ";
$no_est_prj_run=mysqli_query($connection,$no_est_prj);
$prj_sys="SELECT * FROM project_system WHERE Prj_Sys_Status=1";
$prj_sys_run=mysqli_query($connection,$prj_sys);
$client="SELECT * FROM client WHERE Client_Status=1";
$client_run=mysqli_query($connection,$client);
$consultant="SELECT * FROM consultant WHERE Consultant_Status=1";
$consultant_run=mysqli_query($connection,$consultant);
$mc="SELECT * FROM main_contractor WHERE Main_Contractor_Status=1";
$mc_run=mysqli_query($connection,$mc);
// $est="SELECT * FROM estimate AS est
//         LEFT JOIN project_estimation as prj ON est.Prj_Est_Id=prj.Prj_Est_Id
//         WHERE est.Est_Status=1 AND prj.PE_Status=1 ORDER BY prj.PE_Date";
// $est_run=mysqli_query($connection,$est);
$est_stat="SELECT * FROM estimate_status WHERE Est_Status_Status=1";
$est_stat_run=mysqli_query($connection,$est_stat);
$user="SELECT * FROM users WHERE USERTYPE='estimation' AND USER_STATUS=1";
$user_run=mysqli_query($connection,$user);
$target="SELECT Target_Id,Target_Prj_No, YEAR(Target_Date) AS year FROM target WHERE Target_Status=1";
$target_run=mysqli_query($connection,$target);
////////// FUNCTIONS 
function deptName($dept_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM department WHERE Dept_Status=1 AND Dept_Id='$dept_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $dept_name=$row['Dept_Name'];
        return $dept_name;
    }
}
function serialCode() {
    // Get the current Unix timestamp
    $timestamp = time();
    // Convert the timestamp to a string and add any additional formatting if needed
    $serialCode = date('YmdHis', $timestamp);
    return $serialCode;
}
function clientName($client_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM client WHERE Client_Status=1 AND Client_Id='$client_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $name=$row['Client_Name'];
        return $name;
    }
}
function mcName($mc_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM main_contractor WHERE Main_Contractor_Status=1 AND Main_Contractor_Id='$mc_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $name=$row['Main_Contractor_Name'];
        return $name;
    }
}
function consName($cons_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM consultant WHERE Consultant_Status=1 AND Consultant_Id='$cons_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $name=$row['Consultant_Name'];
        return $name;
    }
}
function sysName($sys_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM project_system WHERE Prj_Sys_Status=1 AND Prj_Sys_Id='$sys_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $name=$row['Prj_Sys_Desc'];
        return $name;
    }
}
function sysDeptName($sys_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM project_system WHERE Prj_Sys_Status=1 AND Prj_Sys_Id='$sys_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $dept_id=$row['Dept_Id'];
        $dept_name=deptName($dept_id);
        // $name=$row['Prj_Sys_Desc'];
        return $dept_name;
    }
}
function sysDeptId($sys_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM project_system WHERE Prj_Sys_Status=1 AND Prj_Sys_Id='$sys_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $dept_id=$row['Dept_Id'];
        return $dept_id;
    }
}
function statName($stat_id){
    include('../dbconfig.php');
    $query= "SELECT * FROM estimate_status WHERE Est_Status_Status=1 AND Estimate_Status_Id='$stat_id'";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $name=$row['Est_Status'];
        return $name;
    }
}
function lastPrjCode(){
    include('../dbconfig.php');
    $hf="SELECT max(PE_Code) as lastcode FROM project_estimation WHERE PE_Status=1";
    $hf_run=mysqli_query($connection,$hf);
    if(mysqli_num_rows($hf_run)>0){
        $row=mysqli_fetch_assoc($hf_run);
        // $last_id=$row['Prj_Est_Id'];
        $last_code=$row['lastcode'];
        $last_code = substr($last_code, 2);
        $last_code=$last_code+1;
        $prj_code="P-".$last_code;
    }
    else{
        $prj_code=1;
    }
    return $prj_code;
}
function draftId(){
    include('../dbconfig.php');
    $query= "SELECT Estimate_Status_Id FROM estimate_status WHERE Est_Status LIKE '%draft%' AND Est_Status_Status=1";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $id=$row['Estimate_Status_Id'];
        return $id;
    }
}
function quotedStatId(){
    include('../dbconfig.php');
    $query= "SELECT Estimate_Status_Id FROM estimate_status WHERE Est_Status LIKE '%Quoted%' AND Est_Status_Status=1";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $id=$row['Estimate_Status_Id'];
        return $id;
    }
}
function wonStatId(){
    include('../dbconfig.php');
    $query= "SELECT Estimate_Status_Id FROM estimate_status WHERE Est_Status LIKE '%Won%' AND Est_Status_Status=1";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run)>0){
        $row=mysqli_fetch_assoc($query_run);
        $id=$row['Estimate_Status_Id'];
        return $id;
    }
}
function awardedVal($stat_id,$year){
    
}
//
if(isset($_POST['client_opt'])){
    if(mysqli_num_rows($client_run)>0){
        $options.="<option value=''>None</option>";
        while($row = mysqli_fetch_assoc($client_run)){
            $client_id=$row['Client_Id'];
            $client_desc=$row['Client_Name'];
            $options.="<option value=".$client_id.">".$client_desc."</option>";
        }
    }
    echo $options;
}
// projects with no estimation 
if(isset($_POST['add_est_prj'])){
    if(mysqli_num_rows($no_est_prj_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($no_est_prj_run)){
            $prj_id=$row['Prj_Est_Id'];
            $prj_code=$row['PE_Code'];
            $prj_name=$row['PE_Name'];
            $options.="<option value=".$prj_id.">".$prj_code." ".$prj_name."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['mc_opt'])){
    if(mysqli_num_rows($mc_run)>0){
        $options.="<option value=''>None</option>";
        while($row = mysqli_fetch_assoc($mc_run)){
            $mc_id=$row['Main_Contractor_Id'];
            $mc=$row['Main_Contractor_Name'];
            $options.="<option value=".$mc_id.">".$mc."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['cons_opt'])){
    if(mysqli_num_rows($consultant_run)>0){
        $options.="<option value=''>None</option>";
        while($row = mysqli_fetch_assoc($consultant_run)){
            $cons_id=$row['Consultant_Id'];
            $cons=$row['Consultant_Name'];
            $options.="<option value=".$cons_id.">".$cons."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['prj_opt'])){
    if(mysqli_num_rows($prj_run)>0){
        $options.="<option value=''>None</option>";
        while($row = mysqli_fetch_assoc($prj_run)){
            $prj_id=$row['Prj_Est_Id'];
            $prj=$row['PE_Name'];
            $options.="<option value=".$prj_id.">".$prj."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['status_opt'])){
    if(mysqli_num_rows($est_stat_run)>0){
        while($row = mysqli_fetch_assoc($est_stat_run)){
            $es_id=$row['Estimate_Status_Id'];
            $es=$row['Est_Status'];
            $options.="<option value=".$es_id.">".$es."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['sys_opt'])){
    $dept_id=$_POST['sys_opt'];
    if($dept_id){
        $q="SELECT * FROM project_system WHERE Prj_Sys_Status=1 AND Dept_Id='$dept_id'";
    }
    else{
        $q="SELECT * FROM project_system WHERE Prj_Sys_Status=1";
    }
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)>0){
        while($row = mysqli_fetch_assoc($q_run)){
            $sys_id=$row['Prj_Sys_Id'];
            $sys=$row['Prj_Sys_Desc'];
            $options.="<option value=".$sys_id.">".$sys."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['month_opt'])){
    if($_POST['month_opt']){
    }
    else{
        $options.='<option value="">All</option>';
    }
    $options.='
    <option value="1">January</option>
    <option value="2">February</option>
    <option value="3">March</option>
    <option value="4">April</option>
    <option value="5">May</option>
    <option value="6">June</option>
    <option value="7">July</option>
    <option value="8">August</option>
    <option value="9">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>';
    echo $options;
}
if(isset($_POST['yr_opt'])){
    if($_POST['yr_opt']){
        $options.='<option value="">Select Year</option>';
    }
    else{
        $options.='<option value="">All</option>';
    }
    $options.='
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>';
    echo $options;
}
if(isset($_POST['ptype_opt'])){
    if($_POST['ptype_opt']){
        $options.='<option value="">All</option>';
    }
    else{
        $options.='<option value="">Select Type</option>';
    }
    $options.='
        <option value="Residential">Residential</option>
        <option value="Hotel">Hotel</option>
        <option value="Car Parking">Car Parking</option>
        <option value="Mosque">Mosque</option>
        <option value="Labour Camp">Labour Camp</option>
        <option value="Commercial">Commercial</option>
        <option value="Town Houses">Town Houses</option>
        <option value="Villa">Villa</option>
        <option value="School">School</option>
        <option value="Others">Others</option>';
    echo $options;
}
if(isset($_POST['emirateOpt'])){
    $options.='
        <option value="Abu Dhabi">Abu Dhabi</option>
        <option value="Ajman">Ajman</option>
        <option value="Dubai">Dubai</option>
        <option value="Fujairah">Fujairah</option>
        <option value="Ras al Khaimah">Ras al Khaimah</option>
        <option value="Sharjah">Sharjah</option>
        <option value="Umm al Quwain">Umm al Quwain</option>';
    echo $options;
}
?>