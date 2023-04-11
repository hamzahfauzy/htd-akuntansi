<?php

$fields['debt_bill_account_id']['type'] = "options-obj:accounts,id,CONCAT(code,' ',name)|id,".$data->debt_bill_account_id;
$fields['credit_bill_account_id']['type'] = "options-obj:accounts,id,CONCAT(code,' ',name)|id,".$data->credit_bill_account_id;
$fields['debt_account_id']['type'] = "options-obj:accounts,id,CONCAT(code,' ',name)|id,".$data->debt_account_id;
$fields['credit_account_id']['type'] = "options-obj:accounts,id,CONCAT(code,' ',name)|id,".$data->credit_account_id;

return $fields;