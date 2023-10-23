<?php

use Spipu\Html2Pdf\Html2Pdf;

$conn = conn();
$db   = new Database($conn);

$subject = $db->single('subjects', ['id' => $_GET['id']]);

$db->query = "SELECT bills.*, merchants.name as merchant_name FROM bills JOIN merchants ON merchants.id = bills.merchant_id  WHERE bills.subject_id = $subject->id AND bills.status = 'BELUM LUNAS'";
$subject->bills   = $db->exec('all');

$html = load_templates('subjects/bills-download', [
    'subject' => $subject
], true);

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($html);
$html2pdf->output('bill-download-'.$subject->code.'.pdf');
die;