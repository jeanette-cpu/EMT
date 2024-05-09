
<style>
.container {
  width: 80%;
  margin: 15px auto;
}
</style>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->

<?php
include('../security.php');
include('../includes/header.php'); 
include('../includes/acc_navbar.php');
include('accQuery.php'); ?>
<div class="container">
  <div class="row">
          <div class="col-12">
              <div class="card shadow mb-5">
                  <div class="card-header py-3">
                      <h5 class="m-0 font-weight-bold text-primary">Monthly Overview</h5>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-2">
                              <label for="">Category</label>
                              <select name="" id="cat_opt1" class="form-control"></select>
                          </div>

                      </div>
                      <div id="expChart"></div>
                      <!-- <canvas id="expReport" height="80"></canvas> -->
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->
  <script src="test.js"></script>
<script>
  $(document).ready(function(){
    $('#cat_opt1').on('change', function () {
        var cat_id = $(this).val();
        // alert(cat_id);
        $.ajax({
            url:'accQuery.php',
            method: 'POST',
            data:{'monthExp':cat_id,
            },
            success:function(data){
                $('#expChart').html(data).change();
            }
        });
    });
});
$(document).ready(function(){
    var month_opt="";
    $.ajax({
    url:'options.php',
    method: 'POST',
    data:{'cat_opt': month_opt},
    success:function(data){
            $(document).find('#cat_opt').html(data).change();
            data = "<option value=''>All</option>"+data;
            $(document).find('#cat_opt1').html(data).change();
        }
    });
});
</script>
<?php
include('../includes/scripts.php');
include('../includes/footer.php');
?>
