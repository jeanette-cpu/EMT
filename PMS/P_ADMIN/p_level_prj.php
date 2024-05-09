<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">Manage Level</h5>
        </div>
            <div class="card-body">
                <?php
                    $query1 = "SELECT Prj_Id, Prj_Code,Prj_Name FROM project where Prj_STATUS=1 ";
                    $query_run1 = mysqli_query($connection, $query1);
                ?>
                    
                <label>Project</label>
                <select name="prj_id" id="lvl_project" form="lvl_blg_type" class="form-control selectpicker lvl_project" data-live-search="true" required>
                    <option value="">Select Project</option>
                    <?php
                        while($row1 = mysqli_fetch_array($query_run1))
                        {
                        ?>
                            <option value='<?php echo $row1['Prj_Id']?>'><?php $lvl_prj_name= $row1['Prj_Code'].' - '.$row1['Prj_Name']; echo $lvl_prj_name?></option>
                        <?php
                        }
                    ?>    
                </select>
                <form action="p_level.php" method="POST">
                    <div class="d-none" id="prj_blg">
                        <label class="pt-3">Building</label>
                        <select name="blg_id" id="lvl_blg_p" class="form-control selectpicker" data-live-search="true" required></select>
                    </div>
                    <!-- building type -->
                    <input type="hidden" name="prj_name" id="lvl_bprj_name">
                    <div class="pull-right mt-3 d-none" id="lvl_b1">
                        <button type="submit" name="lvlBtn" class="btn btn-success"> Search</button>
                    </div>
                </form>
                <div class="row d-none pt-3" id="villa_row">
                    <div class="col-6">
                        <label>Villa</label>
                        <select name="" id="lvl_villa" class="form-control selectpicker" data-live-search="true" required>
                        </select>
                    </div>
                    <div class="col-6">
                        <label>Plex</label>
                        <select name="plx_id" id="lvl_plex" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">Select Plex</option>
                        </select>
                    </div>
                </div>
                <form action="p_level.php" method="POST">
                    <div class="d-none" id="level_blg">
                        <label class="pt-3">Building</label>
                        <select name="blg_id" id="lvl_blg" class="form-control selectpicker" data-live-search="true" required>
                        </select>
                    </div>
                    <!-- villa type -->
                    <input type="hidden" id="prj_name1">
                    <input type="hidden" id="villa_name">
                    <input type="hidden" id="plex_name">
                    <input type="hidden" id="building_name">
                    <input type="hidden" name="prj_name" id="lvl_prj_name">
                    <div class="pull-right mt-3 d-none" id="lvl_b2">
                        <button type="submit" name="lvlBtn" id="level_btn" class="btn btn-success"> Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// level
$('#lvl_project').on('change', function (){
    var proj_id = $(this).val();
    // check project type
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
                        $(document).find('#lvl_blg_p').html(data);
                        $('#villa_row').addClass("d-none");
                        $('#level_blg').addClass("d-none");
                        $('#prj_blg').removeClass("d-none"); 
                        $('#lvl_b1').removeClass("d-none");
                        $('#lvl_b2').addClass("d-none"); 
                        $('.selectpicker').selectpicker('refresh');
                    }
                });
            }
            else{
                $('#villa_row').removeClass("d-none");
                $('#level_blg').removeClass("d-none");
                $('#lvl_b2').removeClass("d-none");
                $('#lvl_b1').addClass("d-none");
                $('#prj_blg').addClass("d-none");
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
    // show villa options
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'prj_id': proj_id},
        success:function(data){
            $('#lvl_villa').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    // for project name
    $.ajax({
        url: 'ajax_villa.php',
        method: 'POST',
        data:{'project_name': proj_id},
        success:function(data){
            $('#lvl_prj_name').val(data).change();
            $('#lvl_bprj_name').val(data).change();
            $('#prj_name1').val(data).change();
        }
    });

    $('#lvl_villa').on('change', function (){  
        var villa_id = $(this).val();
        $.ajax({
            url: 'ajax_plex.php',
            method: 'POST',
            data:{'villa_id': villa_id},
            success:function(data){
                $('#lvl_plex').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
        //for project name
        $.ajax({
            url: 'ajax_villa.php',
            method: 'POST',
            data:{'villa_id': villa_id},
            success:function(data){
                $('#lvl_prj_name').val(function(){
                    return this.value +data;
                });
                $('#villa_name').val(data).change();
            }
        });
    });
    $('#lvl_blg_p').on('change', function (){  
        var b_id = $(this).val();
        //for project name
        $.ajax({
            url: 'ajax_villa.php',
            method: 'POST',
            data:{'blg_id': b_id},
            success:function(data){
                $('#lvl_bprj_name').val(function(){
                    return this.value +data;
                });
                $('#building_name').val(data).change();
            }
        });
    });
});
$(document).ready(function(){
    $('#lvl_plex').on('change', function (){  
        var plex_id = $(this).val();
        $.ajax({
            url: 'ajax_blg.php',
            method: 'POST',
            data:{'plx_id': plex_id},
            success:function(data){
                $('#lvl_blg').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
        //for prj_name
        $.ajax({
            url: 'ajax_villa.php',
            method: 'POST',
            data:{'plx_id': plex_id},
            success:function(data){
                $('#lvl_prj_name').val(function(){
                    return this.value +data;
                });
                $('#plex_name').val(data).change(); 
                $('.selectpicker').selectpicker('refresh');              
            }
        });
    });
    //for prj name
    $('#lvl_blg').on('change', function (){  
        var blg_id = $(this).val();
        $.ajax({
            url: 'ajax_villa.php',
            method: 'POST',
            data:{'blg_id': blg_id},
            success:function(data){
                $('#lvl_prj_name').val(function(){
                    return this.value +data;
                });        
                $('#building_name').val(data).change();
            }
        });
    });
    $('#level_btn').on('click', function (){ 
        var prj_name1 = document.getElementById("prj_name1").value;
        var villa_name = document.getElementById("villa_name").value;
        var plex_name = document.getElementById("plex_name").value;
        var building_name = document.getElementById("building_name").value;
        var prj_namee = prj_name1+' '+villa_name+' '+plex_name+' '+building_name;
        $('#lvl_prj_name').val(prj_namee);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>