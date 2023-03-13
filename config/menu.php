<?php

return [
    'dashboard' => 'default/index',
    'akuntansi' => [
        'master data' => 'crud/index?table=reports',
        'akun' => 'accounts/index',
        // 'mutasi kas' => 'crud/index?table=cash_flows',
        'jurnal' => 'journals/index',
        // 'modul' => 'crud/index?table=modules',
        'laporan' => [
            'Buku Besar' => 'reports/general',
            'Laba Rugi' => 'reports/profit',
            'Neraca' => 'reports/balance',
            // 'KKA' => 'reports/worksheet',
            // 'Keuangan' => 'reports/finance',
        ],
    ],
    'keuangan' => [
        'subjek' => 'crud/index?table=subjects',
        'group' => 'crud/index?table=groups',
        'tagihan' => 'crud/index?table=bills',
    ],
    'kontak' => 'crud/index?table=contacts',
    'pengguna'  => [
        'semua pengguna' => 'users/index',
        'roles' => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];