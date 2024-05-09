<?php
include('security.php');
// project 62 local host 
// Plex ID
// A - 98
// B - 99

$prj_query="SELECT * FROM villa WHERE Prj_Id='13'";
$prj_query_run=mysqli_query($connection, $prj_query);

if(mysqli_num_rows($prj_query_run)>0){
    while($row_v=mysqli_fetch_assoc($prj_query_run))
    {
        $v_id=$row_v['Villa_Id'];
        if($v_id){
            $q_plex="SELECT * FROM plex WHERE Villa_Id='$v_id'";
            $q_plex_run=mysqli_query($connection, $q_plex);
            if(mysqli_num_rows($q_plex_run)){
                while($row_p=mysqli_fetch_assoc($q_plex_run)){
                    $plx_id=$row_p['Plx_Id'];
                    $plx_name=$row_p['Plx_Code'];
                    // echo $plx_name.'<br>';
                    if($plx_id){
                        $q_blg="SELECT * FROM building WHERE Plx_Id='$plx_id' AND Blg_Status=1";
                        $q_blg_run=mysqli_query($connection,$q_blg);
                        if(mysqli_num_rows($q_blg_run)>0){
                            while($row_b=mysqli_fetch_assoc($q_blg_run)){
                                $blg_id=$row_b['Blg_Id']; // villa talaga ito
                                $blg_code=$row_b['Blg_Code'];
                                $blg_name=$row_b['Blg_Name'];
                                //INSERTS
                                // echo $blg_code.' '.$blg_name.'<br>';
                                //check if there are levels
                                $q_lvl_chk="SELECT * FROM level WHERE Blg_Id='$blg_id' AND Lvl_Status=1";
                                $q_lvl_chk_run=mysqli_query($connection,$q_lvl_chk);
                                if(mysqli_num_rows($q_lvl_chk_run)>0){
                                    //level exists
                                    while($row_l=mysqli_fetch_assoc($q_lvl_chk_run)){
                                        $lvl_id=$row_l['Lvl_Id'];
                                        $lvl_code=$row_l['Lvl_Code'];
                                        $lvl_name=$row_l['Lvl_Name'];
                                        // echo $lvl_code.' '.$lvl_name;
                                        // check if flat exist
                                        $q_flat_chk="SELECT * FROM flat WHERE Lvl_Id='$lvl_id'";
                                        $q_flat_chk_run=mysqli_query($connection,$q_flat_chk);
                                        if(mysqli_num_rows($q_flat_chk_run)>0){
                                            while($row_f=mysqli_fetch_assoc($q_flat_chk_run)){
                                                $flt_id=$row_f['Flat_Id'];
                                            }
                                        }
                                        else{// insert flat
                                            $insert_flt="INSERT INTO flat (Flat_Code, Flat_Name, Flat_Status, Lvl_Id) VALUES ('$lvl_code','$lvl_name','1','$lvl_id');";
                                            // echo $insert_flt.'<br>';
                                            $insert_flt_run=mysqli_query($connection,$insert_flt);
                                            if($insert_flt_run){
                                            }
                                            else{
                                                echo 'error inserting flat'.$insert_flt.'<br>'.$lvl_code.' '.$lvl_name;
                                            }
                                        }
                                    }
                                }
                                else{
                                    $q_insert="INSERT INTO level (Lvl_Code, Lvl_Name, Lvl_Status, Blg_Id) VALUES ('$blg_code','$blg_name','1','$blg_id');";
                                    $q_insert_run=mysqli_query($connection,$q_insert);
                                    if($q_insert_run){
                                        // echo $q_insert.'<br>'  ;
                                        // SEARCH
                                        // $q_lvl="SELECT * FROM level WHERE Blg_Id='$blg_id'";
                                        // $q_lvl_run=mysqli_query($connection,$q_lvl);
                                        // if(mysqli_num_rows($q_lvl_run)>0){
                                        //     while($row_l=mysqli_fetch_assoc($q_lvl_run)){
                                        //         $lvl_id=$row_l['Lvl_Id'];
                                        //         // INSERT 
                                        //         // $insert_flt="INSERT INTO flat (Flat_Code, Flat_Name, Flat_Status, Lvl_Id) VALUES ('$blg_code','$blg_name','1','$lvl_id');";
                                        //         // echo $insert_flt.'<br>';
                                        //         // SELECT 
                                        //         $q_flat="SELECT * FROM flat WHERE Lvl_Id='$lvl_id'";
                                        //         $q_flat_run=mysqli_query($connection,$q_flat);
                                        //         if(mysqli_num_rows($q_flat_run)>0){
                                        //             while($row_f=mysqli_fetch_assoc($q_flat_run)){
                                        //                 $flt_id=$row_f['Flat_Id'];
                                                        
                                        //             }
                                        //         }
                                        //     }
                                        // }
                                    }
                                    else{
                                        echo 'error insert level on blg'.$blg_code.' '.$blg_name;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }   
}
?>