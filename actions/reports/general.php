<?php
Page::set_title('General Ledger');
if(isset($_GET['code']))
{
    $conn = conn();
    $db   = new Database($conn);

    $account = $db->single('accounts',['code'=>$_GET['code'], 'report_id' => activeMaster()?activeMaster()->id:0]);

    if($account)
    {
        $cash_flows = $db->all('cash_flows',[
            'account_id' => $account->id
        ]);
    
        return compact('account','cash_flows');
    }
}

