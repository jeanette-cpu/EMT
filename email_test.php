<?php 
include('email.php');

$to=['jeanette@emtdubai.ae','jeaitako@gmail.com'];
$subject="EMT Registration";
$body="tesing live";
$bcc=['bernaljeanette28@gmail.com'];
// $body.="<br>
// <p style='font-family: Calibri; '>
// <span style='color:red; font-weight:bold;'>EMT Electromechanical Works L.L.C.	</span>			<br>	
// Tel : <span style='color:blue; '>+971 4 269 2700</span>  / Fax : <span style='color:blue;'>+971 4 269 2267</span>		<br>			
// P.O.Box : 95669, Dubai, UAE 					<br>
// Deira,  Abu Hail, Royal House Building , Office No : M-15, HOR AL ANZ EAST	<br>				
// AL WUHEIDA ROAD, DEIRA, DUBAI					<br>
// <span style='color:blue;'>Website: www.emtdubai.ae / VAT TRN: 100377114200003</span>			<br>		

// <img src='cid:emtlogo' style='width: 40%;'>";

// $cc=[''];
sendmail($to,$subject,$body,$cc,$bcc);
// echo $body;
//subcon/manpower post details
// $post_id_details=36;
// $post_details=postDetails($post_id_details);
// echo $post_details;

// material post details
// $post_id_mdetails=53;
// $post_details=postMatDetails($post_id_mdetails);
// echo $post_details;

// quote details
// $q_id=62;
// $q_details=quoteDetails($q_id);
// echo $q_details;

/////////////


?>