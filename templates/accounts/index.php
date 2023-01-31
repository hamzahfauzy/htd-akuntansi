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
                            (is_allowed(get_route_path('accounts/index',[]),auth()->user->id) && $parent)
                            ): ?>
                            <a href="<?=routeTo('accounts/index', $parent->parent_id ? ['parent_id'=>$parent->parent_id] : [] )?>" class="btn btn-warning btn-round">Kembali</a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('accounts/import',[]),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): ?>
                            <a href="<?=routeTo('accounts/import')?>" class="btn btn-info btn-round">Import Data</a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('crud/create',['table'=>'accounts']),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                            ): 
                            $params = [
                                'table'=>$table
                            ];
                            if(isset($_GET['parent_id']) && !empty($_GET['parent_id']))
                            {
                                $params['parent_id'] = $_GET['parent_id'];
                            }
                            ?>
                            <a href="<?=routeTo('crud/create',$params)?>" class="btn btn-secondary btn-round">Buat <?=_ucwords(__($table))?></a>
                        <?php endif ?>

                        <?php if(
                            (is_allowed(get_route_path('accounts/index',[]),auth()->user->id) && !isset($_GET['full']))
                            ): ?>
                            <a href="<?=routeTo('accounts/index', ['full' => true] )?>" class="btn btn-primary btn-round">Lihat Akun Lengkap</a>
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
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data as $index => $d): ?>
                                        <tr <?=isset($_GET['full']) && strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <?php 
                                            foreach($fields as $key => $field): 
                                                if(is_array($field))
                                                {
                                                    $data_value = $d->{$key};
                                                    if($field['type'] == 'number')
                                                    {
                                                        if($key == 'balance_amount')
                                                        {
                                                            $data_value = number_format($data_value,0,',','.');
                                                        }
                                                        else
                                                        {
                                                            $data_value = number_format($data_value,0,',','.');
                                                        }
                                                    }
                                                    $field = $key;
                                                }
                                                else
                                                {
                                                    $data_value = $d->{$field};
                                                }

                                                if(!isset($_GET['full']) && $field == 'name' && ((is_allowed(get_route_path('accounts/index',[]),auth()->user->id) && strtolower($d->balance_position) == 'header')))
                                                {
                                                    $data_value = "<a href='".routeTo('accounts/index',['parent_id'=>$d->id])."'>".$data_value."</a>";
                                                }

                                            ?>
                                            <td style="white-space:nowrap"><?=$data_value?></td>
                                            <?php endforeach ?>
                                            <td style="white-space:nowrap">
                                                <?php if(
                                                (is_allowed(get_route_path('crud/edit',['table'=>'accounts']),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                                                ):
                                                ?>
                                                <a href="<?=routeTo('crud/edit',['table'=>'accounts','id'=>$d->id])?>" class="btn btn-sm btn-warning btn-account">Edit</a>
                                                <?php endif ?>

                                                <?php if(
                                                (is_allowed(get_route_path('crud/delete',['table'=>'accounts']),auth()->user->id) && activeMaster() && activeMaster()->is_open == 'BUKA')
                                                ):
                                                ?>
                                                <a href="<?=routeTo('crud/delete',['table'=>'accounts','id'=>$d->id])?>" onclick="if(confirm('apakah anda yakin akan menghapus data ini ?')){return true}else{return false}" class="btn btn-sm btn-danger btn-account">Hapus</a>
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