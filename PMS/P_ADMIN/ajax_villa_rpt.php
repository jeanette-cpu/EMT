<?php
include('../../security.php');
// error_reporting(0);
// DEPARTMENT dropdown change
if(isset($_POST['dept_id']))
{
    $dept_id = $_POST['dept_id'];
    $plx_id = $_POST['plx_id'];
    $villa_id = $_POST['villa_id'];
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
    <div class="table-responsive">
        <table class="table table-bordered " id="dept_tbl" width="100%" cellspacing="0">
            <thead>
                <th class="d-none">No.</th>
                <th>Plex</th>
                <th id="rh">Villa</th>
                <th>Total</th>
            </thead>
            <tbody>
            ';
    if($plx_id ==='All'){
        // multiple plex
        $p_q="SELECT * FROM plex where Plx_Status=1 AND Villa_Id='$villa_id' ORDER BY Plx_Code";
    }
    else{
        // single plex
        $p_q="SELECT * FROM plex where Plx_Status=1 AND Plx_Id='$plx_id' ";
    }
    $p_q_run = mysqli_query($connection, $p_q); $i=0; $td=0;$ctn =1; $tbl_sum=0;$tbl_ave=0; 
    $p_count = mysqli_num_rows($p_q_run);
    if(mysqli_num_rows($p_q_run)>0)
    {
        while($p_row = mysqli_fetch_assoc($p_q_run))
        {
            $plex_id= $p_row['Plx_Id'];
            // get buildings(villa)
            $q_blg ="SELECT * FROM building WHERE Blg_Status=1 and Plx_Id='$plex_id'";
            // $q_flat ="SELECT * from flat WHERE Flat_Status=1 and Lvl_Id ='$l_id'";
            // SELECT * FROM building WHERE Blg_Status=1 and Plx_Id=''
            $q_blg_run = mysqli_query($connection, $q_blg); 
            $q_blg_count = mysqli_num_rows($q_blg_run); $row_ave_sum=0;$row_ave=0;
    $table .= '
    <tr id ="tr_'.$ctn.'"> 
        <td width="5%" class="d-none">'.$p_row['Plx_Code'].'</td> 
        <td>'.$p_row['Plx_Code'].' - '.$p_row['Plx_Name'].'</td>
        ';
            $average=0;$ctr=0;
            if(mysqli_num_rows($q_blg_run)>0)
            {
                while($blg_row = mysqli_fetch_assoc($q_blg_run))
                {
                    $blg_id = $blg_row['Blg_Id'];$ctr++;
                    $q_level = "SELECT Lvl_Id from level WHERE Lvl_Status=1 AND Blg_Id='$blg_id'";
                    $q_level_run = mysqli_query($connection,$q_level); $l_id_arr=null; $l_ids=null;
                    if(mysqli_num_rows($q_level_run)>0){
                        while($row_l = mysqli_fetch_assoc($q_level_run)){
                            $l_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $l_ids = implode("', '", $l_id_arr);
                    }
                    $q_flat = "SELECT Flat_Id from flat where Flat_Status=1 AND Lvl_Id in ('$l_ids')";
                    $q_flat_run = mysqli_query($connection,$q_flat); $f_id_arr=null; $f_ids=null;
                    if(mysqli_num_rows($q_flat_run)>0){
                        while($row_f=mysqli_fetch_assoc($q_flat_run)){
                            $f_id_arr[]=$row_f['Flat_Id'];
                        }
                        $f_ids = implode("', '", $f_id_arr);
                    }
                    // ACTIVITY CATEGORY ID ----------------
                    // get assigned activity percentage
                    $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$f_ids') and Act_Cat_Id in ('$act_cat_id')";            
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
            
            $table .='<td id="td_'.$ctn.'_'.$ctr.'" class="toAct" value='.$blg_id.'> 
                <a  href="#actTable">
                        <input type="hidden" id="td_f" class="td_fId" value='.$blg_id.'>
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
                    $table .=  $blg_row['Blg_Code'].' '.$blg_row['Blg_Name'].' '.$average.'%';
                    $td ++; // count flat
                    // progress bar
                    $table .= ' <div class="progress progress-sm mr-2">
                                <div id="bar" class="progress-bar bg-success" role="progressbar" style="width: '.$average.'%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            </a>
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
    if($q_blg_count!=0)
    {
        $row_ave = $row_ave_sum/$q_blg_count;
        $row_ave = number_format($row_ave, 2, '.', '');
        $tbl_sum = $tbl_sum + $row_ave;
    }
    else{ 
        $row_ave='-';
    }
    $table .= $row_ave.'%';
    if ($i <= $td)
    {
        $i = $td; $td =0; // highest no. of flats
    }
    $td =0;
    $table .='</td>
    </tr>';
    $ctn++;
        }
    }
    if($p_count !=0)
    {
        $tbl_ave = $tbl_sum/$p_count;
        $tbl_ave = number_format($tbl_ave, 2, '.', '');
    }
    else
    {
        echo "No Plex Found";
    }
    $table .= '</tbody>
        <tfoot>
            <th class="d-none"></th>
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
    $plx_id = $_POST['plx_id']; 
    $villa_id = $_POST['villa_id'];
    
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
    if($plx_id=='All'){
        // multiple plex
        $l_q="SELECT * FROM plex where Plx_Status=1 AND Villa_Id='$villa_id' ORDER BY Plx_Code";
    }
    else{
        // single plex
        $l_q="SELECT * FROM plex where Plx_Status=1 AND Plx_Id='$plx_id' ";
    }
    // $l_q="SELECT * FROM level where Lvl_Status=1 AND Blg_Id='$blg_id' ORDER BY Lvl_No DESC";
    // echo $l_q;
    $l_q_run = mysqli_query($connection, $l_q); $k=0; $td=0;$ctn =1;$tbl_sum=0;$tbl_ave=0; 
    $l_count = mysqli_num_rows($l_q_run);
    if(mysqli_num_rows($l_q_run)>0)
    {
        while($l_row = mysqli_fetch_assoc($l_q_run))
        {
            $plex_id= $l_row['Plx_Id'];
            // get buildings(villa)
            $q_blg ="SELECT * FROM building WHERE Blg_Status=1 and Plx_Id='$plex_id'";
            $q_blg_run = mysqli_query($connection, $q_blg); 
            $q_blg_count = mysqli_num_rows($q_blg_run); $row_ave_sum=0;$row_ave=0;
    $table .= '
    <tr id="ttr_'.$ctn.'"> 
        <td class="d-none">'.$l_row['Plx_Code'].'</td> 
        <td >'.$l_row['Plx_Code'].' - '.$l_row['Plx_Name'].'</td>
        ';
            $average=0;$ctr=0;
            if(mysqli_num_rows($q_blg_run)>0)
            {
                while($blg_row = mysqli_fetch_assoc($q_blg_run))
                {
                    $blg_id = $blg_row['Blg_Id']; $ctr++;
                    $q_level = "SELECT Lvl_Id from level WHERE Lvl_Status=1 AND Blg_Id='$blg_id'";
                    $q_level_run = mysqli_query($connection,$q_level); $l_id_arr=null; $l_ids=null;
                    if(mysqli_num_rows($q_level_run)>0){
                        while($row_l = mysqli_fetch_assoc($q_level_run)){
                            $l_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $l_ids = implode("', '", $l_id_arr);
                    }
                    $q_flat = "SELECT Flat_Id from flat where Flat_Status=1 AND Lvl_Id in ('$l_ids')";
                    $q_flat_run = mysqli_query($connection,$q_flat); $f_id_arr=null; $f_ids=null;
                    if(mysqli_num_rows($q_flat_run)>0){
                        while($row_f=mysqli_fetch_assoc($q_flat_run)){
                            $f_id_arr[]=$row_f['Flat_Id'];
                        }
                        $f_ids = implode("', '", $f_id_arr);
                    }
                    // ACTIVITY CATEGORY ID ----------------
                    // get assigned activity percentage
                    $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$f_ids') and Act_Cat_Id in ('$act_cat_id')";            
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
                    $table .='<td id=td_'.$ctn.'_'.$ctr.' class="toAct" value='.$blg_id.'> 
                    <a  href="#actTable">
                    <input type="hidden" id="td_f" class="td_fId" value='.$blg_id.'>
                    ';
                    $average = number_format($average, 2, '.', '');
                    $table .= $blg_row['Blg_Code'].' '.$blg_row['Blg_Name'].' '.$average.'%';
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
    if($q_blg_count!=0)
    {
        $row_ave = $row_ave_sum/$q_blg_count;
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
            <th class="d-none">No.</th>
            <th></th>
            <th id="r_footer" class="text-center">Total</th>
            <th>'.$tbl_ave.'%</th>
        </tfoot>
    </table>
    
    
    <input type="hidden" id="count" value='.$k.'>
    </div>'; 
    echo $table;
}
//ACT CAT - PLEX ALL
if(isset($_POST['all']))
{
    $dept_id = $_POST['ddept_id'];
    $act_cat_id = $_POST['ccat_id'];
    $villa_id = $_POST['villa_id'];
    
    $table = '
    <div class="table-responsive">
        <table class="table table-bordered " id="cat_tbl" width="100%" cellspacing="0">
            <thead>
                <th class="d-none">No.</th>
                <th>Level</th>
                <th id="rh">Room</th>
                <th>Total</th>
            </thead>
            <tbody>';
    $l_q="SELECT * FROM plex where Plx_Status=1 AND Villa_Id='$villa_id' ORDER BY Plx_Code";
    $l_q_run = mysqli_query($connection, $l_q); $k=0; $td=0;$ctn =1;$tbl_sum=0;$tbl_ave=0; 
    $l_count = mysqli_num_rows($l_q_run);
    if(mysqli_num_rows($l_q_run)>0)
    {
        while($l_row = mysqli_fetch_assoc($l_q_run))
        {
            $plex_id= $l_row['Plx_Id'];
            // get buildings(villa)
            $q_blg ="SELECT * FROM building WHERE Blg_Status=1 and Plx_Id='$plex_id'";
            $q_blg_run = mysqli_query($connection, $q_blg); 
            $q_blg_count = mysqli_num_rows($q_blg_run); $row_ave_sum=0;$row_ave=0;
    $table .= '
    <tr id="tr_'.$ctn.'"> 
        <td class="d-none">'.$l_row['Plx_Code'].'</td> 
        <td >'.$l_row['Plx_Code'].' - '.$l_row['Plx_Name'].'</td>
        ';
            $average=0;$ctr=0;
            if(mysqli_num_rows($q_blg_run)>0)
            {
                while($blg_row = mysqli_fetch_assoc($q_blg_run))
                {
                    $blg_id = $blg_row['Blg_Id']; $ctr++;
                    $q_level = "SELECT Lvl_Id from level WHERE Lvl_Status=1 AND Blg_Id='$blg_id'";
                    $q_level_run = mysqli_query($connection,$q_level); $l_id_arr=null; $l_ids=null;
                    if(mysqli_num_rows($q_level_run)>0){
                        while($row_l = mysqli_fetch_assoc($q_level_run)){
                            $l_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $l_ids = implode("', '", $l_id_arr);
                    }
                    $q_flat = "SELECT Flat_Id from flat where Flat_Status=1 AND Lvl_Id in ('$l_ids')";
                    $q_flat_run = mysqli_query($connection,$q_flat); $f_id_arr=null; $f_ids=null;
                    if(mysqli_num_rows($q_flat_run)>0){
                        while($row_f=mysqli_fetch_assoc($q_flat_run)){
                            $f_id_arr[]=$row_f['Flat_Id'];
                        }
                        $f_ids = implode("', '", $f_id_arr);
                    }
                    // ACTIVITY CATEGORY ID ----------------
                    // get assigned activity percentage
                    $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$f_ids') and Act_Cat_Id in ('$act_cat_id')";            
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
                    $table .='<td id=td_'.$ctn.'_'.$ctr.' class="toAct" value='.$blg_id.'> 
                    <a  href="#actTable">
                    <input type="hidden" id="td_f" class="td_fId" value='.$blg_id.'>
                    ';
                    $average = number_format($average, 2, '.', '');
                    $table .= $blg_row['Blg_Code'].' '.$blg_row['Blg_Name'].' '.$average.'%';
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
                $table .='<td class="td_no"></td>';
            }
    $table .= '
        <td>
    ';
    if($q_blg_count!=0)
    {
        $row_ave = $row_ave_sum/$q_blg_count;
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
            <th class="d-none">No.</th>
            <th></th>
            <th id="rf" class="text-center">Total</th>
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
//     $plx_id = $_POST['plx_id']; 
//     $villa_id = $_POST['villa_id'];
//     $act_id= $_POST['act_id'];
    
//     $table = '
//     <div class="table-responsive">
//         <table class="table table-bordered " id="cat_tbl" width="100%" cellspacing="0">
//             <thead>
//                 <th class="d-none">No.</th>
//                 <th>Level</th>
//                 <th id="rh">Room</th>
//                 <th>Total</th>
//             </thead>
//             <tbody>';
//     // BLG ID -------------
//     if($plx_id=='All'){
//         // multiple plex
//         $l_q="SELECT * FROM plex where Plx_Status=1 AND Villa_Id='$villa_id' ORDER BY Plx_Code";
//     }
//     else{
//         // single plex
//         $l_q="SELECT * FROM plex where Plx_Status=1 AND Plx_Id='$plx_id' ";
//     }
//     // $l_q="SELECT * FROM level where Lvl_Status=1 AND Blg_Id='$blg_id' ORDER BY Lvl_No DESC";
//     // echo $l_q;
//     $l_q_run = mysqli_query($connection, $l_q); $k=0; $td=0;$ctn =1;$tbl_sum=0;$tbl_ave=0; 
//     $l_count = mysqli_num_rows($l_q_run);
//     if(mysqli_num_rows($l_q_run)>0)
//     {
//         while($l_row = mysqli_fetch_assoc($l_q_run))
//         {
//             $plex_id= $l_row['Plx_Id'];
//             // get buildings(villa)
//             $q_blg ="SELECT * FROM building WHERE Blg_Status=1 and Plx_Id='$plex_id'";
//             $q_blg_run = mysqli_query($connection, $q_blg); 
//             $q_blg_count = mysqli_num_rows($q_blg_run); $row_ave_sum=0;$row_ave=0;
//     $table .= '
//     <tr id="tr_'.$ctn.'"> 
//         <td class="d-none">'.$l_row['Plx_Code'].'</td> 
//         <td >'.$l_row['Plx_Code'].' - '.$l_row['Plx_Name'].'</td>
//         ';
//             $average=0;$ctr=0;
//             if(mysqli_num_rows($q_blg_run)>0)
//             {
//                 while($blg_row = mysqli_fetch_assoc($q_blg_run))
//                 {
//                     $blg_id = $blg_row['Blg_Id']; $ctr++;
//                     $q_level = "SELECT Lvl_Id from level WHERE Lvl_Status=1 AND Blg_Id='$blg_id'";
//                     $q_level_run = mysqli_query($connection,$q_level); $l_id_arr=null; $l_ids=null;
//                     if(mysqli_num_rows($q_level_run)>0){
//                         while($row_l = mysqli_fetch_assoc($q_level_run)){
//                             $l_id_arr[] = $row_l['Lvl_Id'];
//                         }
//                         $l_ids = implode("', '", $l_id_arr);
//                     }
//                     $q_flat = "SELECT Flat_Id from flat where Flat_Status=1 AND Lvl_Id in ('$l_ids')";
//                     $q_flat_run = mysqli_query($connection,$q_flat); $f_id_arr=null; $f_ids=null;
//                     if(mysqli_num_rows($q_flat_run)>0){
//                         while($row_f=mysqli_fetch_assoc($q_flat_run)){
//                             $f_id_arr[]=$row_f['Flat_Id'];
//                         }
//                         $f_ids = implode("', '", $f_id_arr);
//                     }
//                     // ACTIVITY CATEGORY ID ----------------
//                     // get assigned activity percentage
//                     $q_activity ="SELECT * FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$f_ids') and Act_Cat_Id in ('$act_cat_id') AND Act_Id='$act_id'";            
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
//                     $table .='<td id=td_'.$ctn.'_'.$ctr.' class="toAct" value='.$blg_id.'> 
//                     <a  href="#actTable">
//                     <input type="hidden" id="td_f" class="td_fId" value='.$blg_id.'>
//                     ';
//                     $average = number_format($average, 2, '.', '');
//                     $table .= $blg_row['Blg_Code'].' '.$blg_row['Blg_Name'].' '.$average.'%';
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
//     if($q_blg_count!=0)
//     {
//         $row_ave = $row_ave_sum/$q_blg_count;
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
//             <th class="d-none">No.</th>
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