<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?$this->load->view("/shared/header",$this->_ci_cached_vars)?>

<div class="container-fluid">
	<div class="page-header animated slideInDown">
		<h1 class="text-muted discount-card-text">
			<i class="far fa-plus-square"></i> Добавить вид обучения
		</h1>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="container">
				<form id="dataform" onkeypress="return event.keyCode!=13;" enctype="multipart/form-data">
					<?$this->load->view($this->uri->segment(1)."/form",$this->_ci_cached_vars)?>
					<hr>
					<div class="form-group">
						<a class="btn btn-secondary" href="<?=site_url($this->uri->segment(1))?>">
							<i class="fas fa-undo-alt"></i> Отменить и вернуться 
						</a>
						<button class="btn btn-success" type="button" onclick="save(this,2)">
							<i class="far fa-save"></i> Сохранить и перейти к списку
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var dosave=0;

	function save(btn,type){
		dosave = type;
		$(btn).attr("disabled","");
		$("#dataform").submit();
		setTimeout(function(){$(btn).removeAttr("disabled")},2000);
	}

	ONLOAD.push(function(){
		$("#dataform").submit(function(){
			// if(!$("#dataform").valid()) return false;

			sendAsPost({
				url: "<?=site_url($this->uri->segment(1)."/save")?>"
				,formData: new FormData(	this)
				,title: "Сохранение"
				,callbackOk: function(data){
					if(dosave==1)location.href="/<?=$this->uri->segment(1)?>/view/"+data.id+"?draft=1";
					if(dosave==2)location.href="<?=site_url($this->uri->segment(1))?>";
				}
			});
			return false;
		});
	});
</script>
<?$this->load->view("/shared/footer",$this->_ci_cached_vars)?>