<?php
include('security.php');
$project_code='P0060';  // change this
$q_project_id="SELECT * FROM project WHERE Prj_Code like '%P0060%' AND Prj_Status=1";
$q_prj_run=mysqli_query($connection, $q_project_id);
if(mysqli_num_rows($q_prj_run)>0){
    while($row_p=mysqli_fetch_assoc($q_prj_run)){
        $prj_id=$row_p['Prj_Id'];
        $prj_name=$row_p['Prj_Name'];
        $prj_code=$row_p['Prj_Code'];
        $prj_cat=$row_p['Prj_Category'];
        // echo $prj_code.' '.$prj_name;
        if($prj_cat=='Villa'){
            // search for villa ids
            $q_villa="SELECT Villa_Id FROM villa WHERE Prj_Id='$prj_id' AND Villa_Status=1";
            $q_villa_run=mysqli_query($connection,$q_villa);
            if(mysqli_num_rows($q_villa_run)>0){
                while($row_v=mysqli_fetch_assoc($q_villa_run)){
                    $villa_id_arr[]=$row_v['Villa_Id'];
                }
                $villa_ids=implode("', '", $villa_id_arr); 
            }
           $q_plx="SELECT * FROM plex 
                    WHERE  Plx_Status=1 AND Villa_Id in ('$villa_ids')";
            $q_plx_run=mysqli_query($connection,$q_plx);
            if(mysqli_num_rows($q_plx_run)>0){
                while($row_p=mysqli_fetch_assoc($q_plx_run)){
                    $plx_id=$row_p['Plx_Id'];
                    $plx_code=$row_p['Plx_Code'];
                    $plx_name=$row_p['Plx_Name'];
                    // echo $plx_code.' '.$plx_name;
                    //select building
                    $q_villa_b="SELECT * FROM building WHERE Blg_Status=1 AND Plx_Id='$plx_id'";
                    $q_villa_b_run=mysqli_query($connection,$q_villa_b);
                    if(mysqli_num_rows($q_villa_b_run)>0){
                        while($row_vb=mysqli_fetch_assoc($q_villa_b_run)>0){
                            $blg_id=$row_vb['Blg_Id'];
                            $blg_code=$row_vb['Blg_Code'];
                            $blg_name=$row_vb['Blg_Name'];
                            echo $blg_code.' '.$blg_name;
                            // //check if theres level exist
                            // $q_lvl_chk="SELECT * FROM level WHERE Blg_Id='$blg_id' AND Lvl_Status=1";
                            // $q_lvl_chk_run=mysqli_query($connection,$q_lvl_chk);
                            // if(mysqli_num_rows($q_lvl_chk_run)>0){
                            //     while($row_l=mysqli_fetch_assoc($q_lvl_chk_run)){
                            //         //lvl exists check if flat exits
                            //         $lvl_id=$row_l['Lvl_Id'];
                            //         $lvl_code=$row_l['Lvl_Code'];
                            //         $lvl_name=$row_l['Lvl_Name'];
                            //         $q_flt_chk="SELECT * FROM flat WHERE Lvl_Id='$lvl_id' AND Flat_Status=1";
                            //         $q_flt_chk_run=mysqli_query($connection,$q_flt_chk);
                            //         if(mysqli_num_rows($q_flt_chk_run)>0){
                            //             // flats exists in villa prj - 
                            //         }
                            //         else{
                            //             // insert flat
                            //             $q_insert_flat="INSERT INTO flat (Flat_Code, Flat_Name, Flat_Status, Lvl_Id) VALUES ('$lvl_code','$lvl_name','1','$lvl_id')";
                            //             $q_insert_flat_run=mysqli_query($connection,$q_insert_flat);
                            //             if($q_insert_flat_run){
                            //             }
                            //             else{
                            //                 echo 'error inserting flat'.$blg_code.' '.$blg_name;
                            //             }
                            //         }
                            //     }
                            //     // echo 'villa '.$blg_code.' '.$blg_name.' level exists';
                            // }
                            // else{// insert level
                            //     $blg_code=$row_b['Blg_Code'];
                            //     $blg_name=$row_b['Blg_Name'];
                            //     $q_insert_lvl="INSERT INTO level (Lvl_Code,Lvl_Name,Lvl_Status,Blg_Id) VALUES ('$blg_code','$blg_name','1',$blg_id)";
                            //     if ($connection->query($q_insert_lvl) == TRUE) {
                            //         $lvl_id = $connection->insert_id;
                            //         //success
                            //         // insert flat
                            //         $q_insert_flat="INSERT INTO flat (Flat_Code, Flat_Name, Flat_Status, Lvl_Id) VALUES ('$lvl_code','$lvl_name','1','$lvl_id')";
                            //         $q_insert_flat_run=mysqli_query($connection,$q_insert_flat);
                            //         if($q_insert_flat_run){ // sucess
                            //         }
                            //         else{
                            //             echo 'error inserting flat'.$blg_code.' '.$blg_name;
                            //         }
                            //     }
                            //     else{
                            //         echo 'error inserting level'.$blg_code.' '.$blg_name;
                            //     }
                            // }
                        }
                    }
                    else{
                        echo 'no buildings found'.$plx_code.' '.$plx_name;
                    }
                }
            }
        }
        elseif($prj_cat=='Building'){

        }
        else{
            echo 'unknown project category';
        }
        
    }
}
else{
    echo 'no project detected';
}
?>