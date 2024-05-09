<?php
include('../../security.php');
error_reporting(0);
if(isset($_POST['post_id'])){   
    $post_id=$_POST['post_id'];
    $q_post="SELECT * FROM post as p
            LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
            WHERE p.Post_Id='$post_id' AND p.Post_Status=1";
    $q_post_run=mysqli_query($connection,$q_post);
    if(mysqli_num_rows($q_post_run)>0 ){
        while($row_p=mysqli_fetch_assoc($q_post_run)){
            $post_name=$row_p['Post_Name'];
            $prj_name=$row_p['Prj_Name'];
            $prj_code=$row_p['Prj_Code'];
            $post_desc=$row_p['Post_Desc'];
            $post_date=$row_p['Post_Date'];
            $post_date=date("M d, Y",strtotime($post_date)); $tbl='';
            $post_type=$row_p['Post_Type'];
            //post details table
            if($post_type=='manpower'){
                $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                $q_details_run=mysqli_query($connection,$q_details);
                $th_html='<th>Department</th>
                        <th >Description</th>
                        <th >Qty</th>
                        <th>Price/</th>
                        <th>Remarks</th>
                        <th class="d-none">Id</th>';
            }
            elseif($post_type=='subcontractor'){
                $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                $q_details_run=mysqli_query($connection,$q_details);
                $th_html='<th class="d-none">Department</th>
                        <th >Service Desc</th>
                        <th >Unit</th>
                        <th >Total Area No.</th>
                        <th>PRICE/Unit</th>
                        <th>Remarks</th>
                        <th class="d-none">Id</th>';
            }
            elseif($post_type=='material'){
                $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1 ";
                $q_details_run=mysqli_query($connection,$q_details);
            }
            $tbl.='
            <div class="form-row mb-3">
                <div class="col-9">
                    <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
                </div>
                <div class="col-3">'.$post_date.'</div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label class="font-weight-bold mr-1">Project:</label><br>
                </div>
                <div class="col-9">
                    <span>'.$prj_code.' '.$prj_name.'</span>
                </div>
            </div>
            <div class="form-row">
                <div class="col-3">
                    <label class="font-weight-bold mr-2 pr-3">Description:</label><br>
                </div>
                <div class="col-9">
                    <span >'.$post_desc.'</span>
                </div>
            </div>
            <div class="table-responsive" id="tbl">
                <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">';
                if($post_type=='manpower'||$post_type=='subcontractor'){
                $tbl.='
                    <thead> '.$th_html.'</thead>
                    <tbody>';
                    if(mysqli_num_rows($q_details_run)>0){$i=0;
                        while($row_d=mysqli_fetch_assoc($q_details_run)){ 
                            if($post_type=='subcontractor'){
                                $td1=$row_d['MP_Post_Desc'];//description
                                $td2=$row_d['MP_Post_Unit'];//unit
                                $td3=$row_d['MP_Post_Qty'];//area
                                $td4=$row_d['MP_Post_Id'];
                                $unit=$td2;
                            }
                            elseif($post_type=='manpower'){
                                $dept_id=$row_d['Dept_Id'];//department
                                $td2=$row_d['MP_Post_Desc'];//desc    
                                $td3=$row_d['MP_Post_Qty'];//qty person need
                                $td4=$row_d['MP_Post_Id'];
                                $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                                $dept_q_run=mysqli_query($connection,$dept_q);
                                $row_dept=mysqli_fetch_assoc($dept_q_run);
                                $td1=$row_dept['Dept_Name'];
                                $unit='hour';
                            }
                            $tbl.='
                            <tr>
                                <td>'.$td1.'</td>
                                <td>'.$td2.'</td>
                                <td>'.$td3.'</td>
                                <td class=" ">
                                    <div class="form-row">
                                        <div class="col-8">
                                            <input type="number" name="q_qty[]" class="form-control" required>
                                        </div>
                                        <div class="col-4">
                                            '.'/'.$unit.'
                                        </div>
                                    </div>
                                </td>
                                <td><input type="text" name="remarks[]" class="form-control"></td>
                                <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                            </tr>';
                        }
                    }
                }
                elseif($post_type=='material'){
                    // header check 
                    $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id'";
                    $q_header_run=mysqli_query($connection,$q_header); $mat_details='';
                    $row_h=mysqli_fetch_assoc($q_header_run);
                    $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
                    $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
                    $other_th=''; $td_span=5; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
                    if($t_capacity>0){
                        $other_th .='<th>Capacity</th>'; $td_span++; $c_cap=1;
                    }
                    if( $t_esp>0){
                        $other_th .='<th>ESP(pa)</th>'; $td_span++;$c_esp=1;
                    }
                    if($t_prefBrand>0){
                        $other_th .='<th>Preffered Brand</th>'; $td_span++;$c_pb=1;
                    }
                    if($t_location>0){
                        $other_th .='<th>Location</th>'; $td_span++;$c_loc=1;
                    }
                    $tbl.='
                    <input type="hidden" id="grpSpan" value="'.$td_span.'">
                    <thead>
                        <th>Description</th>
                        '.$other_th.'                
                        <th>Unit</th>
                        <th>QTY</th>
                        <th class=" ">U/P</th>
                        <th class="d-none comp">Total</th> 
                        <th class=" ">Remarks</th>
                        <th class=" ">Discount/Grp</th>
                    </thead>';
                    //SEARCH GROUPS by post id
                    $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
                    $q_grp_run=mysqli_query($connection,$q_grp); $visb=''; $total_qty=0;$allTotal=0;
                    if(mysqli_num_rows($q_grp_run)>0){
                        while($row_g=mysqli_fetch_assoc($q_grp_run)){
                            $grp_id=$row_g['MP_Grp_Id'];
                            $grp_name=$row_g['MP_Grp_Name'];
                            $grp_location=$row_g['MP_Grp_Location'];
                            if($grp_location==''){
                                $visb='d-none';
                            }//group 
                            $tbl.='<input type="hidden" id="grp_ids'.$grp_id.'" value="'.$grp_id.'">
                            <tr>
                                <td colspan="'.$td_span.'" class="font-weight-bold grpColspan">
                                    <div class="form-row">
                                        <div class="col-8">
                                            '.$grp_name.' 
                                        </div>
                                        <div class="col-4">
                                            <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                                        </div>
                                    </div>
                                </td>
                                <td class=" ">
                                    <div class="form-row">
                                        <div class="col-8">
                                            <input type="number" name="grp_disc[]" id="grpDisc'.$grp_id.'" step=".01" class="grpDisc form-control form-control-sm" >
                                            <input type="hidden" name="grpIds[]" value="'.$grp_id.'">
                                        </div>
                                        <div class="col-4">
                                            %
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                            // post details by group
                            $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
                            $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                            if(mysqli_num_rows($q_post_details)>0){
                                while($row_p=mysqli_fetch_assoc($q_post_details)){
                                    // header check
                                $mat_id=$row_p['Mat_Id'];//material name
                                $mat_post_id=$row_p['Mat_Post_Id'];    
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
                                $qty=$row_p['Mat_Post_Qty'];//qty
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
                                $tbl.='<input type="hidden" id="mat_post_id'.$grp_id.''.$mat_post_id.'" value="'.$mat_post_id.'">
                                <tr>
                                    <td>'.$mat_desc.'</td>
                                    '.$other_td.'
                                    <td>'.$mat_unit.'</td>
                                    <td>'.$qty.'</td>
                                    <td class=" ">
                                        <div class="form-row">
                                            <div class="col-7">
                                                <input type="hidden" name="qty" id="qty'.$mat_post_id.'" value="'.$qty.'">
                                                <input type="number" id="unitP'.$mat_post_id.'" name="q_qty[]" class="form-control rate form-control-sm " step=".01" required>
                                            </div>
                                            <div class="col-5">
                                                '.$mat_unit.'
                                            </div>
                                        </div>
                                    </td>
                                    <td class="comp d-none"><input type="text" id="tot'.$mat_post_id.'" class="no-border form-control-sm form-control"  ></td>
                                    <td class=" "><input type="text" name="remarks[]" class="form-control form-control-sm"></td>
                                    <td class=" " colspan="2"></td>
                                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$mat_post_id.'"></td>
                                </tr>';$other_td='';
                                $total_qty=$total_qty+$qty;
                                }
                                $allTotal=$allTotal+$total_qty;
                                $total_qty=0;
                            }
                        }
                        $total_td=$td_span-3;
                        $tbl.='
                        <tr>
                            <td colspan="'.$total_td.'" class="font-weight-bold">
                                <span class="float-right">TOTAL AMOUNT</span>
                            </td>
                            <td>'.$allTotal.'</td>
                            <td></td>
                            <td class="comp d-none"><input type="text" id="grpTotal" class="no-border form-control-sm form-control" readonly></td>
                            <td class=" " colspan="2"></td>
                        </tr>
                        <tr class="comp d-none">
                            <td colspan="'.$total_td.'" class="font-weight-bold">
                                <span class="float-right" >AVERAGE DISCOUNT</span>
                            </td>
                            <td><input id="aveDisc" type="text" class="no-border form-control form-control-sm" readonly></td>
                            <td></td>
                            <td class="comp d-none"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="comp d-none">
                            <td colspan="'.$total_td.'" class="font-weight-bold">
                                <span class="float-right">DISCOUNT AMOUNT</span>
                            </td>
                            <td><input id="discAmt" type="text" class="no-border form-control form-control-sm" readonly></td>
                            <td></td>
                            <td class="comp d-none"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="comp d-none">
                            <td colspan="'.$total_td.'" class="font-weight-bold">
                                <span class="float-right">TOTAL AMOUNT after DISCOUNT</span>
                            </td>
                            <td><input id="totAfterDisc" type="text" class="no-border form-control form-control-sm" readonly></td>
                            <td></td>
                            <td class="comp d-none"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="comp d-none">
                            <td colspan="'.$total_td.'" class="font-weight-bold">
                                <span class="float-right">5% VAT</span>
                            </td>
                            <td><input id="vatVal" type="text" class="no-border form-control form-control-sm" readonly></td>
                            <td></td>
                            <td class="comp d-none"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="comp d-none">
                            <td colspan="'.$total_td.'" class="font-weight-bold text-danger">
                                <span class="float-right">TOTAL AMOUNT WITH VAT</span>
                            </td>
                            <td><input id="amtWithVat" type="text" class="no-border form-control form-control-sm" readonly></td>
                            <td></td>
                            <td class="comp d-none"></td>
                            <td colspan="2"></td>
                        </tr>';
                        
                    }
                    $allTotal=0;
                }
                else{ }
                $tbl.='
                    </tbody>
                </table>
            <input type="hidden" name="prj_name" id="prj_name" value="'.$prj_name.'">
            <input type="hidden" name="post_id" id="post_id" value="'.$post_id.'"> 
            <input type="hidden" name="post_name" id="post_name" value="'.$post_name.'">
            <!-- <input type="hidden" name="com"> -->
            <input type="hidden" name="" id="post_id" value="'.$post_id.'"> 
            </div>';
        }
    }
    echo $tbl;
}
function decPlace($num){
    if($num!=null){
        if ( strpos( $num, "." ) !== false ) {
            $num=number_format($num, 2);
        }
    }
    return $num;
}
if(isset($_POST['quote_id'])){   
    $quote_id=$_POST['quote_id'];
    $q_detail="SELECT * FROM quote 
                LEFT JOIN company on company.Comp_Id=quote.Comp_Id 
                WHERE Quote_Id='$quote_id' AND Quote_Status=1 LIMIT 1";
    $q_detail_run=mysqli_query($connection,$q_detail);
    $tbl=''; $post_name='';
    if(mysqli_num_rows($q_detail_run)>0){
        $row_q=mysqli_fetch_assoc($q_detail_run);
        $post_id=$row_q['Post_Id'];
        $comp_id=$row_q['Comp_Id'];
        $message=$row_q['Quote_Message'];
        $tc=$row_q['Quote_T&C'];
        $comp_name=$row_q['Comp_Name'];
        $q_date_submitted=$row_q['Quote_Submitted'];
        $q_date_submitted=date("M d, Y",strtotime($q_date_submitted));
        $q_post="SELECT * FROM post as p
                LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
                WHERE p.Post_Id='$post_id' AND p.Post_Status!=0";
        $q_post_run=mysqli_query($connection,$q_post);
        if(mysqli_num_rows($q_post_run)>0 ){
            while($row_p=mysqli_fetch_assoc($q_post_run)){
                $post_name=$row_p['Post_Name'];
                $prj_name=$row_p['Prj_Name'];
                $prj_code=$row_p['Prj_Code'];
                $post_desc=$row_p['Post_Desc'];
                $post_date=$row_p['Post_Date'];
                $post_date=date("M d, Y",strtotime($post_date)); $tbl='';
                $post_type=$row_p['Post_Type'];$disc_tot_grp=0;
                //post details table
                if($post_type=='manpower'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th>Department</th>
                        <th >Description</th>
                        <th>Remarks</th>
                        <th >Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='subcontractor'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th class="d-none">Department</th>
                        <th >Service Desc</th>
                        <th >Unit</th>
                        <th>Remarks</th>
                        <th >Total Area No.</th>
                        <th>PRICE/Unit</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='material'){
                    $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                }
                $tbl.='
                <div class="form-row mb-1">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary ">Subject:</h5>
                            </div>
                            <div class="6">
                                Quotation from '.$comp_name.'
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary">Date Submitted:</h5>
                            </div>
                            <div class="6">
                                '.$q_date_submitted.'
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-1">Project:</label><br>
                    </div>
                    <div class="col-11">
                        <span>'.$prj_code.' '.$prj_name.'</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-2 pr-3">Description:</label><br>
                    </div>
                    <div class="col-11">
                        <span >'.$post_desc.'</span>
                    </div>
                </div>
                <div class="table-responsive" id="tbl">
                    <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">';
                    if($post_type=='manpower'||$post_type=='subcontractor'){
                    $tbl.='
                        <thead> '.$th_html.'</thead>
                        <tbody>';
                        if(mysqli_num_rows($q_details_run)>0){$i=0; $tot_price=0;
                            while($row_d=mysqli_fetch_assoc($q_details_run)){ 
                                $mp_post_id=$row_d['MP_Post_Id'];
                                if($post_type=='subcontractor'){
                                    $td1=$row_d['MP_Post_Desc'];//description
                                    $td2=$row_d['MP_Post_Unit'];//unit
                                    $td3=$row_d['MP_Post_Qty'];//area
                                    $td4=$row_d['MP_Post_Id'];
                                    $unit=$td2;
                                }
                                elseif($post_type=='manpower'){
                                    $dept_id=$row_d['Dept_Id'];//department
                                    $td2=$row_d['MP_Post_Desc'];//desc    
                                    $td3=$row_d['MP_Post_Qty'];//qty person need
                                    $td4=$row_d['MP_Post_Id'];
                                    $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                                    $dept_q_run=mysqli_query($connection,$dept_q);
                                    $row_dept=mysqli_fetch_assoc($dept_q_run);
                                    $td1=$row_dept['Dept_Name'];
                                    $unit='hour';
                                }
                                $q_disc="SELECT * FROM quote_detail WHERE MP_Post_Id='$mp_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $remarks=$row_discount['Quote_Remarks'];
                                $price=$row_discount['Quote_Detail_Value'];
                                $tot_rate=$tot_rate+$price;
                                $total=$td3*$price;
                                $allTotal=$allTotal+$total;
                                $tbl.='
                                <tr>
                                    <td>'.$td1.'</td>
                                    <td>'.$td2.'</td>
                                    <td>'.$remarks.'</td>
                                    <td>'.$td3.'</td>
                                    <td>'.$price.'</td>
                                    <td>'.$total.'</td>
                                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                                </tr>';
                            }
                            $tbl.='
                            <tr>
                                <td></td>
                                <td></td>    
                                <td></td>    
                                <td>TOTAL</td>    
                                <td>'.$tot_rate.'</td> 
                                <td>'.$allTotal.'</td>   
                            </tr>';
                        }
                    }
                    elseif($post_type=='material'){
                        // header check 
                        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id'";
                        $q_header_run=mysqli_query($connection,$q_header); $mat_details='';
                        $row_h=mysqli_fetch_assoc($q_header_run);
                        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
                        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
                        $other_th=''; $td_span=6; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
                        if($t_capacity>0){
                            $other_th .='<th>Capacity</th>'; $td_span++; $c_cap=1;
                        }
                        if( $t_esp>0){
                            $other_th .='<th>ESP(pa)</th>'; $td_span++;$c_esp=1;
                        }
                        if($t_prefBrand>0){
                            $other_th .='<th>Preffered Brand</th>'; $td_span++;$c_pb=1;
                        }
                        if($t_location>0){
                            $other_th .='<th>Location</th>'; $td_span++;$c_loc=1;
                        }
                        $tbl.='
                        <input type="hidden" id="grpSpan" value="'.$td_span.'">
                        <thead>
                            <th>Description</th>
                            '.$other_th.'                
                            <th>Unit</th>
                            <th>QTY</th>
                            <th class=" ">U/P</th>
                            <th>Total</th> 
                            <th class=" ">Remarks</th>
                            <th class=" ">Discount/Grp</th>
                        </thead>';
                        //SEARCH GROUPS by post id
                        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
                        $q_grp_run=mysqli_query($connection,$q_grp); $visb=''; $total_qty=0;$allTotal=0;$total_disc=0;$compTot=0;
                        if(mysqli_num_rows($q_grp_run)>0){
                            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                                $grp_id=$row_g['MP_Grp_Id'];
                                $grp_name=$row_g['MP_Grp_Name'];
                                $grp_location=$row_g['MP_Grp_Location'];
                                // display group discount
                                $q_grp_disc="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id' LIMIT 1";
                                $q_grp_disc_run=mysqli_query($connection,$q_grp_disc);
                                $row_disc=mysqli_fetch_assoc($q_grp_disc_run);
                                $mat_id=$row_disc['Mat_Id'];//material name
                                $mat_post_id=$row_disc['Mat_Post_Id'];   

                                $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $grp_disc=$row_discount['Quote_Detail_Disc'];

                                if($grp_disc==null){$grp_disc=0;}
                                $total_disc=$total_disc+$grp_disc;
                                if($grp_location==''){
                                    $visb='d-none';
                                }//group 
                                $tbl.='<input type="hidden" id="grp_ids'.$grp_id.'" value="'.$grp_id.'">
                                <tr>
                                    <td colspan="'.$td_span.'" class="font-weight-bold grpColspan">
                                        <div class="form-row">
                                            <div class="col-8">
                                                '.$grp_name.' 
                                            </div>
                                            <div class="col-4">
                                                <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>'.$grp_disc.'%</td>
                                </tr>';
                                // post details by group
                                $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
                                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                                if(mysqli_num_rows($q_post_details)>0){
                                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                                        // header check
                                    $mat_id=$row_p['Mat_Id'];//material name
                                    $mat_post_id=$row_p['Mat_Post_Id'];    
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
                                    $qty=$row_p['Mat_Post_Qty'];//qty
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
                                    $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' LIMIT 1";
                                    $q_disc_run=mysqli_query($connection,$q_disc);
                                    $row_qd=mysqli_fetch_assoc($q_disc_run);
                                    $up= $row_qd['Quote_Detail_Value'];
                                    $tot= $up*$qty;
                                    decPlace($tot);
                                    $remarks=$row_qd['Quote_Remarks'];
                                    $allTotal=$allTotal+$qty;
                                    $tbl.='
                                    <tr>
                                        <td>'.$mat_desc.'</td>
                                        '.$other_td.'
                                        <td>'.$mat_unit.'</td>
                                        <td>'.$qty.'</td>
                                        <td>'.$up.'</td>
                                        <td>'.$tot.'</td>
                                        <td>'.$remarks.'</td>
                                        <td></td>
                                    </tr>';$other_td='';
                                    $total_qty=$total_qty+$tot;
                                    $compTot=$compTot+$tot;
                                    }
                                    // ECHO $compTot.'comp tot<br>';
                                    if($grp_disc!=0 || $grp_disc!=NULL){
                                        // $disc_val= 100-$grp_disc;
                                        $disc_val=$grp_disc*0.01;
                                        $disc_grp= $disc_val*$compTot;
                                        // Echo $disc_grp;
                                        $disc_tot_grp=$disc_tot_grp+$disc_grp;
                                    }
                                    
                                    // echo $total_qty;
                                    $compTot=0;
                                }
                            }
                            $total_td=$td_span-4;
                            $grp_no=mysqli_num_rows($q_grp_run);
                            $ave_disc=$total_disc/$grp_no;  // AVERAGE DISCOUNT
                            //DISCOUNT AMOUNT
                            //TOTAL AMOUNT AFTER DISCOUNT
                            $discounted_amt=$total_qty-$disc_tot_grp;
                            //5% VAT
                            $vat = $discounted_amt*0.05;
                            //TOTAL AMOUNT WITH VAT
                            $totWithVat=$discounted_amt+$vat;
                            //decimal places
                            $compTot=decPlace($compTot);
                            $ave_disc=decPlace($ave_disc);
                            $disc_tot_grp=decPlace($disc_tot_grp);
                            $discounted_amt=decPlace($discounted_amt);
                            $vat=decPlace($vat);
                            $totWithVat=decPlace($totWithVat);
                            $tbl.='
                            <tr>
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT</span>
                                </td>
                                <td>'.$allTotal.'</td>
                                <td></td>
                                <td>'.$total_qty.'</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right" >AVERAGE DISCOUNT</span>
                                </td>
                                <td>'.$ave_disc.'%</td>
                                <td></td>
                                <td ></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">DISCOUNT AMOUNT</span>
                                </td>
                                <td>'.$disc_tot_grp .'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT after DISCOUNT</span>
                                </td>
                                <td>'.$discounted_amt.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">5% VAT</span>
                                </td>
                                <td>'.$vat.'</td>
                                <td></td>
                                <td></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold text-danger">
                                    <span class="float-right">TOTAL AMOUNT WITH VAT</span>
                                </td>
                                <td>'.$totWithVat.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="2"></td>
                            </tr>';
                            
                        }
                        $allTotal=0;
                    }
                    else{ }
                    $tbl.='
                        </tbody>
                    </table>
                <input type="hidden" name="prj_name" id="prj_name" value="'.$prj_name.'">
                <input type="hidden" name="post_id" id="post_id" value="'.$post_id.'"> 
                <input type="hidden" name="post_name" id="post_name" value="'.$post_name.'">
                <!-- <input type="hidden" name="com"> -->
                <input type="hidden" name="" id="post_id" value="'.$post_id.'"> 
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <span class="font-weight-bold">Message:</span><br>
                        '.$message.'
                    </div>
                    <div class="col-6">
                        <span class="font-weight-bold">Terms & Conditions:</span><br>
                        '.$tc.'
                    </div>
                </div>';
            }
        }
        echo $tbl;
    }
    else{
        echo 'Error Loading Quote Details';
    }
}
//company quote view
if(isset($_POST['quote_id_comp'])){   
    $quote_id=$_POST['quote_id_comp'];
    $q_detail="SELECT * FROM quote 
                LEFT JOIN company on company.Comp_Id=quote.Comp_Id 
                WHERE Quote_Id='$quote_id' AND Quote_Status=1 LIMIT 1";
    $q_detail_run=mysqli_query($connection,$q_detail);
    $tbl=''; $post_name='';
    if(mysqli_num_rows($q_detail_run)>0){
        $row_q=mysqli_fetch_assoc($q_detail_run);
        $post_id=$row_q['Post_Id'];
        $comp_id=$row_q['Comp_Id'];
        $message=$row_q['Quote_Message'];
        $tc=$row_q['Quote_T&C'];
        $comp_name=$row_q['Comp_Name'];
        $q_date_submitted=$row_q['Quote_Submitted'];
        $q_date_submitted=date("M d, Y",strtotime($q_date_submitted));
        $q_post="SELECT * FROM post as p
                LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
                WHERE p.Post_Id='$post_id' AND p.Post_Status!=0";
        $q_post_run=mysqli_query($connection,$q_post);
        if(mysqli_num_rows($q_post_run)>0 ){
            while($row_p=mysqli_fetch_assoc($q_post_run)){
                $post_name=$row_p['Post_Name'];
                $prj_name=$row_p['Prj_Name'];
                $prj_code=$row_p['Prj_Code'];
                $post_desc=$row_p['Post_Desc'];
                $post_date=$row_p['Post_Date'];
                $post_date=date("M d, Y",strtotime($post_date)); $tbl='';
                $post_type=$row_p['Post_Type'];$disc_tot_grp=0;
                //post details table
                if($post_type=='manpower'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th>Department</th>
                        <th >Description</th>
                        <th>Remarks</th>
                        <th >Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='subcontractor'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th class="d-none">Department</th>
                        <th >Service Desc</th>
                        <th >Unit</th>
                        <th>Remarks</th>
                        <th >Total Area No.</th>
                        <th>PRICE/Unit</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='material'){
                    $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                }
                $tbl.='
                <div class="form-row mb-1">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary ">Subject:</h5>
                            </div>
                            <div class="6">
                                Quotation from '.$comp_name.'
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary">Date Submitted:</h5>
                            </div>
                            <div class="6">
                                '.$q_date_submitted.'
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-1">Project:</label><br>
                    </div>
                    <div class="col-11">
                        <span>'.$prj_code.' '.$prj_name.'</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-2 pr-3">Description:</label><br>
                    </div>
                    <div class="col-11">
                        <span >'.$post_desc.'</span>
                    </div>
                </div>
                <div class="table-responsive" id="tbl">
                    <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">';
                    if($post_type=='manpower'||$post_type=='subcontractor'){
                    $tbl.='
                        <thead> '.$th_html.'</thead>
                        <tbody>';
                        if(mysqli_num_rows($q_details_run)>0){$i=0; $tot_price=0;
                            while($row_d=mysqli_fetch_assoc($q_details_run)){ 
                                $mp_post_id=$row_d['MP_Post_Id'];
                                if($post_type=='subcontractor'){
                                    $td1=$row_d['MP_Post_Desc'];//description
                                    $td2=$row_d['MP_Post_Unit'];//unit
                                    $td3=$row_d['MP_Post_Qty'];//area
                                    $td4=$row_d['MP_Post_Id'];
                                    $unit=$td2;
                                }
                                elseif($post_type=='manpower'){
                                    $dept_id=$row_d['Dept_Id'];//department
                                    $td2=$row_d['MP_Post_Desc'];//desc    
                                    $td3=$row_d['MP_Post_Qty'];//qty person need
                                    $td4=$row_d['MP_Post_Id'];
                                    $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                                    $dept_q_run=mysqli_query($connection,$dept_q);
                                    $row_dept=mysqli_fetch_assoc($dept_q_run);
                                    $td1=$row_dept['Dept_Name'];
                                    $unit='hour';
                                }
                                $q_disc="SELECT * FROM quote_detail WHERE MP_Post_Id='$mp_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $remarks=$row_discount['Quote_Remarks'];
                                $price=$row_discount['Quote_Detail_Value'];
                                $tot_rate=$tot_rate+$price;
                                $total=$td3*$price;
                                $allTotal=$allTotal+$total;
                                $tbl.='
                                <tr>
                                    <td>'.$td1.'</td>
                                    <td>'.$td2.'</td>
                                    <td>'.$remarks.'</td>
                                    <td>'.$td3.'</td>
                                    <td>'.$price.'</td>
                                    <td>'.$total.'</td>
                                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                                </tr>';
                            }
                            $tbl.='
                            <tr>
                                <td></td>
                                <td></td>    
                                <td></td>    
                                <td>TOTAL</td>    
                                <td>'.$tot_rate.'</td> 
                                <td>'.$allTotal.'</td>   
                            </tr>';
                        }
                    }
                    elseif($post_type=='material'){
                        // header check 
                        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id'";
                        $q_header_run=mysqli_query($connection,$q_header); $mat_details='';
                        $row_h=mysqli_fetch_assoc($q_header_run);
                        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
                        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
                        $other_th=''; $td_span=7; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
                        if($t_capacity>0){
                            $other_th .='<th>Capacity</th>'; $td_span++; $c_cap=1;
                        }
                        if( $t_esp>0){
                            $other_th .='<th>ESP(pa)</th>'; $td_span++;$c_esp=1;
                        }
                        if($t_prefBrand>0){
                            $other_th .='<th>Preffered Brand</th>'; $td_span++;$c_pb=1;
                        }
                        if($t_location>0){
                            $other_th .='<th>Location</th>'; $td_span++;$c_loc=1;
                        }
                        $tbl.='
                        <input type="hidden" id="grpSpan" value="'.$td_span.'">
                        <thead>
                            <th>Description</th>
                            '.$other_th.'                
                            <th>Unit</th>
                            <th>QTY</th>
                            <th class=" ">U/P</th>
                            <th>Total</th> 
                            <th class=" ">Remarks</th>
                            <th class=" ">Discount/Grp</th>
                            <th>Status</th>
                        </thead>';
                        //SEARCH GROUPS by post id
                        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
                        $q_grp_run=mysqli_query($connection,$q_grp); $visb=''; $total_qty=0;$allTotal=0;$total_disc=0;$compTot=0;
                        if(mysqli_num_rows($q_grp_run)>0){
                            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                                $grp_id=$row_g['MP_Grp_Id'];
                                $grp_name=$row_g['MP_Grp_Name'];
                                $grp_location=$row_g['MP_Grp_Location'];
                                // display group discount
                                $q_grp_disc="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id' LIMIT 1";
                                $q_grp_disc_run=mysqli_query($connection,$q_grp_disc);
                                $row_disc=mysqli_fetch_assoc($q_grp_disc_run);
                                $mat_id=$row_disc['Mat_Id'];//material name
                                $mat_post_id=$row_disc['Mat_Post_Id'];   

                                $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $grp_disc=$row_discount['Quote_Detail_Disc'];

                                if($grp_disc==null){$grp_disc=0;}
                                $total_disc=$total_disc+$grp_disc;
                                if($grp_location==''){
                                    $visb='d-none';
                                }//group 
                                $tbl.='<input type="hidden" id="grp_ids'.$grp_id.'" value="'.$grp_id.'">
                                <tr>
                                    <td colspan="'.$td_span.'" class="font-weight-bold grpColspan">
                                        <div class="form-row">
                                            <div class="col-8">
                                                '.$grp_name.' 
                                            </div>
                                            <div class="col-4">
                                                <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>'.$grp_disc.'%</td>
                                </tr>';
                                // post details by group
                                $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
                                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                                if(mysqli_num_rows($q_post_details)>0){
                                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                                        // header check
                                    $mat_id=$row_p['Mat_Id'];//material name
                                    $mat_post_id=$row_p['Mat_Post_Id'];    
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
                                    $qty=$row_p['Mat_Post_Qty'];//qty
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
                                    $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' LIMIT 1";
                                    $q_disc_run=mysqli_query($connection,$q_disc);
                                    $row_qd=mysqli_fetch_assoc($q_disc_run);
                                    $up= $row_qd['Quote_Detail_Value'];
                                    $tot= $up*$qty;
                                    decPlace($tot);
                                    $remarks=$row_qd['Quote_Remarks'];
                                    $allTotal=$allTotal+$qty;
                                    //qd status
                                    $qd_approval=$row_qd['Quote_Detail_Approval'];
                                    // display button for status
                                    if($qd_approval==0){//reject
                                        $app_btn='<button class="btn btn-sm btn-danger mb-1 float-right disabled">Rejected</button>';
                                    }
                                    elseif($qd_approval==1){//approve
                                        $app_btn='<button class="btn btn-sm btn-success mb-1 float-right disabled">Approved</button>';
                                    }
                                    else{ //pending
                                        $app_btn='<button class="btn btn-sm btn-warning mb-1 float-right disabled">Pending</button>';
                                    }
                                    $tbl.='
                                    <tr>
                                        <td>'.$mat_desc.'</td>
                                        '.$other_td.'
                                        <td>'.$mat_unit.'</td>
                                        <td>'.$qty.'</td>
                                        <td>'.$up.'</td>
                                        <td>'.$tot.'</td>
                                        <td>'.$remarks.'</td>
                                        <td></td>
                                        <td>'.$app_btn.'</td>
                                    </tr>';$other_td='';
                                    $total_qty=$total_qty+$tot;
                                    $compTot=$compTot+$tot;
                                    }
                                    // ECHO $compTot.'comp tot<br>';
                                    if($grp_disc!=0 || $grp_disc!=NULL){
                                        // $disc_val= 100-$grp_disc;
                                        $disc_val=$grp_disc*0.01;
                                        $disc_grp= $disc_val*$compTot;
                                        // Echo $disc_grp;
                                        $disc_tot_grp=$disc_tot_grp+$disc_grp;
                                    }
                                    
                                    // echo $total_qty;
                                    $compTot=0;
                                }
                            }
                            $total_td=$td_span-5;
                            $grp_no=mysqli_num_rows($q_grp_run);
                            $ave_disc=$total_disc/$grp_no;  // AVERAGE DISCOUNT
                            //DISCOUNT AMOUNT
                            //TOTAL AMOUNT AFTER DISCOUNT
                            $discounted_amt=$total_qty-$disc_tot_grp;
                            //5% VAT
                            $vat = $discounted_amt*0.05;
                            //TOTAL AMOUNT WITH VAT
                            $totWithVat=$discounted_amt+$vat;
                            //decimal places
                            $compTot=decPlace($compTot);
                            $ave_disc=decPlace($ave_disc);
                            $disc_tot_grp=decPlace($disc_tot_grp);
                            $discounted_amt=decPlace($discounted_amt);
                            $vat=decPlace($vat);
                            $totWithVat=decPlace($totWithVat);
                            $tbl.='
                            <tr>
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT</span>
                                </td>
                                <td>'.$allTotal.'</td>
                                <td></td>
                                <td class="font-weight-bold">'.$total_qty.'</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right" >AVERAGE DISCOUNT</span>
                                </td>
                                <td>'.$ave_disc.'%</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">DISCOUNT AMOUNT</span>
                                </td>
                                <td>'.$disc_tot_grp .'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT after DISCOUNT</span>
                                </td>
                                <td>'.$discounted_amt.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">5% VAT</span>
                                </td>
                                <td>'.$vat.'</td>
                                <td></td>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold text-danger">
                                    <span class="float-right">TOTAL AMOUNT WITH VAT</span>
                                </td>
                                <td>'.$totWithVat.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>';
                            
                        }
                        $allTotal=0;
                        $qd="SELECT Quote_Detail_Approval from quote_detail WHERE Quote_Id=$quote_id AND Quote_Detail_Approval=1 AND Quote_Detail_Status=1 ";
                        $qd_run=mysqli_query($connection,$qd);
                        if(mysqli_num_rows($qd_run)>0){
                            $button_html='<button id="appComp" class="btn btn-info btn-sm mt-3 float-right">Approved Computation <i class="fa fa-file-text ml-1"></i></button>';
                        }
                        else{
                            $button_html='';
                        }
                    }
                    else{ }
                    $tbl.='
                        </tbody>
                    </table>
                <input type="hidden" name="prj_name" id="prj_name" value="'.$prj_name.'">
                <input type="hidden" name="post_id" id="post_id" value="'.$post_id.'"> 
                <input type="hidden" name="post_name" id="post_name" value="'.$post_name.'">
                <!-- <input type="hidden" name="com"> -->
                <input type="hidden" name="" id="post_id" value="'.$post_id.'"> 
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <span class="font-weight-bold">Message:</span><br>
                        '.$message.'
                    </div>
                    <div class="col-6">
                        <span class="font-weight-bold">Terms & Conditions:</span><br>
                        '.$tc.'
                    </div>
                </div>
                '.$button_html.'<input type="hidden" name="q_id" id="q_id" value="'.$quote_id.'">
                ';
            }
        }
        echo $tbl;
    }
    else{
        echo 'Error Loading Quote Details';
    }
}
// company quote view only approve materials
if(isset($_POST['quote_id_app'])){   
    $quote_id=$_POST['quote_id_app'];
    $q_detail="SELECT * FROM quote 
                LEFT JOIN company on company.Comp_Id=quote.Comp_Id 
                WHERE Quote_Id='$quote_id' AND Quote_Status=1 LIMIT 1";
    $q_detail_run=mysqli_query($connection,$q_detail);
    $tbl=''; $post_name='';
    if(mysqli_num_rows($q_detail_run)>0){
        $row_q=mysqli_fetch_assoc($q_detail_run);
        $post_id=$row_q['Post_Id'];
        $comp_id=$row_q['Comp_Id'];
        $message=$row_q['Quote_Message'];
        $tc=$row_q['Quote_T&C'];
        $comp_name=$row_q['Comp_Name'];
        $q_date_submitted=$row_q['Quote_Submitted'];
        $q_date_submitted=date("M d, Y",strtotime($q_date_submitted));
        $q_post="SELECT * FROM post as p
                LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
                WHERE p.Post_Id='$post_id' AND p.Post_Status!=0";
        $q_post_run=mysqli_query($connection,$q_post);
        if(mysqli_num_rows($q_post_run)>0 ){
            while($row_p=mysqli_fetch_assoc($q_post_run)){
                $post_name=$row_p['Post_Name'];
                $prj_name=$row_p['Prj_Name'];
                $prj_code=$row_p['Prj_Code'];
                $post_desc=$row_p['Post_Desc'];
                $post_date=$row_p['Post_Date'];
                $post_date=date("M d, Y",strtotime($post_date)); $tbl='';
                $post_type=$row_p['Post_Type'];$disc_tot_grp=0;
                //post details table
                if($post_type=='manpower'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th>Department</th>
                        <th >Description</th>
                        <th>Remarks</th>
                        <th >Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='subcontractor'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='<th class="d-none">Department</th>
                        <th >Service Desc</th>
                        <th >Unit</th>
                        <th>Remarks</th>
                        <th >Total Area No.</th>
                        <th>PRICE/Unit</th>
                        <th>Total</th>
                        <th class="d-none">Id</th>';
                }
                elseif($post_type=='material'){
                    $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                }
                $tbl.='
                <div class="form-row mb-1">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary ">Subject:</h5>
                            </div>
                            <div class="6">
                                Quotation from '.$comp_name.'
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="6">
                                <h5 class="mr-4 font-weight-bold text-primary">Date Submitted:</h5>
                            </div>
                            <div class="6">
                                '.$q_date_submitted.'
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-1">Project:</label><br>
                    </div>
                    <div class="col-11">
                        <span>'.$prj_code.' '.$prj_name.'</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-1">
                        <label class="font-weight-bold mr-2 pr-3">Description:</label><br>
                    </div>
                    <div class="col-11">
                        <span >'.$post_desc.'</span>
                    </div>
                </div>
                <div class="table-responsive" id="tbl">
                    <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">';
                    if($post_type=='manpower'||$post_type=='subcontractor'){
                    $tbl.='
                        <thead> '.$th_html.'</thead>
                        <tbody>';
                        if(mysqli_num_rows($q_details_run)>0){$i=0; $tot_price=0;
                            while($row_d=mysqli_fetch_assoc($q_details_run)){ 
                                $mp_post_id=$row_d['MP_Post_Id'];
                                if($post_type=='subcontractor'){
                                    $td1=$row_d['MP_Post_Desc'];//description
                                    $td2=$row_d['MP_Post_Unit'];//unit
                                    $td3=$row_d['MP_Post_Qty'];//area
                                    $td4=$row_d['MP_Post_Id'];
                                    $unit=$td2;
                                }
                                elseif($post_type=='manpower'){
                                    $dept_id=$row_d['Dept_Id'];//department
                                    $td2=$row_d['MP_Post_Desc'];//desc    
                                    $td3=$row_d['MP_Post_Qty'];//qty person need
                                    $td4=$row_d['MP_Post_Id'];
                                    $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                                    $dept_q_run=mysqli_query($connection,$dept_q);
                                    $row_dept=mysqli_fetch_assoc($dept_q_run);
                                    $td1=$row_dept['Dept_Name'];
                                    $unit='hour';
                                }
                                $q_disc="SELECT * FROM quote_detail WHERE MP_Post_Id='$mp_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $remarks=$row_discount['Quote_Remarks'];
                                $price=$row_discount['Quote_Detail_Value'];
                                $tot_rate=$tot_rate+$price;
                                $total=$td3*$price;
                                $allTotal=$allTotal+$total;
                                $tbl.='
                                <tr>
                                    <td>'.$td1.'</td>
                                    <td>'.$td2.'</td>
                                    <td>'.$remarks.'</td>
                                    <td>'.$td3.'</td>
                                    <td>'.$price.'</td>
                                    <td>'.$total.'</td>
                                    <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$td4.'"></td>
                                </tr>';
                            }
                            $tbl.='
                            <tr>
                                <td></td>
                                <td></td>    
                                <td></td>    
                                <td>TOTAL</td>    
                                <td>'.$tot_rate.'</td> 
                                <td>'.$allTotal.'</td>   
                            </tr>';
                        }
                    }
                    elseif($post_type=='material'){
                        // header check 
                        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id'";
                        $q_header_run=mysqli_query($connection,$q_header); $mat_details='';
                        $row_h=mysqli_fetch_assoc($q_header_run);
                        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
                        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
                        $other_th=''; $td_span=7; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
                        if($t_capacity>0){
                            $other_th .='<th>Capacity</th>'; $td_span++; $c_cap=1;
                        }
                        if( $t_esp>0){
                            $other_th .='<th>ESP(pa)</th>'; $td_span++;$c_esp=1;
                        }
                        if($t_prefBrand>0){
                            $other_th .='<th>Preffered Brand</th>'; $td_span++;$c_pb=1;
                        }
                        if($t_location>0){
                            $other_th .='<th>Location</th>'; $td_span++;$c_loc=1;
                        }
                        $tbl.='
                        <input type="hidden" id="grpSpan" value="'.$td_span.'">
                        <thead>
                            <th>Description</th>
                            '.$other_th.'                
                            <th>Unit</th>
                            <th>QTY</th>
                            <th class=" ">U/P</th>
                            <th>Total</th> 
                            <th class=" ">Remarks</th>
                            <th class=" ">Discount/Grp</th>
                            <th>Status</th>
                        </thead>';
                        //SEARCH GROUPS by post id
                        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
                        $q_grp_run=mysqli_query($connection,$q_grp); $visb=''; $total_qty=0;$allTotal=0;$total_disc=0;$compTot=0;
                        if(mysqli_num_rows($q_grp_run)>0){
                            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                                $grp_id=$row_g['MP_Grp_Id'];
                                $grp_name=$row_g['MP_Grp_Name'];
                                $grp_location=$row_g['MP_Grp_Location'];
                                // display group discount
                                $q_grp_disc="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id' LIMIT 1";
                                $q_grp_disc_run=mysqli_query($connection,$q_grp_disc);
                                $row_disc=mysqli_fetch_assoc($q_grp_disc_run);
                                $mat_id=$row_disc['Mat_Id'];//material name
                                $mat_post_id=$row_disc['Mat_Post_Id'];   

                                $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $grp_disc=$row_discount['Quote_Detail_Disc'];

                                if($grp_disc==null){$grp_disc=0;}
                                $total_disc=$total_disc+$grp_disc;
                                if($grp_location==''){
                                    $visb='d-none';
                                }//group 
                                $tbl.='<input type="hidden" id="grp_ids'.$grp_id.'" value="'.$grp_id.'">
                                <tr>
                                    <td colspan="'.$td_span.'" class="font-weight-bold grpColspan">
                                        <div class="form-row">
                                            <div class="col-8">
                                                '.$grp_name.' 
                                            </div>
                                            <div class="col-4">
                                                <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>'.$grp_disc.'%</td>
                                </tr>';
                                // post details by group ///////////
                                $q_post="SELECT * FROM material_post as mp
                                        LEFT JOIN quote_detail as qd on qd.Mat_Post_Id=mp.Mat_Post_Id
                                        WHERE mp.Post_Id='$post_id' AND mp.MP_Grp_Id='$grp_id' AND qd.Quote_Detail_Approval=1 AND qd.Quote_Id='$quote_id'";
                                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                                if(mysqli_num_rows($q_post_details)>0){
                                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                                        // header check
                                    $mat_id=$row_p['Mat_Id'];//material name
                                    $mat_post_id=$row_p['Mat_Post_Id'];    
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
                                    $qty=$row_p['Mat_Post_Qty'];//qty
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
                                    $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$quote_id' LIMIT 1";
                                    $q_disc_run=mysqli_query($connection,$q_disc);
                                    $row_qd=mysqli_fetch_assoc($q_disc_run);
                                    $up= $row_qd['Quote_Detail_Value'];
                                    $tot= $up*$qty;
                                    decPlace($tot);
                                    $remarks=$row_qd['Quote_Remarks'];
                                    $allTotal=$allTotal+$qty;
                                    //qd status
                                    $qd_approval=$row_qd['Quote_Detail_Approval'];
                                    // display button for status
                                    if($qd_approval==0){//reject
                                        $app_btn='<button class="btn btn-sm btn-danger mb-1 float-right disabled">Rejected</button>';
                                    }
                                    elseif($qd_approval==1){//approve
                                        $app_btn='<button class="btn btn-sm btn-success mb-1 float-right disabled">Approved</button>';
                                    }
                                    else{ //pending
                                        $app_btn='<button class="btn btn-sm btn-warning mb-1 float-right disabled">Pending</button>';
                                    }
                                    $tbl.='
                                    <tr>
                                        <td>'.$mat_desc.'</td>
                                        '.$other_td.'
                                        <td>'.$mat_unit.'</td>
                                        <td>'.$qty.'</td>
                                        <td>'.$up.'</td>
                                        <td>'.$tot.'</td>
                                        <td>'.$remarks.'</td>
                                        <td></td>
                                        <td>'.$app_btn.'</td>
                                    </tr>';$other_td='';
                                    $total_qty=$total_qty+$tot;
                                    $compTot=$compTot+$tot;
                                    }
                                    // ECHO $compTot.'comp tot<br>';
                                    if($grp_disc!=0 || $grp_disc!=NULL){
                                        // $disc_val= 100-$grp_disc;
                                        $disc_val=$grp_disc*0.01;
                                        $disc_grp= $disc_val*$compTot;
                                        // Echo $disc_grp;
                                        $disc_tot_grp=$disc_tot_grp+$disc_grp;
                                    }
                                    
                                    // echo $total_qty;
                                    $compTot=0;
                                }
                            }
                            $total_td=$td_span-5;
                            $grp_no=mysqli_num_rows($q_grp_run);
                            $ave_disc=$total_disc/$grp_no;  // AVERAGE DISCOUNT
                            //DISCOUNT AMOUNT
                            //TOTAL AMOUNT AFTER DISCOUNT
                            $discounted_amt=$total_qty-$disc_tot_grp;
                            //5% VAT
                            $vat = $discounted_amt*0.05;
                            //TOTAL AMOUNT WITH VAT
                            $totWithVat=$discounted_amt+$vat;
                            //decimal places
                            $compTot=decPlace($compTot);
                            $ave_disc=decPlace($ave_disc);
                            $disc_tot_grp=decPlace($disc_tot_grp);
                            $discounted_amt=decPlace($discounted_amt);
                            $vat=decPlace($vat);
                            $totWithVat=decPlace($totWithVat);
                            $tbl.='
                            <tr>
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT</span>
                                </td>
                                <td>'.$allTotal.'</td>
                                <td></td>
                                <td class="font-weight-bold">'.$total_qty.'</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right" >AVERAGE DISCOUNT</span>
                                </td>
                                <td>'.$ave_disc.'%</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">DISCOUNT AMOUNT</span>
                                </td>
                                <td>'.$disc_tot_grp .'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT after DISCOUNT</span>
                                </td>
                                <td>'.$discounted_amt.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">5% VAT</span>
                                </td>
                                <td>'.$vat.'</td>
                                <td></td>
                                <td></td>
                                <td colspan="3"></td>
                            </tr>
                            <tr >
                                <td colspan="'.$total_td.'" class="font-weight-bold text-danger">
                                    <span class="float-right">TOTAL AMOUNT WITH VAT</span>
                                </td>
                                <td>'.$totWithVat.'</td>
                                <td></td>
                                <td ></td>
                                <td colspan="3"></td>
                            </tr>';
                            
                        }
                        $allTotal=0;
                        $qd="SELECT Quote_Detail_Approval from quote_detail WHERE Quote_Id=$quote_id AND Quote_Detail_Approval=1 AND Quote_Detail_Status=1 ";
                        $qd_run=mysqli_query($connection,$qd);
                        if(mysqli_num_rows($qd_run)>0){
                            // $onclick=onclick="this.form.target='_blank';return true'' ;
                            $button_html='
                            <form action="quoteDL.php" method="post">
                                <input type="hidden" name="qId" id="q_id" value="'.$quote_id.'">
                                <button type="submit" id="dlApp" name="quoteDLapp" class="btn btn-warning btn-sm mt-3 ml-3 float-right" >Download <i class="fa fa-download ml-1"></i></button>
                            </form>
                            <button type="button" id="appComp" class="btn btn-info btn-sm mt-3 float-right">Approved Computation <i class="fa fa-file-text ml-1"></i></button>';
                        }
                        else{
                            $button_html='';
                        }
                    }
                    else{ }
                    $tbl.='
                        </tbody>
                    </table>
                <input type="hidden" name="prj_name" id="prj_name" value="'.$prj_name.'">
                <input type="hidden" name="post_id" id="post_id" value="'.$post_id.'"> 
                <input type="hidden" name="post_name" id="post_name" value="'.$post_name.'">
                <!-- <input type="hidden" name="com"> -->
                <input type="hidden" name="" id="post_id" value="'.$post_id.'"> 
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <span class="font-weight-bold">Message:</span><br>
                        '.$message.'
                    </div>
                    <div class="col-6">
                        <span class="font-weight-bold">Terms & Conditions:</span><br>
                        '.$tc.'
                    </div>
                </div>
                '.$button_html.'<input type="hidden" name="q_id" id="q_id" value="'.$quote_id.'">
                ';
            }
        }
        echo $tbl;
    }
    else{
        echo 'Error Loading Quote Details';
    }
}
?>