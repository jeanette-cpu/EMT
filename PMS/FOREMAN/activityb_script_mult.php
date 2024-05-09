<style>
    .selectdiv{
        z-index: 1000 !important;
        position: absolute;
        width: 70%;
    }
</style>
<script>
//VILLA POPULATING OPTIONS
$(document).ready(function () {
    // populate villa options -villa
    var prj_id = $('#prj_id').val();
    $.ajax({
        url:'../P_ADMIN/ajax_villa.php',
        method:'POST',
        data: {'prj_id':prj_id},
        success:function(data){
            $('#villa_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    // populate plex options -villa
    $(document).on('change','#villa_opt', function(){
        var villa_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_plex.php',
            method: 'POST',
            data:{'villa_id':villa_id},
            success:function(data){
                $('#plex_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // populate building options -villa
    $(document).on('change','#plex_opt', function(){
        var plx_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_blg.php',
            method: 'POST',
            data:{'plex_id_f':plx_id},
            success:function(data){
                $('#flt_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // populate level options -villa
    $(document).on('change','#blg_opt', function(){
        var blg_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_blg.php',
            method: 'POST',
            data:{'blg_id':blg_id},
            success:function(data){
                $('#lvl_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // populate flat options -villa
    $(document).on('change','#lvl_opt', function(){
        var lvl_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_flat.php',
            method: 'POST',
            data:{'lvl_id':lvl_id},
            success:function(data){
                $('#flt_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
//BUILDING POPULATING OPTIONS
$(document).ready(function () {
    // populate building options - building
    var prj_id = $('#prj_id').val();
    $.ajax({
        url:'../P_ADMIN/ajax_blg.php',
        method:'POST',
        data: {'lvl_prj_id':prj_id},
        success:function(data){
            $('#bblg_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $(document).on('change','#bblg_opt', function(){
        // populate level options - building
        var blg_id = $(this).val();
        $.ajax({
            url: '../P_ADMIN/ajax_blg.php',
            method: 'POST',
            data:{'blg_id':blg_id},
            success:function(data){
                $('#blvl_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    $(document).on('change','#blvl_opt', function(){
        // populate flat options - building
        var lvl_id = $(this).val();
        // console.log(lvl_id);
        $.ajax({
            url: '../P_ADMIN/ajax_flat.php',
            method: 'POST',
            data:{'lvl_id':lvl_id},
            success:function(data){
                $(document).find('#bflt_opt').html(data).change();
                // console.log(data);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
// ADD MODAL (daily activity) POPULATING OPTIONS
$(document).ready(function () {
    // populating department options
    $.ajax({
        url:'../P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#dept').html(data).change();
        }
    });
    // populating employee options
    $.ajax({
        url:'../P_ADMIN/ajax_emp.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#employee').html(data).change();
            $('#manpower').html(data).change();
            $('.selectpicker').selectpicker('refresh');         
        }
    });
    // populating activity category options
        var dept_id = $('#dept_id').val();
        $.ajax({
            url:'../P_ADMIN/ajax_act_cat.php',
            method:'POST',
            data: {'dept_id':dept_id},
            success:function(data){
                $('#category').html(data).change();
            }
        });
    // populating activities assigned options
    $(document).on('change', '#category', function() {
        var cat_id = $(this).val();
        var flt_id = document.getElementById("flat_id").value;
        $.ajax({
            url:'ajax_act_cat_mult.php',
            method:'POST',
            data: {'act_cat':cat_id,
                    'flt_id': flt_id
            },
            success:function(data){
                // console.log(data);
                $('#activity').html(data).change();
                $('.selectpicker').selectpicker('refresh');         
            }
        });
    });
});
// ADD MODAL add row employee options DE
$(document).ready(function(){
    var count = 1;
    $('#addBtn').click(function(){
        count ++
        $.ajax({
            url:'../P_ADMIN/ajax_emp.php',
            method: 'POST',
            data:{},
            success:function(data){
                var html_code = "<tr id='row"+count+"'>";
                html_code +="<td><div class='selectdiv '><select name='employee[]' id='emps' class='form-control selectpicker' data-live-search='true' required></select></div></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#materialTbl').append(html_code);
                $(document).find('#row'+count+' #emps').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
// new act row
$(document).ready(function(){
    var count = 1;
    $('#newAct').click(function(){
        var cat_id = $('#category').val();
        var flt_id = document.getElementById("flat_id").value;
        count ++;
        $.ajax({
            url:'ajax_act_cat_mult.php',
            method: 'POST',
            data:{'act_cat':cat_id,
                    'flt_id': flt_id},
            success:function(data){
                var html_code = "<tr id='rowa"+count+"'>";
                html_code +="<td><div class='selectdiv '><select name='activity_id[]' id='act_opt' class='form-control selectpicker' required></select></div></td>";
                html_code += "<td><button type='button' name='remove1' data-row='rowa"+count+"' class='btn btn-danger btn-xs remove1'>-</button></td>";
                html_code += "</tr>";
                $('#actTbl').append(html_code);
                $(document).find('#rowa'+count+' #act_opt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
// delete row
$(document).on('click', '.remove1', function(){
    var delete_row1 = $(this).data("row");
    $('#' + delete_row1).remove();
});
// delete row
$(document).on('click', '.remove', function(){
    var delete_row = $(this).data("row");
    $('#' + delete_row).remove();
});
//EDIT MODAL PROGRESS
$('.editBtn').click(function(){
    $('#editAct').modal('show');
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
        $('#DE_Pct').val(data[10]);
        $('#Easgd_act_id').val(data[1]); 
        $('#DE_Id').val(data[0]);
});
// ADD MODAL MANPOWER indv
$('.addEmp').click(function(){
    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
    $('#DE_Idm').val(data[0]);
    $('#addWorkertoAll').addClass('d-none');
    $('#act_select').addClass('d-none');
    $('#addWorker').removeClass('d-none');
    $('#addManpower').modal('show');
    var cnt = 1;
    $('#addBtnM').click(function(){    
    cnt ++
        $.ajax({
            url:'../P_ADMIN/ajax_emp.php',
            method: 'POST',
            data:{},
            success:function(data){
                var html_code = "<tr id='row"+cnt+"'>";
                html_code +="<td><select name='employee[]' id='emp' class='form-control selectpicker' data-live-search='true' required><option value=''>Select Employee</option></select></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#manpowerTbl').append(html_code);
                $(document).find('#row'+cnt+' #emp').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // delete row
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });
});
// ADD MODAL MANPOWER all
$('.addEmp2').click(function(){
    var de_ids = $('#de_ids').val();
    $('#DE_Idm2').val(de_ids);
    $('#addManpower').modal('show');
    $('#addWorker').addClass('d-none');
    $('#act_select').removeClass('d-none');
    $('#addWorkertoAll').removeClass('d-none');

    var cnt = 1;
    $('#addBtnM').click(function(){    
    cnt ++
        $.ajax({
            url:'../P_ADMIN/ajax_emp.php',
            method: 'POST',
            data:{},
            success:function(data){
                var html_code = "<tr id='row"+cnt+"'>";
                html_code +="<td><select name='employee[]' id='emp' class='form-control selectpicker' data-live-search='true' required><option value=''>Select Employee</option></select></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+cnt+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#manpowerTbl').append(html_code);
                $(document).find('#row'+cnt+' #emp').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    // delete row
    $(document).on('click', '.remove', function(){
        var delete_row = $(this).data("row");
        $('#' + delete_row).remove();
    });
});
// DELETE DE
$(document).ready(function () {
    var flt_id = document.getElementById("flat_id").value;
    var els=document.getElementsByClassName("del_flat");
        for (var i=0;i<els.length;i++) {
            els[i].value = flt_id}
});
// view employee assigned
$(document).ready(function(){ 
     $('.manageBtn').click(function(){
        var td = $(this).closest('td');
        var DE_Id = td.find('#DE_Id_mng_emp').attr("value")
        let flat_id = $('#flt_id').attr("value")
        var plx_id = $("#plx_id").attr("value");
        // alert(plx_id);
        var prj_id =  td.find('#project_id_mng_emp').attr("value")
        var act_name = td.find('#act_name').attr("value")
        var date = $('#date').val();
        $.ajax({
            type:'POST',
            url: 'ajax_emp_manage.php',
            data:{
                'De_Id' : DE_Id,
                'flt_id' : flat_id,
                'prj_id' : prj_id,
                'act_name' : act_name,
                'Date' : date,
                'plx_id': plx_id,
            },
            success: function(data){
                $('#manage_emp').html(data);
                $('#EmployeeAsgn').modal("show");
                
            }
        });
     });
});
$(document).ready(function(){ 
    $('#addManpower').on('hide', function() {
        location.reload();
    });
    $('#editAct').on('hide', function() {
        location.reload();
    });
    $('#EmployeeAsgn').on('hide', function() {
        location.reload();
    });
});
//data table
$(document).ready(function() {
    $('#dataTable').DataTable({
        pageLength: 10,
        filter: true,
        "searching": true,
    });
});
$(document).ready(function(){
    $(document).on("click", ".minBtn", function() {
        var $input = $(this).parent().find('input');
        // console.log($input.attr('id'));
        var n_val = $input.val();
        n_val --;
        $input.val(n_val);
    });

    $(document).on("click", ".addBtn", function() {
        var $input = $(this).parent().find('input');
        // console.log($input.attr('id'));
        var n_val = $input.val();
        n_val ++;
        $input.val(n_val);
    });
});
// ADD Manpower options row
$(document).ready(function(){
    var count = 1;
    $('#addMpRow').click(function(){
        count ++;var mp = '';
        $.ajax({
            url:'../P_ADMIN/ajax_mp.php',
            method: 'POST',
            data:{'mp':mp},
            success:function(data){
                var html_code = "<tr id='row"+count+"'>";
                html_code +="<td><div class=' '><select name='manpower[]' id='mpOpt' class='form-control selectpicker' data-live-search='true' ></select></div></td>";
                html_code +="<td><div class='form-row block'><button type='button'class='btn btn-xs btn-outline-danger minBtn' id='minMP'>-</button><div class='col-4'><input type='text' name='mp_qty[]' class='form-control' id='mpVal'></div><button type='button' class='btn btn-xs btn-outline-success addBtn' >+</button></div></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#ManpowerTbl').append(html_code);
                $(document).find('#row'+count+' #mpOpt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
$(document).ready(function(){
    var count = 1;
    $('#addMpRow1').click(function(){
        count ++;var mp = '';
        $.ajax({
            url:'../P_ADMIN/ajax_mp.php',
            method: 'POST',
            data:{'mp':mp},
            success:function(data){
                var html_code = "<tr id='row"+count+"'>";
                html_code +="<td><div class=' '><select name='manpower[]' id='mpOpt' class='form-control selectpicker' data-live-search='true' ></select></div></td>";
                html_code +="<td><div class='form-row block'><button type='button'class='btn btn-xs btn-outline-danger minBtn' id='minMP'>-</button><div class='col-4'><input type='text' name='mp_qty[]' class='form-control' id='mpVal'></div><button type='button' class='btn btn-xs btn-outline-success addBtn' >+</button></div></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+count+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#ManpowerTbl1').append(html_code);
                $(document).find('#row'+count+' #mpOpt').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
// ADD Subcon options row
$(document).ready(function(){
    var countsb = 1;
    $('#addSbRow').click(function(){
        countsb ++; var sb='';
        $.ajax({
            url:'../P_ADMIN/ajax_mp.php',
            method: 'POST',
            data:{'sb':sb},
            success:function(data){
                var html_code = "<tr id='row"+countsb+"'>";
                html_code +="<td><div class=' '><select name='subcontractor[]' id='sbOpts' class='form-control selectpicker' data-live-search='true'></select></div></td>";
                html_code +="<td><div class='form-row block'><button type='button' class='btn btn-xs btn-outline-danger minBtn'>-</button><div class='col-4'><input type='text' class='form-control' name='sb_qty[]' id='mpVal'></div><button type='button' class='btn btn-xs btn-outline-success addBtn' >+</button></div></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+countsb+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#SubConTbl').append(html_code);
                $(document).find('#row'+countsb+' #sbOpts').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
$(document).ready(function(){
    var countsb = 1;
    $('#addSbRow1').click(function(){
        countsb ++; var sb='';
        $.ajax({
            url:'../P_ADMIN/ajax_mp.php',
            method: 'POST',
            data:{'sb':sb},
            success:function(data){
                var html_code = "<tr id='row"+countsb+"'>";
                html_code +="<td><div class=' '><select name='subcontractor[]' id='sbOpts' class='form-control selectpicker' data-live-search='true'></select></div></td>";
                html_code +="<td><div class='form-row block'><button type='button' class='btn btn-xs btn-outline-danger minBtn'>-</button><div class='col-4'><input type='text' class='form-control' name='sb_qty[]' id='mpVal'></div><button type='button' class='btn btn-xs btn-outline-success addBtn' >+</button></div></td>";
                html_code += "<td><button type='button' name='remove' data-row='row"+countsb+"' class='btn btn-danger btn-xs remove'>-</button></td>";
                html_code += "</tr>";
                $('#SubConTbl1').append(html_code);
                $(document).find('#row'+countsb+' #sbOpts').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});
$(document).ready(function(){
    // populating manpower options
    var m ='';
    $.ajax({
        url:'../P_ADMIN/ajax_mp.php',
        method: 'POST',
        data:{'mp':m},
        success:function(data){
            $('#manpowerOpt').html(data).change();
            $('#manpowerOpt1').html(data).change();
            $('.selectpicker').selectpicker('refresh');         
        }
    });
    var sb ='';
    $.ajax({
        url:'../P_ADMIN/ajax_mp.php',
        method: 'POST',
        data:{'sb':sb},
        success:function(data){
            $('#sbOpt').html(data).change();
            $('#sbOpt1').html(data).change();
            $('.selectpicker').selectpicker('refresh');         
        }
    }); 
});
$(document).ready(function(){
    $(document).on('click', '.record', function() {
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       var asgd_id = data[1];
       $.ajax({
            url: '../P_ADMIN/ajax_act_record.php',
            method: 'POST',
            data:{'asgd_id':asgd_id},
            success:function(data){
                $('#history').html(data).change();
            }
        });
        $('#editProg').modal('show');
    });
});

</script>