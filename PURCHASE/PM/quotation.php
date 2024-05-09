<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); $p_body='';
if(isset($_GET['post_id']))
{
    $post_id=$_GET['post_id']; // may question mark
    $p_details="SELECT * FROM post WHERE Post_Id='$post_id' LIMIT 1";
    $p_details_run=mysqli_query($connection,$p_details);
    $row_p=mysqli_fetch_assoc($p_details_run);
    $post_name=$row_p['Post_Name'];
    $desc=$row_p['Post_Desc'];
    $post_type=$row_p['Post_Type'];
    $date=$row_p['Post_Date'];
    $status=$row_p['Post_Status']; 
    //company applied
    $quotes="SELECT DISTINCT q.Quote_Id FROM quote as q 
            LEFT JOIN post as p on p.Post_Id=q.Post_Id 
            LEFT JOIN quote_detail as qd on qd.Quote_Id=q.Quote_Id 
            WHERE q.Quote_Status=1 AND p.Post_Id='$post_id' AND Quote_Detail_Id is not null";
    $quote_run=mysqli_query($connection,$quotes);
    $q_no=mysqli_num_rows($quote_run);
    if($q_no>0){
        $editClass='d-none';
    }
    else{
        $editClass='';
    }
    if($quote_applied=mysqli_num_rows($quote_run)>0){
        while($row_q = mysqli_fetch_assoc($quote_run)){
            $comp_arr[]=$row_q['Comp_Id'];
        }
         $comp_ids=implode(",",$comp_arr);
    }
    if($status=='1'){
        $stat='<button class="btn btn-sm btn-success disabled">Active</button>';$visibility="";}
    else{
        $stat='<button class="btn btn-sm btn-danger disabled">Closed</button>'; $visibility='d-none';}
    $type= $_GET['type'];
    if($type='job_post'){
        //get the companies applied in quotation
        //manpower
        $q_detail="SELECT * FROM manpower_post WHERE MP_Post_Status=1 AND Post_Id='$post_id'";
        $q_detail_run=mysqli_query($connection,$q_detail);
        if($post_type=='manpower'){
            $post_table_details='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="20%">Department</th>
                        <th>Description</th>
                        <th>Requested Quantity</th>
                    </thead><tbody>';
            $post_table_detail2='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="20%">Department</th>
                        <th width="50%">Description</th>
                        <th width="15%">Requested Quantity</th>
                        <th width="15%">Rate</th>
                    </thead><tbody>';
            if(mysqli_num_rows($q_detail_run)>0){ $qty_total=0;
                while($row_d=mysqli_fetch_assoc($q_detail_run)){
                    $dept=$row_d['Dept_Id'];
                    $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                    $dept_q_run=mysqli_query($connection,$dept_q);
                    $row_dept=mysqli_fetch_assoc($dept_q_run);
                    $department=$row_dept['Dept_Name'];
                    $desc=$row_d['MP_Post_Desc'];
                    $qty=$row_d['MP_Post_Qty'];
                    $qty_total=$qty_total+$qty;
                    $post_table_details.='        
                    <tr>
                        <td>'.$department.'</td>
                        <td>'.$desc.'</td>
                        <td>'.$qty.'</td>
                    </tr>';
                }
                $post_table_details.='        
                <tr>
                    <td></td>
                    <td class="float-right mr-2">Total Qty:</td>
                    <td class="border-top font-weight-bold">'.$qty_total.'</td>
                </tr>';
            }
            $post_table_details.=' </tbody></table>  ';
        }
        if($post_type=='subcontractor'){
            $post_table_details='
            <table width="100%"  cellspacing="0">
                    <thead>
                        <th width="15%">Department</th>
                        <th width="45%">Description</th>
                        <th width="20%">Unit</th>
                        <th width="20%">Requested Quantity</th>
                    </thead>
                    <tbody>';
        if(mysqli_num_rows($q_detail_run)>0){ $qty_total_sub=0;
            while($row_d=mysqli_fetch_assoc($q_detail_run)){
                $dept=$row_d['Dept_Id'];
                $dept_q="SELECT * FROM department where Dept_Id='$dept' and Dept_Status=1 limit 1 ";
                $dept_q_run=mysqli_query($connection,$dept_q);
                $row_dept=mysqli_fetch_assoc($dept_q_run);
                $department=$row_dept['Dept_Name'];
                $desc=$row_d['MP_Post_Desc'];
                $qty=$row_d['MP_Post_Qty'];
                $unit=$row_d['MP_Post_Unit'];
                $post_table_details.='        
                        <tr>
                            <td>'.$department.'</td>
                            <td>'.$desc.'</td>
                            <td>'.$unit.'</td>
                            <td>'.$qty.'</td>
                        </tr>';
                $qty_total_sub=$qty_total_sub+$qty;
                $qty=0;
                }
            }
            $post_table_details.='
                    <tr>
                        <td colspan="3"><span class="float-right mr-3 ">Total Qty:<span></td>
                        <td class="border-top font-weight-bold">'.$qty_total_sub.'</td>
                    </tr>
                </tbody>
            </table>';
        }
    }
    else{
        echo 'Error Loading Details';
    }
}
?>
<script src="table2excel.js"> </script>
<input type="hidden" id="post_id" value="<?php echo $post_id;?>">
<input type="hidden" id="post_type" value="<?php echo $post_type;?>">
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h4 class="text-primary-300 ">Quotations</h4>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="form-row">
                <h5 class="text-primary col-11 mb-1">Post Details <i class="fa fa-edit ml-2 " aria-hidden="true"></i></h5>
                <p class="font-weight-normal col-1 <?php echo $editClass?>"><a href="edit_job_post.php?post_id=<?php echo $post_id?>">
                    <i class="fa fa-file-text mr-2" aria-hidden="true"></i>Edit Post 
                    <!-- <input type="submit"/> -->
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
            <?php echo $post_table_details;?>
        </div>
    </div>
    <!-- DISPLAY QUOTES -->
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
            <div class="table-responsive" id="tbl" data-role="main" class="ui-content">
                <table id="comps_td" class="table table-bordered table-sm mt-3 dtable ui-responsive"  width="100%" cellspacing="0" data-role="table" data-mode="columntoggle">
                </table>
            </div>
            <button id="download_tbl" class="btn btn-success mt-2 float-right">Download <i class="fas fa-fw fa-arrow-down mr-1"></i></button>
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
            <button type="submit" name="post_deact"class="btn btn-primary">Confirm</button>
        </form>
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
<script>
//VIEW QUOTE
$(document).ready(function () {
    $(document).on('click','.quoteView', function(){
        var quote_id = $(this).prevAll('input').val();
        // alert(quote_id);
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
    $(document).on('change','.filter', function(){
        var comp_ids=$('#comps').val();
        var comp_ids=("'" + comp_ids.join("','") + "'");
        var comp_filter_value=$(this).val();
        var post_id=$('#post_id').val();
        var post_type=$('#post_type').val();
        $.ajax({
            url:'ajax_quote.php',
            method: 'POST',
            data:{'comp_filter_mp':comp_filter_value,
                    'post_id':post_id,
                    'comp_ids':comp_ids,
                    'post_type':post_type},
            success:function(data){
                    $('#comps_td').html(data).change();
                }
            });
    });
});
$(document).ready(function(){
    var post_id=$('#post_id').val();
    $.ajax({
    url:'ajax_quote.php',
    method: 'POST',
    data:{'comp_opt':post_id},
    success:function(data){
        $('#comps').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
});
$(document).ready(function(){
    $("#download_tbl").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#comps_td"));
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>
