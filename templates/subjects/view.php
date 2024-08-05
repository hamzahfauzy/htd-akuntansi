<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Subjek</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data subjek</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('crud/index',['table'=>'subjects'])?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Informasi Subjek</h4>
                            <div class="information-group">
                                <div class="form-group">
                                    <label for="">Kode</label>
                                    <p><?=$subject->code?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <p><?=$subject->name?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <p><?=$subject->address?></p>
                                </div>
                                <div class="form-group">
                                    <label for="">No. HP</label>
                                    <p><a href="tel:<?=$subject->phone?>"><?=$subject->phone?></a></p>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <p><?=$subject->email?></p>
                                </div>
                            </div>
                            <hr>
                            <h4 class="mt-5">Informasi Grup</h4>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($subject->groups as $index => $group): ?>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td><?=$group->name?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h4 class="mt-5">Informasi Master Tagihan</h4>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Merchant</th>
                                            <th>Jumlah</th>
                                            <th>Jumlah Tagihan</th>
                                            <th>Total</th>
                                            <th>Terbayar</th>
                                            <th>Sisa Tagihan</th>
                                            <th>Sisa Bill</th>
                                            <th>Tanggal Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($subject->billMaster as $index => $bill): ?>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td><?=$bill->merchant_name?></td>
                                            <td><?=number_format($bill->base_amount)?></td>
                                            <td><?=number_format($bill->num_of_bills)?></td>
                                            <td><?=number_format($bill->total_amount)?></td>
                                            <td><?=number_format($bill->bill_pay)?></td>
                                            <td><?=number_format($bill->actual_remaining_payment)?></td>
                                            <td><?=number_format($bill->bill_remaining_payment)?></td>
                                            <td><?=$bill->start_at?> s/d <?=$bill->end_at?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h4 class="mt-5">Informasi Tagihan</h4>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Merchant</th>
                                            <th>Kode</th>
                                            <th>Jumlah</th>
                                            <th>Sisa Pembayaran</th>
                                            <th>Deskripsi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($subject->bills as $index => $bill): ?>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td><?=$bill->merchant_name?></td>
                                            <td><?=$bill->bill_code?></td>
                                            <td><?=number_format($bill->amount)?></td>
                                            <td><?=number_format($bill->remaining_payment)?></td>
                                            <td><?=$bill->description?></td>
                                            <td><?=$bill->status?></td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h4 class="mt-5">Informasi Transaksi</h4>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Merchant</th>
                                            <th>Kode</th>
                                            <th>Jumlah</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($subject->transactions as $index => $transaction): ?>
                                        <tr>
                                            <td><?=$index+1?></td>
                                            <td><?=$transaction->merchant_name?></td>
                                            <td><?=$transaction->transaction_code?></td>
                                            <td><?=number_format($transaction->amount)?></td>
                                            <td><?=$transaction->description?></td>
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