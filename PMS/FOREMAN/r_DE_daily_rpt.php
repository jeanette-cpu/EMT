<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php'); 
error_reporting(0);
?>
<!-- Download Table as Excel -->
<script src="table2excel.js"> </script>
<?php
if(isset($_POST['Daily_Btn'])){
    $prj_id = $_POST['prj_id'];

    $q_prj_name="SELECT * from project where Prj_Id='$prj_id'";
    $run=mysqli_query($connection, $q_prj_name);
    $row = mysqli_fetch_assoc($run);
    $prj_name= $row['Prj_Code'].' - '.$row['Prj_Name'];
    $prj_loc = $row['Prj_Emirate_Location'].', '.$row['Prj_Location_Desc'];

    date_default_timezone_set('Asia/Dubai');
    $Date = date('d.m.Y');
    $date_today = date('Y-m-d');
    //get prj cat
    $category = $row['Prj_Category'];
    // get flat ids
    if($category=='Building')
    {
        // get building assigned
        $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
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
                }
            }
        }
    }
    elseif($category=='Villa'){
        // get villas assigned
        $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
        $q_villa_run = mysqli_query($connection, $q_villa);
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
                        }
                    }
                }
            }
        }
    }
}
?>
<div class="col-xl-12 col-lg-12">
    <div class="card-body" id="daily_report">
    <table id="tbl1" class="table table-sm table-bordered" style="width:100%;  border-spacing: 0px;  border-collapse: collapse;">
        <tr>
            <td class="th text-center">Project Name</td>
            <td colspan="2"><?php echo $prj_name?></td>
            <td class="text-center"></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td class="text-center">Location</td>
            <td colspan="2"><?php echo $prj_loc?></td>
            <td class="text-center">Date</td>
            <td colspan="2"><?php echo $Date?></td>
        </tr>
        <tr class=" text-danger">
            <td class="text-center" colspan="6">EMT ELECTROMECHANICAL WORK LLC</td> 
        </tr>
        <tr class=" text-danger">
            <td class="text-center" colspan="6"> DAILY WORK REPORT</td>
        </tr>
        <?php
            
            $dept_q ="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id IN ('1','2','3')";
            $dept_q_run = mysqli_query($connection, $dept_q);
            $total_per_dept=0;$d_mp_ctn=0; $tot_mp=0; $d_sb_ctn=0;$tot_sb=0;
            
            if(mysqli_num_rows($dept_q_run)>0)
            {
                while($row1 = mysqli_fetch_assoc($dept_q_run))
                {
                    $dept_id = $row1['Dept_Id'];
                ?>
        <tr>
            <td class="text-danger text-center" colspan="6"><h5><?php echo strtoupper($row1['Dept_Name']) ?></h5></td>
        </tr>
            <td class="text-center" style="color:black" >NO</td>
            <td class="text-center" style="color:black">Description </td>
            <td class="text-center" style="color:black">Location</td>
            <td  class="text-center" style="color:black">Manpower</td>
            <td class="text-center" style="color:black">Remarks</td>
                <?php
                    if(isset($flt_ids)){
                        $DE_q ="SELECT * FROM daily_entry 
                                LEFT JOIN assigned_activity on assigned_activity.Asgd_Act_Id = daily_entry.Asgd_Act_Id 
                                LEFT JOIN activity_category on activity_category.Act_Cat_Id = assigned_activity.Act_Cat_Id 
                                WHERE date(DE_Date_Entry)='$date_today' AND DE_Status=1 
                                AND activity_category.Dept_Id='$dept_id' and Flat_Id in ('$flt_ids')";
                        $DE_q_run = mysqli_query($connection,$DE_q);                   
                    }
                    else{
                        $DE_q ="SELECT * FROM daily_entry where DE_Status=3";
                        $DE_q_run = mysqli_query($connection,$DE_q); 
                    }
                    $overall_total=0; $s=0;
                    if(mysqli_num_rows($DE_q_run)>0){
                        $i = 1;
                        while($row3 = mysqli_fetch_assoc($DE_q_run)){                          
                            $act_id = $row3['Act_Id'];
                            // activity name
                            $q1 = "SELECT * FROM activity where Act_Id='$act_id'";
                            $q_run1=mysqli_query($connection, $q1);
                            $row4 = mysqli_fetch_assoc($q_run1);
                            // Count Manpower
                            $DE_Id = $row3['DE_Id'];
                            $q5="SELECT COUNT(Asgd_Worker_Id) AS ctn  FROM asgn_worker where DE_Id='$DE_Id' and Asgd_Worker_Status=1";
                            $q5_run=mysqli_query($connection, $q5);
                            $row7 = mysqli_fetch_assoc($q5_run);
                            // COUNT MP
                            $q_mp_count="SELECT SUM(Asgn_MP_Qty) AS mp_c  FROM asgn_mp where DE_Id='$DE_Id' and Asgn_MP_Status=1"; 
                            $q_mp_count_run=mysqli_query($connection, $q_mp_count);
                            $mp_row = mysqli_fetch_assoc($q_mp_count_run);
                            $mp_ctn= $mp_row['mp_c']; 
                            $d_mp_ctn = $d_mp_ctn + $mp_ctn;
                            // COUNT SB
                            $q_sb_count="SELECT SUM(Asgn_SB_Qty) AS sb_c  FROM asgn_subcon where DE_Id='$DE_Id' and Asgn_SB_Status=1";
                            $q_sb_count_run=mysqli_query($connection, $q_sb_count);
                            $sb_row = mysqli_fetch_assoc($q_sb_count_run);
                            $sb_ctn = $sb_row['sb_c']; 
                            $d_sb_ctn = $d_sb_ctn + $sb_ctn;
                            //Location
                            $flt_id = $row3['Flat_Id'];
                            $flat_q ="SELECT * FROM flat where Flat_Id='$flt_id'";
                            $flat_q_run = mysqli_query($connection,$flat_q);
                            $row5 = mysqli_fetch_assoc($flat_q_run);
                            //LEVEL
                            $lvl_id = $row5['Lvl_Id'];
                            $level_q = "SELECT * FROM level where Lvl_Id='$lvl_id'";
                            $level_q_run = mysqli_query($connection,$level_q);
                            $row6= mysqli_fetch_assoc($level_q_run);
                            ?>
                <tr>
                    <td class="text-center"><?php echo $i; $i++; ?></td>
                    <td><?php $act_name = $row4['Act_Code'].' '.$row4['Act_Name']; echo $act_name?></td>
                    <td class="text-center"><?php echo $row6['Lvl_Name'].' '.$row5['Flat_Code'].' '.$row5['Flat_Name']?></td>
                    <td class="text-center"><?php $ctn = $row7['ctn']+$mp_ctn+ $sb_ctn; echo $ctn;
                        $total_per_dept = $total_per_dept + $ctn;
                        $tot_mp = $tot_mp + $d_mp_ctn;
                        $tot_sb = $tot_sb + $d_sb_ctn;
                        $s = $s + $ctn; ?></td>
                    <td></td>
                </tr>
                        <?php       
                        }
                    }
                    ?>
                <tr>
                    <td></td>
                    <td class="text-center" colspan="2">TOTAL</td>
                    <td class="text-center"><?php echo $s;?></td>
                    <td></td>
                </tr>   
                    <?php                      
                } 
            }
            else{
                echo "no entry";
            }
            $overall_total = $overall_total + $total_per_dept;
        ?>
                <tr>
                    <td class="text-center text-danger" colspan="3"></td>
                    <td class="text-center" id="tot_val" ><?php echo $overall_total; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center text-danger" colspan="5"><h5>TOTAL EMT MANPOWER</h5></td>
                </tr>
                <tr>
                    <td class="text-center" >Designation</td>
                    <td class="text-center">Manpower(Outsource)</td>
                    <td class="text-center">Subcontractor(Outsource)</td>
                    <td class="text-center">EMT</td>
                    <td class="text-center"  >Total</td>
                </tr>
                <?php
                // select all user_id assign to prj
                $q_users ="SELECT u.USER_ID, u.Dept_Id FROM asgn_emp_to_prj as asgn_emp 
                            LEFT JOIN users as u on u.USER_ID=asgn_emp.User_Id
                            WHERE asgn_emp.Asgd_Emp_to_Prj_Status=1 and asgn_emp.Prj_Id=$prj_id";
                $q_users_run = mysqli_query($connection,$q_users);
                if(mysqli_num_rows($q_users_run)>0){
                    while($row_users = mysqli_fetch_assoc($q_users_run)){
                        $user_id=$row_users['USER_ID']; 
                        $u_id_arr[] = $user_id;
                        $user_dept= $row_users['Dept_Id'];
                        if($user_dept==1){ //ELECTRICAL 
                            $elec_uid_arr[]=$user_id;
                        }
                        elseif($user_dept==2){//PLUMB
                            $plumb_uid_arr[]=$user_id;
                        }
                        elseif($user_dept==3){ //HVAC
                            $hvac_uid_arr[]=$user_id;
                        }
                    }
                }
                $user_ids = implode("','", $u_id_arr);
                if($elec_uid_arr){
                    $elec_user_ids = implode("','", $elec_uid_arr);
                }
                if($plumb_uid_arr){
                    $plumb_user_ids = implode("','", $plumb_uid_arr);
                }
                if($hvac_uid_arr){
                    $hvac_user_ids = implode("','", $hvac_uid_arr);
                }

                $q_elect_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as e_tot FROM daily_entry as DE 
                                LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                WHERE DE.DE_Status=1 AND DE.User_Id in ('$elec_user_ids') AND AS_W.Asgd_Worker_Status=1 
                                AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' 
                                AND AS_ACT.Flat_Id in ('$flt_ids') ";
                $q_elect_total_run = mysqli_query($connection,$q_elect_total);
                $row_e_total = mysqli_fetch_assoc($q_elect_total_run);
                // plumbing p_tot
                $q_plumb_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as p_tot FROM daily_entry as DE 
                                    LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                    LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                    LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                    WHERE DE.DE_Status=1 AND DE.User_Id in ('$plumb_user_ids') AND AS_W.Asgd_Worker_Status=1 AND 
                                    AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' 
                                    AND AS_ACT.Flat_Id in ('$flt_ids') ";
                $q_plumb_total_run = mysqli_query($connection,$q_plumb_total);
                $row_p_total = mysqli_fetch_assoc($q_plumb_total_run);
                // HVAC hvact_tot
                $q_hvac_total1 = "SELECT COUNT(DISTINCT EMP.EMP_ID) as hvac_tot1 FROM daily_entry as DE 
                                LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                WHERE DE.DE_Status=1 AND DE.User_Id in ('$hvac_user_ids') AND AS_W.Asgd_Worker_Status=1 AND 
                                AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today'  
                                AND AS_ACT.Flat_Id in ('$flt_ids')  ";
                $q_hvac_total_run1 = mysqli_query($connection,$q_hvac_total1);
                $row_hvac_total1 = mysqli_fetch_assoc($q_hvac_total_run1);
                //ELECTRICAL
               //mp
                $q_mp_elect="SELECT SUM(Asgn_MP_Total) as mp_e FROM asgn_mp LEFT JOIN daily_entry as de on de.DE_Id=asgn_mp.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('1','2','3','4','5') and de.User_Id in ('$elec_user_ids') AND asgn_mp.Asgn_MP_Status=1 AND de.DE_Status=1";
                $q_mp_elect_run=mysqli_query($connection,$q_mp_elect);
                $row_mp_e=mysqli_fetch_assoc($q_mp_elect_run); 
                //sb
                $q_sb_elect="SELECT SUM(Asgn_SB_Total) as sb_e FROM asgn_subcon 
                            LEFT JOIN daily_entry as de on de.DE_Id=asgn_subcon.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('1','2','3','4','5') and de.User_Id in ('$elec_user_ids') AND asgn_subcon.Asgn_SB_Status=1 AND de.DE_Status=1";
                $q_sb_elect_run=mysqli_query($connection,$q_sb_elect);
                $row_sb_e=mysqli_fetch_assoc($q_sb_elect_run); 
                //PLUMBING
                //mp
                $q_mp_plumb="SELECT SUM(Asgn_MP_Total) as mp_p FROM asgn_mp LEFT JOIN daily_entry as de on de.DE_Id=asgn_mp.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('6','7','8','9','10') and de.User_Id in ('$plumb_user_ids') AND asgn_mp.Asgn_MP_Status=1 AND de.DE_Status=1";
                $q_mp_plumb_run=mysqli_query($connection,$q_mp_plumb);
                $row_mp_p=mysqli_fetch_assoc($q_mp_plumb_run); $mp_p =$row_mp_p['mp_p'];
                //sb
                $q_sb_plumb="SELECT SUM(Asgn_SB_Total) as sb_p FROM asgn_subcon 
                            LEFT JOIN daily_entry as de on de.DE_Id=asgn_subcon.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('6','7','8','9','10') and de.User_Id in ('$plumb_user_ids') AND asgn_subcon.Asgn_SB_Status=1 AND de.DE_Status=1";
                $q_sb_plumb_run=mysqli_query($connection,$q_sb_plumb);
                $row_sb_p=mysqli_fetch_assoc($q_sb_plumb_run); $sb_p=$row_sb_p['sb_p'];
                //HVAC
                //mp
                $q_mp_hvc="SELECT SUM(Asgn_MP_Total) as mp_hvc FROM asgn_mp LEFT JOIN daily_entry as de on de.DE_Id=asgn_mp.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('11','12','13','14','15') and de.User_Id in ('$hvac_user_ids') AND asgn_mp.Asgn_MP_Status=1 AND de.DE_Status=1";
                $q_mp_hvc_run=mysqli_query($connection,$q_mp_hvc);
                $row_mp_hvc=mysqli_fetch_assoc($q_mp_hvc_run);$mp_hvc=$row_mp_hvc['mp_hvc'];
                //sb
                $q_sb_hvc="SELECT SUM(Asgn_SB_Total) as sb_hvc FROM asgn_subcon 
                            LEFT JOIN daily_entry as de on de.DE_Id=asgn_subcon.DE_Id
                            LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id=de.Asgd_Act_Id
                            WHERE de.DE_Date_Entry='$date_today' AND as_act.Act_Cat_Id IN ('11','12','13','14','15') and de.User_Id in ('$hvac_user_ids') AND asgn_subcon.Asgn_SB_Status=1 AND de.DE_Status=1";
                $q_sb_hvc_run=mysqli_query($connection,$q_sb_hvc);
                $row_sb_hvc=mysqli_fetch_assoc($q_sb_hvc_run); $sb_hvc=$row_sb_hvc['sb_hvc'];
            
                ?>
                <tr>
                    <td>ELECTRICAL</td>
                    <td class="text-right"><?php echo $mp_e= $row_mp_e['mp_e'];?></td>
                    <td class="text-right"><?php echo $sb_e=$row_sb_e['sb_e'];?></td>
                    <td class="text-right"><?php $e = $row_e_total['e_tot']; echo $e?></td>
                    <td class="text-center"><?php echo $elect_t= $e+$mp_e+$sb_e; ?></td>
                </tr>
                <tr>
                    <td >PLUMBING</td>
                    <td class="text-right"><?php echo $mp_p?></td>
                    <td class="text-right"><?php echo $sb_p?></td>
                    <td class="text-right"><?php $p = $row_p_total['p_tot']; echo $p ?></td>
                    <td class="text-center"><?php echo $plumb_t =$p+$mp_p+$sb_p?></td>
                </tr>
                <tr>
                    <td >HVAC</td>
                    <td class="text-right"><?php echo $mp_hvc?></td>
                    <td class="text-right" width="10%"><?php echo $sb_hvc?></td>
                    <td class="text-right"><?php $hvac = $row_hvac_total1['hvac_tot1']; echo $hvac?></td>
                    <td class="text-center"><?php echo $hvc_t=$mp_hvc+$sb_hvc+$hvac?></td>
                </tr>
                <tr>
                    <td>Total</td> </div>
                    <td class="text-right"><?php echo $mp_t=$mp_hvc+$mp_p+$mp_e?></td>
                    <td class="text-right"><?php echo $sb_t=$sb_hvc+$sb_p+$sb_e?></td>
                    <td class="text-right"><?php $d_total = $e+$hvac+$p;echo $d_total?></td>
                    <td class="text-center"><?php echo $o_total=$mp_t+$sb_t+$d_total?></td>
                </tr>
    </table>
        <div align="right">
            <button name="" id="btnExcel" class="btn btn-success mt-2">
                <i class="fa fa-download" aria-hidden="true"></i>
                Download
            </button>  
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