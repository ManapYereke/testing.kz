<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?$this->load->view("shared/header", $this->_ci_cached_vars);?>


<h1 class="display-4">Результаты</h1>
<hr>

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<!-- <th>ID</th> -->
			<th>ИИН</th>
			<th>Фамилия</th>
			<th>Имя</th>
			<th>Отчетство</th>
			<th>Начало теста</th>
			<th>Завершение теста</th>
			<th>Длительность теста (мин)</th>
			<th>Вербальный тест</th>
			<th>Числовой тест</th>
			<th>Логика</th>
			<th>Общий балл</th>
		</tr>
	</thead>
	<tbody>
<?
foreach($result as $r){
	$testStart = new DateTime($r->testStart);
	$testDue = $testStart->diff(new DateTime($r->testFinish));
	
	$minutes = $testDue->days * 24 * 60;
	$minutes += $testDue->h * 60;
	$minutes += $testDue->i;
	$minutes=!$r->testFinish?"":$minutes;

?>
		<tr>
			<!-- <td><?=$r->tb0101_id?></td> -->
			<td><?=str_pad($r->tb0101_idn,12,"0",STR_PAD_LEFT)?></td>
			<td><?=$r->tb0101_name1?></td>
			<td><?=$r->tb0101_name2?></td>
			<td><?=$r->tb0101_name3?></td>
			<td><?=$r->testStart?></td>
			<td><?=$r->testFinish?></td>
			<td><?=$minutes?></td>
			<td><?=$r->score2?></td>
			<td><?=$r->score3?></td>
			<td><?=$r->score4?></td>
			<td><?=$r->score2+$r->score3+$r->score4?></td>
		</tr>
<?}?>
	</tbody>
</table>

<script type="text/javascript">
	ONLOAD.push(function(){
		$('.table').dataTable( {
			'language': DT_LANG
		});
	});
</script>
<?$this->load->view("shared/footer", $this->_ci_cached_vars);?>
