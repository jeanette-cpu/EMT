<?php
include('../../security.php'); 
error_reporting(0);

if(isset($_POST['De_Id']))
{
    $DE_ID = $_POST['De_Id'];
    $flt_id = $_POST['flt_id'];
    $plx_id=$_POST['plx_id'];
    $prj_id = $_POST['prj_id'];
    $act_name = $_POST['act_name'];
    // $user_id=$_POST['user_id'];
    echo '<h5 style="color:black" align="center">'.$act_name.'</h5>';
    // EMT Labour
    $query="SELECT * FROM asgn_worker WHERE DE_Id='$DE_ID' and Asgd_Worker_Status=1";
    $query_run = mysqli_query($connection, $query);
    // Manpower
    $q_MP="SELECT * FROM asgn_mp LEFT JOIN manpower on manpower.MP_Id = asgn_mp.MP_Id WHERE asgn_mp.DE_Id='$DE_ID' and asgn_mp.Asgn_MP_Status=1";
    $q_MP_run = mysqli_query($connection, $q_MP);
    // SubContractor
    $q_SB="SELECT * FROM asgn_subcon LEFT JOIN subcontractor on asgn_subcon.SB_Id = subcontractor.SB_Id WHERE asgn_subcon.DE_Id='$DE_ID' and asgn_subcon.Asgn_SB_Status=1";
    $q_SB_run = mysqli_query($connection, $q_SB);

    if($plx_id!=NULL)
    {
        $output .='
        <form action="code.php" method="POST">
        <div class="table-responsive">';
        if(mysqli_num_rows($query_run)>0)
            {
$output.='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none"></th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Performance</th>
                <th>Action</th>
            </thead>
                <tbody>';
                while($row = mysqli_fetch_assoc($query_run))
                {
                    $emp_id = $row['Emp_Id'];
                    $query2 = "SELECT * from employee WHERE EMP_ID='$emp_id' limit 1";
                    $query_run2=mysqli_query($connection,$query2);
                    $row2 = mysqli_fetch_assoc($query_run2);
                    $emp_name = $row2['EMP_FNAME'].' '.$row2['EMP_MNAME'].' '.$row2['EMP_LNAME'];
                    $perf = $row["Asgd_Worker_Performace"];
                    $asgd_worker_id = $row["Asgd_Worker_Id"];
                    $emp_no = $row2["EMP_NO"];
$output .='
            <tr>  
                <td class="d-none"><input name="asgn_worker_id[]" class="form-control no-border" value='.$asgd_worker_id.'></td>
                <td>'.$emp_no.'</td>
                <td>'.$emp_name.'</td>
                <td><input name="emp_perf[]" class="form-control no-border" value='.$perf.'></td>
                <td>
                    <form action="code.php" method="POST">
                        <input type="hidden" name="plx_id" value="'.$plx_id.'">
                        <input type="hidden" name="prj_id" value='.$prj_id.'>
                        <input type="hidden" name="id" value='.$asgd_worker_id.'>
                        <button type="submit" name="delEmp" class="btn btn-primary">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>';       
                }
                $output .= '</tbody>
                </table>';
            }
            else
            {
                echo "No Record Found";
            }
            //MANPOWER
            if(mysqli_num_rows($q_MP_run)>0){
            $output .='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none"></th>
                <th width="50%">Manpower Name</th>
                <th>Performance</th>
                <th>Qty</th>
                <th>Action</th>
            </thead>
            ';
                while($row1 = mysqli_fetch_assoc($q_MP_run))
                {
                $asgn_mp_id =$row1['Asgn_MP_Id'] ;
                    $output .='
                <tr>  
                    <td class="d-none"><input name="asgn_mp_id[]" class="form-control no-border" value='.$asgn_mp_id.'></td>
                    <td>'.$row1['MP_Name'].'</td>
                    <td><input type="text" name="mp_perf[]" class="form-control  no-border" value='.$row1['Asgn_MP_Performance'].'></td>
                    <td>
                        <div class="form-row block">
                            <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name="mp_qty[]" class="form-control  no-border" value='.$row1['Asgn_MP_Qty'].'>
                                </div>
                            <button type="button" class="btn btn-xs btn-outline-success addBtn" >+</button>    
                        </div>
                    </td>
                    <td>
                        <form action="code.php" method="POST">
                            <input type="hidden" name="plx_id" value="'.$plx_id.'">
                            <input type="hidden" name="flt_id" value="'.$flt_id.'">
                            <input type="hidden" name="prj_id" value='.$prj_id.'>
                            <input type="hidden" name="id" value='.$asgn_mp_id.'>
                            <input type="hidden" name="user_id" value='.$user_id.'>
                            <button type="submit" name="delMP" class="btn btn-primary">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
                }
                $output .='
                </table>';
            }
           
            //SUCONTRACTOR
            if(mysqli_num_rows($q_SB_run)>0){
                $output .='
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th width="50%">Sub Contractor</th>
                        <th>Performance</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </thead>
                    ';
                while($row2 = mysqli_fetch_assoc($q_SB_run))
                {
                    $asgn_sb_id =$row2['Asgn_SB_Id'] ;
                    $output .='
                <tr>  
                    <td class="d-none"><input name="asgn_sb_id[]" class="form-control no-border" value='.$asgn_sb_id.'></td>
                    <td>'.$row2['SB_Name'].'</td>
                    <td><input type="text" name="sb_perf[]" class="form-control  no-border" value='.$row2['Asgn_SB_Performance'].'></td>
                    <td>
                        <div class="form-row block">
                            <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name="sb_qty[]" class="form-control  no-border" value='.$row2['Asgn_SB_Qty'].'>
                                </div>
                            <button type="button" class="btn btn-xs btn-outline-success addBtn" >+</button>    
                        </div>                
                    </td>
                    <td>
                        <form action="code.php" method="POST">
                            <input type="hidden" name="plx_id" value="'.$plx_id.'">
                            <input type="hidden" name="prj_id" value='.$prj_id.'>
                            <input type="hidden" name="id" value='.$asgn_sb_id.'>
                            <input type="hidden" name="user_id" value='.$user_id.'>
                            <button type="submit" name="delSB" class="btn btn-primary">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
                }
                $output .='
                </table>
            ';
            }
if(mysqli_num_rows($query_run)>0 || mysqli_num_rows($q_MP_run)>0 || mysqli_num_rows($q_SB_run)>0)
{
    $output .='
    <div align="right"  class="mb-2">
        <input type="hidden" name="plx_id" value="'.$plx_id.'">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="EditBtn" id="EditBtn" class="btn btn-success"><i class="fa fa-check ml-1 mr-2" aria-hidden="true"></i>Save</button>
    </div>
    </form>
    ';
}

$output .='
</div>';
echo $output;
    }
    /////// BUILDING
    else{
        $output .='
    <form action="codeb.php" method="POST">
        <div class="table-responsive">';
        if(mysqli_num_rows($query_run)>0)
            {
$output.='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none"></th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Performance</th>
                <th>Action</th>
            </thead>
                <tbody>';
                while($row = mysqli_fetch_assoc($query_run))
                {
                    $emp_id = $row['Emp_Id'];
                    $query2 = "SELECT * from employee WHERE EMP_ID='$emp_id' limit 1";
                    $query_run2=mysqli_query($connection,$query2);
                    $row2 = mysqli_fetch_assoc($query_run2);
                    $emp_name = $row2['EMP_FNAME'].' '.$row2['EMP_MNAME'].' '.$row2['EMP_LNAME'];
                    $perf = $row["Asgd_Worker_Performace"];
                    $asgd_worker_id = $row["Asgd_Worker_Id"];
                    $emp_no = $row2["EMP_NO"];
$output .='
            <tr>  
                <td class="d-none"><input name="asgn_worker_id[]" class="form-control no-border" value='.$asgd_worker_id.'></td>
                <td>'.$emp_no.'</td>
                <td>'.$emp_name.'</td>
                <td><input name="emp_perf[]" class="form-control no-border" value='.$perf.'></td>
                <td>
                    <form action="codeb.php" method="POST">
                        <input type="hidden" name="plx_id" value="'.$plx_id.'">
                        <input type="hidden" name="prj_id" value='.$prj_id.'>
                        <input type="hidden" name="id" value='.$asgd_worker_id.'>
                        <button type="submit" name="delEmp" class="btn btn-primary">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </form>
                </td>
            </tr>';       
                }
                $output .= '</tbody>
                </table>';
            }
            else
            {
                echo "No Record Found";
            }
            //MANPOWER
            if(mysqli_num_rows($q_MP_run)>0){
            $output .='
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <th class="d-none"></th>
                <th width="50%">Manpower Name</th>
                <th>Performance</th>
                <th>Qty</th>
                <th>Action</th>
            </thead>
            ';
                while($row1 = mysqli_fetch_assoc($q_MP_run))
                {
                $asgn_mp_id =$row1['Asgn_MP_Id'] ;
                    $output .='
                <tr>  
                    <td class="d-none"><input name="asgn_mp_id[]" class="form-control no-border" value='.$asgn_mp_id.'></td>
                    <td>'.$row1['MP_Name'].'</td>
                    <td><input type="text" name="mp_perf[]" class="form-control  no-border" value='.$row1['Asgn_MP_Performance'].'></td>
                    <td>
                        <div class="form-row block">
                            <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name="mp_qty[]" class="form-control  no-border" value='.$row1['Asgn_MP_Qty'].'>
                                </div>
                            <button type="button" class="btn btn-xs btn-outline-success addBtn" >+</button>    
                        </div>
                    </td>
                    <td>
                        <form action="codeb.php" method="POST">
                            <input type="hidden" name="plx_id" value="'.$plx_id.'">
                            <input type="hidden" name="flt_id" value="'.$flt_id.'">
                            <input type="hidden" name="prj_id" value='.$prj_id.'>
                            <input type="hidden" name="id" value='.$asgn_mp_id.'>
                            <input type="hidden" name="user_id" value='.$user_id.'>
                            <button type="submit" name="delMP" class="btn btn-primary">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
                }
                $output .='
                </table>';
            }
           
            //SUCONTRACTOR
            if(mysqli_num_rows($q_SB_run)>0){
                $output .='
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th class="d-none"></th>
                        <th width="50%">Sub Contractor</th>
                        <th>Performance</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </thead>
                    ';
                while($row2 = mysqli_fetch_assoc($q_SB_run))
                {
                    $asgn_sb_id =$row2['Asgn_SB_Id'] ;
                    $output .='
                <tr>  
                    <td class="d-none"><input name="asgn_sb_id[]" class="form-control no-border" value='.$asgn_sb_id.'></td>
                    <td>'.$row2['SB_Name'].'</td>
                    <td><input type="text" name="sb_perf[]" class="form-control  no-border" value='.$row2['Asgn_SB_Performance'].'></td>
                    <td>
                        <div class="form-row block">
                            <button type="button" class="btn btn-xs btn-outline-danger minBtn">-</button>
                                <div class="col-4">
                                    <input type="text" name="sb_qty[]" class="form-control  no-border" value='.$row2['Asgn_SB_Qty'].'>
                                </div>
                            <button type="button" class="btn btn-xs btn-outline-success addBtn" >+</button>    
                        </div>                
                    </td>
                    <td>
                        <form action="codeb.php" method="POST">
                            <input type="hidden" name="flt_id" value="'.$flt_id.'">
                            <input type="hidden" name="prj_id" value='.$prj_id.'>
                            <input type="hidden" name="id" value='.$asgn_sb_id.'>
                            <input type="hidden" name="user_id" value='.$user_id.'>
                            <button type="submit" name="delSB" class="btn btn-primary">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                </tr>';
                }
                $output .='
                </table>
            ';
            }
if(mysqli_num_rows($query_run)>0 || mysqli_num_rows($q_MP_run)>0 || mysqli_num_rows($q_SB_run)>0)
{
    $output .='
    <div align="right"  class="mb-2">
        <input type="hidden" name="flt_id" value="'.$flt_id.'">
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
        <button type="submit" name="EditBtn" id="EditBtn" class="btn btn-success"><i class="fa fa-check ml-1 mr-2" aria-hidden="true"></i>Save</button>
    </div>
    </form>
    ';
}

$output .='
</div>';
echo $output;
        
    }

}  
?>