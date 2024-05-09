<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php');
$username=$_SESSION['USERNAME'];
$query ="SELECT * FROM users WHERE USERNAME='$username'"; $query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id=$row['USER_ID'];
$q_company="SELECT * FROM company WHERE User_Id='$user_id' LIMIT 1";
$q_company_run=mysqli_query($connection,$q_company);
$row_c=mysqli_fetch_assoc($q_company_run);
    if($q_company_run){
        $comp_type=$row_c['Comp_Type'];
        $comp_id=$row_c['Comp_Id'];
        $comp_name=$row_c['Comp_Name'];
        $s1_1=$row_c['s1_1'];
        $s2_1=$row_c['s2_1'];
        $s3_1=$row_c['s3_1'];
        if($s1_1=="" or $s2_1=="" OR $s3_1==""){
            $btn_title.="Please update your Signatories to Send ";
            $btn_disable="disabled";
        }
        else{
            $btn_title.="";
        }
        $license_exp=$row_c['Comp_Reg_End_Date']; //company license exp
        if($license_exp){
            $today = date("Y-m-d");
            if($today>=$license_exp){
                $btn_title.="Please update Trade License to Send ";
                $btn_disable="disabled";
            }
            else{
                $btn_title.="";            
            }
        }
        else{
            $btn_title.="Please update Trade License to Send ";
            $btn_disable="disabled";
        }
        if($comp_type=='subcon'  ){
            $post_type='subcontractor';
            $q_post="SELECT * FROM post as p
            LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
            WHERE p.Post_Type!='material' AND p.Post_Status=1 ORDER BY p.Post_Id DESC";
            $q_post_run=mysqli_query($connection,$q_post);
            $hclass='d-none';
        }
        elseif($comp_type=='laborSupply'|| $comp_type=='agency'){
            $post_type='manpower';
            $q_post="SELECT * FROM post as p
                    LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
                    WHERE p.Post_Type!='material' AND p.Post_Status=1 ORDER BY p.Post_Id DESC";
            $q_post_run=mysqli_query($connection,$q_post);
            $hclass='d-none';
        }
        elseif($comp_type=='trading' || $comp_type=='oem' || $comp_type=='distributor'){
            $post_type='material';
            $q_post="SELECT * FROM post as p
                    LEFT JOIN project as prj on prj.Prj_Id=p.Prj_Id
                    WHERE p.Post_Type='material' AND p.Post_Status=1 ORDER BY p.Post_Id DESC";
            $q_post_run=mysqli_query($connection,$q_post);
            $hclass='';
        }
    }
?>
<style>
    .no-border {
    border: 0;
    box-shadow: none; /* You may want to include this as bootstrap applies these styles too */
}
</style>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h4 class="text-primary-300 ">Recent Posts</h4>
    </div>
    <div class="form-row">
        <?php $c=1;
            if(mysqli_num_rows($q_post_run)>0 ){
                while($row_p=mysqli_fetch_assoc($q_post_run)){
                    $post_id=$row_p['Post_Id'];
                    $post_name=$row_p['Post_Name'];
                    $prj_name=$row_p['Prj_Name'];
                    $prj_code=$row_p['Prj_Code'];
                    $post_desc=$row_p['Post_Desc'];
                    $post_date=$row_p['Post_Date'];
                    $post_date=date("M d, Y",strtotime($post_date));
                    //post details table
                    if($post_type=='manpower' || $post_type=='subcontractor'){
                        if($post_type=='subcontractor'){
                            $th_html='<th class="d-none">Department</th>
                            <th width="55%">Service Desc</th>
                            <th width="15%">Unit</th>
                            <th width="15%">Total Area No.</th>
                            <th class="d-none">Id</th>'; 
                        }
                        else{
                            $th_html='<th width="15%">Department</th>
                            <th  width="55%">Description</th>
                            <th  width="15%">Qty</th>
                            <th class="d-none">Id</th>';
                        }
                        $q_details="SELECT * FROM manpower_post WHERE Post_Id='$post_id' AND MP_Post_Status=1 ";
                        $q_details_run=mysqli_query($connection,$q_details);
                    }
                    elseif($post_type=='material'){
                        $q_details="SELECT * FROM material_post WHERE Post_Id='$post_id' AND Mat_Post_Status=1 ";
                        $q_details_run=mysqli_query($connection,$q_details);
                    }
                    ?>
                        <div class="col-6 pb-3" >
                            <div class="card shadow">
                                <div class="card-header">
                                    <div class="form-row">
                                        <div class="col-9">
                                            <h5 class="m-0 font-weight-bold text-primary"><?php echo $post_name?></h5>
                                        </div>
                                        <div class="col-3"> <?php echo $post_date?> </div>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="font-weight-bold mr-1">Project:</label><br>
                                        </div>
                                        <div class="col-9">
                                            <span><?php echo $prj_code.' '.$prj_name;?></span>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="font-weight-bold mr-2 pr-3">Description:</label><br>
                                        </div>
                                        <div class="col-9">
                                            <span ><?php echo $post_desc?></span>
                                        </div>
                                    </div>
                                    <div class="table-responsive" id="tbl<?php echo $c;?>">
                                        <table class="table table-sm table-bordered mt-3 dtable"  width="100%" cellspacing="0">
                                        <?php
                                        if($post_type=='manpower'|| $post_type=='subcontractor'){
                                            ?>
                                            <thead> <?php echo $th_html;?> </thead>
                                            <tbody>
                                            <?php
                                            if(mysqli_num_rows($q_details_run)>0){$i=0;
                                                while($row_d=mysqli_fetch_assoc($q_details_run)){ 
                                                    if($comp_type=='subcon'){
                                                        $td1=$row_d['MP_Post_Desc'];//description
                                                        $td2=$row_d['MP_Post_Unit'];//unit
                                                        $td3=$row_d['MP_Post_Qty'];//area
                                                        $td4=$row_d['MP_Post_Id'];
                                                        $unit=$td2;
                                                    }
                                                    elseif($comp_type=='laborSupply'){}
                                                        $dept_id=$row_d['Dept_Id'];//department
                                                        $td2=$row_d['MP_Post_Desc'];//desc    
                                                        $td3=$row_d['MP_Post_Qty'];//qty person need
                                                        $td4=$row_d['MP_Post_Id'];
                                                        $dept_q="SELECT * FROM department WHERE Dept_Status=1 and Dept_Id='$dept_id'";
                                                        $dept_q_run=mysqli_query($connection,$dept_q);
                                                        $row_dept=mysqli_fetch_assoc($dept_q_run);
                                                        $td1=$row_dept['Dept_Name'];
                                                        $unit='hour';
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $td1;?></td>
                                                        <td><?php echo $td2;?></td>
                                                        <td><?php echo $td3;?></td>
                                                        <td class="d-none"><input type="text" name="post_desc_id[]" value="<?php echo $td4;?>"></td>
                                                    </tr>
                                                    <?php
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
                                            <input type="hidden" id="'.$c.'grpSpan" value="'.$td_span.'">
                                            <thead>
                                                <th>Description</th>
                                                '.$other_th.'                
                                                <th>Unit</th>
                                                <th>QTY</th>
                                                <th class="d-none comp">Total</th> 
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
                                                    $mat_details.='
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
                                                        $mat_details.='
                                                        <tr>
                                                            <td>'.$mat_desc.'</td>
                                                            '.$other_td.'
                                                            <td>'.$mat_unit.'</td>
                                                            <td>'.$qty.'</td>
                                                        </tr>';$other_td='';
                                                        $total_qty=$total_qty+$qty;
                                                        }
                                                        $allTotal=$allTotal+$total_qty;
                                                        $total_qty=0;
                                                    }
                                                }
                                                $total_td=$td_span-1;
                                                $mat_details.='
                                                <tr>
                                                    <td colspan="'.$total_td.'" class="font-weight-bold">TOTAL</td>
                                                    <td>'.$allTotal.'</td>
                                                </tr>';
                                                
                                            }
                                            echo $mat_details; $mat_details='';$allTotal=0;
                                        }
                                        else{ }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div> 
                                    <input type="hidden" name="prj_name" id="prj_name<?php echo $c?>" value="<?php echo $prj_name?>">
                                    <input type="hidden" name="post_id" id="post_id<?php echo $c?>" value="<?php echo $post_id?>"> 
                                    <input type="hidden" name="post_name" id="post_name<?php echo $c?>" value="<?php echo $post_name?>">
                                    <!-- <input type="hidden" name="com"> -->
                                    <input type="hidden" value="<?php echo $c;?>" >
                                    <button id="quote" class="btn btn-sm btn-info float-right quote"><i class="fas fa-fw fa-paper-plane fa-1x mr-1"></i>Send Quote</button>
                                    <input type="hidden" name="" id="post_id" value="<?php echo $post_id?>"> 
                                </div>
                            </div>
                        </div>
                    <?php
                    $c++;
                }
            }
        ?>
    </div>
</div>
<!-- MODAL SEND QUOTE -->
<div class="modal fade" id="sendQuote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form action="code.php" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Quote</h5>
      </div>
      <div class="modal-body">
        <div class="form-row">
            <div class="col-6">
                <span class="font-weight-bold mr-3">Subject:</span>QUOTE from <?php echo $comp_name;?><span></span>
            </div>
            <div class="col-6">
                <span class="font-weight-bold">Date of Issuance:</span>
                <span class="mr-3"> <?php date_default_timezone_set('Asia/Dubai'); echo date("F j, Y")?></span>
            </div>
        </div>
          <hr>
         <div class="table-responsive">
            <div class="replace">
            </div>
         </div>
        <div class="form-row">
            <div class="col-5">
                <label for="">Message:</label><br>
                <textarea name="message" id="" class="form-control" cols="30" rows="3"></textarea>
            </div>
            <div class="col-7">
                <label for="">Terms & Condition:</label>
                <textarea name="tc" id="" class="form-control" cols="30" rows="3"></textarea>
            </div>
        </div>
    <input type="hidden" name="post_type" value="<?php echo $post_type?>">
    <input type="hidden" name="comp_id" value="<?php echo $comp_id?>">
    <input type="hidden" name="qpost_id" class="post_id">
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" name="" class="btn btn-secondary <?php echo $hclass?>" id="compute">Compute</button>
        <button type="submit" name="sendQuote" title="<?php echo $btn_title;?>" class="btn btn-success" <?php echo $btn_disable;?>>Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>

$(document).ready(function(){
    $('.quote').click(function(){
        var post_id = $(this).next('input').val();
        $.ajax({
            url:'post_ajax.php',
            method:'POST',
            data:{'post_id':post_id},
            success:function(data){
                $('.replace').html(data).change();
                $('#sendQuote').modal('show');
            }
        });
    });
});
$(document).ready(function(){
    $('#sendQuote').on('hidden.bs.modal', function () {
        $(document).find('.comp').addClass('d-none');
    });
});
$(document).ready(function(){
    $(document).on('click','#compute', function(){
        var id='grpSpan'; // adding span to grp
        var grpSpan = $('#'+id).val() ; 
        grpSpan=parseInt(grpSpan)+1;
        $('.grpColspan').attr('colspan',grpSpan);
        $('.comp').removeClass('d-none');
        function decCheck(num){
            if(Number.isInteger(num)){   
            }else{num = num.toFixed(2);
            }return num;
        }
        var ids=$('*[id^="mat_post_id"]'); 
        var grp_tot=0; var i; var disc_total=0;
        for (i = 0; i < ids.length; i++) {
            var mp_id=  $(document).find(ids[i]).val();// mat_post_ids
            var qty_id='#qty'+mp_id;// find qty value
            var qty = $(qty_id).val();
            var unitP_id ='#unitP'+mp_id;// find unit price value
            var unitP = $(unitP_id).val();
            if(unitP){}else{unitP=0;}
            var total_all = qty*parseFloat(unitP);
            total_all = decCheck(total_all);
            var tot_id = '#tot'+mp_id;// find total input id
            $(tot_id).val(total_all);
            grp_tot = parseFloat(grp_tot)+parseFloat(total_all);
        }
        $("#grpTotal").val(grp_tot); // all group total TOTAL AMOUNT
        //DISCOUNT
        var j; var discounted_amt=0; var disc_ave=0;var total_disc=0;var grp_total=0;
        var disc_ids=$('*[id^="grp_ids"]');
        for( j=0;j<disc_ids.length;j++){
            var grp_id=$(document).find(disc_ids[j]).val();
            // get total amount per grp
            // get discounted amount per grp
            var disc_val_id='#grpDisc'+grp_id;
            var disc_val = $(disc_val_id).val(); // discount entered
            if(disc_val){}
            else{disc_val=0; }
            total_disc=total_disc+parseFloat(disc_val);
            var ids=$('*[id^="mat_post_id'+grp_id+'"]'); 
            for (i = 0; i < ids.length; i++){
                var mp_id=  $(document).find(ids[i]).val();// mat_post_ids
                var qty_id='#qty'+mp_id;// find qty value
                var qty = $(qty_id).val();
                var unitP_id ='#unitP'+mp_id;// find unit price value
                var unitP = $(unitP_id).val();
                if(unitP){}else{unitP=0;}
                var total = qty*parseFloat(unitP);
                grp_total=grp_total+total;
            }
            if(disc_val!=0){
                disc_val = 100-disc_val;
                disc_val= disc_val*0.01;
                disc_val = disc_val * grp_total;
                disc_total = disc_total+parseFloat(disc_val);
            }
            else{
                // disc_val=0;
                disc_total = disc_total+total;
            }
            grp_total=0;
        }
        discounted_amt=grp_tot - disc_total;
        discounted_amt =decCheck(discounted_amt);
        //AVERAGE DISCOUNT
        disc_ave=total_disc/disc_ids.length;
        disc_ave= decCheck(disc_ave);
        $('#aveDisc').val(disc_ave+'%');
        // DISCOUNT AMOUNT
        $('#discAmt').val(discounted_amt);
        //AMOUNT AFTER DISCOUNT
        disc_total = decCheck(disc_total);
        $('#totAfterDisc').val(disc_total);
        //5% VAT
        var compVat = disc_total*0.05;
        compVat= decCheck(compVat);
        $('#vatVal').val(compVat);
        var amtWithVat=0;
        //TOTAL AMOUNT WITH VAT
        amtWithVat=parseFloat(compVat)+parseFloat(disc_total);
        amtWithVat= decCheck(amtWithVat);
        $('#amtWithVat').val(amtWithVat);
        $("#sendQuote").val(null).trigger("change");
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>