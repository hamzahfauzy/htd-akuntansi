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
    ],
    'subjects' => [
        // 'user_id' => [
        //     'label' => 'User',
        //     'type'  => 'options-obj:users,id,name'
        // ],
        'code' => [
            'label' => 'Kode',
            'type'  => 'text'
        ],
        'name' => [
            'label' => 'Nama Subjek',
            'type'  => 'text'
        ],
        'address' => [
            'label' => 'Alamat',
            'type'  => 'textarea'
        ],
        'phone' => [
            'label' => 'No WA',
            'type'  => 'tel'
        ],
        'email' => [
            'label' => 'Email',
            'type'  => 'email'
        ],
    ],
    'modules' => [
        'role_id' => [
            'label' => 'Role',
            'type'  => 'options-obj:roles,id,name'
        ],
        'account_id' => [
            'label' => 'Kode Akun',
            'type'  => 'text'
        ],
        'name' => [
            'label' => 'Nama Modul',
            'type'  => 'text'
        ],
        'cash_type' => [
            'label' => 'Tipe Transaksi',
            'type'  => 'options:Kas Masuk|Kas Keluar'
        ],
    ],
    'groups' => [
        'parent_id' => [
            'label' => 'Parent',
            'type'  => 'options-obj:groups,id,name'
        ],
        'name' => [
            'label' => 'Nama Group',
            'type'  => 'text'
        ],
    ],
    'bills' => [
        'subject_id' => [
            'label' => 'Subjek',
            'type'  => 'options-obj:subjects,id,name'
        ],
        'merchant_id' => [
            'label' => 'Merchant',
            'type'  => 'options-obj:merchants,id,name'
        ],
        'bill_code' => [
            'label' => 'Kode',
            'type'  => 'text'
        ],
        'amount' => [
            'label' => 'Jumlah',
            'type'  => 'number'
        ],
        'description' => [
            'label' => 'Deskripsi',
            'type'  => 'textarea'
        ],
        // 'notification_to' => [
        //     'label' => 'Kirim Notifikasi Ke',
        //     'type'  => 'options:Semua|Subjek|Kontak'
        // ],
        // 'notification_date' => [
        //     'label' => 'Tanggal Kirim (Kosongkan jika ingin langsung di kirim)',
        //     'type'  => 'date'
        // ],
    ],
    'contacts' => [
        'name' => [
            'label' => 'Nama',
            'type'  => 'text'
        ],
        'phone' => [
            'label' => 'No WA',
            'type'  => 'tel'
        ],
        'email' => [
            'label' => 'Email',
            'type'  => 'email'
        ],
    ],
    'journals' => [
        'date' => [
            'label' => 'Tanggal',
            'type'  => 'date',
            'search' => false,
        ],
        'transaction_code' => [
            'label' => 'Kode',
            'type'  => 'text',
            'search' => true,
        ],
        'account' => [
            'label' => 'Akun',
            'type'  => 'text',
            'search' => false,
        ],
        'debit' => [
            'label' => 'Debit',
            'type'  => 'number',
            'search' => false,
        ],
        'kredit' => [
            'label' => 'Kredit',
            'type'  => 'number',
            'search' => false,
        ],
        'description' => [
            'label' => 'Deskripsi',
            'type'  => 'textarea',
            'search' => true,
        ],
    ],
    'merchants' => [
        // 'report_id',
        'debt_bill_account_id'  => [
            'label' => 'Akun Debet Tagihan',
            'type'  => 'text'
        ],
        'credit_bill_account_id'  => [
            'label' => 'Akun Kredit Tagihan',
            'type'  => 'text'
        ],
        'debt_account_id'  => [
            'label' => 'Akun Debet Pembayaran',
            'type'  => 'text'
        ],
        'credit_account_id'  => [
            'label' => 'Akun Kredit Pembayaran',
            'type'  => 'text'
        ],
        'name'  => [
            'label' => 'Nama',
            'type'  => 'text'
        ],
    ],
    'transactions' => [
        'subject_id' => [
            'label' => 'Subjek',
            'type'  => 'options-obj:subjects,id,name'
        ],
        'transaction_code' => [
            'label' => 'Kode Transaksi',
            'type'  => 'text'
        ],
        'total' => [
            'label' => 'Total',
            'type'  => 'number'
        ],
        'created_at' => [
            'label' => 'Tanggal',
            'type'  => 'datetime-local'
        ]
    ],
    'transaction_items' => [
        'amount' => [
            'label' => 'Jumlah',
            'type'  => 'number',
        ],
        'description' => [
            'label' => 'Deskripsi',
            'type'  => 'text',
        ]
    ],
    'logs' => [
        'name',
        'description'
    ]
];