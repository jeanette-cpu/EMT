<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/pm_navbar.php');  
?>
<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="col-xl-12 col-lg-12">
        <h5 class="m-0 font-weight-bold text-primary mb-4">Manpower Report</h5>
        <form action="r_mp.php" method="POST">
            <div class="row">
                <div class="col-4">
                    <label for="">Manpower</label>
                        <select name="mp_opt" id="mp_opt" class="form-control selectpicker" data-live-search="true"></select>
                </div>
                <div class="col-3">
                    <label for="">From</label>
                        <input type="date" name="from" class="form-control">
                </div>
                <div class="col-3">
                    <label for="">To</label>
                        <input type="date" name="to" class="form-control">
                </div>
                <div class="col-2">
                    <button type="submit" name="search" class="btn btn-warning mt-4">Search</button>
                </div>
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
            $mp = $_POST['mp_opt'];
            $from = $_POST['from'];
            $to = $_POST['to']; $mp_total=0;

            $query = "SELECT * FROM daily_entry as DE
                    LEFT JOIN asgn_mp ON asgn_mp.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_mp.MP_Id='$mp' and DE.DE_Status=1 
                    and asgn_mp.Asgn_MP_Status=1 and as_act.Asgd_Act_Status=1 
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
            // echo $query;
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
                        <td><?php echo $row['Asgn_MP_Qty']?></td>
                        <td><?php echo $row['Asgn_MP_Total']; $mp_total = $mp_total+$row['Asgn_MP_Total']?></td>
                        <td><?php echo number_format($row['DE_Pct_Done'], 0, '.','')?></td>
                        <td><?php echo number_format($row['Asgn_MP_Performance'], 2, '.','').'%'?></td>
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
                        <td class="font-weight-bold"><?php echo $mp_total?></td>
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
        data:{'mp':mp},
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
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>