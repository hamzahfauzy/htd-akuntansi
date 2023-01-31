<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold"><?=_ucwords(__($table))?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data <?=_ucwords(__($table))?></h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <?php if(
                            (is_allowed(get_route_path($table.'/import',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): ?>
                            <a href="<?=routeTo($table.'/import')?>" class="btn btn-info btn-round">Import Data</a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('crud/create',['table'=>$table]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): 
                            $params = [
                                'table'=>$table
                            ];
                            ?>
                            <a href="<?=routeTo('crud/create',$params)?>" class="btn btn-secondary btn-round">Buat <?=_ucwords(__($table))?></a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode Akun</th>
                                            <?php 
                                            foreach($fields as $field): 
                                                $label = $field;
                                                if(is_array($field))
                                                {
                                                    $label = $field['label'];
                                                }
                                                $label = _ucwords($label);
                                            ?>
                                            <th><?=$label?></th>
                                            <?php endforeach ?>
                                            <th>Saldo</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $balance = 0;
                                        foreach($data as $index => $d): 
                                        ?>
                                        <tr>
                                            <td style="white-space:nowrap"><?=$accounts[$d->account_id]->code;?></td>
                                            <?php 
                                            foreach($fields as $key => $field): 
                                                if(is_array($field))
                                                {
                                                    $data_value = $d->{$key};
                                                    if($field['type'] == 'number')
                                                    {
                                                        if($key == 'amount')
                                                        {
                                                            $data_value = number_format($data_value,0,',','.');
                                                        }
                                                        else
                                                        {
                                                            $data_value = number_format($data_value,0,',','.');
                                                        }
                                                    }
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

                                            ?>
                                            <td style="white-space:nowrap"><?=$data_value?></td>
                                            <?php endforeach ?>
                                            <?php
                                            if($d->cash_type == 'Kas Masuk')
                                            $balance += $d->amount;
                                            if($d->cash_type == 'Kas Keluar')
                                            $balance -= $d->amount;
                                            ?>
                                            <td style="white-space:nowrap"><?=number_format($balance,0,',','.');?></td>
                                            <td style="white-space:nowrap">
                                                <?php if(
                                                (is_allowed(get_route_path('crud/edit',['table'=>'cash_flows']),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                                                ):
                                                ?>
                                                <a href="<?=routeTo('crud/edit',['table'=>'cash_flows','id'=>$d->id])?>" class="btn btn-sm btn-warning btn-account">Edit</a>
                                                <?php endif ?>

                                                <?php if(
                                                (is_allowed(get_route_path('crud/delete',['table'=>'cash_flows']),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                                                ):
                                                ?>
                                                <a href="<?=routeTo('crud/delete',['table'=>'cash_flows','id'=>$d->id])?>" class="btn btn-sm btn-danger btn-account">Hapus</a>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>