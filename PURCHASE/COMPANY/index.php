<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php'); 
$username=$_SESSION['USERNAME'];
  $query_user ="SELECT * FROM users WHERE USERNAME='$username' AND USERTYPE='company' LIMIT 1";
  $query_run_user = mysqli_query($connection, $query_user);
  if(mysqli_num_rows($query_run_user)==1){
    $row=mysqli_fetch_assoc($query_run_user);
    $user_id=$row['USER_ID']; 
    //company details
    $comp="SELECT * FROM company WHERE User_Id='$user_id' AND Comp_Status=1 and Comp_Approval=1 LIMIT 1";
    $comp_run=mysqli_query($connection,$comp);
    $row_comp=mysqli_fetch_assoc($comp_run);
    $comp_type=$row_comp['Comp_Type'];
    $comp_id=$row_comp['Comp_Id']; 
    $s1_1=$row_comp['s1_1'];
    $s2_1=$row_comp['s2_1'];
    $s3_1=$row_comp['s3_1'];
    $license_exp=$row_comp['Comp_Reg_End_Date'];
    if($s1_1=="" or $s2_1=="" OR $s3_1==""){
        $message.="Please update your Signatories".'\n';
    }
    else{
        $message.="";
    }
    if($license_exp){
        $today = date("Y-m-d");
        if($today>=$license_exp){
            $message.="Please update Trade License".'\n';
        }
        else{
            $message.="";            
        }
    }
    else{
        $message.="Please update Trade License".'\n';
    }
    if(strlen($message>1)){
        echo "<script type='text/javascript'>
                alert('$message');
            </script>"; 
    }
    if($comp_type=='subcon' || $comp_type=='laborSupply' || $comp_type=='agency'){
      $post_type="manpower";
      echo "<script type='text/javascript'>
                $(document).ready(function() {
                    $('#service_card').removeClass('d-none');});
            </script>"; 
  }
    elseif($comp_type=='trading' || $comp_type=='distributor' || $comp_type=='oem'){
      $post_type="material";
      echo "<script type='text/javascript'>
          $(document).ready(function() {
                $('#product_card').removeClass('d-none');});
            </script>";
    }
    // problem
    elseif($comp_type="both"){
      $post_type="";
      echo "<script type='text/javascript'>
                $(document).ready(function() {
                $('#product_card').removeClass('d-none');
                $('#service_card').removeClass('d-none');});
            </script>"; 
      $query_post="SELECT * FROM notification as notif
                  LEFT JOIN post as p on notif.Post_Id=p.Post_Id
                  WHERE notif.user_id='$user_id' and p.Post_Status=1 and notif.Not_Type='post' and notif.Not_Status=1";
    }
    else{ }
  }
  else{
    $user_id='';
    echo "<script type='text/javascript'>
    alert ('Access Denied, Please try again.');
    window.location.href='login.php';</script>";
  }
//dashboard cards
  if($post_type=="manpower"){
      //active posts
    $query = "SELECT * FROM post as p 
            LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
            WHERE p.Post_Status=1 AND p.Post_Type!='material'";
    $query_run = mysqli_query($connection, $query);
    $row1= mysqli_num_rows($query_run);
    //quotes send by the user
    $query2 = "SELECT * FROM quote WHERE Comp_Id='$comp_id' AND Quote_Status=1";
    $query_run2 = mysqli_query($connection, $query2);
    $row2= mysqli_num_rows($query_run2);
    //registered service
    $query4 ="SELECT * FROM service WHERE Comp_Id='$comp_id' AND Serve_Status=1";
    $query_run4 = mysqli_query($connection, $query4);
    $row4= mysqli_num_rows($query_run4);
}
elseif($post_type=="material"){
    $query = "SELECT * FROM post as p 
            LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
            WHERE p.Post_Status=1 AND p.Post_Type='material'";
    $query_run = mysqli_query($connection, $query);
    $row1= mysqli_num_rows($query_run);
    // $q_post_ids="SELECT * FROM post WHERE `Post_Status`=1 AND ";
     //quotes send by the user
    $query2="SELECT * FROM quote as qt
    LEFT JOIN post as p on p.Post_Id=qt.Post_Id
    left join project as prj  on p.Prj_Id=prj.Prj_Id
    WHERE qt.Quote_Status=1 AND qt.Comp_Id='$comp_id' AND p.Post_Status=1 AND prj.Prj_Status=1 
    ORDER BY qt.Quote_Id desc";
     $query_run2 = mysqli_query($connection, $query2);
     $row2= mysqli_num_rows($query_run2);
     //registered materials
     $query3 = "SELECT * FROM product WHERE Comp_Id='$comp_id' AND Prod_Status=1";
     $query_run3 = mysqli_query($connection, $query3);
     $row3= mysqli_num_rows($query_run3);
}
elseif($post_type=""){
}
?>
<input type="hidden" id="comp_id" value="<?php echo $comp_id;?>">
<div class="container-fluid">
    <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  </div>
    <div class="row">
        <!-- Active Posts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-success text-uppercase mb-1">Active Posts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$row1.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-clipboard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quotations Sent -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-warning text-uppercase mb-1"> Quotations Sent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php  echo'<h2>'.$row2.'</h2>'; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-paper-plane fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Registered Products-->
        <div id="product_card" class="col-xl-3 col-md-6 mb-4 d-none">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info text-uppercase mb-1">Registered Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$row3.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-archive fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Registered Service-->
        <div id="service_card" class="col-xl-3 col-md-6 mb-4 d-none">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info text-uppercase mb-1">Registered Service</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2>'.$row4.'</h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-handshake-o fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Recent Posts</h5>
        </div>
        <?php 
        // echo $comp_type;
        if($comp_type=='both'){
            $query_post="SELECT * FROM post as p 
                        LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
                        WHERE p.Post_Status=1 
                        ORDER BY p.Post_Date DESC LIMIT 5";
          }
        elseif($post_type=='manpower' && $comp_type!='subcon'){ // manpower
            $query_post="SELECT * FROM post as p 
                    LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
                    WHERE p.Post_Status=1 AND p.Post_Type='manpower' 
                    ORDER BY p.Post_Date DESC LIMIT 5";
            $class="postViewManpower";

        }
        elseif($post_type=='manpower' && $comp_type='subcon'){ //subcon
            $query_post="SELECT * FROM post as p 
                    LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
                    WHERE p.Post_Status=1 AND  p.Post_Type='subcontractor'  
                    ORDER BY p.Post_Date DESC LIMIT 5";
            $class="postViewManpower";

        }
        elseif($post_type=='material'){
            $query_post="SELECT * FROM post as p 
                    LEFT JOIN project as prj on prj.Prj_Id= p.Prj_Id
                    WHERE p.Post_Type='material' and p.Post_Status=1 
                    ORDER BY p.Post_Date DESC LIMIT 5";
            $class="postView";
        }
        else{
        // $query_post='ERR';
        }
        // echo $query_post;
        ?>
        <div class="card-body">
            <table class="table table-sm" id="dataTable" width="100%" cellspacing="0">
            <?php   $ctn=0;
                $query_post_run=mysqli_query($connection,$query_post);
                if(mysqli_num_rows($query_post_run)>0){
                    while($row_p=mysqli_fetch_assoc($query_post_run)){
                        $post_id=$row_p['Post_Id'];
                        $prj_name=$row_p['Prj_Name'];
                        $prj_code=$row_p['Prj_Code'];
                        $post_date=$row_p['Post_Date'];
                        $post_date=date("M d, Y",strtotime($post_date));
                        $post_name=$row_p['Post_Name']; $ctn++;
                        // check if there is a quote already sent 
                        $quote_chk="SELECT * FROM quote WHERE Post_Id='$post_id' AND Comp_Id='$comp_id' AND Quote_Status=1";
                        $quote_chk_run=mysqli_query($connection,$quote_chk);
                        if(mysqli_num_rows($quote_chk_run)>0){
                            $btn_html='
                            <button class="btn btn-sm btn-light ">Already Sent</button>';
                        }
                        else{
                            $btn_html='<button class="btn btn-sm btn-info quote">Send Quote</button>
                            <input type="hidden" name="" id="post_id" value="'.$post_id.'">';
                        }
                        echo '<tr><td>
                        <input type="hidden" name="post_id" id="p'.$ctn.'" value="'.$post_id.'"><a href="#" class="text-secondary '.$class.'" >'.$prj_code.' '.$prj_name.' | '.$post_name.' | view more ... </a><div class="float-right mr-4"><span class="mr-4">'.$post_date.'</span>'.$btn_html.'</div>
                    </td></tr>';
                    }
                }
            ?>
            </table>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Quote Status</h5>
        </div>
        <?php
            $query_quote="SELECT * FROM quote as qt
            LEFT JOIN post as p on p.Post_Id=qt.Post_Id
            left join project as prj  on p.Prj_Id=prj.Prj_Id
            WHERE qt.Quote_Status=1 AND qt.Comp_Id='$comp_id' AND p.Post_Status=1 AND prj.Prj_Status=1 
            ORDER BY qt.Quote_Id desc LIMIT 5";
            $query_quote_run=mysqli_query($connection,$query_quote);
        ?>
        <div class="card-body">
                <tr>
                    <!-- <h6 class="font-weight-bold text-dark"><td>Most Recent<span class="float-right mr-4">Status</td></span></h6> -->
                </tr>
            <table class="table table-sm">
                <?php
                    // project name limit 20 chars,..| post_name limit (30 chars) | Date posted 
                    $query_quote_run=mysqli_query($connection,$query_quote);
                    if(mysqli_num_rows($query_quote_run)>0){
                        while($row_q=mysqli_fetch_assoc($query_quote_run)){
                            $quote_id=$row_q['Quote_Id'];
                            $post_id=$row_q['Post_Id'];
                            $prj_name=$row_q['Prj_Name'];
                            $prj_code=$row_q['Prj_Code'];
                            $post_date=$row_q['Post_Date'];
                            $post_date=date("M d, Y",strtotime($post_date));
                            $post_name=$row_q['Post_Name'];
                            $quote_approval=$row_q['Quote_Approval'];
                            if($quote_approval==0){
                                $btn_html='<button class="btn btn-sm btn-danger disabled">Rejected</button>';
                            }
                            elseif($quote_approval==2){
                                $btn_html='<button class="btn btn-sm btn-warning disabled">Pending</button>';

                            }
                            elseif($quote_approval==1){
                                $btn_html='<button class="btn btn-sm btn-success disabled">Approved</button>';

                            }
                            else{
                                $btn_html='';
                            }
                            echo '<tr><td>
                            <input type="hidden" value="'.$quote_id.'">
                            <a href="#" class="text-secondary quoteView">'.$prj_code.' '.$prj_name.' | '.$post_name.' | view more ... </a><div class="float-right mr-4"><span class="mr-4">'.$post_date.'</span>'.$btn_html.'</div>
                        </td></tr>';
                        }
                    }
                ?>
            </table>
        </div>
    </div>
</div> 
<!-- POST MODAL -->
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-file mr-1"></i>Post Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="postDesc">
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button id="quote" class="btn btn-sm btn-info float-right"><i class="fas fa-fw fa-paper-plane fa-1x mr-1"></i>Send Quote</button> -->
      </div>
    </div>
  </div>
</div>
<!-- QUOTE MODAL -->
<div class="modal fade " id="quoteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-fw fa-file-text mr-1"></i>Quote Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="QuoteDetails"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
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
        <button type="button" name="" class="btn btn-secondary" id="compute">Compute</button>
        <button type="submit" name="sendQuote" class="btn btn-success">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
    $(document).on('click','.postView', function(){
        var post_id = $(this).prevAll('input').val();
        var comp_id=$('#comp_id').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'../PM/ajax_purchase.php',
                method: 'POST',
                data:{'mpost_id':post_id
                },
                // data:{'post_id':post_id,
                //     'comp_id':comp_id
                // },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.postViewManpower', function(){
        var post_id = $(this).prevAll('input').val();
        var comp_id=$('#comp_id').val();
        $('#postModal').modal('show');
        $.ajax({
                url:'../PM/ajax_purchase.php',
                method: 'POST',
                // data:{'mpost_id':post_id
                // },
                data:{'ppost_id':post_id,
                    'comp_id':comp_id
                // },
                },
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.quoteView', function(){
        var quote_id = $(this).prevAll('input').val();
        var comp_id=$('#comp_id').val();
        $('#quoteModal').modal('show');
        $.ajax({
                url:'post_ajax.php',
                method: 'POST',
                data:{'quote_id':quote_id
                },
                success:function(data){
                    $('#QuoteDetails').html(data).change();
                    $('#quoteModal').modal('show');
                }
            });
    });
});
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