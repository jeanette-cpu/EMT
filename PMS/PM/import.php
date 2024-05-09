<?php
include('../../security.php');
// bedroom 3
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
                        if($act_id ==24){
                            $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
                            $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);
                            if(mysqli_num_rows($q_asgn_all_mat_run)>0)
                            {
                                while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
                                {
                                    $asgd_mat_id = $row_a['Asgd_Mat_Id'];
                                    if($asgd_mat_id==559){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==560){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==561){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==562){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==563){
                                        $qty =0.5 ;
                                    } 
                                    elseif($asgd_mat_id==564){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==565){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==566){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==567){
                                        $qty =15 ;
                                    } 
                                    elseif($asgd_mat_id==568){
                                        $qty =15 ;
                                    } 
                                    $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgd_Mat_to_Act_Qty,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,$qty,1)";
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