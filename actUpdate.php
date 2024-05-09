<?php
include('security.php');
// p0050 flats
$q_flats="SELECT Flat_Id FROM flat as f		
        LEFT JOIN level as lvl on f.Lvl_Id = lvl.Lvl_Id		
        LEFT JOIN building as blg on blg.Blg_Id = lvl.Blg_Id		
        LEFT JOIN plex AS plx on plx.Plx_Id = blg.Plx_Id		
        LEFT JOIN villa as v on v.Villa_Id = plx.Villa_Id		
        LEFT JOIN project as prj on prj.Prj_Id = v.Prj_Id		
        WHERE prj.Prj_Id = 1 AND prj.Prj_Status =1 AND		
        f.Flat_Status =1 AND		
        lvl.Lvl_Status =1 AND		
        blg.Blg_Status=1 AND		
        plx.Plx_Status=1 AND		
        v.Villa_Status=1 ORDER BY f.Flat_Id";
$q_flats_run=mysqli_query($connection,$q_flats);
date_default_timezone_set('Asia/Dubai');
// date today
$Date = date('Y-m-d'); 
if(mysqli_num_rows($q_flats_run)>0){
    while($row=mysqli_fetch_assoc($q_flats_run)){
        $flt_id=$row['Flat_Id'];
        //100% activites
        // $q_act="SELECT Act_Id FROM activity WHERE Act_Id IN ('5')";
        $q_act="SELECT Act_Id FROM activity WHERE Act_Id IN ('10','12','13','14','15','16','18','19','20','21','99','169','22','23','24','25','26','27','28','29','170','171','30','31','32','33','34','35')";
        $q_act_run=mysqli_query($connection,$q_act);
        if(mysqli_num_rows($q_act_run)>0){
            while($row_a=mysqli_fetch_assoc($q_act_run)){
                $act_id=$row_a['Act_Id'];
                // diff %
                if($act_id==10){
                    $percent=10;
                }
                elseif($act_id==12){$percent=10;}
                elseif($act_id==99){$percent=90;}
                elseif($act_id==22){$percent=56;}
                elseif($act_id==23){$percent=55;}
                elseif($act_id==26){$percent=70;}
                elseif($act_id==27){$percent=11;}
                elseif($act_id==171){$percent=37;}
                elseif($act_id==35){$percent=7;}
                else{
                    echo 'error'.$act_id;
                }
                //search for Assigned ID
                $q_asgn_act_id = "SELECT * FROM assigned_activity where Act_Id='$act_id' and Flat_Id='$flt_id' limit 1";
                $query_run1 = mysqli_query($connection, $q_asgn_act_id);
                if($query_run1){
                    $row1 = mysqli_fetch_assoc($query_run1);
                    $last_prct = $row1['Asgd_Pct_Done'];
                    $asgd_act_id = $row1['Asgd_Act_Id'];
                    if($last_prct==100){ // if already 100
                        // echo '100';
                        echo 'act ID:'.$act_id.' Flat'.$flt_id.' Asgd_Act'.$asgd_act_id.'<br>';
                        $q_chk="SELECT * FROM daily_entery WHERE Asgd_Act_Id='$asgd_act_id'";
                        $q_chk_run=mysqli_query($connection,$q_chk);
                        if(mysqli_num_rows($q_chk_run)>0){
                        }
                        else{
                            $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$percent', Asgd_Act_Date_Completed='' where Asgd_Act_Id='$asgd_act_id'";
                            $q2_run = mysqli_query($connection, $q2);
                            if($q2_run){
                                // ECHO 'SUCCESS';
                            }
                            else{
                                echo 'error2: flat id: '.$flt_id.' act id:'.$act_id.'<br>';
                            }
                        }
                        
                    }
                    else{
                        // update asgd_activity table
                        $q2="UPDATE assigned_activity SET Asgd_Pct_Done='$percent', Asgd_Act_Date_Completed='' where Asgd_Act_Id='$asgd_act_id'";
                        $q2_run = mysqli_query($connection, $q2);
                        if($q2_run){
                            // ECHO 'SUCCESS';
                        }
                        else{
                            echo 'error2: flat id: '.$flt_id.' act id:'.$act_id.'<br>';
                        }
                    }
                }
                else{
                    echo 'error1: flat id: '.$flt_id.' act id:'.$act_id.'<br>';
                }
                
            }
        }
    }
}
?>