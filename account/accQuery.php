<?php
$currentYear = date('Y');
$currentMonth = date('m');
$currentDay = date('d');
include('../dbconfig.php');
$q_transac_type="SELECT * FROM transaction_type WHERE Transaction_Type_Status=1";
$q_transac_type_run=mysqli_query($connection,$q_transac_type);
$q_transac_status="SELECT * FROM transaction_status WHERE Transaction_Status_Status=1";
$q_transact_status_run=mysqli_query($connection,$q_transac_status);
$q_trans="SELECT * FROM transaction as t
            LEFT JOIN account as acc on acc.Account_Id =t.Account_Id
            LEFT JOIN bank as b on b.Bank_Id =acc.Bank_Id
            WHERE t.Transaction_Status=1 ORDER BY t.Transaction_Date ASC";
$q_trans_run=mysqli_query($connection,$q_trans);
//transaction codes 
$q_codes="SELECT * FROM chq_code WHERE Chq_Code_Status=1";
$q_codes_run=mysqli_query($connection,$q_codes);
//transaction category
$q_category="SELECT * FROM transaction_category WHERE Transaction_Category_Status=1";
$q_cat_run=mysqli_query($connection,$q_category);
//check monitoring all checks per account
$q_chq="SELECT * FROM  transaction 
        WHERE Transaction_Status=1 AND Account_Id='1'
        ORDER BY Transaction_Cheque_No ASC";
$q_chq_run=mysqli_query($connection,$q_chq);
//trans plan 
$q_plan="SELECT * FROM payment_plan as pp
        LEFT JOIN transaction_category as tc on tc.Transaction_Category_Id=pp.Transaction_Category_Id
        WHERE pp.Plan_Status=1 AND tc.Transaction_Category_Status=1";
$q_plan_run=mysqli_query($connection,$q_plan);
//get project code
function prjCode($prj_id){
    include('../dbconfig.php');
    $q_pcode="SELECT * FROM project WHERE Prj_Status=1 AND Prj_Id='$prj_id'";
    $q_pcode_run=mysqli_query($connection,$q_pcode);
    if(mysqli_num_rows($q_pcode_run)==1){
        $row=mysqli_fetch_assoc($q_pcode_run);
        $prj_code=$row['Prj_Code'];
        return $prj_code;
    }
}
function prjName($prj_id){
    include('../dbconfig.php');
    $q_pcode="SELECT * FROM project WHERE Prj_Status=1 AND Prj_Id='$prj_id'";
    $q_pcode_run=mysqli_query($connection,$q_pcode);
    if(mysqli_num_rows($q_pcode_run)==1){
        $row=mysqli_fetch_assoc($q_pcode_run);
        $prj_code=$row['Prj_Code']; $prj_name=$row['Prj_Name'];
        return $prj_code.' '.$prj_name;
    }
}
function statName($status_id){
    include('../dbconfig.php');
    $q_stat_name="SELECT * FROM transaction_status WHERE Transaction_Status_Id='$status_id' AND Transaction_Status_Status=1";
    $q_stat_name_run=mysqli_query($connection,$q_stat_name);
    if(mysqli_num_rows($q_stat_name_run)==1){
        $row=mysqli_fetch_assoc($q_stat_name_run);
        $status=$row['Transaction_Status_Description'];
        return $status;
    }
}
function transTypeName($trans_type_id){
    include('../dbconfig.php');
    $q_trans_type="SELECT * FROM transaction_type WHERE Transaction_Type_Status=1 AND Transaction_Type_Id='$trans_type_id'";
    $q_trans_type_run=mysqli_query($connection,$q_trans_type);
    if(mysqli_num_rows($q_trans_type_run)==1){
        $row=mysqli_fetch_assoc($q_trans_type_run);
        $ttype=$row['Transaction_Type_Name'];
        return $ttype;
    }
}
function bankName($acc_id){
    include('../dbconfig.php');
    $q_bank="SELECT Bank_Name FROM bank AS b
            LEFT JOIN account as acc on acc.Bank_Id=b.Bank_Id 
            WHERE Account_Id='$acc_id' AND acc.Account_Status=1 AND b.Bank_Status=1";
    $q_bank_run=mysqli_query($connection,$q_bank);
    if(mysqli_num_rows($q_bank_run)>0){
        $row=mysqli_fetch_assoc($q_bank_run);
        $bank_name=$row['Bank_Name'];
        return $bank_name;
    }
}
function catName($cat_id){
    include('../dbconfig.php');
    $q_cat="SELECT * FROM transaction_category WHERE Transaction_Category_Status=1 AND Transaction_Category_Id='$cat_id'";
    $q_cat_run=mysqli_query($connection,$q_cat);
    if(mysqli_num_rows($q_cat_run)>0){
        $row=mysqli_fetch_assoc($q_cat_run);
        $cat_name=$row['Transaction_Category_Description'];
        return $cat_name;
    }
}
function bankCode($acc_id){
    include('../dbconfig.php');
    $q_bank="SELECT Bank_Code FROM bank AS b
            LEFT JOIN account as acc on acc.Bank_Id=b.Bank_Id 
            WHERE Account_Id='$acc_id' AND acc.Account_Status=1 AND b.Bank_Status=1";
    $q_bank_run=mysqli_query($connection,$q_bank);
    if(mysqli_num_rows($q_bank_run)>0){
        $row=mysqli_fetch_assoc($q_bank_run);
        $bank_code=$row['Bank_Code'];
        return $bank_code;
    }
}
function chqNo($t_id){
    include('../dbconfig.php');
    $q_trans="SELECT * FROM  transaction 
        WHERE Transaction_Status=1 AND Transaction_Id='$t_id'";
    $q_trans_run=mysqli_query($connection,$q_trans);
    if(mysqli_num_rows($q_trans_run)>0){
        while($row=mysqli_fetch_assoc($q_trans_run)){
            $chq_no=$row['Transaction_Cheque_No'];
            return $chq_no;
        }
    }
}
if(isset($_POST['bankCode'])){
    $acc_id=$_POST['bankCode'];
    $q_bank="SELECT Bank_Code FROM bank AS b
            LEFT JOIN account as acc on acc.Bank_Id=b.Bank_Id 
            WHERE Account_Id='$acc_id' AND acc.Account_Status=1 AND b.Bank_Status=1";
    $q_bank_run=mysqli_query($connection,$q_bank);
    if(mysqli_num_rows($q_bank_run)>0){
        $row=mysqli_fetch_assoc($q_bank_run);
        $bank_code=$row['Bank_Code'];
        echo $bank_code;
    }
}
function chqStatus($acc_id,$chq_no,$code){
    include('../dbconfig.php');
    $html=NULL;
    //search if code valid
    $q_code="SELECT * FROM chq_code WHERE Chq_Code_Status=1 AND Chq_Code='$code'";
    $q_code_run=mysqli_query($connection,$q_code);
    if(mysqli_num_rows($q_code_run)>0){
        $q_trans="SELECT * FROM transaction WHERE Account_Id='$acc_id' AND Transaction_Cheque_No LIKE '%$chq_no%' AND Transaction_Status=1";
        $q_trans_run=mysqli_query($connection,$q_trans);
        if(mysqli_num_rows($q_trans_run)>0){    
            while($row=mysqli_fetch_assoc($q_trans_run)){
                $chq_date=$row['Transaction_Date'];
                $bank_name=bankName($acc_id);
                $details=$row['Transaction_Details'];
                $remarks=$row['Transaction_Remarks'];
                $status_id=$row['Transaction_Status_Id'];
                $html ='
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <table>
                            <thead>
                                <th width="40%"></th>
                                <th width="60%"></th>
                            </thead>
                            <tr>
                                <td class="font-weight-bold">Date: </td>
                                <td>Jan. 02, 2023</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Bank: </td>
                                <td>Abu Dhabi Commercial</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Details: </td>
                                <td>Clients</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Amount: </td>
                                <td>10,000.00 AED</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Remarks: </td>
                                <td>Check with accounts</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Status: </td>
                                <td>Released</td>
                            </tr>
                        </table>
                    </div>
                </div>';
            }
            return $html;
        }
    }
}
if(isset($_POST['chqStatus'])){
    $acc_id=$_POST['acc_id1'];
    $comp_name=$_POST['comp_name'];
    $chq_no=$_POST['chq_no'];
    $code=$_POST['code'];
    $html=NULL;
    //search if code valid
    $q_code="SELECT * FROM chq_code WHERE Chq_Code_Status=1 AND Chq_Code='$code'";
    $q_code_run=mysqli_query($connection,$q_code);
    if(mysqli_num_rows($q_code_run)>0){
        $q_trans="SELECT * FROM transaction WHERE Account_Id='$acc_id' AND Transaction_Details  LIKE '%$comp_name%' AND Transaction_Status=1";
        $q_trans_run=mysqli_query($connection,$q_trans);
        if(mysqli_num_rows($q_trans_run)>0){    
            $html ='
                <div class="card shadow mb-4">
                    <div class="card-body">
                    <h4 class="h4 mb-2 text-gray-800">Cheque Details</h4>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <th>Date</th>
                                <th>Chq. No</th>
                                <th>Bank</th>
                                <th>Details</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Status</th>
                            </thead>
                            <tbody>';
            while($row=mysqli_fetch_assoc($q_trans_run)){
                $chq_date=$row['Transaction_Date'];
                $date_formated=date('M d, Y',strtotime($chq_date));
                $bank_name=bankName($acc_id);
                $chq_no=$row['Transaction_Cheque_No'];
                $details=$row['Transaction_Details'];
                $remarks=$row['Transaction_Remarks'];
                $status_id=$row['Transaction_Status_Id'];
                $status=statName($status_id);
                $amount=$row['Transaction_Amount'];
                $amount=abs($amount);
                $amount=number_format($amount, 2);
                $html .='
                            <tr>
                                <td>'.$date_formated.'</td>
                                <td>'.$chq_no.'</td>
                                <td>'.$bank_name.'</td>
                                <td>'.$details.'</td>
                                <td>'.$amount.'</td>
                                <td>'.$remarks.'</td>
                                <td>'.$status.'</td>
                            </tr>';
                        
            }
            $html.='        <tbody>
                        </table>
                    </div>
                </div>';
            echo $html;
        }
        else{
            echo 'No Record Found';
        }
    }
    else{
        echo 'Invalid Code';
    }
}
// balance as of today include this day
function opBalance($from,$acc_id){
    include('../dbconfig.php');
    //get opening balance
    $opBalance="SELECT SUM(Transaction_Amount) as bal FROM transaction 
        WHERE Transaction_Date<='$from' AND Account_Id IN ('$acc_id') 
        AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $opBalance_run=mysqli_query($connection,$opBalance);
    if($opBalance_run){
        $rowb=mysqli_fetch_assoc($opBalance_run);
        $opBalance=$rowb['bal'];
        // $opBalance_output=number_format($opBalance,2);
        return $opBalance;
    }
}
//balance before date
function opBalBefore($date,$acc_ids){
    include('../dbconfig.php');
    if($acc_ids=='all'){
        $q_acc="SELECT Account_Id FROM account WHERE Account_Status=1";
        $q_acc_run=mysqli_query($connection,$q_acc);
        if(mysqli_num_rows($q_acc_run)>0){
            while($row=mysqli_fetch_assoc($q_acc_run)){
                $acc_id_arr[]=$row['Account_Id'];
            }
            $acc_ids=implode("', '", $acc_id_arr);
        }
    }
    //get opening balance
    $opBalance="SELECT SUM(Transaction_Amount) as bal FROM transaction 
        WHERE Transaction_Date<'$date' AND Account_Id IN ('$acc_ids') 
        AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $opBalance_run=mysqli_query($connection,$opBalance);
    if($opBalance_run){
        $rowb=mysqli_fetch_assoc($opBalance_run);
        $opBalance=$rowb['bal'];
        // $opBalance_output=number_format($opBalance,2);
        return $opBalance;
    }
}
//get number of transaction per tran status
function getStatusNo($status_id,$acc_id){
    include('../dbconfig.php');
    //search for cancell id
    $c_id=cancelId();
     if($status_id==$c_id){
        $cancelQ="";
    }
    else{
        $cancelQ="AND `Transaction_Cancel_Status`=0";
    }
    $q_stat="SELECT COUNT(Transaction_Id) AS no, SUM(Transaction_Amount) AS tot_amt FROM transaction 
            WHERE Account_Id='$acc_id' AND Transaction_Status_Id='$status_id' AND Transaction_Status=1 $cancelQ";
    $q_stat_run=mysqli_query($connection,$q_stat);
    if(mysqli_num_rows($q_stat_run)>0){
        $row=mysqli_fetch_assoc($q_stat_run);
        $no=$row['no'];
        $tot_amt=$row['tot_amt'];
    }
   
    //get paid
    $paid_q="SELECT SUM(Transaction_Amount) AS sumIn FROM transaction
            WHERE Transaction_Amount < 0 AND Account_Id='$acc_id' AND 
            Transaction_Status_Id='$status_id' AND Transaction_Status=1 $cancelQ ";
    $paid_q_run=mysqli_query($connection,$paid_q);
    if(mysqli_num_rows($paid_q_run)>0){
        $row_1=mysqli_fetch_assoc($paid_q_run);
        $p_amt=$row_1['sumIn'];
        $p_amt=abs($p_amt);
    }
    //get received
    $r_q="SELECT SUM(Transaction_Amount) AS sumOut FROM transaction
            WHERE Transaction_Amount > 0 AND Account_Id='$acc_id' AND 
            Transaction_Status_Id='$status_id' AND Transaction_Status=1  $cancelQ";
    $r_q_run=mysqli_query($connection,$r_q);
    if(mysqli_num_rows($r_q_run)>0){
        $row_2=mysqli_fetch_assoc($r_q_run);
        $r_amt=$row_2['sumOut'];
        $r_amt=abs($r_amt);
    }
    $result=array(
        'no' => $no,
        'stat_amt' => $tot_amt,
        'p_amt' => $p_amt,
        'r_amt' => $r_amt
    );
    return $result;
}

//expense tbl
$q_expense="SELECT * FROM expected_expense 
            WHERE Exp_Status=1 AND MONTH(Exp_Date)='$currentMonth' 
            AND YEAR(Exp_Date)='$currentYear'";
$q_expense_run=mysqli_query($connection,$q_expense);
//function get month expected expense (with cat id and only current)
function getMonthExpAmt($t_cat_id){
    $currentYear = date('Y');
    $currentMonth = date('m');
    include('../dbconfig.php');
    $q="SELECT sum(Exp_Amount) as amt FROM expected_expense 
        WHERE MONTH(Exp_Date)='$currentMonth' AND YEAR(Exp_Date)='$currentYear' AND Transaction_Category_Id='$t_cat_id' AND Exp_Status=1";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
// expected expense month
function ExpMonth($month,$year,$ccat_id=null){
    include('../dbconfig.php');
    if($ccat_id==NULL){
        $q="SELECT sum(Exp_Amount) as amt FROM expected_expense 
        WHERE MONTH(Exp_Date)='$month' AND YEAR(Exp_Date)='$year' AND Exp_Status=1";
    }
    else{
        $q="SELECT sum(Exp_Amount) as amt FROM expected_expense 
        WHERE MONTH(Exp_Date)='$month' AND YEAR(Exp_Date)='$year' AND Transaction_Category_Id='$ccat_id' AND Exp_Status=1";
    }
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
// expected expense month
function incomeMonth($month,$year,$ccat_id=null){
    include('../dbconfig.php');
    if($ccat_id==NULL){
        $q="SELECT sum(Income_Amount) as amt FROM expected_income 
        WHERE MONTH(Income_Date)='$month' AND YEAR(Income_Date)='$year' AND Income_Status=1";
    }
    else{
        $q="SELECT sum(Income_Amount) as amt FROM expected_income 
        WHERE MONTH(Income_Date)='$month' AND YEAR(Income_Date)='$year' AND Transaction_Category_Id='$ccat_id' AND Income_Status=1";
    }
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
// get month actual expense
function getActualAmt($t_cat_id){
    $currentYear = date('Y');
    $currentMonth = date('m');
    include('../dbconfig.php');
    $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$currentMonth'
         AND YEAR(Transaction_Date)='$currentYear' AND Transaction_Amount < 0 AND Transaction_Category_Id='$t_cat_id' AND Transaction_Cancel_Status=0";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
// get month actual income
function getActualIncome($t_cat_id){
    $currentYear = date('Y');
    $currentMonth = date('m');
    include('../dbconfig.php');
    $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$currentMonth'
         AND YEAR(Transaction_Date)='$currentYear' AND Transaction_Amount > 0 AND Transaction_Category_Id='$t_cat_id' AND Transaction_Cancel_Status=0";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
//function get month expected expense (with cat id and only current)
function getMonthExpProfit($t_cat_id){
    $currentYear = date('Y');
    $currentMonth = date('m');
    include('../dbconfig.php'); $total_amt=0;
    $q="SELECT sum(Income_Amount) as amt FROM expected_income 
        WHERE MONTH(Income_Date)='$currentMonth' AND YEAR(Income_Date)='$currentYear' AND Transaction_Category_Id='$t_cat_id' AND Income_Status=1";
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    return $total_amt;
}
//actual month expense
function actualMonth($month,$yr,$ccat_id=null){
    include('../dbconfig.php');
    if($ccat_id==NULL){
        $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$month'
         AND YEAR(Transaction_Date)='$yr' AND Transaction_Amount < 0 AND Transaction_Cancel_Status=0";
    }
    else{
        $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$month'
         AND YEAR(Transaction_Date)='$yr' AND Transaction_Amount < 0 AND Transaction_Category_Id='$ccat_id' AND Transaction_Cancel_Status=0";
    }
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    $total_amt=abs($total_amt);
    return $total_amt;
}
//actual month income
function actualMonthInc($month,$yr,$ccat_id=null){
    include('../dbconfig.php');
    if($ccat_id==NULL){
        $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$month'
         AND YEAR(Transaction_Date)='$yr' AND Transaction_Amount > 0 AND Transaction_Cancel_Status=0";
    }
    else{
        $q="SELECT SUM(Transaction_Amount) as amt FROM `transaction` 
        WHERE Transaction_Status=1 AND month(Transaction_Date)='$month'
         AND YEAR(Transaction_Date)='$yr' AND Transaction_Amount > 0 AND Transaction_Category_Id='$ccat_id' AND Transaction_Cancel_Status=0";
    }
    $q_run=mysqli_query($connection,$q);
    if(mysqli_num_rows($q_run)){
        $row_q=mysqli_fetch_assoc($q_run);
        $total_amt=$row_q['amt'];
    }
    $total_amt=abs($total_amt);
    return $total_amt;
}
//CHEQUE OPTIONS 
if(isset($_POST['chq_opt_p'])){
    $cat_id=$_POST['chq_opt_p'];
    $q_chq="SELECT * FROM transaction WHERE Transaction_Status=1 AND Transaction_Category_Id='$cat_id' AND Transaction_Amount<0 AND Transaction_Cancel_Status=0";
    $q_chq_run=mysqli_query($connection,$q_chq);
    if(mysqli_num_rows($q_chq_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($q_chq_run)){
            $t_id=$row['Transaction_Id'];
            $chq_no=$row['Transaction_Cheque_No'];
            $acc_id=$row['Account_Id'];
            $bank_code=bankCode($acc_id);
            $options .="<option value=".$t_id.">".$bank_code.'-'.$chq_no."</option>";
        }
    }
    echo $options;
}
//CHEQUE OPTIONS 
if(isset($_POST['chq_opt_r'])){
    $cat_id=$_POST['cat_id_r'];
    $q_chq="SELECT * FROM transaction WHERE Transaction_Status=1 AND Transaction_Category_Id='$cat_id' AND Transaction_Amount>0 AND Transaction_Cancel_Status=0";
    $q_chq_run=mysqli_query($connection,$q_chq);
    if(mysqli_num_rows($q_chq_run)>0){
        $options="";
        while($row=mysqli_fetch_assoc($q_chq_run)){
            $t_id=$row['Transaction_Id'];
            $chq_no=$row['Transaction_Cheque_No'];
            $acc_id=$row['Account_Id'];
            $bank_code=bankCode($acc_id);
            $options .="<option value=".$t_id.">".$bank_code.'-'.$chq_no."</option>";
        }
    }
    echo $options;
}
//filter reports sa index
if(isset($_POST['filter'])){
    $q_trans=$_POST['filter'];
    $t_query=$_POST['t_query'];
    // echo;
    $prj_id=$_POST['prj_id'];
    $default=$_POST['default'];
    $months=$_POST['months'];
    $years=$_POST['years'];
    $colors = [ "#87bc45","#ede15b", "#ef9b20","#bdcf32","#ea5545", "#27aeef", "#edbf33", "#b33dc6"];
    $colorMapping = []; // An associative array to store the color for each element
    if($default=='true'){
        $months=date('F');
        $years=date('Y');
    }
    else{
        if($months==""){
            $months="All";
        }
        else{
            $monthNumberArray = explode(',', $months); // Split the string into an array
            $monthNames = [];
            foreach ($monthNumberArray as $monthNumber) {
                $monthName = date("F", mktime(0, 0, 0, $monthNumber, 1));
                $monthNames[] = $monthName;
            }
            $months = implode(', ', $monthNames); 
        }
        if($years==""){
            $years="All";
        }
    }
    $q_trans_run=mysqli_query($connection,$q_trans);
    if(mysqli_num_rows($q_trans_run)>0){ 
        $total_income=0; $tot_neg=0;$tot_pos=0;$earn_cat_labels ="";$paid_data=NULL;
        $earn_data=NULL;$cat_pos=0;$exp_cat_labels  ="";$exp_lbl=NULL;$earn_lbl=NULL;
        while($row_f=mysqli_fetch_assoc($q_trans_run)){
            $cat_income=$row_f['income'];
            $cat_id=$row_f['Transaction_Category_Id'];
            $cat_name=catName($cat_id);
            if($cat_name){
            }
            else{
                $cat_name='Uncategorized';
            }
            $cat_neg=$row_f['TotalNegative'];
            $cat_pos=$row_f['TotalPositive'];
            $total_income=$total_income+$cat_income;
            $tot_neg=$tot_neg+$cat_neg;
            $tot_pos=$tot_pos+$cat_pos;
            if($cat_pos!=0){ // earning details
                $earn_arr[]=number_format($cat_pos,2);
                $earn_lbl[]=$cat_name;
                $earn_cat_ids[]=$cat_id;
                $earn_data.=$cat_pos.',';
                $earn_cat_labels.='"'.$cat_name.'",';
            }
            if($cat_neg!=0){
                $exp_cat_ids[]=$cat_id;
                $cat_neg=abs($cat_neg);
                $exp_arr[]=number_format($cat_neg,2);
                $exp_lbl[]=$cat_name;
                $paid_data.=$cat_neg.',';
                $exp_cat_labels.='"'.$cat_name.'",';
            }
        }
        $colors_pie='"'.implode('", "', $colors).'"';
        $total_income=number_format($total_income,2);
        $tot_pos=number_format($tot_pos,2);
        $tot_neg=abs($tot_neg);
        $tot_neg=number_format($tot_neg,2);
        ?>
    <script>
        //expense
        var newDataset_cat=[<?php echo $paid_data;?>];
        var newLabel_cat=[<?php echo $exp_cat_labels?>];
        //earning
        var earning_label=[<?php echo $earn_cat_labels;?>];
        var newDataset_earn=[<?php echo $earn_data;?>];
        var colors=[<?php echo $colors_pie; ?>];
    </script>
        <?php
        echo '<div class="row text-dark">
                <div class="col-2 h3">
                    Period Cover 
                </div>
                <div class="col-4 h4">
                    Months: '.$months.'
                </div>
                <div class="col-3 h4">
                    Year: '.$years.'
                </div>
            </div>';
        if($prj_id!='all'){
            $prj_name=prjName($prj_id);
            if($prj_name){
                echo '
                <div class="row">
                    <div class="col-12 h2 text-primary">
                        '.$prj_name.' 
                    </div>
                </div>';
            }
        }
        if($default=='false'){
            echo '
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-s font-weight-bold text-info text-uppercase mb-1">Earnings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <h2>'.$total_income.'</h2>
                                </div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-fw fa-usd fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-s font-weight-bold text-success text-uppercase mb-1">RECEIVED</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <h2>'.$tot_pos.'</h2>
                                </div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-fw fa-arrow-up fa-2x text-success text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                <div class="text-s font-weight-bold text-warning text-uppercase mb-1">EXPENSE</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <h2>'.$tot_neg.'</h2>
                                </div>
                                </div>
                                <div class="col-auto">
                                <i class="fas fa-fw fa-usd fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        //CHART
        echo '
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-5">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary">Received</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="chart-bar">
                                            <canvas id="myPieChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-6">';
                if($earn_lbl){
                    for ($i = 0; $i < count($earn_lbl); $i++) {
                        $colorMapping[$earn_lbl[$i]] = $colors[$i % count($colors)];
                        echo'                           
                                            <div class="row">
                                                <div class="col-5">
                                                    <span class="mr-2 cat_trans">
                                                        <i class="fas fa-square" style="color: '.$colors[$i % count($colors)].'"></i>  '.$earn_lbl[$i].'
                                                        <input type="hidden" value="'.$earn_cat_ids[$i].'">
                                                    </span>
                                                </div>
                                                <div class="col-6 text-right">
                                                    '.$earn_arr[$i].'
                                                </div>
                                            </div>';
                    }
                    echo'   <hr>
                                        <div class="row">
                                            <div class="col-5">Total</div>
                                            <div class="col-6 text-right font-weight-bold">
                                                '.$tot_pos.'
                                            </div>
                                        </div>';
                }
                else{
                    echo'
                    <div class="row">
                        <div class="col-12 text-center mt-4">
                            <a href="#" class="btn btn-info btn-circle btn-lg mb-2 mt-4">
                                <i class="fas fa-info-circle "></i>
                            </a>
                            <br>
                            <div> <h5 class="mb-3">No Trasancations Found</h5></div>
                        </div>
                    </div>';
                }
                                
                                echo '</div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-5">
                            <div class="card-header py-3">
                                <h5 class="m-0 font-weight-bold text-primary">Expense/Paid</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="chart-bar">
                                            <canvas id="expenseChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-6">';
                if($exp_lbl){
                    for ($i = 0; $i < count($exp_lbl); $i++) {
                        $colorMapping[$exp_lbl[$i]] = $colors[$i % count($colors)];
                        echo'                           
                                            <div class="row">
                                                <div class="col-5">
                                                    <span class="mr-2 cat_trans">
                                                        <i class="fas fa-square" style="color: '.$colors[$i % count($colors)].'"></i>  '.$exp_lbl[$i].'
                                                        <input type="hidden" value="'.$exp_cat_ids[$i].'">
                                                    </span>
                                                </div>
                                                <div class="col-6 text-right">
                                                    '.$exp_arr[$i].'
                                                </div>
                                            </div>';
                    }
                }
                else{
                    echo'
                    <div class="row">
                        <div class="col-12 text-center mt-4">
                            <a href="#" class="btn btn-info btn-circle btn-lg mb-2 mt-4">
                                <i class="fas fa-info-circle "></i>
                            </a>
                            <br>
                            <div> <h5 class="mb-3">No Trasancations Found</h5></div>
                        </div>
                    </div>';
                }
                                echo'</div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shadow mb-5">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary"><a href="transaction.php" target="_blank" rel="noopener noreferrer">Transactions</a></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead> 
                                    <th>Date</th>
                                    <th>Chq No</th>
                                    <th>Details</th>
                                    <th>PAID</th>
                                    <th>RECEIVED</th>
                                    <th>Category</th>
                                </thead>
                                <tbody>';
            $trans_run=mysqli_query($connection,$t_query);
            if(mysqli_num_rows($trans_run)>0){ $total_rAmt=0; $total_pAmt=0;
                while($row_t=mysqli_fetch_assoc($trans_run)){
                    $date=$row_t['Transaction_Date'];
                    $date_formated=date('d-M-Y',strtotime($date));
                    $chq_no=$row_t['Transaction_Cheque_No'];
                    $details=$row_t['Transaction_Details'];
                    $amount=$row_t['Transaction_Amount'];
                    $cat_id=$row_t['Transaction_Category_Id']; $cat_name2=catName($cat_id);
                    if($cat_name2==""){
                        $cat_name2="Uncategorized";
                    }
                    $p_amt=""; $r_amt="";
                    if($amount>0){
                        $r_amt=$amount; $mode='received';
                        $total_rAmt=$total_rAmt+$r_amt;
                        $r_amt=number_format($r_amt, 2);
                    }
                    else{
                        $p_amt=$amount; $mode='paid';
                        $total_pAmt=$total_pAmt+$p_amt;
                        $p_amt=abs($p_amt);
                        $p_amt=number_format($p_amt, 2);
                    }
                    echo '
                    <tr>
                        <td>'.$date_formated.'</td>
                        <td>'.$chq_no.'</td>
                        <td>'.$details.'</td>
                        <td>'.$p_amt.'</td>
                        <td>'.$r_amt.'</td>
                        <td>'.$cat_name2.'</td>
                    </tr>';
                }
            }
            $total_pAmt=abs($total_pAmt);
            $total_pAmt=number_format($total_pAmt,2);
            $total_rAmt=number_format($total_rAmt,2);
                        echo    '</tbody>
                                <tfoot>
                                    <td colspan="3"></td>
                                    <td>'.$total_pAmt.'</td>
                                    <td>'.$total_rAmt.'</td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>';
    }
    else{
        echo '
        <div class="row">
            <div class="col-12 text-center mt-4">
                <a href="#" class="btn btn-info btn-circle btn-lg mb-2">
                    <i class="fas fa-info-circle"></i>
                </a>
                <br>
                <div> <h5 class="mb-3">No Trasancations Found</h5></div>
            </div>
        </div>
       ';
    }
    ?>
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example  ELECTRICAL
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: earning_label,
            datasets: [{
            data: newDataset_earn,
            backgroundColor: colors,
            //   hoverBackgroundColor: ['#f4b30d', '#f57626'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: false
            },
            cutoutPercentage: 75,
        },
        });
        var idk = document.getElementById("expenseChart");
        var expenseChart = new Chart(idk, {
        type: 'doughnut',
        data: {
            labels: newLabel_cat,
            datasets: [{
            data: newDataset_cat,
            backgroundColor: colors,
            //   hoverBackgroundColor: ['#f4b30d', '#f57626'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: false
            },
            cutoutPercentage: 75,
        },
        });
    </script>
    <?php 
}
function eeAmt($ee_id){
    include('../dbconfig.php');
    $q="SELECT Exp_Amount FROM expected_expense WHERE Expense_Id='$ee_id'";
    $q_run=mysqli_query($connection,$q);
    $row=mysqli_fetch_assoc($q_run);
    $amt=$row['Exp_Amount'];
    return $amt;
}
function cancelId(){
    include('../dbconfig.php');
    $q="SELECT Transaction_Status_Id FROM transaction_status WHERE Transaction_Status_Description LIKE '%cancel%' AND Transaction_Status_Status=1";
    $q_run=mysqli_query($connection,$q);
    $row=mysqli_fetch_assoc($q_run);
    $c_id=$row['Transaction_Status_Id'];
    return $c_id;
}
?>

