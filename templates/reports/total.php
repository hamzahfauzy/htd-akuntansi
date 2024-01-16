<?php load_templates('layouts/top') ?>
    <style>.select2,.select2-selection{height:42px!important;} .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:42px!important;}.select2-selection__arrow{height:38px!important;}</style>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Laporan Total</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Laporan Total</h5>
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
                            <form action="" class="d-flex">
                                <div class="form-group flex-fill">
                                    <label for="">Tingkat Kelas</label><br>
                                    <select name="parent_group" id="parent_group" class="form-control" onchange="showChild(this.value)">
                                        <option value="">Semua</option>
                                        <?php foreach($parentGroups as $group): ?>
                                            <option value="<?=$group->id?>" <?=isset($_GET['parent_group']) && $_GET['parent_group'] == $group->id ? 'selected=""' : ''?>><?=$group->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Kelas</label><br>
                                    <select name="group" id="group" class="form-control">
                                        <option value="">Semua</option>
                                        <?php foreach($groups as $group): ?>
                                            <option value="<?=$group->id?>" class="parent-<?=$group->parent_id?>" <?=isset($_GET['group']) && $_GET['group'] == $group->id ? 'selected=""' : ''?> <?=isset($_GET['parent_group']) && !empty($_GET['parent_group']) && $_GET['parent_group'] != $group->parent_id ? 'style="display:none"' : ''?>><?=$group->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Waktu Awal</label><br>
                                    <input type="date" name="start_at" class="form-control" value="<?=isset($_GET['start_at']) ? $_GET['start_at'] : ''?>">
                                </div>
                                <div class="form-group flex-fill">
                                    <label for="">Waktu Akhir</label><br>
                                    <input type="date" name="end_at" class="form-control" value="<?=isset($_GET['end_at']) ? $_GET['end_at'] : ''?>">
                                </div>
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                    <button class="btn btn-primary">Tampilkan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <?php foreach($data as $d): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h3><?=$d->merchant_name?></h2>
                            <h4>Tagihan : Rp. <?=number_format($d->bills_total_amount, 0, ',', '.')?></h4>
                            <h4>Pembayaran : Rp. <?=number_format($d->bills_total_payment, 0, ',', '.')?></h4>
                            <h4>Sisa : Rp. <?=number_format($d->bills_total_remaining_payment, 0, ',', '.')?></h4>
                        </div>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <script>
    function showChild(parentId)
    {
        if(parentId)
        {
            document.querySelector('#group').querySelectorAll('option').forEach(opt => { opt.style.display = 'none' })
            document.querySelector('#group').querySelectorAll('.parent-'+parentId).forEach(opt => { opt.style.display = 'block' })
        }
        else
        {
            document.querySelector('#group').querySelectorAll('option').forEach(opt => { opt.style.display = 'block' })
        }
    }
    </script>
<?php load_templates('layouts/bottom') ?>