<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?$this->load->view("shared/header", $this->_ci_cached_vars);?>

<div class="container pt-3">
	<h1>Смена пароля</h1>
	
	<div class="row">
		<div class="col-4 offset-md-4">
			<?if(@$passOk){?>
				<div class="alert alert-success">Пароль успешно установлен.</div>
			<?}?>
			<?if(@$ex){?>
				<div class="alert alert-danger"><?=$ex?></div>
			<?}?>
			<form method="post">
				<input type="hidden" name="tb0101_idn" value="<?=$this->uri->segment(3)?>">
				<div class="form-group">
					<label for="passwd1">Пароль</label>
					<input type="password" class="form-control form-control-lg text-center" name="passwd1">
				</div>

				<div class="form-group">
					<label for="passwd2">Пароль (повтор)</label>
					<input type="password" class="form-control form-control-lg text-center" name="passwd2">
				</div>

				<br>
				<div class="text-center">
					<button class="btn btn-success btn-lg">Установить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?$this->load->view("shared/footer", $this->_ci_cached_vars);?>
