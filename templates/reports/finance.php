<?php if(!isset($_GET['print'])): ?>
<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Laporan Keuangan</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Laporan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('reports/finance',['print'=>true])?>" class="btn btn-info btn-round">Cetak</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php endif ?>

                            <?php if(isset($accounts)): ?>
                            <h1 align="center">Laporan Posisi Keuangan</h1>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered" <?=isset($_GET['print'])?'border="1" cellpadding="5" cellspacing="0" width="100%"':''?>>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Kode Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Nama Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Pos Saldo</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Saldo Awal</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Saldo Akhir</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                    </tr>
                                    <?php 
                                    $nrc_debit = 0;
                                    $nrc_credit = 0;
                                    $balance_debit = 0;
                                    $balance_credit = 0;
                                    foreach($neracaData as $account): 
                                        $is_header = strtolower($account->balance_position) == 'header';
                                        $first_db_balance = 0;
                                        $first_cr_balance = 0;
                                        $_balance_debit = 0;
                                        $_balance_credit = 0;
                                        if(!$is_header)
                                        {
                                            $first_db_balance = $account->balance_position == 'Db' ? $account->balance_amount : 0;
                                            $first_cr_balance = $account->balance_position == 'Cr' ? $account->balance_amount : 0;
    
                                            $_balance_debit = $first_db_balance;
                                            $_balance_credit = $first_cr_balance;

                                            $kas_masuk = $account->balance_position == 'Db' ? $account->total_cash_flow : 0;
                                            $kas_keluar = $account->balance_position == 'Cr' ? $account->total_cash_flow : 0;
                                            $_balance_debit += $account->balance_position == 'Db' ? ($kas_keluar-$kas_masuk) : 0;
                                            $_balance_credit += $account->balance_position == 'Cr' ? ($kas_masuk-$kas_keluar) : 0;
    
                                            if(isset($cash_flow_settings[$account->id]))
                                            {
                                                $kas_masuk = $cash_flow_settings[$account->id]['Kas Masuk'] ?? 0;
                                                $kas_keluar = $cash_flow_settings[$account->id]['Kas Keluar'] ?? 0;
                                                $_balance_debit += $account->balance_position == 'Db' ? ($kas_masuk-$kas_keluar) : 0;
                                                $_balance_credit += $account->balance_position == 'Cr' ? ($kas_keluar-$kas_masuk) : 0;
                                            }
                                            
                                            if(isset($balance_cash_settings[$account->id]))
                                            {
                                                $cr = $balance_cash_settings[$account->id]['Cr'] ?? 0;
                                                $dbt = $balance_cash_settings[$account->id]['Db'] ?? 0;
                                                $_balance_debit += $account->balance_position == 'Db' ? ($dbt-$cr) : 0;
                                                $_balance_credit += $account->balance_position == 'Cr' ? ($cr-$dbt) : 0;
                                            }

                                            if(isset($lr_settings[$account->id]))
                                            {
                                                $first_credit = $lr_settings[$account->id]['Cr'] ?? 0;
                                                $first_debit = $lr_settings[$account->id]['Db'] ?? 0;
                                                
                                                $cf_cr = $lr_settings[$account->id]['cf_Cr'] ?? 0;
                                                $cf_db = $lr_settings[$account->id]['cf_Db'] ?? 0;
                                                
                                                $first_cr_balance += ($first_credit-$first_debit);
                                                $_balance_credit += ($cf_cr-$cf_db);
                                            }
    
                                            $nrc_debit += $first_db_balance;
                                            $nrc_credit += $first_cr_balance;
                                            $balance_debit += $_balance_debit;
                                            $balance_credit += $_balance_credit;
                                        }
                                    ?>
                                    <tr <?= $is_header ? 'style="font-weight:bold"' : ''?>>
                                        <td><?=$account->code?></td>
                                        <td style="white-space:nowrap;"><?=$account->name?></td>
                                        <td><?=$account->balance_position?></td>

                                        <?php if($account->balance_position != 'Header'): ?>
                                        <td style="text-align:right"><?=($first_db_balance < 0 ? '(' : '') . number_format(abs($first_db_balance),0,',','.') . ($first_db_balance < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($first_cr_balance < 0 ? '(' : '') . number_format(abs($first_cr_balance),0,',','.') . ($first_cr_balance < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($_balance_debit < 0 ? '(' : '') . number_format(abs($_balance_debit),0,',','.') . ($_balance_debit < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($_balance_credit < 0 ? '(' : '') . number_format(abs($_balance_credit),0,',','.') . ($_balance_credit < 0 ? ')' : '') ?></td>
                                        <?php else: ?>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <?php endif ?>
                                    </tr>
                                    <?php endforeach ?>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Total</td>
                                        <td style="text-align:right"><?=number_format(abs($nrc_debit),0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format(abs($nrc_credit),0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format(abs($balance_debit),0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format(abs($balance_credit),0,',','.')?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php else: ?>
                            <i>Tidak ada data</i>
                            <?php endif ?>

                            <?php if(!isset($_GET['print'])): ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php endif ?>

                            <?php if(isset($accounts)): ?>
                            <h1 align="center">Laporan Laba/Rugi</h1>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered" <?=isset($_GET['print'])?'border="1" cellpadding="5" cellspacing="0" width="100%"':''?>>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Kode Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Nama Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Pos Saldo</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Saldo Awal</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Saldo Akhir</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                    </tr>
                                    <?php 
                                    $lr_debit = 0;
                                    $lr_credit = 0;
                                    $balance_debit = 0;
                                    $balance_credit = 0;
                                    foreach($labaRugiData as $account):
                                        $first_lr_debit = $account->balance_position == 'Db' ? $account->balance_amount : 0;
                                        $first_lr_credit = $account->balance_position == 'Cr' ? $account->balance_amount : 0;
                                        $lr_debit += $first_lr_debit;
                                        $lr_credit += $first_lr_credit;

                                        $_balance_debit = $account->balance_position == 'Db' ? $account->total_cash_flow : 0;
                                        $_balance_credit = $account->balance_position == 'Cr' ? $account->total_cash_flow : 0;
                                        $balance_debit += $_balance_debit;
                                        $balance_credit += $_balance_credit;
                                    ?>
                                    <tr <?=strtolower($account->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                        <td><?=$account->code?></td>
                                        <td style="white-space:nowrap;"><?=$account->name?></td>
                                        <td><?=$account->balance_position?></td>

                                        <?php if($account->balance_position != 'Header'): ?>
                                        <td style="text-align:right"><?=($first_lr_debit < 0 ? '(' : '') . number_format(abs($first_lr_debit),0,',','.') . ($first_lr_debit < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($first_lr_credit < 0 ? '(' : '') . number_format(abs($first_lr_credit),0,',','.') . ($first_lr_credit < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($_balance_debit < 0 ? '(' : '') . number_format(abs($_balance_debit),0,',','.') . ($_balance_debit < 0 ? ')' : '')?></td>
                                        <td style="text-align:right"><?=($_balance_credit < 0 ? '(' : '') . number_format(abs($_balance_credit),0,',','.') . ($_balance_credit < 0 ? ')' : '')?></td>
                                        <?php else: ?>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <td style="text-align:right">-</td>
                                        <?php endif ?>


                                    </tr>
                                    <?php endforeach ?>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Sub Total</td>
                                        <td style="text-align:right"><?=number_format($lr_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($lr_credit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($balance_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($balance_credit,0,',','.')?></td>
                                    </tr>
                                    <?php
                                    $laba_rugi = $lr_credit-$lr_debit;
                                    $laba_rugi_akhir = $balance_credit-$balance_debit;
                                    if($laba_rugi < 0)
                                    {
                                        $laba_rugi = '('.number_format(abs($laba_rugi),0,',','.').')';
                                    }
                                    else
                                    {
                                        $laba_rugi = number_format($laba_rugi,0,',','.');
                                    }

                                    if($laba_rugi_akhir < 0)
                                    {
                                        $laba_rugi_akhir = '('.number_format(abs($laba_rugi_akhir),0,',','.').')';
                                    }
                                    else
                                    {
                                        $laba_rugi_akhir = number_format($laba_rugi_akhir,0,',','.');
                                    }

                                    
                                    ?>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Laba/Rugi Tahun Berjalan</td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"><?=$laba_rugi?></td>
                                        <td style="text-align:right"></td>
                                        <td style="text-align:right"><?=$laba_rugi_akhir?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php else: ?>
                            <i>Tidak ada data</i>
                            <?php endif ?>

                            <?php if(!isset($_GET['print'])): ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>
<?php else: ?>
<script>window.print()</script>
<?php endif ?>