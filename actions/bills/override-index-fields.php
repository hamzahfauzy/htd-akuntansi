<?php

unset($fields['subject_id']);

$fields = array_merge([
    'subject_name' => [
        'label' => 'Subjek',
        'type'  => 'text',
        'search' => false
    ]
], $fields);


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