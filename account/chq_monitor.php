<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); 
$currentMname = date('F');
//make cards 
//november prepared cheques = ?
//skipped no. chqs 
?>
<script src="table2excel.js"> </script>
<style>
    table {
        max-height: 300px; /* Set a max height for the container to enable scrolling */
        overflow-y: scroll; /* Enable vertical scrolling */
    }
thead th {
    background-color: #f5f5f5;
    position: sticky;
    top: 0;
}
</style>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Cheque Monitoring</h1>
    <div class="row d-none">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-info text-uppercase mb-1">Balance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2 id="bal_id"></h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-success text-uppercase mb-1">Total Cheques</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2></h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-s font-weight-bold text-warning text-uppercase mb-1">missing cheques</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php  echo'<h2  id="mis_chq"></h2>'; ?>
                        </div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-fw fa-address-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 mb-4">
        <div class="col-5 text-center">
            <h4 class="mb-2 h4 "><span class="text-primary" id="accName">ADCB</span> CHECK PAYMENTS</h4>
        </div>
        <div class="col-2">
            <h5><label for="" class="float-right mt-2 ">Account:</label></h5>
        </div>
        <div class="col-2 float-left">
            <select name="" id="acc_opt" class="form-control"></select>
        </div>
    </div>
    <div id="chq_html"></div>
    <div align="right">
        <button name="" id="btnExcel" class="btn btn-success mt-2">
            <i class="fa fa-download" aria-hidden="true"></i>
            Download
        </button>  
    </div>
</div>
<script>
$(document).ready(function(){
    var acc_opt="";
    $.ajax({
        url:'options.php',
        method: 'POST',
        data:{'acc_opt': acc_opt},
        success:function(data){
            $(document).find('#acc_opt').html(data).change();
        }
    });
});
$(document).ready(function(){
    $('select').on('change', function () {
        var acc_id=$('#acc_opt').val();
        $.ajax({
            url:'accQuery.php',
            method: 'POST',
            data:{'bankCode':acc_id
            },
            success:function(data){
                $('#accName').html(data).change();
            }
        });
        $.ajax({
            url:'chq_q.php',
            method: 'POST',
            data:{'acc_id':acc_id
            },
            success:function(data){
                $('#chq_html').html(data).change();
            }
        });
    });
});
$(document).ready(function () {
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#chqTable'));
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>