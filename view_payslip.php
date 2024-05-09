<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Payslip</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
  </head> 
  <!-- <body> -->
  <body onload="window.print()">
<style>
  .thstyle{
    background: #DADEDF;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    font-weight: normal;
  }
  .body{
    width: 100vw;
    position: relative;
  }
  .border{
    border: 1px solid black;
  }
  .red{
      color:#E72222;
      font-family: "Hind Siliguri";
      letter-spacing: 2px;
      font-size:18px;
      margin-bottom:-15px;
    }
  .hind{
      font-family: "Hind Siliguri";
      font-size:20px;
      margin-bottom:-28px;
  }
  .total{
      font-weight:bold;
      font-size:13.3px;
      padding-top:20%;
      margin-top:15px;
  }
  .deduct_add{
    font-size:12.5px;
  }
  .pagef{
      font-family: "calibri";
      font-size:15.5px;
  }
  .PrintOnly{
    font-size:105%;
    visibility: hidden;
  }
  @media print {
    /* body {transform: scale(.7);}
    table {page-break-inside: avoid;} */
    body {webkit-print-color-adjust: exact}
    html
    {
        zoom:85%;
        margin: 5mm 5mm 5mm 5mm;
    }
  .PrintOnly {visibility: visible;}
  @media screen {
  .PrintOnly {display: none}
  }
}
</style>
<div class="PrintOnly">
<!-- HEADER -->
<table style="width:100%; border-spacing: 0px; cellspacing:0px; border-collapse: collapse;">
  <tr>
    <td style="width: 146px; padding-top: 22px; padding-right: 3px">
      <img src="img/EMT LOGOf.png" alt="" style="width:143px; height: 78px">
    </td>
    <td>
      <p class="hind"><b>E M T Electromechanical Works L.L.C.</b></p> 
      <p class="red">اي ام تي للأعمال الكهروميكانيكيه ش.ذ.م.م</p>
      <font face="Hind Siliguri">

      <p style="font-size: 14px; margin-bottom:-5px">
        <span class="fa-stack" style="font-size:10px;color:#E72222;">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-map-marker fa-stack-1x"></i>
        </span>
            P.O.Box: 95669, Dubai, UAE
        <span class="fa-stack" style="font-size:10px;color:#E72222;">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-phone fa-stack-1x"></i>
        </span>
            +971 4269 2700
        <span class="fa-stack" style="font-size:10px;color:#E72222;">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-fax fa-stack-1x"></i>
        </span>
            +971 4269 2267
        <span class="fa-stack" style="font-size:10px;color:#E72222;">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-envelope-o fa-stack-1x"></i>
        </span>
            emtworks@emtdubai.ae
        <span class="fa-stack" style="font-size:10px;color:#E72222;">
          <i class="fa fa-circle-thin fa-stack-2x"></i>
          <i class="fa fa-globe fa-stack-1x"></i>
        </span>
            www.emtdubai.ae
      </p>
        </font>
        <hr color="#E72222" size="1">
    </td>
  </tr>
</table>

<?php
    include('security.php');
    if(isset($_POST['view_p']))
        {
            $id = $_POST['pv_id'];

            $query ="SELECT * FROM employee LEFT JOIN payslip on payslip.EMP_ID = employee.EMP_ID WHERE PAYSLIP_ID='$id' ";
            $query_run = mysqli_query($connection, $query);
            $query1="SELECT * FROM allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$id' ";
            $query2="SELECT * FROM deduction LEFT JOIN payslip on payslip.PAYSLIP_ID = deduction.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$id' ";
            $query3="SELECT * FROM additional LEFT JOIN payslip on payslip.PAYSLIP_ID = additional.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$id' ";
            $query4="SELECT * FROM full_allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = full_allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$id' ";
            // $al_q = mysqli_query($connection, $query1);
            $al_q1 = mysqli_query($connection, $query1);
            $d_q = mysqli_query($connection, $query2);
            $ad_q = mysqli_query($connection, $query3);
            $full_alw_q = mysqli_query($connection, $query4);
            $al_total=0;$d_total=0; $ad_total=0;$ad_total_=0; $d_total_=0; $p_alw_total=0;

            foreach($query_run as $row)
            {   
              $total_bonus_ot = $row['P_NORM_OTAMT']+$row['P_HOL_OTAMT']+$row['P_SP_AMT']+$row['P_BNS_AMT'];
              $total_bonus_ot_ = $total_bonus_ot;
                ?>
<div class="pagef">
    <table style="width:100%; border-spacing: 0px; cellspacing:0px; border-collapse: collapse;">
      <tr>
        <td width=14%><b>Payslip - <?php echo strtoupper(date('M Y',strtotime($row['P_DATE']))); ?></b></td>
        <td width=10%></td>
        <td width=15%>Designation:</td>
        <td width=35%><?php echo $row['EMP_DESIGNATION'] ?></td>
      </tr>
      <tr>
        <td width=8%>Emp. No.: </td>
        <td width=42%><b><?php echo $row['EMP_NO'] ; $emp_no = $row['EMP_NO']?></b></td>
        <td width=15%>DOJ: </td>
        <td width=35%><?php echo date('d/m/Y', strtotime($row['EMP_DOJ'])) ?></td>
      </tr>
      <tr>
        <td width=8%>Emp. Name.: </td>
        <td width=42%><?php echo ucwords($row['EMP_FNAME']); ?><?php echo ' '.ucwords($row ['EMP_MNAME']); ?><?php echo ' '.ucwords($row['EMP_LNAME']); ?><?php echo ' '.ucwords($row['EMP_SNAME']); ?></td>
        <td width=15%>Bank Name: </td>
        <td width=35%><?php echo $row['EMP_BANK'] ?></td>
      </tr>
      <tr>
        <td width=8%>Pay Mode: </td>
        <td width=42%><?php echo $row['EMP_PAYMODE'] ?></td>
        <td width=15%>Account: No. </td>
        <td width=35%><?php echo substr($row['EMP_ACCNO'], -6) ?></td>
      </tr>
      <tr>
        <td width=8%>Project/Location:</td>
        <td width=42%><?php echo $row['EMP_LOCATION'] ?></td>
        <td width=15%>IBAN No.: </td>
        <td width=20%><?php echo substr($row['EMP_IBANNO'], -6) ?></td>
      </tr>
    </table>
    <table class="border" style="width:100%;  margin-top:5px">
      <!-- T Header -->
      <thead>
        <th width=23% class="thstyle"></th>
        <th width=23% class="thstyle"></th>
        <th width=19% class="thstyle">Deductions</th>
        <th width=19% class="thstyle">Other Additions</th>
        <th width=16% align="left" style="border-bottom: 1px solid black; background-color: #DADEDF; font-weight: normal; padding-left:20px">Net Salary</th>
      </thead>
      <!-- T head end -->
      <tbody>
      <tr>
        <!-- FULL BASIC WITH ALLOWANCES-->
        <td style="min-height=100px; border-right: 1px solid black; vertical-align:top; padding-bottom:80px">
            <table>
                <tr>
                    <td width=90%>Basic</td>
                    <td width=10% align="right"><?php echo number_format($row['P_FULL_BASIC'],3,'.',',') ?></td>
                </tr>
              <?php
              if(mysqli_num_rows($full_alw_q)>0)
                {
                  while($row1 = mysqli_fetch_assoc($full_alw_q))
                  {
                    ?>
                      <tr>
                        <td width=90%><?php echo $row1['FULL_ALW_NAME']?></td>
                        <td width=10% align="right"><?php echo number_format($row1['FULL_ALW_AMT'],3,'.',',') ?></td>
                      </tr>
                    <?php
                    $al_total = $al_total + $row1['FULL_ALW_AMT'];
                  }
                }
                else{}
              ?>
            </table>
        </td>
        <!-- BASIC, ALLOWANCES, WITH BONUS -->
        <td style="border-right: 1px solid black; vertical-align:top;">
          <table>
            <tr>
              <td width=90%>Basic</td>
              <td width=10% align="right"><?php echo number_format($row['P_BASIC_SALARY'],3,'.',',') ?></td>
            </tr>
            <?php 
                if(mysqli_num_rows($al_q1)>0)
                {
                  while($row2 = mysqli_fetch_assoc($al_q1))
                  {
                    ?>
                      <tr>
                        <td width=90%><?php echo $row2['ALW_NAME']?></td>
                        <td width=10% align="right"><?php echo number_format($row2['ALW_AMT'],3,'.',',') ?></td>
                      </tr>
                    <?php
                    $p_alw_total = $p_alw_total + $row2['ALW_AMT'];
                  }
                }
                else{}
              ?>
            <tr class="bonus_ot">
              <td width=90% >Bonus & OT</td>
              <td width=10% align="right"><?php echo number_format($total_bonus_ot,3,'.',',') ?></td>
            </tr>
          </table>
        </td>
        <!-- DEDUCTIONS -->
        <td style="border-right: .2px solid black; vertical-align:top;">
          <table>
              <?php 
                if(mysqli_num_rows($d_q)>0)
                {
                  while($row3 = mysqli_fetch_assoc($d_q))
                  {
                    ?>
                      <tr class="deduct_add">
                        <td width=90%><?php echo $row3['DEDUC_NAME']?></td>
                        <td width=10% align="right"><?php echo number_format($row3['DEDUC_AMT'],3,'.',',') ?></td>
                      </tr>
                    <?php
                    $d_total = $d_total + $row3['DEDUC_AMT'];
                  }
                  ?>
                <tr></tr>
                    <tr></tr>
                    <tr class="total" >
                      <td style="padding-right:25px" align="right">Total:   </td>
                      <td width=10% align="right"><?php echo number_format($d_total,3,'.',',') ?></td>
                    </tr>
                <?php
                }
                else{}
                $d_total_=$d_total;
              ?>
                    
          </table>
        </td>
        <!-- ADDITIONS -->
        <td style="border-right: 1px solid black; vertical-align:top;">
          <table>
              <?php 
                if(mysqli_num_rows($ad_q)>0)
                {
                  while($row4 = mysqli_fetch_assoc($ad_q))
                  {
                    ?>
                      <tr class="deduct_add">
                        <td width=90%><?php echo $row4['ADD_NAME']?></td>
                        <td width=10%><?php echo number_format($row4['ADD_AMT'],3,'.',',') ?></td>
                      </tr>
                    <?php
                    $ad_total = $ad_total + $row4['ADD_AMT'];
                  }
                  ?>
                  <tr></tr><tr></tr>
                    <tr class="total">
                      <td style="padding-right:25px" align="right">Total:   </td>
                      <td width=10% align="right"><?php echo number_format($ad_total,3,'.',',') ?></td>
                    </tr>
                  <?php
                }
                else{}
                $ad_total_=$ad_total;
              ?>
          </table>
        </td>
<?php
$e_basic=$row['P_FULL_BASIC'];
$p_basic = $row['P_BASIC_SALARY']; 
$total1 = $e_basic + $al_total; //TOTAL IN OFFER LETTER
$total2 = $p_basic + $p_alw_total + $total_bonus_ot_; //TOTAL IN PAYSLIP
$netsal=$total2  +$ad_total_ - $d_total_;
?>
        <!-- NET SALARY -->
        <td>
          <center>
          AED
          <hr width=85% size="1">
            <label style="padding-left:30px">
            <b><?php echo number_format(round($netsal),3,'.',',') ?></b>
            </label></center>
          <hr width=85% size="1">
          <br>
        </td>
      </tr>
    <!--  -->
      <tr style="">
        <!-- TOTAL BASIC -->
        <td style="border-right: 1px solid black; border-top: 1px solid black;">
          <table style=" padding-bottom:12px">
              <tr>
                <td width=90%>Total</td>
                <td width=10% align="right"><?php echo number_format($total1,3,'.',',') ?></td>
              </tr>
          </table>
        </td>    
        <!-- TOTAL WITH OT -->
        <td style="border-right: 1px solid black; border-top: 1px solid black;">
          <table style=" padding-bottom:12px">
              <tr>
                <td width=90%>Total</td>
                <td width=10% align="right"><?php echo number_format($total2,3,'.',',') ?></td>
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
      </table>
      <table class="tabl" style="width:100%; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black">
      <tr>
        <td width="20%" style="padding-right:10px">
          <table style="border-spacing: 0px; cellspacing:0px;">
            <tr>
              <td width=60%>Normal OT Hours</td>
              <td width=40% align="right"><?php echo $row['P_NORM_OTHRS'] ?></td>
            </tr>
            <tr>
              <td width=80%>Amount</td>
              <td width=20% align="right"> <?php echo $row['P_NORM_OTAMT'] ?></td>
            </tr>
          </table>
        </td>
        
        <td width="20%" style="padding-right:10px">
          <table style="border-spacing: 0px; cellspacing:0px;">
            <tr>
              <td width=80%>Holiday OT Hours</td>
              <td width=20% align="right"><?php echo $row['P_HOL_OTHRS'] ?></td>
            </tr>
            <tr>
              <td width=80%>Amount</td>
              <td width=20% align="right"><?php echo $row['P_HOL_OTAMT'] ?></td>
            </tr>
          </table>
        </td>

        <td width="20%" style="padding-right:10px">
          <table style="border-spacing: 0px; cellspacing:0px;">
            <tr>
              <td width=80%>Special Hours</td>
              <td width=20% align="right"><?php echo $row['P_SP_HRS'] ?></td>
            </tr>
            <tr>
              <td width=80%>Amount</td>
              <td width=20% align="right"><?php echo $row['P_SP_AMT'] ?></td>
            </tr>
          </table>
        </td>

        <td width="20%" style="padding-right:10px">
          <table style="border-spacing: 0px; cellspacing:0px;">
            <tr>
              <td width=80%>Bonus Hours</td>
              <td width=20% align="right"><?php echo $row['P_BNS_HR'] ?></td>
            </tr>
            <tr>
              <td width=80%>Amount</td>
              <td width=20% align="right"><?php echo $row['P_BNS_AMT'] ?></td>
            </tr>
          </table>
        </td>

        <td width="20%">
          <table style="border-spacing: 0px; cellspacing:0px;">
            <tr>
              <td width=80%>Absent Days:</td>
              <td width=20% align="right"><?php echo $row['P_ABDAYS'] ?></td>
            </tr>
            <tr>
              <td width=80%>Leave Days:</td>
              <td width=20% align="right"><?php echo $row['P_LDAYS'] ?></td>
            </tr>
          </table>           
        </td>
      </tr>
      </table>
                <?php
            }
        }
?>
    <p>All amounts in AED</p>
    <hr>
    <p><center>1/1</center></p>
    <p>This is a system generated document printed by employee.</p>
    </div>
  </div>
  </body>
</html>