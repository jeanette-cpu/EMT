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
  .bold{
    font-weight:bold;
  }
  .PrintOnly{
    font-size:105%;
    visibility: hidden;
  }
  @media print {
    /* body {transform: scale(.7);}
    table {page-break-inside: avoid;} */
    /* body {webkit-print-color-adjust: exact} */
    html
    {
        zoom:85%;
        margin: 5mm 5mm 5mm 5mm;
    }
  .PrintOnly {visibility: visible;}
  @media screen {
  .PrintOnly {display: none}
  }
  .ftable{
    font-family: "Hind Siliguri";
    font-size: 14px; 
    margin-bottom:-5px
  }
}
</style>

<?php 
include('../security.php');
include('est_queries.php');
if(isset($_POST['filter'])){
    $prj_id=$_POST['prj_id'];
    $stat_ids=$_POST['stat_id'];
    if($stat_ids){
        $stat_q="AND Estimate_Status_Id IN ('$stat_ids')";
    }
    else{
        $stat_q="";
    }
}
if(isset($_GET['id'])){
    $prj_id=$_GET['id'];
}
$prj="SELECT * FROM project_estimation WHERE Prj_Est_Id='$prj_id' AND PE_Status=1";
    $prj_run1=mysqli_query($connection,$prj);
    $sys="SELECT * FROM estimate WHERE Prj_Est_Id='$prj_id' AND Est_Status=1 $stat_q";
    $prj_html='';
    $sys_run=mysqli_query($connection,$sys);
    if(mysqli_num_rows($prj_run1)>0){
        $row=mysqli_fetch_assoc($prj_run1);
        $name=$row['PE_Name'];
        $code=$row['PE_Code'];
        $category=$row['PE_Category'];
        $type=$row['PE_Type'];
        $date=$row['PE_Date'];
        $location=$row['PE_Emirate_Location'];

        $client_id=$row['Client_Id'];           $client_name=clientName($client_id);
        $mc_id=$row['Main_Contractor_Id'];      $mc_name=mcName($mc_id);
        $cons_id=$row['Consultant_Id'];         $cons_name=consName($cons_id);

        $prj_html.="
            <tr class=''>
                <td class='bold ftable' width='15%'>Project Name: </td>
                <td>$name</td>
                <td></td>
                <td class='bold' width='15%' >Location:</td>
                <td>$location</td>
            </tr>
            <tr>
                <td class='bold'>Code:</td>
                <td>$code</td>
                <td></td>
                <td class='bold'>Client:</td>
                <td>$client_name</td>
            </tr>
            <tr>
                <td class='bold'>Date:</td>
                <td>$date</td>
                <td></td>
                <td class='bold'>Main Contractor:</td>
                <td>$mc_name</td>
            </tr>
            <tr>
                <td class='bold'>Category:</td>
                <td>$category</td>
                <td></td>
                <td class='bold'>Consultant:</td>
                <td>$cons_name</td>
            </tr>
            <tr>
                <td class='bold'>Type:</td>
                <td>$type</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>";
    }
    if(mysqli_num_rows($sys_run)>0){
        $sys_html="
                <tr>
                    <td>System</td>
                    <td>No. of Apartment</td>
                    <td>No. of Bathroom</td>
                    <td>Connected Load</td>
                    <td>Total Tonnage</td>
                    <td>Ave. Bua</td>
                    <td>Status</td>
                    <td>System Price</td>
                </tr>";
        $tot_price=null;
        while($row1=mysqli_fetch_assoc($sys_run)){
            $sys_id=$row1['Prj_Sys_Id'];            $sys_name=sysName($sys_id); $dept_name=sysDeptName($sys_id);
            $ap_no=$row1['Est_No_Appartment'];
            $bath_no=$row1['Est_No_Bathroom'];
            $con_load=$row1['Est_Connected_Load'];
            $tot_ton=$row1['Est_Total_Tonnage'];
            $ave_bua=$row1['Est_Ave_BUA'];
            $tot_bua=$row1['Est_Total_BUA'];
            $sys_price=$row1['Est_Total_Price'];
            $tot_price=$tot_price+$sys_price;
            $sys_price=number_format($sys_price);
            $stat_id=$row1['Estimate_Status_Id'];   $stat_name=statName($stat_id);
            $sys_html.="
                <tr>
                    <td>$sys_name</td>
                    <td align='right'>$ap_no</td>
                    <td align='right'>$bath_no</td>
                    <td align='right'>$con_load</td>
                    <td align='right'>$tot_ton</td>
                    <td align='right'>$ave_bua</td>
                    <td align='right'>$stat_name</td>
                    <td align='right'>$sys_price</td>
                </tr>";
        }
        $tot_price=number_format($tot_price);
        $sys_html.="
                <tr>
                    <td colspan='7'></td>
                    <td align='right'>$tot_price</td>
                </tr>";
    }
?>

<div class="PrintOnly">
    <table style="width:100%; border-spacing: 0px;  border-collapse: collapse;">
    <tr>
        <td style="width: 146px; padding-top: 22px; padding-right: 3px">
        <img src="img/EMT LOGOf.png" alt="" style="width:143px; height: 78px">
        </td>
        <td>
        <p class="hind"><b>E M T Electromechanical Works L.L.C.</b></p> 
        <p class="red">اي ام تي للأعمال الكهروميكانيكيه ش.ذ.م.م</p>
        <!-- <font face="Hind Siliguri"> -->

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
    <table style="width:100%;"> <?php echo $prj_html;?> </table>
    <table  border="1" id="" width="100%" cellspacing="0" class="">
        <?php echo  $sys_html;?>
    </table>
</div>