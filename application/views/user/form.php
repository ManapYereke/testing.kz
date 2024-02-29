<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?
$tb0101=$this->session->userdata("tb0101");

$readOnly=isset($readOnly)?$readOnly:false;
$cmdSegment=2;
$fields=[
	[
		"id"=>"tb0102_id"
		,"type"=>"hidden"
		,"iskey"=>true
	]	
	,[
		"id"=>"tb0102_tb0101_id"
		,"type"=>"hidden"
		,"value"=>$tb0101->tb0101_idn
	]	
	,[
		"id"=>"tb0102_idn"
		,"type"=>"text"
		,"title"=>"БИН"
		,"class"=>"form-control"
		,"desc"=>"БИН школы"
		,"required"=>true
	]
	,[
		"id"=>"tb0102_name_ru"
		,"type"=>"string"
		,"title"=>"Название"
		,"class"=>"form-control"
		,"desc"=>"Название школы на русском"
		,"required"=>true
	]
	,[
		"id"=>"tb0102_addr"
		,"type"=>"string"
		,"title"=>"Адрес"
		,"class"=>"form-control"
		,"desc"=>"Адрес школы на русском"
		,"required"=>true
	]
];
echo $this->html->formGroups($fields);
// $params=array("fields"=>$fields);
// $params=array_merge($params,$this->_ci_cached_vars);
// $this->load->view("shared/form",$params);
?>