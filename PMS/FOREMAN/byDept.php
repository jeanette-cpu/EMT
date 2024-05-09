<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
// error_reporting(0);
date_default_timezone_set('Asia/Dubai');
if(isset($_POST['byDept'])){
    $dept_id=$_POST['dept_id'];
    $dept_name=$_POST['dept_name'];
    $flt_ids=$_POST['flat_ids'];
    $prj_id=$_POST['prj_id'];
    $category=$_POST['prj_cat'];
    $prj_name=$_POST['prj_name'];
    $fcount=$_POST['fcount'];
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
            <div class="table-responsive ">
                <h5 class="text-primary"><?php echo $dept_name;?> Activities</h5>
                <table class="table table-bordered table-striped table-sm" id="tbl1" width="100%" cellspacing="0">
                    <thead>
                        <th>Activity Name</th>
                        <!-- <th>Date</th> -->
                        <th>Avg Budget/Villa</th>
                        <th>EMT</th>
                        <th>SB</th>
                        <th>MP</th>
                        <th>Total MP Consumed (B)</th>
                        <th>Total Budget/Act</th>
                        <th>Villa Finished/Working</th>
                        <th>Avg Progress</th>
                        <th>Cummulative Budget MP against Current Progress (A)</th>
                        <th>Variance (A-B)</th>
                        <th>PF</th>
                    </thead>
                    <tbody>
                <?php
                    // selecting activities that has budget assigned
                    $dept_act="SELECT act.Act_Id, act.Act_Code, act.Act_Name, ft_act.Flat_Bgt_Manpower FROM flat_type AS ft 
                                LEFT JOIN flat_type_asgn_act as ft_act on ft.Flat_Type_Id = ft_act.Flat_Type_Id 
                                LEFT JOIN activity as act on act.Act_Id =ft_act.Act_Id
                                WHERE ft.Prj_Id='$prj_id' AND ft.Flat_Type_Status=1 
                                AND ft_act.Flt_Asgn_Act_Status=1 AND act.Act_Status=1 
                                AND act.Dept_Id='$dept_id' AND ft_act.Flat_Bgt_Manpower!=0 
                                GROUP BY act.Act_Id";
                    $dept_act_run=mysqli_query($connection,$dept_act);
                    $tot_mp=0;$tot_sb=0;$tot_emt=0;
                    $tot_bgt_per_villa=0;$tot_flt_in_progress=0;$tot_ave=0; $ave_cnt=0; $ave_prog=0;$tot_cum_bgt=0;
                    if(mysqli_num_rows($dept_act_run)>0){ // actvities
                        while($row_act=mysqli_fetch_assoc($dept_act_run)){
                            $ave_progress_act=''; $flat_in_progress=0;  $pf=''; $cum_bgt=''; $variance=''; $bgt_per_act=0; $flt_cnt=0;
                            $act_bgt=0;$total_flt_cnt=0;$ave_mp_per_flt=0;
                            $act_id=$row_act['Act_Id'];
                            $act_code=$row_act['Act_Code'];
                            $act_name=$row_act['Act_Name'];
                            //search ave per activity 
                            // budget per activity $bgt_per_act
                            $q_ft="SELECT count(ft_flt.Flat_Id) as fcount,ft.Flat_Type_Id FROM flat_type as ft
                                    LEFT JOIN flat_asgn_to_type as ft_flt on ft.Flat_Type_Id=ft_flt.Flat_Type_Id
                                    WHERE ft.Prj_Id ='$prj_id' AND ft.Flat_Type_Status=1 AND ft_flt.Flat_Asgd_Status=1 AND ft.Flat_Bgt_Manpower>0
                                    GROUP BY ft.Flat_Type_Id";
                            $q_ft_run=mysqli_query($connection,$q_ft);
                            if(mysqli_num_rows($q_ft_run)>0){
                                while($row_ft=mysqli_fetch_assoc($q_ft_run)){
                                     //search no flat
                                    $flt_cnt=$row_ft['fcount'];
                                    $type_id=$row_ft['Flat_Type_Id'];
                                    $q_act_bgt="SELECT Flat_Bgt_Manpower as act_bgt FROM flat_type_asgn_act 
                                                WHERE Act_Id='$act_id' AND Flat_Type_Id ='$type_id' AND Flt_Asgn_Act_Status=1 GROUP BY Act_Id" ;
                                    $q_act_bgt_run=mysqli_query($connection,$q_act_bgt);
                                    if(mysqli_num_rows($q_act_bgt_run)>0){
                                        while($row_act_bgt=mysqli_fetch_assoc($q_act_bgt_run)){
                                            $act_bgt=$row_act_bgt['act_bgt'];
                                        }
                                    }
                                    $bgt_per_act=$bgt_per_act+($flt_cnt*$act_bgt);
                                    $total_flt_cnt=$total_flt_cnt+$flt_cnt;

                                }
                                $ave_mp_per_flt=$bgt_per_act/$total_flt_cnt;
                            }
                            $emp_bgt_per_villa=$row_act['Flat_Bgt_Manpower'];
                            $asgn_act="SELECT * FROM  assigned_activity 
                                        WHERE Asgd_Act_Status=1 AND Act_Id='$act_id' AND Flat_Id IN ('$flt_ids')";
                            $asgn_act_run=mysqli_query($connection,$asgn_act);
                            $total_emp=0; $total_sb=0;$total_mp=0;$act_tot=0;$sum_percentage=0;
                            
                            if(mysqli_num_rows($asgn_act_run)>0){
                                while($row_asc=mysqli_fetch_assoc($asgn_act_run)){
                                    $asgd_act_id=$row_asc['Asgd_Act_Id'];
                                    $flat_id=$row_asc['Flat_Id'];
                                    $pct_done=$row_asc['Asgd_Pct_Done'];//current activity progress/villa
                                    //search from daily entry days activity updated
                                    $q_de="SELECT * FROM daily_entry WHERE DE_Status=1 AND Asgd_Act_Id='$asgd_act_id'";
                                    $q_de_run=mysqli_query($connection,$q_de);
                                    if(mysqli_num_rows($q_de_run)>0){
                                        $flat_in_progress++;
                                        $sum_percentage=$sum_percentage+$pct_done;
                                        while($row_de=mysqli_fetch_assoc($q_de_run)){
                                            $de_id=$row_de['DE_Id'];
                                            $de_date=$row_de['DE_Date_Entry'];
                                            //emt workers
                                            $q_emp="SELECT * FROM asgn_worker as as_w
                                                    LEFT JOIN employee as emp on emp.Emp_Id=as_w.Emp_Id
                                                    WHERE as_w.Asgd_Worker_Status=1 AND as_w.DE_Id='$de_id'";
                                            $q_emp_run=mysqli_query($connection,$q_emp);  
                                            if(mysqli_num_rows($q_emp_run)>0){
                                                while($row_1=mysqli_fetch_assoc($q_emp_run)){
                                                    $emp_id=$row_1['Emp_Id'];
                                                    //check/count if an employee code worked on the "SAME" activity or "OTHER" on a single day on other villa
                                                    $q_chk_emp="SELECT COUNT(as_w.Emp_Id) as dup FROM asgn_worker as as_w
                                                                LEFT JOIN daily_entry as de on de.DE_Id=as_w.DE_Id
                                                                WHERE as_w.Asgd_Worker_Status=1 AND DE_Date_Entry='$de_date' AND de.DE_Status=1 AND as_w.Emp_Id='$emp_id'";
                                                    $q_chk_emp_run=mysqli_query($connection,$q_chk_emp);
                                                    if(mysqli_num_rows($q_chk_emp_run)>0){
                                                        $row_chk=mysqli_fetch_assoc($q_chk_emp_run);
                                                        $count=$row_chk['dup'];
                                                        if($count==1){
                                                            $total_emp++;
                                                        }
                                                        else{
                                                            // echo $count.'<br>';
                                                            $div=1/$count;
                                                            $total_emp=$total_emp+ $div;//total employee worked in this activity
                                                        }
                                                    }
                                                    //total 
                                                }
                                            }
                                            //sb
                                            $q_sb="SELECT * FROM asgn_subcon as as_sb 
                                                    LEFT JOIN daily_entry as de on de.DE_Id=as_sb.DE_Id
                                                    LEFT JOIN subcontractor as sb on sb.SB_Id=as_sb.SB_Id
                                                    WHERE as_sb.Asgn_SB_Status=1 and de.DE_Id='$de_id'";
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
                                                    }
                                                    else{// show what is entered  
                                                        $total_sb=$total_sb+$row_sb['Asgn_SB_Qty'];  
                                                    }
                                                }
                                            }
                                            //mp
                                            $q_mp="SELECT * FROM asgn_mp as as_mp 
                                                    LEFT JOIN daily_entry as de on de.DE_Id=as_mp.DE_Id
                                                    LEFT JOIN manpower as mp on mp.MP_Id=as_mp.MP_Id
                                                    WHERE as_mp.Asgn_MP_Status=1 and de.DE_Id='$de_id'";
                                            $q_mp_run=mysqli_query($connection,$q_mp); $mp_count='';
                                            if(mysqli_num_rows($q_mp_run)>0){
                                                while($row_mp=mysqli_fetch_assoc($q_mp_run)){
                                                    $MP_Id=$row_mp['MP_Id'];
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
                                                    }
                                                    else{// show what is entered  
                                                        $total_mp=$total_mp+$row_mp['Asgn_MP_Qty'];  
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $act_tot=$total_emp+$total_mp+$total_sb;
                            // $bgt_per_act=$fcount*$emp_bgt_per_villa;
                            if($flat_in_progress){
                                $ave_progress_act=$sum_percentage/$flat_in_progress;

                                $pf=null;
                                $total_bgt=$flat_in_progress*$emp_bgt_per_villa;
                                $ave_eq=$ave_progress_act*.01;
                                $cum_bgt=$total_bgt*$ave_eq;
                                if($act_tot){
                                    $pf=$cum_bgt/$act_tot;
                                    $pf=round($pf,2);
                                    $variance=$cum_bgt-$act_tot;
                                    $variance=decFormat($variance);
                                }
                                else{
                                    $pf=null;
                                }
                            }
                            else{
                                $ave_progress_act='';
                            }
                            $sum_percentage=0;
                ?>
                        <tr>
                            <td class="text-left">
                                <form action="byAct.php" method="post">
                                    <input type="hidden" name="act_id" value="<?php echo $act_id;?>">
                                    <input type="hidden" name="act_name" value="<?php echo $act_code.' '.$act_name;?>">
                                    <input type="hidden" name="dept_id" value="<?php echo $dept_id;?>">
                                    <input type="hidden" name="dept_name" value="<?php echo $dept_name;?>">
                                    <input type="hidden" name="prj_id" value="<?php echo $prj_id;?>">
                                    <input type="hidden" name="prj_name" value="<?php echo $prj_name;?>">
                                    <input type="hidden" name="prj_cat" value="<?php echo $category;?>">
                                    <input type="hidden" name="bgt" value="<?php echo $emp_bgt_per_villa?>">
                                    <input type="hidden" name="flat_ids" value="<?php echo $flt_ids?>">
                                    <button type="submit" name="byAct" class="btn btn-link text-secondary" onclick="this.form.target='_blank';return true;">
                                        <?php echo $act_code.' '.$act_name;?>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo decFormat($ave_mp_per_flt);?></td> <!-- ave budget -->
                            <td><?php if($total_emp){ // emt employee
                                    echo decFormat($total_emp);
                                }?>
                            </td>
                            <td><?php if($total_sb){ // subcon
                                    echo decFormat($total_sb);
                                } ?>
                            </td>
                            <td><?php if($total_mp){echo decFormat($total_mp);}?> <!-- manpower supply -->
                            </td>
                            <td><?php if($act_tot){echo decFormat($act_tot);}?> <!-- total manpower consumed -->
                            <td><?php if($bgt_per_act){echo decFormat($bgt_per_act);}?> <!-- total manpower consumed -->
                            <td><?php if($flat_in_progress){echo decFormat($flat_in_progress);}?>
                            <td><?php echo decFormat($ave_progress_act);?></td>
                            <td><?php echo decFormat($cum_bgt);?></td>
                            <td><?php if($variance){
                                    echo abs($variance);
                                }?>
                            </td>
                            <td><?php echo $pf;?></td>
                        </tr>
                    <?php
                                if($ave_progress_act){
                                    $tot_ave=$tot_ave+$ave_progress_act;
                                    $ave_cnt++;
                                }
                                if($cum_bgt){
                                    $tot_cum_bgt=$tot_cum_bgt+$cum_bgt;
                                }
                                $tot_emt=$tot_emt+$total_emp;
                                $tot_sb=$tot_sb+$total_sb;
                                $tot_mp=$tot_mp+$total_mp;
                                $tot_flt_in_progress=$tot_flt_in_progress+$flat_in_progress;
                                $total_emp=0;$total_sb=0;$total_mp=0;$act_tot=0;$flat_in_progress=0; $pf=''; $cum_bgt='';$variance='';
                                $tot_bgt_per_villa=$tot_bgt_per_villa+$emp_bgt_per_villa; //total budget per villa per department
                            }
                        }  
                        $total_bgt_dept=$fcount*$tot_bgt_per_villa;
                        $ave_prog=$tot_ave/$ave_cnt;
                        $total_manpower=$tot_emt+$tot_sb+$tot_mp;
                        $fvariance=$tot_cum_bgt-$total_manpower;
                        //computing pf
                        $pf=null;
                        $f_ave_eq=$ave_prog*.01;
                        $pf=$tot_cum_bgt/$total_manpower;
                    ?>
                        <tr class="d-none">
                            <td></td>
                            <td><?php echo decFormat($tot_bgt_per_villa); ?></td>
                            <td><?php echo decFormat($tot_emt);?></td>
                            <td><?php echo decFormat($tot_sb);?></td>
                            <td><?php echo decFormat($tot_mp);?></td>
                            <td><?php echo decFormat($total_manpower);?></td>  <!--B-->
                            <td><?php echo number_format(decFormat($total_bgt_dept));?></td>
                            <td><?php echo $tot_flt_in_progress;?></td>
                            <td><?php echo decFormat($ave_prog).'%';?></td> 
                            <td><?php echo decFormat($tot_cum_bgt)?></td> <!--A-->
                            <td><?php echo decFormat($fvariance)?></td> <!-- variance-->
                            <td><?php echo decFormat($pf);?></td> <!--pf-->
                        </tr>
                    </tbody>
                </table>
                <div align="right">
                    <button name="" id="btnExcel" class="btn btn-success mt-2">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Download
                    </button>  
                </div>
            </div>
        </div>
    </div>
</div>
<script>
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