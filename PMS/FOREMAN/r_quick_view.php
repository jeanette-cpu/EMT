<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/foreman_navbar.php');
include('function.php');
if(isset($_POST['qck_sum'])){
    $prj_id = $_POST['prj_id'];
    $flt_ids= getFlatIds($prj_id);
    $q_prj_name="SELECT * from project where Prj_Id='$prj_id'";
    $run=mysqli_query($connection, $q_prj_name);
    $row = mysqli_fetch_assoc($run);
    $prj_name= $row['Prj_Code'].' - '.$row['Prj_Name'];
    $prj_loc = $row['Prj_Emirate_Location'].', '.$row['Prj_Location_Desc'];

    date_default_timezone_set('Asia/Dubai');
    $Date = date('d.m.Y');
    $date_today = date('Y-m-d');

    $username1 = $_SESSION['USERNAME'];
    $query1 = "SELECT USER_ID, Dept_Id FROM users WHERE USERNAME='$username1'";
    $query_run1 = mysqli_query($connection, $query1);
    $row1=mysqli_fetch_assoc($query_run1);
    $user_id = $row1['USER_ID'];
    $u_dept_id = $row1['Dept_Id'];
}
?>
<script src="table2excel.js"> </script>
<div class="col-xl-12 col-lg-12">
    <div class="card-body" id="daily_report">
    <table id="tbl1" class="table table-sm table-bordered"  style="width:100%;   border-collapse: collapse;">
        <tr class=" text-primary">
            <td class="text-center" colspan="6"><h5>EMT ELECTROMECHANICAL WORKS LLC: SUMMARY</h5></td> 
        </tr>
        <tr>
            <td  class="th text-left">Project Name:</td>
            <td colspan="2"><?php echo $prj_name?></td>
            <td class="text-center"></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td  class="text-left">Location:</td>
            <td  colspan="2"><?php echo $prj_loc?></td>
            <td  class="text-left">Date of Entry:</td>
            <td  colspan="2"><?php echo $Date?></td>
        </tr>
        <?php
            $dept_q ="SELECT * FROM department WHERE Dept_Status=1 AND Dept_Id='$u_dept_id '";
            $dept_q_run = mysqli_query($connection, $dept_q);
            $total_per_dept=0; $d_mp_ctn=0; $tot_mp=0; $d_sb_ctn=0;$tot_sb=0;
            
            if(mysqli_num_rows($dept_q_run)>0)
            {
                while($row1 = mysqli_fetch_assoc($dept_q_run))
                {
                    $dept_id = $row1['Dept_Id']; 
                ?>
        <tr>
            <td width="100%" class="text-danger text-center" colspan="6"><?php echo strtoupper($row1['Dept_Name']) ?></td>
        </tr>
            <td width="10%" class="text-center" style="color:black" >NO</td>
            <td width="30%" class="text-center" style="color:black">Description </td>
            <td width="30%" class="text-center" style="color:black">Location</td>
            <td width="10%" class="text-center" style="color:black">Date</td>
            <td width="10%" class="text-center" style="color:black">Manpower</td>
            <td width="10%" class="text-center" style="color:black">Remarks</td>
                <?php
                    // $date_today='2023-07-01';
                    if(isset($flt_ids)){ 
                        $DE_q ="SELECT * FROM daily_entry LEFT JOIN assigned_activity on assigned_activity.Asgd_Act_Id = daily_entry.Asgd_Act_Id 
                        LEFT JOIN activity_category on activity_category.Act_Cat_Id = assigned_activity.Act_Cat_Id 
                        WHERE date(DE_Date_Insert)='$date_today' AND DE_Status=1 
                        AND activity_category.Dept_Id='$dept_id' 
                        AND daily_entry.User_Id='$user_id' and Flat_Id in ('$flt_ids') ORDER BY daily_entry.DE_Date_Entry ";
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
                            $Date_Worked=$row3['DE_Date_Entry'];
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
                    <td><?php echo $Date_Worked;?></td>
                    <td class="text-center"><?php $ctn = $row7['ctn']+$mp_ctn+ $sb_ctn; echo $ctn;
                        $total_per_dept = $total_per_dept + $ctn;
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
                    <td class="text-center" colspan="3">TOTAL</td>
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
                    <td class="text-center text-danger" colspan="4"></td>
                    <td class="text-center" id="tot_val" ><?php echo $overall_total; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-center text-danger" colspan="6"><h5>TOTAL EMT MANPOWER</h5></td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2"></td>
                    <td class="text-center">Manpower(Outsource)</td>
                    <td class="text-center">Subcontractor(Outsource)</td>
                    <td class="text-center">EMT</td>
                    <td class="text-center"  >Total</td>
                </tr>
                <?php
                    $q_emp_total = "SELECT COUNT(DISTINCT EMP.EMP_ID) as e_tot, DE.DE_Date_Entry FROM daily_entry as DE 
                                        LEFT JOIN asgn_worker as AS_W on AS_W.DE_Id = DE.DE_Id 
                                        LEFT JOIN employee as EMP on EMP.EMP_ID = AS_W.Emp_Id 
                                        LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = DE.Asgd_Act_Id 
                                        WHERE DE.DE_Status=1 AND DE.User_Id='$user_id' AND AS_W.Asgd_Worker_Status=1 
                                        AND AS_ACT.Asgd_Act_Status=1 AND EMP.EMP_STATUS=1 AND date(DE.DE_Date_Insert)='$date_today' AND 
                                        AS_ACT.Flat_Id in ('$flt_ids') GROUP BY DE.DE_Date_Entry";
                    $q_emp_total_run = mysqli_query($connection,$q_emp_total);
                    if(mysqli_num_rows($q_emp_total_run)>0){ $total_emt=0;
                        while($row_total = mysqli_fetch_assoc($q_emp_total_run)){
                            $total_emt = $total_emt + $row_total['e_tot'];
                        }
                    }
                     // COUNT MP
                    $q_mp_total="SELECT SUM(asmp.Asgn_MP_Total) as mp_c FROM asgn_mp as asmp 
                                LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id 
                                LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = de.Asgd_Act_Id 
                                where DATE(de.DE_Date_Insert)='$date_today' and asmp.Asgn_MP_Status=1 AND AS_ACT.Flat_Id in ('$flt_ids')
                                and de.DE_Status=1 and de.User_Id in ('$user_id')"; 
                    $q_mp_total_run=mysqli_query($connection, $q_mp_total);
                    $mpt_row = mysqli_fetch_assoc($q_mp_total_run);
                    $mp_total= $mpt_row['mp_c']; 
                    // SB total
                    $q_sb_total="SELECT SUM(asmp.Asgn_SB_Total) as sb_c FROM asgn_subcon as asmp 
                                LEFT JOIN daily_entry AS de on de.DE_Id=asmp.DE_Id 
                                LEFT JOIN assigned_activity as AS_ACT on AS_ACT.Asgd_Act_Id = de.Asgd_Act_Id 
                                where DATE(de.DE_Date_Insert)='$date_today' and asmp.Asgn_SB_Status=1 AND AS_ACT.Flat_Id in ('$flt_ids')
                                and de.DE_Status=1 and de.User_Id in ('$user_id')";
                    $q_sb_total_run=mysqli_query($connection,$q_sb_total);
                    $sbt_row = mysqli_fetch_assoc($q_sb_total_run);
                    $sb_total = $sbt_row['sb_c'];
                    $total=$mp_total+$sb_total+$total_emt;
                ?>
                <tr>
                    <td colspan="2">Total</td> </div>
                    <td class="text-right"><?php echo $mp_total?></td> <!--  MANPOWER -->
                    <td class="text-right"><?php echo $sb_total;?></td><!-- SUBCON  -->
                    <td class="text-right"><?php echo $total_emt;?></td><!--  EMT -->
                    <td class="text-center"><?php echo $total;?></td>   <!--  TOTAL -->
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