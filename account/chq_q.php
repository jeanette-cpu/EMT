<?php 
include('../security.php');
include('accQuery.php'); 
if(isset($_POST['acc_id'])){
    $acc_id=$_POST['acc_id']; $bot_html=''; 
    $tot_chq=0;$tot_r_amt=0;$tot_p_amt=0;$tot_stat_amt=0; 
    //account statuses
    if(mysqli_num_rows($q_transact_status_run)>0){
        $bot_html.='
            <tr class="text-center font-weight-bold">
                <td colspan="2">Cheque Status</td>
                <td>Nos.</td>
                <td>Amount</td>
                <td>PAID</td>
                <td>RECEIVED</td>
                <td></td>
                <td></td>
            </tr>';
        while($row_s=mysqli_fetch_assoc($q_transact_status_run)){$preTag=""; $endTag="";
            $status_id=$row_s['Transaction_Status_Id'];
            $stat_name=$row_s['Transaction_Status_Description'];
            $output=getStatusNo($status_id,$acc_id);
            $no = $output['no']; $tot_chq=$tot_chq+$no;
            $stat_amt = $output['stat_amt']; 
            $p_amt=$output['p_amt']; 
            $r_amt=$output['r_amt']; 
            $wordToCheck = "cancel"; 
            if(stripos($stat_name, $wordToCheck) !== false) {
                $preTag="<s>"; $endTag="</s>";
            }
            else{
                $tot_p_amt=$tot_p_amt+$p_amt; 
                $tot_r_amt=$tot_r_amt+$r_amt; 
                $tot_stat_amt=$tot_stat_amt+$stat_amt; 
                // echo $stat_amt.'<br>';
            }
            $p_amt=number_format($p_amt,2); $r_amt=number_format($r_amt,2); $stat_amt_output=number_format($stat_amt,2); 
            $bot_html.='
            <tr>
                <td class="pl-2" colspan="2">'.$stat_name.'</td>
                <td class="text-right pr-2">'.$no.'</td>
                <td class="text-right pr-2 ">'.$preTag.$stat_amt_output.$endTag.'</td>
                <td class="text-right pr-2">'.$preTag.$p_amt.$endTag.'</td>
                <td class="text-right pr-2">'.$preTag.$r_amt.$endTag.'</td>
                <td></td>
                <td></td>
            </tr>';
        }
    }
    // account types
    $q_trans_type="SELECT * FROM transaction_type WHERE Transaction_Type_Status=1";
    $q_tt_run=mysqli_query($connection,$q_trans_type);$transType_html="";
    if(mysqli_num_rows($q_tt_run)>0){
        $transType_html.='
        <tr class="text-center font-weight-bold d-none">
            <td colspan="2">Type</td>
            <td>Nos.</td>
            <td>Amount</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>';
        while($row_tt=mysqli_fetch_assoc($q_tt_run)){
            $tt_id=$row_tt['Transaction_Type_Id'];
            $tt_code=$row_tt['Transaction_Type_Code'];
            $tt_name=$row_tt['Transaction_Type_Name'];

        }
    }
    //trans
    $q_chq="SELECT * FROM  transaction 
        WHERE Transaction_Status=1 AND Account_Id='$acc_id'
        ORDER BY Transaction_Cheque_No ASC";
    $q_chq_run=mysqli_query($connection,$q_chq);
    $html=''; $tot_m=0; $total_amt=0;
    $html.='
    <div class="table-responsive">
        <table border="1" width="100%" id="chqTable">
            <thead class="text-center">
                <th width="7%">Date</th>
                <th width="6%">Check No.</th>
                <th width="5%">Project</th>
                <th width="10%">Amount</th>
                <th width="30%" colspan="2">Details</th>
                <th width="10%">Remarks</th>
                <th width="10%">Status</th>
            </thead>
            <tbody>';
            if(mysqli_num_rows($q_chq_run)>0){ $cnt=0;$prev_chq_no=NULL; 
                while($row=mysqli_fetch_assoc($q_chq_run)){
                    $cnt++;
                    $tdate=$row['Transaction_Date'];
                    $date=date('d-M-Y',strtotime($tdate));
                    $chq_no=$row['Transaction_Cheque_No'];
                    $prj_id=$row['Prj_Id']; $prj_code=prjCode($prj_id);
                    $amount=$row['Transaction_Amount'];
                    $total_amt=$total_amt+$amount;
                    $amount_output=abs($amount);
                    $amount_output=number_format($amount_output,2);
                    $details=$row['Transaction_Details'];
                    $remarks=$row['Transaction_Remarks'];
                    $status_id=$row['Transaction_Status_Id']; $status=statName($status_id);
                    if($cnt==$chq_no){
                    $html.='
                        <tr class="text-right">
                            <td class="pr-2">'.$date.'</td>
                            <td class="text-center">'.$chq_no.'</td>
                            <td class="pr-2">'.$prj_code.'</td>
                            <td class="pr-2">'.$amount_output.'</td>
                            <td class="pr-2" colspan="2">'.$details.'</td>
                            <td class="pr-2">'.$remarks.'</td>
                            <td class="pr-2">'.$status.'</td>
                        </tr>';
                    }
                    else{
                        // echo $cnt.' '.$chq_no.'<br>';
                        $lenght=$chq_no-$prev_chq_no-1;
                        $mis_chq=0;
                        $mis_chq=$prev_chq_no;
                        // echo $lenght;
                        for($i=0;$i<$lenght;$i++){
                            $mis_chq++; $tot_m++; 
                            // <!-- $mis_chq.' '.$i.'<br>'. -->
                            $html.='
                            <tr class="table-warning  text-right ">
                                <td colspan="" class="pr-2"></td>
                                <td class="text-center">'.$mis_chq.'</td>
                                <td></td>
                                <td></td>
                                <td class="text-center" colspan="2">MISSING CHEQUE</td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                        $html.='
                        <tr class="text-right">
                            <td class="pr-2">'.$date.'</td>
                            <td class="text-center">'.$chq_no.'</td>
                            <td class="pr-2">'.$prj_code.'</td>
                            <td class="pr-2">'.$amount_output.'</td>
                            <td class="pr-2" colspan="2">'.$details.'</td>
                            <td class="pr-2">'.$remarks.'</td>
                            <td class="pr-2">'.$status.'</td>
                        </tr>';
                    }
                    $prev_chq_no=$chq_no;
                }
            }
            $total_amt=number_format($total_amt,2);
            $tot_chq=$tot_chq+$tot_m;
            $tot_stat_amt=number_format($tot_stat_amt,2);
            $tot_p_amt=number_format($tot_p_amt,2);
            $tot_r_amt=number_format($tot_r_amt,2);
            $html.=$bot_html.'
                <tr>
                    <td class="pl-2" colspan="2">Missing</td>
                    <td class="text-right pr-2">'.$tot_m.'</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="font-weight-bold text-right pr-2" colspan="2">TOTAL</td>
                    <td class="text-right pr-2 font-weight-bold">'.$tot_chq.'</td>
                    <td class="text-right pr-2 font-weight-bold">'.$tot_stat_amt.'</td>
                    <td class="text-right pr-2 font-weight-bold">'.$tot_p_amt.'</td>
                    <td class="text-right pr-2 font-weight-bold">'.$tot_r_amt.'</td>
                    <td></td>
                    <td></td>
                </tr>
                '.$transType_html.'
            </tbody>
        </table>
    </div>';
    ?>
    <script>
        var mis_chq = document.getElementById("mis_chq");
        var bal_id = document.getElementById("bal_id");
        if (mis_chq) {
            var content =<?php echo $tot_m;?>;
            mis_chq.innerHTML = content; 
        }
        if (bal_id) {
            var balance ='<?php echo $total_amt;?>';
            bal_id.innerHTML = balance; 
        }
    </script>
    <?php
    echo $html;
}
?>