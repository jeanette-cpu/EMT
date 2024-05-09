<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php'); 
$username=$_SESSION['USERNAME'];
$query ="SELECT * FROM users WHERE USERNAME='$username'";
$query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id=$row['USER_ID'];
$q_company="SELECT * FROM company WHERE User_Id='$user_id' AND Comp_Status=1 and Comp_Approval=1 LIMIT 1";
// $q_company="SELECT * FROM company WHERE Comp_id='63'  LIMIT 1";
$q_company_run=mysqli_query($connection,$q_company);
$row_c=mysqli_fetch_assoc($q_company_run);
if($q_company_run){
    $comp_id=$row_c['Comp_Id'];
    $comp_name=$row_c['Comp_Name'];
    $type=$row_c['Comp_Type'];
    $scopeAuth=$row_c['Comp_Scope_Auth'];
    $website=$row_c['Company_Website'];
    $CP_name=$row_c['Comp_Contact_Person'];
    $CP_position=$row_c['Comp_Contact_Position'];
    $CP_mobile=$row_c['Comp_Contact_Mobile'];
    $CP_landline=$row_c['Comp_Contact_Landline'];
    $CP_email=$row_c['Comp_Contact_Email'];
    $TRN=$row_c['Comp_TRN'];
    $emirateTL=$row_c['Comp_Emirate_TRL'];
    $mg_name=$row_c['Comp_Manager_Name'];
    $mg_mobile=$row_c['Comp_Manager_Mobile'];
    $mg_landline=$row_c['Comp_Manager_Landline'];
    $mg_email=$row_c['Comp_Manager_Email'];
    $license_exp=$row_c['Comp_Reg_End_Date'];
    //search if theres uploaded
    $comp_prof="..\..\uploads\cProf".$comp_id.".pdf";
    $trn_file="..\..\uploads\TRN".$comp_id.".pdf";
    if($type=='oem'){
        $type='Manufacturer/OEM';
    }
    elseif($type=='distributor' or $type=='trading'){
        $type =ucfirst($type);
    }
    elseif($type=='subcon'){
        $type='Subcontractor';
    }
    elseif($type=='laborSupply'){
        $type='Labor Supply';
    }
    //image source address
    $stamp='cstamp'.$comp_id.'.'.$row_c['Comp_Stamp'];
    $s1_1=$comp_id.'_csig1-1.'.$row_c['s1_1'];
    $s1_2=$comp_id.'_csig1-2.'.$row_c['s1_2'];
    $s1_3=$comp_id.'_csig1-3.'.$row_c['s1_3'];
    $s2_1=$comp_id.'_csig2-1.'.$row_c['s2_1'];
    $s2_2=$comp_id.'_csig2-2.'.$row_c['s2_2'];
    $s2_3=$comp_id.'_csig2-3.'.$row_c['s2_3'];
    $s3_1=$comp_id.'_csig3-1.'.$row_c['s3_1'];
    $s3_2=$comp_id.'_csig3-2.'.$row_c['s3_2'];
    $s3_3=$comp_id.'_csig3-3.'.$row_c['s3_3'];
    $signatory1=$row_c['Comp_Sig_Name1'];
    $signatory2=$row_c['Comp_Sig_Name2'];
    $signatory3=$row_c['Comp_Sig_Name3'];

    if (file_exists('../../uploads/comp_stamp/'.$stamp)) {
        $stamp_lbl=""; $stamp_v="";
    }
    else{
        $stamp_v="d-none"; $stamp_lbl="no file";
    }
    if(file_exists('../../uploads/signitures/'.$s1_1)){
        $s1_1_v=""; $s1_1_lbl="";
    }
    else{
        $s1_1_v="d-none"; $s1_1_lbl="no signatory 1 file";
    }
    if(file_exists('../../uploads/signitures/'.$s2_1)){
        $s2_1_v=""; $s2_1_lbl="";
    }
    else{
        $s2_1_v="d-none"; $s2_1_lbl="no signatory 2 file";
    }
    if(file_exists('../../uploads/signitures/'.$s3_1)){
        $s3_1_v=""; $s3_1_lbl="";
    }
    else{
        $s3_1_v="d-none"; $s3_1_lbl="no signatory 3 file";
    }
    $attach_html='
    <div class="row mt-2">
        <div class="col text-primary font-weight-bold"> Company Stamp
        </div>
    </div>
    <div class="row">
        <div class="col-3 mr-1">
            '.$stamp_lbl.'
            <a href="#" class="pop '.$stamp_v.'"><img src="../../uploads/comp_stamp/'.$stamp.'"" style="width: 350px; height: 200px;"  alt="no image ">
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <span class=" text-primary font-weight-bold">Signatories</span><br>
            '.$s1_1_lbl.'
            '.$s2_1_lbl.'
            '.$s3_1_lbl.'
        </div>
    </div>
    <div class="row">
        <div class="col '.$s1_1_v.'">1.'.$signatory1.'</div> 
    </div>
    <div class="row '.$s1_1_v.'">
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s1_1.'"" style="width: 350px; height: 200px;"  alt="no image ">
            </a>
        </div>
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s1_2.'"  style="width: 350px; height: 200px;" alt="no image ">
            </a>
        </div>
        <div class="col-3">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s1_3.'"  style="width: 350px; height: 200px;" alt="no image ">
            </a>
        </div>
    </div>
    <div class="row '.$s2_1_v.'">
        <div class="col">2.'.$signatory2.'</div>
    </div>
    <div class="row mt-1 '.$s2_1_v.'">
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img  src="../../uploads/signitures/'.$s2_1.'" style="width: 350px; height: 200px;" alt="no image ">
            </a>
        </div>
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s2_2.'" style="width: 350px; height: 200px;" alt="no image ">
            </a>
        </div>
        <div class="col-3">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s2_3.'" style="width: 350px; height: 200px;" alt="no image ">
            </a>
        </div>
    </div>
    <div class="row '.$s3_1_v.'">
        <div class="col">3.'.$signatory3.'</div>
    </div>
    <div class="row mt-1 '.$s3_1_v.'">
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img  src="../../uploads/signitures/'.$s3_1.'" style="width: 350px; height: 200px;" alt="no image">
            </a>
        </div>
        <div class="col-3 mr-1">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s3_2.'" style="width: 350px; height: 200px;" alt="no image">
            </a>
        </div>
        <div class="col-3">
            <a href="#" class="pop"><img src="../../uploads/signitures/'.$s3_3.'" style="width: 350px; height: 200px;" alt="no image">
            </a>
        </div>
    </div>';
}
else{
    echo 'Error Loading Company Details';
}
?>
<style>
.modal-dialog,
.modal-content {
    /* 80% of window height */
    height: 95%;
}
.modal-body {
    /* 100% = dialog height, 120px = header + footer */
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
</style>
<div class="container-fluid">
    <input type="hidden" id="comp_id" value="<?php echo $comp_id?>">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Company Profile</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h5 class="mt-1 font-weight-bold"><?php echo $comp_name?></h5>
                </div>
                <div class="col-6" align="right">
                    <a href="edit_profile.php"><i class="fa fa-file-text mr-2" aria-hidden="true"></i>Edit Profile</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-3">
                    <b>Type :</b>       
                </div>
                <div class="col-3">
                    <?php echo $type?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>Scope Authorized :</b>
                </div>
                <div class="col-3">
                    <?php echo $scopeAuth?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>TRN :</b>
                </div>
                <div class="col-3">
                    <?php echo $TRN?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>Emirate Trade Licensed:</b>
                </div>
                <div class="col-3">
                    <?php echo $emirateTL;?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>Website :</b>
                </div>
                <div class="col-3">
                    <?php echo $website?>
                </div>
            </div>
            <!-- SEARCH IF THERE IS A TRADELICENSE -->
            <div class="row">
                <div class="col-3">
                    <b>Trade License File :</b>
                </div>
                <div class="col-3">
                <?php 
                if (file_exists($trn_file)) {
                    echo '<a href="#" class=" view btn-sm text-primary">
                            <span class="text">View</span>
                            <span class="icon ">
                                <i class="fas fa-file-pdf-o"></i>
                            </span>
                        </a>';
                    } else {
                        echo "no file";
                    }
                ?>
                    <input id="trn" type="hidden" value="<?php echo $TRN?>">
                </div>
            </div>
            <div class="row">
                <div class="col-3 font-weight-bold">
                    Licence Expire Date:
                </div>
                <div class="col-3">
                    <?php echo $license_exp;?>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>Company Profile :</b>
                </div>
                <div class="col-3">
                <?php 
                if (file_exists($comp_prof)) {
                    echo '<a href="#" class="viewProf btn-sm text-primary">
                            <span class="text">Company Profile (.pdf)</span>
                            <span class="icon ml-1">
                                <i class="fas fa-file-pdf-o"></i>
                            </span>
                        </a>';
                    } else {
                        echo "no file";
                    }
                ?>
                    <input id="trn" type="hidden" value="<?php echo $TRN?>">
                </div>
            </div>  
            <h5 class="mt-3 text-primary">Contact Information</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <td>Contact Person</td>
                            <td><?php echo $CP_name?></td>
                            <td>Manager</td>
                            <td><?php echo $CP_name?></td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td><?php echo $CP_position?></td>
                            <td>Email</td>
                            <td><?php echo $mg_email?></td>
                        </tr>
                        <tr>
                            <td>Contact No:</td>
                            <td><?php echo $CP_mobile?></td>
                            <td>Contact No:</td>
                            <td><?php echo $mg_mobile?></td>
                        </tr>
                        <tr>
                            <td>Landline:</td>
                            <td><?php echo $CP_landline?></td>
                            <td>Landline:</td>
                            <td><?php echo $mg_landline?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $CP_email?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <?php 
                    echo $attach_html;
                ?>
            </div>
        </div>
    </div>
<div>
<div class="modal fade" id="viewPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
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
<!-- image modal company sig -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">              
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
<script>
$(document).on('click','.view', function(){
    var comp_id = $(document).find('#comp_id').val();
    filename='TRN'+comp_id+'.pdf';
    $.ajax({
        url:'../PM/ajax_purchase.php',
        method: 'POST',
        data:{'filename':filename},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });
});
$(document).on('click','.viewProf', function(){
    var comp_id = $(document).find('#comp_id').val();
    cprofile='cProf'+comp_id+'.pdf';
    // alert(filename);
    $.ajax({
        url:'../PM/ajax_purchase.php',
        method: 'POST',
        data:{'filename':cprofile},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });  
});
$(document).ready(function(){
  $(document).on('click','.pop', function(){
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>