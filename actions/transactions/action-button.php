<?php

$isOperator = $status = config('OPERATOR_ROLE_ID') == get_role(auth()->user->id)->role_id;

$button = '<a href="'.routeTo('crud/index',['table'=>'transaction_items','transaction_id'=>$d->id]).'" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> Lihat</a> ';

if(!$isOperator && $d->status == 'PENDING')
{
    $button .= '<a href="'.routeTo('transactions/confirm',['id'=>$d->id]).'" class="btn btn-info btn-sm" onclick="if(confirm(\'Apakah anda yakin akan mengkonfirmasi transaksi ini ?\')){ return true }else{return false}"><i class="fas fa-check"></i> Konfirmasi</a>';
}

return $button;