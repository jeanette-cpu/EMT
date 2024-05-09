<?php
include('../security.php');
// include('accQuery.php');
//MONTH OPT
if(isset($_POST['month_opt'])){
    if($_POST['month_opt']){
    }
    else{
        $options.='<option value="all">All</option>';
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
if(isset($_POST['month_opt2'])){
    $options='
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
//YEAR OPT
if(isset($_POST['yr_opt'])){
    if($_POST['yr_opt']){
    }
    else{
        $options.='<option value="all">All</option>';
    }
    $options.='
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
        <option value="2027">2027</option>';
    echo $options;
}
//PRJ OPT
if(isset($_POST['prj_opt'])){
    $q_prj="SELECT Prj_Id, Prj_Code, Prj_Name  FROM project WHERE Prj_Status=1";
    $q_prj_run=mysqli_query($connection,$q_prj);
    if(mysqli_num_rows($q_prj_run)>0){
        if($_POST['prj_opt']){
            $options='<option value="NULL">None</option>';
        }
        else{
            $options='<option value="all">All</option>';
        }
        while($row_p=mysqli_fetch_assoc($q_prj_run)){
            $prj_id=$row_p['Prj_Id'];
            $prj_code=$row_p['Prj_Code'];
            $prj_name=$row_p['Prj_Name'];
            $options.="<option value=".$prj_id.">".$prj_code.' '.$prj_name."</option>";
        }
    }
    echo $options;
}
// ACCOUNT OPTIONS
if(isset($_POST['acc_opt'])){
    $q_acc="SELECT * FROM account as acc
            LEFT JOIN bank as b on b.Bank_Id=acc.Bank_Id
            WHERE acc.Account_Status=1 AND b.Bank_Status=1";
    $q_acc_run=mysqli_query($connection,$q_acc);
    if(mysqli_num_rows($q_acc_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($q_acc_run)){
            $bank_code=$row['Bank_Code'];
            $bname=$row['Bank_Name'];
            $acc_holder=$row['Account_Name'];
            $acc_id=$row['Account_Id'];
            
            $options .="<option value='$acc_id'>".$bank_code.' ('.$acc_holder.")</option>";
        }
    }
    echo $options;
}
//CATEGORY OPTIONS
if(isset($_POST['cat_opt'])){
    if(isset($_POST['cat_opt'])){
    }
    else{
        $options.='<option value="">No Category</option>';
    }
    $q_cat="SELECT * FROM transaction_category WHERE Transaction_Category_Status =1";
    $q_cat_run=mysqli_query($connection,$q_cat);
    if(mysqli_num_rows($q_cat_run)>0){
        while($row=mysqli_fetch_assoc($q_cat_run)){
            $cat_id=$row['Transaction_Category_Id'];
            $cat_code=$row['Transaction_Cat_Code'];
            $cat_name=$row['Transaction_Category_Description'];
            $options .="<option value='$cat_id'>".$cat_name."</option>";
        }
    }
    echo $options;
}
// ACCOUNT OPTIONS BUT BANK NAME ONLY SHOWS
if(isset($_POST['bank_opt'])){
    $q_acc="SELECT * FROM account as acc
            LEFT JOIN bank as b on b.Bank_Id=acc.Bank_Id
            WHERE acc.Account_Status=1 AND b.Bank_Status=1";
    $q_acc_run=mysqli_query($connection,$q_acc);
    if(mysqli_num_rows($q_acc_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($q_acc_run)){
            $bank_code=$row['Bank_Code'];
            $bname=$row['Bank_Name'];
            $acc_holder=$row['Account_Name'];
            $acc_id=$row['Account_Id'];
            
            $options .="<option value='$acc_id'>".$bname."</option>";
        }
    }
    echo $options;
}
//BANK OPTIONS
if(isset($_POST['bbank_opt'])){
    $q_bank="SELECT * FROM bank WHERE Bank_Status=1";
    $q_bank_run=mysqli_query($connection,$q_bank);
    if(mysqli_num_rows($q_bank_run)>0){
        $options="";
        while($row_b=mysqli_fetch_assoc($q_bank_run)){
            $bank_id=$row_b['Bank_Id'];
            $bank_name=$row_b['Bank_Name'];
            $options .="<option value=".$bank_id.">".$bank_name."</option>";
        }
    }
    echo $options;
}
//TRANSACTION TYPES
if(isset($_POST['transTypeOpt'])){
    $trans_type="SELECT * FROM transaction_type WHERE Transaction_Type_Status=1";
    $trans_type_run=mysqli_query($connection,$trans_type);
    if(mysqli_num_rows($trans_type_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($trans_type_run)){
            $trans_type_id=$row['Transaction_Type_Id'];
            $trans_type_name=$row['Transaction_Type_Name'];
            $options .="<option value='$trans_type_id'>".$trans_type_name."</option>";
        }
    }
    echo $options;
}
//TRANSACTION STATUS 
if(isset($_POST['tran_stat_opt'])){
    $trans_stat="SELECT * FROM transaction_status WHERE Transaction_Status_Status=1";
    $trans_stat_run=mysqli_query($connection,$trans_stat);
    if(mysqli_num_rows($trans_stat_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($trans_stat_run)){
            $trans_stat_id=$row['Transaction_Status_Id'];
            $trans_stat_desc=$row['Transaction_Status_Description'];
            $options .="<option value='$trans_stat_id'>".$trans_stat_desc."</option>";
        }
    }
    echo $options;
}
if(isset($_POST['bankfile'])){
    $filename= $_POST['bankfile'];
    $backslash='\ ';
    $backslash= str_replace(' ', '', $backslash);
    $first='<iframe src=\'..\uploads\bankAccProf';
    $end='\' width=\'100%\' style=\'height:100%\'></iframe>';
    $file=$first.$backslash.$filename.$end;
    echo $file;
}

//cheque opt with bank filter
?>