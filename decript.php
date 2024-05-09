<?php  
 include('security.php');

 $string = 'c4ca4238a0b923820dcc';
 $encrypted = \Illuminate\Support\Facades\Crypt::encrypt($string);
 $decrypted_string = \Illuminate\Support\Facades\Crypt::decrypt($encrypted);
 
 var_dump($string);
 var_dump($encrypted);
 var_dump($decrypted_string);

 ?>