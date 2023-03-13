<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Laba Rugi</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen laporan Laba Rugi</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        
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
                                        foreach($pendapatan as $index => $d): 
                                            $total_debt += $d->debt;
                                            $total_credit += $d->credit;
                                        ?>
                                        <tr <?=strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <td><?=$d->code.' '.$d->name?></td>
                                            <td><?=number_format($d->debt,0,',','.')?></td>
                                            <td><?=number_format($d->credit,0,',','.')?></td>
                                        </tr>
                                        <?php 
                                        endforeach; 
                                        $total_pendapatan = $total_credit-$total_debt;
                                        ?>
                                        <tr>
                                            <td style="font-weight:bold">Sub Total</td>
                                            <td style="font-weight:bold"><?=number_format($total_debt,0,',','.')?></td>
                                            <td style="font-weight:bold"><?=number_format($total_credit,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Total</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_pendapatan,0,',','.')?></td>
                                        </tr>
                                    
                                        <?php 
                                        $total_debt = 0; 
                                        $total_credit = 0;
                                        foreach($beban as $index => $d): 
                                            $total_debt += $d->debt;
                                            $total_credit += $d->credit;
                                        ?>
                                        <tr <?=strtolower($d->balance_position) == 'header' ? 'style="font-weight:bold"' : ''?>>
                                            <td><?=$d->code.' '.$d->name?></td>
                                            <td><?=number_format($d->debt,0,',','.')?></td>
                                            <td><?=number_format($d->credit,0,',','.')?></td>
                                        </tr>
                                        <?php 
                                        endforeach; 
                                        $total_beban = $total_debt-$total_credit;
                                        ?>
                                        <tr>
                                            <td style="font-weight:bold">Sub Total</td>
                                            <td style="font-weight:bold"><?=number_format($total_debt,0,',','.')?></td>
                                            <td style="font-weight:bold"><?=number_format($total_credit,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Total</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_beban,0,',','.')?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold">Laba Rugi</td>
                                            <td style="font-weight:bold" colspan="5"><?=number_format($total_pendapatan-$total_beban,0,',','.')?></td>
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