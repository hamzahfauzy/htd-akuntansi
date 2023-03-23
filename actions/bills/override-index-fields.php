<?php

$fields['notification_to'] = [
    'label' => 'Target Notifikasi',
    'type'  => 'options:Semua|Subjek|Kontak'
];

$fields['remaining_payment'] = [
    'label' => 'Sisa Pembayaran',
    'type'  => 'number'
];

$fields['status'] = [
    'label' => 'Status',
    'type'  => 'text'
];

$fields['created_at']['label'] = 'Tanggal';
$fields['created_at']['type'] = 'text';

return $fields;