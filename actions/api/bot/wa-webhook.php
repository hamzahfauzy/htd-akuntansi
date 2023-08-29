<?php

$message_lists = [
    'cek bill'
];

header('content-type: application/json');
$data    = json_decode(file_get_contents('php://input'), true);
$message = $data['message'];


if(!in_array($message, $message_lists)) die();


$from    = $data['from'];
$from2   = explode('@',$from)[0];

$froms   = [$from2, '0'.substr($from2, 2)];

$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster()?activeMaster()->id:0;

$where = "WHERE phone IN ('".implode("','", $froms)."')";

$db->query = "SELECT *, CONCAT(subjects.code,' - ',subjects.name) as subject_name, (SELECT groups.name FROM `groups` WHERE groups.id = (SELECT group_id FROM subject_groups WHERE user_id = subjects.user_id AND report_id = $report_id)) as group_name FROM subjects $where ORDER BY id";
$data  = $db->exec('single');

try {
    //code...
    if(!$data)
    {
        $msg = "No WA anda ".$froms[1]." tidak terdaftar di sistem. Hubungi petugas untuk penyesuaian data siswa dengan no WA terkait";
        Whatsapp::send($froms[0], $msg);
        die();
    }

    if($message == 'cek bill')
    {
        require 'webhook-action/cek-bill.php';
    }
    // Whatsapp::send($from2, "");
} catch (\Throwable $th) {
    //throw $th;
}

die();