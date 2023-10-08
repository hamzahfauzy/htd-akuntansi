<?php

// check if file exists
$parent_path = '';
if (!in_array(php_sapi_name(),["cli","cgi-fcgi"])) {
    $parent_path = 'public/';
}

if(file_exists($parent_path . 'reminder-lock.txt'))
{
    die();
}

if(!config('REMINDER_DATE') || config('REMINDER_DATE') != date('d'))
{
    die();
}

echo "Today is a day to send reminder\n";

if(!file_exists($parent_path . 'config/notifications/bill-reminder.php'))
{
    die;
}

file_put_contents($parent_path . 'reminder-lock.txt', strtotime('now'));

try {
    //code...
    echo date('Y-m-d H:i:s') . " - Send Reminder Start\n";

    $reminderConfig = require $parent_path . 'config/notifications/bill-reminder.php';

    $conn = conn();
    $db   = new Database($conn);

    $payload = [];

    $groups = $db->all('groups', [
        'report_id' => (activeMaster()?activeMaster()->id:0)
    ]);

    foreach($groups as $g)
    {
        $db->query = "SELECT 
                    bills.subject_id, subjects.code subject_code, 
                    subjects.name subject_name, subjects.email subject_email, 
                    subjects.address subject_address,
                    subjects.phone subject_phone,
                    subjects.user_id
                    FROM 
                    bills 
                    JOIN subjects ON 
                    subjects.id=bills.subject_id 
                    WHERE bills.status='BELUM LUNAS' AND subjects.user_id IN (SELECT user_id FROM subject_groups WHERE group_id = $g->id) 
                    GROUP BY bills.subject_id";
        $subjects = $db->exec('all');

        $payload['group_name'] = $g->name;

        foreach($subjects as $subject)
        {
            $db->query = "SELECT bills.remaining_payment bill_amount, bills.bill_code, merchants.name merchant_name FROM bills JOIN merchants ON merchants.id=bills.merchant_id WHERE bills.subject_id=$subject->subject_id AND bills.status='BELUM LUNAS'";
            $bills  = $db->exec('all');

            $payload['subject_code'] = $subject->subject_code;
            $payload['subject_name'] = $subject->subject_name;
            $payload['subject_email'] = $subject->subject_email;
            $payload['subject_phone'] = $subject->subject_phone;
            $payload['subject_address'] = $subject->subject_address;
            
            foreach($bills as $bill)
            {
                $payload['bill_amount']   = number_format($bill->bill_amount);
                $payload['bill_code']     = $bill->bill_code;
                $payload['merchant_name'] = $bill->merchant_name;

                
                try {
                    //code...
                    $message = $reminderConfig['message'];;
                    $target  = $payload[$reminderConfig['target']];
        
                    foreach($payload as $key => $value)
                    {
                        $message = str_replace('{'.$key.'}', $value, $message);
                    }
        
                    (new $reminderConfig['provider'])->send($target, $message);
                    sleep(10);
                } catch (\Throwable $th) {
                    //throw $th;
                    print_r($th);
                }
            }
        }
    }


    echo date('Y-m-d H:i:s') . " - Send Reminder Finish\n";
} catch (\Throwable $th) {
    //throw $th;
    print_r($th);
}

unlink($parent_path . 'reminder-lock.txt');

die();
