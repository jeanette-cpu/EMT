<?php
include('../../security.php');
include('../../includes/header.php'); 
include('../../includes/purchase_navbar.php'); 
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary"></h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-6">
                    <label for="" class="font-weight-bold text-danger">Item Description:</label>
                    <select name="item_name" id="item_opt" class="selectpicker form-control" data-live-search="true">
                    </select>
                </div>
                <div class="col-4">
                    <label for="" class="invisible"> 1</label><br>
                    <button class="btn btn-success">Search</button>
                </div>
            </div>
            <div class="form-row">
                <h5 class="mt-3 font-weight-bold">Records:</h5>
                <div class="col-12">
                    <div class="table-responsive">
                        <div  id="record_tbl">
                        </div>
                    </div>
                </div>
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
<script>
$(document).ready(function () {
    var item_opt='';
    $.ajax({
    url:'ajax_record.php',
    method: 'POST',
    data:{'item_opt':item_opt},
    success:function(data){
        $(document).find('#item_opt').html(data).change();
        $('.selectpicker').selectpicker('refresh');
        }
    });
}); 
$(document).ready(function () {
    $(document).on('change','#item_opt', function(){
        var item_opt=$(this).val();
        // alert(item_opt);
        $.ajax({
        url:'ajax_record.php',
        method: 'POST',
        data:{'item_opt_change':item_opt},
        success:function(data){
            $('#record_tbl').html(data).change();
            $(document).find('#dataTable').DataTable({
                    pageLength: 10,
                    "searching": true
                });
            }
        });
        $('.selectpicker').selectpicker('refresh');
    });
}); 
$(document).ready(function () {
    $(document).on('click','.quoteView', function(){
        var quote_id = $(this).prevAll('input').val();
        $.ajax({
                // url:'../COMPANY/post_ajax.php',
                url:'../../PURCHASE/COMPANY/post_ajax.php',
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
$(document).ready( function () {
    
});
    
</script>
<?php
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>