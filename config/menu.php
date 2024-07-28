<?php

return [
    'dashboard' => 'default/index',
    'akuntansi' => [
        'master data' => 'crud/index?table=reports',
        'akun' => 'accounts/index',
        // 'mutasi kas' => 'crud/index?table=cash_flows',
        'jurnal' => 'journals/index',
        // 'modul' => 'crud/index?table=modules',
        'merchant' => 'crud/index?table=merchants',
        'laporan' => [
            'Buku Besar' => 'reports/general',
            'Laba Rugi' => 'reports/profit',
            'Neraca' => 'reports/balance',
            // 'KKA' => 'reports/worksheet',
            // 'Keuangan' => 'reports/finance',
        ],
    ],
    'keuangan' => [
        'group' => 'crud/index?table=groups',
        'subjek' => 'crud/index?table=subjects',
        'tagihan' => 'crud/index?table=bills',
        'transaksi' => 'crud/index?table=transactions',
        'laporan' => [
            'rangkuman' => 'reports/summary',
            'rekapitulasi' => 'reports/recaps',
            'total' => 'reports/total',
            'logs' => 'crud/index?table=logs',
        ]
    ],
    'kontak' => 'crud/index?table=contacts',
    'pengguna'  => [
        'semua pengguna' => 'users/index',
        'roles' => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];