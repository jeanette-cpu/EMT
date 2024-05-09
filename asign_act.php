<?php
include('security.php');
echo'asign_act.php';
$prj_id=6;
// //GET ALL FLAT ID BY PRJ_ID
// //CHECK WHAT TYPE
// $p="SELECT * FROM project WHERE Prj_Id='$prj_id'";
// $p_run=mysqli_query($connection,$p);
// $row_p=mysqli_fetch_assoc($p_run);
// $cat=$row_p['Prj_Category'];
// // echo $cat;
// if($cat=='Villa'){
//     $q_flats="SELECT f.Flat_Id from project as p
//     LEFT join villa as v on v.Prj_Id=p.Prj_Id
//     LEFT JOIN plex as plx on plx.Villa_Id=v.Villa_Id
//     LEFT JOIN building as blg on blg.Plx_Id = plx.Plx_Id
//     LEFT JOIN level as lvl on lvl.Blg_Id= blg.Blg_Id
//     LEFT JOIN flat as f on f.Lvl_Id= lvl.Lvl_Id
//     WHERE p.Prj_Id=$prj_id";
//     $q_flats_run=mysqli_query($connection,$q_flats);
//     if(mysqli_num_rows($q_flats_run)>0)
//     {
//         while($row_f = mysqli_fetch_assoc($q_flats_run))
//         {
//             // $flat_id_arr[] = $row_f['Flat_Id'];
//             $flat_id=$row_f['Flat_Id'];
//             $a1="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','101','1','1')";
//             $a2="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','102','1','1')";
//             $a3="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','103','1','1')";
//             $a4="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','104','1','1')";
//             $a5="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','105','1','2')";
//             $a6="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','106','1','2')";
//             $a7="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','107','1','2')";
//             $a8="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','108','1','2')";
//             $a9="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','109','1','3')";
//             $a10="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','110','1','3')";
//             $a11="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','111','1','3')";
//             $a12="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','112','1','3')";
//             $a13="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','113','1','6')";
//             $a14="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','114','1','6')";
//             $a15="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','115','1','6')";
//             $a16="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','116','1','6')";
//             $a17="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','117','1','7')";
//             $a18="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','118','1','7')";
//             $a19="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','119','1','7')";
//             $a20="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','120','1','7')";
//             $a21="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','121','1','8')";
//             $a22="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','122','1','8')";
//             $a23="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','123','1','8')";
//             $a24="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','124','1','8')";
//             $a25="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','125','1','6')";
//             $a26="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','126','1','6')";
//             $a27="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','127','1','6')";
//             $a28="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','128','1','11')";
//             $a29="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','129','1','12')";
//             $a30="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','130','1','13')";
//             $a31="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','131','1','1')";
//             $a32="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','132','1','1')";
//             $a33="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','133','1','1')";
//             $a34="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','134','1','1')";
//             $a35="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','135','1','1')";
//             $a36="INSERT INTO `assigned_activity` (Flat_Id,Act_Id,Asgd_Act_Status,Act_Cat_Id) VALUES ('$flat_id','136','1','1')";
//             // $a1_run=mysqli_query($connection,$a1);
//             $a2_run=mysqli_query($connection,$a2);
//             $a3_run=mysqli_query($connection,$a3);
//             $a4_run=mysqli_query($connection,$a4);
//             $a5_run=mysqli_query($connection,$a5);
//             $a6_run=mysqli_query($connection,$a6);
//             $a7_run=mysqli_query($connection,$a7);
//             $a8_run=mysqli_query($connection,$a8);
//             $a9_run=mysqli_query($connection,$a9);
//             $a10_run=mysqli_query($connection,$a10);
//             $a11_run=mysqli_query($connection,$a11);
//             $a12_run=mysqli_query($connection,$a12);
//             $a13_run=mysqli_query($connection,$a13);
//             $a14_run=mysqli_query($connection,$a14);
//             $a15_run=mysqli_query($connection,$a15);
//             $a16_run=mysqli_query($connection,$a16);
//             $a17_run=mysqli_query($connection,$a17);
//             $a18_run=mysqli_query($connection,$a18);
//             $a19_run=mysqli_query($connection,$a19);
//             $a20_run=mysqli_query($connection,$a20);
//             $a21_run=mysqli_query($connection,$a21);
//             $a22_run=mysqli_query($connection,$a22);
//             $a23_run=mysqli_query($connection,$a23);
//             $a24_run=mysqli_query($connection,$a24);
//             $a25_run=mysqli_query($connection,$a25);
//             $a26_run=mysqli_query($connection,$a26);
//             $a27_run=mysqli_query($connection,$a27);
//             $a28_run=mysqli_query($connection,$a28);
//             $a29_run=mysqli_query($connection,$a29);
//             $a30_run=mysqli_query($connection,$a30);
//             $a31_run=mysqli_query($connection,$a31);
//             $a32_run=mysqli_query($connection,$a32);
//             $a33_run=mysqli_query($connection,$a33);
//             $a34_run=mysqli_query($connection,$a34);
//             $a35_run=mysqli_query($connection,$a35);
//             $a36_run=mysqli_query($connection,$a36);
//             // if($a1_run){}else{echo $a1;}
//             if($a2_run){}else{echo $a2;}
//             if($a3_run){}else{echo $a3;}
//             if($a4_run){}else{echo $a4;}
//             if($a5_run){}else{echo $a5;}
//             if($a6_run){}else{echo $a6;}
//             if($a7_run){}else{echo $a7;}
//             if($a8_run){}else{echo $a8;}
//             if($a9_run){}else{echo $a9;}
//             if($a10_run){}else{echo $a10;}
//             if($a11_run){}else{echo $a11;}
//             if($a12_run){}else{echo $a12;}
//             if($a13_run){}else{echo $a13;}
//             if($a14_run){}else{echo $a14;}
//             if($a15_run){}else{echo $a15;}
//             if($a16_run){}else{echo $a16;}
//             if($a17_run){}else{echo $a17;}
//             if($a18_run){}else{echo $a18;}
//             if($a19_run){}else{echo $a19;}
//             if($a20_run){}else{echo $a20;}
//             if($a21_run){}else{echo $a21;}
//             if($a22_run){}else{echo $a22;}
//             if($a23_run){}else{echo $a23;}
//             if($a24_run){}else{echo $a24;}
//             if($a25_run){}else{echo $a25;}
//             if($a26_run){}else{echo $a26;}
//             if($a27_run){}else{echo $a27;}
//             if($a28_run){}else{echo $a28;}
//             if($a29_run){}else{echo $a29;}
//             if($a30_run){}else{echo $a30;}
//             if($a31_run){}else{echo $a31;}
//             if($a32_run){}else{echo $a32;}
//             if($a33_run){}else{echo $a33;}
//             if($a34_run){}else{echo $a34;}
//             if($a35_run){}else{echo $a35;}
//             if($a36_run){}else{echo $a36;}
//         }
//         // $f_ids = implode("', '", $flat_id_arr); 
//     }
// }
// elseif($cat=='Building')
// {

// }
?>