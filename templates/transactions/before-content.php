<div class="row row-card-no-pd">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" class="d-flex" onsubmit="window.trxData.draw(); return false">
                    <div class="form-group w-100">
                        <input type="hidden" name="table" value="bills">
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
    var startAt = document.querySelector('input[name=start_at]').value
    var endAt = document.querySelector('input[name=end_at]').value
    var search = trxData.search()
    const url = new URLSearchParams({
        start_at: startAt ?? '',
        end_at: endAt ?? '',
        search: search ?? '',
    }).toString();

    window.location = "<?=routeTo('transactions/download')?>?"+url
}
</script>