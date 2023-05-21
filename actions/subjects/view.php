<?php

$table = 'subjects';
Page::set_title('Detail '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');

Validation::run([
    'id' => [
        'required','exists:subjects,id,'.$_GET['id']
    ]
], $_GET);

$conn = conn();
$db   = new Database($conn);

$subject = $db->single($table, ['id' => $_GET['id']]);

$db->query = "SELECT bills.*, merchants.name as merchant_name FROM bills JOIN merchants ON merchants.id = bills.merchant_id  WHERE bills.subject_id = $subject->id";
$subject->bills   = $db->exec('all');

$db->query = "SELECT transaction_items.*, transactions.transaction_code, transactions.subject_id, merchants.name as merchant_name FROM `transaction_items` JOIN transactions ON transactions.id = transaction_items.transaction_id JOIN merchants ON merchants.id = transaction_items.merchant_id WHERE transactions.subject_id = $subject->id";
$subject->transactions = $db->exec('all');

$db->query = "SELECT * FROM `groups` WHERE id IN (SELECT group_id FROM subject_groups WHERE subject_groups.user_id = $subject->user_id)";
$subject->groups   = $db->exec('all');

return compact('subject');