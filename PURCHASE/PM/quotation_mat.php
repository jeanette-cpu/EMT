<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); $p_body='';
// error_reporting(0);
if(isset($_GET['post_id']))
{
    $post_id=$_GET['post_id']; 
    $p_details="SELECT * FROM post WHERE Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details); //POST DETAILS
    $row_p=mysqli_fetch_assoc($p_details_run); 
    $post_name=$row_p['Post_Name'];
    $desc=$row_p['Post_Desc'];
    $date=$row_p['Post_Date'];
    $status=$row_p['Post_Status']; 
    $quotes="SELECT DISTINCT q.Quote_Id, q.Comp_Id FROM quote as q
            LEFT JOIN post as p on p.Post_Id=q.Post_Id
            LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id
            WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null"; //company applied
    $quote_run=mysqli_query($connection,$quotes);
    $q_no=mysqli_num_rows($quote_run);
    if($q_no>0){
        $editClass='d-none';
    }
    else{
        $editClass='';
    }
    if($quote_applied=mysqli_num_rows($quote_run)>0){
        $compare='';
        while($row_q = mysqli_fetch_assoc($quote_run)){
            $comp_arr[]=$row_q['Comp_Id'];
        }
         $comp_ids=implode(",",$comp_arr);
    }
    else{
        $compare ="d-none";
    }
    if($status=='1'){
        $stat='<button class="btn btn-sm btn-success disabled">Active</button>';$visibility="";}
    else{
        $stat='<button class="btn btn-sm btn-danger disabled">Closed</button>'; $visibility='d-none';}
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
    <div class="table-responsive " id="tbl" data-role="main" >
        <table class="table table-sm table-bordered mt-3 table-fixed"  width="100%" cellspacing="0" data-role="table" data-mode="columntoggle">
             <tbody>
             ';
 // header check 
 $q_header="SELECT sum(length(`Mat_Post_Capacity`)) as sum_capacity,Sum(length(`Mat_Post_Esp`)) as sum_esp,sum(length(Mat_Post_Location)) as sum_location,sum(length(Mat_Post_Brand)) as sum_brand FROM `material_post` WHERE Post_Id='$post_id';";
 $q_header_run=mysqli_query($connection,$q_header);  $mat_details=''; $qty_tot=0;
 $row_h=mysqli_fetch_assoc($q_header_run);
 $t_location=$row_h['sum_location']; $t_capacity=$row_h['sum_capacity'];
 $t_esp=$row_h['sum_esp']; $t_prefBrand=$row_h['sum_brand'];//preffered brand
 $other_th=''; $td_span=4; $c_cap='';$c_esp='';$c_pb='';$c_loc='';
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
         <th>Material Code</th>
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
            $grp_location=$row_g['MP_Grp_Location'];
            if($grp_location==''){
                $visb='d-none';
            }
            // $mat_details.='';
            $mat_details.='
            <tr>
                <td colspan="'.$td_span.'">
                '.$grp_name.'
                <span class="float-right '.$visb.'">(Location: '.$grp_location.')</span>
                </td>
            </tr>';
            // post details by group
            $q_post="SELECT * FROM material_post WHERE Post_Id='$post_id' AND MP_Grp_Id='$grp_id'";
            $q_post_details=mysqli_query($connection,$q_post); $other_td='';
            if(mysqli_num_rows($q_post_details)>0){
                while($row_p=mysqli_fetch_assoc($q_post_details)){
                    // header check
                    $mat_id=$row_p['Mat_Id'];//material name
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
                    $mat_code=$row_m['Mat_Code'];
                }
                else{
                    $mat_desc=$row_p['Mat_Id'];
                    $mat_unit=$row_p['Mat_Post_Unit'];
                    $mat_code=$row_p['Mat_Post_Ref_Code'];
                }
                $qty_tot=$qty_tot+$qty;
                $mat_details.='
                <tr>
                    <td>'.$mat_desc.'</td>
                    <td>'.$mat_code.'</td>
                    '.$other_td.'
                    <td>'.$mat_unit.'</td>
                    <td>'.$qty.'</td>
                </tr>';$other_td='';
                }
            }
        }
    }         
    $td_span=$td_span-1;
    $mat_details .='<tr>
                        <td colspan="'.$td_span.'" ><span class="float-right font-weight-bold mr-3"> Total Qty:</span></td>
                        <td class="font-weight-bold">'.$qty_tot.'</td>
                    </tr>
                </tbody>
            </table>
        </div> ';       
    }
}
?>
<style>
.tableFixHead { overflow: auto; height: 600px; table-layout: fixed;}

/* Just common table stuff. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
/* for td */
.tableFixHead td:nth-child(3) {
  position:sticky;
  left:0;
  z-index:1;
background-color: white;
}
</style>
<script src="table2excel.js"> </script>
<script src="https://code.jquery.com/jquery-3.1.0.js"></script>
<input type="hidden" id="post_id" value="<?php echo $post_id;?>">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h4 class="text-primary-300 ">Quotations</h4>
    </div>
    <div class="card shadow mb-4 ">
        <div class="card-header py-3">
            <div class="form-row">
                <h5 class="text-primary col-11 mb-1">Post Details <i class="fa fa-edit ml-2 " aria-hidden="true"></i></h5>
                <p class="font-weight-normal col-1"><a href="edit_mat_post.php?post_id=<?php echo $post_id?>" class="<?php echo $editClass?>">
                    <i class="fa fa-file-text mr-2" aria-hidden="true"></i>Edit Post 
                </p></a>
            </div>
            <table width="100%" cellspacing="0">
                <tr >
                    <td><span class="font-weight-bold mr-4">Post Name:</span><?php echo $post_name;?></td>
                    <td><span class="font-weight-bold mr-4">Companies Applied:</span><?php echo $quote_applied?></td>
                </tr>
                <tr>
                    <td><span class="font-weight-bold mr-3">Date Posted:</span><?php echo $date?></td>
                    <td><span class="mr-3 font-weight-bold">Quote Applications:</span>  <?php echo $q_no?></td>
                </tr>
                <tr>
                    <td ><span class="mr-3 font-weight-bold">Description:</span>  <?php echo $desc?></td>
                    <td> 
                        <span class="mr-3 font-weight-bold"> Status:</span>      <?php echo $stat?>
                        <div class="float-right <?php echo $visibility?>"> 
                            <button type="submit" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#exampleModal">Deactivate Post</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-body">
           <!-- dito yung card body post details -->
           <?php echo $p_desc.$mat_details;?>
        </div>
</div>
<!-- ///////////////////// -->
<div class="card shadow mb-4 <?php echo $compare;?>">
    <div class="card-header py-3 text-primary ">
        <h5 class="font-weight-bold">Compare Quotes</h5>
    </div>
    <div class="card-body">
        <div class="form-row px-4">
            <div class="col-4" >
                <label for="">Filter</label>
                <select name="" id="sortFilter" class="form-control filter">
                    <option value="Select Company">Sort by</option>
                    <option value="All">All</option>
                    <option value="h">Sort Hightest</option>
                    <option value="l">Sort Lowest</option>
                </select>
            </div>
            <div class="col-4" >
                <label for="">Choose Companies</label>
                <select name="companies[]" id="comps" class="form-control selectpicker filter" data-live-search="true" multiple></select>
            </div>
        </div>
        <div class="view">
            <div class="wrapper">
                <div class="table-responsive tableFixHead" id="tbl">
                    <table id="comps_td" class="table table-bordered table-sm mt-3 sticky"  width="100%" cellspacing="0" data-role="table" data-mode="columntoggle">
                    </table>
                </div>
            </div>
        </div>
        <button id="download_tbl" class="btn btn-success mt-2 float-right">Download <i class="fas fa-fw fa-arrow-down mr-1"></i></button>
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
<!-- MODAL DEACTIVATE POST -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Deactivate Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to Deactivate this post?
      </div>
      <div class="modal-footer">
        <form action="code.php" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post_id?>">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="post_deact_m"class="btn btn-primary">Confirm</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Send Approval -->
<div class="modal fade " id="sendApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fas fa-fw fa-file-text mr-1"></i>Approve Materials</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="post">
        <div class="modal-body">
            <div id="appDetails"></div>
            <div class="mt-4">
                <input class="" type="checkbox" name="" id="checkAll">
                <label for="">Select All</label>
            </div>
            <input type="hidden" name="post_id" value='<?php echo $post_id;?>'>
        </div>
        <div class="modal-footer">
            <button name="appMatQ" class="btn btn-success" type="submit">Approve</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    var post_id=$('#post_id').val();
    $.ajax({
    url:'ajax_quote.php',
    method: 'POST',
    data:{'post_opt':post_id},
    success:function(data){
        $('#comps').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
});

$(document).ready(function(){
    $(document).on('change','.filter', function(){
        var comp_ids=$('#comps').val();
        var comp_ids=("'" + comp_ids.join("','") + "'");
        var comp_filter_value=$(this).val();
        var post_id=$('#post_id').val();
        $.ajax({
            url:'ajax_quote.php',
            method: 'POST',
            data:{'comp_filter':comp_filter_value,
                    'post_id':post_id,
                    'comp_ids':comp_ids},
            success:function(data){
                    $('#comps_td').html(data).change();
                }
            });
        });
    var $th = $('.tableFixHead').find('thead th')
    $('.tableFixHead').on('scroll', function() {
    $th.css('transform', 'translateY('+ this.scrollTop +'px)');
    });    
});
$(document).ready(function () {
    $(document).on('click','.quoteView', function(){
        var quote_id = $(this).prevAll('input').val();
        // var comp_id=$('#comp_id').val();
        $.ajax({
                url:'../COMPANY/post_ajax.php',
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
    $("#download_tbl").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#comps_td"));
    });
});
$(document).ready(function(){
  $(".sticky-header").floatThead({top:50});
});
$(document).ready(function(){ //button on approve all
    $(document).on('click','#appBtn', function(){
         var mat_post_id=[];
        //get all company
        $('.comp_ids').each(function(){
             comp_id=$(this).val();
             mat_pid= $(this).nextAll('input').val();
             mat_post_id.push([comp_id,mat_pid]);
        });
        // console.log(mat_post_id);
        $.ajax({
            url:'ajax_quote.php',
            method: 'POST',
            data:{'mat_post_id':mat_post_id},
                success:function(data){
                $('#appDetails').html(data).change();
                $('#sendApp').modal('show');
                // console.log(data);
            }
        });
    });
});
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
// Fix table head
function tableFixHead (e) {
    const el = e.target,
          sT = el.scrollTop;
    el.querySelectorAll("thead th").forEach(th => 
      th.style.transform = `translateY(${sT}px)`
    );
}
document.querySelectorAll(".tableFixHead").forEach(el => 
    el.addEventListener("scroll", tableFixHead)
);
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>