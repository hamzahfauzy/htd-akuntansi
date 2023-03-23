<?php

$table = 'journals';
Page::set_title(_ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];

$fields['transaction_code'] = [
    'label' => 'Kode',
    'type'  => 'text'
];

if(isset($_GET['draw']))
{
    $draw    = $_GET['draw'];
    $start   = $_GET['start'];
    $length  = $_GET['length'];
    $search  = $_GET['search']['value'];
    $order   = $_GET['order'];

    $columns = [];
    $search_columns = [];
    foreach($fields as $key => $field)
    {
        $columns[] = is_array($field) ? $key : $field;
        if(is_array($field) && isset($field['search']) && !$field['search']) continue;
        $search_columns[] = is_array($field) ? $key : $field;
    }
    
    $data_params = "WHERE report_id=".(activeMaster()?activeMaster()->id:0);

    $order = [
        'date' => 'ASC',
        'transaction_code' => 'ASC'
    ];

    if(!empty($search))
    {
        $add_params = [];
        foreach($search_columns as $col)
        {
            $add_params[] = $col." LIKE '%$search%'";
        }

        $data_params .= ' AND ('.implode(' OR ',$add_params).')';
    }

    $data = $db->all($table,$data_params,$order,$start.','.$length);
    $total = $db->exists($table,$data_params,$order);
    
    $account_ids = array_column((array) $data, 'account_id');
    $params = $account_ids ? ['id' => ['IN','('.implode(',',$account_ids).')']] : [];
    $accounts = $db->all('accounts',$params);
    $dump_accounts = [];
    foreach($accounts as $key => $account)
    {
        $dump_accounts[$account->id] = $account;
    }
    
    $data = array_map(function($d) use ($dump_accounts){
        $d->debit = $d->transaction_type == 'Debit' ? number_format($d->amount) : '';
        $d->kredit = $d->transaction_type == 'Kredit' ? number_format($d->amount) : '';
        $d->account = $dump_accounts[$d->account_id]->code .' '. $dump_accounts[$d->account_id]->name;
        return $d;
    }, $data);

    $datatable = [];

    foreach($data as $index => $d)
    {
        foreach($fields as $key => $field)
        {
            $type = null;
            if(is_array($field))
            {
                $data_value = $d->{$key};
                $type = $field['type'];
                if($field['type'] == 'date')
                {
                    $data_value = date('d/m/Y', strtotime($data_value));
                }
                $field = $key;
            }
            else
            {
                $data_value = $d->{$field};
            }

            if($field == 'account_id')
            {
                $data_value = $accounts[$data_value]->name;
            }

            $datatable[$index][] = $data_value;
        }

        $action = '';
        if(activeMaster() && activeMaster()->is_open == 'BUKA')
        {
            if(
                (is_allowed(get_route_path('journals/edit',[]),auth()->user->id))
                )
                {
                    $action .= '<a href="'.routeTo('journals/edit',['id'=>$d->id]).'" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>';
                }
            if(
                (is_allowed(get_route_path('journals/edit',[]),auth()->user->id))
                )
                {
                    $action .= '<a href="'.routeTo('journals/delete',['id'=>$d->id]).'" class="btn btn-sm btn-danger" onclick="if(confirm(\'Apakah anda yakin akan menghapus data ini ?\')){return true}else{return false}"><i class="fas fa-trash"></i> Hapus</a>';
                }
        }

        $datatable[$index][] = $action;
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => (int)$total,
        "recordsFiltered" => (int)$total,
        "data" => $datatable,
        "search_columns" => $search_columns,
        'params' => $data_params
    ]);

    die();

}


return [
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
];
