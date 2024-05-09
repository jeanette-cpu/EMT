<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php');
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
<style>
    div.dropdown-menu.open { width: 100%; } ul.dropdown-menu.inner>li>a { white-space: initial; }
</style>
<div class="container-fluid">
    <input type="hidden" value="<?php echo $user_email?>" id="pmail">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Manage Material Posts
            <!-- BUTTON -->
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#adduser">
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
            <div class="table-responsive">
                <div id="divP"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Add Material Post -->
<div class="modal fade bd-example-modal-lg" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fa fa-archive mr-2" aria-hidden="true"></i>Material Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="post" enctype="multipart/form-data">
      <div class="modal-body">
          <div class="form-row">
            <div class="col-7">
                <h6 class="font-weight-bold" for="">Email Subject: *</h6>
                <input type="text" name="post_name" class="form-control form-control-sm" required>
            </div>
            <div class="col-5">
                <h6 class="font-weight-bold " for="">Project *</h6>
                <select name="project" id="prj_opt" class="form-control form-control-sm"></select>
            </div>
          </div>
            <div class="mb-3">
                <h6 class="font-weight-bold mt-2" for="">Email Body:</h6>
                <input type="text" name="post_desc" class="form-control form-control-sm" >
            </div>
            <h5 class="text-primary">Material Details</h5>
            <!-- table form -->
            <div class="card" style="background-color: #f7f9fd;">
                <div class="card-body">
                    <div class="form-row mb-2">
                        <div class="col-6">
                            <h6 for="" class="font-weight-bold">Group Name: *</h6>
                            <input type="text" name="grpName" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-6">
                            <h6 for="" class="font-weight-bold ">Location:</h6>
                            <input type="text" name="grpLocation" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-row mt-3">
                    <div class="col-2">
                            <label>
                                <span>Ref. Code</span>
                                <input type="checkbox" value="0" name="ref_code" >
                            </label>
                        </div>
                        <div class="col-2">
                            <label>
                                <span>Location</span>
                                <input type="checkbox" value="0" name="location" >
                            </label>
                        </div>
                        <div class="col-2">
                            <label>
                                <span>Capacity</span>
                                <input type="checkbox" value="0" name="capacity"  >
                            </label>
                        </div>
                        <div class="col-2">
                            <label>
                                <span>ESP (Pa)</span>
                                <input type="checkbox" value="0" name="esp" >
                            </label>
                        </div>
                        <div class="col-2">
                            <label>
                                <span>Preffered Brand</span>
                                <input type="checkbox" value="0" name="prefBrand" >
                            </label>
                        </div>
                    </div>
                    <div class="table table-responsive table-bordered">
                        <table class="table " id="prodTbl">
                            <tr>
                                <td class="col-6">
                                    <div class="form-group">
                                        <h6 class="font-weight-bold">Material *</h6><br>
                                        <select name="mat_id[]" id="mat_opt" class="form-control form-control-sm selectpicker" style="z-index:2" data-live-search="true" data-container='body'>
                                            <option value="">Select Material</option>
                                        </select>
                                        <div class="form-row mt-2 mr-4" >
                                            <div class="col-2"  class="">
                                                <h6 for=""  class="float-right mt-1">Others:</h6> 
                                            </div>
                                            <div class="col-5">
                                                <input type="text" id="input" class="form-control othersInput form-control-sm">
                                            </div>
                                            <div class="col-1"  class="">
                                                <h6 for=""  class="mt-1 float-right">Unit:</h6> 
                                            </div>
                                            <div class="col-4">
                                                <select name="mat_unit[]" id="" class="form-control form-control-sm">
                                                    <option value="">Select Unit</option>
                                                    <option value="m">m</option>
                                                    <option value="No">No</option>
                                                    <option value="Roll">Roll</option>
                                                    <option value="Item">Item</option><option value="Kg">Kg</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="ref_code">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Ref. Code</h6>
                                            <input type="text" name="ref_code[]" class="form-control form-control-sm location_input">
                                    </div>
                                </td>
                                <td class="location">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Location</h6>
                                            <input type="text" name="mat_location[]" class="form-control form-control-sm location_input">
                                    </div>
                                </td>
                                <td class="capacity">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Capacity</h6>
                                            <input type="text" name="mat_capacity[]" class="form-control form-control-sm capacity_input">
                                    </div>
                                </td>
                                <td class="esp">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >ESP (Pa)</h6>
                                            <input type="text" name="mat_esp[]" class="form-control form-control-sm esp_input">
                                    </div>
                                </td>
                                <td class="prefBrand">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Preffered Brand</h6>
                                            <input type="text" name="mat_pref_brand[]" class="form-control form-control-sm prefBrand">
                                    </div>
                                </td>
                                <td class="">
                                    <div class="form-group">
                                        <h6 class="font-weight-bold">Qty*</h6>
                                        <input type="text" name="mat_qty[]" class="form-control form-control-sm" >
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </div>
                        <div align="right">
                            <button type="button" name="add" id="adBtn" class="btn btn-success btn-xs">+</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-1">
                            <input type="text" id="row_val" class="form-control form-control-sm ml-2">
                        </div>
                        <div class="col-3">
                            <button type="button" id="addRow" class="btn btn-success btn-sm ml-1">+ Rows</button>
                        </div>
                    </div>
                    <table cellspacing="5" class="m-2" id="tablep1">
                        <thead>
                            <th width="20%">Reference Code</th>
                            <th width="60%">Material Description *</th>
                            <th width="10%">Unit</th>
                            <th width="10%">Quantity *</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="ref_code[]" class="form-control form-control-sm"></td>
                                <td><input type="text" name="mat_id[]" class="form-control form-control-sm"></td>
                                <td><input type="text" name="mat_unit[]" class="form-control form-control-sm"></td>
                                <td><input type="text" name="mat_qty[]" class="form-control form-control-sm"></td>                    
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="up"></div>
        <!-- END FORM -->
        </div>
        <h5 class="mt-1 ml-2" > 
            <label>Group </label>
            <a href="#" class="btn btn-success btn-circle" id="grpBtn">
                <i class="fas fa-plus"></i>
            </a>
        </h5>
        <div class="form-group">
            <label for="" class="mr-2 mt-1 ml-3">Send with email notification?</label>
            <input type="checkbox" name="emailSend" id="emailNotif" value="1">
        </div>
        <div class="mt-1 pl-3 pb-3 pt-3 ml-3 mr-3 mb-3 d-none" id="grpForm" style="background-color: #f7f9fd;">
            <div class="form-row">
                <div class="col-6">
                    <label for="">Attachment</label>
                    <input type="file" name="attachment">
                </div>
            </div>
            <div class="form-row pt-2"  style="background-color: #f7f9fd;">
                <div class="col-6">
                    <label for="">CC Email: </label>
                    <select name="ccEmail[]" id="ccEmail1" class="form-control selectpicker" multiple></select>
                </div>
                <div class="col-4">
                    Other CC email:
                    <input type="email" id="otherEmails3" name="" class="form-control">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addEmail3" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="">Bcc Email Group:</label>
                    <select name="bccEmail[]" id="bcc_emailGrp" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                </div>
                <div class="col-4">
                    <label for="">Other Emails:</label>
                    <input type="email" id="bccOtherEmail" class="form-control form-control-sm">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addEmail4" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6">
                    <label for="">Email Group:</label>
                    <select name="emalGrp[]" id="grpOpt1" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
                </div>
                <div class="col-4">
                    <label for="">Other Emails:</label>
                    <input type="email" id="cEmail" class="form-control form-control-sm">
                </div>
                <div class="col-2">
                    <div class="invisible"><label>1</label></div>
                    <button id="addEmail" class="btn btn-success btn-sm" type="button">+ Add</button>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="add_mat_post" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Project -->
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
        <div>

        </div>
      </div>
      <div class="modal-footer">
        <!-- <button id="quote" class="btn btn-sm btn-info float-right"><i class="fas fa-fw fa-paper-plane fa-1x mr-1"></i>Send Quote</button> -->
      </div>
    </div>
  </div>
</div> 
<!-- SEND EMAIL MODAL -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-file mr-1"></i>Post Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php"  method="post" enctype="multipart/form-data">
      <div class="modal-body">
        <div id="emailSendBody">
        </div>
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
        <div class="form-row " id="" style="background-color: #f7f9fd;">
            <div class="col-6">
                <label for="">Email Group:</label>
                <select name="emalGrp[]" id="emailGrps" class="form-control selectpicker grpOpt" data-live-search="true" multiple></select>
            </div>
            <div class="col-4">
                <label for="">Other company email:</label>
                <input type="email" id="otherEmails" class="form-control form-control-sm">
            </div>
            <div class="col-2">
                <div class="invisible"><label>1</label></div>
                <button id="addEmail1" class="btn btn-success btn-sm" type="button">+ Add</button>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" name="post_name" id="post_name">
          <input type="hidden" name="post_id" id="post_id" required>
        <button type="submit" name="sendEmail" class="btn btn-sm btn-info float-right"><i class="fas fa fa-envelope-o fa-1x mr-2"></i>Send Mail</button>
      </div>
      </form>
    </div>
  </div>
</div> 
<script>
$(document).ready(function () { // project ajax options
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.prj_opt').html(data).change();
        $(document).find('#prj_opt').html(data).change();
        $('.prj_opt').selectpicker('refresh');
        $('#prj_opt').selectpicker('refresh');
        }
    });
});
$(document).ready(function () {// material ajax options 1st group
    var mat_id='';
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
    method: 'POST',
    data:{'mat_id': mat_id},
    success:function(data){
        $(document).find('#mat_opt').html(data).change();
        $('#mat_opt').selectpicker('refresh');
        }
    });
});
$(document).ready(function(){//1st group
    var cnt = 1;
    var mat_id='';
    $('#adBtn').click(function(){
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
        method: 'POST',
        data: {'mat_id': mat_id},
        success:function(data){
            var html_code = "<tr id='row"+cnt+"'>";
            html_code += "<td><select name='mat_id[]' id='a_mat_id"+cnt+"' class='form-control no-border mat_opt' data-container='body' style='z-index:2' data-live-search='true' required></select>";
            // other material input
            html_code +="<div class='form-row mt-2 mr-4'> <div class='col-2'  class=''> <h6 for=''  class='float-right mt-1'>Others:</h6>  </div> <div class='col-5'> <input type='text' id='input' class='form-control othersInput form-control-sm'></div> <div class='col-1'><h6 for='' class='mt-1 float-right'>Unit:</h6></div><div class='col-4'><select name='mat_unit[]' id='' class='form-control form-control-sm'> <option value=''>Select Unit</option>  <option value='m'>m</option> <option value='No'>No</option> <option value='Roll'>Roll</option> <option value='Item'>Item</option><option value='Kg'>Kg</option> </select></div></div></td>";
            html_code += "<td class='ref_code'><input name='ref_code[]' class='form-control ref_code_input form-control-sm'  type='text'></td>";
            html_code += "<td class='location'><input name='mat_location[]' class='form-control location_input form-control-sm'  type='text'></td>";
            html_code += "<td class='capacity'><input name='mat_capacity[]' class='form-control capacity_input form-control-sm'  type='text'></td>";
            html_code += "<td class='esp'><input name='mat_esp[]' class='form-control esp_input form-control-sm' type='text'></td>";
            html_code += "<td class='prefBrand'><input name='mat_pref_brand[]' class='form-control prefBrand_input form-control-sm' type='text'></td>";
            html_code += "<td> <input name='mat_qty[]'  class='form-control form-control-sm' required>  </td>";
            html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code += "</tr>";
            $('#prodTbl').append(html_code);
            $(document).find('#row'+cnt+' #a_mat_id'+cnt).html(data).change();
            $(document).find('#row'+cnt+' #a_mat_id'+cnt).addClass('selectpicker');
            $('.selectpicker').selectpicker('refresh');
            cnt++;
                $("input:checkbox:not(:checked)").each(function() {
                    var column = "table ." + $(this).attr("name");
                    $(column).hide();
                });
            }
        });
    });
});
$(document).ready(function(){//2ND GROUP
    var c = 1;
    var mat_id='';
    $(document).on("click", "#adBtn1", function() {
        $.ajax({
        url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
        method: 'POST',
        data: {'mat_id': mat_id},
        success:function(data){
            var html_code1 = "<tr id='row"+c+"'>";
            html_code1 += "<td><select name='mat_id1[]' id='a_mat_id1"+c+"' class='form-control no-border mat_opt' data-container='body' style='z-index:2' data-live-search='true' required></select>";
            // other material input
            html_code1 +="<div class='form-row mt-2 mr-4'> <div class='col-2'  class=''> <h6 for=''  class='float-right mt-1'>Others:</h6>  </div> <div class='col-5'> <input type='text' id='input' class='form-control othersInput form-control-sm'></div> <div class='col-1'><h6 for='' class='mt-1 float-right'>Unit:</h6></div><div class='col-4'><select name='mat_unit1[]' id='' class='form-control form-control-sm'> <option value=''>Select Unit</option>  <option value='m'>m</option> <option value='No'>No</option> <option value='Roll'>Roll</option> <option value='Item'>Item</option><option value='Kg'>Kg</option> </select></div></div></td>";
            html_code1 += "<td class='ref_code1'><input name='ref_code1[]' class='form-control ref_code_input1 form-control-sm'  type='text'></td>";
            html_code1 += "<td class='location1'><input name='mat_location1[]' class='form-control location_input1 form-control-sm'  type='text'></td>";
            html_code1 += "<td class='capacity1'><input name='mat_capacity1[]' class='form-control capacity_input1 form-control-sm'  type='text'></td>";
            html_code1 += "<td class='esp1'><input name='mat_esp1[]' class='form-control esp_input1 form-control-sm' type='text'></td>";
            html_code1 += "<td class='prefBrand1'><input name='mat_pref_brand1[]' class='form-control prefBrand_input1 form-control-sm' type='text'></td>";
            html_code1 += "<td> <input name='mat_qty1[]'  class='form-control form-control-sm' required>  </td>";
            html_code1 += "<td><button type='button' name='remove' data-row='row"+c+"' class='btn btn-danger btn-xs remove'>-</button></td>";
            html_code1 += "</tr>";
            $('#prodTbl1').append(html_code1);
            $(document).find('#row'+c+' #a_mat_id1'+c).html(data).change();
            $(document).find('#row'+c+' #a_mat_id1'+c).addClass('selectpicker');
                $('.selectpicker').selectpicker('refresh'); 
                $('.checkTwo:checkbox:not(:checked)').each(function() {
                    var column = $(this).attr("name");
                    $("."+column).hide();
                });
            c++;
            }
        });
    })
});
$(document).ready(function(){ //department options
    $(document).on('change', '#dept_id', function(){
        var dept_id = $(this).val();
        $.ajax({
            url:'../../PURCHASE/PM/ajax_purchase.php',
            method: 'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $(document).find('#prod_desc').html(data).change();
                $('#prod_desc').selectpicker('refresh');
            }
        });
    });   
}); 
// $(document).ready(function () { //purchase email options
//     var em='';
//     $.ajax({
//         url:'ajax_purchase.php',
//         method: 'POST',
//         data:{'pEmails':em},
//         success:function(data){
//             $(document).find('#ccEmail').html(data).change();
//             $(document).find('#ccEmail1').html(data).change();
//             $('#ccEmail').selectpicker('refresh');
//             $('#ccEmail1').selectpicker('refresh');
//         }
//     });
// });
$(document).ready(function(){ // remove row table
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });  
    $(document).on('click', '.rremove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });   
    $(document).on('click', '.iremove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });   
}); 
$(document).ready(function () {// department options
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.dept_opt').html(data).change();
        $('.dept_opt').selectpicker('refresh');
        }
});
$(document).ready(function () {// MAIN TABLE FILTERS
        // $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' LIMIT 500";
        // ajaxPost($query);
        $(document).on('change', '.filter', function(){
            var prj_id =$('#project_opt').val();
            var status =$('#status_opt').val();
            var fromDate =$('#fromDate').val(); 
            var toDate =$('#toDate').val(); 
            //all 
            if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && status!='' && typeof status!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){
                if(status=='all'){
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                }
                else{
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" AND p.Post_Status="+status+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                }
                ajaxPost($query);
            }
            else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && status!='' && typeof status!=='undefined'){//prj stat
                if(status=='all'){
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" ORDER BY p.Post_Id DESC";
                }
                else{
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" AND p.Post_Status="+statu+" ORDER BY p.Post_Id DESC";
                }
                ajaxPost($query);
            }
            else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//prj & date
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                ajaxPost($query);
            }else if(status!='' && typeof status!=='undefined' && fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//status & date
                if(status=='all'){
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                }
                else{
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Post_Status="+status+" AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                }
                ajaxPost($query);
            }else if(fromDate!='' && typeof fromDate!=='undefined' && toDate!='' && typeof toDate!=='undefined'){//date
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Post_Date BETWEEN '"+fromDate+"' AND '"+toDate+"' ORDER BY p.Post_Id DESC";
                ajaxPost($query);
            }else if(status!='' && typeof status!=='undefined'){//status
                if(status=='all'){
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' LIMIT 500";
                }
                else{
                    $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Post_Status="+status+" ORDER BY p.Post_Id DESC LIMIT 500";
                }
                ajaxPost($query);
            }else if(prj_id!='Select Project' && prj_id!='' && typeof prj_id!=='undefined'){// project
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' AND p.Prj_Id="+prj_id+" ORDER BY p.Post_Id DESC";
                ajaxPost($query);
            }
            // alert($query);
            else{//default
                $query="SELECT * FROM post as p LEFT JOIN project as prj on prj.Prj_Id = p.Prj_Id WHERE Post_Status!=0 AND Post_Type='material' ORDER BY p.Post_Id DESC LIMIT 500";
                ajaxPost($query);
            }
        });
    });
    function ajaxPost($query){
        $.ajax({
            url:'ajax_material_post.php',
            method: 'POST',
            data:{'query':$query},
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
$(document).ready(function () {// POST details VIEW
    $(document).on('click','.postView', function(){
        var post_id = $(this).prevAll('input').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'ajax_purchase.php',
                method: 'POST',
                data:{'mpost_id':post_id
                },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});

$(document).ready(function () {//custom input other material
    $(document).on('input','.othersInput', function(){
        var customInput = $(this).val();
        var select_id= $(this).closest('td').find('.selectpicker').attr('id');
        $("#"+select_id).append('<option value="'+customInput+'">'+customInput+'</option>');
        $("#"+select_id).selectpicker('refresh');
        $("#"+select_id).val(customInput);
        $("#"+select_id).selectpicker('refresh');
    });
});
$(document).ready(function(){  //addional group append
    $(document).on('click', '#grpBtn', function(){
        var visible_no=$(".ahh:visible").length;
        if(visible_no==0){
            var data = '<div class="card ahh mt-3" style="background-color: #f7f9fd;"> <div class="card-body"> <div> <a href="#" class="btn btn-danger btn-sm btn-circle removeTbl d-flex float-right" ><i class="fas fa-times"></i></a> </div> <div class="form-row mb-2"> <div class="col-6"> <h6 for="" class="font-weight-bold">Group Name: *</h6> <input type="text" name="grpName1" class="form-control form-control-sm " required> </div> <div class="col-6"> <h6 for="" class="font-weight-bold ">Location:</h6> <input type="text" name="grpLocation1" class="form-control form-control-sm"> </div> </div> <div class="form-row mt-3"> <div class="col-2"> <label> <span>Ref. Code</span><input type="checkbox" value="0" name="ref_code1" class="checkTwo ml-1"></label></div><div class="col-2"> <label> <span>Location</span> <input type="checkbox" value="0" name="location1" class="checkTwo"> </label> </div> <div class="col-2"> <label> <span>Capacity</span> <input type="checkbox" value="0" name="capacity1" class="checkTwo" > </label> </div> <div class="col-2"> <label> <span>ESP (Pa)</span> <input type="checkbox" value="0" name="esp1" class="checkTwo"> </label> </div> <div class="col-3"> <label> <span>Preffered Brand</span> <input type="checkbox" value="0" name="prefBrand1" class="checkTwo"> </label> </div> </div> <div class="table table-responsive table-bordered"> <table class="table " id="prodTbl1"> <tr> <td class="col-6"> <div class="form-group"> <h6 class="font-weight-bold">Material *</h6><br> <select name="mat_id1[]" id="mat_opt1" class="form-control form-control-sm selectpicker" style="z-index:2" data-live-search="true" data-container="body"> </select> <div class="form-row mt-2 mr-4" > <div class="col-2" class=""> <h6 for="" class="float-right mt-1">Others:</h6> </div> <div class="col-5"> <input type="text" id="input" class="form-control othersInput form-control-sm"> </div> <div class="col-1" class=""> <h6 for="" class="mt-1 float-right">Unit:</h6> </div> <div class="col-4"> <select name="mat_unit1[]" id="" class="form-control form-control-sm"> <option value="">Select Unit</option> <option value="m">m</option> <option value="No">No</option> <option value="Roll">Roll</option> <option value="Item">Item</option><option value="Kg">Kg</option> </select> </div> </div> </div> </td><td class="ref_code1"><div class="form-group"> <h6 class="font-weight-bold">Ref. Code</h6><input type="text" name="ref_code1[]" class="form-control form-control-sm ref_code1"></div></td> <td class="location1"> <div class="form-group"> <h6 class="font-weight-bold" >Location</h6> <input type="text" name="mat_location1[]" class="form-control form-control-sm location_input1"> </div> </td> <td class="capacity1"> <div class="form-group"> <h6 class="font-weight-bold" >Capacity</h6> <input type="text" name="mat_capacity1[]" class="form-control form-control-sm capacity_input1"> </div> </td> <td class="esp1"> <div class="form-group"> <h6 class="font-weight-bold" >ESP (Pa)</h6> <input type="text" name="mat_esp1[]" class="form-control form-control-sm esp_input1"> </div> </td> <td class="prefBrand1"> <div class="form-group"> <h6 class="font-weight-bold" >Preffered Brand</h6> <input type="text" name="mat_pref_brand1[]" class="form-control form-control-sm prefBrand_input1"> </div> </td> <td class=""> <div class="form-group"> <h6 class="font-weight-bold">Qty*</h6> <input type="text" name="mat_qty1[]" class="form-control form-control-sm" > </div> </td> <td></td> </tr> </table> </div> <div align="right"> <button type="button" name="add" id="adBtn1" class="btn btn-success btn-xs">+</button> </div> </div> <div class="form-row"> <div class="col-1"> <input type="text" id="row_val1" class="form-control form-control-sm ml-2"> </div> <div class="col-3"> <button type="button" id="addRow1" class="btn btn-success btn-sm ml-1">+ Rows</button> </div> </div> <table cellspacing="5" class="m-2" id="tablep2"> <thead> <th width="20%">Reference Code</th> <th width="60%">Material Description *</th> <th width="10%">Unit</th> <th width="10%">Quantity *</th> </thead> <tbody> <tr> <td><input type="text" name="ref_code1[]" class="form-control form-control-sm"></td> <td><input type="text" name="mat_id1[]" class="form-control form-control-sm"></td> <td><input type="text" name="mat_unit1[]" class="form-control form-control-sm"></td> <td><input type="text" name="mat_qty1[]" class="form-control form-control-sm"></td> </tr> </tbody> </table></div> <div id="up"></div>';
            $("#up").append(data);
            var mat_id='';
            $.ajax({
            url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id': mat_id},
            success:function(data){
                    $(document).find('#mat_opt1').html(data).change();
                    $("input:checkbox:not(:checked)").each(function() {
                        var column = "table ." + $(this).attr("name");
                        $(column).hide();
                    });
                    $('#mat_opt1').selectpicker('refresh');
                }
            });
            //////////////
            $('input').bind('paste', function (e) {
            var $start = $(this);
            var source
            //check for access to clipboard from window or event
            if (window.clipboardData !== undefined) {
                source = window.clipboardData
            } else {
                source = e.originalEvent.clipboardData;
            }
            var data = source.getData("Text");
            if (data.length > 0) {
                if (data.indexOf("\t") > -1) {
                    var columns = data.split("\n");
                    $.each(columns, function () {
                        var values = this.split("\t");
                        $.each(values, function () {
                            $start.val(this);
                            if ($start.closest('td').next('td').find('input,textarea')[0] != undefined || $start.closest('td').next('td').find('textarea')[0] != undefined) {
                                $start = $start.closest('td').next('td').find('input,textarea');
                            }
                            else
                            {
                                return false;  
                            }
                        });
                        $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
                    });
                    e.preventDefault();
                }
            }
    });
        } else{}
        var k=1; //2nd grp
        $('#addRow1').click(function(){
            var rrow = $('#row_val1').val();
            // alert(rrow);
            if(rrow==0){
                var row_html2='<tr id="irow'+k+'">';
                    row_html2+='<td><input name="ref_code1[]" type="text" class="form-control form-control-sm"></td>';
                    row_html2+='<td><input name="mat_id1[]" type="text" class="form-control form-control-sm" required></td>';
                    row_html2+='<td><input name="mat_unit1[]" type="text" class="form-control form-control-sm"></td>';
                    row_html2+='<td><input name="mat_qty1[]" type="text" class="form-control form-control-sm" required></td>';
                    row_html2 +='<td><button class="btn btn-danger btn-sm rremove" data-row="irow'+k+'" type="button">-</button><td></tr>';
                $('#tablep2').append(row_html2);
                k++;
            }
            if(rrow!=0 || rrow != null){
                for(i=0;i<rrow;i++){
                    var row_html2='<tr id="irow'+k+'">';
                        row_html2+='<td><input name="ref_code1[]" type="text" class="form-control form-control-sm"></td>';
                        row_html2+='<td><input name="mat_id1[]" type="text" class="form-control form-control-sm" required></td>';
                        row_html2+='<td><input name="mat_unit1[]" type="text" class="form-control form-control-sm"></td>';
                        row_html2+='<td><input name="mat_qty1[]" type="text" class="form-control form-control-sm" required></td>';
                        row_html2 +='<td><button class="btn btn-danger btn-sm rremove" data-row="irow'+k+'" type="button">-</button><td></tr>';
                    $('#tablep2').append(row_html2);
                    k++;
                }
            }
            
        });
    });
});
$(document).ready(function(){  // remove 2nd group
    $(document).on('click', '.removeTbl', function(){
        $(this).closest('.ahh').remove();
    });
});
$(document).ready(function(){   // toggle columns, clear items
    $("input:checkbox:not(:checked)").each(function() {
        var column = "table ." + $(this).attr("name");
        $(column).hide();
    });
    $(document).on("click", "input:checkbox",function(){
        var column = "table ." + $(this).attr("name");
        var col_name=$(this).attr("name");
        if(col_name=='ref_code'){
            $('.ref_code_input').val('');
        }
        if(col_name=='ref_code1'){
            $('.ref_code_input1').val('');
        }
        if(col_name=='location'){
            $('.location_input').val('');
        }
        if(col_name=='capacity'){
            $('.capacity_input').val('');
        }
        if(col_name=='esp'){
            $('.esp_input').val('');
        }
        if(col_name=='prefBrand'){
            $('.prefBrand_input').val('');
        }//2nd group
        if(col_name=='location1'){
            $('.location_input1').val('');
        }
        if(col_name=='capacity1'){
            $('.capacity_input1').val('');
        }
        if(col_name=='esp1'){
            $('.esp_input1').val('');
        }
        if(col_name=='prefBrand1'){
            $('.prefBrand_input1').val('');
        }
        $(column).toggle();
    });
});
$(document).ready(function(){   // move to next td paste
    $('input').bind('paste', function (e) {
        var $start = $(this);
        var source
        //check for access to clipboard from window or event
        if (window.clipboardData !== undefined) {
            source = window.clipboardData
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData("Text");
        if (data.length > 0) {
            if (data.indexOf("\t") > -1) {
                var columns = data.split("\n");
                $.each(columns, function () {
                    var values = this.split("\t");
                    $.each(values, function () {
                        $start.val(this);
                        if ($start.closest('td').next('td').find('input,textarea')[0] != undefined || $start.closest('td').next('td').find('textarea')[0] != undefined) {
                        $start = $start.closest('td').next('td').find('input,textarea');
                        }
                        else
                        {
                        return false;  
                        }
                    });
                    $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
                });
                e.preventDefault();
            }
        }
    });
});
$(document).ready(function(){//1st group n 2nd
    var d=1;
    $('#addRow').click(function(){
        var row = $('#row_val').val();
        if(row==0){
            var row_html='<tr id="rrow'+d+'">';
                row_html+='<td><input name="ref_code[]" type="text" class="form-control form-control-sm"></td>';
                row_html+='<td><input name="mat_id[]" type="text" class="form-control form-control-sm" required></td>';
                row_html+='<td><input name="mat_unit[]" type="text" class="form-control form-control-sm"></td>';
                row_html+='<td><input name="mat_qty[]" type="text" class="form-control form-control-sm" required></td>';
                row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button><td></tr>';
            $('#tablep1').append(row_html);
            d++;
        }
        if(row!=0 || row != null){
            for(i=0;i<row;i++){
                var row_html='<tr id="rrow'+d+'">';
                    row_html+='<td><input name="ref_code[]" type="text" class="form-control form-control-sm"></td>';
                    row_html+='<td><input name="mat_id[]" type="text" class="form-control form-control-sm" required></td>';
                    row_html+='<td><input name="mat_unit[]" type="text" class="form-control form-control-sm"></td>';
                    row_html+='<td><input name="mat_qty[]" type="text" class="form-control form-control-sm" required></td>';
                    row_html +='<td><button class="btn btn-danger btn-sm rremove" data-row="rrow'+d+'" type="button">-</button><td></tr>';
                $('#tablep1').append(row_html);
                d++;
            }
        }
    });
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
$(document).ready(function(){ // add other emails
    $(document).on('click', '#addEmail',function() {
        var custom_email=$('#cEmail').val();
        var other_emails =$('#grpOpt1').val();
        var all_email=[];
        if(custom_email.length != 0){
            $('#grpOpt1').append('<option val="'+custom_email+'">'+custom_email+'</option>');
            $("#grpOpt1").selectpicker("refresh");
            all_email.push(custom_email);
            $('#cEmail').val("");
        }
        if(other_emails.length != 0){
            for(i=0;i<other_emails.length;i++){
                all_email.push(other_emails[i]);
            }
        }
        $('#grpOpt1').selectpicker('val',all_email);
        $("#grpOpt1").selectpicker("refresh");
    });
}); 
$(document).ready(function(){
    $(document).on('click', '.sendMail',function() {
        var pmail=$('#pmail').val();
        $('#ccEmail1').selectpicker('val', pmail);
        $('#ccEmail').selectpicker('val', pmail);
        var post_id = $(this).prevAll('input').val();
        var post_name = $(this).nextAll('input').val();
        $('#post_name').val(post_name);
        $('#post_id').val(post_id);
        $('#sendEmailModal').modal('show');
        // show post name
        $.ajax({
            url:'ajax_purchase.php',
            method: 'POST',
            data:{'mpost_id':post_id
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
            $(document).find('#bccGrps').html(data).change();
            $('#bccGrps').selectpicker('refresh');
            }
        });
}); 
$(document).on('click', '#addEmail1',function() {
    var custom_email=$('#otherEmails').val();
    var other_emails =$('#emailGrps').val();
    var all_email=[];
    if(custom_email.length != 0){
        $('#emailGrps').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#emailGrps").selectpicker("refresh");
        all_email.push(custom_email);
        $('#otherEmails').val("");
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            all_email.push(other_emails[i]);
        }
    }
    $('#emailGrps').selectpicker('val',all_email);
    $("#emailGrps").selectpicker("refresh");
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
$(document).on('click', '#addEmail2',function() {
    var custom_email=$('#otherEmails2').val();
    var other_emails =$('#ccEmail').val();
    var all_email=[];
    if(custom_email.length != 0){
        $('#ccEmail').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#ccEmail").selectpicker("refresh");
        all_email.push(custom_email);
        $('#otherEmails2').val("");
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            all_email.push(other_emails[i]);
        }
    }
    $('#ccEmail').selectpicker('val',all_email);
    $("#ccEmail").selectpicker("refresh");
});
$(document).on('click', '#addEmail3',function() {
    var custom_email=$('#otherEmails3').val();
    var other_emails =$('#ccEmail1').val();
    var emails=[];
    if(custom_email.length != 0){
        $('#ccEmail1').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#ccEmail1").selectpicker("refresh");
        $('#otherEmails3').val("");
        emails.push(custom_email);
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            emails.push(other_emails[i]);
        }
    }
    console.log(emails);
    $('#ccEmail1').selectpicker('val',emails);
    $("#ccEmail1").selectpicker("refresh");
});
$(document).on('click', '#addEmail4',function() {
    var custom_email=$('#bccOtherEmail').val();
    var other_emails =$('#bcc_emailGrp').val();
    var all_email=[];
    if(custom_email.length != 0){
        $('#bcc_emailGrp').append('<option val="'+custom_email+'">'+custom_email+'</option>');
        $("#bcc_emailGrp").selectpicker("refresh");
        $('#bccOtherEmail').val("");
        all_email.push(custom_email);
    }
    if(other_emails.length != 0){
        for(i=0;i<other_emails.length;i++){
            all_email.push(other_emails[i]);
        }
    }
    $('#bcc_emailGrp').selectpicker('val',all_email);
    $("#bcc_emailGrp").selectpicker("refresh");
});
$(document).on('click', '#adduser',function() {
    var pmail=$('#pmail').val();
    $('#ccEmail1').selectpicker('val', pmail);
    $('#ccEmail').selectpicker('val', pmail);
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>