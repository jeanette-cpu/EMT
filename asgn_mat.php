<?php
include('security.php');
//SELECT THE FLAT TYPE
$q_flats="SELECT Flat_Id FROM flat WHERE Flat_Id in ('944')";
$q_flat_run=mysqli_query($connection,$q_flats);

if(mysqli_num_rows($q_flat_run)>0)
{
    //run for every flat
    while($row_f = mysqli_fetch_assoc($q_flat_run))
    {
        $flt_id=$row_f['Flat_Id'];
        //output all assigned activity
        $q_asgn_act="SELECT * FROM assigned_activity WHERE Flat_Id='$flt_id'"; 
        $q_asgn_act_run=mysqli_query($connection,$q_asgn_act);
        if(mysqli_num_rows($q_asgn_act_run)>0){
            while($row_act=mysqli_fetch_assoc($q_asgn_act_run)){
                //find activity ID
                $act_id=$row_act['Act_Id'];
                $asgd_act_id= $row_act['Asgd_Act_Id'];
                //assign the material
                if($act_id==42){
                    // search for asgd_mat_id for       --each material assigned
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11557){
                                $qty=1.4;}
elseif($mat_id==11550){
                                $qty=7.7;}
elseif($mat_id==11547){
                                $qty=1.4;}

elseif($mat_id==11780){
                                $qty=0.6;}
elseif($mat_id==11781){
                                $qty=2.2;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==43){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11536){
                                $qty=42;}
elseif($mat_id==11577){
                                $qty=17;}
elseif($mat_id==11561){
                                $qty=12;}
elseif($mat_id==11535){
                                $qty=6;}
elseif($mat_id==11533){
                                $qty=10;}
elseif($mat_id==11539){
                                $qty=10;}
elseif($mat_id==11542){
                                $qty=6;}
elseif($mat_id==11573){
                                $qty=8;}
elseif($mat_id==11576){
                                $qty=3;}
elseif($mat_id==11564){
                                $qty=8;}
elseif($mat_id==11565){
                                $qty=11;}
elseif($mat_id==11617){
                                $qty=5;}
elseif($mat_id==11538){
                                $qty=1;}
elseif($mat_id==11571){
                                $qty=7;}
elseif($mat_id==11611){
                                $qty=14;}
elseif($mat_id==11613){
                                $qty=3;}
elseif($mat_id==11612){
                                $qty=8;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==48){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11546){
                                $qty=78.8;}
elseif($mat_id==11549){
                                $qty=24.6;}
elseif($mat_id==11548){
                                $qty=54.2;}
elseif($mat_id==11633){
                                $qty=28;}
elseif($mat_id==11530){
                                $qty=28;}
elseif($mat_id==11631){
                                $qty=28;}
elseif($mat_id==11632){
                                $qty=28;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==57){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11732){
                                $qty=1;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==99){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11773){
                                $qty=1;}
elseif($mat_id==11774){
                                $qty=1;}
elseif($mat_id==11775){
                                $qty=1;}
elseif($mat_id==11776){
                                $qty=1;}
elseif($mat_id==11777){
                                $qty=1;}
elseif($mat_id==11778){
                                $qty=1;}
elseif($mat_id==11779){
                                $qty=1;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==63){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11666){
                                $qty=1;}
                                
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==64){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11600){
                                $qty=1;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==61){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11634){
                                $qty=1;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==65){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11627){
                                $qty=1;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
                                }
                            }
                        }
                    }
                }
                elseif($act_id==67){
                    $q_asm="SELECT * FROM assigned_material where Act_Id='$act_id'";
                    $q_asm_run=mysqli_query($connection,$q_asm);
                    if(mysqli_num_rows($q_asm_run)>0){
                        while($row_asm=mysqli_fetch_assoc($q_asm_run))
                        {
                            $asgd_mat_id = $row_asm['Asgd_Mat_Id'];
                            $mat_id=$row_asm['Mat_Id'];
                            if($mat_id==11618){
                                $qty=7;}
                            elseif($mat_id==11580){
                                    $qty=5;}
                            else{
                                $qty=NULL;
                            }
                            if(is_numeric($qty)){
                                $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status,Asgd_Mat_to_Act_Qty ) VALUES ($asgd_mat_id,$asgd_act_id,1,$qty)";
                                $q_insert_run =mysqli_query($connection,$q_insert);
                                if($q_insert_run){}
                                else{
                                    echo 'error'.$q_insert;
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