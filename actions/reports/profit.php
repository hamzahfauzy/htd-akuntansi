<?php
Page::set_title('Laba Rugi');
$conn = conn();
$db   = new Database($conn);

$accounts = $db->all('accounts',['report_position' => 'LR', 'report_id' => activeMaster()?activeMaster()->id:0]);
$journals = null;

if($accounts)
{
    // $dump_accounts = [];
    // foreach($accounts as $key => $account)
    // {
    //     $dump_accounts[$account->id] = $account;
    // }

    // $account_ids = array_column((array) $accounts, 'id');
    // $account_ids = '('.implode(',',$account_ids).')';
    // $journals = $db->all('journals',[
    //     'account_id' => ['IN',$account_ids]
    // ]);

    // $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id IN $account_ids AND transaction_type = 'Debit'";
    // $debt = $db->exec('single')->TOTAL;
    
    // $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id IN $account_ids AND transaction_type = 'Kredit'";
    // $credit = $db->exec('single')->TOTAL;

    // return compact('dump_accounts','journals','debt','credit');

    $report_id = activeMaster() ? activeMaster()->id : 0;

    $params = [
        'report_id' => $report_id,
        'code' => ['LIKE','4%'],
        'report_position' => 'LR'
    ];

    $pendapatan = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);

    $pendapatan = array_map(function($d) use ($db) {
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Debit'";
        $d->debt = $db->exec('single')->TOTAL;
        
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Kredit'";
        $d->credit = $db->exec('single')->TOTAL;

        return $d;
    }, $pendapatan);

    $params['code'] = ['LIKE','5%'];
    $beban = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);

    $beban = array_map(function($d) use ($db) {
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Debit'";
        $d->debt = $db->exec('single')->TOTAL;
        
        $db->query = "SELECT SUM(amount) as TOTAL FROM journals WHERE account_id = $d->id AND transaction_type = 'Kredit'";
        $d->credit = $db->exec('single')->TOTAL;

        return $d;
    }, $beban);

    return compact('pendapatan','beban');
}

