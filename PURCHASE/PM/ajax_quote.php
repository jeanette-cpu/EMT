<?php
include('../../security.php');
error_reporting(0);
if(isset($_POST['post_opt'])){
    $post_id=$_POST['post_opt'];
        $q_comp="SELECT DISTINCT q.Quote_Id, q.Comp_Id, c.Comp_Id, c.Comp_Name FROM quote as q
                    LEFT JOIN post as p on p.Post_Id=q.Post_Id
                    LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id
                    LEFT JOIN company as c on c.Comp_Id=q.Comp_Id
                    WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
    // echo $dept_id;
    $q_comp_run=mysqli_query($connection,$q_comp);
    $options='';
    $options.='
    <option value="">Select Company</option>';
    if(mysqli_num_rows($q_comp_run)>0){
        while($row=mysqli_fetch_assoc($q_comp_run)){
            $options.='<option value='.$row['Comp_Id'].'>'.$row['Comp_Name'].'</option>';
        }
    }
    echo $options;
}
// company applied quote (subcon/manpower)
if(isset($_POST['comp_opt'])){
    $post_id=$_POST['comp_opt'];
        $q_comp="SELECT DISTINCT q.Quote_Id, q.Comp_Id, c.Comp_Id, c.Comp_Name FROM quote as q
                    LEFT JOIN post as p on p.Post_Id=q.Post_Id
                    LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id
                    LEFT JOIN company as c on c.Comp_Id=q.Comp_Id
                    WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
    // echo $dept_id;
    $q_comp_run=mysqli_query($connection,$q_comp);
    $options='';
    $options.='
    <option value="">Select Company</option>';
    if(mysqli_num_rows($q_comp_run)>0){
        while($row=mysqli_fetch_assoc($q_comp_run)){
            $options.='<option value='.$row['Comp_Id'].'>'.$row['Comp_Name'].'</option>';
        }
    }
    echo $options;
}
function decPlace($num){
    if($num!=null){
        if ( strpos( $num, "." ) !== false ) {
            $num=number_format($num, 2);
        }
    }
    $num=number_format($num);
    return $num;
}
// material
if(isset($_POST['comp_filter'])){
    $comp_filter=$_POST['comp_filter'];
    $post_id=$_POST['post_id'];
    $comp_ids=$_POST['comp_ids'];
    if($comp_ids!="''"){
        $additional=' AND comp.Comp_Id IN ('.$comp_ids.')';
    }
    else{
        $additional='';
    }
    $comp_tbl='';$comp_name='';$second_h='';$c=0; $compTot=0;
    if($comp_filter=='Select Company'){
        $comps_applied="SELECT comp.Comp_Id, comp.Comp_Name, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    elseif($comp_filter=='All'){
        $comps_applied="SELECT comp.Comp_Id, comp.Comp_Name, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    elseif($comp_filter=='h'){
        $ccomps_applied="SELECT comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
        $ccomps_applied_run=mysqli_query($connection,$ccomps_applied);
        if(mysqli_num_rows($ccomps_applied_run)){
            while($row11=mysqli_fetch_assoc($ccomps_applied_run)){
                $comp_id=$row11['Comp_Id'];
                $quote_id=$row11['Quote_Id'];
                $q_grp="SELECT q.Comp_Id, ((qd.Quote_Detail_Disc*.01)* sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value)) as discounted_grp, sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as qty
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                        WHERE q.Post_Id='$post_id' AND q.Comp_Id='$comp_id' AND mp.Mat_Post_Status!=0 and q.Quote_Status=1 and qd.Quote_Detail_Status=1 GROUP BY  q.Comp_Id, qd.Quote_Detail_Disc;";
                $q_grp_run=mysqli_query($connection,$q_grp);
                // if(mysqli_num_rows($q_grp_run)>0){ $total_disc=0; $total_qty=0;
                //     while($row_g=mysqli_fetch_assoc($q_grp_run)){
                //         $comp_id=$row_g['Comp_Id'];
                //         $grp_disc=$row_g['discounted_grp'];
                //         $total_disc=$total_disc+$grp_disc;
                //         $qty_rate=$row_g['qty'];
                //         $total_qty=$total_qty+$qty_rate;
                //     }
                // }
                // $computed_total=$total_qty-$total_disc; 
                // //update 
                // $q_update="UPDATE quote SET Quote_Discount='$computed_total' WHERE Quote_Id='$quote_id'";
                // $q_update_run=mysqli_query($connection,$q_update);
                // $computed_total=0;
                if(mysqli_num_rows($q_grp_run)>0){ $total_disc=0; $total_qty=0;
                    while($row_g=mysqli_fetch_assoc($q_grp_run)){
                        $comp_id=$row_g['Comp_Id'];
                        $grp_disc=$row_g['discounted_grp'];
                        $qty_rate=$row_g['qty'];
                        if($grp_disc==0){
                            // $total_qty=$total_qty+$qty_rate;
                        }
                        else{
                            $total_disc=$total_disc+$grp_disc;
                        }
                        $total_qty=$total_qty+$qty_rate;
                    }
                }
                $computed_total=$total_qty-$total_disc; 
                //update 
                $q_update="UPDATE quote SET Quote_Discount='$computed_total' WHERE Quote_Id='$quote_id'";
                $q_update_run=mysqli_query($connection,$q_update);
                $computed_total=0;
            } 
        }
        $comps_applied="SELECT comp.Comp_Name, comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id
                        ORDER BY q.Quote_Discount DESC";
    }
    elseif($comp_filter=='l'){
        $ccomps_applied="SELECT comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
        $ccomps_applied_run=mysqli_query($connection,$ccomps_applied);
        if(mysqli_num_rows($ccomps_applied_run)){
            while($row11=mysqli_fetch_assoc($ccomps_applied_run)){
                $comp_id=$row11['Comp_Id'];
                $quote_id=$row11['Quote_Id'];
                $q_grp="SELECT q.Comp_Id, ((qd.Quote_Detail_Disc*.01)* sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value)) as discounted_grp, sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as qty
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                        WHERE q.Post_Id='$post_id' AND q.Comp_Id='$comp_id' AND mp.Mat_Post_Status!=0 and q.Quote_Status=1 and qd.Quote_Detail_Status=1 GROUP BY  q.Comp_Id, qd.Quote_Detail_Disc";
                $q_grp_run=mysqli_query($connection,$q_grp);
                if(mysqli_num_rows($q_grp_run)>0){ $total_disc=0; $total_qty=0;
                    while($row_g=mysqli_fetch_assoc($q_grp_run)){
                        $comp_id=$row_g['Comp_Id'];
                        $grp_disc=$row_g['discounted_grp'];
                        $qty_rate=$row_g['qty'];
                        if($grp_disc==0){
                            // $total_qty=$total_qty+$qty_rate;
                        }
                        else{
                            $total_disc=$total_disc+$grp_disc;
                        }
                        $total_qty=$total_qty+$qty_rate;
                    }
                }
                $computed_total=$total_qty-$total_disc; 
                //update 
                $q_update="UPDATE quote SET Quote_Discount='$computed_total' WHERE Quote_Id='$quote_id'";
                $q_update_run=mysqli_query($connection,$q_update);
                $computed_total=0;
            } 
        }
        $comps_applied="SELECT comp.Comp_Name, comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id
                        ORDER BY q.Quote_Discount ASC";
    }
    else{
        $comps_applied="SELECT comp.Comp_Name, comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    // $comps_applied;
    $comps_applied_run=mysqli_query($connection,$comps_applied);
    $comp_no=mysqli_num_rows($comps_applied_run);
    $min_total=0;
    if($comp_no>0){ 
        // post details
        // header check 
        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id';";
        $q_header_run=mysqli_query($connection,$q_header);  $mat_details=''; $qty_tot=0;
        $row_h=mysqli_fetch_assoc($q_header_run);
        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
        $other_th=''; $td_span=4 ; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
        if($t_capacity>0){
            $other_th .='<th>Capacity</th>'; $th_span++; $c_cap=1;
        }
        if( $t_esp>0){
            $other_th .='<th>ESP(pa)</th>'; $th_span++;$c_esp=1;
        }
        if($t_prefBrand>0){
            $other_th .='<th>Preffered Brand</th>'; $th_span++;$c_pb=1;
        }
        if($t_location>0){
            $other_th .='<th>Location</th>'; $th_span++;$c_loc=1;
        }
        $comp_span=$th_span+1;
        $f_space=$comp_span+4;
        $comp_tbl.='
            <thead >
                <th colspan="'.$f_space.'" ah></th>';
        $comp_id_arr[]=null;
        $quote_id_arr[]=null;
        while($row=mysqli_fetch_assoc($comps_applied_run)){
            $quote_id=$row['Quote_Id'];
            $quote_id_arr[]=$row['Quote_Id'];
            $comp_name=$row['Comp_Name'];
            $comp_id_arr[]=$row['Comp_Id'];
            $comp_tbl.='
            <th colspan="3" class="table-danger">
                <input type="hidden" value="'.$quote_id.'">
                <a href="#" class="text-danger font-weight-bold quoteView">'.$comp_name.'</a>
            </th>';
            $second_h.='
                <th class="table-danger">U/P</th>
                <th class="table-danger">TOTAL</th>
                <th class="table-danger">REMARKS</th>';
        }
        $second_h.='<th class="table-info">Lowest U/P</th>
                    <th class="table-info">Total</th>
                    <th class="table-info">Status</th>
                    <th class="table-info">Company Name</th>';
        $comp_id_imp=implode("','",$comp_id_arr);
        $q_id_imp=implode("','",$quote_id_arr);
        $comp_tbl.='
                <th class="table-danger" colspan="4"></th>
            
                    <tr class="table-secondary font-weight-bold">
                        <th class="">S.NO.</th>
                        <th class="">Material Code</th>
                        <th class="">Description</th>
                        '.$other_th.'                
                        <th>Unit</th>
                        <th>QTY</th>'.$second_h;
        $comp_tbl.='                
                    </tr>
        </thead>';
        //SEARCH GROUPS by post id
        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
        $q_grp_run=mysqli_query($connection,$q_grp); $total_disc=0; $mat_post_id='';
        if(mysqli_num_rows($q_grp_run)>0){
            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                $mp_grp_id=$row_g['MP_Grp_Id'];
                $grp_name=$row_g['MP_Grp_Name'];
                $grp_location=$row_g['MP_Grp_Location'];
                if($grp_location==''){
                    $visb='d-none';
                }
                
                $comp_tbl.='
                <tr>
                    <td colspan="'.$f_space.'" class="text-primary">'.$grp_name.'</td>';
                //SEARCH FOR THE GRP DISCOUNT
                // display group discount
                $q_grp_disc="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$mp_grp_id' AND Mat_Post_Status=1  LIMIT 1";
                $q_grp_disc_run=mysqli_query($connection,$q_grp_disc); 
                if(mysqli_num_rows($q_grp_disc_run)>0){
                    $row_disc=mysqli_fetch_assoc($q_grp_disc_run);
                    $mat_id=$row_disc['Mat_Id'];//material name
                    $mat_post_id=$row_disc['Mat_Post_Id']; 
                }
                //group 
                $comps_applied_run1=mysqli_query($connection,$comps_applied);
                if(mysqli_num_rows($comps_applied_run)){
                    while($row1=mysqli_fetch_assoc($comps_applied_run1)){
                        $q_id=$row1['Quote_Id'];
                        $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$q_id' AND Quote_Detail_Status=1 ";
                        $q_disc_run=mysqli_query($connection,$q_disc);
                        $row_discount=mysqli_fetch_assoc($q_disc_run);
                        $grp_disc=$row_discount['Quote_Detail_Disc']; //////////

                        if($grp_disc==null){$grp_disc=0;}
                        $total_disc=$total_disc+$grp_disc;
                        if($grp_location==''){
                            $visb='d-none';
                        }
                        $comp_tbl.='
                        <td colspan="2"></td>
                        <td>'.$grp_disc.'%</td>';
                    }
                    $comp_tbl.='<td></td><td class="table-active"></td><td></td><td></td>';
                }
                $comp_tbl.='
                </tr>';
                // post details by group
                $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$mp_grp_id'";
                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                if(mysqli_num_rows($q_post_details)>0){ 
                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                    // header check
                    $mat_post_id=$row_p['Mat_Post_Id'];
                    $mat_id=$row_p['Mat_Id'];//material name
                    $mat_ref_code=$row_p['Mat_Post_Ref_Code'];
                    $qty=$row_p['Mat_Post_Qty'];//qty
                    if($c_cap==1){
                        $capacity=$row_p['Mat_Post_Capacity'];
                        $other_td .=' <td>'.$capacity.'</td>';
                    }
                    if($c_esp==1){
                        $esp=$row_p['Mat_Post_Esp'];
                        $other_td .='<td>'.$esp.'</td>';
                    }
                    if($c_pb==1){
                        $prefBrand=$row_p['Mat_Post_Brand'];//preffered brand
                        $other_td .='<td>'.$prefBrand.'</td>';
                    }
                    if($c_loc==1){
                        $location=$row_p['Mat_Post_Location'];
                        $other_td .='<td>'.$location.'</td>';
                    }
                    $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                    $mat_q_run=mysqli_query($connection,$mat_q);
                    if(mysqli_num_rows($mat_q_run)>0){
                        $row_m=mysqli_fetch_assoc($mat_q_run);
                        $mat_desc=$row_m['Mat_Desc'];
                        $mat_unit=$row_m['Mat_Unit'];
                    }
                    else{
                        $mat_desc=$row_p['Mat_Id'];
                        $mat_unit=$row_p['Mat_Post_Unit'];
                    }
                    $total=0;
                    $qty_tot=$qty_tot+$qty;
                    $c++;
                    $comp_tbl.='
                    <tr>
                        <td>'.$c.'</td>
                        <td>'.$mat_ref_code.'</td>
                        <td>'.$mat_desc.'</td>
                        '.$other_td.'
                        <td>'.$mat_unit.'</td>
                        <td>'.$qty.'</td>';
                    $comps_applied_run2=mysqli_query($connection,$comps_applied);// per comp
                    if(mysqli_num_rows($comps_applied_run2)){
                        while($row2=mysqli_fetch_assoc($comps_applied_run2)){
                            $q_id=$row2['Quote_Id'];
                            $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$q_id' AND Quote_Detail_Status=1 ";
                            $q_disc_run=mysqli_query($connection,$q_disc);
                            if(mysqli_num_rows($q_disc_run)>0){
                                while($row_qd=mysqli_fetch_assoc($q_disc_run)){
                                    $up= $row_qd['Quote_Detail_Value'];
                                    $remarks=$row_qd['Quote_Remarks'];
                                }
                            }
                            $tot= $up*$qty;
                            decPlace($tot);
                            decPlace($up);
                            $compTot=$compTot+$tot;
                            $comp_tbl.='
                            <td>'.$up.'</td>
                            <td>'.$tot.'</td>
                            <td>'.$remarks.'</td>';
                        }
                    }
                    $q_lowest="SELECT c.Comp_Id, qd.Quote_Detail_Id, qd.Quote_Detail_Approval, qd.`Quote_Detail_Value`,qd.`Quote_Detail_Disc`, c.Comp_Name
                                FROM `quote_detail` as qd
                                LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                                LEFT JOIN company AS c on c.Comp_Id=q.Comp_Id
                                WHERE q.`Quote_Id` in('$q_id_imp') and qd.Mat_Post_Id='$mat_post_id' AND qd.`Quote_Detail_Status`=1;";
                    $q_low_run=mysqli_query($connection,$q_lowest);
                    if($q_low_run){
                         $lowest=null;$min_arr=array(); $disc=null;$up_with_disc=null;
                        while($row_l=mysqli_fetch_assoc($q_low_run)){
                            $qd_id=$row_l['Quote_Detail_Id'];
                            $lcname=$row_l['Comp_Name'];
                            $comp_id=$row_l['Comp_Id'];
                            $unit_price=$row_l['Quote_Detail_Value'];
                            $discount=$row_l['Quote_Detail_Disc'];
                            if($discount!=0){ //discount has value
                                $disc=$unit_price*($discount*.01);
                                $up_with_disc=$unit_price-$disc;
                            }
                            else{// no discount
                                $up_with_disc=$unit_price;
                            }
                            // $index_c=$c.$qd_id;
                            $min_arr[$comp_id]=$up_with_disc; //
                            
                        }
                        $lowest= min($min_arr);
                        $index = array_search(min($min_arr), $min_arr); //
                        $tot_low=$lowest*$qty;
                        $min_total=$min_total+$tot_low;
                        $q_company_name="SELECT Comp_Name FROM company WHERE Comp_Id=$index"; 
                        $q_company_name_run=mysqli_query($connection,$q_company_name);
                        if($q_company_name_run){
                            $row_c=mysqli_fetch_assoc($q_company_name_run);
                            $company_name=$row_c['Comp_Name'];
                        }
                        // display button for status
                        $qd_stat="SELECT qd.Quote_Detail_Approval 
                                    FROM quote_detail as qd
                                    LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                                    WHERE q.Comp_Id='$index' AND qd.Mat_Post_Id='$mat_post_id' AND q.Quote_Status=1";
                        $qd_stat_run=mysqli_query($connection,$qd_stat);
                        if($qd_stat_run){
                            $row_stat=mysqli_fetch_assoc($qd_stat_run);
                            $qd_approval=$row_stat['Quote_Detail_Approval'];
                            if($qd_approval==0){//reject
                                $app_btn='<button class="btn btn-sm btn-danger mb-1 float-right disabled">Rejected</button>';
                                $comp_class='';
                            }
                            elseif($qd_approval==1){//approve
                                $app_btn='<button class="btn btn-sm btn-success mb-1 float-right disabled">Approved</button>';
                                $comp_class='';
                            }
                            else{ //pending
                                $app_btn='<button class="btn btn-sm btn-warning mb-1 float-right disabled">Pending</button>';
                                $comp_class='comp_ids';
                            }
                        }
                    }
                    $comp_tbl.='
                        <td>'.$lowest.'
                            <input type="text" class="'.$comp_class.' d-none" value="'.$index.'">
                            <input type="text" class="d-none" value="'.$mat_post_id.'">
                        </td>
                        <td class="table-active">'.$tot_low.'</td>
                        <td>'.$app_btn.'</td>
                        <td>'.$company_name.'</td>
                    </tr>';
                    $other_td='';
                    }
                }
            }
        }  
        // $td_span--;      
        $comp_tbl .='<tr>
                        <td colspan="'.$td_span.'" >
                            <span class="float-right font-weight-bold mr-3">TOTAL AMOUNT:</span>
                        </td>
                        <td class="font-weight-bold">'.$qty_tot.'</td>';
        $comps_applied_run3=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run3)){
            while($row3=mysqli_fetch_assoc($comps_applied_run3)){
                $q_id=$row3['Quote_Id'];
                $q_disc="SELECT qd.Quote_Detail_Value as Mat_UP, mp.Mat_Post_Qty as qty
                        FROM quote_detail as qd
                        LEFT JOIN material_post AS mp on mp.Mat_Post_Id=qd.Mat_Post_Id
                        WHERE Quote_Id='$q_id' AND Quote_Detail_Status=1 AND mp.Mat_Post_Status!=0";
                $q_disc_run=mysqli_query($connection,$q_disc);
                if(mysqli_num_rows($q_disc_run)>0){
                    while($row_qd=mysqli_fetch_assoc($q_disc_run)){
                        $mat_up= $row_qd['Mat_UP'];
                        $qty = $row_qd['qty']; // qty of post
                        $tot=$mat_up*$qty;
                        $total=$total+$tot;
                    }
                    decPlace($total);/////////////
                    $comp_tbl.='
                        <td></td>
                        <td class="font-weight-bold ">'.$total.'</td>
                        <td></td>';
                        $total=0;
                }
            }
            $comp_tbl.='<td></td><td class="font-weight-bold table-active">'.$min_total.'</td><td></td><td></td>';
        }
        
        $comp_tbl .='
                    </tr>';
                    $comp_tbl .='<tr>
                    <td colspan="'.$td_span.'" >
                        <span class="float-right font-weight-bold mr-3">DISCOUNT AMOUNT:</span>
                    </td>
                    <td></td>';
        $comps_applied_run4=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run4)){
            while($row4=mysqli_fetch_assoc($comps_applied_run4)){
                $q_id=$row4['Quote_Id'];
                $q_disc="SELECT Quote_Detail_Disc as disc, MP_Grp_Id FROM material_post as mat_p
                            LEFT JOIN quote_detail AS qd on qd.Mat_Post_Id=mat_p.Mat_Post_Id
                            WHERE Quote_Id='$q_id' GROUP BY MP_Grp_Id"; $total_disc=0;
                $q_disc_run=mysqli_query($connection,$q_disc);
                if(mysqli_num_rows($q_disc_run)>0){
                    while($row_qd=mysqli_fetch_assoc($q_disc_run)){
                        $disc=$row_qd['disc'];
                        $total_disc=$total_disc+$disc;
                        $grp_id=$row_qd['MP_Grp_Id'];
                    }
                }
                // search material unit price and mat_post_qty
                $q_total="SELECT sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as rate, qd.Quote_Detail_Disc
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        WHERE qd.Quote_Id='$q_id' GROUP BY mp.MP_Grp_Id ";
                $q_total_run=mysqli_query($connection,$q_total);
                if(mysqli_num_rows($q_total_run)>0){ $disc_amt_tot=0;$total_sum=0;
                    while($row_q=mysqli_fetch_assoc($q_total_run)){
                        $sum=$row_q['rate'];
                        $total_sum=$total_sum+$sum;
                        $disc_pct=$row_q['Quote_Detail_Disc'];
                        if($disc_pct!=0 || $disc_pct!=NULL){
                            $ddisc=$disc_pct*0.01;
                            $disc_amt=$sum*$ddisc;
                            $disc_amt_tot=$disc_amt_tot+$disc_amt;
                        }
                    }
                }
                $disc_avg=$total_disc/(mysqli_num_rows($q_disc_run));
                decPlace($disc_avg); decPlace($disc_amt_tot); $disc=0;
                $comp_tbl.='
                <td></td>
                <td class="table-active font-weight-bold">'.$disc_amt_tot.'</td>
                <td>'.$disc_avg.'%</td>';
                $disc_avg=0; $total_disc=0; $disc_amt_tot=0; 
            }
            $comp_tbl.='<td></td><td class="table-active"></td><td></td><td></td>';
        } 
        $comp_tbl .='
                    </tr>';$comp_tbl .='<tr>
                    <td colspan="'.$td_span.'" >
                        <span class="float-right font-weight-bold mr-3">TOTAL AMOUNT after DISCOUNT:</span>
                    </td>
                    <td></td>';
        $comps_applied_run6=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run6)){
            while($row6=mysqli_fetch_assoc($comps_applied_run6)){
                $q_id=$row6['Quote_Id'];
                $q_total="SELECT sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as rate, qd.Quote_Detail_Disc
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        WHERE qd.Quote_Id='$q_id' GROUP BY mp.MP_Grp_Id ";
                $q_total_run=mysqli_query($connection,$q_total);
                if(mysqli_num_rows($q_total_run)>0){ $disc_amt_tot=0;$total_sum=0;
                    while($row_q=mysqli_fetch_assoc($q_total_run)){
                        $sum=$row_q['rate'];
                        $total_sum=$total_sum+$sum;
                        $disc_pct=$row_q['Quote_Detail_Disc'];
                        if($disc_pct!=0 || $disc_pct!=NULL){
                            $ddisc=$disc_pct*0.01;
                            $disc_amt=$sum*$ddisc;
                            $disc_amt_tot=$disc_amt_tot+$disc_amt;
                        }
                    }
                }
                $amt_after_disc=$total_sum-$disc_amt_tot;
                decPlace($amt_after_disc);
                $comp_tbl.='
                <td></td>
                <td class="font-weight-bold">'.$amt_after_disc.'</td>
                <td></td>';
            } 
            $comp_tbl.='<td></td><td class="table-active"></td><td></td><td></td>';
        }
        $comp_tbl .='
                </tr>';$comp_tbl .='<tr>
                <td colspan="'.$td_span.'" >
                    <span class="float-right font-weight-bold mr-3">5% VAT:</span>
                </td>
                <td></td>';
        $comps_applied_run7=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run7)){
            while($row7=mysqli_fetch_assoc($comps_applied_run7)){
                $q_id=$row7['Quote_Id'];
                $q_total="SELECT sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as rate, qd.Quote_Detail_Disc
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        WHERE qd.Quote_Id='$q_id' GROUP BY mp.MP_Grp_Id ";
                $q_total_run=mysqli_query($connection,$q_total);
                if(mysqli_num_rows($q_total_run)>0){ $disc_amt_tot=0;$total_sum=0;
                    while($row_q=mysqli_fetch_assoc($q_total_run)){
                        $sum=$row_q['rate'];
                        $total_sum=$total_sum+$sum;
                        $disc_pct=$row_q['Quote_Detail_Disc'];
                        if($disc_pct!=0 || $disc_pct!=NULL){
                            $ddisc=$disc_pct*0.01;
                            $disc_amt=$sum*$ddisc;
                            $disc_amt_tot=$disc_amt_tot+$disc_amt;
                        }
                    }
                }
                $amt_after_disc=$total_sum-$disc_amt_tot;
                $amt_after_disc=$amt_after_disc*0.05;
                $comp_tbl.='
                    <td></td>
                    <td>'.$amt_after_disc.'</td>
                    <td></td>';
            }
            $vat_l=$min_total*0.05; 
            $amt_l_vat=$vat_l+$min_total;
            $vat_l=round($vat_l,2);
            $amt_l_vat=round($amt_l_vat,2);
            $comp_tbl.='<td></td><td class="table-active">'.$vat_l.'</td><td></td><td></td>';
        }
        $comp_tbl .='
            </tr>';$comp_tbl .='<tr>
            <td colspan="'.$td_span.'" >
                <span class="float-right font-weight-bold mr-3 text-danger">TOTAL AMOUNT WITH VAT:</span>
            </td>
            <td></td>';
        $comps_applied_run8=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run8)){
            while($row8=mysqli_fetch_assoc($comps_applied_run8)){
                $q_id=$row8['Quote_Id'];
                $q_total="SELECT sum(mp.Mat_Post_Qty*qd.Quote_Detail_Value) as rate, qd.Quote_Detail_Disc
                        FROM material_post as mp 
                        LEFT JOIN quote_detail as qd on mp.Mat_Post_Id=qd.Mat_Post_Id 
                        WHERE qd.Quote_Id='$q_id' GROUP BY mp.MP_Grp_Id ";
                $q_total_run=mysqli_query($connection,$q_total);
                if(mysqli_num_rows($q_total_run)>0){ $disc_amt_tot=0;$total_sum=0;
                    while($row_q=mysqli_fetch_assoc($q_total_run)){
                        $sum=$row_q['rate'];
                        $total_sum=$total_sum+$sum;
                        $disc_pct=$row_q['Quote_Detail_Disc'];
                        if($disc_pct!=0 || $disc_pct!=NULL){
                            $ddisc=$disc_pct*0.01;
                            $disc_amt=$sum*$ddisc;
                            $disc_amt_tot=$disc_amt_tot+$disc_amt;
                        }
                    }
                }
                $amt_after_disc=$total_sum-$disc_amt_tot;
                $vat=$amt_after_disc*0.05;
                $final_total=$vat+$amt_after_disc;
                decPlace($final_total);
                $comp_tbl.='
                    <td></td>
                    <td class="text-danger font-weight-bold">'.$final_total.'</td>
                    <td></td>';
            }
            $comp_tbl.='<td></td><td class="text-danger font-weight-bold table-active">'.$amt_l_vat.'</td><td></td><td></td>';
        }
        $terms='';$message='';
        $message .='<tr>
            <td colspan="'.$td_span.'" class="font-weight-bold"><span class="float-right mr-3">MESSAGE:</span></td>
            <td></td>';
        $terms .='<tr>
            <td colspan="'.$td_span.'" class="font-weight-bold"><span class="float-right mr-3">TERMS & CONDITION:</span></td>
            <td></td>';
        $comps_applied_run10=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run10)){
            while($row10=mysqli_fetch_assoc($comps_applied_run10)){
                $mmessage=$row10['Quote_Message'];
                $tterms=$row10['Quote_T&C'];
                $message.='<td colspan="3">'.$mmessage.'</td>';
                $terms.='<td colspan="3">'.$tterms.'</td>';
            }
            $message.='<td></td><td class=""></td><td></td><td></td>';
            $terms.='<td></td><td class=""></td><td></td><td></td>';
        }
        $message .='</tr>';
        $terms .='</tr>';
        $comp_tbl.=$message.$terms;
        $comp_tbl .='<tr>
            <td colspan="'.$td_span.'" class="font-weight-bold"><span class="float-right mr-3">STATUS:</span></td>
            <td></td>';
        $comps_applied_run9=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run9)){
            while($row9=mysqli_fetch_assoc($comps_applied_run9)){
                $q_id=$row9['Quote_Id'];
                $company_id=$row9['Comp_Id'];
                $q_quote="SELECT * FROM quote WHERE Quote_Id='$q_id'";
                $q_quote_run=mysqli_query($connection,$q_quote);
                if(mysqli_num_rows($q_quote_run)>0){$status ='';
                    $row_q=mysqli_fetch_assoc($q_quote_run);
                    $app_stat=$row_q['Quote_Approval'];
                    //BUTTONS 
                    if($app_stat==0){//rejected
                        $status='<button class="btn btn-sm btn-danger mb-1 float-right disabled">Rejected</button>';
                        $button_html='';
                    }
                    elseif($app_stat==1){//approved
                        $status=' <button class="btn btn-sm btn-success mb-1 float-right disabled">Approved</button>';
                        $button_html='';
                    }
                    elseif($app_stat==2){// pending, /show approve button
                        // $status='<button class="btn btn-sm btn-warning mb-1 disabled">Pending</button>';
                        $button_html='
                        <div class="btn-group">
                            <form action="code.php" method="POST">
                                <input type="hidden" name="post_id" value='.$post_id.'>
                                <input type="hidden" name="q_id" value='.$q_id.'>
                                <button  name="reject_m" type="submit" class="border-0 btn btn-light">
                                    <a href="#" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        <span class="text">Reject</span>
                                    </a>
                                </button>
                            </form>
                            <form action="code.php" method="POST">
                                <input type="hidden" name="post_id" value='.$post_id.'>
                                <input type="hidden" name="q_id" value='.$q_id.'>
                                <input type="hidden" name="comp_id" value='.$company_id.'>
                                <button name="approve_quote_m" type="submit" class="border-0 btn btn-light">
                                    <a href="#" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Approve</span>
                                    </a>
                                </button>
                            </form>
                        </div>';
                    }
                    else{
                        $status='';
                        $button_html=''; 
                    }
                }
                $comp_tbl.='
                <td colspan="3"></td>';
            }
            $comp_tbl.='<td></td><td></td><td></td><td><button class="btn btn-sm btn-success appBtn" id="appBtn">Approve All</button></td>';
        }
        $comp_tbl .='
        </tr>';
    }
    echo $comp_tbl;
}
// approve
if(isset($_POST['mat_post_id'])){
    $mat_post_id=$_POST['mat_post_id'];
    foreach ($mat_post_id as $row){
        $comp_id= $row[0];
        $c_arr[]=$comp_id;
        $mat_pid= $row[1];
        //search quote_detail id
        $q_qd_id="SELECT * FROM quote_detail as qd
                    LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                    WHERE qd.Quote_Detail_Status=1 and qd.Mat_Post_Id=$mat_pid AND q.Comp_Id=$comp_id AND q.Quote_Status=1";
        $q_qd_id_run=mysqli_query($connection,$q_qd_id);
        $row_num=mysqli_num_rows($q_qd_id_run);
        if($row_num>1){ // determine the lowest
            if(mysqli_num_rows($q_qd_id_run)>0){
                while($row=mysqli_fetch_assoc($q_qd_id_run)){
                    $qd_id=$row['Quote_Detail_Id'];
                    $unit_price=$row_l['Quote_Detail_Value'];
                    $discount=$row_l['Quote_Detail_Disc'];
                    if($disc_pct!=0 || $disc_pct!=null){ //with value
                        $disc=$unit_price*($discount*.01);
                        $up_with_disc=$unit_price-$disc;
                    }
                    else{
                        $up_with_disc=$unit_price;
                    }
                    $min_arr[$qd_id]=$up_with_disc;
                }
            }
            $index = array_search(min($min_arr), $min_arr); //qd id
            $qd_id_arr[]=$index;
        }
        else{
            if(mysqli_num_rows($q_qd_id_run)>0){
                while($row=mysqli_fetch_assoc($q_qd_id_run)){
                    $qd_id=$row['Quote_Detail_Id'];
                    $qd_id_arr[]=$qd_id;
                }
            }
        }
    }
    //select all qd id
    $qd_ids=implode("', '",$qd_id_arr);
    $result = array_unique($c_arr); $tbl='';
    foreach ($result as $ccomp_id) {
        //get company name
        $q_comp="SELECT * FROM company WHERE Comp_Id='$ccomp_id'";
        $q_comp_run=mysqli_query($connection,$q_comp);
        $tbl .="<table cellspacing='3'>";
        if(mysqli_num_rows($q_comp_run)>0){
            $row_c=mysqli_fetch_assoc($q_comp_run);
            $ccomp_name=$row_c['Comp_Name'];
            $tbl .="<tr>
                        <td width='65%'><h5 class='font-weight-bold text-primary'>".$ccomp_name."</h5></td>
                        <td class='pl-5 font-weight-bold'>Posted Qty</td>
                    </tr><tr></tr>";
        }
        $q_qd="SELECT * FROM quote_detail as qd
                LEFT JOIN quote as q on q.Quote_Id=qd.Quote_Id
                LEFT JOIN material_post as mp on mp.Mat_Post_Id = qd.Mat_Post_Id
                WHERE Quote_Detail_Id IN ('$qd_ids') AND q.Comp_Id='$ccomp_id'";
        $q_qd_run=mysqli_query($connection,$q_qd);
        if(mysqli_num_rows($q_qd_run)>0){
            while($row_qd=mysqli_fetch_assoc($q_qd_run)){
                $qqd_id=$row_qd['Quote_Detail_Id'];
                $qty=$row_qd['Mat_Post_Qty'];
                $mat_id=$row_qd['Mat_Id'];//material name
                $mat_ref_code=$row_qd['Mat_Post_Ref_Code'];
                if(is_numeric($mat_id)){
                    $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                    $mat_q_run=mysqli_query($connection,$mat_q);
                    if(mysqli_num_rows($mat_q_run)>0){
                        $row_m=mysqli_fetch_assoc($mat_q_run);
                        $mat_desc=$row_m['Mat_Desc'];
                        $mat_unit=$row_m['Mat_Unit'];
                    }
                    else{
                        $mat_desc=$row_p['Mat_Id'];
                        $mat_unit=$row_p['Mat_Post_Unit'];
                    }
                }
                else{
                    $mat_desc=$mat_id;
                }
                // <input class="form-control form-control-sm" type="number" name="qty[]" value="'.$qty.'">
                $tbl.= '<tr>
                            <td width="65%">
                                <input class="mr-1" type="checkbox" name="qd_id[]" value="'.$qqd_id.'">'.$mat_ref_code.' '.$mat_desc.' '.$mat_unit.'
                                <input type="hidden" name="comp_id[]" value="'.$ccomp_id.'">
                            </td>
                            <td class="pl-5">'.$qty.'</td>
                        </tr>';
            }
            $tbl.='<br>';
        }
    }
    $tbl.='</table>';
    echo $tbl;
}
// manpower/subcon
if(isset($_POST['comp_filter_mp'])){
    $comp_filter=$_POST['comp_filter_mp'];
    $post_id=$_POST['post_id'];
    $post_type=$_POST['post_type'];
    $comp_ids=$_POST['comp_ids'];
    if($comp_ids!="''"){
        $additional=' AND comp.Comp_Id IN ('.$comp_ids.')';
    }
    else{
        $additional='';
    }
    $comp_tbl='';$comp_name='';$second_h='';$c=0; $compTot=0;
    if($comp_filter=='Select Company'){
        $comps_applied="SELECT comp.Comp_Name,comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id, q.Quote_Approval FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    elseif($comp_filter=='All'){
        $comps_applied="SELECT comp.Comp_Name,comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id, q.Quote_Approval FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    elseif($comp_filter=='h'){
        $comps_applied="SELECT comp.Comp_Name,comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id, q.Quote_Approval, SUM(qd.Quote_Detail_Value) as tot FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id
                        ORDER BY tot DESC";
    }
    elseif($comp_filter=='l'){
        $comps_applied="SELECT comp.Comp_Name,comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id, q.Quote_Approval, SUM(qd.Quote_Detail_Value) as tot FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id
                        ORDER BY tot ASC";
    }
    else{
        $comps_applied="SELECT comp.Comp_Name,comp.Comp_Id, q.`Quote_T&C`, q.Quote_Message, q.Quote_Id, q.Quote_Approval FROM quote as q 
                        LEFT JOIN company as comp on comp.Comp_Id=q.Comp_Id 
                        LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
                        WHERE comp.Comp_Status=1 AND q.Quote_Status=1
                        AND q.Post_Id='$post_id' AND Quote_Detail_Id is not null $additional
                        GROUP BY q.Quote_Id, q.Comp_Id";
    }
    $comps_applied_run=mysqli_query($connection,$comps_applied);
    $comp_no=mysqli_num_rows($comps_applied_run);
    if($comp_no>0){ $header='';
        //subcon
            $header.='<thead>
            <tr class="table-secondary font-weight-bold">
                <th>Department</th>     
                <th>Description</th> 
                <th>Unit</th>
                <th>QTY</th>';
        //1st col (POST DETAILS)
        $comp_tbl.='
            <tr>
                <td colspan="3"><td>';
        while($row=mysqli_fetch_assoc($comps_applied_run)){
            $quote_id=$row['Quote_Id'];
            $comp_name=$row['Comp_Name'];
            $comp_tbl.='
            <td colspan="2" class="table-danger">
                <input type="hidden" value="'.$quote_id.'">
                <a href="#" class="text-danger font-weight-bold quoteView">'.$comp_name.'</a>
            </td>';
        
            $second_h.='
                <td class="table-danger">Rate/Unit</td>
                <td class="table-danger">TOTAL</td>';
        }
        $comp_tbl.='</tr>
        <tr>'.$header.$second_h.'</tr>
        </thead>';
        // post details
        $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
        $q_detail_run=mysqli_query($connection,$q_detail);
        if(mysqli_num_rows($q_detail_run)>0){
            while($row_d=mysqli_fetch_assoc($q_detail_run)){
                $dept=$row_d['Dept_Id'];
                $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                $dept_q_run=mysqli_query($connection,$dept_q);
                $row_dept=mysqli_fetch_assoc($dept_q_run);
                $department=$row_dept['Dept_Name'];
                $desc=$row_d['MP_Post_Desc'];
                $qty=$row_d['MP_Post_Qty'];
                $unit=$row_d['MP_Post_Unit'];
                $manpower_pId=$row_d['MP_Post_Id'];
                $comp_tbl.='
                <tr>
                    <td>'.$department.'</td>
                    <td>'.$desc.'</td>
                    <td>'.$unit.'</td>
                    <td>'.$qty.'</td>';
                //companies rate
                $comps_applied_run2=mysqli_query($connection,$comps_applied);
                if(mysqli_num_rows($comps_applied_run2)){
                    while($row2=mysqli_fetch_assoc($comps_applied_run2)){
                        $q_id=$row2['Quote_Id'];
                        $q_disc="SELECT * FROM quote_detail WHERE MP_Post_Id='$manpower_pId' AND Quote_Id='$q_id' AND Quote_Detail_Status=1 ";
                        $q_disc_run=mysqli_query($connection,$q_disc);
                        if(mysqli_num_rows($q_disc_run)>0){
                            while($row_qd=mysqli_fetch_assoc($q_disc_run)){
                                $up= $row_qd['Quote_Detail_Value'];
                            }
                        }
                        $tot= $up*$qty;
                        $tot=decPlace($tot);
                        $up=decPlace($up);
                        if($up==0){
                            $up=NULL;
                        }
                        if($tot==0){
                            $tot=NULL;
                        }
                        $comp_tbl.='
                        <td>'.$up.'</td>
                        <td>'.$tot.'</td>';
                        $tot=0; $up=0;
                    }
                }
                $comp_tbl.='</tr>';
            }
        }
        // total
        $comp_tbl.='
        <tr>
            <td colspan="3"></td>
            <td><span class="float-right font-weight-bold mr-3">TOTAL:</span></td>';
        //total /company
        $comps_applied_run3=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run3)){
            while($row3=mysqli_fetch_assoc($comps_applied_run3)){
                $q_id=$row3['Quote_Id'];
                $q_disc="SELECT qd.Quote_Detail_Value, mp.MP_Post_Qty FROM quote_detail as qd
                        LEFT JOIN manpower_post as mp on mp.MP_Post_Id=qd.MP_Post_Id
                        WHERE  qd.Quote_Id='$q_id' AND qd.Quote_Detail_Status=1 AND qd.Quote_Detail_Status=1";
                $q_disc_run=mysqli_query($connection,$q_disc);
                if(mysqli_num_rows($q_disc_run)>0){$all_t=0;
                    while($row_qd=mysqli_fetch_assoc($q_disc_run)){
                        $up= $row_qd['Quote_Detail_Value'];
                        $qty=$row_qd['MP_Post_Qty'];
                        $total=$up*$qty;
                        $all_t=$all_t+$total;
                        $total=0;
                    }
                    $all_t=decPlace($all_t);
                    $comp_tbl.='<td></td>
                        <td class="font-weight-bold text-success">'.$all_t.'</td>';
                    $all_t=0;
                }
            }
        }
        $comp_tbl.='
        </tr>';
        //MESSAGE
        $comp_tbl.='
        <tr>
            <td colspan="3"></td>
            <td><span class="float-right font-weight-bold mr-3">MESSAGE:</span></td>';
        $comps_applied_run4=mysqli_query($connection,$comps_applied);
        if(mysqli_num_rows($comps_applied_run4)){ $mmessage='';$ttc='';$status='';$stat='';
            while($row4=mysqli_fetch_assoc($comps_applied_run4)){
                $q_id=$row4['Quote_Id'];
                $comp_id=$row4['Comp_Id'];
                $message=$row4['Quote_Message'];
                $tc=$row4['Quote_T&C'];
                $mmessage.='
                <td colspan=2>'.$message.'</td>';
                $ttc.='
                <td colspan=2>'.$tc.'</td>';
                $app_stat=$row4['Quote_Approval']; 
                //BUTTONS 
                if($app_stat==0){//rejected
                    $status='<button class="btn btn-sm btn-danger mb-1 float-right disabled">Rejected</button>';
                    $button_html='';
                }
                elseif($app_stat==1){//approved
                    $status=' <button class="btn btn-sm btn-success mb-1 float-right disabled">Approved</button>';
                    $button_html='';
                }
                elseif($app_stat==2){// pending, /show approve button
                    $status='';
                    $button_html='
                    <div class="btn-group">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="post_id" value='.$post_id.'>
                            <input type="hidden" name="q_id" value='.$q_id.'>
                            <button  name="reject" type="submit" class="border-0 btn btn-sm btn-light">
                                <a href="#" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <span class="text">Reject</span>
                                </a>
                            </button>
                        </form>
                        <form action="code.php" method="POST">
                            <input type="hidden" name="post_id" value='.$post_id.'>
                            <input type="hidden" name="q_id" value='.$q_id.'>
                            <input type="hidden" name="comp_id" value='.$comp_id.'>
                            <button name="approve_quote" type="submit" class="border-0 btn btn-sm btn btn-light">
                                <a href="#" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Approve</span>
                                </a>
                            </button>
                        </form>
                    </div>';
                }
                else{
                    $status='';
                    $button_html=''; 
                }
                $stat.='
                <td colspan="2">'.$status.'<br>'.$button_html.'</td>';
            }
        }
        $comp_tbl.=$mmessage.'</tr>';
        //TERMS AND CONDITION
        $comp_tbl.='
        <tr>
            <td colspan="3"></td>
            <td><span class="float-right font-weight-bold mr-3">TERMS & CONDITION:</span></td>';
        $comp_tbl.=$ttc.'</tr>';
        //STATUS
        $comp_tbl.='
        <tr>
            <td colspan="3"></td>
            <td><span class="float-right font-weight-bold mr-3">STATUS:</span></td>
            '.$stat.'
        <tr>';
    }
    echo $comp_tbl;
}
?>