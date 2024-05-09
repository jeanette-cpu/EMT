<?php
include('../../security.php');
if(isset($_POST['dept_id']))
{
    $dept_id = $_POST['dept_id'];
    $query="SELECT * FROM material WHERE Mat_Status=1 AND Dept_Id='$dept_id'";
    $query_run = mysqli_query($connection, $query);

    $table ='
        <table class="table table-bordered table-striped" id="mat_tbl" width="100%" cellspacing="0">
        <thead>
            <th>Code</th>
            <th>Material Desc</th>
            <th>Unit</th>
            <th>Qty Available</th>
            <th>Qty Assigned</th>
            <th>Est. Qty Used by Done%</th>
            <th>Est. Qty Needed (order/transfer)</th>
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_run)>0)
    {
        while($row = mysqli_fetch_assoc($query_run))
        {
            $mat_id = $row['Mat_Id'];
            // MATERIALS ASSIGNED
            $q_total = "SELECT SUM(Asgd_Mat_to_Act_Qty) as tot_qty FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m.Mat_Id='$mat_id'";
            $q_total_run = mysqli_query($connection, $q_total);
            $row1 = mysqli_fetch_assoc($q_total_run);
            $tot_asgn = $row1['tot_qty'];
            if($tot_asgn == Null)
            {
                $tot_asgn=0;
            }
            // MATERIAL USED BY PCT DONE
            $q_used ="SELECT * FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN assigned_activity as as_a on as_a.Asgd_Act_Id = as_m_a.Asgd_Act_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m.Mat_Id='$mat_id'";
            $q_used_run = mysqli_query($connection, $q_used); $tot_used=0;
            if(mysqli_num_rows($q_used_run)>0)
            {
                while($row_u = mysqli_fetch_assoc($q_used_run))
                {
                    $qty = $row_u['Asgd_Mat_to_Act_Qty']; 
                    $pct = $row_u['Asgd_Pct_Done'];
                    $pct = $pct * 0.01; 
                    $used_qty = $pct * $qty;
                    $tot_used = $tot_used + $used_qty;
                }
            }
            $n = $tot_asgn - $tot_used;
            if($n==0)
            {
                $n = $row['Mat_Qty'];
            }
            else
            {
                $n = $row['Mat_Qty'] -$n;
            }
    $table .='
    <tr>
        <td>'.$row['Mat_Code'].'</td>
        <td>'.$row['Mat_Desc'].'</td>
        <td>'.$row['Mat_Unit'].'</td>
        <td>'.number_format($row['Mat_Qty'], 0, '.', '').'</td>
        <td>'.number_format($tot_asgn, 0, '.', '').'</td>
        <td>'.number_format($tot_used, 0, '.', '').'</td>
        <td>'.number_format($n, 0, '.', '').'</td>
    </tr>
    ';
            }
        }
        else
        {
            echo "No Record Found";
        }
    $table .= '
        </tbody>
    </table>
    ';
    echo $table;
}
// BY PROJECTS
if(isset($_POST['prj_id']))
{
    $prj_id=$_POST['prj_id']; $asgd_id='';
    $query = "SELECT * FROM project WHERE Prj_Status =1 and Prj_Id='$prj_id'";
    // echo $query;
    $query_run = mysqli_query($connection, $query);
    $row_p = mysqli_fetch_assoc($query_run);
    $category = $row_p['Prj_Category'];
    if($category=='Building')
    {
         // get building assigned
         $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Prj_Id='$prj_id'";
         $q_building_run = mysqli_query($connection, $q_building);
         $b_id_arr=null; $b_ids= null;
         if(mysqli_num_rows($q_building_run)>0)
        {
            while($row_b = mysqli_fetch_assoc($q_building_run))
            {
                $b_id_arr[] = $row_b['Blg_Id'];
            }
            $b_ids = implode("', '", $b_id_arr);
            // get levels
            $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
            $q_level_run = mysqli_query($connection, $q_levels);
            $lvl_id_arr=null; $lvl_ids = null;
            if(mysqli_num_rows($q_level_run)>0)
            {
                while($row_l = mysqli_fetch_assoc($q_level_run))
                {
                    $lvl_id_arr[] = $row_l['Lvl_Id'];
                }
                $lvl_ids = implode("', '", $lvl_id_arr);
                // get flat id
                $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                $q_flat_run = mysqli_query($connection, $q_flat);
                $flt_ids =null; $flat_id_arr=null;
                if(mysqli_num_rows($q_flat_run)>0)
                {
                    while($row_f = mysqli_fetch_assoc($q_flat_run))
                    {
                        $flat_id_arr[] = $row_f['Flat_Id'];
                    }
                    $flt_ids = implode("', '", $flat_id_arr);
                    // get assigned id
                    $q_asgd_id ="SELECT Asgd_Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                    $q_asgd_id_run = mysqli_query($connection, $q_asgd_id);
                    if(mysqli_num_rows($q_asgd_id_run)>0)
                    {
                        while($row_act = mysqli_fetch_assoc($q_asgd_id_run ))
                        {
                            $asgd_arr[] = $row_act['Asgd_Act_Id'];
                        }
                        $asgd_id = implode("', '", $asgd_arr);
                    }
                }
            }
        }
    }
    else
    {
        // get villas assigned
        $q_villa = "SELECT Villa_Id FROM villa where Villa_Status='1' AND Prj_Id='$prj_id'";
        $q_villa_run = mysqli_query($connection, $q_villa);
        $villa_id_arr= null; $villa_ids= null;
        if(mysqli_num_rows($q_villa_run)>0)
        {
            while($row_v = mysqli_fetch_assoc($q_villa_run))
            {
                $villa_id_arr[] = $row_v['Villa_Id'];
            }
            $villa_ids = implode("', '", $villa_id_arr);
            // get plex
            $q_plex = "SELECT Plx_Id from plex where Plx_Status='1' and Villa_Id in ('$villa_ids')";
            $q_plex_run = mysqli_query($connection, $q_plex);
            if(mysqli_num_rows($q_plex_run)>0)
            {
                while($row_p = mysqli_fetch_assoc($q_plex_run))
                {
                    $plex_id_arr[] = $row_p['Plx_Id'];
                }
                $plex_ids = implode("', '", $plex_id_arr);
                // get building
                $q_building = "SELECT Blg_Id FROM building where Blg_Status='1' AND Plx_Id in ('$plex_ids')";
                $q_building_run = mysqli_query($connection, $q_building);
                if(mysqli_num_rows($q_building_run)>0)
                {
                    while($row_b = mysqli_fetch_assoc($q_building_run))
                    {
                        $b_id_arr[] = $row_b['Blg_Id'];
                    }
                    $b_ids = implode("', '", $b_id_arr);
                    // get levels
                    $q_levels ="SELECT Lvl_Id from level where Lvl_Status=1 and Blg_Id in ('$b_ids')";
                    $q_level_run = mysqli_query($connection, $q_levels);
                    if(mysqli_num_rows($q_level_run)>0)
                    {
                        while($row_l = mysqli_fetch_assoc($q_level_run))
                        {
                            $lvl_id_arr[] = $row_l['Lvl_Id'];
                        }
                        $lvl_ids = implode("', '", $lvl_id_arr);
                        // get flat id
                        $q_flat = "SELECT Flat_Id from flat WHERE Flat_Status=1 and Lvl_Id in ('$lvl_ids')";
                        $q_flat_run = mysqli_query($connection, $q_flat);
                        if(mysqli_num_rows($q_flat_run)>0)
                        {
                            while($row_f = mysqli_fetch_assoc($q_flat_run))
                            {
                                $flat_id_arr[] = $row_f['Flat_Id'];
                            }
                            $flt_ids = implode("', '", $flat_id_arr);
                            // get assigned activities
                            $q_asgd_id ="SELECT Asgd_Act_Id FROM assigned_activity WHERE Asgd_Act_Status=1 and Flat_Id in ('$flt_ids')";
                            $q_asgd_id_run = mysqli_query($connection, $q_asgd_id);
                            if(mysqli_num_rows($q_asgd_id_run)>0)
                            {
                                while($row_act = mysqli_fetch_assoc($q_asgd_id_run ))
                                {
                                    $asgd_arr[] = $row_act['Asgd_Act_Id'];
                                }
                                $asgd_id = implode("', '", $asgd_arr);
                            }
                        }
                    }
                }
            }
        }
    }
    $queryq="SELECT SUM(Asgd_Mat_to_Act_Qty)as tot_qty, matt.Mat_Code, matt.Mat_Desc, matt.Mat_Qty, matt.Mat_Unit, as_m.Mat_Id FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN material as matt on matt.Mat_Id= as_m.Mat_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and matt.Mat_Status=1 and as_m.Asgd_Mat_Status=1 and as_m_a.Asgd_Act_Id in ('$asgd_id') GROUP BY as_m.Mat_Id";
    // echo $queryq;
    $query_runq = mysqli_query($connection, $queryq);

    $table ='
        <table class="table table-bordered table-striped" id="mat_tbl" width="100%" cellspacing="0">
        <thead>
            <th>Code</th>
            <th>Material Desc</th>
            <th>Unit</th>
            <th>Qty Available</th>
            <th>Qty Assigned</th>
            <th>Est. Qty Used by Done%</th>
            
        </thead>
        <tbody>
    ';
    if(mysqli_num_rows($query_runq)>0)
    {
        while($row = mysqli_fetch_assoc($query_runq))
        {
 
            $mat_id = $row['Mat_Id'];
            // MATERIAL USED BY PCT DONE
            $q_used ="SELECT SUM((as_act.Asgd_Pct_Done * 0.01)*as_m_a.Asgd_Mat_to_Act_Qty) as mat_used FROM asgn_mat_to_act as as_m_a LEFT JOIN assigned_material as as_m on as_m.Asgd_Mat_Id = as_m_a.Asgd_Mat_Id LEFT JOIN assigned_activity as as_act on as_act.Asgd_Act_Id = as_m_a.Asgd_Act_Id WHERE as_m_a.Asgn_Mat_to_Act_Status=1 and as_m.Asgd_Mat_Status=1 and as_m_a.Asgd_Act_Id in ('$asgd_id') and as_m.Mat_Id='$mat_id' group by as_m.Mat_Id";
            $q_used_run = mysqli_query($connection, $q_used); 
            $row_u= mysqli_fetch_assoc($q_used_run);
            // echo $q_used;   
    $table .='
    <tr>
        <td>'.$row['Mat_Code'].'</td>
        <td>'.$row['Mat_Desc'].'</td>
        <td>'.$row['Mat_Unit'].'</td>
        <td>'.number_format($row['Mat_Qty'], 0, '.', '').'</td>
        <td>'.number_format($row['tot_qty'], 0, '.', '').'</td>
        <td>'.number_format($row_u['mat_used'], 0, '.', '').'</td>
    </tr>
    ';
            }
        }
        else
        {
            echo "No Record Found";
        }
    $table .= '
        </tbody>
    </table>
    ';
    echo $table;
}
?>