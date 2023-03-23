<?php

$db->delete('journals',[
    'transaction_code' => 'bill-'.$data->bill_code
]);