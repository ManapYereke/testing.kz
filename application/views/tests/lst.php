<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<? $this->load->view("shared/header", $this->_ci_cached_vars); ?>

<div class="container-fluid">
	<div class="container-lst">
		<div class="sidebar-container">
			<?php $this->load->view('shared/sidebar'); ?>
		</div>
		<div class="content-container">
			<div class="page-header animated slideInDown">
				<h1 class="text-muted discount-card-text">
					<i class="fas fa-list"></i>
					<?= $lang["tests"] ?>
				</h1>
			</div>

			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th><?= $lang["name_kz"] ?></th>
						<th><?= $lang["name_ru"] ?></th>
						<th><?= $lang["variant"] ?></th>
						<th><?= $lang["speciality"] ?></th>
						<th><?= $lang["test_type"] ?></th>
						<th><?= $lang["desc_kz"] ?></th>
						<th><?= $lang["desc_ru"] ?></th>
						<th><?= $lang["created_time"] ?></th>
						<th><?= $lang["created_by"] ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div><!-- container-fluid -->

<script type="text/javascript">
	ONLOAD.push(function() {
		const lang = new URLSearchParams(window.location.search).get("lang");
		$('.table').dataTable({
			'ajax': '<?= site_url($this->uri->segment(1) . "/lst") ?>'
				// ,'scrollY': '481px'
				// ,'scrollCollapse': true
				,
			'language': lang === "ru" ? DT_LANG_RU : DT_LANG_KZ,
			"order": [
				[1, 'desc']
			],
			initComplete: function() {
				$('.dataTables_length').prepend('<a class="btn btn-primary btn-sm" href="<?= site_url($this->uri->segment(1) . "/add") ?>"><i class="far fa-plus-square"></i> <?= $lang["add_test"] ?></a>&nbsp;&nbsp;&nbsp;');
			},
			"columns": [{
				"data": "tb0304_id",
				"className": "text-center"
			}, {
				"data": "tb0304_name_kz",
				"className": "text-center"
			}, {
				"data": "tb0304_name_ru",
				"className": "text-center"
			}, {
				"data": "tb0304_variant",
				"className": "text-center"
			}, {
				"data": "tb0304_0202_id",
				"className": "text-center"
			}, {
				"data": "tb0304_0203_id",
				"className": "text-center"
			}, {
				"data": "tb0304_desc_kz",
				"className": "text-center"
			}, {
				"data": "tb0304_desc_ru",
				"className": "text-center"
			}, {
				"data": "tb0304_created",
				"className": "text-center"
			}, {
				"data": "tb0304_createdby",
				"className": "text-center"
			}],
			"columnDefs": [{
				"targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
				"render": function(data, type, row) {
					if (!data) return "";
					return '<a href="<?= site_url($this->uri->segment(1) . "/edit") ?>/' + row.tb0304_id + '">' + data + '</a>';
				}
			}]
		}).on('draw.dt', function() {
			// if($('.dataTables_length .btn').length) return;
			// $('.dataTables_length').prepend('<a class="btn btn-primary btn-sm" href="<?= site_url($this->uri->segment(1) . "/add") ?>">Add a new record</a>&nbsp;&nbsp;&nbsp;')
		});
	});
</script>

<? $this->load->view("/shared/footer", $this->_ci_cached_vars) ?>