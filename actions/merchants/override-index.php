<?php

$db->query = "SELECT *, (SELECT concat(code,' ',name) FROM accounts WHERE accounts.id = $table.debt_account_id) debt_account_id, (SELECT concat(code,' ',name) FROM accounts WHERE accounts.id = $table.credit_account_id) credit_account_id, (SELECT concat(code,' ',name) FROM accounts WHERE accounts.id = $table.credit_bill_account_id) credit_bill_account_id, (SELECT concat(code,' ',name) FROM accounts WHERE accounts.id = $table.debt_bill_account_id) debt_bill_account_id FROM $table $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');