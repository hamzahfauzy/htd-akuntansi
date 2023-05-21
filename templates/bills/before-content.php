<div class="row row-card-no-pd">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" class="d-flex justify-content-between" onsubmit="window.billData.draw(); return false">
                    <input type="hidden" name="table" value="bills">
                    <div class="form-group">
                        <label for="">Group</label><br>
                        <select name="group" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['groups'] as $group): ?>
                            <option value="<?=$group->id?>"><?=$group->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Subjek</label><br>
                        <select name="subject" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['subjects'] as $subject): ?>
                            <option value="<?=$subject->id?>"><?=$subject->code.' - '.$subject->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Merchant</label><br>
                        <select name="merchant" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['merchants'] as $merchant): ?>
                            <option value="<?=$merchant->id?>"><?=$merchant->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label><br>
                        <select name="status" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <option>LUNAS</option>
                            <option>BELUM LUNAS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">&nbsp;</label><br>
                        <button class="btn btn-primary btn-sm">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>