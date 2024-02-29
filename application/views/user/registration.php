<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?$this->load->view("shared/header", $this->_ci_cached_vars);?>

<div class="container pt-3">
	<h1>Регистрация</h1>
	<?if(@$ex){?>
		<div class="alert alert-danger">
			<?=$ex->getMessage()?>
		</div>
	<?}?>

	<nav>
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<a class="nav-item nav-link active" id="tab-student-title" data-toggle="tab" href="#tab-student" role="tab" aria-controls="tab-student" aria-selected="true">Ученик</a>
			<a class="nav-item nav-link" id="tab-teacher-title" data-toggle="tab" href="#tab-teacher" role="tab" aria-controls="tab-teacher" aria-selected="false">Преподаватель</a>
			<a class="nav-item nav-link" id="tab-director-title" data-toggle="tab" href="#tab-director" role="tab" aria-controls="tab-director" aria-selected="false">Директор</a>
		</div>
	</nav>
	<div class="tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="tab-student" role="tabpanel" aria-labelledby="tab-student">
			<form method="post">
				<?$this->load->view($this->uri->segment(1)."/_student", $this->_ci_cached_vars);?>

				<div class="text-center pt-2">
					<button type="submit" class="btn btn-primary btn-lg">Отправить</button>
				</div>
			</form>
		</div>
		<div class="tab-pane fade" id="tab-teacher" role="tabpanel" aria-labelledby="tab-teacher">
			<form method="post">
				<?$this->load->view($this->uri->segment(1)."/_teacher", $this->_ci_cached_vars);?>

				<div class="text-center pt-2">
					<button type="submit" class="btn btn-primary btn-lg">Отправить</button>
				</div>
			</form>
		</div>
		<div class="tab-pane fade" id="tab-director" role="tabpanel" aria-labelledby="tab-director">
			<form method="post">
				<?$this->load->view($this->uri->segment(1)."/_director", $this->_ci_cached_vars);?>

				<div class="text-center pt-2">
					<button type="submit" class="btn btn-primary btn-lg">Отправить</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- 
<script type="text/javascript">
	ONLOAD.push(function(){
		$("input[name=tb0101_tb0003_id]").click(function(){
			$("#tab-student,#tab-teacher,#tab-director").hide().removeClass("d-none");
			
			var v=$(this).val();
			if(v=="11") $("#tab-student").show();
			if(v=="12") $("#tab-teacher").show();
			if(v=="13") $("#tab-director").show();
		});
		$("#tb0101_tb0003_id<?=@$_POST["tb0101_tb0003_id"]?$_POST["tb0101_tb0003_id"]:1?>").click();
	})
</script> -->
<?$this->load->view("shared/footer", $this->_ci_cached_vars);?>
