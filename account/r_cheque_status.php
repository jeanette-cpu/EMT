<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-file-text" aria-hidden="true"></i> Cheque Status
            </h5>
        </div>
        <div class="card-body">
                <div class="form-row">
                    <div class="col-4">
                        <label for="">Bank</label>
                        <select name="acc_id" id="bank_opt" class="form-control"></select>
                    </div>
                    <div class="col-2 ">
                        <label for="">Company Name</label>
                        <input type="text" id="comp_name" name="comp_name" maxlength="100" class="form-control" require>
                    </div>
                    <div class="col-2 d-none">
                        <label for="">Cheque Number</label>
                        <input type="number" id="chq_no" class="form-control">
                    </div>
                    <div class="col-2">
                        <label for="">Code</label>
                        <input type="text" id="code" class="form-control" require>
                    </div>
                    <div class="col-2">
                        <label for="" class="invisible">K</label><br>
                        <button class="btn btn-success" id="search" type="submit">Search</button>
                    </div>
                </div>
        </div>
    </div>
    <div id="chqBody">
        
    </div>
</div>
<script>
$(document).ready(function(){
    var bank_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'bank_opt': bank_opt},
        success:function(data){
            $(document).find('#bank_opt').html(data).change();
        }
    });
});
$(document).ready(function(){
    $('#search').click(function(){
        event.preventDefault();
        var chqStatus="";
        var acc_id= $('#bank_opt').val();
        var chq_no= $('#chq_no').val();
        var comp_name=$('#comp_name').val();
        var code= $('#code').val();
        $.ajax({
            url:'accQuery.php',
            method: 'POST',
            data:{'chqStatus': chqStatus,   
                'acc_id1': acc_id,
                'comp_name': comp_name,
                'chq_no': chq_no,
                'code': code
            },
            success:function(data){
                $('#chqBody').html(data);
            }
        });
    });
}); 
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>