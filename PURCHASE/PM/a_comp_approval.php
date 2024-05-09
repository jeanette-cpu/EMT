<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
// error_reporting(0);
?>
<style>
.modal-dialog,
.modal-content {
    /* 80% of window height */
    height: 95%;
}
.modal-body {
    /* 100% = dialog height, 120px = header + footer */
    max-height: calc(100% - 120px);
    overflow-y: scroll;
}
.popover {
  z-index: 1070;
}
.grp{
    word-break:break-all;
}
</style>
<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Companies for Approval</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <label for="">Company Type</label>
                    <select name="comp_type" id="comp_type" class="form-control selectpicker mb-4" data-live-search="true" >
                        <option value="select" disabled>Select Company Type</option>
                        <option value="all">All</option>
                        <option value="supplier">Supplier/OEM/Trading</option>
                        <option value="manpower">Manpower/Subcontractor</option>
                    </select>
                </div>
                <div class="col-3">
                    <label>Category</label>
                    <select name="[]" id="cat_opt" class="form-control selectpicker mb-4" data-live-search="true">
                        <option value="any">Select Category</option>
                    </select>
                </div>
                <div class="col-3 d-none">
                    <label>Department</label>
                    <select name="dept[]" id="dept_opt1" class="form-control selectpicker mb-4" data-live-search="true" multiple>
                        <option value="any">Select Department</option>
                    </select>
                </div>
            </div>
            <div class="row">
              <div class="col-6">
              Toggle column: <a id="contact" href="#" >Contact Details</a> - 
                              <a id="ps" href="#">Product/Service Details</a> -
                              <a id="att" href="#">Signitures & Stamp</a>
              </div>
            </div>
        <div id="dtbl">
        </div>
        <div class="d-flex float-right m-3" align="right">
          <button type="button" class="btn btn-success btn-sm" id="download_tbl"><i class="fa fa-download mr-2" aria-hidden="true"></i>Download</button>
        </div>
    </div>
    
</div>
<!-- Modal -->
<div class="modal fade appM" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <form action="code.php" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Approve Company?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <input type="hidden" name="user_id" id="userid">
          <label for="">Assign</label>
          <div class="row">
            <div class="col-6">
              <label for="">Group</label>
              <select name="group_id[]" id="cat_opt1" class="form-control selectpicker mb-4 " data-live-search="true" multiple>
                  <option value="">Select Category</option>
              </select>
            </div>
            <div class="col-6">
              <label for="">Department</label>
              <select name="dept_id[]" id="dept_opt" class="form-control selectpicker mb-4 " data-live-search="true" multiple>
                  <option value="">Select Department</option>
              </select>
            </div>
          </div>
          <div id="viewCat"> </div>
      </div>
      <input type="hidden" name="comp_id" id="comp_id">
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="approve" class="btn btn-success">Approve</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="ajax_purchase.php" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remove Company?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="comp_id" id="com_id">
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="remove" class="btn btn-primary">Remove</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- PDF Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remove Company?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" name="comp_id" id="com_id">
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="remove" class="btn btn-primary">Remove</button>
      </div>
    </form>
    </div>
  </div>
</div>
<div class="modal fade" id="viewPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="ff">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Comp Profile Modal -->
<div class="modal fade" id="compProf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <form action="" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary" id="exampleModalLabel"><i class="fas fa-fw fa-file-text mr-1"></i>Company Profile</h5>
      </div>
      <div class="modal-body" id="comp_profile"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="download"><i class="fa fa-download mr-2" aria-hidden="true"></i>Download</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
  </div>
</div> 

<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">              
      <div class="modal-body">
      	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <img src="" class="imagepreview" style="width: 100%;" >
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    //approve
    $(document).on('click', '.approveBtn', function() {
      $tr = $(this).closest('tr');
      var data = $tr.children("td").map(function(){
          return $(this).text();
      }).get();
      $('#comp_id').val(data[0]);
        var user_id =$(this).next().val();
        var location='a_comp_approval';
        $.ajax({
            url:'ajax_purchase.php',
            method: 'POST',
            data:{'grp':user_id,
                  'location':location},
            success:function(data){
                $('#viewCat').html(data);
                $('.selectpicker').selectpicker('refresh');
            }
        });
        $.ajax({
            url:'ajax_purchase.php',
            method: 'POST',
            data:{'cat_opt1':user_id
            },
            success:function(data){
                $('#cat_opt1').html(data).change();
                $('.selectpicker').selectpicker('refresh');
                // console.log(data);
            }
        }); 
        $("#userid").val(user_id);
        $('#approveModal').modal('show');
    });
    //delete
    $(document).on('click', '.delBtn', function() {
        $('#delModal').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
        $('#com_id').val(data[0]);
    });
});
$(document).ready(function(){
    $.ajax({
        url:'../../PMS/P_ADMIN/ajax_dept.php',
        method: 'POST',
        data:{},
        success:function(data){
           
            $('#dept_opt').html(data).change();
            $('#dept_opt').selectpicker('refresh');
        }
    });
    var i='';
    $.ajax({
        url:'ajax_purchase.php',
        method: 'POST',
        data:{'cat_opt':i
        },
        success:function(data){
            $('#cat_opt').html(data).change();
            $('#cat_opt').selectpicker('refresh');
        }
    });
});
$(document).ready(function(){
  $('select').on('change', function () {
        var comp_type=$('#comp_type').val(); // manpower / supplier
        var dept = $('#dept_opt').val();
        var dept_id=("'" + dept.join("','") + "'");
        var dept_l=(dept.length);
        var category_id=$('#cat_opt').val();
        var cat_sql1=''; var cat_sql2='';
        var dept_sql1=''; var dept_sql='';
        ctype="";
        if(comp_type !='all'){
          if(comp_type=='manpower'){
            ctype="AND comp.Comp_Id IN (SELECT Comp_Id FROM company WHERE Comp_Type='subcon' or Comp_Type='laborSupply' or Comp_Type='agency')";
          }
          else if(comp_type=='supplier'){
            ctype="AND comp.Comp_Id in (SELECT Comp_Id FROM company WHERE Comp_Type='oem' or Comp_Type='trading' or Comp_Type='distributor')";
          }
        }
        if(category_id!='all'){
          var cat_sql1='LEFT JOIN email  on comp.User_Id = email.User_Id';
          var cat_sql2='AND email.Email_Status=1 AND email.Email_Grp_Id='+category_id;
        }
        // if(dept_l){
        //   var dept_sql ="LEFT JOIN comp_department as cd on cd.Comp_Id=comp.Comp_Id";
        //   var dept_sql1="AND cd.Comp_Dept_Status=1 AND cd.Dept_Id IN ("+dept_id+")";
        // }
        var query="SELECT * FROM company as comp "+cat_sql1+" "+dept_sql+" WHERE comp.Comp_Status=1 AND comp.Comp_Approval=2 "+ctype+" "+cat_sql2+" "+dept_sql1+" GROUP BY comp.Comp_Id";
        // console.log(query);
        $.ajax({
            url:'ajax_purchase.php',
            method: 'POST',
            data:{'query':query},
            success:function(data){
                $('#dtbl').html(data).change();
                $(document).find('#dataTable').DataTable({
                pageLength: 10,
                "searching": true,
                });
            }
        });
    });
});
$(document).on('click','.view', function(){
  //TRN
    var comp_id =$(this).next().val();
    filename='TRN'+comp_id+'.pdf';
    $.ajax({
        url:'ajax_purchase.php',
        method: 'POST',
        data:{'filename':filename},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });
});
$(document).on('click','.viewProf', function(){
  var comp_id =$(this).next().val();
    filename='cProf'+comp_id+'.pdf';
    $.ajax({
        url:'ajax_purchase.php',
        method: 'POST',
        data:{'filename':filename},
        success:function(data){
            $('#viewPdf .modal-body').html(data);
            $('#viewPdf').modal('show');
        }
    });  
});
$(document).on('click','.cname', function(){
  var comp_id = $(this).prevAll('input').val();
  $.ajax({
        url:'ajax_company.php',
        method: 'POST',
        data:{'comp_id':comp_id},
        success:function(data){
            $('#compProf .modal-body').html(data);
            $('#compProf').modal('show');
        }
    });
});
$(document).ready(function(){
    $("#download_tbl").click(function(){
        var table = new Table2Excel();
        table.export(document.querySelectorAll("#dataTable"));
    });
});
$(document).ready(function(){
  $('#contact').on('click', function (e) {
    e.preventDefault();
    if(document.getElementById("contact_th").classList.contains("d-none")){//
      $('.noCD').removeClass('d-none');
    }
    else{
      $('.noCD').addClass('d-none');
    }
  });
});
$(document).ready(function(){
  $('#ps').on('click', function (e) {
    e.preventDefault();
    if(document.getElementById("ps_th").classList.contains("d-none")){//hide
      $('.noPS').removeClass('d-none');
    }
    else{
      $('.noPS').addClass('d-none');
    }
  });
});
$(document).ready(function(){
  $('#att').on('click', function (e) {
    e.preventDefault();
    if(document.getElementById("att_th").classList.contains("d-none")){//hide
      $('.noATT').removeClass('d-none');
    }
    else{
      $('.noATT').addClass('d-none');
    }
  });
});
$(document).ready(function(){
  $(document).on('click','.pop', function(){
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
});
$(document).ready(function () {
    var arr = [];
    $(document).on('click', '.chkBoxIds', function() {
        var remove =$(this).val();
        if($(this).prop('checked')){
            arr.push("'"+$(this).val()+"'");
        }
        else{
            arr.splice( $.inArray(remove,arr) ,1 );
        }
        // console.log(arr);
        $('#chkIds1').val(arr);
    });
});
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>