<?php

$notif_fields = [
    'sent_notif' => [
        'label' => 'Kirim Notifikasi',
        'type'  => 'options:Ya|Tidak'
    ],
    'notification_to' => [
        'label' => 'Kirim Notifikasi Ke',
        'type'  => 'options:Semua|Subjek|Kontak'
    ],
    'notification_date' => [
        'label' => 'Tanggal Kirim (Kosongkan jika ingin langsung di kirim)',
        'type'  => 'date'
    ]
];

return array_merge($fields, $notif_fields);