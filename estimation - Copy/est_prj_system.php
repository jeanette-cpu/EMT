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
  <!-- <body onload="window.print()"> -->
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
  /* .PrintOnly{
    font-size:105%;
    visibility: hidden;
  } */
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
include('../includes/header.php'); 
include('../includes/estimation_nav.php');
include('est_queries.php');
error_reporting(0);
if(isset($_GET['id'])){
    $prj_id=$_GET['id'];
    if($_GET['stat_id']){
        $stat_id=$_GET['stat_id'];
        $stat_q="AND Estimate_Status_Id='$stat_id'";
    }
    else{
        $stat_ids=NULL;
        $stat_q="";
    }
}
if(isset($_POST['filter'])){
    $prj_id=$_POST['prj_id'];
    $stat_ids=implode("', '", $_POST['stat_id']);
    $stat_q="AND Estimate_Status_Id IN ('$stat_ids')";
}
    $prj="SELECT * FROM project_estimation WHERE Prj_Est_Id='$prj_id' AND PE_Status=1 ";
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
<div class="container-fluid">
    <form action="est_prj_system.php" method="post">
        <div class="form-row">
            <div class="col-2">
                <label for="">Project</label>
                <select name="prj_id" id="prj_opt" class="form-control " required></select>
            </div>
            <div class="col-2">
                <label for="">Status</label>
                <select name="stat_id[]" id="status_opt" class="form-control selectpicker" multiple required></select>
            </div>
            
            <div class="col-1">
                <label for="" class="invisible">1</label><br>
                <button type="submit" name="filter" class="btn btn-success ">Search</button>
            </div>
        </div>
    </form>
    <div class="PrintOnly">
        <table style="width:100%; border-spacing: 0px;  border-collapse: collapse;">
        <tr>
            <td style="width: 146px; padding-top: 22px; padding-right: 3px">
                <img src="img/EMT LOGOf.png" alt="" style="width:143px; height: 78px">
            </td>
            <td class="pt-4 mt-2" >
                <p class="hind pt-4"><b>E M T Electromechanical Works L.L.C.</b></p> <br>
                <p class="red">اي ام تي للأعمال الكهروميكانيكيه ش.ذ.م.م</p> <br>
                <font face="Hind Siliguri">
                <p style="font-size: 14px; margin-bottom:0">
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
        <table  border="1" id="dataTable" width="100%" cellspacing="0" class="">
            <?php echo  $sys_html;?>
        </table>
    </div>
    <div align="right">
        <div class="form-row" >
            <div class="col-9">

            </div>
            <div class="col-2">
                <form action="est_prj_dl.php" method="POST" target="_blank">
                    <input type="hidden" name="stat_id" value="<?php echo $stat_ids;?>">
                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                    <button type="submit" name="filter" id="btnPrint" class="btn btn-warning mt-2 text-white" >
                        <!-- <a href="est_prj_dl.php?id=< ?php echo $prj_id?>" class="text-white">  -->
                            <i class="fa fa-print" aria-hidden="true"></i> Print
                        <!-- </a> -->
                    </button>  
                </form>
            </div>
            <div class="col-1">
                <button name="" id="btnExcel" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#dataTable'));
    });
});
$(document).ready(function(){
    var client="";
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'status_opt': client},
        success:function(data){
            $(document).find('#status_opt').html(data).change();
        }
    }); 
    $.ajax({
        url:'est_queries.php',
        method: 'POST',
        data:{'prj_opt': client},
        success:function(data){
            $(document).find('#prj_opt').html(data).change();
        }
    });  
});

</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>