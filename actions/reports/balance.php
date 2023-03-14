<?php
Page::set_title('Neraca');
$conn = conn();
$db   = new Database($conn);

$accounts = $db->all('accounts',['report_position' => 'LR', 'report_id' => activeMaster()?activeMaster()->id:0]);
$journals = null;

if($accounts)
{
    $report_id = activeMaster() ? activeMaster()->id : 0;

    $params = [
        'report_id' => $report_id,
        'code' => ['LIKE','1%'],
        'report_position' => 'NRC'
    ];

    $aktiva = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);

    $aktiva = array_map(function($d) use ($db) {
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Debit'";
        $d->debt = $db->exec('single')->TOTAL;
        
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Kredit'";
        $d->credit = $db->exec('single')->TOTAL;

        return $d;
    }, $aktiva);

    $params['code'] = ['LIKE','2%'];
    $hutang = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);

    $hutang = array_map(function($d) use ($db) {
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Debit'";
        $d->debt = $db->exec('single')->TOTAL;
        
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Kredit'";
        $d->credit = $db->exec('single')->TOTAL;

        return $d;
    }, $hutang);

    $params['code'] = ['LIKE','3%'];
    $modal = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);

    $modal = array_map(function($d) use ($db) {
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Debit'";
        $d->debt = $db->exec('single')->TOTAL;
        
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Kredit'";
        $d->credit = $db->exec('single')->TOTAL;

        return $d;
    }, $modal);

    return compact('aktiva','hutang','modal');
}
