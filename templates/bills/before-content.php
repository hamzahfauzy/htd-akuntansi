<div class="row row-card-no-pd">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" class="d-flex justify-content-between" onsubmit="window.billData.draw(); return false">
                    <div class="form-group w-100">
                        <input type="hidden" name="table" value="bills">
                        <label for="">Group</label><br>
                        <select name="group" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['groups'] as $group): ?>
                            <option value="<?=$group->id?>"><?=$group->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group w-100">
                        <label for="">Subjek</label><br>
                        <select name="subject" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['subjects'] as $subject): ?>
                            <option value="<?=$subject->id?>"><?=$subject->code.' - '.$subject->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group w-100">
                        <label for="">Merchant</label><br>
                        <select name="merchant" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <?php foreach($additional_data['merchants'] as $merchant): ?>
                            <option value="<?=$merchant->id?>"><?=$merchant->name?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group w-100">
                        <label for="">Status</label><br>
                        <select name="status" id="" class="form-control select2">
                            <option value="">Pilih</option>
                            <option>LUNAS</option>
                            <option>BELUM LUNAS</option>
                        </select>
                    </div>
                    <div class="form-group w-100">
                        <label for="">Tanggal Awal</label><br>
                        <input type="date" name="start_at" class="form-control">
                    </div>
                    <div class="form-group w-100">
                        <label for="">Tanggal Akhir</label><br>
                        <input type="date" name="end_at" class="form-control">
                    </div>
                    <div class="form-group w-100">
                        <label for="">&nbsp;</label><br>
                        <div class="d-flex">
                            <button class="btn btn-primary btn-sm w-100">Filter</button>
                            &nbsp;
                            <button type="button" class="btn btn-secondary btn-sm w-100" onclick="downloadReport()">Download</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function downloadReport()
{
    var group = document.querySelector('select[name=group]').value
    var subject = document.querySelector('select[name=subject]').value
    var merchant = document.querySelector('select[name=merchant]').value
    var status = document.querySelector('select[name=status]').value
    var startAt = document.querySelector('input[name=start_at]').value
    var endAt = document.querySelector('input[name=end_at]').value
    var search = billData.search()
    const url = new URLSearchParams({
        group: group ?? '',
        subject: subject ?? '',
        merchant: merchant ?? '',
        status: status ?? '',
        start_at: startAt ?? '',
        end_at: endAt ?? '',
        search: search ?? '',
    }).toString();

    window.location = "<?=routeTo('bills/download')?>?"+url
}
</script>