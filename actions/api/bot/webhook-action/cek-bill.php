<?php

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