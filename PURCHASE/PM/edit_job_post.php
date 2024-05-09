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
    $post_q="SELECT * FROM post WHERE Post_Id='$post_id' AND Post_Status!=0 LIMIT 1";
    // echo $post_q;
    $post_q_run=mysqli_query($connection,$post_q);
    if(mysqli_num_rows($post_q_run)>0){
        $row=mysqli_fetch_assoc($post_q_run);
        $post_name=$row['Post_Name'];
        $post_desc=$row['Post_Desc'];
        $post_type=$row['Post_Type'];
        $project=$row['Prj_Id'];
        echo "<script>
                $(document).ready(function () {
                    $('#post_type').val('$post_type');
                });
             </script>";
        if($post_type=='manpower'){
            echo "<script>
                $(document).ready(function () {
                    $('#adBtnMP').removeClass('d-none');
                    $('#adBtnSB').addClass('d-none');
                });
             </script>";
        }
        elseif($post_type=='subcontractor'){
            echo "<script>
                $(document).ready(function () {
                    $('#adBtnSB').removeClass('d-none');
                    $('#adBtnMP').addClass('d-none');
                });
             </script>";
        }
    }
    else{
        echo 'Error Retrieving Post Details';
    }
}
?>
<input type="hidden" id="prj_id" value="<?php echo $project?>">
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-edit mr-2" aria-hidden="true"></i>Edit Post Details</h5>
        </div>
        <div class="card-body">
            <form action="code.php" method="post">
                <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                <div class="form-group form-row">
                    <div class="col-6">
                        <label for="">Post Name: *</label>
                        <input type="text" value="<?php echo $post_name;?>" name="post_name" minlength="5" maxlength="25"  class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label for="">Post Desc: </label>
                        <input type="text" value="<?php echo $post_desc;?>" name="post_desc" minlength="5" maxlength="25"  class="form-control" >
                    </div>
                </div>
                <div class="form-group form-row">
                    <label for="">Post Type: *</label>
                    <select name="post_type" id="post_type" class="form-control" >
                        <option value="" disabled>Select Type</option>
                        <!-- <option value="manpower">Manpower</option> -->
                        <option value="subcontractor">Subcontractor</option>
                        <option value="manpower">Manpower</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="">Project: *</label>
                    <select name="prj_id" id="prj_opt" class="form-control" > </select>
                </div>
<?php
    $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
    $q_detail_run=mysqli_query($connection,$q_detail);
?>
                <label>Details</label>
                <div class="table table-responsive">
                    <table  class="table table-bordered" id="serviceTbl">
                    <?php $c=0;
                    // manpower post type
                    if(mysqli_num_rows($q_detail_run)>0){
                        while($row_d=mysqli_fetch_assoc($q_detail_run)){
                            $id=$row_d['MP_Post_Id'];
                            $desc=$row_d['MP_Post_Desc'];
                            $qty=$row_d['MP_Post_Qty'];
                            $unit=$row_d['MP_Post_Unit'];
                            if($post_type=='manpower')
                            {
                                ?>
                                <input type="hidden" id="counter" value="<?php echo $c;?>">
                                <tr id='row' class="mp_row">
                                    <td class="col-2">
                                        <div class="form-group">
                                            <input type="hidden" name="mp_post_id[]" value="<?php echo $id;?>">
                                            <label for="">Department *</label><br>
                                            <input type="hidden" value="<?php echo $row_d['Dept_Id'];?>">
                                            <select name="dept_id[]" class="form-control dept_opt" id="dept<?php echo $c;?>" required>
                                                <option value="">Select Deparment</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="col-6">
                                        <div class="form-group">
                                            <label for="">Description *</label><br>
                                            <input name="desc[]" minlength="5" maxlength="35" type="text" class="form-control "value="<?php echo $desc?>" required>
                                        </div>
                                    </td>
                                    <td class="col-2">
                                        <div class="form-group">
                                            <label for="" >Unit *</label><br>
                                            <input type="hidden" value="<?php echo $unit;?>">
                                            <select name="unit_mp[]" id="unit_mp<?php echo $c;?>" class="form-control unitMP_opt" >
                                                <option value="Hr">Hr</option>
                                                <option value="Day">Day</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="col-2 qtyLbl ">
                                        <div class="form-group">
                                            <label for="" >Qty. *</label><br>
                                            <input name="qty[]" type="number" maxlength="20" class="form-control" value="<?php echo $qty?>" required>
                                        </div>
                                    </td>
                                    <td class="col-2">
                                        <form action="code.php" method="post">
                                                <input type="hidden" name="mp_post_del" value="<?php echo $id;?>">
                                                <input type="hidden" name="p_id" value="<?php echo $post_id;?>">
                                            <button type='submit' name='removeMP' data-row='row' class='btn btn-danger btn-xs mt-4'>-</button>
                                        </form>
                                    </td>
                                </tr>
                                    <?php
                                $c++;
                            }
                            elseif($post_type=='subcontractor'){
                                $unit=$row_d['MP_Post_Unit'];
                                ?>
                                <tr id='row' class="sb_row">
                                    <td class="col-3">
                                        <div class="form-group">
                                            <input type="hidden" name="mp_post_id_sb[]" value="<?php echo $id;?>">
                                            <label for="">Department *</label><br>
                                            <input type="hidden" value="<?php echo $row_d['Dept_Id'];?>">
                                            <select name="e_dept_id_sb[]" class="form-control dept_opt" id="dept<?php echo $c;?>" required>
                                                <option value="">Select Deparment</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="col-6">
                                        <div class="form-group">
                                            <label for="">Description *</label><br>
                                            <input name="e_desc_sb[]" minlength="5" maxlength="35" type="text" class="form-control "value="<?php echo $desc?>" required>
                                        </div>
                                    </td>
                                    
                                    <td class="col-2 qtyLbl ">
                                        <div class="form-group">
                                            <label for="" >Unit *</label><br>
                                            <input name="e_unit_sb[]"  maxlength="20" class="form-control" value="<?php echo $unit;?>" required>
                                        </div>
                                    </td>
                                    <td class="col-2 ">
                                        <div class="form-group">
                                            <label for=""  >No.</label><br>
                                            <input name="e_qty_sb[]" type="number" maxlength="20" class="form-control check-fields4" value="<?php echo $qty?>" required>
                                        </div>
                                    </td>
                                    <td class="col-2">
                                        <form action="code.php" method="post">
                                            <input type="hidden" name="mp_post_del" value="<?php echo $id;?>">
                                            <input type="hidden" name="p_id" value="<?php echo $post_id;?>">
                                            <button type='submit' name='removeMP' data-row='row' class='btn btn-danger btn-xs remove mt-4'>-</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                $c++;
                                }
                                
                            }
                        }
                        ?>
                    </table>
                    <div align="right" class="d-none" id="adBtnMP">
                        <button type="button" name="add" id="adBtn" class="btn btn-success btn-xs">+</button>
                    </div>
                    <div align="right" class="d-none" id="adBtnSB">
                        <button type="button" name="add"  class="btn btn-success btn-xs adBtnSB">+</button>
                    </div>
                </div>
                <button type="submit" name="update_post" class="btn btn-success">Save<i class="fa fa-file-text ml-2" aria-hidden="true"></i></button>
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
            var html_code = "<tr id='row"+cnt+"' class='mp_row'>";
            html_code += "<td><select name='a_dept_id[]' id='dept_optt' class='form-control ' required> <option value=''>Select Department</option> </select></td>";
            html_code += "<td><input name='a_desc[]' class='form-control no-border' type='text'></td>";
            html_code += "<td><select name='a_unit_mp[]'  class='form-control'><option value='Hr'>Hr</option><option value='Day'>Day</option></select></td>";
            html_code += "<td><input name='a_qty[]' class='form-control no-border' type='number'></td>";
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
    var cnt = 1;// subon tbl NEW/ ADD
    $('.adBtnSB').click(function(){
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data: {},
        success:function(data){
            var html_code = "<tr id='row"+cnt+"' class='new_sb sb_row'>";
            html_code += "<td><select name='dept_id_sb[]' id='dept_optt' class='form-control ' required> <option value=''>Select Department</option> </select></td>";
            html_code += "<td><input name='desc_sb[]' class='form-control no-border' type='text' required></td>";
            html_code += "<td><select name='unit_sb[]'  class='form-control'> <option value='Sq. F'>Sq. F</option> </select></td>";
            html_code += "<td><input name='qty_sb[]' class='form-control no-border' type='number' required></td>";
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
$(document).ready(function(){
    $(document).on('change', '#post_type', function(){
        var type = $(this).val();
        if(type=='manpower'){
            $('#adBtnMP').removeClass('d-none');
            $('#adBtnSB').addClass('d-none');
            $('.mp_row').removeClass('d-none');
            $('.sb_row').addClass('d-none');
        }
        if(type=='subcontractor'){
            $('#adBtnSB').removeClass('d-none');
            $('#adBtnMP').addClass('d-none');
            $('.sb_row').removeClass('d-none');
            $('.mp_row').addClass('d-none');
        }
    });   
}); 
$(document).ready(function () {
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
$(document).ready(function () {
    $.ajax({
    url:'../../PMS/P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $(document).find('.dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
            $('.dept_opt').each( function () {
                var dept_opt_id = $(this).prop('id');
                dept_opt_id='#'+dept_opt_id;
                var dept_id =($(this).prev('input').val());
                $(dept_opt_id).val(dept_id).change();
            });
        }
    });
});
$(document).ready(function () {
    $('.unitMP_opt').each( function () {
        var unit_opt_id = $(this).prop('id');
        unit_opt_id='#'+unit_opt_id;
        var unit_val =($(this).prev('input').val());
        $(unit_opt_id).val(unit_val).change();
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>