<?php
include('security.php');
include('includes/header.php');
include('includes/user_navbar.php');
error_reporting(0);
$username = $_SESSION['USERNAME'];
$query = "SELECT * FROM employee LEFT JOIN users ON users.USER_ID =employee.USER_ID WHERE users.USERNAME='$username' AND users.USER_STATUS=1 AND employee.EMP_STATUS=1";
$query_run = mysqli_query($connection, $query);
if(mysqli_num_rows($query_run)>0){
    while($row=mysqli_fetch_assoc($query_run)){
        $emp_id=$row['EMP_ID'];
        $emp_no=$row['EMP_NO'];
        $emp_fname=$row['EMP_FNAME'];
        $emp_fname=ucwords(strtolower($emp_fname));
        $desig=$row['EMP_DESIGNATION'];
        $doj=$row['EMP_DOJ'];
        $doj_date=date("F d, Y", strtotime($doj));
    }
}
//select from increment msg
$q_inc="SELECT * FROM increment_mgs WHERE Emp_Id='$emp_id' AND Inc_Msg_Status=1";
$q_inc_run=mysqli_query($connection,$q_inc);
if(mysqli_num_rows($q_inc_run)>0){
   $row_msg=mysqli_fetch_assoc($q_inc_run);
   $old_desig=$row_msg['Old_Designation'];
   $old_sal=$row_msg['Old_Salary'];
   $category=$row_msg['Catergory'];
   if($category){
       $message="<h4><span class='text-primary font-weight-bold'>Category:</span> ".$category."</h4>";
   }
   $increment=$row_msg['Increment'];
   $new_desig=$row_msg['New_Designation'];
//    $new_desig='Web Dev';
   $new_sal=$row_msg['New_Salary'];
   $new_sal_text=number_format($new_sal=$row_msg['New_Salary'], 2, '.', ',');
   if($old_desig==$new_desig AND $old_sal<$new_sal ){ // no promotion, increased salary only
        $message .="
                <br>
                Congratulations ".$emp_fname." on your well-deserved salary increase of <span class='text-primary font-weight-bold'>".$new_sal_text."</span> AED per month. <br><br>
                This promotion is effective in month of July, 2023. <br><br>
                We are pleased to award you this salary raise in gratitude for your continued hard work on behalf of our company. <br><br>
                Thank you for your loyalty and professional excellence ";
   }
   elseif($old_desig!=$new_desig AND $old_sal<$new_sal){ //promotion, increased salary
        $message .="Congratulations ".$emp_fname." on your promotion as <span class='text-primary font-weight-bold'>".$new_desig."</span> and well-deserved salary increase of <span class='text-primary font-weight-bold'>".$new_sal_text."</span> AED per month. <br><br>
                This promotion is effective in month of July, 2023. <br><br>
                We are pleased to award you this salary raise in gratitude for your continued hard work on behalf of our company. <br><br>
                Thank you for your loyalty and professional excellence";
   }
   elseif($increment=='' || $increment=NULL || $increment==0){ // same salary
     $message.="Dear ".$emp_fname.", after careful considiration and analysis of your performance there will be no increment to your salary. <br><br>
              We encourage you to enhance your performance further.";
   }
   elseif($increment<0){ // deduction
    $message."Dear ".$emp_fname.", after careful considiration and analysis of your performance there will be no increment to your salary. <br><br>
            We are not satisfied with your production and your salary did not match your category. 
            After 3 months there will be a re-evaluation for your performance and if there is no improvement deduction may apply.";
   }
   ?>
   <input type="hidden" id="msg_input" value="<?php echo $message;?>">
   <script>
    $(document).ready(function (){
        var msg = $('#msg_input').val();
        $('#message').html(msg).change();
        $('#msgModal').modal('show');
    });
   </script>
   <?php
}
//date since doj
date_default_timezone_set('Asia/Dubai');
$now = time();
$dojj = strtotime($doj);
$days = $now-$dojj;
$days = round($days/(60*60*24));
if($days > 30) {
    $month = $days/30;
    if($month>12){
        $years=$month/12;
        $years=floor($years); // whole
        $months=$month%12;
        if($months>1){
            $word_month='months';
        }
        else{
            $word_month='month';
        }
        if($years>1){
            $word_year='years';
        }
        else{
            $word_year='year';
        }
    }
    $days = $days % 30;
    $date_html= $years.' years and '.$months.' months';
}
else{
    $date_html=$days;
}

$p_query = "SELECT * FROM payslip WHERE EMP_ID=$emp_id AND P_STATUS=1 ORDER BY P_FULL_BASIC asc";
$p_query_run=mysqli_query($connection,$p_query);
if(mysqli_num_rows($p_query_run)>0){ $salary_arr=array();
    while($rowp=mysqli_fetch_assoc($p_query_run)){
        $payslip_id=$rowp['PAYSLIP_ID'].'<br>';
        $e_basic = $rowp['P_FULL_BASIC']; //basic
        $query4="SELECT * FROM full_allowance LEFT JOIN payslip on payslip.PAYSLIP_ID = full_allowance.PAYSLIP_ID WHERE payslip.PAYSLIP_ID = '$payslip_id'";
        $full_alw_q = mysqli_query($connection, $query4);
        if(mysqli_num_rows($full_alw_q)>0){ $al_total=0;
            while($row1 = mysqli_fetch_assoc($full_alw_q)){
                $al_total = $al_total + $row1['FULL_ALW_AMT'];
            }
        }
        $total1 = $e_basic + $al_total; //1st col
        $salary_arr[$payslip_id]=$total1;
    }
}
$result = array_unique($salary_arr);
$count=count($result);
$html='
<div class="row d-flex justify-content-between col-xl-8 col-lg-7">
';
// $col_no=12/$count; 
$font_size=48;
$col_no=2;
$date_chart=null; $sal_chart=null; 
foreach ($result as $key=>$salary) {
    
    $p_query1 = "SELECT * FROM payslip WHERE PAYSLIP_ID='$key' AND P_STATUS=1";
    $p_query_run1=mysqli_query($connection,$p_query1);
    if(mysqli_num_rows($p_query_run1)>0){
        $row_p=mysqli_fetch_assoc($p_query_run1);
        $date=$row_p['P_DATE'];
        $month=date("F", strtotime($date));
        $year=date("o", strtotime($date));
    }
    $html.='
    <div class="col-'.$col_no.' align-self-center ml-4 p-3">
        <i class="fa fa-reply rotate-15" style="font-size:'.$font_size.'px;" style="opacity: 0.3;" aria-hidden="true"></i><br>
        '.$year.'<br>
        <h5 class="font-weight-bold mt-2">'.$salary.' AED</h5>
    </div>';
    $date_chart.='"'.$month.' '.$year.'",';
    $font_size=$font_size+20;
    $sal_chart.=$salary.',';
}
$html.='
</div>';
?>
<style>
.modal-dialog ,
.modal-content {
    /* 80% of window height */
    height: 95%;
}
</style>
 <script>
    var maxValue=<?php echo max($result)?>;
    var newDataset=[<?php echo $sal_chart;?>];
    var newLabel=[<?php echo $date_chart?>];
  </script>
<div class="container-fluid">
    <div class="">
        <h2 class="text-primary mb-2 h3">Hey <span ><?php echo $emp_fname.'!';?></span></h2>
        <p class="mb-4 h5">
            You've been in EMT Electromechanical Works as <u><?php echo $desig?></u> since <?php echo $doj_date?> (<?php echo $date_html?>).
        </p>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="card shadow mb-4 ">
                <div class="card-header py-3 ">
                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview <i class="d-none fas fa-fw fa-line-chart "></i></h6>
                </div>
                <!-- < ? php echo $html;?> -->
                <div class="card-body ml-4">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                        <input type="hidden" id="emp_id" value="<?php echo $emp_id;?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 ">
                    <h6 class="m-0 font-weight-bold text-primary">Downloadable Files <i class="d-none fas fa-fw fa-line-chart "></i></h6>
                </div>
            <?php
                $q_files="SELECT * FROM files WHERE Emp_Id='$emp_id'";
                $q_files_run=mysqli_query($connection,$q_files);
            ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <td>File Description</td>
                                    <td>Actions</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(mysqli_num_rows($q_files_run)>0){ $file_html='';
                                    while($row_f=mysqli_fetch_assoc($q_files_run)){
                                        $file_type=$row_f['File_Type'];
                                        $filename=$row_f['File_Desc'];
                                        if($file_type=='EID'){ //EMIRATES ID
                                            $file_desc='Emirates ID';
                                            $cfilename='EID'.$emp_no;
                                        }
                                        elseif($file_type=='PP'){ //PASSPORT
                                            $file_desc='Passport';
                                            $cfilename='PP'.$emp_no;
                                        }
                                        elseif($file_type=='VS'){ //VISA
                                            $file_desc='Visa';
                                            $cfilename='VS'.$emp_no; 
                                        }
                                        elseif($file_type=='CNT'){ //CONTRACT
                                            $file_desc='Contract';
                                            $cfilename='CNT'.$emp_no;
                                        }
                                        else{ //OTHER FILE TYPE
                                            $file_desc=$file_type;
                                        }
                                        // check if 2nd or 3rd copy
                                        $ordinal=''; $number=null;
                                        if($cfilename==$filename){
                                            //first copy
                                        }
                                        else{// count how many 1
                                            if (str_contains($cfilename, $filename) || str_contains($filename,$cfilename)) { 
                                                $number=str_replace($cfilename,'',$filename);
                                                // echo $number;
                                                $number=strlen($number);
                                                if($number==1){
                                                    $ordinal='2nd copy';
                                                }
                                                elseif($number==2){
                                                    $ordinal='3rd copy';
                                                }
                                                elseif($number>=3){
                                                    $ordinal=$number++.'th copy';
                                                }
                                                else{
                                                    $ordinal='';
                                                }
                                            }
                                            else{
                                                $ordinal='';
                                            }
                                        }
                                        $checkFile="empFiles/".$filename.".pdf";
                                        $file_html.='<tr>';
                                        //CHECK FILE EXISTS
                                        if(file_exists($checkFile)){
                                            $file_html.='<td>'.$file_desc." ".$ordinal.'</td>
                                                <td>
                                                    <a href="#" class="btn btn-info btn-icon-split viewFile">
                                                        <span class="icon text-white">
                                                            <i class="fas fa-file-text"></i>
                                                        </span>
                                                        <span class="text">View</span>
                                                    </a>
                                                    <input type="hidden" value="'.$filename.'">
                                                </td>';
                                        }
                                        else{
                                            $file_html.= '<td>nothing uploaded yet</td><td></td>';
                                        }
                                       
                                    }
                                }
                                echo $file_html;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-7">
            <div class="card shadow ">
                <div class="card-header py-3 "><span class="text-primary"> <h5 class="modal-title" id="" ><i class="fa fa-font-awesome" aria-hidden="true"></i> Notification </h5> </span>
                </div>
                <div class="card-body">
                    <?php echo $message;?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl view-md" role="document">
    <div class="modal-content view">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ff">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="text-primary"> <h5 class="modal-title" id="" ><i class="fa fa-font-awesome" aria-hidden="true"></i> Notification </h5> </span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="message">
        Message
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <!-- <script src="js/demo/chart-bar-demo.js"></script>  -->
<script>
    $(document).on('click','.viewFile', function(){
    var filename =$(this).next().val();
    filename=filename+'.pdf';
    $.ajax({
        url:'ajax_user.php',
        method: 'POST',
        data:{'filename':filename},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });  
});
</script>
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>