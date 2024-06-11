<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars); ?>

<div class="container">
	<div class="step step0">
		<h1><?= $lang["home_h1"] ?></h1>

		<div class="alert alert-warning"><?= $lang["home_warning"] ?></div>
		<?
		$fields = [
			[
				"id" => "tb0101_idn", "type" => "string", "title" => $lang["idn"], "class" => "form-control", "mask" => "000 000 000 000", "desc" => "ИИН (вместо ФИО)", "required" => true, "placeholder" => "*** *** *** ***"
			], [
				"id" => "tb0101_phone1", "type" => "string", "title" => "Телефон", "class" => "form-control", "mask" => "+7 (000) 000-0000", "desc" => "Номер сотового телефона", "required" =>
				true, "placeholder" => "+7(___)___ __ __"
			], [
				"id" => "tb0101_name1", "type" => "string", "title" => $lang["surname"], "class" => "form-control", "required" =>
				true, "placeholder" => "Иванов"
			], [
				"id" => "tb0101_name2", "type" => "string", "title" => $lang["name"], "class" => "form-control", "required" =>
				true, "placeholder" => "Иван"
			], [
				"id" => "tb0101_name3", "type" => "string", "title" => $lang["fname"], "class" =>
				"form-control", "placeholder" => "Иванович"
			], [
				"id" => "tb0203_id", "type" => "dropdown", "title" => $lang["test_type"], "sql" => "SELECT * FROM tb0203_testtypes", "class" => "selectpicker w-100", "fieldId" => "tb0203_id", "fieldText" => "tb0203_name_" . $l, "required" => true
			], [
				"id" => "tb0202_id", "type" => "dropdown", "title" => $lang["speciality"], "sql" => "SELECT * FROM tb0202_specialities", "class" => "selectpicker w-100", "fieldId" => "tb0202_id", "fieldText" => "tb0202_name_" . $l, "required" => true, "data-filter-id" => true
			], [
				"id" => "tb0101_tb0002_id", "type" => "dropdown", "title" => $lang["lang"], "sql" => "SELECT * FROM tb0002_langs", "class" => "selectpicker w-100", "fieldId" => "tb0002_id", "fieldText" => "tb0002_name_ru", "desc" => "", "required" => true
			]
		];
		echo $this->html->formGroups($fields);
		?>

		<div class="text-center">
			<button type="button" onclick="testGo(this)" class="btn btn-primary btn-lg"><?= $lang['go'] ?></button>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {
		const tb0101_tb0304_id = $("#tb0101_tb0304_id");

		$('#tb0202_id, #tb0203_id').change(function() {
			const tb0202_id = $('#tb0202_id').val();
			const tb0203_id = $('#tb0203_id').val();
			if (tb0202_id && tb0203_id) {
				$.ajax({
					url: "<?= site_url("main/home") ?>",
					method: 'POST',
					data: {
						tb0202_id,
						tb0203_id,
						lang: "<?= $l ?>"
					},
					success: function(response) {
						var options = JSON.parse(response);
						tb0101_tb0304_id.empty();
						$.each(options, function(index, option) {
							tb0101_tb0304_id.append($('<option>', {
								value: option.value,
								text: option.text
							}));
						});
						tb0101_tb0304_id.selectpicker('refresh');
					},

					error: function(xhr, status, error) {
						console.error(error);
					}
				});
			}
		});
	});

	function testGo(o) {
		var d = {
			tb0101_idn: $(o).parents(".step").find("#tb0101_idn").unmask().val(),
			tb0101_name1: $(o).parents(".step").find("#tb0101_name1").val(),
			tb0101_name2: $(o).parents(".step").find("#tb0101_name2").val(),
			tb0101_name3: $(o).parents(".step").find("#tb0101_name3").val(),
			tb0101_phone1: $(o).parents(".step").find("#tb0101_phone1").unmask().val(),
			tb0101_tb0002_id: $(o).parents(".step").find("#tb0101_tb0002_id").val(),
			tb0202_id: $(o).parents(".step").find("#tb0202_id").val(),
			tb0203_id: $(o).parents(".step").find("#tb0203_id").val(),
		}

		$(o).parents(".step").find("#tb0101_idn").mask("000 000 000 000")
		$(o).parents(".step").find("#tb0101_phone1").mask("+7 (000) 000-0000")

		if (!d.tb0101_idn) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["idnError"] ?>'
			});
			return;
		}

		if (!d.tb0101_name1 || !d.tb0101_name2) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["fioError"] ?>'
			});
			return;
		}

		if (!d.tb0101_phone1) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["phoneError"] ?>'
			});
			return;
		}

		if (!d.tb0101_tb0002_id) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["langError"] ?>'
			});
			return;
		}

		if (!d.tb0202_id) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["positionError"] ?>'
			});
			return;
		}

		if (!d.tb0203_id) {
			Swal.fire({
				icon: 'error',
				title: '<?= $lang["error"] ?>',
				text: '<?= $lang["serviceError"] ?>'
			});
			return;
		}

		sendAsPost({
			url: "<?= site_url("test/start") ?>",
			data: d,
			title: "Начало теста"
		});

		$("#tb0101_idn").mask("000 000 000 000");
	}
</script>
<? $this->load->view("shared/footer", $this->_ci_cached_vars); ?>