<?php

return [
    'dashboard' => 'default/index',
    'akuntansi' => [
        'master data' => 'crud/index?table=reports',
        'akun' => [
            'master akun' => 'accounts/index',
            'pengaturan akun' => 'crud/index?table=account_settings',
        ],
        'mutasi kas' => 'crud/index?table=cash_flows',
        'modul' => 'crud/index?table=modules',
        'laporan' => [
            'General Ledger' => 'reports/general',
            'KKA' => 'reports/worksheet',
            'Keuangan' => 'reports/finance',
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