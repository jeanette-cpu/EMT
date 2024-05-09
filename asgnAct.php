<?php
include('security.php');
// p0050 flats
// $q_flats="SELECT Flat_Id FROM flat as f		
//         LEFT JOIN level as lvl on f.Lvl_Id = lvl.Lvl_Id		
//         LEFT JOIN building as blg on blg.Blg_Id = lvl.Blg_Id		
//         LEFT JOIN plex AS plx on plx.Plx_Id = blg.Plx_Id		
//         LEFT JOIN villa as v on v.Villa_Id = plx.Villa_Id		
//         LEFT JOIN project as prj on prj.Prj_Id = v.Prj_Id		
//         WHERE prj.Prj_Id = 1 AND prj.Prj_Status =1 AND		
//         f.Flat_Status =1 AND		
//         lvl.Lvl_Status =1 AND		
//         blg.Blg_Status=1 AND		
//         plx.Plx_Status=1 AND		
//         v.Villa_Status=1 ORDER BY f.Flat_Id";
// $q_flats_run=mysqli_query($connection,$q_flats);
// if(mysqli_num_rows($q_flats_run)>0){
//     while($row=mysqli_fetch_assoc($q_flats_run)){
//         $flt_id=$row['Flat_Id'];
//         // NEW ACTIVITIES
//         $q_act="SELECT * FROM activity WHERE Act_Id IN ('185','186')";
//         $q_act_run=mysqli_query($connection,$q_act);
//         if(mysqli_num_rows($q_act_run)>0){
//             while($row_a=mysqli_fetch_assoc($q_act_run)){
//                 $act_id=$row_a['Act_Id'];
//                 $act_cat_id=$row_a['Act_Cat_Id'];
//                 //CHECK IF ACTIVITY ALREADY ASSIGNED
//                 $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Act_Id='$act_id' AND Asgd_Act_Status=1";
//                 $q_search_run=mysqli_query($connection,$q_search);
//                 if(mysqli_num_rows($q_search_run)>0){
//                     echo 'already exists: flat '.$flt_id.' activity '.$act_id.'<br>';
//                 }
//                 else{
//                     $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ($flt_id,$act_id,1,$act_cat_id,0)";
//                     $q_insert_run=mysqli_query($connection,$q_insert);
//                     if($q_insert_run){
//                         //inserted
//                     }
//                     else{
//                         echo 'error: flat '.$flt_id.' activity '.$act_id.'<br>';
//                     }
//                 }
//             }
//         }
//         else{ echo 'error finding act ids';}
//     }
// }
// else{ echo 'error finding flt ids';}

$q_flats="SELECT Flat_Id, Flat_Code, Flat_Name FROM flat as f		
        LEFT JOIN level as lvl on f.Lvl_Id = lvl.Lvl_Id		
        LEFT JOIN building as blg on blg.Blg_Id = lvl.Blg_Id		
        LEFT JOIN plex AS plx on plx.Plx_Id = blg.Plx_Id		
        LEFT JOIN villa as v on v.Villa_Id = plx.Villa_Id		
        LEFT JOIN project as prj on prj.Prj_Id = v.Prj_Id		
        WHERE prj.Prj_Id =13 AND prj.Prj_Status =1 AND		
        f.Flat_Status =1 AND		
        lvl.Lvl_Status =1 AND		
        blg.Blg_Status=1 AND		
        plx.Plx_Status=1 AND		
        v.Villa_Status=1 ORDER BY f.Flat_Id";
$q_flats_run=mysqli_query($connection,$q_flats);
if(mysqli_num_rows($q_flats_run)>0){
    while($row=mysqli_fetch_assoc($q_flats_run)){
        $flt_id=$row['Flat_Id'];
        // $flt_code=$row['Flat_Code'];
        // $flt_name=$row['Flat_Name'];

        // echo $flt_id.' '.$flt_code.' '.$flt_name.'<br>';
        // NEW ACTIVITIES
        // $q_act="SELECT * FROM activity WHERE Act_Id IN ('185','186')";
        // $q_act_run=mysqli_query($connection,$q_act);
        $q_act="SELECT * FROM activity WHERE `Act_Status`=1 and Act_Code LIKE '%EV%' OR Act_Code LIKE '%PV%' OR Act_Code LIKE '%HV%'";
        $q_act_run=mysqli_query($connection,$q_act);
        if(mysqli_num_rows($q_act_run)>0){
            while($row_a=mysqli_fetch_assoc($q_act_run)){
                $act_id=$row_a['Act_Id'];
                $act_cat_id=$row_a['Act_Cat_Id'];
                //CHECK IF ACTIVITY ALREADY ASSIGNED
                $q_search="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id' AND Act_Id='$act_id' AND Asgd_Act_Status=1";
                $q_search_run=mysqli_query($connection,$q_search);
                if(mysqli_num_rows($q_search_run)>0){
                    echo 'already exists: flat '.$flt_id.' activity '.$act_id.'<br>';
                }
                else{
                    $q_insert="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id,Asgd_Pct_Done) VALUES ($flt_id,$act_id,1,$act_cat_id,0)";
                    $q_insert_run=mysqli_query($connection,$q_insert);
                    if($q_insert_run){
                        //inserted
                    }
                    else{
                        echo 'error: flat '.$flt_id.' activity '.$act_id.'<br>';
                    }
                }
            }
        }
        else{ echo 'error finding act ids';}
    }
}
else{ echo 'error finding flt ids';}


?>