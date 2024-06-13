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
					<i class="fas fa-list"></i><?= $lang["users"] ?>
				</h1>
			</div>

			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th><?= $lang["idn"] ?></th>
						<th><?= $lang["group"] ?></th>
						<th><?= $lang["surname"] ?></th>
						<th><?= $lang["name"] ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tfoot>
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
				$('.dataTables_length').prepend('<a class="btn btn-primary btn-sm" href="<?= site_url($this->uri->segment(1) . "/add") ?>"><i class="far fa-plus-square"></i> <?= $lang["add_user"] ?></a>&nbsp;&nbsp;&nbsp;');
			}
		}).on('draw.dt', function() {
			// if($('.dataTables_length .btn').length) return;
			// $('.dataTables_length').prepend('<a class="btn btn-primary btn-sm" href="<?= site_url($this->uri->segment(1) . "/add") ?>">Add a new record</a>&nbsp;&nbsp;&nbsp;')
		});
	});
</script>

<? $this->load->view("/shared/footer", $this->_ci_cached_vars) ?>