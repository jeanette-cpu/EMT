<?php
include('../../security.php');
if(isset($_POST['dept_id2'])){
    $q_activities="SELECT * FROM activity WHERE Act_Status=1";
    $q_activities_run=mysqli_query($connection,$q_activities);
    if(mysqli_num_rows($q_activities_run)>0){
        $options_html='';
        while($row=mysqli_fetch_assoc($q_activities_run)){
            $act_id=$row['Act_Id'];
            $act_code=$row['Act_Code'];
            $act_name=$row['Act_Name'];
            $act_fname=$act_code.' '.$act_name;
            $options_html.='<option value="'.$act_id.'">'.$act_fname.'</option>';
        }
    }
    echo $options_html;
}
if(isset($_POST['prj_id'])){
    $prj_id=$_POST['prj_id'];
    $q_prj="SELECT * FROM project WHERE Prj_Id='$prj_id'";
    $q_prj_run=mysqli_query($connection,$q_prj); $option='';
    if(mysqli_num_rows($q_prj_run)>0){
        $row_p=mysqli_fetch_assoc($q_prj_run);
        $prj_cat=$row_p['Prj_Category'];
        if($prj_cat=='Building'){ // find levels
            $q_blg="SELECT * FROM building WHERE Prj_Id='$prj_id'";
            $q_blg_run=mysqli_query($connection,$q_blg);
            if(mysqli_num_rows($q_blg_run)>0){
                while($row_b=mysqli_fetch_assoc($q_blg_run)){
                    $blg_arr[]=$row_b['Blg_Id'];
                }
            }
            $blg_ids=implode("', '", $blg_arr);
            $q_area="SELECT * FROM level WHERE Blg_Id IN ('$blg_ids')";
            $q_area_run=mysqli_query($connection,$q_area);
            if(mysqli_num_rows($q_area_run)>0){
                while($row_a=mysqli_fetch_assoc($q_area_run)){
                    $lvl_id=$row_a['Lvl_Id'];
                    $lvl_code=$row_a['Lvl_Code'];
                    $option.=' <option value="'.$lvl_id.'"><'.$lvl_code.'</option>';
                }
            }
        }
        elseif($prj_cat=='Villa'){ //find plex
            $q_v="SELECT * FROM Villa WHERE Prj_Id='$prj_id'";
            $q_v_run=mysqli_query($connection,$q_v);
            if(mysqli_num_rows($q_v_run)>0){
                while($row_v=mysqli_fetch_assoc($q_v_run)){
                    $v_arr[]=$row_v['Villa_Id'];
                }
            }
            $v_ids=implode("', '", $v_arr);
            $q_area2="SELECT Plx_Code, Plx_Name, Plx_Id FROM plex WHERE Villa_Id in ('$v_ids')";
            $q_area_run2=mysqli_query($connection,$q_area2);
            if(mysqli_num_rows($q_area_run2)>0){
                while($row_a2=mysqli_fetch_assoc($q_area_run2)){
                    $plx_id=$row_a2['Plx_Id'];
                    $plx_code=$row_a2['Plx_Code'];
                    $plx_name=$row_a2['Plx_Name'];
                    $option.='<option value="'.$plx_id.'">'.$plx_code.' '.$plx_name.'</option>';
                }
            }
        }
        else{
            echo 'error';
        }
    }
    echo $option;
}
if(isset($_POST['act_tbl'])){
    $tbl_html1='';
    $prj_id=$_POST['act_tbl'];
    $tbl_html1.='<thead>
                    <th>Activity Code</th>
                    <th>Activity Name</th>
                    <th>Activity Category</th>
                    <th>Standard</th>
                    <th>Prj Standard</th>
                </thead>
                <tbody>';
    $query1 = "SELECT * FROM activity WHERE Act_Status='1'";
    $query_run1 = mysqli_query($connection, $query1);
    if(mysqli_num_rows($query_run1)>0)
    {
        while($row = mysqli_fetch_assoc($query_run1))
        { 
            $act_id=$row['Act_Id'];
            $dept_id = $row['Dept_Id'];
            $query2 = "SELECT Dept_Name from department WHERE Dept_Id='$dept_id'";
            $query_run2=mysqli_query($connection,$query2);
            $row2 = mysqli_fetch_assoc($query_run2);

            $act_cat_id = $row['Act_Cat_Id'];
            $query3 = "SELECT Act_Cat_Name from activity_category WHERE Act_Cat_Id='$act_cat_id'";
            $query_run3=mysqli_query($connection,$query3);
            $row3 = mysqli_fetch_assoc($query_run3);
            $emp_r=$row['Act_Emp_Ratio'];
            $output_r=$row['Act_Output_Ratio'];
            if($emp_r!=null && $output_r!=null){
                $emp_r = floor($emp_r);
                $output_r =floor($output_r);
                $ratio_s=$emp_r.':'.$output_r;
            }
            else{
                $ratio_s='not set';
            }
            //prj standard
            $q_s="SELECT * FROM activity_standard WHERE Act_Standard_Status=1 AND Act_Id ='$act_id' AND Prj_Id='$prj_id'";
            $q_s_run=mysqli_query($connection,$q_s); $ratio_sa='';
            if(mysqli_num_rows($q_s_run)>0){
                $row_sa=mysqli_fetch_assoc($q_s_run); 
                $emp_rs=$row_sa['Act_Standard_Emp_Ratio'];
                $output_rs=$row_sa['Act_Standard_Output_Ratio'];
                if($emp_rs!=null && $output_rs!=null){
                    $emp_rs = floor($emp_rs);
                    $output_rs =floor($output_rs);
                    $ratio_sa=$emp_rs.':'.$output_rs;
                }
                else{
                    $ratio_sa='not set';
                }
            }
            $tbl_html1.='
            <tr>
                <td>'.$row['Act_Code'].'</td>
                <td>'.$row['Act_Name'].'</td>
                <td>'.$row3['Act_Cat_Name'].'</td>
                <td>'.$ratio_s.'</td>
                <td>'.$ratio_sa.'</td>
            </tr>';
        }
    }
    $tbl_html1.='
    </tbody>
    ';
    echo $tbl_html1;
}
if(isset($_POST['area_tbl'])){
    $h1=''; $prj_cat=''; $blg_ids='';
    $tbl_html=''; 
    $prj_id=$_POST['area_tbl'];
    //check if villa/building
    $q_p="SELECT Prj_Category FROM project WHERE Prj_Id='$prj_id'";
    $q_p_run=mysqli_query($connection,$q_p);
    if(mysqli_num_rows($q_p_run)>0){
        $row_p=mysqli_fetch_assoc($q_p_run);
        $prj_cat=$row_p['Prj_Category'];
        if($prj_cat=='Villa'){ $v_ids='';
            $q_v="SELECT * FROM Villa WHERE Prj_Id='$prj_id'";
            $q_v_run=mysqli_query($connection,$q_v);
            if(mysqli_num_rows($q_v_run)>0){
                $h1='Plex';
                while($row_v=mysqli_fetch_assoc($q_v_run)){
                    $v_arr[]=$row_v['Villa_Id'];
                }
            }
            $v_ids=implode("', '", $v_arr);
        }
        elseif($prj_cat=='Building'){
            $q_blg="SELECT * FROM building WHERE Prj_Id='$prj_id'";
            $q_blg_run=mysqli_query($connection,$q_blg);
            if(mysqli_num_rows($q_blg_run)>0){
                $h1='Level';
                while($row_b=mysqli_fetch_assoc($q_blg_run)){
                    $blg_arr[]=$row_b['Blg_Id'];
                }
            }
            $blg_ids=implode("', '", $blg_arr);
        }
        $tbl_html.='<thead>
                    <th>'.$h1.' Code</th>
                    <th>'.$h1.' Name</th>
                </thead>
                <tbody>';
    }
    
    if($prj_cat=='Villa'){
        //select from plexes
        $q_area="SELECT * FROM plex WHERE Villa_Id IN ('$v_ids')";
        $q_area_run=mysqli_query($connection,$q_area);
        if(mysqli_num_rows($q_area_run)>0){
            while($row_a=mysqli_fetch_assoc($q_area_run)){
                $plx_code=$row_a['Plx_Code'];
                $plx_name=$row_a['Plx_Name'];
                $tbl_html.='
                    <tr>
                        <td>'.$plx_code.'</td>
                        <td>'.$plx_name.'</td>
                    </tr>';
            }
            $tbl_html.=' </tbody> ';
        }
    }
    elseif($prj_cat='Building'){
        //select from leves
        $q_area2="SELECT * FROM level WHERE Lvl_Id IN ('$blg_ids')";
        $q_area_run2=mysqli_query($connection,$q_area2);
        if(mysqli_num_rows($q_area_run2)>0){
            while($row_a2=mysqli_fetch_assoc($q_area_run2)){
                $lvl_code=$row_a2['Lvl_Code'];
                $lvl_name=$row_a2['Lvl_Name'];
                $tbl_html.='
                <tr>
                    <td>'.$lvl_code.'</td>
                    <td>'.$lvl_name.'</td>
                </tr>';
            }
            $tbl_html.=' </tbody> ';
        }
    }
    echo $tbl_html;
}
?>