<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Panel Transaksi</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data transaksi</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('crud/index',['table'=>'transactions'])?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="d-flex mb-2">
                                    <div class="flex-grow-1 mr-2">
                                        <label for="">Subjek</label>
                                        <select name="subject_id" class="form-control select-subject" required></select>
                                    </div>
        
                                    <div class="flex-grow-1">
                                        <label for="">Kode Transaksi</label>
                                        <input type="text" name="transaction_code" class="form-control" value="TRX-<?=strtotime('now').(mt_rand(111,999)+mt_rand(111,999))?>">
                                    </div>
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Tagihan</th>
                                            <th>Jumlah</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="transaction-items">
                                            <td><button type="button" onclick="addItem()" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button></td>
                                            <td>
                                                <select name="bill_id[]" class="form-control select-bill" required></select>
                                            </td>
                                            <td>
                                                <input name="amount[]" type="number" class="form-control payment_value" required>
                                            </td>
                                            <td>
                                                <input name="description[]" type="text" class="form-control" required>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function addItem()
    {
        let r = (Math.random() + 1).toString(36).substring(7);
        var transactionItem = document.querySelector('.transaction-items:last-child')
        var html = `  <tr class="transaction-items" id="item-${r}">
                            <td><button type="button" onclick="removeItem('#item-${r}')" class="btn btn-danger btn-sm"><i class="fas fa-minus"></i></button></td>
                            <td>
                                <select name="bill_id[]" class="form-control select-bill" id="select-${r}" required></select>
                            </td>
                            <td>
                                <input name="amount[]" type="number" class="form-control payment_value-${r}" required>
                            </td>
                            <td>
                                <input name="description[]" type="text" class="form-control" required>
                            </td>
                        </tr>`
        var template = document.createElement('template');
        html = html.trim(); // Never return a text node of whitespace as the result
        template.innerHTML = html;
        transactionItem.parentNode.insertBefore(template.content.firstChild, transactionItem.nextSibling)

        $("#select-"+r).select2({
            theme: "bootstrap",
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '<?=routeTo('api/bills/lists')?>',
                dataType: "json",
                type: "GET",
                data: function (params) {

                    var subject_id = $(".select-subject").val()

                    var queryParameters = {
                        keyword: params.term,
                        subject_id:subject_id
                    }
                    return queryParameters;
                },
                templateSelection: function(container) {
					$(container.element).attr("data-remaining", container.remaining_payment);
					return container.text;
				},
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								text: item.bill_name,
								remaining_payment : item.remaining_payment,
								id: item.id
							}
						})
					};
				}
			}
		}).on("select2:select", function (e) {
            var remaining_payment = e.params.data.remaining_payment
			$('.payment_value-'+r).val(remaining_payment)
        });
    }

    function removeItem(target)
    {
        var item = document.querySelector(target)
        item.remove()
    }
    </script>
<?php load_templates('layouts/bottom') ?>