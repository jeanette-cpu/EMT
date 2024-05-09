<?php
include('../../security.php');
error_reporting(0);

if(isset($_POST['asgd_id']))
{
    $asgd = $_POST['asgd_id'];
    $query="SELECT * from daily_entry where DE_Status=1 and Asgd_Act_Id ='$asgd' ORDER BY DE_Date_Entry ";
    $query_run = mysqli_query($connection, $query);$q_mcount=0;$total=0;

    $q_mat="SELECT SUM(Asgd_Mat_to_Act_Qty) as msum FROM asgn_mat_to_act as as_m  
            LEFT JOIN assigned_material as mat on mat.Asgd_Mat_Id = as_m.Asgd_Mat_Id 
            LEFT JOIN material on material.Mat_Id = mat.Mat_Id 
            WHERE as_m.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Act_Id='$asgd'
            ";
    $q_mat_run=mysqli_query($connection,$q_mat);
    $row_mat_sum=mysqli_fetch_assoc($q_mat_run);
    $table ='
    <div class="table-responsive">
        <table class="table table-bordered " id="" width="100%" cellspacing="0">
            <thead>
                <th>Date</th>
                <th>No</th>
                <th>Manpower</th>
                <th>Assigned:' .' '.number_format($row_mat_sum['msum'], 2, '.', '').' <br>Material Used</th>
                <th>Percentage Done</th>
            </thead>
            <tbody>';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $de_id = $row['DE_Id'];
            // EMPLOYEES
            $q_m ="SELECT * from asgn_worker as as_w LEFT JOIN employee as emp on emp.EMP_ID=as_w.Emp_Id where as_w.Asgd_Worker_Status=1 and as_w.DE_Id='$de_id'";
            $q_m_run = mysqli_query($connection, $q_m);
            $q_mcount = mysqli_num_rows($q_m_run);
            // MANPOWER
            $q_mp ="SELECT * from asgn_mp as as_mp 
            LEFT JOIN manpower as mp on mp.MP_Id=as_mp.MP_Id 
            where as_mp.Asgn_MP_Status=1 and as_mp.DE_Id='$de_id'";
            $q_mp_run = mysqli_query($connection, $q_mp);
            // SUBCONTRACTOR
            $q_sb ="SELECT * from asgn_subcon as as_sb 
            LEFT JOIN subcontractor as sb on sb.SB_Id=as_sb.SB_Id 
            where as_sb.Asgn_SB_Status=1 and as_sb.DE_Id='$de_id'";
            $q_sb_run = mysqli_query($connection, $q_sb);
            // COUNT MP
            $q_mp_count="SELECT SUM(Asgn_MP_Qty) AS mp_c  FROM asgn_mp where DE_Id='$de_id' and Asgn_MP_Status=1"; 
            $q_mp_count_run=mysqli_query($connection, $q_mp_count);
            $mp_row = mysqli_fetch_assoc($q_mp_count_run);
            // COUNT SB
            $q_sb_count="SELECT SUM(Asgn_SB_Qty) AS sb_c  FROM asgn_subcon where DE_Id='$de_id' and Asgn_SB_Status=1";
            $q_sb_count_run=mysqli_query($connection, $q_sb_count);
            $sb_row = mysqli_fetch_assoc($q_sb_count_run);
            $row_tot = $q_mcount+$mp_row['mp_c']+$sb_row['sb_c'];
            $total = $total+$q_mcount+$mp_row['mp_c']+$sb_row['sb_c'];
            //MATERIAL Computation
            $pct = 0.01*$row_mat_sum['msum'];
            $mat_used= $pct*$row['DE_Pct_Done'];
    $table .='
            <tr>
                <td>'.$row['DE_Date_Entry'].'</td>
                <td> '.$row_tot.'</td>
                <td>';
                if(mysqli_num_rows($q_m_run)>0)
                {
                    while($row_m = mysqli_fetch_assoc($q_m_run))
                    {   
                        $table .='  '.$row_m['EMP_NO'].' - '.$row_m['EMP_FNAME'].' '.$row_m['EMP_LNAME'].'<br> ';
                    }
                }
                if(mysqli_num_rows($q_mp_run)>0)
                {
                    while($row_mp = mysqli_fetch_assoc($q_mp_run))
                    {   
                        $table .='  '.$row_mp['MP_Name'].'<br> ';
                    }
                }
                if(mysqli_num_rows($q_sb_run)>0)
                {
                    while($row_sb = mysqli_fetch_assoc($q_sb_run))
                    {   
                        $table .='  '.$row_sb['SB_Name'].'<br> ';
                    }
                }     
    $table .='<br>
                </td>
                <td>'.$mat_used.'</td>
                <td>'.$row['DE_Pct_Done'].'</td>
            </tr>';
        }
        $table .= '<td class="text-center font-weight-bold">Total</td>
                <td  class="text-center font-weight-bold">'.$total.'</td>
                <td colspan="3"></td>';
    }
    $table .= '</tbody>
    </table>
    </div>';
    echo $table; 
}