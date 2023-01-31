<?php

Page::set_title('KKA');
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
    $db->query = "SELECT account_id, cash_type, SUM(amount) as total FROM `cash_flows` WHERE report_id=$report_id AND account_id IN (".implode(',', $account_ids).") GROUP BY cash_type, account_id";
    $cash_flows_sum = $db->exec('all');
    $cash_flows = [];
    foreach($cash_flows_sum as $cf)
    {
        $cash_flows[$cf->account_id][$cf->cash_type] = $cf->total;
    }

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

    return compact('accounts','cash_flows','cash_flow_settings','balance_cash_settings');
}


