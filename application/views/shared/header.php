<?
		if (!$this->input->get('lang') || $this->input->get('lang') == 'kz')
			$file = 'lang_kz.php';
		else
			$file = 'lang_ru.php';
		include __DIR__ . '/' . $file; ?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/ie.css">
<![endif]-->

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/fc-3.3.0/fh-3.1.6/datatables.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.12/css/bootstrap-select.min.css" integrity="sha256-l3FykDBm9+58ZcJJtzcFvWjBZNJO40HmvebhpHXEhC0=" crossorigin="anonymous" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<script>
		var ONLOAD = []
	</script>

</head>

<body style="padding-top: 70px; padding-bottom: 20px">
	<nav class="navbar navbar-expand-md fixed-top navbar-light bg-light">
		<a class="navbar-brand" href="/">
			<img src="https://gov4c.kz/public/img/logo.png" alt="logo.png" height="40" style="margin-top: -15px;">
			<span class="navbar-text" style="font-size: 12px; padding: 0px"><?= $lang['nao'] ?></span>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<form method="GET" id="formLanguage" class="navbar-nav ml-auto">
			<select class="form-control" name="lang" onchange="$('#formLanguage').submit()">
				<option value="kz" <?= $this->input->get('lang') == 'kz' ? "selected" : "" ?>>ҚАЗ</option>
				<option value="ru" <?= $this->input->get('lang') == 'ru' ? "selected" : "" ?>>РУС</option>
			</select>
		</form>
		<?
		if (@$tb0101->tb0101_id) { ?>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?= $tb0101->tb0101_name2 ?> <?= $tb0101->tb0101_name1 ?>
					</a>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?= site_url("user/passwd") ?>">Сменить пароль</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?= site_url("user/logout") ?>">Выход</a>
					</div>
				</li>
			</ul>
		<? }
		?>
	</nav>
	<div class="container-fluid">