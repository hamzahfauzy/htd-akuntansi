<?php if(!isset($_GET['print'])): ?>
<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Rangkuman Kertas Kerja</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Laporan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('reports/worksheet',['print'=>true])?>" class="btn btn-info btn-round">Cetak</a>
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
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered" <?=isset($_GET['print'])?'border="1" cellpadding="5" cellspacing="0"':''?>>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Kode Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Nama Akun</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Pos Saldo</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Neraca Saldo</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" rowspan="2">Pos Laporan</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Laba Rugi</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;" colspan="2">Neraca</td>
                                    </tr>
                                    <tr>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Debet</td>
                                        <td style="white-space:nowrap;text-align:center;font-weight:bold;">Kredit</td>
                                    </tr>
                                    <?php 
                                    $balance_debit  = 0;
                                    $balance_credit = 0;
                                    $lr_debit = 0;
                                    $lr_credit = 0;
                                    $nrc_debit = 0;
                                    $nrc_credit = 0;
                                    foreach($accounts as $account): 
                                        $_balance_debit  = 0;
                                        $_balance_credit = 0;
                                        if($account->balance_position != 'Header')
                                        {
                                            if(isset($cash_flows[$account->id]))
                                            {
                                                $kas_masuk = $cash_flows[$account->id]['Kas Masuk'] ?? 0;
                                                $kas_keluar = $cash_flows[$account->id]['Kas Keluar'] ?? 0;
                                                $_balance_debit += $account->balance_position == 'Db' ? ($kas_keluar-$kas_masuk) : 0;
                                                $_balance_credit += $account->balance_position == 'Cr' ? ($kas_masuk-$kas_keluar) : 0;
                                            }

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

                                            if($account->report_position == 'NRC')
                                            {
                                                $_balance_debit += $account->balance_position == 'Db' ? $account->balance_amount : 0;
                                                $_balance_credit += $account->balance_position == 'Cr' ? $account->balance_amount : 0;

                                                $nrc_debit += $_balance_debit;
                                                $nrc_credit += $_balance_credit;
                                            }
                                            else
                                            {
                                                $lr_debit += $_balance_debit;
                                                $lr_credit += $_balance_credit;
                                            }

                                        }

                                        $balance_debit += $_balance_debit;
                                        $balance_credit += $_balance_credit;
                                    ?>
                                    <tr <?=strtolower($account->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                        <td><?=$account->code?></td>
                                        <td style="white-space:nowrap;"><?=$account->name?></td>
                                        <td><?=$account->balance_position?></td>

                                        <?php if($account->balance_position != 'Header'): ?>
                                        <td style="text-align:right"><?=number_format($_balance_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($_balance_credit,0,',','.')?></td>
                                        <?php else: ?>
                                        <td></td>
                                        <td></td>
                                        <?php endif ?>

                                        <td><?=$account->report_position?></td>

                                        <?php if($account->report_position == 'LR'): ?>
                                        <td style="text-align:right"><?=number_format($_balance_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($_balance_credit,0,',','.')?></td>
                                        <?php else: ?>
                                        <td></td>
                                        <td></td>
                                        <?php endif ?>

                                        <?php if($account->report_position == 'NRC'): ?>
                                        <td style="text-align:right"><?=number_format($_balance_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($_balance_credit,0,',','.')?></td>
                                        <?php else: ?>
                                        <td></td>
                                        <td></td>
                                        <?php endif ?>
                                    </tr>
                                    <?php endforeach ?>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Sub Total</td>
                                        <td style="text-align:right"><?=number_format($balance_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($balance_credit,0,',','.')?></td>
                                        <td></td>
                                        <td style="text-align:right"><?=number_format($lr_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($lr_credit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($nrc_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($nrc_credit,0,',','.')?></td>
                                    </tr>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Laba Rugi</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:right"><?=number_format($lr_credit-$lr_debit,0,',','.')?></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:right"><?=number_format($lr_credit-$lr_debit,0,',','.')?></td>
                                    </tr>
                                    <tr style="font-weight:bold">
                                        <td colspan="3">Total</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:right"><?=number_format($lr_debit+($lr_credit-$lr_debit),0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($lr_credit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($nrc_debit,0,',','.')?></td>
                                        <td style="text-align:right"><?=number_format($nrc_credit+($lr_credit-$lr_debit),0,',','.')?></td>
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