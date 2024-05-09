<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
?>

<div class="container-fluid">
    <div class="col-xl-12 col-lg-12">
        <h5 class="m-0 font-weight-bold text-primary mb-4">Manpower Supply Total</h5>
        <form action="m_mp.php" method="POST">
            <div class="row">
                <div class="col-4">
                    <label for="">Manpower Supply Companies</label>
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
                        <th>Project</th>
                        <th>Department</th>
                        <th>Total</th>
                        <th class="d-none"></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
        if(isset($_POST['search']) or isset($_GET['id'])){
            
            if(isset($_POST['search']))
            {
                $mp = $_POST['mp_opt'];
                $from = $_POST['from'];
                $to = $_POST['to']; 
            }
            else
            {
                $mp = $_GET['id'];
                $from = $_GET['from'];
                $to = $_GET['to']; 
            }
            $mp_total=0;

            $query = "SELECT * FROM daily_entry as DE LEFT JOIN asgn_mp ON asgn_mp.DE_Id = DE.DE_Id 
                    LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE asgn_mp.MP_Id='$mp' and DE.DE_Status=1 
                    and asgn_mp.Asgn_MP_Status=1 and as_act.Asgd_Act_Status=1  AND asgn_mp.Asgn_MP_Total is not null
                    and DE.`DE_Date_Entry` BETWEEN '".$from."' AND '".$to."' ORDER BY DE.DE_Date_Entry ASC";
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
                        //department name
                        $user_id = $row['User_Id']; $dept_q ="SELECT Dept_Name  from department LEFT JOIN users on users.Dept_Id =department.Dept_Id
                        where users.USER_ID='$user_id' LIMIT 1";
                        $dept_q_run=mysqli_query($connection,$dept_q);
                        $row_d=mysqli_fetch_assoc($dept_q_run);

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
                        <td><?php echo $prj_name?></td>
                        <td><?php echo $row_d['Dept_Name']?></td>   <!--  dept -->
                        <td><?php echo $row['Asgn_MP_Total']; $mp_total = $mp_total+$row['Asgn_MP_Total']?></td>
                        <td class="d-none"><?php echo $row['Asgn_MP_Id'] ?></td>
                        <td class="btn-group text-center">
                             <!-- edit -->
                            <button type="button" class="btn btn-success editBtn">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            
                        </td> <!--  buttons -->
                    </tr>
                        <?php
                    }
                    ?>
                    <tfoot>
                        <td></td>
                        <td></td>
                        <td class="font-weight-bold">Total</td>
                        <td class="d-none"></td>
                        <td class="font-weight-bold"><?php echo $mp_total?></td>
                        <td></td>
                    </tfoot>
                    <?php
                }
            }
                ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
<!-- Modal Edit Total -->
<div class="modal fade bd-example-modal-sm" id="editAct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-child" aria-hidden="true"></i> Manpower Supply</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="code.php" method="POST">
            <div class="form-group">
                <input type="hidden" name="Asgn_MP_Id" id="Asgn_MP_Id" >
                <label>Total: </label>
                <input type="number" name="Asgn_MP_Total" id="Asgn_MP_Total" class="form-control">
                
                <input type="hidden" name="mp_id" value="<?php echo $mp?>">
                <input type="hidden" name="from" value="<?php echo $from?>">
                <input type="hidden" name="to" value="<?php echo $to?>">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="mpTotal" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Edit Total -->
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
});
//EDIT MODAL PROGRESS
$('.editBtn').click(function(){
    $('#editAct').modal('show');
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            console.log(data);
        $('#Asgn_MP_Id').val(data[4]);
        $('#Asgn_MP_Total').val(data[3]); 
});
</script>
<?php
include('activity_script.php');
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>