<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); 
$currentMonth = date('m');
$currentYear = date('Y');
$currentDay = date('d');
$today = date('Y-m-d');
    //NET BALANCE
    $currentBal="SELECT SUM(Transaction_Amount) as bal FROM transaction 
        WHERE Transaction_Date<'$today' AND Transaction_Cancel_Status=0
        AND `Transaction_Status`=1";
    $currentBal_run=mysqli_query($connection,$currentBal);
    if($currentBal_run){
        $row=mysqli_fetch_assoc($currentBal_run);
        $currentBalance=$row['bal'];
        $currentBalance_output=number_format($currentBalance,2);
    }
    //total money in 
     $totalIn="SELECT SUM(Transaction_Amount) AS sumIn FROM transaction
                WHERE Transaction_Amount > 0
                AND Transaction_Date >= '$currentYear-$currentMonth-01' AND Transaction_Date <= '$today' 
                AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0"; 
    $totalIn_run=mysqli_query($connection,$totalIn);
    if($totalIn_run){
        $rowIn=mysqli_fetch_assoc($totalIn_run);
        $inAmt=$rowIn['sumIn'];
        $inAmt=number_format($inAmt,2);
    }
    //total money out
     $totalOut="SELECT SUM(Transaction_Amount) AS sumOut FROM transaction
            WHERE Transaction_Amount < 0
            AND Transaction_Date >= '$currentYear-$currentMonth-01' AND Transaction_Date <= '$today' 
            AND `Transaction_Status`=1 AND Transaction_Cancel_Status=0";
    $totalOut_run=mysqli_query($connection,$totalOut);
    if($totalOut_run){
        $rowOut=mysqli_fetch_assoc($totalOut_run);
        $outAmt=$rowOut['sumOut'];
        $outAmt=abs($outAmt);
        $outAmt=number_format($outAmt,2);
    }
// find current month + previous 5 months
$previousMonths=NULL;$balances=NULL; $balance_arr=[];
// Loop to generate the previous 5 months in ascending order
for ($i = 5; $i >= 0; $i--) {
    // Calculate the timestamp for the previous month
    $timestamp = strtotime("-$i months", strtotime("$currentYear-$currentMonth-01"));
    $month =date('m', $timestamp);
    $month_lbl=date('M', $timestamp);
    $yr =date('Y', $timestamp);
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $yr);
    if($currentMonth==1){
        $currentYear=$currentYear--;
    }
    
    // Format the timestamp to get the month and year
    $from =date('Y-m-'.$daysInMonth, $timestamp);
    $previousMonth = date('Y-m-'.$daysInMonth, $timestamp);
    $previousMonths.='"'.$month_lbl.' '.$yr.'",';
    //get opening balance
    $opBalance="SELECT SUM(Transaction_Amount) as bal FROM transaction 
        WHERE Transaction_Date<='$from' AND Transaction_Cancel_Status=0 AND `Transaction_Status`=1";
    // echo $opBalance.'<br>';
    $opBalance_run=mysqli_query($connection,$opBalance);
    if($opBalance_run){
        $rowb=mysqli_fetch_assoc($opBalance_run);
         $opBalance=$rowb['bal'];
         $opBalance_output=number_format($opBalance,0);
         $opBalance_output=$opBalance;
         if($opBalance_output){
         }
         else{
            $opBalance_output=0;
         }
         $balances.=$opBalance_output.",";
         $balance_arr[]=$opBalance;
         
    }
}
if(max($balance_arr)){
}
else{
    $balance_arr[]=1;
}
?>

 <script>
    var maxValue=<?php echo max($balance_arr)?>;
    var newDataset=[<?php echo $balances;?>];
    var newLabel=[<?php echo $previousMonths?>];
  </script>
<div class="container-fluid">   
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info text-uppercase mb-1">Total Net Balance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$currentBalance_output.'</h2>'; ?>
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
                            <?php  echo'<h2>'.$inAmt.'</h2>'; ?>
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-warning text-uppercase mb-1">PAID</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$outAmt.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
$q_acc="SELECT * FROM bank AS b LEFT JOIN account as acc on acc.Bank_Id=b.Bank_Id  WHERE acc.Account_Status=1 AND b.Bank_Status=1";
$q_acc_run=mysqli_query($connection,$q_acc);
$from=$today;
?>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Bank Accounts</h5>
                </div>
                <div class="card-body">
                    <div class="table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <th>Account</th>
                                <th>Balance</th>
                            </thead>
                            <tbody>
                    <?php $total_balance=NULL;
                    if(mysqli_num_rows($q_acc_run)>0){
                        while($rowc=mysqli_fetch_assoc($q_acc_run)){
                            $bank_name=$rowc['Bank_Name'];
                            $bank_code=$rowc['Bank_Code'];
                            $acc_id=$rowc['Account_Id'];
                            $acc_balance=opBalance($from,$acc_id);
                            $total_balance=$total_balance+$acc_balance;
                            $acc_balance=number_format($acc_balance,2);
                        
                    ?>
                                <tr>
                                    <td><a href='bank_trans.php?id=<?php echo $acc_id;?>' target="_blank" rel="noopener noreferrer"><?php echo $bank_name.' ('.$bank_code.')';?></a></td>
                                    <td><?php echo $acc_balance?></td>
                                </tr>
                    <?php 
                        }
                        ?>
                            <tfoot>
                                <tr>
                                    <td><span class="float-right font-weight-bold">Total:</span> </td>
                                    <td><?php echo number_format($total_balance,2);?></td>
                                </tr>
                            </tfoot>
                        <?php
                    }
                    else{
                        echo 'No found accounts';
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-5">
                <div class="card-header py-3">
                    <h5 class="m-0 font-weight-bold text-primary">Account Balance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <div class="form-group">
                <label for="">Month</label>
                <select name="month[]" id="month" class="form-control selectpicker" multiple></select>
            </div>
        </div>
        <div class="col-2">
            <div class="form-group">
                <label for="">Year</label>
                <select name="year[]" id="year" class="form-control selectpicker" multiple></select>
            </div>
        </div>
        <div class="col-2">
            <label for="">Project:</label>
            <select name="prj_id[]" id="prj_opt" class="form-control selectpicker"></select>
        </div>
    </div>
    <div class="row" id="prj_row"> </div>
    <div id="chart"></div>
</div>
<div>
    <div id="container" style="width: 75%;">
    <canvas id="canvas"></canvas>
    </div>
</div>
<script>
$(document).ready(function(){
    var month_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'month_opt': month_opt},
        success:function(data){
            $(document).find('#month').html(data).change();
            $('#month').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'yr_opt':month_opt},
        success:function(data){
            $(document).find('#year').html(data).change();
            $('#year').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'prj_opt':month_opt},
        success:function(data){
            // data = "<option value='all'>All</option>"+data;
            $(document).find('#prj_opt').html(data).change();
            $('#prj_opt').selectpicker('refresh');
        }
    });
});
//filter reports
$(document).ready(function(){
    $('select').on('change', function () {
        var prj_id=$('#prj_opt').val();
        var month=$('#month').val();
        var year=$('#year').val();
        var default_f="true";
        var month_q="AND MONTH(Transaction_Date)='<?php echo $currentMonth?>'"; 
        var year_q="AND YEAR(Transaction_Date)='<?php echo $currentYear?>'"; 
        var prj_q=""; 
        var months=""; var years=""; var year_name="";var month_name="";
        if(month!="" || year!=""){
            month_q=""; year_q="";
        }
        if(!month.includes('all') && month!=""){
            month_name=(month.join(","));
            months=("'" + month.join("','") + "'");
            month_q=" AND MONTH(Transaction_Date) IN ("+months+") ";
            default_f="false";
        }
        if(!year.includes('all') && year!=""){
            years=("'" + year.join("','") + "'");
            year_name=(year.join(","));
            year_q=" AND YEAR(Transaction_Date) IN ("+years+") ";
            default_f="false";
        }
        if(prj_id){
            if(prj_id=='all'){
                prj_q="";
            }
            else{
                prj_q=" AND Prj_Id='"+prj_id+"'";
            }
        }
        var query ="SELECT SUM(Transaction_Amount) as income,Transaction_Category_Id, SUM(CASE WHEN Transaction_Amount < 0 THEN Transaction_Amount ELSE 0 END) AS TotalNegative, SUM(CASE WHEN Transaction_Amount > 0 THEN Transaction_Amount ELSE 0 END) AS TotalPositive FROM transaction  WHERE Transaction_Status=1  AND Transaction_Cancel_Status=0 "+month_q+" "+year_q+" "+prj_q+" GROUP BY Transaction_Category_Id";
        var t_query ="SELECT * FROM transaction  WHERE Transaction_Status=1  AND Transaction_Cancel_Status=0 "+month_q+" "+year_q+" "+prj_q+"";
        $.ajax({
            url:'accQuery.php',
            method: 'POST',
            data:{'filter':query,
                    'prj_id':prj_id,
                    'default':default_f,
                    'months':month_name,
                    'years':year_name,
                    't_query':t_query
            },
            success:function(data){
                $('#chart').html(data).change();
                $('#dataTable').DataTable({
                    "searching": true,
                    "bPaginate": true
                });
            }
        });
        
    });
});
$(document).ready(function () {
    $(document).on('click', '.cat_trans', function() {
        
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>