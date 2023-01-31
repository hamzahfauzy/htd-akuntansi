<?php

if(isset($_POST[$table]['ref_id']))
{
    $report_id = $_POST[$table]['ref_id'];
    // copy account
    $map_accounts = [];
    $accounts = $db->all('accounts',['report_id'=>$report_id]);
    foreach($accounts as $account)
    {
        $data = (array) $account;
        $data['report_id'] = $insert->id;
        unset($data['id']);
        if(empty($data['parent_id']))
        {
            unset($data['parent_id']);
        }
        else
        {
            $data['parent_id'] = $map_accounts[$data['parent_id']];
        }
        unset($data['created_at']);

        $new_account = $db->insert('accounts', $data);
        $map_accounts[$account->id] = $new_account->id;
    }

    // copy setting
    $account_settings = $db->all('account_settings',[
        'report_id' => $report_id
    ]);

    foreach($account_settings as $setting)
    {
        $setting = (array) $setting;
        unset($setting['id']);
        // setting account_source, account_target, cash_source
        $setting['account_source'] = $map_accounts[$setting['account_source']];
        $setting['account_target'] = $map_accounts[$setting['account_target']];

        $db->insert('account_settings',$setting);
    }

}

