
<?php 
include('../dbconfig.php');
if(isset($_POST['acc_statement'])){
    $acc_id=$_POST['acc_id'];
    $month=$_POST['month'];
    $yr=$_POST['yr'];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $yr);
    $q_acc="SELECT * FROM account as acc 
        LEFT JOIN bank as b on b.Bank_Id=acc.Bank_Id
        WHERE acc.Account_Status=1 AND acc.Account_Id='$acc_id'";
    $q_acc_run=mysqli_query($connection,$q_acc);
    //account information
    if(mysqli_num_rows($q_acc_run)==1){ 
        $row=mysqli_fetch_assoc($q_acc_run);
        $acc_name=$row['Account_Name'];
        $iban=$row['Account_IBAN'];
        $bank_code=$row['Bank_Code'];
        $bank_name=$row['Bank_Name'];
    }
    $from=$yr.'-'.$month.'-01';
    $to=$yr.'-'.$month.'-'.$daysInMonth;
    //get opening balance
    $opBalance="SELECT SUM(Transaction_Amount) as bal FROM transaction 
        WHERE Transaction_Date<'$from' AND Account_Id='$acc_id' 
        AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $opBalance_run=mysqli_query($connection,$opBalance);
    if($opBalance_run){
        $rowb=mysqli_fetch_assoc($opBalance_run);
        $opBalance=$rowb['bal'];
        $opBalance_output=number_format($opBalance,2);
    }
    //total money in 
    $totalIn="SELECT SUM(Transaction_Amount) AS sumIn FROM transaction
                WHERE Transaction_Amount > 0
                AND Transaction_Date >= '$from' AND Transaction_Date <= '$to' 
                AND Account_Id='$acc_id' AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $totalIn_run=mysqli_query($connection,$totalIn);
    if($totalIn_run){
        $rowIn=mysqli_fetch_assoc($totalIn_run);
        $inAmt=$rowIn['sumIn'];
        $inAmt=number_format($inAmt,2);
    }
    //total money out
    $totalOut="SELECT SUM(Transaction_Amount) AS sumOut FROM transaction
            WHERE Transaction_Amount < 0
            AND Transaction_Date >= '$from' AND Transaction_Date <= '$to' 
            AND Account_Id='$acc_id' AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $totalOut_run=mysqli_query($connection,$totalOut);
    if($totalOut_run){
        $rowOut=mysqli_fetch_assoc($totalOut_run);
        $outAmt=$rowOut['sumOut'];
        $outAmt=number_format($outAmt,2);
    }
    //transactions
    $q_trans="SELECT * FROM transaction 
                WHERE Account_Id='$acc_id' AND MONTH(Transaction_Date)='$month' 
                AND YEAR(Transaction_Date)='$yr' AND Transaction_Status=1 AND Transaction_Cancel_Status=0";
    $q_trans_run=mysqli_query($connection,$q_trans);
    if(mysqli_num_rows($q_trans_run)>0){ 
        $balance=$opBalance; $trans_html='';
        while($rowT=mysqli_fetch_assoc($q_trans_run)){
            $date=$rowT['Transaction_Date'];
            $date_formated=date('d-M-Y',strtotime($date));
            $chq_no=$rowT['Transaction_Cheque_No'];
            $details=$rowT['Transaction_Details'];
            $amount=$rowT['Transaction_Amount'];
            $balance=$balance+$amount;
            $amount=number_format($amount, 2);
            $balance_ouput=number_format($balance,2);
            $p_amt=""; $r_amt=""; $mode="";
            if($amount>0){
                $r_amt=$amount; $mode='received';
            }
            else{
                $p_amt=$amount; $mode='paid';
            }
            $trans_html .='
            <tr>
                <td >'.$date.'</td>
                <td >'.$details.'</td>
                <td >'.$chq_no.'</td>
                <td >'.$p_amt.'</td>
                <td >'.$r_amt.'</td>
                <td>'.$balance_ouput.'</td>
            </tr>';
        }
    }

$html = '
    <table width="100%" id="dataTable">
        <thead>
            <th width="20%"></th>
            <th width="50%"></th>
            <th width="30%"></th>
        </thead>
        <tr>
            <td>Bank: </td>
            <td>'.$bank_name.' ('.$bank_code.')</td>
        </tr>
        <tr>
            <td>Account Name: </td>
            <td>'.$acc_name.'</td>
        </tr>
        <tr>
            <td>Account Number: </td>
            <td>IBAN NO '.$iban.'</td>
        </tr>
        <tr>
            <td>Period Covered: </td>
            <td>'.$month.'/1/'.$yr.' - '.$month.'/'.$daysInMonth.'/'.$yr.'</td>
        </tr>
        <tr>
            <td ><h5 class="mt-4 font-weight-bold"> Account Summary </h5></td>
        </tr>
        <tr>
            <td>Opening Balance: </td>
            <td>'.$opBalance_output.'</td>
        </tr>
        <tr>
            <td>Total Money In: </td>
            <td>'.$inAmt.'</td>
        </tr>
        <tr>
            <td>Total Money Out: </td>
            <td>'.$outAmt.'</td>
        </tr>
        <tr>
            <td colspan="7"> <hr></td> 
        </tr>
        <tr>
            <td>Transactions</td>  
        </tr>
        <tr>
            <td colspan="7">
                <table class="table table-bordered table-sm" width="100%">
                    <thead>
                        <th width="">Date</th>
                        <th width="">Description</th>
                        <th>Ref.No /Cheque No.</th>
                        <th>Withdrawal</th>
                        <th>Deposit</th>
                        <th>Balance</th>
                    </thead>
                    <tbody>
                    '.$trans_html.'
                    </tbody>
                </table>
            <td>
        </tr>
    </table>
    ';
echo $html;
}
?>