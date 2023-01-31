<?php

Page::set_title('Laporan Keuangan');
$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster() ? activeMaster()->id : 0;

if($report_id)
{
    $params = [
        'report_id' => $report_id,
    ];
    
    $accounts = $db->all('accounts', $params,[
        'code' => 'ASC'
    ]);
    
    $account_ids = array_column((array) $accounts, 'id');
    // account setting
    $settings = [];
    $db->query = "SELECT * FROM account_settings WHERE report_id=$report_id AND cash_source = 'Mutasi Kas' AND account_source IN (".implode(',', $account_ids).")";
    $account_settings = $db->exec('all');
    foreach($account_settings as $setting)
    {
        $settings[$setting->account_source][] = $setting->account_target;
    }

    // cash flows from account setting
    $cash_flow_settings = [];
    foreach($settings as $account_id => $setting)
    {
        $in_ids = implode(',',$setting);
        $db->query = "SELECT account_id, cash_type, SUM(amount) as total FROM `cash_flows` WHERE report_id=$report_id AND account_id IN ($in_ids) GROUP BY cash_type";
        $cf = $db->exec('all');
        foreach($cf as $c)
        {
            $cash_flow_settings[$account_id][$c->cash_type] = $c->total;
        }
    }

    // balance_account setting
    $db->query = "SELECT * FROM account_settings WHERE report_id=$report_id AND cash_source = 'Saldo Akun' AND account_source IN (".implode(',', $account_ids).")";
    $balance_account_settings = $db->exec('all');
    $b_settings = [];
    foreach($balance_account_settings as $setting)
    {
        $b_settings[$setting->account_source][] = $setting->account_target;
    }

    $balance_cash_settings = [];
    foreach($b_settings as $account_id => $setting)
    {
        $in_ids = implode(',',$setting);
        $db->query = "SELECT id, balance_position, SUM(balance_amount) as total FROM `accounts` WHERE report_id=$report_id AND id IN ($in_ids) GROUP BY balance_position";
        $cf = $db->exec('all');
        foreach($cf as $c)
        {
            $balance_cash_settings[$account_id][$c->balance_position] = $c->total;
        }
    }

    $db->query = "SELECT * FROM account_settings WHERE report_id=$report_id AND cash_source = 'Laba Rugi' AND account_source IN (".implode(',', $account_ids).")";
    $lr_account_settings = $db->exec('all');
    $lr_accounts = [];
    foreach($lr_account_settings as $setting)
    {
        $lr_accounts[$setting->account_source][] = $setting->account_target;
    }

    $lr_settings = [];
    foreach($lr_accounts as $account_id => $setting)
    {
        $in_ids = implode(',',$setting);
        $db->query = "SELECT accounts.balance_position, SUM(accounts.balance_amount) as total, accounts.report_position FROM accounts WHERE id IN ($in_ids) AND accounts.report_position='LR' and balance_position <> 'Header' GROUP BY accounts.balance_position";
        $lr_balances = $db->exec('all');
        foreach($lr_balances as $lr)
        {
            $lr_settings[$account_id][$lr->balance_position] = $lr->total;
        }

        $db->query = "SELECT account_id, (SELECT balance_position FROM accounts WHERE id=cash_flows.account_id) as balance_position, cash_type, SUM(amount) as total FROM `cash_flows` WHERE report_id=$report_id AND account_id IN ($in_ids) GROUP BY cash_type";
        $lr_cash_flows = $db->exec('all');
        foreach($lr_cash_flows as $lr)
        {
            $lr_settings[$account_id]['cf_'.$lr->balance_position] = $lr->total;
        }
    }

    // query data laba rugi
    $db->query = "SELECT accounts.id, accounts.code, accounts.name, accounts.balance_position, accounts.balance_amount, accounts.report_position, (SELECT SUM(cash_flows.amount) FROM cash_flows WHERE account_id=accounts.id GROUP BY account_id) as total_cash_flow FROM accounts WHERE accounts.report_position='LR' AND accounts.report_id=$report_id";
    $labaRugiData = $db->exec('all');
    
    // query data neraca
    $db->query = "SELECT accounts.id, accounts.code, accounts.name, accounts.balance_position, accounts.balance_amount, accounts.report_position, (SELECT SUM(cash_flows.amount) FROM cash_flows WHERE account_id=accounts.id GROUP BY account_id) as total_cash_flow FROM accounts WHERE accounts.report_position='NRC' AND accounts.report_id=$report_id";
    $neracaData = $db->exec('all');

    // for laba rugi account setting
    // $db->query = "SELECT accounts.id, accounts.code, accounts.name, accounts.balance_position, accounts.balance_amount, accounts.report_position, (SELECT SUM(cash_flows.amount) FROM cash_flows WHERE account_id=accounts.id GROUP BY account_id) as total_cash_flow FROM accounts WHERE accounts.report_position='LR' AND accounts.report_id=$report_id";
    // $labaRugiSetting = $db->exec('all');

    return compact('accounts','cash_flow_settings','balance_cash_settings','labaRugiData','neracaData','lr_settings');
}


