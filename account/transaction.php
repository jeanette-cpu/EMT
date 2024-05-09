<?php
use Mpdf\Tag\Td;
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
error_reporting(0);
include('accQuery.php'); 
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
$query_run2=mysqli_query($connection,$sql);
$row2 = mysqli_fetch_assoc($query_run2);
$user_id = $row2['USER_ID'];
?>

<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-list-alt" aria-hidden="true"></i> Manage Transactions
                <!-- BUTTON -->
                <button type="button" class="btn btn-sm btn-primary float-right mr-1 " data-toggle="modal" data-target="#addTransaction">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add Transaction
                </button>
                <button type="button" name="upload" class="btn btn-sm btn-success float-right mr-3" data-toggle="modal" data-target="#importTrans">
                    <i class="fa fa-upload" aria-hidden="true"></i> Upload
                </button>
            </h5>
        </div>
        <div class="card-body">
            <form action="transaction.php" method="post">
            <div class="form-row">
                <div class="col-1">
                    <label for="">Month</label>
                    <select name="month[]" id="month" class="form-control selectpicker monthOpt" multiple></select>
                </div>
                <div class="col-1">
                    <label for="">Year</label>
                    <select name="year[]" id="year" class="form-control selectpicker" multiple ></select>
                </div>
                <div class="col-2">
                    <label for="">Account</label>
                    <select name="acc_id[]" id="acc_opt1" class="form-control acc_opt1 selectpicker" multiple ></select>
                </div>
                <div class="col-1">
                    <label for="">Status</label>
                    <select name="status[]" id="status_opt1" class="form-control status_opt1 selectpicker" multiple ></select>
                </div>
                <div class="col-2">
                    <label for="">Project:</label>
                    <select name="prj_id[]" id="prj_opt" class="form-control selectpicker" multiple ></select>
                </div>
                <div class="col-2">
                    <label for="">Cheque No.</label>
                    <input type="number" name="chq_no" class="form-control">
                </div>
                <div class="col-1">
                    <label for="" class="invisible">D</label><br>
                    <button class="btn btn-warning" name="search_t" id="search">Search <i class="fas fa-fw fa-search "></i></button>
                </div>
                </form>
                <div class="col-1">
                    <label for="" class="invisible">D</label><br>
                    <div class="btn-group ">
                        <form action="code.php" method="POST">
                            <input type="hidden" id="chkIds" name="chkIds[]" >
                            <button class="btn btn-sm btn-outline-warning" name="cancelTrans" type="submit">CANCEL</button>
                        </form>
                        <form action="code.php" method="POST">
                            <input type="hidden" id="chkIds1" name="chkIds" >
                            <button class="btn btn-sm btn-outline-danger ml-2" name="multDelTrans" type="submit">DELETE</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <?php 
                $balance=0;
                if(isset($_POST['search_t'])){
                    $chq_no=$_POST['chq_no'];
                    $acc_ids=NULL;
                    if($chq_no){
                        $chq_q=" AND t.Transaction_Cheque_No LIKE'%$chq_no%'";
                    }
                    else{
                        $chq_q=NULL;
                    }
                    if($_POST['month']){
                        if(in_array("all",$_POST['month'])){
                        }
                        else{
                            $months=implode("', '", $_POST['month']);
                            $month_q =" AND MONTH(t.Transaction_Date) IN ('$months')";
                        }
                    }
                    else{ $month_q=NULL; }
                    if($_POST['year']){
                        if(in_array("all",$_POST['year'])){
                        }
                        else{
                            $years=implode("', '", $_POST['year']);
                            $yr_q =" AND YEAR(t.Transaction_Date) IN ('$years')";
                        }
                    }
                    else { $yr_q=NULL;}
                    if($_POST['acc_id']){
                        if(in_array("all",$_POST['acc_id'])){
                            $acc_ids='all';
                        }
                        else{
                            $acc_ids=implode("', '", $_POST['acc_id']);
                            $acc_id_q=" AND t.Account_Id IN ('$acc_ids')";
                        }
                    }
                    else{ $acc_id_q=NULL; $acc_ids='all';}
                    if($_POST['status']){
                        if(in_array("all",$_POST['status'])){
                        }
                        else{
                            $stat_ids=implode("', '", $_POST['status']);
                            $stat_q=" AND t.Transaction_Status_Id IN ('$stat_ids')";
                        }
                    }
                    else { $stat_q=NULL;}
                    if($_POST['prj_id']){
                        if(in_array("all",$_POST['prj_id'])){
                        }
                        else{
                            $prj_ids=implode("', '", $_POST['prj_id']);
                            $prj_q=" AND t.Prj_Id IN ('$prj_ids')";
                        }
                    }
                    else { $prj_q=NULL;}
                    $q_trans ="SELECT * FROM transaction as t
                            LEFT JOIN account as acc on acc.Account_Id =t.Account_Id
                            LEFT JOIN bank as b on b.Bank_Id =acc.Bank_Id
                            WHERE t.Transaction_Status=1 
                            $month_q $yr_q $acc_id_q $stat_q $prj_q $chq_q
                            ORDER BY t.Transaction_Date ASC LIMIT 500";
                }
                else{
                    $acc_ids='all'; $currentMonth = date('m'); $currentYear = date('Y');
                    $q_trans = "SELECT * FROM transaction as t
                            LEFT JOIN account as acc on acc.Account_Id =t.Account_Id
                            LEFT JOIN bank as b on b.Bank_Id =acc.Bank_Id
                            WHERE t.Transaction_Status=1 AND t.Transaction_Cancel_Status=0  AND MONTH(Transaction_Date)=$currentMonth AND YEAR(Transaction_Date)=$currentYear
                            ORDER BY t.Transaction_Date ASC LIMIT 500";
                }
                $q_trans2=$q_trans;
                if($acc_ids=='all'){
                    $q_acc="SELECT Account_Id FROM account WHERE Account_Status=1";
                }
                else{
                    $q_acc="SELECT Account_Id FROM account WHERE Account_Status=1 AND Account_Id IN('$acc_ids')";
                }
                $q_acc_run=mysqli_query($connection,$q_acc);
                $q_acc_run2=mysqli_query($connection,$q_acc);
                $q_trans_run2=mysqli_query($connection,$q_trans2);
                if(mysqli_num_rows($q_acc_run)>0){
                    $th_html=''; $td_html='';$acc_cnt=0;
                    while($row_th=mysqli_fetch_assoc($q_acc_run)){
                        $acc_id=$row_th['Account_Id'];
                        $acc_balance=0; $acc_tot_p=0; $acc_tot_r=0;
                        $new_data=array('account_id'=>$acc_id,'place'=>$acc_cnt,'balance'=>$acc_balance,'acc_tot_r'=>$acc_tot_r,'acc_tot_p'=>$acc_tot_p);
                        $acc_arr[]=$new_data;
                        $bank_=bankCode($acc_id);
                        $th_html.='<th colspan="2" class="text-center">'.$bank_.'</th><th></th>';
                        $td_html.='<td colspan="2"></td><td></td>';
                        $acc_cnt++;
                    }
                }
                ?>
            <table class="" border="1" id="dataTable" width="100%" cellspacing="0">
            <!-- <table class="table table-bordered table-sm table-striped table-hover" border=1 id="dataTable" width="100%" cellspacing="0"> -->
                <thead class="border border-3">
                    <th class="d-none" colspan="2"></th>
                    <th class="">Date<span class="invisible">cccccccccc</span></th>
                    <th class="">Chq.No</th>
                    <th><span class="invisible">cc</span>Bank<span class="invisible">cc</span></th>
                    <th>Details <span class="invisible">cccccccccccccccccccccccccccccccccccccccccc</span></th>
                    <th>Type    <span class="invisible">ccccccccccccccccccccccc</span></th>
                    <th class="text-danger text-center"><span class="invisible">cccc</span>PAID<span class="invisible">cccc</span></th>
                    <th class="text-success text-center"><span class="invisible">c</span>RECEIVED<span class="invisible">c</span></th>
                    <?php  echo $th_html; ?>
                    <th><span class="invisible">cccc</span>Balance<span class="invisible">cccc</span></th>
                    <th>Status <span class="invisible">ccccccccccccccccccccccc</span></th>
                    <th>Remarks<span class="invisible">ccccccccccccccccccccccc</span></th>
                    <th>PrjCode</th>
                    <th>Category<span class="invisible">cccccccccccccccccccc</span></th>
                    <th>Actions</th>
                    <th></th>
                </thead>
                <tbody>
                <?php 
                    //additional headers
                    $q_trans_run=mysqli_query($connection,$q_trans);
                    if(mysqli_num_rows($q_trans_run)>0){
                         $total_rAmt=0; $total_pAmt=0; $firstRow=true;
                        while($row=mysqli_fetch_assoc($q_trans_run)){
                            $trans_id=$row['Transaction_Id'];
                            $date=$row['Transaction_Date'];
                            $date_formated=date('d-M-Y',strtotime($date));
                            $chq_no=$row['Transaction_Cheque_No'];
                            $bank_code=$row['Bank_Code'];
                            $details=$row['Transaction_Details'];
                            $amount=$row['Transaction_Amount'];
                            $cancel_stat=$row['Transaction_Cancel_Status'];
                            $cat_id=$row['Transaction_Category_Id']; $cat_name=catName($cat_id);
                            $preTag=""; $endTag="";
                            
                            $trans_type_id=$row['Transaction_Type_Id'];     $trans_type_name=transTypeName($trans_type_id);
                            $status_id=$row['Transaction_Status_Id'];       $status=statName($status_id);
                            $remarks=$row['Transaction_Remarks'];
                            $prj_id=$row['Prj_Id'];                         $prj_code=prjCode($prj_id);
                            
                            $p_amt=""; $r_amt=""; $mode="";
                            if($cancel_stat!=1){
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
                            }
                            else{
                                if($amount>0){
                                    $r_amt=$amount; $mode='received';
                                    $r_amt=number_format($r_amt, 2);
                                }
                                else{
                                    $p_amt=$amount; $mode='paid';
                                    $p_amt=abs($p_amt);
                                    $p_amt=number_format($p_amt, 2);
                                }
                            }
                            if($firstRow){
                                $prev_ids=$acc_ids;
                                if(mysqli_num_rows($q_acc_run2)>0){
                                    $th_html=''; $td_html='';$bank_bal=0;
                                    while($row_td=mysqli_fetch_assoc($q_acc_run2)){
                                        $acc_ids=$row_td['Account_Id']; 
                                        $bank_bal=opBalBefore($date,$acc_ids);
                                        //updating balance 
                                        foreach ($acc_arr as &$subarray) {
                                            if ($subarray['account_id'] == $acc_ids) {
                                                // echo $bank_bal.' '.$acc_ids.'<br>';
                                                if($bank_bal){
                                                    $subarray['balance'] = $bank_bal;
                                                }
                                                else{
                                                    $subarray['balance'] = 0;
                                                }
                                                break; // Stop searching after the first match
                                            }
                                        }
                                        $bank_bal=number_format($bank_bal,2);
                                        $acc_id=$acc_ids;
                                        $bc_name=bankCode($acc_id);
                                        $td_html.='<td colspan="2"><span class="float-right font-weight-bold">'.$bank_bal.'</span></td><td></td>';
                                        $td_html2.='<td class="text-center text-danger font-weight-bold"><span class="invisible">cccc</span>PAID<span class="invisible">cccc</span></td>
                                                    <td class="text-center text-success font-weight-bold"><span class="invisible">c</span>RECEIVED<span class="invisible">c</span></td>
                                                    <td><small class="font-weight-bold">'.$bc_name.'Balance</small></td>';
                                        
                                    }
                                }
                                $acc_ids=$prev_ids;
                                $op_bal=opBalBefore($date,$acc_ids);
                                $balance=$balance+($op_bal);
                                $opBalOutput=number_format($op_bal,2);
                                $firstRow=false;
                                echo '<tr class="table-info table-border" >
                                        <td colspan="5">Beginning Balance</td> 
                                        <td colspan="2"><span class="float-right font-weight-bold">'.$opBalOutput.'</span></td> 
                                        '.$td_html.'
                                        <td><span class="float-right ">'.$opBalOutput.'</span></td>
                                        <td colspan="6"></td> 
                                    </tr>
                                    <tr class="table-info">
                                        <td colspan="5"></td> 
                                        <td colspan="2"><span class="float-right font-weight-bold"></span></td> 
                                        '.$td_html2.'
                                        <td><span class="float-right"></span></td> 
                                        <td colspan="6"></td> 
                                    </tr>';
                                    // var_dump($acc_arr);
                            }
                            if($cancel_stat==1){
                                $preTag="<s>"; $endTag="</s>";
                            }
                            else{
                                $balance=$balance+($amount);
                            }
                            $balance_ouput=number_format($balance,2);
                            $acc_id=$row['Account_Id'];
                            
                            ?>
                    <tr class="border border-bottom-4">
                        <td class="d-none"><?php echo $trans_id;?></td> <!-- 0-->
                        <td class="d-none"><?php echo $date?></td> <!-- 1-->
                        <td><?php echo $date_formated;?></td> <!-- 2-->
                        <td><?php echo $chq_no;?></td> <!-- 3-->
                        <td><?php echo $bank_code;?></td> <!-- 4-->
                        <td><?php echo $details;?></td> <!-- 5-->
                        <td><?php echo $trans_type_name;?></td> <!-- 6-->
                        <td><span class="float-right"><?php echo $preTag.$p_amt.$endTag;?></span></td>
                        <td><span class="float-right"><?php echo $preTag.$r_amt.$endTag?></span></td> 
                        <td class="d-none"><?php echo $row['Transaction_Amount'];?></td> <!-- 9-->
                        <td class="d-none"><?php echo $mode?></td> <!-- 10-->
                        <td class="d-none"><?php echo $remarks?></td> <!-- 11-->
                        <td class="d-none"><?php echo $acc_id?></td> <!-- 12 -->
                        <td class="d-none"><?php echo $trans_type_id?></td> <!-- 13 -->
                        <td class="d-none"><?php echo $status_id?></td> <!-- 14 -->
                        <td class="d-none"><?php echo $prj_id?></td> <!-- 15 -->
                        <td class="d-none"><?php echo $cat_id?></td><!-- 16 -->
                        <?php 
                        if($acc_cnt){
                            $found = false;
                            foreach ($acc_arr as $place_arr) {
                                if ($place_arr['account_id'] === $acc_id) {
                                    $found = true;
                                    $pplace = $place_arr['place']; //
                                    break; 
                                }
                            }
                            // echo $pplace.')    ';
                            for($i=0; $i<$acc_cnt; $i++){
                                // search for acc id place
                                if($pplace==$i){
                                    if($p_amt){
                                        $r_amt='-';
                                        $rtd_class="text-center"; $ptd_class="text-right";
                                    }
                                    else{
                                        $p_amt='-';
                                        $rtd_class="text-right"; $ptd_class="text-center";
                                    }
                                    //updating balance
                                    foreach ($acc_arr as &$bal_arr) {
                                        if ($bal_arr['account_id'] == $acc_id) {
                                            if($cancel_stat==0){
                                                $acc_bal=$bal_arr['balance']+($amount);
                                                if($amount>0){
                                                    $bal_arr['acc_tot_r']=$bal_arr['acc_tot_r']+$amount;
                                                }
                                                else{
                                                    $bal_arr['acc_tot_p']=$bal_arr['acc_tot_p']+$amount;
                                                }
                                                $bal_arr['balance'] = $acc_bal;  
                                            }
                                            else{
                                                $acc_bal=$bal_arr['balance'];
                                            }
                                            if($acc_bal<0){
                                                $acc_bal=abs($acc_bal);
                                                $bal_html="table-danger ";
                                            }
                                            else{
                                                $bal_html="";
                                            }   
                                            break; 
                                        }
                                    }
                                    $acc_bal=number_format($acc_bal,2);
                                    echo '<td class="'.$ptd_class.' table-active"><span class="">'.$preTag.$p_amt.$endTag.'</span></td>
                                            <td class="'.$rtd_class.' table-active"><span class="">'.$preTag.$r_amt.$endTag.'</span></td>
                                            <td class="'.$bal_html.' text-right">'.$acc_bal.'</td>';
                                }
                                else{
                                    $found1 = false;
                                    foreach ($acc_arr as $prevBal) {
                                        if ($prevBal['place'] == $i) {
                                            $found1 = true;
                                            $acc_bal_stat = $prevBal['balance']; 
                                            if($acc_bal_stat<0){
                                                $acc_bal_stat=abs($acc_bal_stat);
                                                $bal_html="table-danger";
                                            }
                                            else{
                                                $bal_html="";
                                            } 
                                            $acc_bal_stat= number_format($acc_bal_stat,2);
                                            break; 
                                        }
                                    }
                                    echo '<td class="text-center table-active">-</td>
                                        <td  class="text-center table-active">-</td>
                                        <td class="'.$bal_html.' text-right">'.$acc_bal_stat.'</td>';
                                }
                            }
                        }
                    ?>
                        <td><span class="float-right"> <?php echo $balance_ouput?></span></td> 
                        <td><?php echo $status;?></td>
                        <td><?php echo $remarks;?></td>
                        <td><?php echo $prj_code;?></td> 
                        <td><?php echo $cat_name;?></td>
                        <td class="btn-group text-center">
                            <!-- EDIT -->
                            <button type="button" class="btn btn-success editTrans">
                                <i class="fa fa-edit" area-hidden="true"></i>
                            </button>
                            <!-- DELETE -->
                            <form action="code.php" method="POST">  
                                <input type="hidden" name="trans_id" value="<?php echo $trans_id;?>">  
                                <button type="submit" name ="delTrans" class="btn btn-danger">
                                    <i class="fa fa-trash" area-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="form-check ">
                                <input type="checkbox" class="form-control chkBoxIds" id="checkboxNoLabel" value="<?php echo $trans_id;?>"> 
                            </div>
                        </td>
                    </tr>
                            <?php 
                        }
                        $total_pAmt=abs($total_pAmt);
                        $total_pAmt=number_format($total_pAmt,2);
                        $total_rAmt=number_format($total_rAmt,2);
                        // var_dump($acc_arr);
                    }
                    else{
                        echo 'no record found';
                    }
                ?>
                    <tr class="table-success">
                        <td colspan="5"></td>
                        <td><span class="float-right"><?php echo $total_pAmt;?></span></td> <!-- PAID -->
                        <td><span class="float-right"><?php echo $total_rAmt?></span></td> <!-- RECEIVED-->
                    <?php 
                        if($acc_cnt){
                            for($i=0; $i<$acc_cnt; $i++){
                                foreach ($acc_arr as &$endbal) {
                                    if ($endbal['place'] == $i){
                                        $tot_receive=$endbal['acc_tot_r'];
                                        $tot_paid=$endbal['acc_tot_p'];
                                        $accEndBal=$endbal['balance'];
                                        $accEndBal=number_format($accEndBal,2);
                                        $tot_paid=abs($tot_paid);
                                        $tot_receive=number_format($tot_receive,2);
                                        $tot_paid=number_format($tot_paid,2);
                                        $balance_html.='<td colspan="2"><span class="float-right font-weight-bold">'.$accEndBal.'</span></td><td></td>';
                                        break; 
                                    }
                                }
                                echo '<td><span class="float-right">'.$tot_paid.'</span></td>
                                        <td><span class="float-right">'.$tot_receive.'</span></td>
                                        <td></td>';
                            }
                        }
                    ?>
                        <td><span class="float-right"> <?php echo $balance_ouput?></span></td>
                        <td colspan="7"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="table-active">Balance</td>
                        <td colspan="2"><span class="float-right"> <?php echo $balance_ouput?></span></td> <!-- BALANCE-->
                        <?php echo $balance_html;?>
                        <td class="table-active"><span class="float-right font-weight-bold"> <?php echo $balance_ouput?></span></td>
                        <td class="table-active" colspan="6"></td>
                    </tr>
                </tfoot>
                </table>
            </div>
            <div align="right">
                <button name="" id="btnExcel" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
    </div>                    
</div>   
<!-- Modal Add Transaction -->
<div class="modal fade bd-example-modal-xl" id="addTransaction" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Add Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <div class="form-row">
                <div class="col-3">
                    <label for="">Date:</label>
                    <input type="date" name="tdate" class="form-control" id="">
                </div>
                
                <div class="col-3">
                    <label for="">Cheque No:</label>
                    <input name="chq_no" type="text" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Bank Account:</label>
                    <select name="acc_id" id="acc_opt" class="form-control">
                    </select>
                </div>
                <div class="col-3">
                    <label for="">Type</label>
                    <select name="t_type_id" class="form-control" id="t_type">
                    </select>
                </div>
            </div>
            <div class="form-row mt-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Details: <i class="ml-3">i.e. Clients, Subcontractor</i> </label> 
                        <input type="text" name="details" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Mode:</label><br>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mode" id="inlineRadio1" value="paid" required>
                            <label class="form-check-label" for="inlineRadio1">Paid</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mode" id="inlineRadio2" value="received">
                            <label class="form-check-label" for="inlineRadio2">Receive</label>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="form-row ">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Remarks:</label>
                        <input type="textarea" name="remarks" class="form-control">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Status:</label>
                        <select name="stat_id" id="status_opt" class="form-control" required> </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Project:</label>
                        <select name="prj_id" id="" class="form-control">
                            <option value="">Uncategorized</option>
                            <?php 
                                $q_prj="SELECT Prj_Id, Prj_Code, Prj_Name  FROM project WHERE Prj_Status=1";
                                $q_prj_run=mysqli_query($connection,$q_prj);
                                if(mysqli_num_rows($q_prj_run)>0){
                                    while($row_p=mysqli_fetch_assoc($q_prj_run)){
                                        $prj_id=$row_p['Prj_Id'];
                                        $prj_code=$row_p['Prj_Code'];
                                        $prj_name=$row_p['Prj_Name'];
                                        echo"<option value=".$prj_id.">".$prj_code.' '.$prj_name."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="cat_id" id="cat_opt" class="form-control">
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="addTransaction" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Transaction -->
<!-- Modal Edit Transaction -->
<div class="modal fade bd-example-modal-xl" id="editTrans" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Edit Transaction</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="hidden" id="etrans_id" name="trans_id">
            <div class="form-row">
                <div class="col-3">
                    <label for="">Date:</label>
                    <input type="date" name="tdate" class="form-control" id="edate">
                </div>
                <div class="col-3">
                    <label for="">Cheque No:</label>
                    <input name="chq_no" type="text" class="form-control" id="echq_no">
                </div>
                <div class="col-3">
                    <label for="">Bank Account:</label>
                    <select name="acc_id" id="e_acc_id" class="form-control acc_opt">
                    </select>
                </div>
                <div class="col-3">
                    <label for="">Type</label>
                    <select name="t_type_id" class="form-control t_type" id="e_t_type">
                    </select>
                </div>
            </div>
            <div class="form-row mt-2">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Details: <i class="ml-3">i.e. Clients, Subcontractor</i> </label> 
                        <input type="text" name="details" id="e_details" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <label for="">Amount:</label>
                    <input type="decimal" name="amount" id="e_amt" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">Mode:</label><br>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mode" id="e_mode" value="paid" required>
                            <label class="form-check-label" for="inlineRadio1">Paid</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="mode" id="inlineRadio2" value="received">
                            <label class="form-check-label" for="inlineRadio2">Receive</label>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="form-row ">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Remarks:</label>
                        <input type="textarea" name="remarks" id="e_remarks" class="form-control">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Status:</label>
                        <select name="stat_id" id="e_stat" class="form-control status_opt" required> </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Project:</label>
                        <select name="prj_id" id="e_prj_id" class="form-control">
                            <option value="NULL"></option>
                            <?php 
                                $q_prj="SELECT Prj_Id, Prj_Code, Prj_Name  FROM project WHERE Prj_Status=1";
                                $q_prj_run=mysqli_query($connection,$q_prj);
                                if(mysqli_num_rows($q_prj_run)>0){
                                    while($row_p=mysqli_fetch_assoc($q_prj_run)){
                                        $prj_id=$row_p['Prj_Id'];
                                        $prj_code=$row_p['Prj_Code'];
                                        $prj_name=$row_p['Prj_Name'];
                                        echo"<option value=".$prj_id.">".$prj_code.' '.$prj_name."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label for="">Category</label>
                        <select name="cat_id" id="e_cat_opt" class="form-control cat_opt"></select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="editTrans" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD transanction -->
<!-- Modal Add Transaction -->
<div class="modal fade bd-example-modal-md" id="importTrans" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-clipboard" aria-hidden="true"></i> Upload Transactions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST"  enctype="multipart/form-data">
      <div class="modal-body">
        <!-- THE FORM -->
            <input type="file" name="file" class="form-control" required>
            <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
        <!-- END FORM -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
        <button type="submit" name="importTrans" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Transaction IMPORT-->
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
            $(document).find('#prj_opt').html(data).change();
            $('#prj_opt').selectpicker('refresh');
        }
    });
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'transTypeOpt': month_opt},
        success:function(data){
            $(document).find('#t_type').html(data).change();
            $(document).find('.t_type').html(data).change();
        }
    });
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'cat_opt': month_opt},
        success:function(data){
            $(document).find('#cat_opt').html(data).change();
            $(document).find('.cat_opt').html(data).change();
        }
    });
});
$(document).ready(function(){
    var acc_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'acc_opt': acc_opt},
        success:function(data){
            $(document).find('#acc_opt').html(data).change();
            $(document).find('.acc_opt').html(data).change();
            data = "<option value='all'>All</option>"+data;
            $(document).find('#acc_opt1').html(data).change();
            $('#acc_opt1').selectpicker('refresh');
        }
    });
});
$(document).ready(function(){
    var tran_stat_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'tran_stat_opt': tran_stat_opt},
        success:function(data){
            $(document).find('#status_opt').html(data).change();
            $(document).find('.status_opt').html(data).change();
            data = "<option value='all'>All</option>"+data;
            $(document).find('#status_opt1').html(data).change();
            $('#status_opt1').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {
    $(document).on('click', '.editTrans', function() {
        $('#editTrans').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        // alert(data[16]);
       $('#etrans_id').val(data[0]);
       $('#edate').val(data[1]);
       $('#echq_no').val(data[3]);
       $('#e_details').val(data[5]);
       $('#e_acc_id').val(data[12]);
       $('#e_t_type').val(data[13]); 
       $('#e_amt').val(data[9]);
        $("input[name=mode][value='"+(data[10])+"'").prop("checked",true);
       $('#e_remarks').val(data[11]);
       $('#e_stat').val(data[14]);
       $('#e_prj_id').val(data[15]); 
       $('#e_cat_opt').val(data[16]);
    });
});
$(document).ready(function () {
    var arr = [];
    $(document).on('click', '.chkBoxIds', function() {
        // alert($(this).val());
        if($(this).prop('checked')){
            arr.push("'"+$(this).val()+"'");
        }
        else{
            var remove =$(this).val();
            arr.splice( $.inArray(remove,arr) ,1 );
        }
        $('#chkIds').val(arr);
        $('#chkIds1').val(arr);
    });
});
$(document).ready(function () {
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#dataTable'));
    });
});

</script>             
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>