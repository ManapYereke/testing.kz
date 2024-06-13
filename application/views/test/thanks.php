<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars); ?>

<div class="container">
	<div class="alert alert-success text-center"><?= $lang["testing_finished"] ?></div>

	<h1 class="display-4 text-center"><?= $lang["result"] ?></h1>
	<hr>

	<div class="row">
		<div class="col-3"></div>
		<div class="col-6">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Субтест</th>
						<th><?= $lang["correctAnswers"] ?></th>
						<th><?= $lang["incorrectAnswers"] ?></th>
						<th><?= $lang["question_count"] ?></th>
					</tr>
				</thead>
				<tbody>
					<?
					$sumv = 0;
					$sum = 0;
					foreach ($result as $r) {
						$sumv += $r->v;
						$sum += $r->c;
					?>
						<tr>
							<td><?= $l == "ru" ? $r->tb0301_name_ru : $r->tb0301_name_kz ?></td>
							<td><?= $r->v ?></td>
							<td><?= $r->c - $r->v ?></td>
							<td><?= $r->c ?></td>
						</tr>
					<? } ?>
				</tbody>
				<tfoot>
					<tr>
						<th><?= $lang["total"] ?></th>
						<th><?= $sumv ?></th>
						<th><?= $sum - $sumv ?></th>
						<th><?= $sum ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<? $this->load->view("shared/footer", $this->_ci_cached_vars); ?>