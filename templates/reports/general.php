<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Buku Besar</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data laporan</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <form class="filter-form" name="filterForm">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Kode Akun</label>
                                            <input type="text" name="code" class="form-control" value="<?=isset($_GET['code'])?$_GET['code']:''?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Pos Saldo</label>
                                            <input type="text" class="form-control" readonly value="<?=isset($account)?$account->balance_position:''?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Nama Akun</label>
                                            <input type="text" class="form-control" readonly value="<?=isset($account)?$account->name:''?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="">Saldo Awal</label>
                                            <input type="text" class="form-control" readonly value="<?=isset($account)?number_format($account->balance_amount,0,',','.'):''?>">
                                        </div>
                                    </div>
                                </div>
                                <button class="d-none">submit</button>
                            </form>

                            <?php if(isset($journals) && $journals): ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Debet</th>
                                            <th>Kredit</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $balance = $account->balance_amount;
                                        $balance_type = ['Db' => 'Debit','Cr' => 'Kredit'];
                                        foreach($journals as $idx => $trx): 
                                            $balance = $trx->transaction_type==$balance_type[$account->balance_position] ? $balance+$trx->amount : $balance-$trx->amount;
                                        ?>
                                        <tr>
                                            <td style="white-space:nowrap"><?=$idx+1?></td>
                                            <td style="white-space:nowrap"><?=date('d/m/Y', strtotime($trx->date))?></td>
                                            <td style="white-space:nowrap"><?=$trx->description?></td>
                                            <td style="white-space:nowrap;text-align:right"><?=$trx->transaction_type=='Debit'?number_format($trx->amount,0,',','.'):''?></td>
                                            <td style="white-space:nowrap;text-align:right"><?=$trx->transaction_type=='Kredit'?number_format($trx->amount,0,',','.'):''?></td>
                                            <td style="white-space:nowrap;text-align:right"><?=number_format($balance,0,',','.')?></td>
                                        </tr>
                                        <?php endforeach ?>
                                        <tr>
                                            <td colspan="6">
                                                <center>Balance : <b><?=number_format($balance, 0,',','.')?></b></center>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>