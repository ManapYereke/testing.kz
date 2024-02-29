<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars);
if (!$this->input->get('lang') || $this->input->get('lang') == 'kz') {
	$file = 'lang_kz.php';
	$l = 'kz';
} else {
	$file = 'lang_ru.php';
	$l = 'ru';
}
include __DIR__ . '/../shared/' . $file;
$id = $this->uri->segment(3);
$tb0101 = $this->db->query("SELECT tb0101_name1, tb0101_name2, tb0101_name3, tb0101_idn, tb0101_phone1, tb0101_tb0204_id FROM tb0101_users WHERE tb0101_id=$id")->row();
?>

<style>
	.container {
		text-align: center;
		font-family: 'Times New Roman', Times, serif;
	}

	.table {
		border-collapse: collapse;
		width: 100%;
	}

	.table thead th,
	.table td {
		border: 1px solid black;
		padding: 8px;
	}

	.col-4 {
		text-align: left;
	}

	.qr {
		text-align: left;
		display: inline-block;
		width: 100%;
	}

	.fio {
		position: relative;
		width: 80%;
		left: 0%;
	}

	.signature {
		position: relative;
		width: 18%;
		left: 82%;
		top:-10%;
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-4">
			<u><?= $lang["surname"] . ":" ?></u><?= " " . $tb0101->tb0101_name1 ?><br>
			<u><?= $lang["name"] . ":" ?></u><?= " " . $tb0101->tb0101_name2 ?><br>
			<u><?= $lang["fname"] . ":" ?></u><?= " " . $tb0101->tb0101_name3 ?><br>
			<u><?= $lang["idn"] . ":" ?></u><?= " " . $tb0101->tb0101_idn ?><br>
			<u><?= $lang["phone"] . ":" ?></u><?= " +" . $tb0101->tb0101_phone1 ?><br>
		</div>
		<div class="col-4"></div>
		<div class="col-4">
		</div>
	</div>
	<br><br>
	<div class="result"><b><?= $tb0101->tb0101_tb0204_id == 1 ? $lang["resultDiplom"] : $lang["resultAttestat"] ?></b></div><br>
	<table class="table">
		<thead>
			<tr>
				<th><?= $lang["positionExam"] ?></th>
				<th><?= $lang["isPassed"] ?></th>
				<th><?= $lang["dateExam"] ?></th>
				<th><?= $lang["comment"] ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>2</td>
				<td>3</td>
				<td>4</td>
			</tr>
			<?
			$sum = 0;
			foreach ($result as $r) {
				$sum += $r->v;
			?>
				<tr>
					<td><?= $r->{'tb0203_name_' . $l} ?></td>
					<td><?= $r->v >= $r->tb0301_min ? $lang["passed"] : $lang["notPassed"] ?></td>
					<td><?= date('d.m.Y') ?></td>
					<td><?= $lang["correctAnswers"] . $r->v ?><br><?= $lang["incorrectAnswers"] . $r->w ?></td>
				</tr>
			<? } ?>
		</tbody>
	</table>
	<br><br>
	<div class="qr">
		<div class="fio"><b><?= $lang['fio'] ?></b></div>
		<div class="signature"><?= $lang["signature"] ?></div>
	</div>
</div>

<? $this->load->view("shared/footer", $this->_ci_cached_vars); ?>