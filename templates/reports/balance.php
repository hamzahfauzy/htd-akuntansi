<?php if(!isset($_GET['print'])): ?>
<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Neraca</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen laporan Neraca</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('reports/balance',['print'=>1])?>" class="btn btn-secondary btn-round">Cetak</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-hover table-sales">
                                <table class="table table-bordered">
                                <?php else: ?>
                                <script>window.print()</script>
                                <div class="kop" style="width:100%;text-align:center">
                                    <img src="<?=asset(app('logo'))?>" alt="" width="100px" style="position:absolute;left:10px;top:10px;">
                                    <br>
                                    <h2 style="margin:0;padding:0"><?=app('name')?></h2>
                                    <p style="margin:0;padding:0"><?=nl2br(app('address'))?></p>
                                    <p style="margin:0;padding:0">HP : <?=app('phone')?>, Email : <?=app('email')?></p>
                                </div>
                                <hr>
                                <center><h3>LAPORAN NERACA</h3></center>
                                <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                <?php endif ?>
                                    <thead>
                                        <tr>
                                            <th>Akun</th>
                                            <td>Debet</td>
                                            <td>Kredit</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_debt = 0; 
                                        $total_credit = 0;
                                        foreach($aktiva as $index => $d): 
                                            $total_debt += $d->debt;
                                            $total_credit += $d->credit;
                                        ?>
                                        <tr <?=strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <td><?=$d->code.' '.$d->name?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->debt,0,',','.')?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->credit,0,',','.')?></td>
                                        </tr>
                                        <?php 
                                        endforeach; 
                                        $total_aktiva = $total_debt-$total_credit;
                                        ?>
                                        <tr>
                                            <td style="font-weight:bold">Sub Total</td>
                                            <td style="font-weight:bold"><?=number_format($total_debt,0,',','.')?></td>
                                            <td style="font-weight:bold"><?=number_format($total_credit,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Total Aktiva</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_aktiva,0,',','.')?></td>
                                        </tr>
                                    
                                        <?php 
                                        $total_debt = 0; 
                                        $total_credit = 0;
                                        foreach($hutang as $index => $d): 
                                            $total_debt += $d->debt;
                                            $total_credit += $d->credit;
                                        ?>
                                        <tr <?=strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <td><?=$d->code.' '.$d->name?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->debt,0,',','.')?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->credit,0,',','.')?></td>
                                        </tr>
                                        <?php 
                                        endforeach; 
                                        $total_hutang = $total_credit-$total_debt;
                                        ?>
                                        <tr>
                                            <td style="font-weight:bold">Sub Total</td>
                                            <td style="font-weight:bold"><?=number_format($total_debt,0,',','.')?></td>
                                            <td style="font-weight:bold"><?=number_format($total_credit,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Total Kewajiban</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_hutang,0,',','.')?></td>
                                        </tr>

                                        <?php 
                                        $total_debt = 0; 
                                        $total_credit = 0;
                                        foreach($modal as $index => $d): 
                                            $total_debt += $d->debt;
                                            $total_credit += $d->credit;
                                        ?>
                                        <tr <?=strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <td><?=$d->code.' '.$d->name?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->debt,0,',','.')?></td>
                                            <td><?=strtolower($d->balance_position) == 'header' ? '-' : number_format($d->credit,0,',','.')?></td>
                                        </tr>
                                        <?php 
                                        endforeach; 
                                        ?>
                                        <tr>
                                            <td style="font-weight:bold">Sub Total</td>
                                            <td style="font-weight:bold"><?=number_format($total_debt,0,',','.')?></td>
                                            <td style="font-weight:bold"><?=number_format($total_credit,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Laba Rugi</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($laba_rugi,0,',','.')?></td>
                                        </tr>
                                        <?php $total_modal = ($total_credit-$total_debt) + $laba_rugi; ?>
                                        <tr>
                                            <td style="font-weight:bold">Total Modal</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_modal,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Total Pasiva</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_hutang+$total_modal,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Neraca</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_aktiva-($total_hutang+$total_modal),0,',','.')?></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <?php if(isset($_GET['print'])): ?>
                                <table align="right">
                                    <tr>
                                        <td>
                                            <br><br>
                                            <center>
                                            <?=app('district')?>, <?=date('d F Y')?><br>
                                            <br><br><br><br>
                                            (<?=app('leader')?>)
                                            </center>
                                        </td>
                                    </tr>
                                </table>
                                <?php else: ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>
<?php endif ?>