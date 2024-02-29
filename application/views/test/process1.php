<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?$this->load->view("shared/header", $this->_ci_cached_vars);?>

<?
$tb0101_id=$this->uri->segment(3);
?>

<!-- <h1><?=$this->session->userdata("lang")?></h1> -->
<div class="container">
	<form method="post" action="<?=site_url($this->uri->segment(1)."/save")?>">
		<input type="hidden" name="tb0101_id" id="tb0101_id" value="<?=$tb0101_id?>">
<?
$step=0;
$tb0301=$this->db->query("SELECT * FROM tb0301_subtests ORDER BY tb0301_order,tb0301_id")->result();
foreach ($tb0301 as $r1){
	$tb0302=$this->db->query("SELECT * FROM tb0302_questions WHERE tb0302_tb0301_id=?",[$r1->tb0301_id])->result();
	shuffle($tb0302);

	$i=0;
	$step++;
?>
<div id="tb0301-<?=$r1->tb0301_id?>" class="card mt-2 step step<?=$step?> <?=$step==1?"":"d-none"?>" step="<?=$step?>" tb0301_timelimit="<?=$r1->tb0301_timelimit?>">
	<h5 class="card-header"><?=$this->utils->t([$r1->tb0301_name_ru,$r1->tb0301_name_kz])?> <span class="badge badge-secondary">00:00</span></h5>
	<div class="card-body">
		<div class="intro">
			<?=$this->utils->t([$r1->tb0301_desc_ru,$r1->tb0301_desc_kz])?>
			<div class="text-center">
				<button onclick="stepStart(this)" type="button" class="btn btn-success btn-lg">НАЧАТЬ</button>
			</div>
		</div>
		<div class="questions d-none">
			<nav>
				<div class="nav nav-pills" id="nav-tab" role="tablist">
<?
foreach($tb0302 as $r2){
	$i++;
?>
					<a class="nav-item nav-link <?=$tb0302[0]==$r2?"active":""?>" id="tab-<?=$r2->tb0302_id?>-title" data-toggle="tab" href="#tab-<?=$r2->tb0302_id?>" role="tab" aria-selected="true"><?=$i?></a>
<?}?>
				</div>
			</nav>
			<div class="tab-content pt-2" id="nav-tabContent">
<?foreach($tb0302 as $r2){?>
				<div class="tab-pane fade show <?=$tb0302[0]==$r2?"active":""?>" id="tab-<?=$r2->tb0302_id?>" role="tabpanel">
					<div class="alert alert-info">
						<strong>Вопрос:</strong>
						<br><?=$this->utils->t([$r2->tb0302_desc_ru,$r2->tb0302_desc_kz])?>
					</div>

					<table class="table table-striped table-hover">
<?
for($x=1;$x<=5;$x++){
	$f="tb0302_answer".$x."_".$this->utils->l();
	if(!$r2->$f) continue;
?>
						<tr>
							<th width="1%"><input type="radio" name="tb0302_id-<?=$r2->tb0302_id?>" value="<?=$x?>"></th>
							<td>
								<?=$r2->$f?>
							</td>
						</tr>
<?}?>
					</table>
				</div>
<?}?>
			</div>
		</div>

	</div>
	<div class="card-footer text-right d-none">
		<!-- <button id="btGoToNextSubTest" type="button" onclick="stepEnd(<?=$step+1?>)" class="btn btn-secondary d-none">Завершить задание и перейти к следующему</button> -->
		<button id="btGoToNextSubTest" type="button" onclick="stepEnd(<?=$step+1?>)" class="btn btn-secondary <?=$tb0301[count($tb0301)-1]==$r1?"d-none":""?>">Следующий блок вопросов</button>
        <button id="btGoToNextQuestion" type="button" onclick="nextQuestion(<?=$step+1?>)" class="btn btn-secondary <?=$tb0301[count($tb0301)-1]==$r1?"d-none":""?>">Следующий вопрос</button>
		<button id="btGoToFinish" type="button" onclick="finish()" class="btn btn-warning <?=$tb0301[count($tb0301)-1]==$r1?"":"d-none"?>">Завершить тест</button>
		<!-- <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
			<button onclick="step(<?=$step-1?>)" type="button" class="btn btn-secondary <?=$tb0301[0]==$r1?"disabled":""?>">« Назад</button>
			<button onclick="step(<?=$step+1?>)" type="button" class="btn btn-secondary <?=$tb0301[count($tb0301)-1]==$r1?"disabled":""?>">Вперед »</button>
		</div> -->
	</div>
</div>
<?}?>
	</form>
</div>

<script>
	var STEPMAX=<?=$step?>;


	function finish(force=0)
	{
		if(force){
			$("form").submit();
			return;
		}

		Swal.fire({
			title: "Вы уверены?",
			text: "Вы уверены, что хотите завершить тест и отправить ответы?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: "Да, уверен (-а)!",
			cancelButtonText: "Отменить!"
		}).then((result) => {
			if (result.value) {
				$("form").submit();
			} 
			else Swal.fire({icon:'error',title:"Отменено",text:"Завершение отменено."});
		});
	}

	var countDown;
	function stepStart(o)
	{
		var card=$(o).parents(".card");
		card.find(".intro").hide();
		card.find(".questions").removeClass("d-none").show();
		card.find(".card-footer").removeClass("d-none").show();

		var step=parseInt(card.attr("step"));

		// Set the date we're counting down to
		var tb0301_timelimit = card.attr("tb0301_timelimit")+":00";
		card.find(".card-header").find(".badge").text(tb0301_timelimit);

		// Update the count down every 1 second
		countDown = setInterval(function() {
			var timer = card.find(".card-header").find(".badge").text().split(':');

			//by parsing integer, I avoid all extra string processing
			var minutes = parseInt(timer[0], 10);
			var seconds = parseInt(timer[1], 10);
			--seconds;
			minutes = (seconds < 0) ? --minutes : minutes;
			minutes = (minutes < 10) ? '0' + minutes : minutes;

			seconds = (seconds < 0) ? 59 : seconds;
			seconds = (seconds < 10) ? '0' + seconds : seconds;
			//minutes = (minutes < 10) ?  minutes : minutes;
			$('.countdown').html(minutes + ':' + seconds);

			card.find(".card-header").find(".badge").text(minutes + ':' + seconds);

			// If the count down is finished, write some text 

			if (minutes<=0&&seconds<=0)
			{
				clearInterval(countDown);
				if(step!=STEPMAX)
				{
					console.log("[GOTO NEXT STEP / FORCED] "+(step+1)+" / "+STEPMAX);
					stepEnd(step+1,1);
				}
				else finish(1);
			}
		}, 1000);
	}

	function stepEnd(x,force=0)
	{
		if(force){
			$(".step").hide().removeClass("d-none");
			$(".step"+x).show();
			return;
		}
		Swal.fire({
			title: "Вы уверены?",
			text: "Вы уверены, что хотите завершить текущее задание и перейти дальше?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: "Да, уверен (-а)!",
			cancelButtonText: "Отменить!"
		}).then((result) => {
			if (result.value) {
				$(".step").hide().removeClass("d-none");
				$(".step"+x).show();
			} 
			else Swal.fire({icon:'error',title:"Отменено",text:"Завершение отменено."});
		});

		return false;
	}
    function nextQuestion(q){
        $(".fade").hide();
			$(".fade show").show();
			return;
    }
</script>
<?$this->load->view("shared/footer", $this->_ci_cached_vars);?>
