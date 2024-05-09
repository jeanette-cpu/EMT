<?php
include('dbconfig.php');
if(isset($_POST['filename'])){
  $filename= $_POST['filename'];
  $backslash='\ ';
  $backslash= str_replace(' ', '', $backslash);
  $first='<iframe src=\'empFiles';
  $end='\' width=\'100%\' style=\'height:100%\'></iframe>';
  $file=$first.$backslash.$filename.$end;
  echo $file;
}
?>