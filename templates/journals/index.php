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
                            (is_allowed(get_route_path('journals/import',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): ?>
                            <a href="<?=routeTo('journals/import')?>" class="btn btn-info btn-round">Import Data</a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('journals/clear',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): ?>
                            <a href="<?=routeTo('journals/clear')?>" class="btn btn-danger btn-round">Clear Data</a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('journals/create',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): ?>
                            <a href="<?=routeTo('journals/create')?>" class="btn btn-primary btn-round">Tambah Data</a>
                        <?php endif ?>

                        

                        <?php /* if(
                            (is_allowed(get_route_path('journals/panel',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): 
                            ?>
                            <a href="<?=routeTo('journals/panel')?>" class="btn btn-secondary btn-round">Panel Jurnal</a>
                        <?php endif */ ?>
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
                                            <?php 
                                            foreach($fields as $key => $field): 
                                                $label = $field;
                                                if(is_array($field))
                                                {
                                                    $label = $field['label'];
                                                }
                                                $label = _ucwords($label);
                                            ?>
                                            <th><?=$label?></th>
                                            <?php endforeach ?>
                                            <?php if(activeMaster() && activeMaster()->is_open == 'BUKA'): ?>
                                            <th></th>
                                            <?php endif ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $balance = 0;
                                        $debt_total = 0;
                                        $credit_total = 0;
                                        foreach($data as $index => $d): 
                                            if($d->transaction_type == 'Debit')
                                            {
                                                $debt_total += $d->amount;
                                            }
                                            if($d->transaction_type == 'Kredit')
                                            {
                                                $credit_total += $d->amount;
                                            }
                                        ?>
                                        <tr>
                                            <?php 
                                            foreach($fields as $key => $field): 
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

                                            ?>
                                            <td style="white-space:nowrap;<?=$type=='number'?'text-align:right;':''?>"><?=$data_value?></td>
                                            <?php endforeach ?>
                                            <?php if(activeMaster() && activeMaster()->is_open == 'BUKA'): ?>
                                            <td style="white-space:nowrap;">
                                            <?php if(
                                                    (is_allowed(get_route_path('journals/edit',[]),auth()->user->id))
                                                    ): ?>
                                                <a href="<?=routeTo('journals/edit',['id'=>$data->id])?>" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>
                                            <?php endif ?>
                                            
                                            <?php if(
                                                (is_allowed(get_route_path('journals/edit',[]),auth()->user->id))
                                                ): ?>
                                                <a href="<?=routeTo('journals/delete',['id'=>$data->id])?>" class="btn btn-sm btn-danger" onclick="if(confirm('Apakah anda yakin akan menghapus data ini ?')){return true}else{return false}"><i class="fas fa-trash"></i> Hapus</a>
                                            <?php endif ?>
                                            </td>
                                            <?php endif ?>
                                        </tr>
                                        <?php endforeach ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:right"><?=number_format($debt_total)?></td>
                                            <td style="text-align:right"><?=number_format($credit_total)?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
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