<?php

$conn = conn();
$db   = new Database($conn);

$bill = $db->single('bills',['id'=>$_GET['id']]);
$subject = $db->single('subjects',['id'=>$bill->subject_id]);
$db->query = "SELECT * FROM `groups` WHERE id = (SELECT group_id FROM subject_groups WHERE user_id = $subject->user_id)";
$group   = $db->exec('single');
$merchant = $db->single('merchants',['id'=>$bill->merchant_id]);

$payload = [];
$payload['group_name'] = $group->name;
$payload['subject_code'] = $subject->code;
$payload['subject_name'] = $subject->name;
$payload['subject_email'] = $subject->email;
$payload['subject_phone'] = $subject->phone;
$payload['subject_address'] = $subject->address;
$payload['bill_amount']   = number_format($bill->amount);
$payload['bill_code']     = $bill->bill_code;
$payload['merchant_name'] = $merchant->name;

new Notification('bill-reminder', $payload);

set_flash_msg(['success'=>'Berhasil! Notifikasi sedang dalam antrian dan akan segera dikirim.']);
header('location:'.routeTo('crud/index',['table'=>'bills']));
die();