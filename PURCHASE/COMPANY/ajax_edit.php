<?php
include('../../security.php');
error_reporting(0);
function decPlace($num){
    if($num!=null){
        if ( strpos( $num, "." ) !== false ) {
            $num=number_format($num, 2);
        }
    }
    return $num;
}
if(isset($_POST['quote_id_edit'])){  
    $q_id=$_POST['quote_id_edit']; 
    $q_search="SELECT * FROM quote where Quote_Id='$q_id'";
    $q_search_run=mysqli_query($connection,$q_search);
    $tbl='';
    if(mysqli_num_rows($q_search_run)>0){
        $row=mysqli_fetch_assoc($q_search_run);
        $post_id=$row['Post_Id'];
        $message=$row['Quote_Message'];
        $tc=$row['Quote_T&C'];
        
        // $post_id=$_POST['post_id'];
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
                    <input type="hidden" name="quote_id" value="'.$q_id.'">
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
                                // search for quote detail
                                $q_qd="SELECT * FROM quote_detail WHERE Quote_Id='$q_id' AND MP_Post_Id='$td4' AND Quote_Detail_Status=1";
                                $q_qd_run=mysqli_query($connection,$q_qd);
                                if(mysqli_num_rows($q_qd_run)>0){
                                    while($row_detail=mysqli_fetch_assoc($q_qd_run)){
                                        $rate=$row_detail['Quote_Detail_Value'];
                                        $remarks=$row_detail['Quote_Remarks'];
                                        $qd_id=$row_detail['Quote_Detail_Id'];
                                    }
                                }
                                else{
                                    $rate='';$remarks='';  $qd_id='';
                                }
                                $tbl.='
                                <tr>
                                    <td>'.$td1.'</td>
                                    <td>'.$td2.'</td>
                                    <td>'.$td3.'</td>
                                    <td class=" ">
                                        <div class="form-row">
                                            <div class="col-8">
                                                <input type="number" name="e_unitP[]" class="form-control" value="'.$rate.'" required>
                                                <input type="hidden" name="qd_detail_id[]" value="'.$qd_id.'">
                                            </div>
                                            <div class="col-4">
                                                '.'/'.$unit.'
                                            </div>
                                        </div>
                                    </td>
                                    <td><input type="text" name="remarks[]" class="form-control" value="'.$remarks.'"></td>
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
                        $other_th=''; $td_span=6; $c_cap='';$c_esp='';$c_pb='';$c_loc='';$disc_tot_grp=null; $total_disc=null;
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
                            <th >Total</th> 
                            <th class=" ">Remarks</th>
                            <th class=" ">Discount/Grp</th>
                        </thead>';
                        //SEARCH GROUPS by post id
                        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
                        $q_grp_run=mysqli_query($connection,$q_grp); $visb=''; $total_qty=0;$allTotal=0; $compTot=null;
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

                                $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$q_id' AND Quote_Detail_Status=1";
                                $q_disc_run=mysqli_query($connection,$q_disc);
                                $row_discount=mysqli_fetch_assoc($q_disc_run);
                                $grp_disc=$row_discount['Quote_Detail_Disc'];

                                if($grp_disc==null){$grp_disc=0;}
                                $total_disc=$total_disc+$grp_disc;
                                if($grp_location==''){
                                    $visb='d-none';
                                }
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
                                                <input type="hidden" name="" value="">
                                                <input type="number" name="grp_disc[]" id="grpDisc'.$grp_id.'" step=".01" class="grpDisc form-control form-control-sm" value="'.$grp_disc.'">
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
                                    $q_disc="SELECT * FROM quote_detail WHERE Mat_Post_Id='$mat_post_id' AND Quote_Id='$q_id' LIMIT 1";
                                    $q_disc_run=mysqli_query($connection,$q_disc);
                                    $row_qd=mysqli_fetch_assoc($q_disc_run);
                                    $qd_id=$row_qd['Quote_Detail_Id'];
                                    $up= $row_qd['Quote_Detail_Value'];
                                    $tot= $up*$qty;
                                    decPlace($tot);
                                    $remarks=$row_qd['Quote_Remarks'];
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
                                                    <input type="number" id="unitP'.$mat_post_id.'" name="e_unitP[]" class="form-control rate form-control-sm " step=".01" required value="'.$up.'">
                                                    <input type="hidden" name="qd_detail_id[]" value="'.$qd_id.'">
                                                </div>
                                                <div class="col-5">
                                                    '.$mat_unit.'
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" "><input type="text" id="tot'.$mat_post_id.'" class="no-border form-control-sm form-control" value="'.$tot.'"  ></td>
                                        <td class=" "><input type="text" name="remarks[]" class="form-control form-control-sm" value="'.$remarks.'"></td>
                                        <td class=" " colspan="2"></td>
                                        <td class="d-none"><input type="text" name="post_desc_id[]" value="'.$mat_post_id.'"></td>
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

                                    $allTotal=$allTotal+$total_qty;
                                    // $total_qty=0;
                                }
                            }
                            $total_td=$td_span-3;
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
                                <td class=" ">
                                    <input type="text" id="grpTotal" class="no-border form-control-sm form-control" value="'.$total_qty.'">
                                </td>
                                <td class=" " colspan="2"></td>
                            </tr>
                            <tr class=" ">
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right" >AVERAGE DISCOUNT</span>
                                </td>
                                <td><input id="aveDisc" value="'.$ave_disc.'%" type="text" class="no-border form-control form-control-sm"  ></td>
                                <td></td>
                                <td class=" "></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class=" ">
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">DISCOUNT AMOUNT</span>
                                </td>
                                <td><input id="discAmt" value="'.$disc_tot_grp.'" type="text" class="no-border form-control form-control-sm"  ></td>
                                <td></td>
                                <td class=" "></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class=" ">
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">TOTAL AMOUNT after DISCOUNT</span>
                                </td>
                                <td><input id="totAfterDisc" value="'.$discounted_amt.'" type="text" class="no-border form-control form-control-sm"  ></td>
                                <td></td>
                                <td class=" "></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class=" ">
                                <td colspan="'.$total_td.'" class="font-weight-bold">
                                    <span class="float-right">5% VAT</span>
                                </td>
                                <td><input id="vatVal" type="text" value="'.$vat.'" class="no-border form-control form-control-sm"  ></td>
                                <td></td>
                                <td class=" "></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr class=" ">
                                <td colspan="'.$total_td.'" class="font-weight-bold text-danger">
                                    <span class="float-right">TOTAL AMOUNT WITH VAT</span>
                                </td>
                                <td><input id="amtWithVat" value="'.$totWithVat.'" type="text" class="no-border form-control form-control-sm"  ></td>
                                <td></td>
                                <td class=" "></td>
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
                    <div class="col-5">
                        <label for="">Message:</label><br>
                        <textarea name="message" id="" class="form-control" cols="30" rows="3" >'.$message.'</textarea>
                    </div>
                    <div class="col-7">
                        <label for="">Terms & Condition:</label>
                        <textarea name="tc" id="" class="form-control" cols="30" rows="3">'.$tc.'</textarea>
                    </div>
                </div>';
            }
        }
    echo $tbl;
    }
}
?>