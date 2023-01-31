<?php

return [
    'reports'    => [
        'ref_id' => [
            'label' => 'Referensi',
            'type'  => 'options-obj:reports,id,name'
        ],
        'name' => [
            'label' => 'Judul',
            'type'  => 'text'
        ],
        'description' => [
            'label' => 'Deskripsi',
            'type'  => 'textarea'
        ],
        'is_active' => [
            'label' => 'Aktif',
            'type'  => 'options:YA|TIDAK'
        ],
        'is_open' => [
            'label' => 'Status',
            'type'  => 'options:BUKA|TUTUP'
        ]
    ],
    'accounts' => [
        'parent_id' => [
            'label' => 'Parent',
            'type'  => 'options-obj:accounts,id,name'
        ],
        'code'      => [
            'label' => 'Kode Akun',
            'type'  => 'text'
        ],
        'name'      => [
            'label' => 'Nama Akun',
            'type'  => 'text'
        ],
        'balance_position' => [
            'label' => 'Pos Saldo',
            'type'  => 'options:Header|Cr|Db'
        ],
        'report_position' => [
            'label' => 'Pos Laporan',
            'type'  => 'options:NRC|LR'
        ],
        'balance_amount' => [
            'label' => 'Saldo',
            'type'  => 'number'
        ],
        'budget_amount' => [
            'label' => 'Anggaran',
            'type'  => 'number'
        ],
    ],
    'account_settings' => [
        'account_source'  => [
            'label' => 'Akun Asal',
            'type'  => 'text'
        ],
        'account_target'  => [
            'label' => 'Akun Tujuan',
            'type'  => 'text'
        ],
        'cash_source' => [
            'label' => 'Asal Kas',
            'type'  => 'options:Mutasi Kas|Saldo Akun|Laba Rugi'
        ],
    ],
    'cash_flows' => [
        'account_id' => [
            'label' => 'Akun',
            'type'  => 'options-obj:accounts,id,name'
        ],
        'cash_type' => [
            'label' => 'Tipe Transaksi',
            'type'  => 'options:Kas Masuk|Kas Keluar'
        ],
        'amount' => [
            'label' => 'Jumlah',
            'type'  => 'number'
        ],
        'description' => [
            'label' => 'Deskripsi',
            'type'  => 'textarea'
        ],
        'notes' => [
            'label' => 'Catatan',
            'type'  => 'text'
        ],
        'date' => [
            'label' => 'Tanggal',
            'type'  => 'date'
        ],
    ]
];