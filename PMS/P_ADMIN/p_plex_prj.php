<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>
<div class="container-fluid">
<!-- PLEX -->
    <div class="card shadow mb-4 col-md-11">
        <div class="card-header py-3">
            <h5 class="font-weight-bold text-primary"><i class="fa fa-th-large mr-2" aria-hidden="true"></i>Manage Plex</h5>
        </div>
        <div class="card-body">
            <form action="p_plex.php" method="POST">
            <div class="row">
            <?php
                $query = "SELECT Prj_Id, Prj_Code,Prj_Name FROM project where Prj_STATUS=1 AND Prj_Category='Villa'";
                $query_run = mysqli_query($connection, $query);
            ?>
                <div class="col-md-6 prj">
                    <label>Project</label>
                    <select name="" id="project" class="form-control selectpicker" data-live-search="true" required>
                        <option value="">Select Project</option>
                        <?php
                            while($row = mysqli_fetch_array($query_run))
                            {
                            ?>
                                <option value='<?php echo $row['Prj_Id']?>'><?php $prj_name= $row['Prj_Code'].' - '.$row['Prj_Name']; echo $prj_name?></option>
                            <?php
                            }
                        ?>                               
                    </select>
                </div>
                    
                <div class="col-md-6">
                    <label>Villa</label>
                    <select name="villa_id" id="villa" class="form-control selectpicker" data-live-search="true" required>
                        <option>Select Project First</option>
                    </select>
                </div>
            </div>
            <br>
                <input type="hidden" id="prj_name1" value="">
                <input type="hidden" id="villa_name" value="">
                <input type="hidden" id="prj_name" name="prj_name" value="<?php echo $prj_name?>">
            <div class="pull-right mt-3">
                <button type="submit" name="plexBtn" id="btnPlx" class="btn btn-success"> Search
                </button>
            </div>
            </form>
        </div>
    </div>   
</div>
<script>
// plex
$(document).ready(function(){
    $('#project').on('change', function () {
        var prj_id = $(this).val();
            $.ajax({
                url: 'ajax_villa.php',
                method: 'POST',
                data:{'prj_id': prj_id},
                success:function(data){
                    $('#villa').html(data).change();
                    $('.selectpicker').selectpicker('refresh');
                }
            });
            // for project name - prj_name blg type
            $.ajax({
                url: 'ajax_villa.php',
                method: 'POST',
                data:{'project_name': prj_id},
                success:function(data){
                    $('#prj_name1').val(data).change();
                    $('.selectpicker').selectpicker('refresh');
                }
            });
    });
    $('#villa').on('change', function (){
        var villa_id = $(this).val();
        $.ajax({
            url: 'ajax_villa.php',
            method: 'POST',
            data:{'villa_id': villa_id},
            success:function(data){
                $('#prj_name').val(function(){
                    return this.value +data;
                });
                $('#villa_name').val(data).change();
            }
        });
        $('.selectpicker').selectpicker('refresh');
        // villa name

    });
    $('#btnPlx').on('click', function (){
        var prj_name1 = document.getElementById("prj_name1").value;
        var villa_name = document.getElementById("villa_name").value;
        var prj_name = prj_name1+' '+villa_name;
        $('#prj_name').val(prj_name);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>