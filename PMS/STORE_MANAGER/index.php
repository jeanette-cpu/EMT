<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/store_mgr_navbar.php');
//GET PRJ ID ASSIGNED TO STR MGR
$username = $_SESSION['USERNAME'];
$sql="SELECT USER_ID from users where username='$username' and USER_STATUS=1 limit 1";
$query_run2=mysqli_query($connection,$sql);
$row_uu = mysqli_fetch_assoc($query_run2);
$user_id = $row_uu['USER_ID'];

$query_p = "SELECT * FROM project as p LEFT JOIN asgn_emp_to_prj as ass_e on ass_e.Prj_Id = p.Prj_Id WHERE p.Prj_Status =1 and ass_e.User_Id='$user_id' LIMIT 1";
// echo $query_p;
$query_p_run = mysqli_query($connection, $query_p);
if(mysqli_num_rows($query_p_run)>0)
{ 
    $row_p = mysqli_fetch_assoc($query_p_run);
    $prj_id = $row_p['Prj_Id'];
    $prj_name = $row_p['Prj_Code'].' - '.$row_p['Prj_Name'];
    $category = $row_p['Prj_Category'];
    if($category=='Building')
    {
         // get building assigned
         $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
         $q_building_run = mysqli_query($connection, $q_building);
         $b_id_arr=null; $b_ids= null;
         if(mysqli_num_rows($q_building_run)>0)
        {
            while($row_b = mysqli_fetch_assoc($q_building_run))
            {
                $b_id_arr[] = $row_b['Blg_Id'];
            }
            $b_ids = implode("', '", $b_id_arr);
            // get levels
            $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
            $q_level_run = mysqli_query($connection, $q_levels);
            $lvl_id_arr=null; $lvl_ids = null;
            if(mysqli_num_rows($q_level_run)>0)
            {
                while($row_l = mysqli_fetch_assoc($q_level_run))
                {
                    $lvl_id_arr[] = $row_l['Lvl_Id'];
                }
                $lvl_ids = implode("', '", $lvl_id_arr);
                // get flat id
                $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                $q_flat_run = mysqli_query($connection, $q_flat);
                $flt_ids =null; $flat_id_arr=null;
                if(mysqli_num_rows($q_flat_run)>0)
                {
                    while($row_f = mysqli_fetch_assoc($q_flat_run))
                    {
                        $flat_id_arr[] = $row_f['Flat_Id'];
                    }
                    $flt_ids = implode("', '", $flat_id_arr);
                    // get assigned id
                    $q_asgd_id ="SELECT Asgd_Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                    $q_asgd_id_run = mysqli_query($connection, $q_asgd_id);
                    if(mysqli_num_rows($q_asgd_id_run)>0)
                    {
                        while($row_act = mysqli_fetch_assoc($q_asgd_id_run ))
                        {
                            $asgd_arr[] = $row_act['Asgd_Act_Id'];
                        }
                        $asgd_id = implode("', '", $asgd_arr);
                    }
                }
            }
        }
    }
    else
    {
        // get villas assigned
        $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
        $q_villa_run = mysqli_query($connection, $q_villa);
        $villa_id_arr= null; $villa_ids= null;
        if(mysqli_num_rows($q_villa_run)>0)
        {
            while($row_v = mysqli_fetch_assoc($q_villa_run))
            {
                $villa_id_arr[] = $row_v['Villa_Id'];
            }
            $villa_ids = implode("', '", $villa_id_arr);
            // get plex
            $q_plex = "SELECT Plx_Id from plex where Plx_Status='1' and Villa_Id in ('$villa_ids')";
            $q_plex_run = mysqli_query($connection, $q_plex);
            if(mysqli_num_rows($q_plex_run)>0)
            {
                while($row_p = mysqli_fetch_assoc($q_plex_run))
                {
                    $plex_id_arr[] = $row_p['Plx_Id'];
                }
                $plex_ids = implode("', '", $plex_id_arr);
                // get building
                $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
                $q_building_run = mysqli_query($connection, $q_building);
                if(mysqli_num_rows($q_building_run)>0)
                {
                    while($row_b = mysqli_fetch_assoc($q_building_run))
                    {
                        $b_id_arr[] = $row_b['Blg_Id'];
                    }
                    $b_ids = implode("', '", $b_id_arr);
                    // get levels
                    $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                    $q_level_run = mysqli_query($connection, $q_levels);
                    if(mysqli_num_rows($q_level_run)>0)
                    {
                        while($row_l = mysqli_fetch_assoc($q_level_run))
                        {
                            $lvl_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $lvl_ids = implode("', '", $lvl_id_arr);
                        // get flat id
                        $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                        $q_flat_run = mysqli_query($connection, $q_flat);
                        if(mysqli_num_rows($q_flat_run)>0)
                        {
                            while($row_f = mysqli_fetch_assoc($q_flat_run))
                            {
                                $flat_id_arr[] = $row_f['Flat_Id'];
                            }
                            $flt_ids = implode("', '", $flat_id_arr);
                            // get assigned activities
                            $q_asgd_id ="SELECT Asgd_Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                            $q_asgd_id_run = mysqli_query($connection, $q_asgd_id);
                            if(mysqli_num_rows($q_asgd_id_run)>0)
                            {
                                while($row_act = mysqli_fetch_assoc($q_asgd_id_run ))
                                {
                                    $asgd_arr[] = $row_act['Asgd_Act_Id'];
                                }
                                $asgd_id = implode("', '", $asgd_arr);
                            }
                        }
                    }
                }
            }
        }
    }
}
else
{
    echo "<div class='ml-4 '>     No Projects Assigned</div>"; 
    $prj_id=NULL; $asgd_id=NULL;$prj_name=NULL;
}
// ALL ROWS
$queryq="(select Mat_Id from mat_qty WHERE Prj_Id='$prj_id' ) union (SELECT DISTINCT(as_m.Mat_Id) from asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN material as matt on matt.Mat_Id= as_m.Mat_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and matt.Mat_Status=1 and as_m.Asgd_Mat_Status=1 and as_m_a.Asgd_Act_Id in ('$asgd_id'))";
// echo $asgd_id;
$query_runq = mysqli_query($connection, $queryq);
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Dashboard </h5>
        <H4 class="ml-2 mt-1 b"><?php echo $prj_name?></H4>
        <input type="hidden" id="prj_id" value="<?php echo $prj_id?>">

        </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-3">
                        <!-- <label for="">Department</label>
                        <select name="" id="dept_opt" class="form-control"></select> -->
                    </div>
                </div>
                <div class="table-responsive" >
                    <div id="mat_table">
                    <table class="table table-bordered table-striped" id="mat_tbl" width="100%" cellspacing="0">
                        <thead>
                            <th>Code</th>
                            <th>Material Desc</th>
                            <th>Unit</th>
                            <th>Qty Available</th>
                            <th>Qty Assigned</th>
                            <th>Est. Qty Used by Done%</th>
                            <th>Est. Qty Needed (order/transfer)</th>
                        </thead>
                        <tbody>
                        <?php $c=0;
                        if(mysqli_num_rows($query_runq)>0)
                        {
                            while($row = mysqli_fetch_assoc($query_runq))
                            {
                            // while($c<=1)
                            // {
                            //     $c++;
                                // $row = mysqli_fetch_assoc($query_runq);
                                $mat_id = $row['Mat_Id'];
                                // MAT NAME
                                $m_name ="SELECT * FROM material where Mat_Id='$mat_id'";
                                $m_name_run =mysqli_query($connection,$m_name);
                                $row_m=mysqli_fetch_assoc($m_name_run);
                                // OVERALL QTY AVAILABLE 1ST COL
                                $qty_total = "SELECT SUM(Mat_Q_Qty) as qty from mat_qty where Mat_Id='$mat_id' and Mat_Qty_Status=1 and Prj_Id='$prj_id'";
                                $qty_total_run=mysqli_query($connection,$qty_total);
                                $row_q = mysqli_fetch_assoc($qty_total_run);
                                // TOTAL MAT QTY ASSIGNED - 2ND COL
                                // $asgn_mat ="SELECT SUM(Asgd_Mat_to_Act_Qty)as tot_qty FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN material as matt on matt.Mat_Id= as_m.Mat_Id LEFT JOIN Mat_Qty as mat_q on mat_q.Mat_Id = matt.Mat_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and matt.Mat_Status=1 and as_m.Asgd_Mat_Status=1 and matt.Mat_Id='$mat_id' and as_m_a.Asgd_Act_Id in ('$asgd_id') and mat_q.Prj_Id='$prj_id'";
                                $asgn_mat="SELECT SUM(Asgd_Mat_to_Act_Qty)as tot_qty FROM asgn_mat_to_act as as_m_a 
                                            LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id 
                                            WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 
                                            and as_m.Mat_Id='$mat_id' and as_m_a.Asgd_Act_Id in ('$asgd_id') ";
                                // ECHO $asgn_mat;
                                $asgn_mat_run=mysqli_query($connection,$asgn_mat);
                                $row_5 = mysqli_fetch_assoc($asgn_mat_run);
                                // MATERIAL USED BY PCT DONE - 3RD COL
                                // $q_used ="SELECT SUM((as_act.Asgd_Pct_Done * 0.01)*as_m_a.Asgd_Mat_to_Act_Qty) as mat_used FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = as_m_a.Asgd_Act_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m_a.Asgd_Act_Id in ('$asgd_id') and as_m.Mat_Id='$mat_id'";

                                $q_used ="SELECT SUM((as_act.Asgd_Pct_Done * 0.01)*as_m_a.Asgd_Mat_to_Act_Qty) as mat_used FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = as_m_a.Asgd_Act_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m_a.Asgd_Act_Id in ('$asgd_id') and as_m.Mat_Id='$mat_id'";
                                $q_used_run = mysqli_query($connection, $q_used); 
                                $row_u= mysqli_fetch_assoc($q_used_run);
                                $tot_asgn =$row_5['tot_qty']; 
                                $tot_used = $row_u['mat_used'];
                                // compuation for transfer/order - 4TH COL
                                $n = $tot_asgn - $tot_used;
                                if($n==0)
                                {
                                    $n = $row_q['qty']; 
                                }
                                else
                                {
                                    $n = $row_q['qty'] -$n; 
                                } 
                                        ?>
                            <tr>
                                <td><?php echo $row_m['Mat_Code']?></td>
                                <td><?php echo $row_m['Mat_Desc']?></td>
                                <td><?php echo $row_m['Mat_Unit']?></td>
                                <td><?php echo number_format($row_q['qty'], 0, '.', '')?></td>
                                <td><?php echo number_format($row_5['tot_qty'], 0, '.', '')?></td>
                                <td><?php echo number_format($row_u['mat_used'], 0, '.', '')?></td>
                                <td><?php echo number_format($n, 0, '.', '');?></td>
                            </tr>
                            <?php
                            }
                        }
                        else
                        {
                            echo "No Record Found";
                        }
                    ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div align="right">
                    <button name="" id="dl_btn" class="btn btn-success mt-3">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Download
                    </button>  
                </div>
            </div>
        </div>
    </div>
</div>
<script src="table2excel.js"> </script>
<script>
//data table
$(document).ready(function() {
    $('#mat_tbl').DataTable({
        pageLength: 500,
        filter: true,
        "searching": true,
    });
});
// download table
$(document).ready(function(){
    $("#dl_btn").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#mat_tbl"));
    });
});
// populate department options
$.ajax({
    url:'../P_ADMIN/ajax_dept.php',
    method: 'POST',
    data:{},
    success:function(data){
        $('#dept_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
$.ajax({
    url:'../P_ADMIN/ajax_project.php',
    method: 'POST',
    data:{},
    success:function(data){
        $('#prj_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
    }
});
$(document).ready(function () {
    $('#dept_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var dept_id = $(this).val();
            var prj_id = $('#prj_id').val();
            $.ajax({
            url:'../P_ADMIN/ajax_mat_tbl.php',
            method: 'POST',
            data:{'d_id':dept_id,
                    'p_id': prj_id},
            success:function(data){
                $('#mat_table').html(data).change();
                $('#mat_tbl').DataTable({
                    pageLength: 500,
                    filter: true,
                    "searching": true,
                });
                }
            });
        }
    });
});
$(document).ready(function () {
    $('#prj_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var prj_id = $(this).val();
            $.ajax({
            url:'ajax_mat_tbl.php',
            method: 'POST',
            data:{'prj_id':prj_id},
            success:function(data){
                $('#mat_table').html(data).change();
                $('#mat_tbl').DataTable({
                    pageLength: 10,
                    filter: true,
                    "searching": true,
                });
                }
            });
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>