<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Laporan Ringkasan</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Laporan Ringkasan</h5>
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
                            <form action="" class="d-flex" onsubmit="window.billData.draw(); return false">
                                <input type="hidden" name="table" value="bills">
                                <div class="form-group flex-fill">
                                    <label for="">Group</label><br>
                                    <select name="group" id="" class="form-control select2">
                                        <option value="">Pilih</option>
                                        <?php foreach($groups as $group): ?>
                                        <option value="<?=$group->id?>"><?=$group->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Merchant</label><br>
                                    <select name="merchant" id="" class="form-control select2">
                                        <option value="">Pilih</option>
                                        <?php foreach($merchants as $merchant): ?>
                                        <option value="<?=$merchant->id?>"><?=$merchant->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">&nbsp;</label><br>
                                    <button class="btn btn-primary btn-sm">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable-crud">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Group</th>
                                            <th>Merchant</th>
                                            <th>Deskripsi</th>
                                            <th>Total Tagihan</th>
                                            <th>Total Pembayaran</th>
                                            <th>Sisa</th>
                                            <th>Presentase</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>