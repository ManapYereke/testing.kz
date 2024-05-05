<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?
$tb0101 = $this->session->userdata("tb0101");

$readOnly = isset($readOnly) ? $readOnly : false;
$cmdSegment = 2;

$subtests = $this->db->get_where("tb0305_tests_subtests", ["tb0305_0304_id" => $tb0304_id])->result_array();
$subtest_ids = [];
foreach ($subtests as $subtest) {
	// Добавляем значение поля tb0305_0301_id в массив $subtest_ids
	$subtest_ids[] = $subtest['tb0305_0301_id'];
}

$fields = [
	[
		"id" => "tb0304_id", "type" => "hidden", "iskey" => true
	], [
		"id" => "tb0304_name_kz", "type" => "text", "title" => "{$lang['name_kz']}", "class" => "form-control", "desc" => "Название субтеста на русском", "required" => true
	], [
		"id" => "tb0304_name_ru", "type" => "text", "title" => "{$lang['name_ru']}", "class" => "form-control", "desc" => "Название субтеста на русском", "required" => true
	], [
		"id" => "tb0304_variant", "type" => "text", "mask" => "00", "title" => "{$lang['variant']}", "class" => "form-control", "desc" => "Порядковый номер правильного ответа", "required" => true
	], [
		"id" => "tb0304_0202_id", "type" => "dropdown", "title" => "{$lang['speciality']}", "sql" => "SELECT * FROM tb0202_specialities", "class" => "selectpicker w-100", "fieldId" => "tb0202_id", "fieldText" => "tb0202_name_$l", "desc" => "", "required" => true
	], [
		"id" => "tb0304_0203_id", "type" => "dropdown", "title" => "{$lang['test_type']}", "sql" => "SELECT * FROM tb0203_testtypes", "class" => "selectpicker w-100", "fieldId" => "tb0203_id", "fieldText" => "tb0203_name_$l", "desc" => "", "required" => true
	], [
		"id" => "tb0304_desc_kz", "type" => "textarea", "title" => "{$lang['desc_kz']}", "class" => "form-control", "desc" => "Название субтеста на русском", "required" => true
	], [
		"id" => "tb0304_desc_ru", "type" => "textarea", "title" => "{$lang['desc_ru']}", "class" => "form-control", "desc" => "Название субтеста на русском", "required" => true
	], [
		"id" => "tb0305_0301_id", "type" => "dropdown", "title" => "{$lang['subtests']}", "sql" => "SELECT * FROM tb0301_subtests", "class" => "selectpicker w-100", "fieldId" => "tb0301_id", "fieldText" => "tb0301_name_$l", "desc" => "", "required" => true, "multiple" => true
	]
];
echo $this->html->formGroups($fields);
?>
<script>
	//document.getElementById("tb0305_0301_id").value = <?= json_encode($subtest_ids) ?>;
	ONLOAD.push(function() {
		console.log('djhb')
		$('#tb0305_0301_id').val(<?= json_encode($subtest_ids) ?>)
	})
</script>