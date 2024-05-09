<?php
include('../../security.php');
error_reporting(0);
// DEPARTMENT dropdown change
if(isset($_POST['dept_id']))
{
    $dept_id = $_POST['dept_id'];
    $blg_id = $_POST['blg_id'];
    // get all activity category
    $cat_id_q = "SELECT Act_Cat_Id FROM activity_category WHERE Act_Cat_Status=1 AND Dept_Id='$dept_id'";
    $cat_id_q_run = mysqli_query($connection, $cat_id_q);
    if(mysqli_num_rows($cat_id_q_run)>0)
    {
        while($row_id = mysqli_fetch_assoc($cat_id_q_run))
        {
            $id_arr[] = $row_id['Act_Cat_Id'];
        }
        $act_cat_id=implode("', '", $id_arr);
    }
    $table = '
    <div class="table-responsive ">
        <table class="table table-bordered" id="dept_tbl" style="width:100%;" cellspacing="0">
            <thead>
                <th class="d-none">No.</th>
                <th>Level</th>
                <th id="rh">Room</th>
                <th>Total</th>
            </thead>
            <tbody>';
    // BLG ID -------------
    $l_q="SELECT * FROM level where Lvl_Status=1 AND Blg_Id='$blg_id' ORDER BY Lvl_No desc";
    // SELECT * FROM plex where Plx_Status=1 and Villa_Id='' ORDER BY Plx_Code
    $l_q_run = mysqli_query($connection, $l_q); $i=0; $td=0;$ctn =1; $tbl_sum=0;$tbl_ave=0; 
    $l_count = mysqli_num_rows($l_q_run);
    if(mysqli_num_rows($l_q_run)>0)
    {
        while($l_row = mysqli_fetch_assoc($l_q_run))
        {
            $l_id= $l_row['Lvl_Id'];
            $q_flat ="SELECT * from flat WHERE Flat_Status=1 and Lvl_Id ='$l_id'";
            // SELECT * FROM building WHERE Blg_Status=1 and Plx_Id=''
            $q_flat_run = mysqli_query($connection, $q_flat); 
            $q_flat_count = mysqli_num_rows($q_flat_run); $row_ave_sum=0;$row_ave=0;
    $table .= '
    <tr id ="tr_'.$ctn.'"  style="width:100%"> 
        <td width="5%" class="d-none">'.$l_row['Lvl_No'].'</td> 
        <td>'.$l_row['Lvl_Code'].' '.$l_row['Lvl_Name'].'</td>
        ';
        //Blg_No Blg_Name
            $average=0; $ctr=0;
            if(mysqli_num_rows($q_flat_run)>0)
            {
                while($f_row = mysqli_fetch_assoc($q_flat_run))
                {
                    $flat_id= $f_row['Flat_Id']; $td ++; // count flat
                    $ctr++;
                    // ACTIVITY CATEGORY ID ----------------
                    // get assigned activity percentage
                    $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id='$flat_id' and Act_Cat_Id in ('$act_cat_id')";            
                    $q_activity_run = mysqli_query($connection, $q_activity);
                    $q_act_count = mysqli_num_rows($q_activity_run); $sum=0;
                    if(mysqli_num_rows($q_activity_run)>0)
                    {
                        while($row6 = mysqli_fetch_assoc($q_activity_run))
                        {
                            $percentage = $row6['Asgd_Pct_Done'];
                            $sum = $sum + $percentage;
                        }
                    }
            $table .='<td id="td_'.$ctn.'_'.$ctr.'" class="toAct" value='.$flat_id.'> 
            <a  href="#actTable">
            <input type="hidden" id="td_f" class="td_fId" value='.$flat_id.'>
            ';
                    if($q_act_count !=0)
                    {
                        $average = $sum/$q_act_count;
                        $average = number_format($average, 2, '.', '');
                    }
                    else
                    {   
                        $average = 0;
                    }
                    $table .=  $f_row['Flat_Code'].' '.$f_row['Flat_Name'].' '.$average.'%';
                    // progress bar
                    $table .= ' <div class="progress progress-sm mr-2">
                                <div id="bar" class="progress-bar bg-success" role="progressbar" style="width: '.$average.'" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div></a>
                    </td>';
                    $row_ave_sum = $row_ave_sum + $average;
                }
            }
            else
            {
                $table .= '<td class="td_no"></td>';
            }
            
    $table .= '
    <td>';
    if($q_flat_count!=0)
    {
        $row_ave = $row_ave_sum/$q_flat_count;
        $row_ave = number_format($row_ave, 2, '.', '');
        $tbl_sum = $tbl_sum + $row_ave;
    }
    else{ 
        $row_ave='-';
    }

    if ($i < $td)
    {
        $i = $td; // highest no. of flats
    }
    $table .= $row_ave.'%';
    $table .='</td>
    </tr>';
    $td =0;
    $ctn++;
        }
    }
    if($l_count !=0)
    {
        $tbl_ave = $tbl_sum/$l_count;
        $tbl_ave = number_format($tbl_ave, 2, '.', '');
    }
    else
    {
        echo "No Levels Found";
    }
    $table .= '</tbody>
        <tfoot>
            <th class="d-none">No.</th>
            <th></th>
            <th id="rf" class="text-center">Total</th>
            <th> '.$tbl_ave.'%</th>
        </tfoot>
    </table>
    <input type="hidden" id="count" value='.$i.'>
    </div>';
    echo $table;
}
// CATEGORY
if(isset($_POST['cat_id']))
{
    $dept_id = $_POST['ddept_id'];
    $act_cat_id = $_POST['cat_id'];
    $blg_id = $_POST['blg_id'];
    
    $table = '
    <div class="table-responsive">
        <table class="table table-bordered " id="cat_tbl" width="100%" cellspacing="0">
            <thead>
                <th class="d-none">No.</th>
                <th>Level</th>
                <th id="r_header">Room</th>
                <th>Total</th>
            </thead>
            <tbody>';
    // BLG ID -------------
    $l_q="SELECT * FROM level where Lvl_Status=1 AND Blg_Id='$blg_id' ORDER BY Lvl_No DESC";
    $l_q_run = mysqli_query($connection, $l_q); $k=0; $td=0;$ctn =1;$tbl_sum=0;$tbl_ave=0; 
    $l_count = mysqli_num_rows($l_q_run);
    if(mysqli_num_rows($l_q_run)>0)
    {
        while($l_row = mysqli_fetch_assoc($l_q_run))
        {
            $l_id= $l_row['Lvl_Id'];
            $q_flat ="SELECT * from flat WHERE Flat_Status=1 and Lvl_Id ='$l_id'";
            $q_flat_run = mysqli_query($connection, $q_flat); 
            $q_flat_count = mysqli_num_rows($q_flat_run); $row_ave_sum=0;$row_ave=0;
    $table .= '
    <tr id="ttr_'.$ctn.'"> 
        <td class="d-none">'.$l_row['Lvl_No'].'</td> 
        <td >'.$l_row['Lvl_Code'].' '.$l_row['Lvl_Name'].'</td>
        ';
            $average=0;$ctr=0;
            if(mysqli_num_rows($q_flat_run)>0)
            {
                while($f_row = mysqli_fetch_assoc($q_flat_run))
                {
                    $flat_id= $f_row['Flat_Id']; $ctr++;
                    // ACTIVITY CATEGORY ID ----------------
                    // get assigned activity percentage
                    $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id='$flat_id' and Act_Cat_Id in ('$act_cat_id')";            
                    $q_activity_run = mysqli_query($connection, $q_activity);
                    $q_act_count = mysqli_num_rows($q_activity_run); $sum=0;
                    if(mysqli_num_rows($q_activity_run)>0)
                    {
                        while($row6 = mysqli_fetch_assoc($q_activity_run))
                        {
                            $percentage = $row6['Asgd_Pct_Done'];
                            $sum = $sum + $percentage;
                        }
                    }
                        if($q_act_count !=0)
                        {
                            $average = $sum/$q_act_count;
                        }
                        else
                        {   
                            $average = 0;
                        }
                    $table .='<td id=ttd_'.$ctn.'_'.$ctr.' class="toAct" value='.$flat_id.'> 
                    <a  href="#actTable">
                    <input type="hidden" id="td_f" class="td_fId" value='.$flat_id.'>
                    ';
                    $average = number_format($average, 2, '.', '');
                    $table .= $f_row['Flat_Code'].' '.$f_row['Flat_Name'].' '.$average.'%';
                    // progress bar
                    $row_ave_sum = $row_ave_sum + $average;
                    $td ++; // count flat
                    $table.='
                    <div class="progress progress-sm mr-2">
                        <div id="bar" class="progress-bar bg-success" role="progressbar" style="width: '.$average.'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div></a>
                    </td>';
                }
            }
            else
            {
                $table .='<td class="tdd_no"></td>';
            }
    $table .= '
        <td>
    ';
    if($q_flat_count!=0)
    {
        $row_ave = $row_ave_sum/$q_flat_count;
        $row_ave = number_format($row_ave, 2, '.', '');
        $tbl_sum = $tbl_sum + $row_ave;
    }
    else{ 
        $row_ave='-';
    }
    $table .= $row_ave.'%';
    if ($k <= $td)
    {
        $k = $td; $td =0; // highest no. of flats
    }
    $td =0;
    $table .='
        </td>
    </tr>';
    $ctn++;
        }
    }
    if($l_count !=0)
    {
        $tbl_ave = $tbl_sum/$l_count;
        $tbl_ave = number_format($tbl_ave, 2, '.', '');
    }
    else
    {
        echo "No Levels Found";
    }
    $table .= '</tbody>
        <tfoot>
            <th class="d-none"></th>
            <th></th>
            <th id="r_footer" class="text-center">Total</th>
            <th>'.$tbl_ave.'%</th>
        </tfoot>
    </table>
    
    
    <input type="hidden" id="count" value='.$k.'>
    </div>'; 
    echo $table;
}
// ACTIVITY 
// if(isset($_POST['act_id']))
// {
//     $dept_id = $_POST['dddept_id'];
//     $act_cat_id = $_POST['ccat_id'];
//     $blg_id = $_POST['blg_id'];
//     $act_id= $_POST['act_id'];
//     $table = '
//     <div class="table-responsive">
//         <table class="table table-bordered " id="cat_tbl" width="100%" cellspacing="0">
//             <thead>
//                 <th class="d-none">No.</th>
//                 <th>Level</th>
//                 <th id="r_header">Room</th>
//                 <th>Total</th>
//             </thead>
//             <tbody>';
//     // BLG ID -------------
//     $l_q="SELECT * FROM level where Lvl_Status=1 AND Blg_Id='$blg_id' ORDER BY Lvl_No DESC";
//     $l_q_run = mysqli_query($connection, $l_q); $k=0; $td=0;$ctn =1;$tbl_sum=0;$tbl_ave=0; 
//     $l_count = mysqli_num_rows($l_q_run);
//     if(mysqli_num_rows($l_q_run)>0)
//     {
//         while($l_row = mysqli_fetch_assoc($l_q_run))
//         {
//             $l_id= $l_row['Lvl_Id'];
//             $q_flat ="SELECT * from flat WHERE Flat_Status=1 and Lvl_Id ='$l_id'";
//             $q_flat_run = mysqli_query($connection, $q_flat); 
//             $q_flat_count = mysqli_num_rows($q_flat_run); $row_ave_sum=0;$row_ave=0;
//     $table .= '
//     <tr id="ttr_'.$ctn.'"> 
//         <td class="d-none">'.$l_row['Lvl_No'].'</td> 
//         <td >'.$l_row['Lvl_Code'].'  '.$l_row['Lvl_Name'].'</td>
//         ';
//             $average=0;$ctr=0;
//             if(mysqli_num_rows($q_flat_run)>0)
//             {
//                 while($f_row = mysqli_fetch_assoc($q_flat_run))
//                 {
//                     $flat_id= $f_row['Flat_Id']; $ctr++;
//                     // ACTIVITY CATEGORY ID ----------------
//                     // get assigned activity percentage
//                     $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id='$flat_id' and Act_Cat_Id='$act_cat_id' AND Act_Id='$act_id'";            
//                     $q_activity_run = mysqli_query($connection, $q_activity);
//                     $q_act_count = mysqli_num_rows($q_activity_run); $sum=0;
//                     if(mysqli_num_rows($q_activity_run)>0)
//                     {
//                         while($row6 = mysqli_fetch_assoc($q_activity_run))
//                         {
//                             $percentage = $row6['Asgd_Pct_Done'];
//                             $sum = $sum + $percentage;
//                         }
//                     }
//                         if($q_act_count !=0)
//                         {
//                             $average = $sum/$q_act_count;
//                         }
//                         else
//                         {   
//                             $average = 0;
//                         }
//                     $table .='<td id=ttd_'.$ctn.'_'.$ctr.' class="toAct" value='.$flat_id.'> 
//                     <a  href="#actTable">
//                     <input type="hidden" id="td_f" class="td_fId" value='.$flat_id.'>
//                     ';
//                     $average = number_format($average, 2, '.', '');
//                     $table .= $f_row['Flat_Code'].' '.$f_row['Flat_Name'].' '.$average.'%';
//                     // progress bar
//                     $row_ave_sum = $row_ave_sum + $average;
//                     $td ++; // count flat
//                     $table.='
//                     <div class="progress progress-sm mr-2">
//                         <div id="bar" class="progress-bar bg-success" role="progressbar" style="width: '.$average.'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
//                     </div></a>
//                     </td>';
//                 }
//             }
//             else
//             {
//                 $table .='<td class="tdd_no"></td>';
//             }
//     $table .= '
//         <td>
//     ';
//     if($q_flat_count!=0)
//     {
//         $row_ave = $row_ave_sum/$q_flat_count;
//         $row_ave = number_format($row_ave, 2, '.', '');
//         $tbl_sum = $tbl_sum + $row_ave;
//     }
//     else{ 
//         $row_ave='-';
//     }
//     $table .= $row_ave.'%';
//     if ($k <= $td)
//     {
//         $k = $td; $td =0; // highest no. of flats
//     }
//     $td =0;
//     $table .='
//         </td>
//     </tr>';
//     $ctn++;
//         }
//     }
//     if($l_count !=0)
//     {
//         $tbl_ave = $tbl_sum/$l_count;
//         $tbl_ave = number_format($tbl_ave, 2, '.', '');
//     }
//     else
//     {
//         echo "No Levels Found";
//     }
//     $table .= '</tbody>
//         <tfoot>
//             <th class="d-none"></th>
//             <th></th>
//             <th id="r_footer" class="text-center">Total</th>
//             <th>'.$tbl_ave.'%</th>
//         </tfoot>
//     </table>
    
    
//     <input type="hidden" id="count" value='.$k.'>
//     </div>'; 
//     echo $table;
// }
?>