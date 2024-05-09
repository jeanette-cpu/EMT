<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
$q_job_post="SELECT * FROM post as p
LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id
WHERE Post_Type!='material' AND Post_Status!=0 ";
$q_job_post_run=mysqli_query($connection,$q_job_post);
error_reporting(0);
$username=$_SESSION['USERNAME']; //purchase department username
$query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='purchase' AND USER_STATUS=1 LIMIT 1";
$query_run_user = mysqli_query($connection, $query_user);
if(mysqli_num_rows($query_run_user)==1){
    $row=mysqli_fetch_assoc($query_run_user);
    $user_email=$row['USER_EMAIL'];
    $user_id=$row['USER_ID'];
}
?>
<div class="container-fluid">
<input type="hidden" value="<?php echo $user_email?>" id="pmail">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Job Posts
                <!-- BUTTON -->
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addPost">
                    <i class="fa fa-plus" aria-hidden="true"></i> New Post
                </button></h5>
        </div>
        <div class="form-row mt-1">
            <div class="col-4 ml-3">
                <label for="">Projects</label>      
                    <select name="" id="project_opt" class="form-control prj_opt filter ">
                        <option value="" disabled> Select Project</option>
                    </select>  
            </div>
            <div class="col-2 ">
                <label for="">Status</label>      
                <select name="" id="status_opt" class="form-control filter">
                    <option value="Select Status" disabled> Select Status</option>
                    <option value="all">All</option>
                    <option value="1">Active</option>
                    <option value="2">Closed</option>
                </select>  
            </div>
            <div class="col-2">
                <label for="">From:</label>
                <input type="date" class="form-control filter" id="fromDate">
            </div>
            <div class="col-2">
                <label for="">To:</label>
                <input type="date" class="form-control filter" id="toDate">
            </div>
        </div>
        <div class="card-body">
            <div id="divP">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <th class="d-none">Ref. No</th>
                            <th>Post Description</th>
                            <th>Project</th>
                            <th>Quotations</th>
                            <th>Status</th>
                            <th>Date Posted</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        <?php
                            if(mysqli_num_rows($q_job_post_run)>0)
                            {
                                while($row = mysqli_fetch_assoc($q_job_post_run))
                                {
                                    $post_id=$row['Post_Id'];
                                    //status
                                    $post_status=$row['Post_Status'];
                                    if($post_status==1){
                                        $btn_html='<button class="btn btn-sm btn-success disabled">Active</button>';
                                    }
                                    elseif($post_status==2){
                                        $btn_html='<button class="btn btn-sm btn-danger disabled">Closed</button>';
                                    }
                                    else{ $btn_html='';}
                                    $date=$row['Post_Date'];
                                    $post_date=date("m/d/Y",strtotime($date));
                                    //  quote applied
                                    $quotes="SELECT DISTINCT q.Quote_Id FROM quote as q 
                                            LEFT JOIN post as p on p.Post_Id=q.Post_Id 
                                            LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                                            WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
                                    $quote_run=mysqli_query($connection,$quotes);
                                    $quote_applied=mysqli_num_rows($quote_run);
                                    if($quote_applied>0){
                                        $disabled='disabled';
                                    }
                                    else{
                                        $disabled='';
                                    }
                        ?>
                            <tr>
                                <td class="d-none"></td>
                                <td><input type="hidden" value="<?php echo $post_id?>"><a href="#" class="text-primary postView"><?php echo $row['Post_Name'];?></a></td>
                                <td><?php echo $row['Prj_Code'].' '.$row['Prj_Name'];?></td>
                                <td><a href="quotation.php?post_id=<?php echo $post_id?>&type=job_post"> <?php echo $quote_applied?> </a></td>
                                <td><?php echo $btn_html?></td>
                                <td><?php echo $post_date?></td>
                                <td class="btn-group">
                                    <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                                    <button type="button" name="send_mail" class="btn btn-warning rounded sendMail">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </button>
                                    <form action="edit_job_post.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                                        <button type="submit" name="edit_post" class="btn btn-success editPost" <?php echo $disabled?>>
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                    <form action="code.php" method="post">
                                        <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                                        <button type="submit" name="delPost" class="btn btn-danger delBtn">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                                }
                            }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Job Post -->
<div class="modal fade bd-example-modal-lg" id="addPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fa fa-user mr-2" aria-hidden="true"></i>Job Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post"  enctype="multipart/form-data">
      <div class="modal-body">
            <div class="form-group ">
                <div class="">
                    <label class="font-weight-bold" for="">Post Name: *</label>
                    <input type="text" name="post_name" class="form-control" Post_Type!='material'>
                </div>
                <div class="">
                    <label class="font-weight-bold mt-2" for="">Post Description:</label>
                    <input type="text" name="post_desc" class="form-control" >
                </div>
                    <label class="font-weight-bold mt-2" for="">Type: *</label>
                    <select name="job_type" id="job_type" class="form-control" Post_Type!='material'>
                        <option value="" disabled>Select Type</option>
                        <option value="manpower">Manpower Supply</option>
                        <option value="subcontractor">Subcontractor</option>
                    </select>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="">Project: *</label>
                <select name="project" id="project" class="form-control" Post_Type!='material'>
                    <option value=""></option>
                </select>
            </div>
            <label>Details</label>
            <div class="table table-responsive">
                    <table  class="table table-bordered" id="serviceTbl">
                        <tr class="d-none" id="tr_mp">
                            <td class="col-2">
                                <div class="form-group">
                                    <label for="">Department *</label><br>
                                    <select name="dept_id[]" class="form-control check-fields4 dept_opt" >
                                        <option value="">Select Deparment</option>
                                    </select>
                                </div>
                            </td>
                            <td class="col-6">
                                <div class="form-group">
                                    <label for="">Description *</label><br>
                                    <input name="desc[]" minlength="5" maxlength="35" type="text" class="form-control check-fields4" >
                                </div>
                            </td>
                            <td class="col-2">
                                <div class="form-group">
                                    <label for="">Unit *</label><br>
                                    <select name="unit_mp[]" minlength="1" maxlength="35" class="form-control">
                                        <option value="Hr">Hr</option>
                                        <option value="Day">Day</option>
                                    </select>
                                </div>
                            </td>
                            <td class="col-2 qtyLbl ">
                                <div class="form-group">
                                    <label for="" >Qty. *</label><br>
                                    <input name="qty[]" type="number" maxlength="20" class="form-control check-fields4">
                                </div>
                            </td>
                            <td class="col-2 sqLbl d-none">
                                <div class="form-group">
                                    <label for=""  >Sqr. Ft.</label><br>
                                    <input name="Qty[]" type="number" maxlength="20" class="form-control check-fields4">
                                </div>
                            </td>
                            <td class="col-2">
                            </td>
                        </tr>
                        <tr class="d-none" id="tr_sb">
                            <td class="col-2">
                                <div class="form-group">
                                    <label for="">Department *</label><br>
                                    <select name="dept_id_sb[]" class="form-control dept_opt" >
                                        <option value="">Select Deparment</option>
                                    </select>
                                </div>
                            </td>
                            <td class="col-6">
                                <div class="form-group">
                                    <label for="">Description *</label><br>
                                    <input name="desc_sb[]" minlength="5" maxlength="35" type="text" class="form-control " >
                                </div>
                            </td>
                            <!-- sq.m/flat -->
                            <td class="col-2">
                                <div class="form-group">
                                    <label for="" >Unit*</label><br>
                                    <select name='unit_sb[]'  class='form-control'> <option value='Sq. F'>price/Sq. F</option> </select>
                                </div>
                            </td>
                            <!-- 12000 -->
                            <td class="col-2">
                                <div class="form-group">
                                    <label for="" >Area</label><br>
                                    <input name="qty_sb[]" type="number" maxlength="20" class="form-control check-fields4">
                                </div>
                            </td>
                            <td class="col-2">
                            </td>
                        </tr>
                    </table>
                    <div align="right" class="d-none" id="adBtnMP">
                        <button type="button" name="add" id="adBtn" class="btn btn-success btn-xs">+</button>
                    </div>
                    <div align="right" class="d-none" id="adBtnSB">
                        <button type="button" name="add" id="addBtnSB" class="btn btn-success btn-xs">+</button>
                    </div>
                </div>
            <input type="hidden" name="postDate" value="">
            <!-- SEND Email -->
            <div class="form-group">
                <label for="" class="mr-2 mt-1 ">Send with email notification?</label>
                <input type="checkbox" name="emailSend" id="emailNotif" value="1">
            </div>
            <div class=" mt-1 pl-3 pb-3 pt-3 ml-3 mr-3 mb-3 d-none" id="grpForm" style="background-color: #f7f9fd;">
                <div class="form-row">
                    <div class="col-6">
                        <label for="">Attachment: </label>
                        <input type="file" name="attachment">
                    </div>
                </div>
                <div class="form-row pt-2"  style="background-color: #f7f9fd;">
                    <div class="col-7">
                        <label for="">CC Email: </label>
                        <select name="ccEmail[]" id="ccEmail1" class="form-control selectpicker" multiple></select>
                    </div>
                    <div class="col-4">
                        Other CC email:
                        <input type="email" id="otherEmails3" name="" class="form-control form-control-sm">
                    </div>
                    <div class="col-1">
                        <div class="invisible"><label>1</label></div>
                        <button id="addEmail3" class="btn btn-success btn-sm" type="button">+ Add</button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-7">
                        <label for="">Bcc Email Group:</label>
                        <select name="bccEmail[]" id="bcc_emailGrp" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                    </div>
                    <div class="col-4">
                        <label for="">Other Emails:</label>
                        <input type="email" id="bccOtherEmail" class="form-control form-control-sm">
                    </div>
                    <div class="col-1">
                        <div class="invisible"><label>1</label></div>
                        <button id="addEmail4" class="btn btn-success btn-sm" type="button">+ Add</button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-7">
                        <label for="">Email Group:</label>
                        <select name="emalGrp[]" id="grpOpt1" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                    </div>
                    <div class="col-4">
                        <label for="">Other Emails:</label>
                        <input type="email" id="cEmail" class="form-control form-control-sm">
                    </div>
                    <div class="col-1">
                        <div class="invisible"><label>1</label></div>
                        <button id="addEmail" class="btn btn-success btn-sm" type="button">+ Add</button>
                    </div>
                </div>
                    
            </div>
        <!-- END FORM -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="addJobPost" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- POST MODAL -->
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-file mr-1"></i>Post Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="postDesc">
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button id="quote" class="btn btn-sm btn-info float-right"><i class="fas fa-fw fa-paper-plane fa-1x mr-1"></i>Send Quote</button> -->
      </div>
    </div>
  </div>
</div>
<!-- End Modal ADD Post -->
<!-- SEND EMAIL MODAL -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-envelope mr-2"></i>Send Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="post"  enctype="multipart/form-data">
      <div class="modal-body">
        <div id="emailSendBody">
        </div>
        <div class="pt-3 pl-2" id="grpForm" style="background-color: #f7f9fd;">
            <div class="form-row">
                <div class="col-6">
                    <label for="">Attachment</label>
                    <input type="file" class=" " name="attachment" id="">
                </div>
            </div>
            <div class="form-row pt-2"  style="background-color: #f7f9fd;">
                <div class="col-6">
                    <label for="">CC Email: </label>
                    <select name="ccEmail[]" id="ccEmail" class="form-control selectpicker" multiple></select>
                </div>
                <div class="col-4">
                    Other CC email:
                    <input type="email" id="otherEmails2" name="" class="form-control">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addEmail2" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row " id="" style="background-color: #f7f9fd;">
                <div class="col-6">
                    <label for="">BCC Email Group:</label>
                    <select name="bccEmail[]" id="bccGrps" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                </div>
                <div class="col-4">
                    <label for="">Other company email:</label>
                    <input type="email" id="otherEmailsBcc" class="form-control form-control-sm">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addBcc" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row " >
                <div class="col-6">
                    <label for="">Email Group:</label>
                    <select name="emalGrp[]" id="emailGrps" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                </div>
                <div class="col-4">
                    <label for="">Other Emails:</label>
                    <input type="email" id="otherEmails" class="form-control form-control-sm">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addEmail1" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" name="post_id" id="post_id">
        <button type="submit" name="sendEmailJP" class="btn btn-sm btn-info float-right"><i class="fas fa fa-envelope-o fa-1x mr-2"></i>Send Mail</button>
      </div>
      </form>
    </div>
  </div>
</div> 
<script>
$(document).ready(function(){
    var cnt = 1;//SERVICE TBL
    $('#adBtn').click(function(){
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+cnt+"' class='new_mp'>";
            html_code += "<td><select name='dept_id[]' id='dept_optt' class='form-control ' Post_Type!='material'> <option value=''>Select Department</option> </select></td>";
            html_code += "<td><input name='desc[]' class='form-control no-border' type='text' Post_Type!='material'></td>";
            html_code += "<td><select name='unit_mp[]' min='1' class='form-control'><option value='Hr'>Hr</option><option value='Day'>Day</option></select></td>";
            html_code += "<td><input name='qty[]' class='form-control no-border' type='number' Post_Type!='material'></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#serviceTbl').append(html_code);
            $(document).find('#row'+cnt+' #dept_optt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
            cnt = cnt + 1; 
                }
            });
    });
});
$(document).ready(function(){
    var cnt = 1;//SERVICE TBL
    $('#addBtnSB').click(function(){
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+cnt+"' class='new_sb'>";
            html_code += "<td><select name='dept_id_sb[]' id='dept_optt' class='form-control ' Post_Type!='material'> <option value=''>Select Department</option> </select></td>";
            html_code += "<td><input name='desc_sb[]' class='form-control no-border' type='text' Post_Type!='material'></td>";
            html_code += "<td><select name='unit_sb[]'  class='form-control'> <option value=''>price/Sq. F</option> </select></td>";
            html_code += "<td><input name='qty_sb[]' class='form-control no-border' type='number' Post_Type!='material'></td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#serviceTbl').append(html_code);
            $(document).find('#row'+cnt+' #dept_optt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
            cnt = cnt + 1; 
                }
            });
    });
});
$(document).ready(function(){
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });   
});  
$(document).ready(function () {
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('#project').html(data).change();
        $('#project').selectpicker('refresh');
        }
    });
}); 
$(document).ready(function () {
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.dept_opt').html(data).change();
        $('.dept_opt').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {
    var j_type=$('#job_type').val();
    if(j_type=='manpower'){
        $("#tr_mp").removeClass("d-none");
        $("#adBtnMP").removeClass("d-none");
    }
    if(j_type=='subcontractor'){
        $("#tr_sb").removeClass("d-none");
        $("#adBtnSB").removeClass("d-none");
    }
});
$(document).ready(function(){
    $(document).on('change', '#job_type', function(){
        var j_type=$(this).val();
        if(j_type=='manpower'){
            $("#tr_mp").removeClass("d-none");
            $("#tr_sb").addClass("d-none");
            $("#adBtnMP").removeClass("d-none");
            $("#adBtnSB").addClass("d-none");
            $('.new_sb').remove();
        }
        if(j_type=='subcontractor'){
            $("#tr_sb").removeClass("d-none");
            $("#tr_mp").addClass("d-none");
            $("#adBtnSB").removeClass("d-none");
            $("#adBtnMP").addClass("d-none");
            $('.new_mp').remove();
        }
    });   
});  
$(document).ready(function () {// POST details VIEW
    $(document).on('click','.postView', function(){
        var post_id = $(this).prevAll('input').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'ajax_purchase.php',
                method: 'POST',
                data:{'ppost_id':post_id
                },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
$(document).ready(function(){ // re send email
    $(document).on('click', '.sendMail',function() {
        var pmail=$('#pmail').val();
        $('#ccEmail1').selectpicker('val', pmail);
        $('#ccEmail').selectpicker('val', pmail);

        var post_id = $(this).prevAll('input').val();
        $('#post_id').val(post_id);
        $('#sendEmailModal').modal('show');
        // show post name
        $.ajax({
            url:'ajax_purchase.php',
            method: 'POST',
            data:{'ppost_id':post_id
            },
            success:function(data){
                $('#emailSendBody').html(data).change();
            }
        });
        //show all groups available
        // select multiple groups
    }); 
    var b='';
    $.ajax({
        url:'code.php',
        method: 'POST',
        data:{'emailGrp':b},
        success:function(data){
            $(document).find('#emailGrps').html(data).change();
            $('#emailGrps').selectpicker('refresh');
            }
        });
}); 
$(document).on('click', '#addEmail1',function() { // add email to options
        var custom_email=$('#otherEmails').val();
        var other_emails =$('#emailGrps').val();
        if(custom_email.length != 0){
            $('#emailGrps').append('<option val="'+custom_email+'">'+custom_email+'</option>');
            $("#emailGrps").selectpicker("refresh");
            $('#emailGrps').selectpicker('val', custom_email);
            $("#emailGrps").selectpicker("refresh");
            $('#otherEmails').val("");
        }
        if(other_emails.length != 0){
            for(i=0;i<other_emails.length;i++){
                $("#emailGrps option[value='" + other_emails[i] + "']").prop("selected", true);
                $("#emailGrps").selectpicker("refresh");
            }
        }
    });
$(document).ready(function () { // project ajax options
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.prj_opt').html(data).change();
        $('.prj_opt').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {// MAIN TABLE FILTERS
    $(document).on('change', '.filter', function(){
        var prj_id =$('#project_opt').val();
        var status =$('#status_opt').val();
        var fromDate =$('#fromDate').val(); 
        var toDate =$('#toDate').val(); 
        //all 
        if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && status!='' && typeof status!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){
            if(status=='all'){
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Type!='material' AND Post_Status!=0 ";
            }
            else{
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Prj_Id="+prj_id+" AND p.Post_Status="+status+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
            }
            ajaxPost($query);
        }
        else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && status!='' && typeof status!=='undefined'){//prj stat
            if(status=='all'){
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Prj_Id="+prj_id+" ORDER BY p.Post_Id DESC";
            }
            else{
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Prj_Id="+prj_id+" AND p.Post_Status="+statu+" ORDER BY p.Post_Id DESC";
            }
            ajaxPost($query);
        }
        else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//prj & date
            $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Prj_Id="+prj_id+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
            ajaxPost($query);
        }else if(status!='' && typeof status!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//status & date
            if(status=='all'){
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
            }
            else{
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Post_Status="+status+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
            }
            ajaxPost($query);
        }else if(fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//date
            $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
            ajaxPost($query);
        }else if(status!='' && typeof status!=='undefined'){//status
            if(status=='all'){
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' LIMIT 500";
            }
            else{
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Post_Status="+status+" ORDER BY p.Post_Id DESC LIMIT 500";
            }
            ajaxPost($query);
        }else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined'){// project
            $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' AND p.Prj_Id="+prj_id+" ORDER BY p.Post_Id DESC";
            ajaxPost($query);
        }
        // alert($query);
        else{//default
            $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type!='material' ORDER BY p.Post_Id DESC LIMIT 500";
            ajaxPost($query);
        }
    });
    function ajaxPost($query){
        // alert($query);
        $.ajax({
            url:'ajax_material_post.php',
            method: 'POST',
            data:{'jquery':$query},
            success:function(data){
                $('#divP').html(data).change();
                $(document).find('#dataTable').DataTable({
                    pageLength: 10,
                    "searching": true,
                    "order": [[ 4, "desc" ]],
                });
            }
        });  
    }
});
$(document).ready(function(){   //email send checkbox
    $('#emailNotif').click(function(){
        if($('#emailNotif').is(':checked')){
            $('#grpForm').removeClass('d-none');
            var a='';
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'emailGrp':a},
            success:function(data){
                $(document).find('#grpOpt1').html(data).change();
                $('.selectpicker').selectpicker('refresh');
                }
            });
        }
        else{
            $('#grpForm').addClass('d-none');
        }
    });
});
$(document).ready(function(){ // add other emails
    $(document).on('click', '#addEmail',function() {
        var custom_email=$('#cEmail').val();
        var other_emails =$('#grpOpt1').val();
        if(custom_email.length != 0){
            $('#grpOpt1').append('<option val="'+custom_email+'">'+custom_email+'</option>');
            $("#grpOpt1").selectpicker("refresh");
            $("#grpOpt1").selectpicker('val', custom_email);
            $("#emails").selectpicker("refresh");
            $('#cEmail').val("");
        }
        if(other_emails.length != 0){
            
            for(i=0;i<other_emails.length;i++){
                $("#grpOpt1 option[value='" + other_emails[i] + "']").prop("selected", true);
                $("#grpOpt1").selectpicker("refresh");
            }
        }
    });
});
$(document).on('click', '#addEmail3',function() {   //custom cc
    var custom_email=$('#otherEmails3').val();
    var other_emails =$('#ccEmail1').val();
    if(custom_email.length != 0){
        $('#ccEmail1').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#ccEmail1").selectpicker("refresh");
        $("#ccEmail1").selectpicker('val', custom_email);
        $("#ccEmail1").selectpicker("refresh");
        $('#otherEmails3').val("");
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            $("#ccEmail1 option[value='" + other_emails[i] + "']").prop("selected", true);
            $("#ccEmail1").selectpicker("refresh");
        }
    }
}); 
$(document).ready(function () { //purchase email options
    var em='';
    $.ajax({
        url:'ajax_purchase.php',
        method: 'POST',
        data:{'pEmails':em},
        success:function(data){
            $(document).find('#ccEmail').html(data).change();
            $(document).find('#ccEmail1').html(data).change();
            $('#ccEmail').selectpicker('refresh');
            $('#ccEmail1').selectpicker('refresh');
        }
    });
});
$(document).on('click', '#addEmail2',function() {
    var custom_email=$('#otherEmails2').val();
    var other_emails =$('#ccEmail').val();
    if(custom_email.length != 0){
        $('#ccEmail').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#ccEmail").selectpicker("refresh");
        $('#ccEmail').selectpicker('val', custom_email);
        $("#ccEmail").selectpicker("refresh");
        $('#otherEmails2').val("");
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            $("#ccEmail option[value='" + other_emails[i] + "']").prop("selected", true);
            $("#ccEmail").selectpicker("refresh");
        }
    }
});
$(document).on('click', '#addPost',function() {
    var pmail=$('#pmail').val();
    $('#ccEmail1').selectpicker('val', pmail);
    $('#ccEmail').selectpicker('val', pmail);
});
$(document).ready(function(){   //email send checkbox
    $('#emailNotif').click(function(){
        if($('#emailNotif').is(':checked')){
            $('#grpForm').removeClass('d-none');
            var a='';
            $.ajax({
            url:'code.php',
            method: 'POST',
            data:{'emailGrp':a},
            success:function(data){
                $(document).find('#grpOpt1').html(data).change();
                $('#grpOpt1').selectpicker('refresh');
                $(document).find('#bcc_emailGrp').html(data).change();
                $('#bcc_emailGrp').selectpicker('refresh');
                }
            });
        }
        else{
            $('#grpForm').addClass('d-none');
        }
    });
});
$(document).on('click', '#addBcc',function() {
    var custom_email=$('#otherEmailsBcc').val();
    var other_emails =$('#bccGrps').val();
    var all_email=[];
    if(custom_email.length != 0){
        $('#bccGrps').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#bccGrps").selectpicker("refresh");
        all_email.push(custom_email);
        $('#otherEmailsBcc').val("");
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            all_email.push(other_emails[i]);
        }
    }
    $('#bccGrps').selectpicker('val',all_email);
    $("#bccGrps").selectpicker("refresh");
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>