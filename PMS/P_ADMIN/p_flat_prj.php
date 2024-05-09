<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>

<div class="container-fluid">
    <div class="card shadow mb-4 col-md-11">
        <div class="card-header py-3">
            <h5 class="font-weight-bold text-primary"><i class="fa fa-cube mr-2" aria-hidden="true"></i>Manage Flat</h5>
        </div>
        <label>Project</label>
            <select name="prj_id" id="flt_project" class="mb-3 form-control selectpicker lvl_project" data-live-search="true" required>
                <option value="">Select Project</option>
            </select>
        <!-- Building Type -->
        <div id="blg_div" class="mt-3 mb-3 d-none">
            <div class="row">
                <div class="col-6">
                    <label>Building</label>
                    <select name="" id="flt_blg" class="form-control selectpicker" data-live-search="true" required>
                        <option>Select Building</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Level</label>
                    <form action="p_flat.php" method="POST">
                        <input type="hidden" name="prj_name" id="flt_bprj_name">
                        <select name="lvl_id" id="flt_lvl" class="form-control selectpicker" data-live-search="true" required>
                            <option value=""></option>
                        </select>
                    
                </div>
            </div>
            <div class="pull-right mt-3">
                <button type="submit" name="flatBtn" class="btn btn-success"> Search</button>
            </div>
            </form>
        </div>
        <!-- Villa Type -->
        <div id="villa_div" class="d-none">
            <div class="row">
                <div class="col-6">
                    <label>Villa</label>
                    <select name="" id="flt_villa" class="form-control selectpicker" data-live-search="true" required>
                        <option>Select Villa</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Plex</label>
                    <select name="" id="flt_plx" class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Plex</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <label>Building</label>
                    <select name="" id="flt_vblg" class="form-control selectpicker" data-live-search="true" required>
                        <option>Select Villa</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>Level</label>
                    <form action="p_flat.php" method="POST">
                    <input type="hidden" name="prj_name" id="lvl_vprj_name">
                    <select name="lvl_id" id="flt_vlvl" class="form-control selectpicker" data-live-search="true" required>
                        <option>Select Villa</option>
                    </select>
                </div>
            </div>
            <div class="row pull-right mt-3 mb-3 mr-2">
                <button type="submit" name="flatBtn" class="btn btn-success"> Search</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
$.ajax({
    url:'ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $('#flt_project').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
var ctr=0;
$('#flt_project').on('change', function (){
    var proj_id = $(this).val();
    ctr++;
    // check project type
    if(ctr>=1){
    $.ajax({
        url: 'ajax_prj_cat.php',
        method: 'post',
        data:{'proj_id': proj_id},
        success:function(data){
            if(data=='Building'){
                $.ajax({
                    url: 'ajax_blg.php',
                    method: 'POST',
                    data:{'lvl_prj_id': proj_id},
                    success:function(data){
                        $(document).find('#flt_blg').html(data);
                        $('#villa_div').addClass("d-none");
                        $('#blg_div').removeClass("d-none"); 
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
            else{
                $('#villa_div').removeClass("d-none");
                $('#blg_div').addClass("d-none"); 
                // show villa options
                $.ajax({
                    url: 'ajax_villa.php',
                    method: 'POST',
                    data:{'prj_id': proj_id},
                    success:function(data){
                        $('#flt_villa').html(data).change();
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
        }
    });
    // for project name - prj_name blg type
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'project_name': proj_id},
        success:function(data){
            $('#flt_bprj_name').val(data).change();
            $('#lvl_vprj_name').val(data).change();
        }
    });}
});
$('#flt_villa').on('change', function(){
    var villa_id = $(this).val();
    $.ajax({
        url: 'ajax_plex.php',
        method: 'POST',
        data:{'villa_id': villa_id},
        success:function(data){
            $('#flt_plx').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    var v_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'villa_id': v_id},
        success:function(data){
            $('#lvl_vprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
$('#flt_plx').on('change', function(){
    var plex_id = $(this).val();
    $.ajax({
        url: 'ajax_blg.php',
        method: 'POST',
        data:{'plx_id': plex_id},
        success:function(data){
            $('#flt_vblg').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    var plx_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'plx_id': plx_id},
        success:function(data){
            $('#lvl_vprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
$('#flt_vblg').on('change', function(){
    var blg_id = $(this).val();
    $.ajax({
        url: 'ajax_blg.php',
        method: 'POST',
        data:{'blg_id': blg_id},
        success:function(data){
            $('#flt_vlvl').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    var vblg_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'blg_id': vblg_id},
        success:function(data){
            $('#lvl_vprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
$('#flt_blg').on('change', function(){
    var blg_id = $(this).val();
    $.ajax({
        url: 'ajax_blg.php',
        method: 'POST',
        data:{'blg_id': blg_id},
        success:function(data){
            $('#flt_lvl').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    // for prj name- blg name - blg-type
    var fblg_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'blg_id': fblg_id},
        success:function(data){
            $('#flt_bprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
//for prj name - level - blg type
$('#flt_lvl').on('change', function(){
var fblg_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'lvl_id': fblg_id},
        success:function(data){
            $('#flt_bprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
//for prj name - level - villa type
$('#flt_vlvl').on('change', function(){
var fblg_id = $(this).val();
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'lvl_id': fblg_id},
        success:function(data){
            $('#lvl_vprj_name').val(function(){
                return this.value +data;
            });
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>