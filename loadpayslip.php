<?php

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->loadHtml(file_get_contents("print_payslip.php"));

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('codexworld', array('Attachment'=>0));

?>
