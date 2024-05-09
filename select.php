<?php  
 include('security.php');

 if(isset($_POST["payslip_id"]))  
 {  
      $output = '';

      $query = "SELECT * FROM employee LEFT JOIN payslip on payslip.EMP_ID = employee.EMP_ID WHERE PAYSLIP_ID = '".$_POST["payslip_id"]."'";           
      $query1="SELECT * FROM allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '".$_POST["payslip_id"]."'";
      $query2="SELECT * FROM deduction LEFT JOIN payslip on payslip.PAYSLIP_ID = deduction.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '".$_POST["payslip_id"]."'";
      $query3="SELECT * FROM additional LEFT JOIN payslip on payslip.PAYSLIP_ID = additional.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '".$_POST["payslip_id"]."'";
      $query4="SELECT * FROM full_allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = full_allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '".$_POST["payslip_id"]."'";
      
      $result = mysqli_query($connection, $query);
      $al_q1 = mysqli_query($connection, $query1);
      $d_q = mysqli_query($connection, $query2);
      $ad_q = mysqli_query($connection, $query3);
      $full_alw_q = mysqli_query($connection, $query4);
      $output .= '
      <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300&display=swap" rel="stylesheet">
<style>
    .thstyle{
    background-color: #DADEDF;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: normal;
    text-align:center;
    color:black
    }
    .body{
    width: 100vw;
    position: relative;
    }
    .hind{
        font-family: "Hind Siliguri"; !important
        letter-spacing: .8px;
    }
    .PrintOnly{
    font-size:105%;
    visibility: visible;
    }
    .deduct_add{
        font-size:14px;
    }
    @media print {
    /* body {transform: scale(.7);}
    table {page-break-inside: avoid;} */
    body {-webkit-print-color-adjust: exact;}
    html
    {
        zoom:85%;
        margin: 5mm 5mm 5mm 5mm;
    }
    .PrintOnly {visibility: visible;}
    @media screen (min-width: 800px){
        .srollmenu{
            width: 740px;
        }
    }
    .PrintOnly {display: none}
    }
    .scrollmenu {
        width:1050px !important;
    }
    .red{
        color:#E72222;
        font-family: "Hind Siliguri";
        font-face:calibri;
        letter-spacing: 2.7px;
    }
    .total{font-weight:bold; font-size:15px; padding-top:10px}
    .tabl.table{border-collapse: collapse; cellspacing=0; border:0;cellpadding=0}
}
</style>
    <div class="scrollmenu">
        <table class="mtable" style="width:100%; border-spacing: 0px; cellspacing:0px; ">
        <tr>
            <td class="mr-2 mt-n3" style="width: 175px; height:120px">
                <img src="img/EMT LOGOf.png" alt="" style="width:178px; height: 100px">
            </td>
            <td style="">
                <h4 class="hind mt-2 ml-2 mb-0"><b>E M T Electromechanical Works L.L.C.</b></h4> 
                <h5 class="red ml-2 mb-0">اي ام تي للأعمال الكهروميكانيكيه ش.ذ.م.م</h5>
            <font face="Hind Siliguri">
            <p class="ml-2 mb-n2" style="font-size: 15px letter-spacing:2px">
                <span class="fa-stack" style="font-size:14px;color:#E72222;">
                    <i class="fa fa-circle-thin fa-stack-2x"></i>
                    <i class="fa fa-map-marker fa-stack-1x"></i>
                </span>
                    P.O.Box: 95669, Dubai, UAE
                <span class="fa-stack" style="font-size:14px;color:#E72222;">
                    <i class="fa fa-circle-thin fa-stack-2x"></i>
                    <i class="fa fa-phone fa-stack-1x"></i>
                </span>
                    +971 4269 2700
                <span class="fa-stack" style="font-size:14px;color:#E72222;">
                    <i class="fa fa-circle-thin fa-stack-2x"></i>
                    <i class="fa fa-fax fa-stack-1x"></i>
                </span>
                    +971 4269 2267
                <span class="fa-stack" style="font-size:14px;color:#E72222;">
                    <i class="fa fa-circle-thin fa-stack-2x"></i>
                    <i class="fa fa-envelope-o fa-stack-1x"></i>
                </span>
                    emtworks@emtdubai.ae
                <span class="fa-stack" style="font-size:14px;color:#E72222;">
                <i class="fa fa-circle-thin fa-stack-2x"></i>
                <i class="fa fa-globe fa-stack-1x"></i>
                </span>
                    www.emtdubai.ae
                </p>
                </font>
                <hr color="#E72222"/>
            </td>
        </tr>
        </table>
        <font face="Calibri">
        <table style="width:100%; border-spacing: 0px; cellspacing:0px; padding:0px; border-collapse:collapse;">';  
      while($row = mysqli_fetch_array($result))  
      {  
        $date=' ' .strtoupper(date('M Y',strtotime($row['P_DATE'])));
        $computed_basic=number_format($row['P_BASIC_SALARY'],3,'.',',');
        $full_basic=number_format($row['P_FULL_BASIC'],3,'.',',');
        $fname=$row["EMP_FNAME"];$mname=$row["EMP_MNAME"]; $lname=$row["EMP_LNAME"];$sname=$row["EMP_SNAME"];
        $name=$fname." ".$mname." ".$lname." ".$sname;
        $accno=substr($row['EMP_ACCNO'], -6); $ibanno=substr($row['EMP_IBANNO'], -6);
        $doj=date('d/m/Y', strtotime($row['EMP_DOJ']));
        $emp_no = $row['EMP_NO'];

        $e_basic = $row['P_FULL_BASIC'];
        $p_basic = $row['P_BASIC_SALARY'];
        $al_total=0; $d_total=0; $ad_total=0; $ad_total_=0; $d_total_=0; $bat_=0; $p_alw_total=0;$alw_total=0;

        $output .= '  
                <tr>
                    <td width=14%><b>Payslip -'.$date.'</b></td>
                    <td width=10%></td>
                    <td width=15%>Designation:</td>
                    <td width=35%>'.$row["EMP_DESIGNATION"].'</td>
                </tr>  
                <tr>  
                    <td width=8%>Emp. No.: </td> 
                    <td width=42%><b>'.$row["EMP_NO"].'</b></td>
                    <td width=15%>DOJ: </td>
                    <td width=35%>'.$doj.'</td> 
                </tr>  
                <tr>  
                    <td width=8%>Emp. Name.: </td>  
                    <td width=42%>'.$name.'</td>
                    <td width=15%>Bank Name: </td>
                    <td width=35%>'.$row['EMP_BANK'].'</td>
                </tr>  
                <tr>  
                    <td width=8%>Pay Mode: </td> 
                    <td width=42%>'.$row['EMP_PAYMODE'].'</td>  
                    <td width=15%>Account: No. </td>
                    <td width=35%>'.$accno.'</td>  
                </tr>  
                <tr>  
                    <td width=8%>Project/Location:</td> 
                    <td width=42%>'.$row['EMP_LOCATION'].'</td>
                    <td width=15%>IBAN No.: </td>
                    <td width=35%>'.$ibanno.'</td>
                </tr>  
            </table>
            <table style="width:100%; border: 1px solid black;">
                <thead>
                    <th width=23% class="thstyle"></th>
                    <th width=23% class="thstyle"></th>
                    <th width=19% class="thstyle">Deductions</th>
                    <th width=19% class="thstyle">Other Additions</th>
                    <th width=16% style="border-bottom: 1px solid black; text-align:center; background-color: #DADEDF; font-weight: normal; color:#000000">Net Salary</th>
                </thead>
                <tbody>';
                // $query_basic_sal="SELECT";
                
                $output.=
                    '<tr>
                        <!-- BASIC with ALLOWANCES-->
                        <td style="height=100px; border-right: 1px solid black; vertical-align:top;">
                            <table>
                                <tr>
                                    <td width=90%>Basic</td>
                                    <td width=10% align="right">'.$full_basic.'</td>
                                </tr>';
                
                        
                if(mysqli_num_rows($full_alw_q)>0)
                {
                    while($row1 = mysqli_fetch_assoc($full_alw_q))
                    {
                        $output .=                                   
                                '<tr>
                                    <td width=90%>'.$row1['FULL_ALW_NAME'].'</td>
                                    <td width=10% align="right">'.$row1['FULL_ALW_AMT'].'</td>
                                </tr>';
                        $al_total = $al_total + $row1['FULL_ALW_AMT'];
                    }
                }
                else{}
                $output .=
                            '</table>
                        </td>
                        <!-- WITH BONUS -->
                        <td style="border-right: 1px solid black; vertical-align:top;">
                            <table>
                                <tr>
                                    <td width=90%>Basic</td>
                                    <td width=10% align="right">'.$computed_basic.'</td>
                                </tr>';
                if(mysqli_num_rows($al_q1)>0)
                {
                    while($row2 = mysqli_fetch_assoc($al_q1))
                    {
                        $output .=                
                                '<tr>
                                    <td width=90%>'.$row2['ALW_NAME'].'</td>
                                    <td width=10% align="right">'.$row2['ALW_AMT'].'</td>
                                </tr>';
                        $p_alw_total = $p_alw_total + $row2['ALW_AMT'];
                    }
                    $alw_total=$p_alw_total;
                }
                else{}
                
                $bat=$row['P_NORM_OTAMT']+$row['P_HOL_OTAMT']+$row['P_SP_AMT']+$row['P_BNS_AMT'];
                $bat_=$bat;
                $bat=number_format($bat,3,'.',',');

                $output.=          
                                '
                                <div class="bonus_ot">
                                <tr>
                                    <td width=90%>Bonus & OT</td>
                                    <td width=10% align="right">'.$bat.'</td>
                                </tr>
                                </div>
                            </table>
                        </td>
                        <!-- DEDUCTIONS -->
                        <td style="border-right: 1px solid black; vertical-align:top;">
                            <table>';
                if(mysqli_num_rows($d_q)>0)
                {
                    while($row3 = mysqli_fetch_assoc($d_q))
                    {
                        $output .=                       
                                '<tr class="deduct_add">
                                    <td width=90%>'.$row3['DEDUC_NAME'].'</td>
                                    <td width=10% align="right">'.$row3['DEDUC_AMT'].'</td>
                                </tr>';
                        $d_total = $d_total + $row3['DEDUC_AMT'];
                    }
                    $d_total_=$d_total;
                    $d_total=number_format($d_total,3,'.',',');

                    $output.=
                                '<b><tr class="total">
                                    <td  align="right">Total:</td>
                                    <td class="pl-4">'.$d_total.'</td>
                                </tr></b>';
                }
                else{}

                $output.= 
                            '</table>
                        </td>
                        <!-- ADDITIONS -->
                        <td style="border-right: 1px solid black; vertical-align:top;">
                            <table>';
                if(mysqli_num_rows($ad_q)>0)
                {
                    while($row4 = mysqli_fetch_assoc($ad_q))
                    {
                        $output .=  
                                '<tr class="deduct_add">
                                    <td width=90%>'.$row4['ADD_NAME'].'</td>
                                    <td width=10% align="right">'.$row4['ADD_AMT'].'</td>
                                </tr>';
                        $ad_total = $ad_total + $row4['ADD_AMT'];
                    }
                    $ad_total_=$ad_total;
                    $ad_total=number_format($ad_total,3,'.',',');

                    $output.=
                                '<tr class="total">
                                    <td align="right">Total:</td>
                                    <td class="pl-4" align="right">'.$ad_total.'</td>
                                </tr>';
                }
                else{}
                
                $total1 = $e_basic + $al_total; //1st col
                $total2 = $p_basic + $alw_total + $bat_; // 2nd col
                $netsal = $total2 + $ad_total_ - $d_total_;
                $netsal = number_format(round($netsal),3,'.',',');
                $total1=number_format($total1,3,'.',',');
                $total2 = number_format($total2,3,'.',',');

                $output.=                
                            '</table>
                        </td>
                        <!-- NET SALARY -->
                        <td>
                            <center>
                            AED
                            <b><hr width=90%>
                                '.$netsal.'
                            <hr width=90%>
                            </center></b>
                            <br>
                        </td>
                    </tr>
                    <tr>
                    <!-- TOTAL BASIC -->
                    <td style="border-right: 1px solid black; border-top: 1px solid black">
                        <table>
                            <tr>
                                <td width=90%>Total</td>
                                <td width=10% align="right">'.$total1.'</td>
                            </tr>
                        </table>
                    </td>
                    <!-- TOTAL WITH OT -->
                    <td style="border-right: 1px solid black; border-top: 1px solid black">
                        <table>
                            <tr>
                                <td width=90%>Total</td>
                                <td width=10% align="right">'.$total2.'</td>
                            </tr>
                        </table>
                    </td>
                    <!-- DEDUCTION -->
                    <td style="border-right: 1px solid black"></td>
                    <!-- ADDITION -->
                    <td style="border-right: 1px solid black"></td>
                    <!-- NET SALARY -->
                    <td></td>
                </tr>
                </tbody>
                <table class="tabl" style="width:100%; border: 1px solid black;">
                <tr>
                    <td width=20% class="mr-5">
                        <table class="tabl" style="border-spacing: 0px; cellspacing:0px;">
                            <tr>
                                <td width=80%>Normal OT Hours</td>
                                <td width=20% align="right">'.$row['P_NORM_OTHRS'].'</td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <td align="right">'.$row['P_NORM_OTAMT'].'</td>
                            </tr>
                        </table>
                    </td>
                    <td width=20% class="mr-2">
                        <table class="tabl" style="border-spacing: 0px; cellspacing:0px;">
                            <tr>
                                <td width=80%>Holiday OT Hours</td>
                                <td width=20% align="right">'.$row['P_HOL_OTHRS'].'</td>
                            </tr>
                            <tr>
                                <td width=80%>Amount</td>
                                <td width=20% align="right">'.$row['P_HOL_OTAMT'].'</td>
                            </tr>
                        </table>
                    </td>
                    <td width=20% class="mr-2">
                        <table class="tabl" style="border-spacing: 0px; cellspacing:0px;">
                            <tr>
                                <td width=80%>Special Hours</td>
                                <td width=20% align="right">'.$row['P_SP_HRS'].'</td>
                            </tr>
                            <tr>
                                <td width=80%>Amount</td>
                                <td width=20% align="right">'.$row['P_SP_AMT'].'</td>
                            </tr>
                        </table>
                    </td>
                    <td width=20% class="mr-2">
                        <table class="tabl" style="border-spacing: 0px; cellspacing:0px;">
                            <tr>
                                <td width=80%>Bonus Hours</td>
                                <td width=20% align="right">'.$row['P_BNS_HR'].'</td>
                            </tr>
                            <tr>
                                <td width=80%>Amount</td>
                                <td width=20% align="right">'.$row['P_BNS_AMT'].'</td>
                            </tr>
                        </table>
                    </td>
                    <td width=20% class="pl-3">
                        <table class="tabl" style="border-spacing: 0px; cellspacing:0px;">
                            <tr>
                                <td width=80%>Absent Days:</td>
                                <td width=20% align="right">'.$row['P_ABDAYS'].'</td>
                            </tr>
                            <tr>
                                <td width=80%>Leave Days:</td>
                                <td width=20% align="right">'.$row['P_LDAYS'].'</td>
                            </tr>
                        </table>           
                    </td>
                </tr>
                </table>
                ';  
      }  
      $output .= "</table>
      <p>All amounts in AED</p>
        <hr>
    </font>
</div>
      ";  
      echo $output;  
 }  
 ?>