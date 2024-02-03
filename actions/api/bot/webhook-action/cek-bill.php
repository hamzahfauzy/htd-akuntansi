<?php

if(!$data)
{
    $msg = "No WA anda ".$froms[1]." tidak terdaftar di sistem. Hubungi petugas untuk penyesuaian data siswa dengan no WA terkait";
    Whatsapp::send($froms[0], $msg);
    die();
}

$msg = "Status pembayaran atas nama
Nama: $data->name
NIS: $data->code
Kelas: $data->group_name
Rekening: $data->email

--------------------------";

$bills = $db->all('bills',[
    'subject_id' => $data->id
]);

foreach($bills as $bill)
{
    $msg .= "
$bill->description - Rp.".number_format($bill->amount)." - $bill->status";
}

Whatsapp::send($froms[0], $msg);
die();