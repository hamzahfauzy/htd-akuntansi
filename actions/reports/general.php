<?php
Page::set_title('Buku Besar');
if(isset($_GET['code']))
{
    $conn = conn();
    $db   = new Database($conn);

    $account = $db->single('accounts',['code'=>$_GET['code'], 'report_id' => activeMaster()?activeMaster()->id:0]);
    $journals = null;

    if($account)
    {
        $journals = $db->all('journals',[
            'account_id' => $account->id
        ]);
    
        return compact('account','journals');
    }
}

