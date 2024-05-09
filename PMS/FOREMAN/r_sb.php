<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
?>
<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="col-xl-12 col-lg-12">
        <h5 class="m-0 font-weight-bold text-primary mb-4">Subcontractor Report</h5>
        <form action="r_sb.php" method="POST">
            <div class="row">
                <div class="col-4">
                    <br class="mt-2">
                    <label for="">Subcontractor</label>
                        <select name="mp_opt" id="mp_opt" class="form-control selectpicker" data-live-search="true" required></select>
                </div>
                <div class="col-2">
                    <br class="mt-2">
                    <label for="">From</label>
                        <input type="date" name="from" class="form-control">
                </div>
                <div class="col-2">
                    <br class="mt-2">
                    <label for="">To</label>
                        <input type="date" name="to" class="form-control">
                </div>
                <div class="col-2">
                    <label>Percentage Range</label><br>
                    <label for="">Min</label>
                    <input type="number" name="min" class="form-control" value="0" placeholder="any">
                </div>
                <div class="col-2">
                    <br class="mt-2">
                    <label>Max</label>
                    <input type="number" name="max" class="form-control" value="0" placeholder="any">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <label>Department</label>
                    <select name="dept_id" id="dept" class="form-control selectpicker" data-live-search="true" >
                            <option value="">Select Department</option>
                    </select>
                </div>
                <div class="col-3">
                    <label>Category</label>
                    <select name="category_id" id="category" class="form-control selectpicker" data-live-search="true" >
                            <option value="any">Select Department </option>
                    </select>
                </div>
                <div class="col-4">
                    <label>Activity</label>
                    <select name="act_id[]" id="activity" class="form-control selectpicker mb-4" data-live-search="true" multiple>
                        <option value="any">Select Activity</option>
                    </select>
                </div>
                <div class="col-2">
                    <button type="submit" name="search" class="btn btn-warning mt-4">Search</button>
                </div>
            </div>
            <!-- END FORM -->
        </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Activity</th>
                        <th>Project</th>
                        <th>Area</th>
                        <th>Qty</th>
                        <th>Day Total</th>
                        <th>%</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                <?php
        if(isset($_POST['search'])){
            $sb = $_POST['mp_opt'];
            $from = $_POST['from']; $dept_id=$_POST['dept_id']; $cat_id =$_POST['category_id']; 
            $to = $_POST['to']; $sb_total=0;  $min=$_POST['min'];$max=$_POST['max'];
            //no act id, with pct    -+
            if($dept_id=='Select Department' && $max !=0){
                $query = "SELECT * FROM daily_entry as DE
                    LEFT JOIN asgn_subcon ON asgn_subcon.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_subcon.SB_Id='$sb' and DE.DE_Status=1 
                    and asgn_subcon.Asgn_SB_Status=1 and as_act.Asgd_Act_Status=1 and DE.DE_Pct_Done BETWEEN '$min' AND '$max'
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
                // echo 'no act id, with pct';
            }
            // act id, no pct   +- /
            elseif($dept_id!='Select Department'  && $max==0){
                $act_id = implode(', ', $_POST['act_id']); 
                $query = "SELECT * FROM daily_entry as DE
                    LEFT JOIN asgn_subcon ON asgn_subcon.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_subcon.SB_Id='$sb' and DE.DE_Status=1 
                    and asgn_subcon.Asgn_SB_Status=1 and as_act.Asgd_Act_Status=1 and as_act.Act_Id in (".$act_id.")
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
                // echo 'act id, no pct';
            }// with act_id, with pct   ++ /
            elseif($dept_id!='Select Department'  && $max!=0){
                // with act id
                $act_id = implode(', ', $_POST['act_id']); 
                $query = "SELECT * FROM daily_entry as DE
                    LEFT JOIN asgn_subcon ON asgn_subcon.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_subcon.SB_Id='$sb' and DE.DE_Status=1 
                    and as_act.Act_Id in (".$act_id.") and DE.DE_Pct_Done BETWEEN '$min' AND '$max'
                    and asgn_subcon.Asgn_SB_Status=1 and as_act.Asgd_Act_Status=1 
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
                // echo 'with act_id, with pct';
            }// --
            else{
                $query = "SELECT * FROM daily_entry as DE
                    LEFT JOIN asgn_subcon ON asgn_subcon.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_subcon.SB_Id='$sb' and DE.DE_Status=1 
                    and asgn_subcon.Asgn_SB_Status=1 and as_act.Asgd_Act_Status=1 
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
                // echo '--';
            }
            $query_run = mysqli_query($connection, $query);
                if(mysqli_num_rows($query_run)>0)
                {
                    while($row = mysqli_fetch_assoc($query_run))
                    {
                        $act_id = $row['Act_Id'];
                        $act_query="SELECT * FROM activity WHERE Act_Status=1 and Act_Id='$act_id'";
                        $act_query_run = mysqli_query($connection,$act_query);
                        $row1 = mysqli_fetch_assoc($act_query_run);
                        $flat_id = $row['Flat_Id'];
                        // get building id
                        $b_query="SELECT * FROM building as b LEFT JOIN level as l on l.Blg_Id = b.Blg_Id LEFT JOIN flat as f on f.Lvl_Id = l.Lvl_Id WHERE b.Blg_Status=1 and l.Lvl_Status=1 and f.Flat_Id = $flat_id LIMIT 1";
                        $b_query_run =mysqli_query($connection, $b_query);
                        $row2 = mysqli_fetch_assoc($b_query_run);
                        $area = $row2['Blg_Code'].' '.$row2['Blg_Name'];
                        $prj_id = $row2['Prj_Id'];
                        if($prj_id===NULL)
                        {
                            $plex_id = $row2['Plx_Id'];
                            $p_query="SELECT * FROM project as p LEFT JOIN villa AS v on v.Prj_Id = p.Prj_Id LEFT JOIN plex AS plx on plx.Villa_Id = v.Villa_Id WHERE plx.Plx_Id ='$plex_id'  LIMIT 1";
                            $p_query_run = mysqli_query($connection, $p_query);
                            $result = mysqli_fetch_assoc($p_query_run);
                            $prj_name = $result['Prj_Code'].' - '.$result['Prj_Name'];
                        }
                        else
                        {
                            $p_query="SELECT * FROM project where Prj_Id='$prj_id' LIMIT 1";
                            $p_query_run = mysqli_query($connection, $p_query);
                            $result = mysqli_fetch_assoc($p_query_run);
                            $prj_name = $result['Prj_Code'].' - '.$result['Prj_Name'];
                        }
                        ?>
                    <tr>
                        <td><?php echo $row['DE_Date_Entry']?></td>
                        <td><?php echo $row1['Act_Code'].' - '.$row1['Act_Name']?></td>
                        <td><?php echo $prj_name?></td>
                        <td><?php echo $area?></td>
                        <td><?php echo $row['Asgn_SB_Qty']?></td>
                        <td><?php echo $row['Asgn_SB_Total']; $sb_total = $sb_total+$row['Asgn_SB_Total']?></td>
                        <td><?php echo number_format($row['DE_Pct_Done'], 0, '.','')?></td>
                        <td><?php echo number_format($row['Asgn_SB_Performance'], 2, '.','').'%'?></td>
                    </tr>
                        <?php
                    }
                    ?>
                    <tfoot>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="font-weight-bold">Total</td>
                        <td class="font-weight-bold"><?php echo $sb_total?></td>
                        <td></td>
                        <td></td>
                    </tfoot>
                    <?php
                }
            }
                ?>
                </tbody>
            </table>
        </div>
        <div align="right">
            <button name="" id="download" class="btn btn-success mt-2">
                <i class="fa fa-download" aria-hidden="true"></i>
                Download
            </button>  
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    mp='';
    $.ajax({
        url:'../P_ADMIN/ajax_mp.php',
        method: 'POST',
        data:{'sb':mp},
        success:function(data){
            $('#mp_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
    $('#dataTable').dataTable({
        "paging": false
    });
    $(document).ready(function(){
        $("#download").click(function(){
            var table = new Table2Excel();
            table.export(document.querySelectorAll("#dataTable"));
        });
        
    });
    
});
$.ajax({
    url:'ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $('#dept').html(data).change();
        $('#dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
$(document).on('change','#dept', function(){
        var dept_id = $(this).val();
        $.ajax({
            url:'ajax_act_cat.php',
            method: 'POST',
            data:{'dept_id': dept_id},
            success:function(data){
                $('#category').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
    $(document).on('change','#category', function(){
        var cat_id = $(this).val();
        $.ajax({
            url:'ajax_act_cat.php',
            method: 'POST',
            data:{'act_cat_id': cat_id},
            success:function(data){
                $('#activity').html(data).change();
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>