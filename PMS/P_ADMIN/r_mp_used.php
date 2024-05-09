<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/prj_admin_navbar.php');
error_reporting(0);
?>
<!-- Download Table as Excel -->
<script src="table2excel.js"> </script>
<div class="col-xl-12 col-lg-12">
    <h5 class="m-0 font-weight-normal text-primary mb-4">Generate Manpower Report: <span class="font-weight-normal text-dark"><?php echo $prj_name?></span></h5> 
    <form action="r_mp_used.php" method="POST">
        <div class="row">
            <div class="col-4">
                <label for="">Project</label>
                    <select name="prj_id" id="project_opt" class="form-control">
                        <option value="">select project</option>
                    </select>
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
<?php
if(isset($_POST['search'])){
    $prj_id = $_POST['prj_id'];

    $q_prj_name="SELECT * from project where Prj_Id='$prj_id'";
    $run=mysqli_query($connection, $q_prj_name);
    $row = mysqli_fetch_assoc($run);
    $prj_name= $row['Prj_Code'].' - '.$row['Prj_Name'];
    $prj_loc = $row['Prj_Emirate_Location'].', '.$row['Prj_Location_Desc'];

    // select all user_id assign to prj
    $q_users ="SELECT User_Id FROM asgn_emp_to_prj WHERE Asgd_Emp_to_Prj_Status=1 and Prj_Id=$prj_id";
    $q_users_run = mysqli_query($connection,$q_users);
    if(mysqli_num_rows($q_users_run)>0)
    {
        while($row_users = mysqli_fetch_assoc($q_users_run))
        {
            $u_id_arr[] = $row_users['User_Id']; 
        }
    }
    $user_ids = implode("','", $u_id_arr);

    $ffrom=$_POST['from'];
    $tto=$_POST['to'];
    $t2=$tto;
    $from = new DateTime($ffrom);
    $to = new DateTime($tto);
    $t2=new DateTime($t2);

    $monthInterval= new DateInterval('P1M'); // Month
    $monthrange= new DatePeriod($from, $monthInterval ,$to);

    $t2=new DateTime($tto);
    $to = $to->modify( '+1 day' ); 
    $interval = new DateInterval('P1D'); // Days
    $daterange = new DatePeriod($from, $interval ,$to);
    $daterange1 = new DatePeriod($from, $interval ,$t2);
    $e_total=0;$elv_total=0;$p_total=0;$hvac_total=0;$fa_total=0;$ff_total=0;$rtbl='';$colspan=0;$r_total=0;$rt_total=0;

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
// }
foreach($daterange as $date){
    $arr[] = $date->format("F");
    $d++;
    $days++;
} $days=$days+2;
$unique_data = array_unique($arr);

$rtbl.= '
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered table-sm" id="tbl1">
            <thead class="bg-danger">
                <th colspan="'.$days.'" class="text-center text-white">'.$prj_name.'</th>
            </thead>
            <tbody>
                <tr><td></td>';
            foreach($unique_data as $val) {
                foreach($daterange as $date)
                {
                    if($val==$date->format("F"))
                    {
                        $cp++;
                    }
                }
                $rtbl.=  '<td colspan="'.$cp.'" class="text-center text-uppercase text-primary">'.$val.'</td>';
                $cp=0; 
            }
        $rtbl.= '
               <td></td> 
            </tr>
                <tr>
                    <td></td>';
            foreach($daterange as $date){
                $rtbl.=  '<td>'.$date->format("d").'</td>';//day in number
            }
        $rtbl.= '    
                    <td></td>
                </tr>
                <tr>
                <td class="text-center text-primary">Designation</td>';
            foreach($daterange as $date){
                $rtbl.=  '<td>'.$date->format("D").'</td>';//day in word
            }        
            $rtbl.= '  
                    <td>Total</td>
                </tr>
                <tr>
                    <td class="text-dark">ELECTRICAL</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_elect_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as e_tot FROM daily_entry as DE 
                                            LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                            LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                            LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                            WHERE DE.DE_Status=1 AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 
                                            AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') 
                                            AND EMP.EMP_DESIGNATION LIKE '%elect%' ";
                        $q_elect_total_run = mysqli_query($connection,$q_elect_total);
                        $row_e_total = mysqli_fetch_assoc($q_elect_total_run);
                        $rtbl.=  '<td>'.$row_e_total['e_tot'].'</td>';
                        $e_total=$e_total+$row_e_total['e_tot'];
                    }  
            $rtbl.= '
                    <td>'.$e_total.'</td>
                </tr>
                <tr>
                    <td class="text-dark">ELV</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_elv_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as elv_tot FROM daily_entry as DE 
                                        LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                        LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                        LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                        WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 
                                        AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' 
                                        AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%ELV%'";
                        $q_elv_total_run = mysqli_query($connection,$q_elv_total);
                        $row_elv_total = mysqli_fetch_assoc($q_elv_total_run);
                        $rtbl.=  '<td>'.$row_elv_total['elv_tot'].'</td>';
                        $elv_total=$elv_total+$row_elv_total['elv_tot'];
                    }  
           $rtbl.= '
                    <td>'.$elv_total.'</td>
                </tr>
                <tr>
                    <td class="text-dark">PLUMBING</td>';$elv_total=0;
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_plumb_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as p_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%plumb%'";
                        $q_plumb_total_run = mysqli_query($connection,$q_plumb_total);
                        $row_p_total = mysqli_fetch_assoc($q_plumb_total_run);
                        $rtbl.=  '<td>'.$row_p_total['p_tot'].'</td>';
                        $p_total=$p_total+$row_p_total['p_tot'];
                    }  
            $rtbl.= '
                    <td>'.$p_total.'</td>
                </tr>
                <tr>
                    <td class="text-dark">HVAC</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        // HVAC hvact_tot
                        $q_hvac_total1 = "SELECT COUNT(DISTINCT EMP.EMP_ID) as hvac_tot1 FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today'  AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%HVAC%' ";
                        $q_hvac_total_run1 = mysqli_query($connection,$q_hvac_total1);
                        $row_hvac_total1 = mysqli_fetch_assoc($q_hvac_total_run1);
                        // HVAC hvact_tot
                        $q_hvac_total2 = "SELECT COUNT(DISTINCT EMP.EMP_ID) as hvac_tot2 FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today'  AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%A/C%'";
                        $q_hvac_total_run2 = mysqli_query($connection,$q_hvac_total2);
                        $row_hvac_total2 = mysqli_fetch_assoc($q_hvac_total_run2);
                        //PIPE FITTER
                        //pipe fitter
                        $q_pf_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as pf_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%pipe%'";
                        $q_pf_total_run = mysqli_query($connection,$q_pf_total);
                        $row_pf_total = mysqli_fetch_assoc($q_pf_total_run);
                        $hvac = $row_hvac_total1['hvac_tot1']+$row_hvac_total2['hvac_tot2']+$row_pf_total['pf_tot'];
                        $rtbl.=  '<td>'.$hvac.'</td>';
                        $hvac_total=$hvac_total+$hvac;
                    }  
            $rtbl.= '
                    <td>'.$hvac_total.'</td>
                </tr>
                <tr>
                    <td class="text-dark">FIRE ALARM</td>';$hvac_total=0;
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_fa_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as fa_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND DE.User_Id in ('$user_ids') AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE'%alarm%' ";
                        $q_fa_total_run = mysqli_query($connection,$q_fa_total);
                        $row_fa_total = mysqli_fetch_assoc($q_fa_total_run);
                        $rtbl.=  '<td>'.$row_fa_total['fa_tot'].'</td>';
                        $fa_total=$fa_total+$row_fa_total['fa_tot'];
                    }  
            $rtbl.= '
                    <td>'.$fa_total.'</td>
                </tr>
                <tr>
                    <td class="text-dark">FIRE FIGHTING</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_ff_total = "SELECT DISTINCT(EMP.EMP_DESIGNATION) as desig FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') and EMP.EMP_DESIGNATION NOT LIKE '%Elect%'";
                        // echo $q_ff_total;
                        $q_ff_total_run = mysqli_query($connection,$q_ff_total); $ff=0;
                        if(mysqli_num_rows($q_ff_total_run)>0)
                        {
                            while($row_ff = mysqli_fetch_assoc($q_ff_total_run))
                            {
                                $desig = $row_ff['desig'];
                                if(strpos($desig,'fighting')!==false || strpos($desig,'Fighting')!==false || strpos($desig,'Welder')!==false || strpos($desig,'welder')!==false)
                                {
                                    $ff++;
                                }
                            }
                        }
                        $rtbl.=  '<td>'.$ff.'</td>';
                        $ff_total=$ff_total+$ff;
                    }  
            $rtbl.= '
                    <td>'.$ff_total.'</td>
                </tr>
                <tr class="table-danger">
                    <td class="text-center font-weight-bold ">TOTAL EMT</td>';$ff_total=0;
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_dt_total = "SELECT COUNT(DISTINCT(EMP.EMP_ID)) as day_total FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') ";
                        $q_dt_total_run = mysqli_query($connection,$q_dt_total);
                        $row_dt=mysqli_fetch_assoc($q_dt_total_run);$dt=$row_dt['day_total'];
                        $rtbl.=  '<td>'.$dt.'</td>';
                        $rt_total=$rt_total+$dt;
                    }  
            $rtbl.= '
                    <td>'.$rt_total.'</td>
                </tr>';
                
                //MP OUTSOURCE
                //determining if there is mp in range of date
                $q_mp="SELECT * from asgn_mp as asmp
                    LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                    LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                    WHERE de.DE_Date_Entry BETWEEN '$ffrom' AND '$tto' AND  as_act.Flat_Id in ('$flt_ids') 
                    AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 GROUP by asmp.MP_Id";
                    $q_mp_run=mysqli_query($connection,$q_mp);
                if(mysqli_num_rows($q_mp_run)>0)
                {
                    foreach($daterange as $date){
                        $colspan++;
                    }
                    $colspan=$colspan+2;//HEADER MP
                    $rtbl.=' <tr class="table-dark"><td colspan="'.$colspan.'" class="text-center text-white font-weight-bold">MANPOWER OUTSOURCE</td></tr> ';
                    while($row_mp=mysqli_fetch_assoc($q_mp_run)){
                        $mp_id=$row_mp['MP_Id'];$mp_old='';$mp_name=$row_mp['MP_Name']; 
                        $q_dept="SELECT * from department WHERE Dept_Status=1";
                        $q_dept_run=mysqli_query($connection,$q_dept);
                        if(mysqli_num_rows($q_dept_run)>0){
                            while($row_d=mysqli_fetch_assoc($q_dept_run)){
                                $dept_id=$row_d['Dept_Id'];$dept_name=$row_d['Dept_Name'];
                                $q_mp_day="SELECT SUM(asmp.Asgn_MP_Total) as mp_t from asgn_mp as asmp
                                        LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                                        LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                                        WHERE de.DE_Date_Entry BETWEEN '$ffrom' AND '$tto' AND  as_act.Flat_Id in ('$flt_ids') and mp.MP_Id='$mp_id'
                                        AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id='$dept_id'";
                                $q_mp_day_run=mysqli_query($connection,$q_mp_day);
                                $row_total_mp=mysqli_fetch_assoc($q_mp_day_run);
                                if($row_total_mp['mp_t']>0){
                                    if($mp_name=!$mp_old){
                                        // MP NAMES
                                        $rtbl.= '<tr><td colspan="'.$colspan.'" class="text-center table-active">'.$row_mp['MP_Name'].'</td></tr>';
                                        $mp_old=$mp_name;
                                    }
                                    $rtbl.= '
                                    <tr>
                                        <td class="text-uppercase">'.$dept_name.'</td>';
                                    foreach($daterange as $date){
                                        $date_today=$date->format("Y-m-d");
                                        $q_mp_day="SELECT SUM(asmp.Asgn_MP_Total) as mp_t from asgn_mp as asmp
                                        LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                                        LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                                        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') and mp.MP_Id='$mp_id'
                                        AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id='$dept_id'";
                                        $q_mp_day_run=mysqli_query($connection,$q_mp_day);
                                        $row_day_mp=mysqli_fetch_assoc($q_mp_day_run);
                                        $rtbl.='<td>'.$row_day_mp['mp_t'].'</td>';
                                        $r_total=$r_total+$row_day_mp['mp_t'];
                                    }
                                    $rtbl.='
                                        <td>'.$r_total.'</td>
                                    </tr>
                                    ';$r_total=0;
                                }
                            }
                            //TOTAL PER COMPANY
                            $rtbl.= '
                                <tr>
                                    <td class="text-right text-black">Total '.$row_mp['MP_Name'].'</td>';
                                foreach($daterange as $date){
                                    $date_today=$date->format("Y-m-d");
                                    $q_mp_day="SELECT SUM(asmp.Asgn_MP_Total) as mp_t from asgn_mp as asmp
                                    LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                                    LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                    LEFT JOIN users as u on u.USER_ID=de.User_Id
                                    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') and mp.MP_Id='$mp_id'
                                    AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1";
                                    $q_mp_day_run=mysqli_query($connection,$q_mp_day);
                                    $row_day_mp=mysqli_fetch_assoc($q_mp_day_run);
                                    $rtbl.='<td class="">'.$row_day_mp['mp_t'].'</td>';
                                    $r_total=$r_total+$row_day_mp['mp_t'];
                                }
                                $rtbl.='
                                    <td class="">'.$r_total.'</td>
                                </tr>
                                '; $r_total=0;
                        }
                    }
                    //end total
                    $rtbl.= '
                        <tr class="table-danger">
                            <td class="font-weight-bold text-center text-black">MANPOWER TOTAL</td>';
                        foreach($daterange as $date){
                            $date_today=$date->format("Y-m-d");
                            $q_mp_day="SELECT SUM(asmp.Asgn_MP_Total) as mp_t from asgn_mp as asmp
                            LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                            LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                            LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                            LEFT JOIN users as u on u.USER_ID=de.User_Id
                            WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
                            AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1";
                            $q_mp_day_run=mysqli_query($connection,$q_mp_day);
                            $row_day_mp=mysqli_fetch_assoc($q_mp_day_run);
                            $rtbl.='<td >'.$row_day_mp['mp_t'].'</td>';
                            $r_total=$r_total+$row_day_mp['mp_t'];
                        }
                        $rtbl.='
                            <td class="">'.$r_total.'</td>
                        </tr>
                        ';$r_total=0;
                }
                //SB OUTSOURCE
                $q_sb="SELECT * from asgn_subcon as assb
                    LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                    LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                    WHERE de.DE_Date_Entry BETWEEN '$ffrom' AND '$tto' AND  as_act.Flat_Id in ('$flt_ids') 
                    AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 GROUP by assb.SB_Id";
                    $q_sb_run=mysqli_query($connection,$q_sb);
                if(mysqli_num_rows($q_sb_run)>0)
                {
                    foreach($daterange as $date){
                        $colspan++;
                    }
                    $colspan=$colspan+2;
                    $rtbl.='<tr class="table-dark text-white"><td colspan="'.$colspan.'" class="text-center font-weight-bold">SUBCONTRACTOR</td></tr> ';
                    while($row_sb=mysqli_fetch_assoc($q_sb_run)){
                        $sb_id=$row_sb['SB_Id'];$sb_old='';$sb_name=$row_sb['SB_Name']; 
                        $q_dept="SELECT * from department WHERE Dept_Status=1";
                        $q_dept_run=mysqli_query($connection,$q_dept);
                        if(mysqli_num_rows($q_dept_run)>0){
                            while($row_d=mysqli_fetch_assoc($q_dept_run)){
                                $dept_id=$row_d['Dept_Id'];$dept_name=$row_d['Dept_Name'];
                                $q_sb_day="SELECT SUM(assb.Asgn_SB_Total) as sb_t from asgn_subcon as assb
                                        LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                                        LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                                        WHERE de.DE_Date_Entry BETWEEN '$ffrom' AND '$tto' AND  as_act.Flat_Id in ('$flt_ids') and sb.SB_Id='$sb_id'
                                        AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id='$dept_id'";
                                $q_sb_day_run=mysqli_query($connection,$q_sb_day);
                                $row_total_sb=mysqli_fetch_assoc($q_sb_day_run);
                                if($row_total_sb['sb_t']>0){
                                    if($sb_name=!$sb_old){
                                        // echo $mp_name;
                                        $rtbl.= '<tr class="table-secondary"><td colspan="'.$colspan.'" class="text-center">'.$row_sb['SB_Name'].'</td></tr>';
                                        $sb_old=$sb_name;
                                    }
                                    $rtbl.= '
                                    <tr>
                                        <td class="text-uppercase">'.$dept_name.'</td>';
                                    foreach($daterange as $date){
                                        $date_today=$date->format("Y-m-d");
                                        $q_sb_day="SELECT SUM(assb.Asgn_SB_Total) as sb_t from asgn_subcon as assb
                                        LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                                        LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                                        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') and sb.SB_Id='$sb_id'
                                        AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id='$dept_id'";
                                        $q_sb_day_run=mysqli_query($connection,$q_sb_day);
                                        $row_day_sb=mysqli_fetch_assoc($q_sb_day_run);
                                        $rtbl.='<td>'.$row_day_sb['sb_t'].'</td>';
                                        $r_total=$r_total+$row_day_sb['sb_t'];
                                    }
                                    $rtbl.='
                                        <td>'.$r_total.'</td>
                                    </tr>
                                    ';$r_total=0;
                                }
                            }
                            //SB TOTAL per COMPANY
                            $rtbl.= '
                            <tr >
                                <td class="text-right">Total '.$row_sb['SB_Name'].'</td>';
                            foreach($daterange as $date){
                                $date_today=$date->format("Y-m-d");
                                $q_sb_day="SELECT SUM(assb.Asgn_SB_Total) as sb_t from asgn_subcon as assb
                                LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                                LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                                LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                                LEFT JOIN users as u on u.USER_ID=de.User_Id
                                WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') and sb.SB_Id='$sb_id'
                                AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1";
                                $q_sb_day_run=mysqli_query($connection,$q_sb_day);
                                $row_day_sb=mysqli_fetch_assoc($q_sb_day_run);
                                $rtbl.='<td>'.$row_day_sb['sb_t'].'</td>';
                                $r_total=$r_total+$row_day_sb['sb_t'];
                            }
                            $rtbl.='
                                <td>'.$r_total.'</td>
                            </tr>
                            ';$r_total=0;
                        }
                        
                    }
                    //SUB END TOTAL
                    $rtbl.= '
                    <tr class="table-danger">
                        <td class="font-weight-bold text-center">SUBCON TOTAL</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $q_sb_day="SELECT SUM(assb.Asgn_SB_Total) as sb_t from asgn_subcon as assb
                        LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                        LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
                        AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1";
                        $q_sb_day_run=mysqli_query($connection,$q_sb_day);
                        $row_day_sb=mysqli_fetch_assoc($q_sb_day_run);
                        $rtbl.='<td>'.$row_day_sb['sb_t'].'</td>';
                        $r_total=$r_total+$row_day_sb['sb_t'];
                    }
                    $rtbl.='
                        <td>'.$r_total.'</td>
                    </tr>
                    ';$r_total=0;
                }
        //OVERALL TOTAL
        $ef=0; $elect_total=0;       
        $rtbl.= '
                <tr class="bg-danger text-white"><td colspan="'.$days.'" class="text-center font-weight-bold">OVERALL TOTAL</td></tr> 
                <tr>
                    <td>ELECTRICAL</td>';
                    foreach($daterange as $date){
                        $date_today=$date->format("Y-m-d");
                        $e_emt_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as e_tot FROM daily_entry as DE 
                        LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                        LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                        LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                        WHERE DE.DE_Status=1 AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 
                        AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
                        AND EMP.EMP_DESIGNATION LIKE '%elect%'";
                        $e_mp_total="SELECT SUM(asmp.Asgn_MP_Total) as e_tot from asgn_mp as asmp
                        LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                        LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
                        AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=1";
                        $e_sb_total="SELECT SUM(assb.Asgn_SB_Total) as e_tot from asgn_subcon as assb
                        LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                        LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                        LEFT JOIN users as u on u.USER_ID=de.User_Id
                        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
                        AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=1";
                        $e_emt_total_run = mysqli_query($connection,$e_emt_total);
                        $e_sb_total_run=mysqli_query($connection,$e_sb_total);
                        $e_mp_total_run=mysqli_query($connection,$e_mp_total);
                        $row_emt_total = mysqli_fetch_assoc($e_emt_total_run); 
                        $e_emt =$row_emt_total['e_tot'];
                        $row_mp_total=mysqli_fetch_assoc($e_mp_total_run); $e_mp=$row_mp_total['e_tot'];
                        $row_sb_total=mysqli_fetch_assoc($e_sb_total_run); $e_sb=$row_sb_total['e_tot'];
                        $rtbl.=  '<td>'.$ef=$e_emt+$e_mp+$e_sb.'</td>';
                        $elect_total=$elect_total+$ef;
                    }  
            $rtbl.= '
                    <td>'.$elect_total.'</td>
                </tr>
                <tr>
                <td>ELV</td>';$elect_total=0;
                foreach($daterange as $date){
                    $date_today=$date->format("Y-m-d"); 
                    $elv_emt="SELECT COUNT(DISTINCT EMP.EMP_ID) as elv_tot FROM daily_entry as DE 
                    LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                    LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                    LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                    WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 
                    AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' 
                    AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%ELV%'";
                    $elv_mp="SELECT SUM(asmp.Asgn_MP_Total) as e_tot from asgn_mp as asmp
                    LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                    LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                    LEFT JOIN users as u on u.USER_ID=de.User_Id
                    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
                    AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=6";
                    $elv_sb="SELECT SUM(assb.Asgn_SB_Total) as e_tot from asgn_subcon as assb
                    LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                    LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                    LEFT JOIN users as u on u.USER_ID=de.User_Id
                    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
                    AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=6";
                    $elv_emt_run=mysqli_query($connection,$elv_emt); $r_emt_elv=mysqli_fetch_assoc($elv_emt_run); $emt_elv=$r_emt_elv['elv_tot'];
                    $elv_mp_run=mysqli_query($connection,$elv_mp);$r_mp_elv=mysqli_fetch_assoc($elv_mp_run); $mp_elv=$r_emt_elv['e_tot'];
                    $elv_sb_run=mysqli_query($connection,$elv_sb);$r_sb_elv=mysqli_fetch_assoc($elv_sb_run); $sb_elv=$r_emt_elv['e_tot'];
                    $rtbl.=  '<td>'.$elv_t=$emt_elv+$mp_elv+$sb_elv.'</td>';
                        $elv_total=$elv_total+$elv_t;
                }  
        $rtbl.= '
                <td>'.$elv_total.'</td>
            </tr> 
            <tr>
            <td>PLUMBING</td>';
            foreach($daterange as $date){
                $date_today=$date->format("Y-m-d");
                $emt_p="SELECT COUNT(DISTINCT EMP.EMP_ID) as p_tot FROM daily_entry as DE 
                LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 
                AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' 
                AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%plumb%'";
                $mp_p="SELECT SUM(asmp.Asgn_MP_Total) as p_tot from asgn_mp as asmp
                LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
                LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
                LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                LEFT JOIN users as u on u.USER_ID=de.User_Id
                WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
                AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=2";
                $sb_p="SELECT SUM(assb.Asgn_SB_Total) as p_tot from asgn_subcon as assb
                LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
                LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
                LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
                LEFT JOIN users as u on u.USER_ID=de.User_Id
                WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
                AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=2";
                $emt_p_run=mysqli_query($connection,$emt_p);$r_emt_p=mysqli_fetch_assoc($emt_p_run);$p_emt=$r_emt_p['p_tot'];
                $mp_p_run=mysqli_query($connection,$mp_p);$r_mp_p=mysqli_fetch_assoc($mp_p_run);$p_mp=$r_mp_p['p_tot'];
                $sb_p_run=mysqli_query($connection,$sb_p);$r_sb_p=mysqli_fetch_assoc($sb_p_run);$p_sb=$r_sb_p['p_tot'];
                $rtbl.=  '<td>'.$t_plumb=$p_emt+$p_mp+$p_sb.'</td>';
                $op_total=$op_total+$t_plumb;
            }  
    $rtbl.= '
            <td>'.$op_total.'</td>
        </tr> 
        <tr>
        <td>HVAC</td>';
        foreach($daterange as $date){
            $date_today=$date->format("Y-m-d");
            $hvac_emt1="SELECT COUNT(DISTINCT EMP.EMP_ID) as hvac_tot1 FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today'  AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%HVAC%'";
            $hvac_emt2="SELECT COUNT(DISTINCT EMP.EMP_ID) as hvac_tot2 FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today'  AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%A/C%'";
            $hvac_emt3="SELECT COUNT(DISTINCT EMP.EMP_ID) as pf_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE '%pipe%'";
            $hvac_mp="SELECT SUM(asmp.Asgn_MP_Total) as hvac_mp from asgn_mp as asmp
            LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
            LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
            LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
            LEFT JOIN users as u on u.USER_ID=de.User_Id
            WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
            AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=3";
            $hvac_sb="SELECT SUM(assb.Asgn_SB_Total) as hvac_sb from asgn_subcon as assb
            LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
            LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
            LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
            LEFT JOIN users as u on u.USER_ID=de.User_Id
            WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
            AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=3";
            $hvac_emt1_run=mysqli_query($connection,$hvac_emt1);$r_emt1=mysqli_fetch_assoc($hvac_emt1_run);$t_empt1=$r_emt1['hvac_tot1'];
            $hvac_emt2_run=mysqli_query($connection,$hvac_emt2);$r_emt2=mysqli_fetch_assoc($hvac_emt2_run);$t_empt2=$r_emt2['hvac_tot2'];
            $hvac_emt3_run=mysqli_query($connection,$hvac_emt3);$r_emt3=mysqli_fetch_assoc($hvac_emt3_run);$t_empt3=$r_emt3['pf_tot'];
            $hvac_mp_run=mysqli_query($connection, $hvac_mp);$r_mp_hvac=mysqli_fetch_assoc($hvac_mp_run);$t_mp_hvac=$r_mp_hvac['hvac_mp'];
            $hvac_sb_run=mysqli_query($connection,$hvac_sb);$r_sb_hvac=mysqli_fetch_assoc($hvac_sb_run);$t_sb_hvac=$r_sb_hvac['hvac_sb'];
            $rtbl.=  '<td>'.$hvac_dt=$t_empt1+$t_empt2+$t_empt3+$t_mp_hvac+$t_sb_hvac.'</td>';
            $hvac_total=$hvac_total+$hvac_dt;
        }  
$rtbl.= '
        <td>'.$hvac_total.'</td>
    </tr> <tr>
    <td>FIRE ALARM</td>';
    foreach($daterange as $date){
        $date_today=$date->format("Y-m-d");
        $emt_fa="SELECT COUNT(DISTINCT EMP.EMP_ID) as fa_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND DE.User_Id in ('$user_ids') AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') AND EMP.EMP_DESIGNATION LIKE'%alarm%'";
        $mp_fa="SELECT SUM(asmp.Asgn_MP_Total) as fa_mp from asgn_mp as asmp
        LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
        LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
        LEFT JOIN users as u on u.USER_ID=de.User_Id
        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
        AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=5";
        $sb_fa="SELECT SUM(assb.Asgn_SB_Total) as fa_sb from asgn_subcon as assb
        LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
        LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
        LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
        LEFT JOIN users as u on u.USER_ID=de.User_Id
        WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
        AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=5";
        $emt_fa_run=mysqli_query($connection,$emt_fa);$r_emt_fa=mysqli_fetch_assoc($emt_fa_run);$fa_emt=$r_emt_fa['fa_tot'];
        $mp_fa_run=mysqli_query($connection,$mp_fa);$r_mp_fa=mysqli_fetch_assoc($mp_fa_run);$fa_mp=$r_mp_fa['fa_mp'];
        $sb_fa_run=mysqli_query($connection,$sb_fa);$r_sb_fa=mysqli_fetch_assoc($sb_fa_run);$fa_sb=$r_sb_fa['fa_sb'];
        $rtbl.=  '<td>'.$dt_fa=$fa_emt+$fa_mp+$fa_sb.'</td>';
        $ffa_total=$ffa_total+$dt_fa;
    }  
$rtbl.= '
    <td>'.$ffa_total.'</td>
</tr> <tr>
<td>FIRE FIGHTING</td>';
foreach($daterange as $date){
    $date_today=$date->format("Y-m-d");
    $q_ff_total = "SELECT DISTINCT(EMP.EMP_DESIGNATION) as desig FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND DE.User_Id in ('$user_ids') AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids') and EMP.EMP_DESIGNATION NOT LIKE '%Elect%'";
    // echo $q_ff_total;
    $q_ff_total_run = mysqli_query($connection,$q_ff_total); $ff=0;
    if(mysqli_num_rows($q_ff_total_run)>0)
    {
        while($row_ff = mysqli_fetch_assoc($q_ff_total_run))
        {
            $desig = $row_ff['desig'];
            if(strpos($desig,'fighting')!==false || strpos($desig,'Fighting')!==false || strpos($desig,'Welder')!==false || strpos($desig,'welder')!==false)
            {
                $ff++;
            }
        }
    }
    $mp_ff="SELECT SUM(asmp.Asgn_MP_Total) as e_tot from asgn_mp as asmp
    LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
    LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
    LEFT JOIN users as u on u.USER_ID=de.User_Id
    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
    AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1 AND u.Dept_Id=4";
    $sb_ff="SELECT SUM(assb.Asgn_SB_Total) as e_tot from asgn_subcon as assb
    LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
    LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
    LEFT JOIN users as u on u.USER_ID=de.User_Id
    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
    AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1 AND u.Dept_Id=4";
    $mp_ff_run=mysqli_query($connection,$mp_ff);$r_mp_ff=mysqli_fetch_assoc($mp_ff_run);$ff_mp=$r_mp_ff['e_tot'];
    $sb_ff_run=mysqli_query($connection,$sb_ff);$r_sb_ff=mysqli_fetch_assoc($sb_ff_run);$ff_sb=$r_sb_ff['e_tot'];
    $rtbl.=  '<td>'.$dt_ff=$ff+$ff_mp+$ff_sb.'</td>';
    $ff_total=$ff_total+$dt_ff;
}  
$rtbl.= '
<td>'.$ff_total.'</td>
</tr> <tr class="table-danger">
<td class="text-center font-weight-bold">TOTAL</td>';
foreach($daterange as $date){
    $date_today=$date->format("Y-m-d");
    $q_emt_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as emt_tot FROM daily_entry as DE LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id WHERE DE.DE_Status=1 AND AS_W.Asgd_Worker_Status=1 AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND DE.User_Id in ('$user_ids') AND date(DE.DE_Date_Entry)='$date_today' AND AS_ACT.Flat_Id in ('$flt_ids')";
    $q_emt_total_run = mysqli_query($connection,$q_emt_total);
    $row_dta_total = mysqli_fetch_assoc($q_emt_total_run);$emt_t=$row_dta_total['emt_tot'];
    $mp_dat="SELECT SUM(asmp.Asgn_MP_Total) as e_tot from asgn_mp as asmp
    LEFT JOIN daily_entry as de on de.DE_Id=asmp.DE_Id
    LEFT JOIN manpower as mp on mp.MP_Id=asmp.MP_Id
    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
    LEFT JOIN users as u on u.USER_ID=de.User_Id
    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids') 
    AND de.DE_Status=1 AND asmp.Asgn_MP_Status=1 AND mp.MP_Status=1";
    $sb_dat="SELECT SUM(assb.Asgn_SB_Total) as e_tot from asgn_subcon as assb
    LEFT JOIN daily_entry as de on de.DE_Id=assb.DE_Id
    LEFT JOIN subcontractor as sb on sb.SB_Id=assb.SB_Id
    LEFT JOIN assigned_activity as as_act on de.Asgd_Act_Id = as_act.Asgd_Act_Id
    LEFT JOIN users as u on u.USER_ID=de.User_Id
    WHERE de.DE_Date_Entry='$date_today' AND  as_act.Flat_Id in ('$flt_ids')
    AND de.DE_Status=1 AND assb.Asgn_SB_Status=1 AND sb.SB_Status=1";
    $mp_dat_run=mysqli_query($connection,$mp_dat);$r_dat_mp=mysqli_fetch_assoc($mp_dat_run);$dat_mp=$r_dat_mp['e_tot'];
    $sb_dat_run=mysqli_query($connection,$sb_dat);$r_dat_sb=mysqli_fetch_assoc($sb_dat_run);$dat_sb=$r_dat_sb['e_tot'];
    $rtbl.=  '<td>'.$dta_tot=$emt_t+$dat_mp+$dat_sb.'</td>';
    $ft_total=$ft_total+$dta_tot;
}  
$rtbl.= '
<td>'.$ft_total.'</td>
</tr>
                </tbody>
            </table>
        </div>
    </div>';
    echo $rtbl;?>
    <div align="right">
    <button name="" id="btnExcel" class="btn btn-success mt-2">
        <i class="fa fa-download" aria-hidden="true"></i>
        Download
    </button>  
        </div>
    <?php
}
?>
<script>
$(document).ready(function(){
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#tbl1'));
    });
    $.ajax({
        url:'../P_ADMIN/ajax_project.php',
        method: 'POST',
        data:{},
        success:function(data){
            $('#project_opt').html(data).change();
            $('.selectpicker').selectpicker('refresh');
        }
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>