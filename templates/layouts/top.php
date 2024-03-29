<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?=get_title()?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="<?=asset('assets/img/main-logo.png')?>" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="<?=asset('assets/js/plugin/webfont/webfont.min.js')?>"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['<?=asset('assets/css/fonts.min.css')?>']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?=asset('assets/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?=asset('assets/css/atlantis.min.css')?>">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?=asset('assets/css/select2-bootstrap.min.css')?>">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="<?=asset('assets/css/demo.css')?>">
	<style>
    .table>tbody>tr>td,.table>thead>tr>th {
        padding:5px !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
        height: auto !important;
    }
	.datatable-crud tbody tr td {
		white-space: nowrap;
	}
	.form-control {
		padding:.375rem .75rem !important;
	}
	<?php if(get_route() == 'crud/index' && $_GET['table'] == 'bills'): ?>
	.select2-selection__rendered {
	    line-height: 33px !important;
	}
	.select2-selection__clear {
	    line-height: 30px !important;
	}
	.select2-container .select2-selection--single {
		height: 33px !important;
	}
	.select2-selection__arrow {
		height: 33px !important;
	}
	<?php endif ?>
    </style>
</head>
<body>
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="<?=config('theme')['header_color']?>">
				
				<a href="index.php" class="logo text-white">
					<?=app('name')?>
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="<?=config('theme')['top_navbar_color']?>">
				
				<div class="container-fluid">
					<div class="collapse" id="search-nav">
						<form class="navbar-left navbar-form nav-search mr-md-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pr-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control">
							</div>
						</form>
					</div>
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						<li class="nav-item toggle-nav-search hidden-caret">
							<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="<?=asset('assets/img/user-placeholder.png')?>" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="<?=asset('assets/img/user-placeholder.png')?>" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4><?=auth()->user->name?></h4>
												<p class="text-muted"><?=get_role(auth()->user->id)->name?></p>
												<a href="<?=routeTo('default/profile')?>" class="btn btn-xs btn-success btn-sm">Profil</a>
												<a href="<?=routeTo('auth/logout')?>" class="btn btn-xs btn-secondary btn-sm">Log Out</a>
											</div>
										</div>
									</li>
								</div>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<?php load_templates('layouts/sidebar') ?>
		<div class="main-panel">