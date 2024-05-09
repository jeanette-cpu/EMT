<form action="update100.php" method="post">
    <!-- <label for="act_code">Activity Code</label>
    <input type="text" name="act_code"> 
    <br> -->
    <label for="">Activity ID</label>
    <input type="text" name="act_id" required> 
    <br>
    <label for="">Manpower Qty</label>
    <input type="text" name="mp_qty" required>
    <br>
    <label for="">Flat ids</label>
    <input type="textarea" name="flt_ids" required>
    <br>
    <button name="update" type="submit">submit</button>
</form>
<?php 
include('dbconfig.php');
if(isset($_POST['update'])){
    // 35 emt manpower
    // $act_code=$_POST['act_code'];
    $act_id=$_POST['act_id'];
    $mp_qty=$_POST['mp_qty'];
    $flt_ids=$_POST['flt_ids'];
    $today=date('Y-m-d');

    // $inputString = "This is a sample string with numbers 123 and 4567.";
    $success=0;
    // Use a regular expression to insert a single quote before each number
    $flt_ids = preg_replace('/(\d+)/', "'$1',", $flt_ids);
    $flt_ids = substr($flt_ids, 0, -1);

    $q_flats="SELECT * FROM assigned_activity WHERE Flat_Id in ($flt_ids) AND Asgd_Act_Status=1 AND Asgd_Pct_Done<100 AND Act_Id='$act_id'";
    $q_flats_run=mysqli_query($connection,$q_flats);
    if(mysqli_num_rows($q_flats_run)>0){
        while($row=mysqli_fetch_assoc($q_flats_run)){
            $asgd_id=$row['Asgd_Act_Id'];
            //update to 100%
            $q_update="UPDATE assigned_activity SET Asgd_Pct_Done=100, Asgd_Act_Date_Completed='$today' WHERE Asgd_Act_Id='$asgd_id'";
            $q_update_run=mysqli_query($connection,$q_update);
            if($q_update_run){
                //insert daily entry
                $q_de="INSERT INTO daily_entry (DE_Date_Entry,DE_Pct_Done,DE_Status,User_Id,Asgd_Act_Id) VALUES ('$today','100',1,'1770','$asgd_id')";
                if($connection->query($q_de)===TRUE){
                    $l_id = $connection->insert_id; 
                    // insert emt emp
                    $insert="INSERT INTO asgn_mp (DE_Id, Asgn_MP_Status,MP_Id,Asgn_MP_Qty) VALUES ('$l_id',1,'35','$mp_qty')";
                    $insert_run=mysqli_query($connection,$insert);
                    if($insert_run){
                        $success++;
                    }
                    else{
                        echo 'error inserting manpower '.$asgd_id.'<br>';
                    }
                }
            }
            else{
                echo 'error update percentage '.$asgd_id.'<br>';
            }
            // //new inserted id DE
        }
    }
    echo $success;
}
?>