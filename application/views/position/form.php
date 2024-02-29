<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?
$tb0101=$this->session->userdata("tb0101");

$readOnly=isset($readOnly)?$readOnly:false;
$cmdSegment=2;

$fields=[
	[
		"id"=>"tb0203_id"
		,"type"=>"hidden"
		,"iskey"=>true
	], [
		"id"=>"tb0203_name_kz"
		,"type"=>"text"
		,"title"=> "Қызметтің қазақша атауы"
		,"class"=>"form-control"
		,"desc"=> "Қызметтің қазақша атауы"
		,"required"=>true
	], [
		"id" => "tb0203_name_ru"
		, "type" => "text"
		, "title" => "Название должности на русском"
		, "class" => "form-control"
		, "desc" => "Название должности на русском"
		, "required" => true
	], [
		"id" => "tb0203_speciality_id", 
		"type" => "dropdown", 
		"title" => "Специальность", 
		"sql" => "SELECT * FROM tb0202_specialities", 
		"class" => "selectpicker w-100", 
		"fieldId" => "tb0202_id", 
		"fieldText" => "tb0202_name_ru", 
		"desc" => "", 
		"required" => true
    ]
];
echo $this->html->formGroups($fields);
?>

<script type="text/javascript">
	ONLOAD.push(function(){
		$('textarea').summernote({
			callbacks: {
				onImageUpload: function(files) {
					$summernote=this;
					SummernoteImageUpload(files[0]);
				}
			}
		});
	})
</script>