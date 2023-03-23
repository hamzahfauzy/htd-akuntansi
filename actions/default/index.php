<?php

Page::set_title('Dashboard');

$conn = conn();
$db   = new Database($conn);
$report_id = activeMaster()?activeMaster()->id:0;

$total_pendapatan = get_total_pendapatan();
$total_beban = get_total_beban();
$laba_rugi = get_laba_rugi();

$stats = [
    'subjects' => $db->exists('subjects'),
    'masters' => $db->exists('reports'),
    'accounts' => $db->exists('accounts',[
        'report_id' => $report_id
    ]),
];

return compact('total_pendapatan','total_beban','laba_rugi','stats');