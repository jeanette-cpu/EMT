<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/company_navbar.php');
error_reporting(0);
// search table
// filter table by status
$username=$_SESSION['USERNAME'];
$query ="SELECT * FROM users WHERE USERNAME='$username'"; $query_run = mysqli_query($connection, $query);
$row=mysqli_fetch_assoc($query_run);
$user_id=$row['USER_ID'];
$q_company="SELECT * FROM company WHERE User_Id='$user_id' LIMIT 1";
$q_company_run=mysqli_query($connection,$q_company);
$row_c=mysqli_fetch_assoc($q_company_run);
if($q_company_run){
    $comp_id=$row_c['Comp_Id'];
    $comp_type=$row_c['Comp_Type'];
    $comp_name=$row_c['Comp_Name'];
    // $comp_type='laborSupply';
    if($comp_type=='subcon' || $comp_type=='laborSupply' || $comp_type=='laborSupply'){
        $class="postViewManpower"; $comp_btn_visb="d-none";
    }
    elseif($comp_type=='trading' || $comp_type=='distributor' || $comp_type=='oem'){
        $class="postView"; $comp_btn_visb="";
    }
    else{}
    $q_quotations="SELECT * FROM quote as qt
    LEFT JOIN post as p on p.Post_Id=qt.Post_Id
    left join project as prj  on p.Prj_Id=prj.Prj_Id
    WHERE qt.Quote_Status=1 AND qt.Comp_Id='$comp_id' AND p.Post_Status!=0 AND prj.Prj_Status=1 
    ORDER BY qt.Quote_Id desc LIMIT 50";
    $q_quotations_run=mysqli_query($connection,$q_quotations);
}
?>
<style>
    .no-border {
    border: 0;
    box-shadow: none; /* You may want to include this as bootstrap applies these styles too */
}
.modal {
  overflow-y:auto;
}
</style>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-file-text mr-1"></i>Quotation Status</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" >
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th>Post Name</th>
                        <th>Post Status</th>
                        <th>Date Posted</th>
                        <th>Project</th>
                        <th>Date Submitted</th>
                        <th class="d-none">Overall Status</th>
                        <th>Items Approved</th>         
                        <th>Action</th>                
                    </thead>
                    <tbody>
            <?php
                if(mysqli_num_rows($q_quotations_run)>0){
                    while($row_q=mysqli_fetch_assoc($q_quotations_run)){
                        $prj_name=$row_q['Prj_Name'];
                        $prj_code=$row_q['Prj_Code'];
                        $post_id=$row_q['Post_Id'];
                        $post_date=$row_q['Post_Date'];
                        $post_date=date("M d, Y",strtotime($post_date));
                        $post_name=$row_q['Post_Name'];
                        $post_status=$row_q['Post_Status'];
                        $q_id=$row_q['Quote_Id'];
                        $q_sub=$row_q['Quote_Submitted'];
                        $q_sub=date("M d, Y",strtotime($q_sub));
                        $quote_approval=$row_q['Quote_Approval'];
                        //get the Quote_Detail_Approval check if theres =1
                        $qd="SELECT Quote_Detail_Approval from quote_detail WHERE Quote_Id=$q_id AND Quote_Detail_Approval=1 AND Quote_Detail_Status=1 ";
                        $qd_run=mysqli_query($connection,$qd);
                        if($qd_run){
                            $no_row=mysqli_num_rows($qd_run);
                        }
                        if($quote_approval==0 || $no_row>0){
                            $btn_html='<button class="btn btn-sm btn-danger disabled">Rejected</button>';
                            $edit_btn='';
                            $del_btn_visb="d-none";
                        }
                        elseif($quote_approval==2){
                            $edit_btn='<button class="btn btn-success editQuote rounded ">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </button>';
                            $btn_html='<button class="btn btn-sm btn-warning disabled">Pending</button>';
                            $del_btn_visb="";
                        }
                        elseif($quote_approval==1 || $no_row>0){
                            $btn_html='<button class="btn btn-sm btn-success disabled">Approved</button>';
                            $edit_btn='';
                            $del_btn_visb="d-none";
                        }
                        elseif($no_row>0){
                            $del_btn_visb="d-none";
                        }
                        else{ $btn_html=''; $edit_btn='';}
                        // post status
                        if($post_status==1){
                            $ppost_status="<div class='text-success'>Active</div>";
                        }
                        elseif($post_status==2){
                            $ppost_status="<div class='text-danger'>Closed</div>";
                        }
                        else{
                            $ppost_status="Deleted";
                        }
                    ?>
                        <tr>
                            <td class="font-weight-bold">
                                <input type="hidden" name="post_id" value="<?php echo $post_id?>"><a href="#" class="text-secondary <?php echo $class?>" ><?php echo $post_name?>
                            </td>
                            <td><?php echo $ppost_status;?></td>
                            <td><?php echo $post_date?></td>
                            <td><?php echo $prj_code.' '.$prj_name?></td>
                            <td><?php echo $q_sub?></td>
                            <td class="d-none"><?php echo $btn_html?></td>
                            <td><?php echo $no_row;?></td>
                            <td class="btn-group">
                                <?php echo $edit_btn;?>
                                <input type="hidden" value="<?php echo $q_id?>">
                                <button class="btn btn-info quoteView rounded ">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                                <form action="quoteDL.php" method="post">
                                    <input type="hidden" name="qId" value="<?php echo $q_id?>">
                                    <button type="submit" name="quoteDL" class="btn btn-warning  rounded " onclick="this.form.target='_blank';return true;">
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                    </button>
                                </form>
                                <button type="submit" id="" name="delQuote" class="btn btn-danger rounded delQuote <?php echo $del_btn_visb;?>">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                                <input type="hidden" name="quoteId" value="<?php echo $q_id;?>">
                            </td>
                        </tr>
                    <?php
                    }
                }
               else{
                    echo 'No Quotations Made';
                }
                    ?>
                    </tbody>
                </table>
            </div>
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
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fas fa-fw fa-file-text mr-1"></i>Quotation Details</h5>
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
<!-- QUOTE MODAL -->
<div class="modal fade " id="quoteModalApproved" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fas fa-fw fa-file-text mr-1"></i>Quotation Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="QuoteDetailsApp"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- MODAL EDIT QUOTE -->
<div class="modal fade" id="quoteEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            <div class="replace"  id="QuoteEdit">
            </div>
         </div>
        
    <input type="hidden" name="post_type" value="<?php echo $post_type?>">
    <input type="hidden" name="comp_id" value="<?php echo $comp_id?>">
    <input type="hidden" name="qpost_id" class="post_id">
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" name="" class="btn btn-secondary <?php echo $comp_btn_visb?>" id="compute">Compute</button>
        <button type="submit" name="updateQuote" class="btn btn-success">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- QUOTE confirmation delete -->
<div class="modal fade " id="quoteDel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Delete Quote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body">
                Confirm delete quote?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary cancel">Cancel</button>
                <form action="code.php" method="post">
                    <input type="hidden" id="quoteDelId" name="quoteId" value="">
                    <button class="btn btn-primary" type="submit" name="delQuote">Confirm</button>
                </form>
            </div>
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
                success:function(data){
                    $('#postDesc').html(data).change();
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.quoteView', function(){
        var quote_id = $(this).prevAll('input').val();
        // var comp_id=$('#comp_id').val();
        $.ajax({
                url:'post_ajax.php',
                method: 'POST',
                data:{'quote_id_comp':quote_id
                },
                success:function(data){
                    $('#QuoteDetails').html(data).change();
                    $('#quoteModal').modal('show');
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.editQuote', function(){
        var quote_id = $(this).next('input').val();
        $.ajax({
                url:'ajax_edit.php',
                method: 'POST',
                data:{'quote_id_edit':quote_id
                },
                success:function(data){
                    $('#QuoteEdit').html(data).change();
                    $('#quoteEdit').modal('show');
                }
            });
    });
});
$(document).ready(function () {
    $(document).on('click','.delQuote', function(){
        var qId= $(this).nextAll('input').val();
        $('#quoteDel').modal('show');
        $('#quoteDelId').val(qId);

    });
    $(".cancel").click(function(){
            $("#quoteDel").modal('hide');
    });
});
$(document).ready(function () {
    $(document).on('click','#appComp', function(){
        $("#quoteModal").modal('hide');
        var quote_id_app=$(this).nextAll('input').val();
        $.ajax({
            url:'post_ajax.php',
            method: 'POST',
            data:{'quote_id_app':quote_id_app
            },
            success:function(data){
                $('#QuoteDetailsApp').html(data).change();
                $('#quoteModalApproved').modal('show');
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
$(document).ready(function(){
    $(document).on('click','#compute', function(){
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
        $("#grpTotal").val(grp_tot); // all group total TOTAL AMOUNT without discount
        //DISCOUNT
        var j; var discounted_amt=0; var disc_ave=0;var total_disc=0;var grp_total=0;
        var disc_ids=$('*[id^="grp_ids"]');
        for( j=0;j<disc_ids.length;j++){
            var grp_id=$(document).find(disc_ids[j]).val();
            // get total amount per grp
            // get discounted amount per grp
            var disc_val_id='#grpDisc'+grp_id;
            var disc_val = $(disc_val_id).val(); // discount entered
            if(disc_val){
            }
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
                // console.log(disc_total);
            }
            else{
                // disc_val=0;
                console.log(grp_total);
                disc_total = disc_total+parseFloat(grp_total);
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
$(document).ready(function(){
    $('#quoteEdit').on('hidden.bs.modal', function () {
        // $(document).find('.comp').addClass('d-none');
        // location.reload();
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>