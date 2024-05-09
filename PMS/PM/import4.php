<?php
include('../../security.php');
// bedroom 4
?>
<form action="import.php" method="post" enctype="multipart/form-data" class="mb-3">                     
        <h6 class="text-primary">Material Quantity</h6>
        <input type="file" name="file" required/>
        <input class="mt-1" type="submit" name="import" value="Import"/>
</form>

<?php

if(isset($_POST["import"]))
{
    if($_FILES['file']['name'])
    {
        $filename = explode(".", $_FILES['file']['name']);
        if($filename[1] == 'csv')
        {
            $handle = fopen($_FILES['file']['tmp_name'], "r");
            $success=0; $err_name[]=null; $ctn=0;

            while($data = fgetcsv($handle))
            {
                // 322 flats
                $flat_id= mysqli_real_escape_string($connection, $data[0]);
                // loop all activities   - 1 flat = 96 activities
                $q_asgn_act ="SELECT * FROM assigned_activity where Flat_Id='$flat_id'";
                $q_asgn_act_run = mysqli_query($connection,$q_asgn_act); $ctn=1;
                //loop of 96 activities assigned 
                if(mysqli_num_rows($q_asgn_act_run)>0)
                {
                    while($row=mysqli_fetch_assoc($q_asgn_act_run))
                    {
                        $asgd_id = $row['Asgd_Act_Id'];
                        $act_id = $row['Act_Id'];
                        if($act_id == 3){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    // insert materials
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==3){
                                        $qty = 1;
                                    }
                                    elseif($asgd_mat_id==4){
                                        $qty = 2;
                                    }
                                    elseif($asgd_mat_id==5){
                                        $qty = 2;
                                    }
                                    elseif($asgd_mat_id==6){
                                        $qty = 2;
                                    }
                                    elseif($asgd_mat_id==7){
                                        $qty = 1;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert);
                                }
                            }
                        }
                        if($act_id ==4){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==8){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==9){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==10){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==11){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==12){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==13){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==14){
                                        $qty =1 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==5){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==15){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==16){
                                        $qty =21 ;
                                    } 
                                        elseif($asgd_mat_id==17){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==18){
                                        $qty =23 ;
                                    }                                     
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                            }
                        }
                        if($act_id ==6){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==19){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==20){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==21){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==22){
                                        $qty =23 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                            }
                        }
                        if($act_id ==7){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==23){
                                        $qty =57 ;
                                    } 
                                    elseif($asgd_mat_id==24){
                                        $qty =31 ;
                                    } 
                                    elseif($asgd_mat_id==25){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==26){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==27){
                                        $qty =306 ;
                                    } 
                                    elseif($asgd_mat_id==28){
                                        $qty =114 ;
                                    } 
                                    elseif($asgd_mat_id==29){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==30){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==31){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==32){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==33){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==34){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==35){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==36){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==37){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==38){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==39){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==40){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==41){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==42){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==43){
                                        $qty =3 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==8){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==44){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==45){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==46){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==47){
                                        $qty =23 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                            }
                        }
                        if($act_id ==9){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==48){
                                        $qty =51 ;
                                    } 
                                    elseif($asgd_mat_id==49){
                                        $qty =26 ;
                                    } 
                                    elseif($asgd_mat_id==50){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==51){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==52){
                                        $qty =306 ;
                                    } 
                                    elseif($asgd_mat_id==53){
                                        $qty =114 ;
                                    } 
                                    elseif($asgd_mat_id==54){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==55){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==56){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==57){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==58){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==59){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==60){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==61){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==62){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==63){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==64){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==65){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==66){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==67){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==68){
                                        $qty =3 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==10){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==69){
                                        $qty =21 ;
                                    } 
                                    elseif($asgd_mat_id==70){
                                        $qty =21 ;
                                    } 
                                    elseif($asgd_mat_id==71){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==72){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==73){
                                        $qty =23 ;
                                    } 
                                    elseif($asgd_mat_id==74){
                                        $qty =2 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==11){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==75){
                                        $qty =0.5 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==12){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==76){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==77){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==78){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==79){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==80){
                                        $qty =2 ;
                                    }         
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==13){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==81){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==82){
                                        $qty =65 ;
                                    } 
                                    elseif($asgd_mat_id==83){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==84){
                                        $qty =7 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==14){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==85){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==86){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==87){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==88){
                                        $qty =23 ;
                                    } 
                                    elseif($asgd_mat_id==89){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==90){
                                        $qty =75 ;
                                    } 
                                    elseif($asgd_mat_id==91){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==92){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==93){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==94){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==15){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==95){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==96){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==97){
                                        $qty =23 ;
                                    }                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==16){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==98){
                                        $qty =31 ;
                                    } 
                                    elseif($asgd_mat_id==99){
                                        $qty =31 ;
                                    } 
                                    elseif($asgd_mat_id==100){
                                        $qty =71 ;
                                    } 
                                    elseif($asgd_mat_id==101){
                                        $qty =53 ;
                                    }    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==17){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==102){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==103){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==104){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==105){
                                        $qty =33 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==19){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==106){
                                        $qty =13 ;
                                    } 
                                    elseif($asgd_mat_id==107){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==108){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==109){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==110){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==20){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==111){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==112){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==113){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==114){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==21){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==115){
                                        $qty =21 ;
                                    } 
                                    elseif($asgd_mat_id==116){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==117){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==118){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==119){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==120){
                                        $qty =28 ;
                                    } 
                                    elseif($asgd_mat_id==121){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==122){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==123){
                                        $qty =40 ;
                                    } 
                                    elseif($asgd_mat_id==124){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==125){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==126){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==127){
                                        $qty =1 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==22){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==128){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==129){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==130){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==131){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==132){
                                        $qty =5 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==23){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==133){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==134){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==135){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==136){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==137){
                                        $qty =5 ;
                                    }                                     
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==24){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==138){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==139){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==140){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==141){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==142){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==143){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==144){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==145){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==146){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==147){
                                        $qty =15 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==25){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==148){
                                        $qty =115 ;
                                    } 
                                    elseif($asgd_mat_id==149){
                                        $qty =19 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==27){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==150){
                                        $qty =30 ;
                                    } 
                                    elseif($asgd_mat_id==151){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==152){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==153){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==154){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==155){
                                        $qty =2 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==28){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==156){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==157){
                                        $qty =20 ;
                                    } 
                                    elseif($asgd_mat_id==158){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==159){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==160){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==161){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==162){
                                        $qty =10 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==29){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==163){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==164){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==165){
                                        $qty =0.14 ;
                                    } 
                                    elseif($asgd_mat_id==166){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==167){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==168){
                                        $qty =1 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        
                        if($act_id ==31){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==169){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==170){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==171){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==172){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==173){
                                        $qty =20 ;
                                    } 
                                    elseif($asgd_mat_id==174){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==175){
                                        $qty =19 ;
                                    } 
                                    elseif($asgd_mat_id==176){
                                        $qty =30 ;
                                    } 

                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==32){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==177){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==178){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==179){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==180){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==181){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==182){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==183){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==184){
                                        $qty =6 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==33){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==185){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==186){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==187){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==188){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==189){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==190){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==191){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==192){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==193){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==194){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==195){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==196){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==197){
                                        $qty =24 ;
                                    } 
                                    elseif($asgd_mat_id==198){
                                        $qty =24 ;
                                    } 
                                    elseif($asgd_mat_id==199){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==200){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==201){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==202){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==203){
                                        $qty =16 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==34){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==204){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==205){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==206){
                                        $qty =4 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==35){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==207){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==208){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==209){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==210){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==211){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==212){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==213){
                                        $qty =19 ;
                                    } 
                                    elseif($asgd_mat_id==214){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==215){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==216){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==217){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==218){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==219){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==220){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==221){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==222){
                                        $qty =19 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==36){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==223){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==224){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==225){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==226){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==227){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==228){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==229){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==230){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==231){
                                        $qty =115 ;
                                    } 
                                    elseif($asgd_mat_id==232){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==233){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==234){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==235){
                                        $qty =14 ;
                                    } 
                                    elseif($asgd_mat_id==236){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==237){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==238){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==239){
                                        $qty =1 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==37){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==240){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==241){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==242){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==243){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==244){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==245){
                                        $qty =6;
                                    } 
                                    elseif($asgd_mat_id==246){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==247){
                                        $qty =3 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==38){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==249){
                                        $qty =0 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==39){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==248){
                                        $qty =1 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        
                        if($act_id ==42){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==250){
                                        $qty =0.8 ;
                                    } 
                                    elseif($asgd_mat_id==251){
                                        $qty =2.4 ;
                                    } 
                                    elseif($asgd_mat_id==252){
                                        $qty =2.4 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==43){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==253){
                                        $qty =45 ;
                                    } 
                                    elseif($asgd_mat_id==254){
                                        $qty =20 ;
                                    } 
                                    elseif($asgd_mat_id==255){
                                        $qty =22 ;
                                    } 
                                    elseif($asgd_mat_id==256){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==257){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==258){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==259){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==260){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==261){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==262){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==263){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==264){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==265){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==266){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==267){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==268){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==269){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==270){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==271){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==272){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==273){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==274){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==275){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==276){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==277){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==278){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==279){
                                        $qty =1.8 ;
                                    } 
                                    elseif($asgd_mat_id==280){
                                        $qty =1.8 ;
                                    } 
                                    elseif($asgd_mat_id==281){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==282){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==283){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==284){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==285){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==286){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==287){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==288){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==289){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==290){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==291){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==292){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==293){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==294){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==295){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==296){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==297){
                                        $qty =1 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==44){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==298){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==299){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==300){
                                        $qty =0 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==46){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==301){
                                        $qty =2 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==48){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==302){
                                        $qty =79.2 ;
                                    } 
                                    elseif($asgd_mat_id==303){
                                        $qty =37 ;
                                    } 
                                    elseif($asgd_mat_id==304){
                                        $qty =25.6 ;
                                    } 
                                    elseif($asgd_mat_id==305){
                                        $qty =28 ;
                                    } 
                                    elseif($asgd_mat_id==306){
                                        $qty =28 ;
                                    } 
                                    elseif($asgd_mat_id==307){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==308){
                                        $qty =13 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==49){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==309){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==310){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==311){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==312){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==313){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==314){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==315){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==316){
                                        $qty =16.7 ;
                                    } 
                                    elseif($asgd_mat_id==317){
                                        $qty =11.5 ;
                                    } 
                                    elseif($asgd_mat_id==318){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==319){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==320){
                                        $qty =8.5 ;
                                    } 
                                    elseif($asgd_mat_id==321){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==322){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==323){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==324){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==325){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==326){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==327){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==328){
                                        $qty =23 ;
                                    } 
                                    elseif($asgd_mat_id==329){
                                        $qty =23 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            } 
                        }
                        if($act_id ==50){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==330){
                                        $qty =4.1 ;
                                    } 
                                    elseif($asgd_mat_id==331){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==332){
                                        $qty =4.6 ;
                                    } 
                                    elseif($asgd_mat_id==333){
                                        $qty =3.2 ;
                                    } 
                                    elseif($asgd_mat_id==334){
                                        $qty =3.6 ;
                                    } 
                                    elseif($asgd_mat_id==335){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==336){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==337){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==338){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==339){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==340){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==341){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==342){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==343){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==344){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==345){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==346){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==347){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==348){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==349){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==350){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==351){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==352){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==353){
                                        $qty =6 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==51){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==354){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==355){
                                        $qty =8.5 ;
                                    } 
                                    elseif($asgd_mat_id==356){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==357){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==358){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==359){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==360){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==361){
                                        $qty =11.5 ;
                                    } 
                                    elseif($asgd_mat_id==362){
                                        $qty =1 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==53){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==363){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==364){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==365){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==366){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==367){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==368){
                                        $qty =2.8 ;
                                    } 
                                    elseif($asgd_mat_id==369){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==370){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==371){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==372){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==373){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==374){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==375){
                                        $qty =2 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==54){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==376){
                                        $qty =6.7 ;
                                    } 
                                    elseif($asgd_mat_id==377){
                                        $qty =5.8 ;
                                    } 
                                    elseif($asgd_mat_id==378){
                                        $qty =13.5 ;
                                    } 
                                    elseif($asgd_mat_id==379){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==380){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==381){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==382){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==383){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==384){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==385){
                                        $qty =2 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==55){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==386){
                                        $qty =27.2 ;
                                    } 
                                    elseif($asgd_mat_id==387){
                                        $qty =1.5 ;
                                    } 
                                    elseif($asgd_mat_id==388){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==389){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==390){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==391){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==392){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==393){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==394){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==395){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==396){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==397){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==398){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==399){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==400){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==401){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==402){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==403){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==404){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==405){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==406){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==407){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==408){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==409){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==410){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==411){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==412){
                                        $qty =6.5 ;
                                    } 
                                    elseif($asgd_mat_id==413){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==414){
                                        $qty =7 ;
                                    } 
                                    elseif($asgd_mat_id==415){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==416){
                                        $qty =29 ;
                                    } 
                                    elseif($asgd_mat_id==417){
                                        $qty =22 ;
                                    } 
                                    elseif($asgd_mat_id==418){
                                        $qty =8 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==57){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==419){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==420){
                                        $qty =6.6 ;
                                    } 
                                    elseif($asgd_mat_id==421){
                                        $qty =61.2 ;
                                    } 
                                    elseif($asgd_mat_id==422){
                                        $qty =32.3 ;
                                    } 
                                    elseif($asgd_mat_id==423){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==424){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==425){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==426){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==427){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==428){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==429){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==430){
                                        $qty =13 ;
                                    } 
                                    elseif($asgd_mat_id==431){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==432){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==433){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==434){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==435){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==436){
                                        $qty =17 ;
                                    } 
                                    elseif($asgd_mat_id==437){
                                        $qty =14 ;
                                    } 
                                    elseif($asgd_mat_id==438){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==439){
                                        $qty =13 ;
                                    } 
                                    elseif($asgd_mat_id==440){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==441){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==442){
                                        $qty =25 ;
                                    } 
                                    elseif($asgd_mat_id==443){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==444){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==445){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==446){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==447){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==448){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==449){
                                        $qty =5 ;
                                    } 
                                    elseif($asgd_mat_id==450){
                                        $qty =11 ;
                                    } 
                                    elseif($asgd_mat_id==451){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==452){
                                        $qty =9 ;
                                    } 
                                    elseif($asgd_mat_id==453){
                                        $qty =115 ;
                                    } 
                                    elseif($asgd_mat_id==454){
                                        $qty =65 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==58){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==455){
                                        $qty =5.1 ;
                                    } 
                                    elseif($asgd_mat_id==456){
                                        $qty =10.4 ;
                                    } 
                                    elseif($asgd_mat_id==457){
                                        $qty =39.8 ;
                                    } 
                                    elseif($asgd_mat_id==458){
                                        $qty =42 ;
                                    } 
                                    elseif($asgd_mat_id==459){
                                        $qty =15 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==59){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==460){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==461){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==462){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==463){
                                        $qty =0.3 ;
                                    } 
                                    elseif($asgd_mat_id==464){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==465){
                                        $qty =0.3 ;
                                    } 
                                    elseif($asgd_mat_id==466){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==467){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==468){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==60){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==469){
                                        $qty =1 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==61){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==470){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==471){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==472){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==63){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==473){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==474){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==475){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==476){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==64){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==477){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==478){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==65){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==479){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==480){
                                        $qty =2.5 ;
                                    } 
                                    elseif($asgd_mat_id==481){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==482){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==483){
                                        $qty =1.5 ;
                                    } 
                                    elseif($asgd_mat_id==484){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==485){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==486){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==487){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==488){
                                        $qty =2 ;
                                    } 
                                    elseif($asgd_mat_id==489){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==490){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==491){
                                        $qty =0 ;
                                    } 
                                    elseif($asgd_mat_id==492){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==493){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==494){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==495){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==496){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==497){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==498){
                                        $qty =4 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==67){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==499){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==500){
                                        $qty =4 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==68){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==501){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==502){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        
                        if($act_id ==71){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==503){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==504){
                                        $qty =2 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==74){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==505){
                                        $qty =66 ;
                                    } 
                                    elseif($asgd_mat_id==506){
                                        $qty =82 ;
                                    } 
                                    elseif($asgd_mat_id==507){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==508){
                                        $qty =25 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==76){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==509){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==510){
                                        $qty =8 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==77){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==511){
                                        $qty =55 ;
                                    } 
                                    elseif($asgd_mat_id==512){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==513){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==514){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==515){
                                        $qty =1 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==78){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==516){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==517){
                                        $qty =24 ;
                                    } 
                                    elseif($asgd_mat_id==518){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==519){
                                        $qty =10 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==79){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==520){
                                        $qty =41 ;
                                    } 
                                    elseif($asgd_mat_id==521){
                                        $qty =22 ;
                                    } 
                                    elseif($asgd_mat_id==522){
                                        $qty =70 ;
                                    } 
                                    elseif($asgd_mat_id==523){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==524){
                                        $qty =3 ;
                                    } 
                                    elseif($asgd_mat_id==525){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==526){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==527){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==80){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==528){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==529){
                                        $qty =1 ;
                                    } 
                                    elseif($asgd_mat_id==530){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==81){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==531){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==532){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==533){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==534){
                                        $qty =3;
                                    } 
                                    elseif($asgd_mat_id==535){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==82){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==536){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==537){
                                        $qty =16 ;
                                    } 
                                    elseif($asgd_mat_id==538){
                                        $qty =4 ;
                                    } 
                                    elseif($asgd_mat_id==539){
                                        $qty =4 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==83){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==540){
                                        $qty =40 ;
                                    } 
                                    elseif($asgd_mat_id==541){
                                        $qty =45 ;
                                    } 
                                    elseif($asgd_mat_id==542){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==84){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==543){
                                        $qty =8 ;
                                    } 
                                    elseif($asgd_mat_id==544){
                                        $qty =8 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==85){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==545){
                                        $qty =20 ;
                                    } 
                                    elseif($asgd_mat_id==546){
                                        $qty =1 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==86){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==547){
                                        $qty =10 ;
                                    } 
                                    elseif($asgd_mat_id==548){
                                        $qty =1 ;
                                    } 

                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==88){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==549){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==550){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==551){
                                        $qty =6 ;
                                    } 
                                    elseif($asgd_mat_id==552){
                                        $qty =12 ;
                                    } 
                                    elseif($asgd_mat_id==553){
                                        $qty =6 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==89){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==554){
                                        $qty =32 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==90){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==555){
                                        $qty =24 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==91){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];

                                    if($asgd_mat_id==556){
                                        $qty =3 ;
                                    }

                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==93){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==557){
                                        $qty =4 ;
                                    } 
                                    
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        if($act_id ==94){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            echo $q_asgn_all_mat.'<br>';
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==558){
                                        $qty =4 ;
                                    }
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
                                    echo $q_insert.' <br> qty <br>'.$qty.'<br>'.'asgd_id = '.$asgd_mat_id;
                                    $q_insert_run =mysqli_query($connection,$q_insert); 
                                }
                                
                            }
                        }
                        
                    }
                }
            } 
            fclose($handle);
        }
    }
}
?>