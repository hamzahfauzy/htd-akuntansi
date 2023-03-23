<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Administrator Dashboard</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('crud/index',['table'=>'reports'])?>" class="btn btn-white btn-border btn-round mr-2">Master Data</a>
                        <a href="<?=routeTo('crud/index',['table'=>'subjects'])?>" class="btn btn-secondary btn-round">Subjek</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row mt--2">
                <div class="col-md-6">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">Overall statistics</div>
                            <div class="card-category">Daily information about statistics in system</div>
                            <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="circles-1"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Subjek</h6>
                                </div>
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="circles-2"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Master Data</h6>
                                </div>
                                <?php if(activeMaster()): ?>
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="circles-3"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Akun</h6>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(activeMaster()): ?>
                <div class="col-md-6">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">Total Pendapatan & Beban</div>
                            <div class="row py-3">
                                <div class="col-md-6 d-flex flex-column justify-content-around">
                                    <div>
                                        <h6 class="fw-bold text-uppercase text-success op-8">Total Pendapatan</h6>
                                        <h3 class="fw-bold">Rp. <?=number_format($total_pendapatan)?></h3>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-uppercase text-danger op-8">Total Beban</h6>
                                        <h3 class="fw-bold">Rp. <?=number_format($total_beban)?></h3>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-uppercase text-success op-8">Laba Rugi</h6>
                                        <h3 class="fw-bold">Rp. <?=number_format($laba_rugi)?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom', $stats) ?>