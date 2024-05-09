<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 

$query="SELECT * FROM material WHERE Mat_Status=1";
$query_run = mysqli_query($connection, $query);
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <h5 class="m-0 font-weight-bold text-primary">Dashboard </h5>
        </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-3">
                        <label for="">Department</label>
                        <select name="" id="dept_opt" class="form-control">Projects</select>
                    </div>
                    <div class="col-3">
                        <label for="">Project</label>
                        <select name="" id="prj_opt" class="form-control ">Department</select>
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
                        <?php
                                if(mysqli_num_rows($query_run)>0)
                                {
                                    while($row = mysqli_fetch_assoc($query_run))
                                    {
                                        $mat_id = $row['Mat_Id'];
                                         // OVERALL QTY
                                        $qty_total = "SELECT SUM(mq.Mat_Q_Qty) as qty from mat_qty as mq LEFT JOIN project as p on p.Prj_Id = mq.Prj_Id where mq.Mat_Id='$mat_id' and mq.Mat_Qty_Status=1 and p.Prj_Status=1";
                                        $qty_total_run=mysqli_query($connection,$qty_total);
                                        $row_q = mysqli_fetch_assoc($qty_total_run);
                                        // MATERIALS ASSIGNED
                                        $q_total = "SELECT SUM(Asgd_Mat_to_Act_Qty) as tot_qty FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m.Mat_Id='$mat_id'";
                                        $q_total_run = mysqli_query($connection, $q_total);
                                        $row1 = mysqli_fetch_assoc($q_total_run);
                                        $tot_asgn = $row1['tot_qty'];
                                        if($tot_asgn == Null)
                                        {
                                            $tot_asgn=0;
                                        }
                                        // MATERIAL USED BY PCT DONE
                                        $q_used ="SELECT * FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN assigned_activity as as_a on as_a.Asgd_Act_Id = as_m_a.Asgd_Act_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m.Mat_Id='$mat_id'";
                                        $q_used_run = mysqli_query($connection, $q_used);
                                        $tot_used=0; 
                                        if(mysqli_num_rows($q_used_run)>0)
                                        {
                                            while($row_u = mysqli_fetch_assoc($q_used_run))
                                            {
                                                $qty = $row_u['Asgd_Mat_to_Act_Qty']; 
                                                $pct = $row_u['Asgd_Pct_Done'];
                                                $pct = $pct * 0.01; 
                                                $used_qty = $pct * $qty;
                                                $tot_used = $tot_used + $used_qty;
                                            }
                                        }
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
                                <td><?php echo $row['Mat_Code']?></td>
                                <td><?php echo $row['Mat_Desc']?></td>
                                <td><?php echo $row['Mat_Unit']?></td>
                                <td><?php echo number_format($row_q['qty'], 0, '.', '')?></td> 
                                <td><?php echo number_format($tot_asgn, 0, '.', '')?></td>
                                <td><?php echo number_format($tot_used, 0, '.', '')?></td>
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
            </div>
        </div>
    </div>
</div>
<script>
//data table
$(document).ready(function() {
    $('#mat_tbl').DataTable({
        pageLength: 10,
        filter: true,
        "searching": true,
    });
});
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
            $.ajax({
            url:'../P_ADMIN/ajax_mat_tbl.php',
            method: 'POST',
            data:{'dept_id':dept_id},
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
$(document).ready(function () {
    $('#prj_opt').change(function(e) { 
        if(e.originalEvent)
        {
            var prj_id = $(this).val();
            $.ajax({
            url:'../P_ADMIN/ajax_mat_tbl.php',
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