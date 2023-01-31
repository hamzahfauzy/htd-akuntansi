<?php

return [
    'dashboard' => 'default/index',
    'master data' => 'crud/index?table=reports',
    'akun' => [
        'master akun' => 'accounts/index',
        'pengaturan akun' => 'crud/index?table=account_settings',
    ],
    'mutasi kas' => 'crud/index?table=cash_flows',
    'laporan' => [
        'General Ledger' => 'reports/general',
        'KKA' => 'reports/worksheet',
        'Keuangan' => 'reports/finance',
    ],
    'pengguna'  => [
        'semua pengguna' => 'users/index',
        'roles' => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];