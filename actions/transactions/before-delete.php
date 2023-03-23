<?php

$transaction_items = $db->all('transaction_items',['transaction_id' => $data->id]);
foreach($transaction_items as $item)
{
    $bill = $db->single('bills',['id' => $item->bill_id]);
    $db->update('bills',[
        'remaining_payment' => $bill->remaining_payment+$item->amount,
        'status' => 'BELUM LUNAS'
    ],[
        'id' => $bill->id
    ]);
}
$db->delete('journals', "transaction_code='transaction-$data->transaction_code'");