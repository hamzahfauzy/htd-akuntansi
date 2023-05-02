<footer class="footer">
        <div class="container-fluid">
            <nav class="pull-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Z-Techno
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright ml-auto">
                Copyright &copy; 2021, made with <i class="fa fa-heart heart text-danger"></i> by <a href="#">Z-Techno</a>
            </div>				
        </div>
    </footer>
</div>
<!-- End Custom template -->
</div>
	<!--   Core JS Files   -->
	<script src="<?=asset('assets/js/core/jquery.3.2.1.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/popper.min.js')?>"></script>
	<script src="<?=asset('assets/js/core/bootstrap.min.js')?>"></script>

	<!-- jQuery UI -->
	<script src="<?=asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')?>"></script>
	<script src="<?=asset('assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


	<!-- jQuery Scrollbar -->
	<script src="<?=asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')?>"></script>


	<!-- Chart JS -->
	<script src="<?=asset('assets/js/plugin/chart.js/chart.min.js')?>"></script>

	<!-- jQuery Sparkline -->
	<script src="<?=asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')?>"></script>

	<!-- Chart Circle -->
	<script src="<?=asset('assets/js/plugin/chart-circle/circles.min.js')?>"></script>

	<!-- Datatables -->
	<script src="<?=asset('assets/js/plugin/datatables/datatables.min.js')?>"></script>

	<!-- Bootstrap Notify -->
	<script src="<?=asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')?>"></script>

	<!-- jQuery Vector Maps -->
	<script src="<?=asset('assets/js/plugin/jqvmap/jquery.vmap.min.js')?>"></script>
	<script src="<?=asset('assets/js/plugin/jqvmap/maps/jquery.vmap.world.js')?>"></script>

	<!-- Sweet Alert -->
	<script src="<?=asset('assets/js/plugin/sweetalert/sweetalert.min.js')?>"></script>
	
	<script src="<?=asset('assets/js/plugin/select2/select2.full.min.js')?>"></script>

	<!-- Atlantis JS -->
	<script src="<?=asset('assets/js/atlantis.min.js')?>"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<?php if(get_route() == 'default/index'): ?>
	<script src="<?=asset('assets/js/setting-demo.js')?>"></script>
	<script src="<?=asset('assets/js/demo.js')?>"></script>
	<?php endif ?>
	<script src="<?=asset('assets/js/plugin/datatables-pagingtype/full_numbers_no_ellipses.js')?>"></script>
	<script>
		<?php if(get_route() == 'crud/index' && $_GET['table'] == 'bills'): ?>
		window.billData = $('.datatable-crud').DataTable({
			stateSave:true,
			pagingType: 'full_numbers_no_ellipses',
			processing: true,
			// searching: false,
			search: {
				return: true
			},
			serverSide: true,
			ajax: {
				url: location.href,
				data: function(data){
					// Read values
					var group = $('select[name=group]').val()
					var subject = $('select[name=subject]').val()
					var merchant = $('select[name=merchant]').val()
					var status = $('select[name=status]').val()

					// Append to data
					data.searchByGroup = group
					data.searchBySubject = subject
					data.searchByMerchant = merchant
					data.searchByStatus = status
				}
			}
		})
		<?php else: ?>
		$('.datatable-crud').dataTable({
			stateSave:true,
			pagingType: 'full_numbers_no_ellipses',
			processing: true,
			search: {
				return: true
			},
			serverSide: true,
			ajax: location.href
		})
		<?php endif ?>
		$('.datatable').dataTable();
		$('[name="bills[subject_id]"]').select2({
			allowClear: true,
			placeholder: 'Pilih'
		});
		$(".select2").select2({
			allowClear: true,
			placeholder: 'Pilih'
		});
		<?php if(get_route() == 'default/index'): ?>
		Circles.create({
			id:'circles-1',
			radius:45,
			value:<?=$subjects?>,
			maxValue:<?=$subjects?>,
			width:7,
			text: <?=$subjects?>,
			colors:['#f1f1f1', '#FF9E27'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-2',
			radius:45,
			value:<?=$masters?>,
			maxValue:<?=$masters?>,
			width:7,
			text: <?=$masters?>,
			colors:['#f1f1f1', '#2BB930'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		Circles.create({
			id:'circles-3',
			radius:45,
			value:<?=$accounts?>,
			maxValue:<?=$accounts?>,
			width:7,
			text: <?=$accounts?>,
			colors:['#f1f1f1', '#F25961'],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})

		var totalIncomeChart = document.getElementById('totalIncomeChart').getContext('2d');

		var mytotalIncomeChart = new Chart(totalIncomeChart, {
			type: 'bar',
			data: {
				labels: ["S", "M", "T", "W", "T", "F", "S", "S", "M", "T"],
				datasets : [{
					label: "Total Income",
					backgroundColor: '#ff9e27',
					borderColor: 'rgb(23, 125, 255)',
					data: [6, 4, 9, 5, 4, 6, 4, 3, 8, 10],
				}],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					display: false,
				},
				scales: {
					yAxes: [{
						ticks: {
							display: false //this will remove only the label
						},
						gridLines : {
							drawBorder: false,
							display : false
						}
					}],
					xAxes : [ {
						gridLines : {
							drawBorder: false,
							display : false
						}
					}]
				},
			}
		});

		$('#lineChart').sparkline([105,103,123,100,95,105,115], {
			type: 'line',
			height: '70',
			width: '100%',
			lineWidth: '2',
			lineColor: '#ffa534',
			fillColor: 'rgba(255, 165, 52, .14)'
		});

		<?php endif ?>

		$(".select-subject").select2({
			theme: "bootstrap",
			width: '100%',
			minimumInputLength: 2,
			ajax: {
				url: '<?=routeTo('api/subjects/lists')?>',
				dataType: "json",
				type: "GET",
				data: function (params) {

					var queryParameters = {
						keyword: params.term
					}
					return queryParameters;
				},
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								text: item.subject_name,
								id: item.id
							}
						})
					};
				}
			}
		});

		$(".select-bill").select2({
			allowClear: true,
			placeholder: 'Pilih',
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
			$('.payment_value').val(remaining_payment)
        });

		<?php if(in_array(get_route(),['crud/create','crud/edit']) && (isset($_GET['table']) && $_GET['table'] == 'merchants')): ?>
		$("select").select2({
			allowClear: true,
			placeholder: 'Pilih',
			theme: "bootstrap",
			width: '100%',
			minimumInputLength: 2,
			ajax: {
				url: '<?=routeTo('api/accounts/lists')?>',
				dataType: "json",
				type: "GET",
				data: function (params) {

					var queryParameters = {
						keyword: params.term,
					}

					return queryParameters;
				},
				processResults: function (data) {
					return {
						results: $.map(data.data, function (item) {
							return {
								text: item.code+' '+item.name,
								id: item.id
							}
						})
					};
				}
			}
		});
		<?php endif ?>
	</script>
</body>
</html>
