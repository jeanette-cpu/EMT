<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
if(isset($_POST['edit_post'])||isset($_GET['post_id'])){
    if(isset($_POST['edit_post'])){
        $post_id=$_POST['post_id']; 
    }
    else{
        $post_id=$_GET['post_id']; 
    }
     // post details
    $post_q="SELECT * FROM post WHERE Post_Id='$post_id' LIMIT 1";
    $post_q_run=mysqli_query($connection,$post_q);
    if(mysqli_num_rows($post_q_run)>0){
         $row=mysqli_fetch_assoc($post_q_run);
         $post_name=$row['Post_Name'];
         $post_desc=$row['Post_Desc'];
         $post_type=$row['Post_Type'];
         $project=$row['Prj_Id'];
    }
    else{
        echo 'Error Retrieving Post Details';
    }
}
?>
<style>
    div.dropdown-menu.open { width: 100%; } ul.dropdown-menu.inner>li>a { white-space: initial; }
</style>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-edit mr-2" aria-hidden="true"></i>Edit Material Post Details</h5>
        </div>
        <form action="code.php" method="post">
        <div class="card-body">
            <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
            <div class="form-group form-row">
                <div class="col-6">
                    <label for="" class="font-weight-bold">Post Name: *</label>
                    <input type="text" value="<?php echo $post_name;?>" name="post_name" minlength="5" maxlength="25"  class="form-control" required>
                </div>
                <div class="col-6">
                    <label for="" class="font-weight-bold">Post Desc: </label>
                    <input type="text" value="<?php echo $post_desc;?>" name="post_desc" minlength="5" maxlength="25"  class="form-control" >
                </div>
            </div>
            <input type="hidden" id="prj_id" class="form-control" value="<?php echo $project?>">
            <div class="form-group ">
                <label for=""  class="font-weight-bold">Project: *</label>
                <select name="prj_id" id="prj_opt" class="form-control" > </select>
            </div>
            <h5  class="font-weight-bold">Details:</h5>
<?php //GROUP DETAILS
    $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";
    $q_grp_run=mysqli_query($connection,$q_grp);
    if(mysqli_num_rows($q_grp_run)>0){
        while($row_g=mysqli_fetch_assoc($q_grp_run)){
            $grp_id=$row_g['MP_Grp_Id'];
            $grp_name=$row_g['MP_Grp_Name'];
            $grp_loc=$row_g['MP_Grp_Location'];
            ?>
            <div class="form-row mb-2" >
                <div class="col-6">
                    <label for="" class="font-weight-bold">Group Name:</label>
                    <input type="hidden" name="grp_id[]" class="form-control" value="<?php echo $grp_id;?>">
                    <input type="text" name="grp_name[]" class="form-control" value="<?php echo $grp_name;?>">
                </div>
                <div class="col-4">
                    <label for="" class="font-weight-bold">Group Location:</label>
                    <input type="text" name="grp_loc[]" class="form-control" value="<?php echo $grp_loc;?>">
                </div>
                <div class="col-2 mt-4 pt-1" align="right">
                    <form action="code.php" method="post">
                        <input type="hidden" name="post_id" value=<?php echo $post_id;?>>
                        <input type="hidden" name="del_grp_id" value="<?php echo $grp_id?>">
                        <button class="btn btn-outline-primary btn-sm" type="submit" name="del_grp">
                            Delete Group<i class="fas fa-trash ml-1"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php
             $q_detail="SELECT * FROM material_post WHERE Mat_Post_Status=1 AND Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
             $q_detail_run=mysqli_query($connection,$q_detail); $c=0;
            if(mysqli_num_rows($q_detail_run)>0){
            ?>
            <div class="table table-responsive mb-2" style="background-color: #f7f9fd; " >
                    <table  class="table table-bordered" id="mat_details<?php echo $grp_id;?>">
                        <thead>
                            <th width="40%">Material *</th>
                            <th>Ref. Code</th>
                            <th>Location</th>
                            <th>Capacity</th>
                            <th>ESP (Pa)</th>
                            <th>Preffer Brand</th>
                            <th>Qty *</th>
                            <th></th>
                        </thead>
                        <tbody>
                <?php
                while($row_d=mysqli_fetch_assoc($q_detail_run)){
                    $mat_post_id=$row_d['Mat_Post_Id'];
                    $mat_id=$row_d['Mat_Id'];
                    $ref_code=$row_d['Mat_Post_Ref_Code'];
                    $qty=$row_d['Mat_Post_Qty'];
                    $pref_brand=$row_d['Mat_Post_Brand'];
                    $mat_location=$row_d['Mat_Post_Location'];
                    $mat_capacity=$row_d['Mat_Post_Capacity'];
                    $mat_esp=$row_d['Mat_Post_Esp'];
                    $q_chk_mat="SELECT * FROM material WHERE Mat_Id='$mat_id'"; // check if mat id
                    $q_chk_mat_run=mysqli_query($connection,$q_chk_mat);
                    if(mysqli_num_rows($q_chk_mat_run)>0){
                        $row_m=mysqli_fetch_assoc($q_chk_mat_run);
                        $mat_desc=''; $mat_unit='';
                    }
                    else{
                        $mat_desc=$row_d['Mat_Id'];
                        $mat_unit=$row_d['Mat_Post_Unit'];
                    }
                ?>
                        <tr id='row'>
                            <td class="">
                                <div class="form-group">
                                    <input type="hidden" name="mat_post_id[]" value="<?php echo $mat_post_id;?>">
                                    <input type="hidden" id="v<?php echo $c?>" value="<?php echo $mat_id;?>">
                                    <select name="e_mat_id[]" class="form-control form-control-sm mat_id_opt" style="z-index:2" id="mat<?php echo $mat_post_id;?>" data-container='body' data-live-search="true" required>
                                        <option value="">Select Material</option>
                                    </select>
                                    <div class="form-row">
                                        <div class="col-8">
                                            <label for="">Others:</label>
                                            <input type="text" class="form-control form-control-sm othersInput" id="<?php echo $c?>" value="<?php echo $mat_desc;?>">
                                        </div>
                                        <div class="col-4">
                                            <label for="">Unit:</label>
                                            <input type="hidden" id="unitVal<?php echo $mat_post_id;?>" value="<?php echo $mat_unit;?>">
                                            <select name="e_mat_unit[]" id="unit<?php echo $mat_post_id;?>" class="form-control form-control-sm" value=<?php echo $mat_unit;?>>
                                                <option value="">Select Unit</option>
                                                <option value="m">m</option>
                                                <option value="No">No</option>
                                                <option value="Roll">Roll</option>
                                                <option value="Item">Item</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="e_ref_code[]" class="form-control form-control-sm" value="<?php echo $ref_code;?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="e_loc[]" class="form-control form-control-sm" value="<?php echo $mat_location;?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="e_cap[]" class="form-control form-control-sm" value="<?php echo $mat_capacity;?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" name="e_esp[]" class="form-control form-control-sm" value="<?php echo $mat_esp;?>">
                                </div>
                            </td>
                            <td class="">
                                <div class="form-group">
                                    <input name="e_pref_b[]" type="text" maxlength="50" class="form-control form-control-sm" value="<?php echo $pref_brand?>">
                                </div>
                            </td>
                            <td class="">
                                <div class="form-group">
                                    <input name="e_mat_qty[]" type="number" class="form-control form-control-sm"value="<?php echo $qty?>" required>
                                </div>
                            </td>
                            <td class="">
                                <form action="code.php" method="post">
                                        <input type="hidden" name="mat_post_del" value="<?php echo $mat_post_id;?>">
                                        <input type="hidden" name="p_id" value="<?php echo $post_id;?>">
                                    <button type='submit' name='del_mat_post_detail' data-row='row' class='btn btn-danger btn-xs mt-4'>-</button>
                                </form>
                            </td>
                        </tr>
                            <?php
                        $c++; $mat_desc=''; $mat_unit='';
                    }
                    ?>
                        </tbody>
                    </table>
                    </div>
                    <div align="right">
                        <input type="hidden" name="groupId" class="gId" value="<?php echo $grp_id;?>">
                        <button type="button" name="add" id="" class="btn btn-success btn-xs newRow">+</button>
                    </div>
                    <?php
                }
        }
        ?>
            <h5 class="mt-1 ml-2" > 
                <label>Group </label>
                <a href="#" class="btn btn-success btn-circle" data-toggle="modal" data-target="#adduser">
                    <i class="fas fa-plus"></i>
                </a>
            </h5>
            <button type="submit" name="update_mat_post" class="btn btn-success float-right mb-3">Save<i class="fa fa-file-text ml-2" aria-hidden="true"></i></button>
        </form>
        <?php
    }
?>
               
        </div>
    </div>
</div>
<!-- Modal Add Additional Group -->
<div class="modal fade bd-example-modal-lg" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fa fa-archive mr-2" aria-hidden="true"></i>Additional Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <form action="code.php" method="POST">
      <div class="modal-body">
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
                        <div class="col-3">
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
                                        <select name="mat_id[]" id="mat_opt" class="form-control form-control-sm selectpicker" style="z-index:2" data-live-search="true" data-container='body' required>
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
                                                    <option value="Item">Item</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="ref_code">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Ref. Code</h6>
                                            <input type="text" name="mat_ref_code[]" class="form-control form-control-sm ref_code_input">
                                    </div>
                                </th>
                                <td class="location">
                                    <div class="form-group">
                                            <h6 class="font-weight-bold" >Location</h6>
                                            <input type="text" name="mat_location[]" class="form-control form-control-sm location_input">
                                    </div>
                                </th>
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
                                        <input type="text" name="mat_qty[]" class="form-control form-control-sm" required>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        </table>
                        </div>
                        <div align="right">
                            <button type="button" name="addGroup" id="adBtn" class="btn btn-success btn-xs">+</button>
                        </div>
                    </div>
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
        <div class="modal-footer">
            <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="add_mat_grp" class="btn btn-success "><i class="fa fa-check" aria-hidden="true"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal ADD Group -->
<script>
$(document).ready(function () { // project selection selected project
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('#prj_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        var prj_id = $('#prj_id').val();
        $('#prj_opt').val(prj_id).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).ready(function(){// material list optons
    var mat_id='';
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
    method: 'POST',
    data:{'mat_id': mat_id},
    success:function(data){
        $(document).find('.mat_id_opt').html(data).change();
            $('.mat_id_opt').each( function () {
                var mat_opt = $(this).prop('id');
                mat_opt_id='#'+mat_opt;
                var mat_id =$(this).prev('input').val(); 
                if(isNaN(mat_id)){ // custom input lagay sa options
                    var select_id =mat_opt_id.slice(4); // other id
                    $("#mat"+select_id).append('<option value="'+mat_id+'">'+mat_id+'</option>');
                    $('.selectpicker').selectpicker('refresh');
                    $("#mat"+select_id).val(mat_id);
                    $("#mat"+select_id).selectpicker({
                        liveSearch: true
                    });
                    $('.selectpicker').selectpicker('refresh');
                    var unit = $("#unitVal"+select_id).val();// unit
                    $("#unit"+select_id).val(unit);// select id
                }
                else{
                   
                    $(mat_opt_id).val(mat_id).change();
                    $(mat_opt_id).addClass('selectpicker');
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        }
    });
});
$(document).ready(function(){// additional columns
    var cnt = 1;
    $('.newRow').click(function(){
        var grp_id=$(this).prev("input").val();
        $.ajax({
            url:'../../PMS/P_ADMIN/ajax_dept.php',
            method: 'POST',
            data: {},
            success:function(data){
            var html_code = "<tr id='r"+cnt+"'>";
                html_code += "<td><select name='add_mat_id[]' id='m"+cnt+"' class='form-control form-control-sm m_opt no-border selectpicker ' data-live-search='true' data-container='body' style='z-index:2' required></select><br>";
                html_code +='<div class="form-row"> <div class="col-8"> <label>Others:</label><input type="text" class="form-control form-control-sm othersInput"></div><div class="col-4"> <label>Unit:</label><select class="form-control form-control-sm" name="a_mat_unit[]"> <option value="">Select Unit</option> <option value="m">m</option> <option value="No">No</option> <option value="Roll">Roll</option> <option value="Item">Item</option> </select> </div></div></td>';
                html_code += "<td><input name='a_mat_ref_code[]' class='form-control form-control-sm'  type='text'></td>";
                html_code += "<td><input name='a_mat_loc[]' class='form-control form-control-sm'  type='text'></td>";
                html_code += "<td><input name='a_mat_cap[]' class='form-control form-control-sm'  type='text'></td>";
                html_code += "<td><input name='a_mat_esp[]' class='form-control form-control-sm'  type='decimal'></td>";
                html_code += "<td><input name='a_mat_pref_brand[]' class='form-control form-control-sm'  type='text'></td>";
                html_code += "<td><input name='a_mat_qty[]' class='form-control form-control-sm' required> ";
                html_code += "<input name='a_mat_grp_id[]' class='form-control form-control-sm' type='hidden' value='"+grp_id+"'required>  </td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
            $('#mat_details'+grp_id).append(html_code);
            $('.selectpicker').selectpicker('refresh');
            var mat_id='#m'+cnt;
            $.ajax({
            url:'../../PMS/P_ADMIN/ajax_mat_opt.php',
            method: 'POST',
            data:{'mat_id': mat_id},
            success:function(data){
                $(document).find(mat_id).html(data).change();
                    $('.selectpicker').selectpicker('refresh');
                    }
                });
                cnt++; 
            }
        });
    });  
});
$(document).ready(function () {//custom input other material
    $(document).on('input','.othersInput', function(){
        var customInput = $(this).val();
        var select_id= $(this).closest('td').find('.selectpicker').attr('id');
        $("#"+select_id).append('<option value="'+customInput+'">'+customInput+'</option>');
        $('.selectpicker').selectpicker('refresh');
        $("#"+select_id).val(customInput);
        $('.selectpicker').selectpicker('refresh');
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
        $('.selectpicker').selectpicker('refresh');
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
            html_code +="<div class='form-row mt-2 mr-4'> <div class='col-2'  class=''> <h6 for=''  class='float-right mt-1'>Others:</h6>  </div> <div class='col-5'> <input type='text' id='input' class='form-control othersInput form-control-sm'></div> <div class='col-1'><h6 for='' class='mt-1 float-right'>Unit:</h6></div><div class='col-4'><select name='mat_unit[]' id='' class='form-control form-control-sm'> <option value=''>Select Unit</option>  <option value='m'>m</option> <option value='No'>No</option> <option value='Roll'>Roll</option> <option value='Item'>Item</option> </select></div></div></td>";
            html_code += "<td class='ref_code'><input name='mat_ref_code[]' class='form-control ref_code_input form-control-sm'  type='text'></td>";
            html_code += "<td class='location'><input name='mat_location[]' class='form-control location_input form-control-sm'  type='text'></td>";
            html_code += "<td class='capacity'><input name='mat_capacity[]' class='form-control capacity_input form-control-sm'  type='text'></td>";
            html_code += "<td class='esp'><input name='mat_esp[]' class='form-control esp_input form-control-sm' type='decimal'></td>";
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
            html_code1 +="<div class='form-row mt-2 mr-4'> <div class='col-2'  class=''> <h6 for=''  class='float-right mt-1'>Others:</h6>  </div> <div class='col-5'> <input type='text' id='input' class='form-control othersInput form-control-sm'></div> <div class='col-1'><h6 for='' class='mt-1 float-right'>Unit:</h6></div><div class='col-4'><select name='mat_unit1[]' id='' class='form-control form-control-sm'> <option value=''>Select Unit</option>  <option value='m'>m</option> <option value='No'>No</option> <option value='Roll'>Roll</option> <option value='Item'>Item</option> </select></div></div></td>";
            html_code1 += "<td class='ref_code1'><input name='mat_ref_code1[]' class='form-control ref_code_input1 form-control-sm'  type='text'></td>";
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
$(document).ready(function(){  //addional group append
    $(document).on('click', '#grpBtn', function(){
        var visible_no=$(".ahh:visible").length;
        if(visible_no==0){
            // var data = $("#cardGrp" ).html();
            var data = '<div class="card ahh mt-3" style="background-color: #f7f9fd;"> <div class="card-body"> <div> <a href="#" class="btn btn-danger btn-sm btn-circle removeTbl d-flex float-right" ><i class="fas fa-times"></i></a> </div> <div class="form-row mb-2"> <div class="col-6"> <h6 for="" class="font-weight-bold">Group Name: *</h6> <input type="text" name="grpName1" class="form-control form-control-sm" required> </div> <div class="col-6"> <h6 for="" class="font-weight-bold ">Location:</h6> <input type="text" name="grpLocation1" class="form-control form-control-sm"> </div> </div> <div class="form-row mt-3"><div class="col-2"> <label> <span>Ref. Code</span><input type="checkbox" class="ml-1" value="0" name="ref_code1" class="checkTwo"></label></div> <div class="col-2"> <label> <span>Location</span> <input type="checkbox" value="0" name="location1" class="checkTwo"> </label> </div> <div class="col-2"> <label> <span>Capacity</span> <input type="checkbox" value="0" name="capacity1" class="checkTwo" > </label> </div> <div class="col-2"> <label> <span>ESP (Pa)</span> <input type="checkbox" value="0" name="esp1" class="checkTwo"> </label> </div> <div class="col-3"> <label> <span>Preffered Brand</span> <input type="checkbox" value="0" name="prefBrand1" class="checkTwo"> </label> </div> </div> <div class="table table-responsive table-bordered"> <table class="table " id="prodTbl1"> <tr> <td class="col-6"> <div class="form-group"> <h6 class="font-weight-bold">Material *</h6><br> <select name="mat_id1[]" id="mat_opt1" class="form-control form-control-sm selectpicker" style="z-index:2" data-live-search="true" data-container="body" required> </select> <div class="form-row mt-2 mr-4" > <div class="col-2" class=""> <h6 for="" class="float-right mt-1">Others:</h6> </div> <div class="col-5"> <input type="text" id="input" class="form-control othersInput form-control-sm"> </div> <div class="col-1" class=""> <h6 for="" class="mt-1 float-right">Unit:</h6> </div> <div class="col-4"> <select name="mat_unit1[]" id="" class="form-control form-control-sm"> <option value="">Select Unit</option> <option value="m">m</option> <option value="No">No</option> <option value="Roll">Roll</option> <option value="Item">Item</option> </select> </div> </div> </div> </td><td class="ref_code1"><div class="form-group"> <h6 class="font-weight-bold">Ref. Code</h6><input type="text" name="mat_ref_code1[]" class="form-control form-control-sm ref_code1"></div></td> <td class="location1"> <div class="form-group"> <h6 class="font-weight-bold" >Location</h6> <input type="text" name="mat_location1[]" class="form-control form-control-sm location_input1"> </div> </td> <td class="capacity1"> <div class="form-group"> <h6 class="font-weight-bold" >Capacity</h6> <input type="text" name="mat_capacity1[]" class="form-control form-control-sm capacity_input1"> </div> </td> <td class="esp1"> <div class="form-group"> <h6 class="font-weight-bold" >ESP (Pa)</h6> <input type="text" name="mat_esp1[]" class="form-control form-control-sm esp_input1"> </div> </td> <td class="prefBrand1"> <div class="form-group"> <h6 class="font-weight-bold" >Preffered Brand</h6> <input type="text" name="mat_pref_brand1[]" class="form-control form-control-sm prefBrand_input1"> </div> </td> <td class=""> <div class="form-group"> <h6 class="font-weight-bold">Qty*</h6> <input type="text" name="mat_qty1[]" class="form-control form-control-sm" required> </div> </td> <td></td> </tr> </table> </div> <div align="right"> <button type="button" name="add" id="adBtn1" class="btn btn-success btn-xs">+</button> </div> </div> </div> <div id="up"></div>';
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
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        } else{}
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
        $(document).find(column).toggle();
        // $(column).toggle();
    });
});
$(document).ready(function(){ // remove row table
    $(document).on('click', '.remove', function(){
        $(this).closest("tr").remove();
    });   
}); 
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>