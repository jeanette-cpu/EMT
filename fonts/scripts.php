<!-- FOR search dropdown list -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('.editbtn').on('click', function() {
        $('#EditUserModal').modal('show');
            $tr = $(this). closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            console.log(data);

            $('#update_id').val(data[0]);
            $('#username').val(data[1]);
            $('#usertype').val(data[2]);
            $('#email').val(data[3]);
            // $('#password').val(data[4]);
    });
});
</script> 
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
                swal({
                        title: "<?php echo $_SESSION['status'];?>",
                        icon: "<?php echo $_SESSION['status_code'];?>",
                        button: "Ok",
                    });
            </script>
        <?php
        unset($_SESSION['status']);
        
    }
?>
<!-- FOR EDIT USER -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
