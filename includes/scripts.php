<!-- user.php -->
<script>
$(document).ready(function () {
    $('.editbtn').on('click', function() {
        $('#EditUserModal').modal('show');
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            console.log(data);

            $('#update_id').val(data[0]);
            $('#username').val(data[1]);
            $('#usertype').val(data[2]);
            $('#email').val(data[3]);
    });
});
</script>
<!-- payslip.php -->
<script>
  $(document).ready(function(){  
      $('.view_data').click(function(){  
           var payslip_id = $(this).attr("id");  
           $.ajax({  
                url:"select.php",  
                method:"post",  
                data:{payslip_id:payslip_id},  
                success:function(data){  
                     $('#payslip_detail').html(data);  
                     $('#dataModal').modal("show");  
                }  
           });  
      });  
  });
</script>
<!-- timesheet.php -->
<script>
    $('.view_timesheet').click(function(){  
        var td = $(this).closest('td');

        var var_emp_id = td.find('#emp_id_m').attr("value"),
            var_emp_no = td.find('#emp_no_m').attr("value"),
            var_month = td.find('#month_m').attr("value"),
            var_year = td.find('#year_m').attr("value");

        var link = "timesheet_modal.php";
        
        $.ajax({  
            type: 'POST',
            url: link,  
            data:{
                'emp_id': var_emp_id,
                'emp_no': var_emp_no,
                'month': var_month,
                'year': var_year,
            },  
            success: function(data){
                $('#payslip_detail').html(data);  
                $('#dataModal').modal("show");  
            }
        });  
    });
</script>

<script>
// u_manage_users.php
$(document).ready(function () {
    $(document).on('click', '.editUsersbtn', function() {
        $('#EditModal').modal('show');
        $tr = $(this).closest('tr');
        var usertype = "";
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
        $('#user_update_id').val(data[0]);
        $('#user_username').val(data[1]);
        $('#user_usertype').val(data[3]);
        if (data[3] == 'foreman')
        {
            $('#eDept').removeClass('d-none');
            $('#dept_optt').val(data[5]);
        }
        else{
            $('#eDept').addClass('d-none');
        }
    });
});
// u_asgn_to_prj.php 
$(document).ready(function(){
    $(document).on('change','#usertype', function() {    
        var usertype = $(this).val();
        $.ajax({
            url: 'u_ajax.php',
            method:'POST',
            data: {usertype:usertype},
            success:function(data){
                var ids= document.querySelectorAll('*[id^="row"] #username');
                var i;
                for (i = 0; i < ids.length; i++) {
                    $(document).find(ids[i]).html(data).change();
                    }
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });
});

$(document).ready(function () {
    $(document).on('click', '.editAsgnBtn', function() {
        $('#editAsgn').modal('show');
        $tr = $(this).closest('tr');
        var usertype = "";
        var data = $tr.children("td").map(function(){
            return $(this).text();
        }).get();
       
       $('#asgn_id').val(data[0]);
       $('#username_edit').val(data[1]);
       $('#emp_edit').val(data[2]);
       $('#prj_edit').val(data[3]);
       $('.selectpicker').selectpicker('refresh');
    });
});
// ADD DE
$(document).ready(function() {
    $('#employee').change(function(e) { 
        if(e.originalEvent)
        {$(document).find('#SubConTbl').remove();
        $(document).find('#addSbRow').remove();
        }
    });
    $('#manpowerOpt').change(function(e) { 
        if(e.originalEvent)
        {$(document).find('#SubConTbl').remove();
        $(document).find('#addSbRow').remove();
        }
    });
});
$(document).ready(function() {
    $('#sbOpt').change(function(e) { 
        if(e.originalEvent)
        {
            $(document).find('#ManpowerTbl').remove();
            $(document).find('#materialTbl').remove();
            $(document).find('#addBtn').remove();
            $(document).find('#addMpRow').remove();
        }
    });
});
// EDIT DE
$(document).ready(function() {
    $('#manpower').change(function(e) { 
        if(e.originalEvent)
        {$(document).find('#SubConTbl1').remove();
        $(document).find('#addSbRow1').remove();
        }
    });
    $('#manpowerOpt1').change(function(e) { 
        if(e.originalEvent)
        {$(document).find('#SubConTbl1').remove();
        $(document).find('#addSbRow1').remove();
        }
    });
});
$(document).ready(function() {
    $('#sbOpt1').change(function(e) { 
        if(e.originalEvent)
        {
            $(document).find('#manpowerTbl').remove();
            $(document).find('#ManpowerTbl1').remove();
            $(document).find('#addBtnM').remove();
            $(document).find('#addMpRow1').remove();
        }
    });
});
$(document).ready(function(){   // move to next td paste
    $('input').bind('paste', function (e) {
        var $start = $(this);
        var source
        //check for access to clipboard from window or event
        if (window.clipboardData !== undefined) {
            source = window.clipboardData
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData("Text");
        if (data.length > 0) {
            if (data.indexOf("\t") > -1) {
                var columns = data.split("\n");
                $.each(columns, function () {
                    var values = this.split("\t");
                    $.each(values, function () {
                        $start.val(this);
                        if ($start.closest('td').next('td').find('input,textarea')[0] != undefined || $start.closest('td').next('td').find('textarea')[0] != undefined) {
                        $start = $start.closest('td').next('td').find('input,textarea');
                        }
                        else
                        {
                        return false;  
                        }
                    });
                    $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
                });
                e.preventDefault();
            }
        }
    });
});
</script>
<!-- FOR search dropdown list -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-bar-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

 <!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
 <script src="js/demo/datatables-demo.js"></script>

<!-- messeges -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
<!-- datepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<link rel="stylesheet" href="css/style.css">

<!-- Display Messege -->
<?php
    if(isset($_SESSION['status']) && $_SESSION['status'] !='')
    {
        ?>
            <script> 
                $(document).ready(function(){
                    var myhtml = document.createElement("div");
                    myhtml.innerHTML = "<?php echo $_SESSION['message'];?>";
                    swal({
                            title: "<?php echo $_SESSION['status'];?>",
                            content: myhtml,
                            icon: "<?php echo $_SESSION['status_code'];?>",
                            button: "Ok",
                        });
                        // myhtml ='';
                });
            </script>
        <?php
        unset($_SESSION['status']);
        // unset($_SESSION['message']);
    }
?>

<!-- FOR EDIT USER -->
<!-- Download Table as Excel -->
<!-- <script src="vendor/jquery/table2excel.js"> </script> -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- For Search select -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" /> -->