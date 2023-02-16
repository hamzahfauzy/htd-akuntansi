<?php

$report_id = activeMaster()->id;
$_POST[$table]['report_id'] = $report_id;
$sent_notif = false;

if($_POST[$table]['sent_notif'] == 'Tidak')
{
    unset($_POST[$table]['notification_to']);
    unset($_POST[$table]['notification_date']);
}
else
{
    $sent_notif = true;
    if(empty($_POST[$table]['notification_date']))
    {
        $_POST[$table]['notification_date'] = date('Y-m-d');
    }
}

unset($_POST[$table]['sent_notif']);