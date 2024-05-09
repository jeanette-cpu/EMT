<?php
include('security.php');
if(isset($_POST['act_id']))
{
    $act_id = $_POST['act_id'];
    $asgd_id = $_POST['asgd_id'];
    // ASSIGNED MATERIALS, EXCEPT ALREADY ASSIGNED
    // $q_asgn_all_mat ="select * from (SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id') as query2 except select * from (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id') as query1";
    //LIVE
    $q_asgn_all_mat ="SELECT Asgd_Mat_Id FROM assigned_material where Asgd_Mat_Status=1 and Act_Id='$act_id' AND Asgd_Mat_Id NOT IN (SELECT Asgd_Mat_Id from asgn_mat_to_act WHERE Asgn_Mat_to_Act_Status=1 and Asgd_Act_Id='$asgd_id')";
    // echo $q_asgn_all_mat;
    $q_asgn_all_mat_run =mysqli_query($connection,$q_asgn_all_mat);

    if(mysqli_num_rows($q_asgn_all_mat_run)>0)
    {
        while($row_a = mysqli_fetch_assoc($q_asgn_all_mat_run))
        {
            // insert materials
            $asgd_mat_id = $row_a['Asgd_Mat_Id'];
            $q_insert ="INSERT INTO asgn_mat_to_act (Asgd_Mat_Id,Asgd_Act_Id,Asgn_Mat_to_Act_Status) VALUES ($asgd_mat_id,$asgd_id,1)";
            // echo $q_insert;
            $q_insert_run =mysqli_query($connection,$q_insert);
            
        }
    }
    else{}

    $query = "SELECT * FROM asgn_mat_to_act as as_m  LEFT JOIN assigned_material as mat on mat.Asgd_Mat_Id = as_m.Asgd_Mat_Id LEFT JOIN material on material.Mat_Id = mat.Mat_Id WHERE as_m.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Act_Id='$asgd_id'";
    $query_run = mysqli_query($connection,$query);
    $table = '
    
    <div class="table-responsive">
    <form action="code1.php" method="POST">
    <input type="hidden" name="prj_name" value="'.$_POST['prj_name'].'">
    <input type="hidden" name="flt_id" value="'.$_POST['flt_id'].'">
    <table class="table table-bordered " id="Datatable" width="100%" cellspacing="0">
        <thead>
            <th class="d-none"></th>
            <th width="15%">Code</th>
            <th width="65%">Material Description</th>
            <th >Qty<th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while ($row = mysqli_fetch_assoc($query_run))
        {
            $table .='
            <tr>
                <td class="d-none"><input name="asgd_mat_id[]" value="'.$row['Asgd_Mat_to_Act_Id'].'"></td>
                <td>'.$row['Mat_Code'].'</td>
                <td>'.$row['Mat_Desc'].'</td>
                <td colspan="2"><input name="mat_qty[]" type="number" step="0.1"  class="form-control no-border" value="'.$row['Asgd_Mat_to_Act_Qty'].'"></td>
            </tr>
            ';
        }
    }
    $table .='
        </tbody>
    </table>
        <div align="right">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
            <button type="submit" name="EditQty" id="EditBtn" class="btn btn-success"><i class="fa fa-check ml-1 mr-2" aria-hidden="true"></i>Save</button>
        </div>
    <form>
    </div>
    ';

    echo $table;
}
?>