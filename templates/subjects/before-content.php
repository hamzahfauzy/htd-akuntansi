<div class="row row-card-no-pd">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="" class="d-flex justify-content-between" onsubmit="window.subjectData.draw(); return false">
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
                        <label for="">&nbsp;</label><br>
                        <button class="btn btn-primary btn-sm w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>