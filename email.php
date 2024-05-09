<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
error_reporting(0);
function decPlace($num){
    if($num!=null){
        if ( strpos( $num, "." ) !== false ) {
            $num=number_format($num, 2);
        }
    return $num;
    }
    // $num=number_format($num);
}
function sendmail($to, $subject,$body,$cc=null,$bcc=null,$attachment=null){
    $name = "EMT Electromechanical Works LLC"; // NAME of your website
    // $to = "bernaljeanette28@gmail.com"; // receiver
    // $subject="Testing";
    // $body="Send Mail";
    $from ="emtnotification@gmail.com";
    $password="mtarmnkukxitkwxd";

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";
    $mail = new PHPMailer();

    //SMTP Settings
    $mail->isSMTP();
    // $mail->SMTPDebug=3;
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Host="smtp.gmail.com"; //smtp address of your email
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPAuth=true;
    $mail->Username=$from;
    $mail->Password=$password;
    if($cc){
        $count = count($cc);
        if($count>=1){
            foreach ($cc as $ccMail){
                // echo $ccMail;
                $mail->addCC($ccMail,$ccMail);
            }
        }
    }
    if($bcc){
        $bcc_count = count($bcc);
        if($bcc_count>=1){
            foreach ($bcc as $bccMail){
                // echo $ccMail;
                $mail->addBcc($bccMail,$bccMail);
            }
        }
    }
    $mail->AddEmbeddedImage('img/EMT-LOGO.png', 'emtlogo_');
    $mail->Port=587; // $mail->Port=587;465/25
    $mail->SMTPSecure="tls"; // tls or ssl
    // SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    $mail->smtpConnect([
    'ssl'=> [
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=> true
        ]
    ]);
    if($attachment){
        $file_name=$attachment['name'];
        $file_type=$attachment['type'];
        $file_tmp  = $attachment['tmp_name'];
        $mail->AddAttachment($file_tmp, $file_name);
    }
    $body.="<br>
    <p style='font-family: Calibri; '>
    <span style='color:red; font-weight:bold;'>EMT Electromechanical Works L.L.C.	</span>			<br>	
    Tel : <span style='color:blue; '>+971 4 269 2700</span>  / Fax : <span style='color:blue;'>+971 4 269 2267</span>		<br>			
    P.O.Box : 95669, Dubai, UAE 					<br>
    Deira,  Abu Hail, Royal House Building , Office No : M-15, HOR AL ANZ EAST	<br>				
    AL WUHEIDA ROAD, DEIRA, DUBAI					<br>
    <span style='color:blue;'>Website: www.emtdubai.ae / VAT TRN: 100377114200003</span>			<br>		
    <img src='cid:emtlogo_'>";
    //Email Settings
    $mail->isHTML(true);
    $mail->setFrom($from,$name);
    // $to=array($to);
    foreach($to as $em){
        $mail->addAddress($em);
    }
    // $mail->addAddress($to);
    $mail->Subject=("$subject");
    $mail->Body=$body;

    // $mail->send();
    if($mail->send()){
        return true;
    }
    else{
        // echo "Something is wrong: <br><br>".$mail->ErrorInfo;
        return false;
    }
}
// manpower/subcon post email attach
function postDetails($post_id_details){
    include('security.php');
    $post_id=$post_id_details;
    $p_details="SELECT * FROM post as p
               LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
               WHERE  p.Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    if($p_details_run){
       $row_p=mysqli_fetch_assoc($p_details_run);
       //post details
       $post_name=$row_p['Post_Name'];
       $prj_name=$row_p['Prj_Name'];
       $prj_code=$row_p['Prj_Code'];
       $post_date=$row_p['Post_Date'];
       $post_desc=$row_p['Post_Desc'];
       $post_date=date("M d, Y",strtotime($post_date));
       $post_type=$row_p['Post_Type'];
       if($post_type=='material'){
           $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1";
           $q_details_run=mysqli_query($connection,$q_details);
           $th_html='
                       <th width="55%">Material</th>
                       <th width="5%">Unit</th>
                       <th width="10%">Qty Needed</th>
                       <th width="15%">Preffered Brand</th>';
       }
       if($post_type=='manpower'){
           $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status!=0 ";
           $q_details_run=mysqli_query($connection,$q_details);
           $th_html='<th width="15%">Department</th>
           <th  width="55%">Description</th>
           <th  width="15%">Qty</th>';
       }
       if($post_type=='subcontractor'){
            $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status!=0  ";
            $q_details_run=mysqli_query($connection,$q_details);
            $th_html='
            <th width="55%">Service Desc</th>
            <th width="15%">Unit</th>
            <th width="15%">Total Area No.</th>';
       }
       $p_desc='
           <div class="form-row mb-3">
               <div class="col-9">
                   <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
               </div>
               <div class="col-3">'.$post_date.'</div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Project:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$prj_code.' '.$prj_name.'</span>
               </div>
           </div>
           <div class="form-row">
               <div class="col-2">
                   <label class="font-weight-bold mr-1">Description:</label><br>
               </div>
               <div class="col-10">
                   <span>'.$post_desc.'</span>
               </div>
           </div>
           <div class="table-responsive" id="tbl">
               <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0" border="1">
                   <thead> '.$th_html.' </thead>
                   <tbody>';
       if(mysqli_num_rows($q_details_run)>0){
           while($row_d=mysqli_fetch_assoc($q_details_run)){
               if($post_type=='subcontractor' ){
                   $td1=$row_d['MP_Post_Desc'];//description
                   $td2=$row_d['MP_Post_Unit'];//unit
                   $td3=$row_d['MP_Post_Qty'];//area
                   $td4=$row_d['MP_Post_Id'];
                   $unit=$td2;
               }
               if($post_type=='manpower' ){
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
               if($post_type=='material'){
                   $mat_id=$row_d['Mat_Id'];//material name
                   $td2=$row_d['Mat_Post_Qty'];//qty
                   $td3=$row_d['Mat_Post_Brand'];//preffered brand
                   $td4=$row_d['Mat_Post_Id'];//
   
                   $mat_q="SELECT * FROM material WHERE Mat_Status=1 AND Mat_Id='$mat_id'";
                   $mat_q_run=mysqli_query($connection,$mat_q);
                   if(mysqli_num_rows($mat_q_run)>0 ){
                    $row_m=mysqli_fetch_assoc($mat_q_run);
                    $mat_unit=$row_m['Mat_Unit'];
                    $td1=$row_m['Mat_Desc'];
                   }
                   else{
                       $td1=$row_d['Mat_Id'];;
                       $mat_unit=$row_d['Mat_Post_Unit'];
                   }
               }
               if($post_type=='material'){
                   $p_desc.='
                   <tr>
                       <td>'.$td1.'</td>
                       <td>'.$mat_unit.'</td>
                       <td>'.$td2.'</td>
                       <td>'.$td3.'</td>
                   </tr> ';
               }
               else{
                   $p_desc.='
                   <tr>
                       <td>'.$td1.'</td>
                       <td>'.$td2.'</td>
                       <td>'.$td3.'</td>
                   </tr> '; 
               }
       }
       $p_desc.='
                   </tbody>
               </table>
           </div> ';
       }
       return $p_desc;
    }
}
// material post details email attach
function postMatDetails($post_id_mdetails){
    include('security.php');
    $post_id=$post_id_mdetails; $mat_details='';
    $p_details="SELECT * FROM post as p
        LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
        WHERE  p.Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    if($p_details_run){ 
        $row_p=mysqli_fetch_assoc($p_details_run);
        //post details
        $post_name=$row_p['Post_Name'];
        $prj_name=$row_p['Prj_Name'];
        $prj_code=$row_p['Prj_Code'];
        $post_date=$row_p['Post_Date'];
        $post_desc=$row_p['Post_Desc'];
        $post_date=date("M d, Y",strtotime($post_date));
        $post_type=$row_p['Post_Type'];
        $p_desc='
        <div class="form-row mb-3">
            <div class="col-9">
                <h5 class="m-0 font-weight-bold text-primary">'.$post_name.'</h5>
            </div>
            <div class="col-3">'.$post_date.'</div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Project:</label><br>
            </div>
            <div class="col-10">
                <span>'.$prj_code.' '.$prj_name.'</span>
            </div>
        </div>
        <div class="form-row">
            <div class="col-2">
                <label class="font-weight-bold mr-1">Description:</label><br>
            </div>
            <div class="col-10">
                <span>'.$post_desc.'</span>
            </div>
        </div>
        <div class="table-responsive" id="tbl" data-role="main" class="ui-content">
            <table class="table table-sm table-bordered mt-3 dtable ui-responsive"  width="100%" cellspacing="0" data-role="table" data-mode="columntoggle" border="1">
                    <tbody>
                    ';
        // header check 
        $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id';";
        $q_header_run=mysqli_query($connection,$q_header); 
        $row_h=mysqli_fetch_assoc($q_header_run);
        $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
        $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
        $other_th=''; $td_span=3; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
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
        $mat_details.='
            <thead>
                <th>Description</th>
                '.$other_th.'                
                <th>Unit</th>
                <th>QTY</th>
            </thead>';
        //SEARCH GROUPS by post id
        $q_grp="SELECT * FROM material_post_group WHERE Post_Id='$post_id' AND MP_Grp_Status=1";  
        $q_grp_run=mysqli_query($connection,$q_grp); $visb='';
        if(mysqli_num_rows($q_grp_run)>0){
            while($row_g=mysqli_fetch_assoc($q_grp_run)){
                $grp_id=$row_g['MP_Grp_Id'];
                $grp_name=$row_g['MP_Grp_Name'];
                // $grp_location=$row_g['MP_Grp_Location'];
                // if($grp_location==''){
                //     $visb='d-none';
                // }
                // $mat_details.=''; <-- // <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                $mat_details.='
                <tr>
                    <td colspan="'.$td_span.'">
                    '.$grp_name.'
                </td>
                </tr>';
                // post details by group
                $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
                $q_post_details=mysqli_query($connection,$q_post); $other_td='';
                if(mysqli_num_rows($q_post_details)>0){
                    while($row_p=mysqli_fetch_assoc($q_post_details)){
                        // header check
                        $mat_id=$row_p['Mat_Id'];//material name
                    ///////
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
                    $mat_details.='
                    <tr>
                        <td>'.$mat_desc.'</td>
                        '.$other_td.'
                        <td>'.$mat_unit.'</td>
                        <td>'.$qty.'</td>
                    </tr>';$other_td='';
                    }
                }
            }
        }          
        $mat_details .='</tbody>
                </table>
            </div> ';       
    }
    //END POST DETAILS
    $post_details=$p_desc.$mat_details;
    return $post_details;
}
//quote detail attachment
function quoteDetails($q_id){
    include('security.php');
    $quote_id=$q_id;
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
                        <th>Total</th>';
                }
                elseif($post_type=='subcontractor'){
                    $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                    $q_details_run=mysqli_query($connection,$q_details);
                    $th_html='
                        <th >Service Desc</th>
                        <th >Unit</th>
                        <th>Remarks</th>
                        <th >Total Area No.</th>
                        <th>PRICE/Unit</th>
                        <th>Total</th>';
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
                    <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0" border="1">';
                    if($post_type=='manpower'||$post_type=='subcontractor'){
                    $tbl.='
                        <thead> '.$th_html.'</thead>
                        <tbody>';
                        if(mysqli_num_rows($q_details_run)>0){$i=0; $tot_price=0; $tot_rate=0; $allTotal=0;
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
                                // if($grp_location==''){
                                //     $visb='d-none';
                                // }//group  <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                                $tbl.='<input type="hidden" id="grp_ids'.$grp_id.'" value="'.$grp_id.'">
                                <tr>
                                    <td colspan="'.$td_span.'" class="font-weight-bold grpColspan">
                                        <div class="form-row">
                                            <div class="col-8">
                                                '.$grp_name.' 
                                            </div>
                                            <div class="col-4">
                                                
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
        return $tbl;
    }
    else{
        // echo 'Error Loading Quote Details';
    }
}
?>