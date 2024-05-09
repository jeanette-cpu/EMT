<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php'); 
if(isset($_POST['byAct'])){
    $act_id=$_POST['act_id'];
    $act_name=$_POST['act_name'];
    $dept_id=$_POST['dept_id'];
    $dept_name=$_POST['dept_name'];
    $prj_id=$_POST['prj_id'];
    $prj_name=$_POST['prj_name'];
    $prj_cat=$_POST['prj_cat'];
    $flt_ids=$_POST['flat_ids'];
    $bgt_per=$_POST['bgt'];
}
function decFormat($value) {
    $pattern = '/\.\d{3,}/'; // Regular expression pattern
    if (preg_match($pattern, $value)) {
      $value=round($value, 2);
      return $value; // More than two decimal points found
    }
    return $value; // Less than or equal to two decimal points
  }
?>
<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-1">
            <i class="fas fa-fw fa-building fa-3x text-danger-500" style="color:#bc0203"></i>
        </div>
        <div class="col-10" style="color:#bc0203">
            <h3 class="float-left"><?php echo $prj_name;?></h3>
        </div>
    </div>
    <div class="card  shadow h-100 py-2">
        <div class="card-body">
            <h5 class="text-primary"><?php echo $act_name;?> </h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="tbl1" width="100%" cellspacing="0">
                    <thead>
                        <th>Villa/Flat</th>
                        <th>Percentage</th>
                        <th>Date</th>
                        <th colspan="2">EMT </th>
                        <th colspan="2">SB </th>
                        <th colspan="2">MP </th>
                        <th>Tot</th>
                        <th>Bgt</th>
                    </thead>
                    <tbody>
<?php 
    $asgn_act="SELECT DISTINCT flt.Flat_Id, as_act.Asgd_Act_Id, flt.Flat_Id, flt.Flat_Code, flt.Flat_Name, as_act.Asgd_Pct_Done 
                FROM assigned_activity as as_act
                LEFT JOIN flat as flt on flt.Flat_Id=as_act.Flat_Id
                LEFT JOIN daily_entry as de on de.Asgd_Act_Id=as_act.Asgd_Act_Id
                WHERE as_act.Asgd_Act_Status=1 AND as_act.Flat_Id IN ('$flt_ids') 
                AND flt.Flat_Status=1 AND as_act.Act_Id='$act_id' AND de.DE_Id IS NOT NULL AND de.DE_Status=1";
    $asgn_act_run=mysqli_query($connection,$asgn_act); $total_pct_done=0; $total_bgt=0;
    $v_cnt=mysqli_num_rows($asgn_act_run); $total_worker=0;
    if($v_cnt>0){
        while($row=mysqli_fetch_assoc($asgn_act_run)){
            $asgd_act_id=$row['Asgd_Act_Id'];
            $flat_id=$row['Flat_Id'];
            $code=$row['Flat_Code']; //villa or flat
            $name=$row['Flat_Name'];
            $pct_done=$row['Asgd_Pct_Done']; //current pct done
            $total_pct_done=$total_pct_done+$pct_done;
            if($prj_cat=='Villa'){
                $bgt=$bgt_per;
            }
            elseif($prj_cat=='Building'){

            }
            ?>
                <tr>
                    <td><?php echo $code.' '.$name;?></td>
                    <td class="font-weight-bold"><?php echo $pct_done;?></td>
                    <td colspan="9"></td>
                </tr>
            <?php
            //2nd tr for the history of daily entry
            $q_de="SELECT * FROM daily_entry as de
                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id=as_act.Asgd_Act_Id
                    WHERE de.DE_Status=1 AND de.Asgd_Act_Id='$asgd_act_id' AND as_act.Flat_Id IN ('$flt_ids') AND as_act.Asgd_Act_Status=1";
            $q_de_run=mysqli_query($connection,$q_de);
            $num_rows=mysqli_num_rows($q_de_run);
            $total_emp=NULL;$total_sb=NULL; $total_mp=NULL;
            if($num_rows>0){
                $num_rows++;
                ?>
                    <tr>
                        <td rowspan="<?php echo $num_rows;?>"></td> 
                <?php
                while($row_de=mysqli_fetch_assoc($q_de_run)){
                    $de_id=$row_de['DE_Id'];
                    $de_pct_done=$row_de['DE_Pct_Done'];
                    $de_date=$row_de['DE_Date_Entry'];
                    //emt workers
                    $q_emt="SELECT * FROM asgn_worker as as_w
                            LEFT JOIN employee as emp on emp.Emp_Id=as_w.Emp_Id
                            WHERE as_w.Asgd_Worker_Status=1 AND as_w.DE_Id='$de_id'";
                    $q_emt_run=mysqli_query($connection,$q_emt);
                    $emp_row_cnt='';  $emp_names='';
                    if(mysqli_num_rows($q_emt_run)>0){
                        while($row_emt=mysqli_fetch_assoc($q_emt_run)){
                            $emp_id=$row_emt['EMP_ID'];
                            $emp_name=$row_emt['EMP_NO'].' - '.$row_emt['EMP_FNAME'].' '.$row_emt['EMP_LNAME'];
                            $emp_names.='<input type="hidden" value="'.$de_date.'">';
                            $emp_names.='<span class="record">'.$emp_name.'</span><input type="hidden" value="'.$emp_id.'"><br>';
                            //check/count if an employee code worked on the "SAME" activity or "OTHER" on a single day on other villa
                            $q_chk_emp="SELECT COUNT(as_w.Emp_Id) as dup FROM asgn_worker as as_w
                                        LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id
                                        WHERE as_w.Asgd_Worker_Status=1 AND de.DE_Date_Entry='$de_date' 
                                        AND de.DE_Status=1 AND as_w.Emp_Id='$emp_id'";
                            $q_chk_emp_run=mysqli_query($connection,$q_chk_emp); 
                            // echo $q_chk_emp.'<br>';
                            if(mysqli_num_rows($q_chk_emp_run)>0){ 
                                $row_chk=mysqli_fetch_assoc($q_chk_emp_run);
                                $count=$row_chk['dup'];
                                if($count==1){
                                    $total_emp=$total_emp+1;
                                    $emp_row_cnt.= '1<br>';
                                }
                                elseif($count>1){
                                    // echo $count.'<br>';
                                    $div=1/$count;
                                    $div=decFormat($div);
                                    $emp_row_cnt.=$div.'<br>';
                                    $total_emp=$total_emp+ $div;//total employee worked in this activity
                                }
                            }
                        }
                    }
                    // sb
                    $q_sb="SELECT * FROM asgn_subcon as as_sb 
                           LEFT JOIN daily_entry as de on de.DE_Id=as_sb.DE_Id
                           LEFT JOIN subcontractor as sb on sb.SB_Id=as_sb.SB_Id
                            WHERE as_sb.Asgn_SB_Status=1 and de.DE_Id='$de_id' AND de.DE_Status=1";
                    $q_sb_run=mysqli_query($connection,$q_sb);
                    $sb_name=''; $sb_count='';
                    if(mysqli_num_rows($q_sb_run)>0){
                        while($row_sb=mysqli_fetch_assoc($q_sb_run)){
                            $sb_id=$row_sb['SB_Id'];
                            $sb_name.=$row_sb['SB_Name'].'<br>';
                            //check how many total 
                            $sb_chk_tot="SELECT COUNT(as_sb.SB_Id) as cnt, SUM(as_sb.Asgn_SB_Total) as sb_tot
                                        FROM asgn_subcon as as_sb
                                        LEFT JOIN daily_entry as de on as_sb.DE_Id=de.DE_Id
                                        LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                                        LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                                        WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                                        AND as_act.Flat_Id IN ('$flt_ids') AND as_sb.Asgn_SB_Status=1 
                                        AND de.DE_Date_Entry='$de_date' AND as_sb.SB_Id='$sb_id' AND act_cat.Dept_Id='$dept_id'";
                            // echo $sb_chk_tot.'<br>';
                            $sb_chk_tot_run=mysqli_query($connection,$sb_chk_tot); 
                            if(mysqli_num_rows($sb_chk_tot_run)>1){
                                $row_sb_chk=mysqli_fetch_assoc($sb_chk_tot_run);
                                $count=$row_sb_chk['cnt'];
                                $total=$row_sb_chk['sb_tot'];
                                $sb_cnt_per_act=$count/$total;
                                $total_sb=$total_sb+$sb_cnt_per_act;  
                                decFormat($sb_cnt_per_act);
                                $sb_count.=$sb_cnt_per_act.'<br>';
                            }
                            else{// show what is entered  
                                $total_sb=$total_sb+$row_sb['Asgn_SB_Qty'];  
                                $sb_count.=$row_sb['Asgn_SB_Qty'].'<br>';
                            }
                        }
                    }
                    // manpower
                    $q_mp="SELECT * FROM asgn_mp as as_mp 
                           LEFT JOIN daily_entry as de on de.DE_Id=as_mp.DE_Id
                           LEFT JOIN manpower as mp on mp.MP_Id=as_mp.MP_Id
                            WHERE as_mp.Asgn_MP_Status=1 and de.DE_Id='$de_id'";
                    $q_mp_run=mysqli_query($connection,$q_mp);
                    $mp_name=''; $mp_count='';
                    if(mysqli_num_rows($q_mp_run)>0){
                        while($row_mp=mysqli_fetch_assoc($q_mp_run)){
                            $MP_Id=$row_mp['MP_Id'];
                            $mp_name.=$row_mp['MP_Name'].'<br>';
                            //check how many total 
                            $mp_chk_tot="SELECT COUNT(as_mp.MP_Id) as cnt, SUM(as_mp.Asgn_MP_Total) as mp_tot
                                        FROM asgn_mp as as_mp
                                        LEFT JOIN daily_entry as de on as_mp.DE_Id=de.DE_Id
                                        LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                                        LEFT JOIN activity_category as act_cat on act_cat.Act_Cat_Id=as_act.Act_Cat_Id
                                        WHERE de.DE_Status=1 AND as_act.Asgd_Act_Status=1 
                                        AND as_act.Flat_Id IN ('$flt_ids') AND as_mp.Asgn_MP_Status=1 
                                        AND de.DE_Date_Entry='$de_date' AND as_mp.MP_Id='$MP_Id' AND act_cat.Dept_Id='$dept_id'";
                            // echo $mp_chk_tot.'<br>';
                            $mp_chk_tot_run=mysqli_query($connection,$mp_chk_tot); 
                            if(mysqli_num_rows($mp_chk_tot_run)>1){
                                $row_mp_chk=mysqli_fetch_assoc($mp_chk_tot_run);
                                $count=$row_mp_chk['cnt'];
                                $total=$row_mp_chk['mp_tot'];
                                $mp_cnt_per_act=$count/$total;
                                $total_mp=$total_mp+$mp_cnt_per_act;  
                                decFormat($mp_cnt_per_act);
                                $mp_count.=$mp_cnt_per_act.'<br>';
                            }
                            else{// show what is entered  
                                $total_mp=$total_mp+$row_mp['Asgn_MP_Qty'];  
                                $mp_count.=$row_mp['Asgn_MP_Qty'].'<br>';
                            }
                        }
                    }
            ?>
                        <td><?php echo decFormat($de_pct_done);?></td>
                        <td><?php echo date("M j, Y", strtotime($de_date));?></td>
                        <td>
                            <span >
                                <?php echo $emp_names;?>
                            </span>
                        </td>
                        <td><?php echo decFormat($emp_row_cnt);?></td>
                        <td><?php echo $sb_name;?></td>
                        <td><?php echo $sb_count;?></td>
                        <td><?php echo $mp_name;?></td>
                        <td><?php echo $mp_count;?></td>
                        <td></td>
                        <td></td>
                </tr>
            <?php
                }
                $villa_tot=$total_emp+$total_mp+$total_sb;
                ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo decFormat($total_emp);?></td>
                        <td></td>
                        <td><?php echo decFormat($total_sb);?></td>
                        <td></td>
                        <td><?php echo decFormat($total_mp)?></td>
                        <td class="font-weight-bold"><?php echo decFormat($villa_tot);?></td>
                        <td><?php echo $bgt?></td>
                    </tr>
                <?php
                $total_bgt=$total_bgt+$bgt;
                $total_worker=$total_worker+$villa_tot;
            }
        }
    }
    $ave_comp=$total_pct_done/$v_cnt;
    $ave_bgt=$total_bgt/$v_cnt;
    decFormat($ave_bgt);
?>    
                        <tr>
                            <td>Average</td>
                            <td class="text-primary font-weight-bold"><?php echo round($ave_comp, 2);?></td><!-- ave percentage -->
                            <td colspan="7"></td>
                            <td class="text-primary font-weight-bold"><?php echo decFormat($total_worker); ?></td><!-- total worker -->
                            <td class="text-primary font-weight-bold "><?php echo $ave_bgt;?></td><!-- total budget -->
                        </tr>
                    </tbody>
                </table>
                <?php 
                    // echo $total_bgt.'<br>';
                    $pf=null;
                    $ave_eq=$ave_comp*.01;
                    $cum_bgt=$total_bgt*$ave_eq;
                    $pf=$cum_bgt/$total_worker;
                    $pf=round($pf,2);
                    $total_worker=decFormat($total_worker);
                    echo 'Actual Manpower: '.$total_worker.'<br>';
                    echo 'Cummulated Budgeted Manpower Against Current Progress: '.$cum_bgt.'<br>';
                    echo 'PF: '.$pf;
                ?>
            </div>
            <div align="right">
                <button name="" id="btnExcel" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
    </div>
</div>
<!-- Modal Record -->
<div class="modal fade bd-example-modal-md" id="editProg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <!-- MODAL TITLE -->
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-history" aria-hidden="true"></i> Records</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- THE FORM -->
        <div id="history">
        </div>
        <!-- END FORM -->
      </div>
    </div>
  </div>
</div>
<!-- End Modal Record -->
<script>
 $(document).ready(function(){
    $(document).on('click', '.record', function() {
        var emp_id= $(this).next().val();
        var date =$(this).prev().val();
        $.ajax({
            type:'POST',
            url: 'ajax_emp_manage.php',
            data:{
                'emp_id' : emp_id,
                'date' : date,
            },
            success: function(data){
                $('#history').html(data);
                $('#editProg').modal("show");
                
            }
        });
    });
});
$(document).ready(function(){
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#tbl1'));
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>