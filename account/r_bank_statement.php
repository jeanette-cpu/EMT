<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php'); 
?>
<script src="table2excel.js"> </script>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary"><i class="fa fa-file-text" aria-hidden="true"></i> Generate Bank Account Statement
            </h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <div class="form-row">
                    <div class="col-5">
                        <label for="">Account</label>
                        <select name="acc_id" id="acc_opt" class="form-control">

                        </select>
                    </div>
                    <div class="col-2">
                        <label for="">Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="">Year</label>
                        <select name="year" id="year" class="form-control">
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="" class="invisible">D</label><br>
                        <button class="btn btn-info" id="search">Generate <i class="fas fa-fw fa-cogs "></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div id="accStatementCard">
            </div>
            <div align="right">
                <button name="" id="btnExcel" class="btn btn-success mt-2">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download
                </button>  
            </div>
        </div>
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
    $('#search').click(function(){
        event.preventDefault();
        var acc_s="";
        var acc_id= $('#acc_opt').val();
        var month= $('#month').val();
        var yr= $('#year').val();
        $.ajax({
            url:'r_acc_statement.php',
            method: 'POST',
            data:{'acc_statement': acc_s,   
                'acc_id': acc_id,
                'month': month,
                'yr': yr
            },
            success:function(data){
                $('#accStatementCard').html(data);
            }
        });
    });
});   

$(document).ready(function () {
    $('#btnExcel').click(function(){
       var table = new Table2Excel();
       table.export(document.querySelectorAll('#dataTable'));
    });
});
</script>                 
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>