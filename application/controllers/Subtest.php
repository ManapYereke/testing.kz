<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Subtest extends CI_Controller {
	var $data=[];
	var $CI;

	function __construct() {
		parent::__construct();

		$this->data["tb0101"]=$this->session->userdata("tb0101"); 
	}

	public function index()
	{ 
		$this->controller();
	}

	public function lst(){ $this->controller(); }

	public function add(){ $this->controller(); }

	public function edit(){ $this->controller(); }

	public function save(){ $this->controller(); }

	public function del($tb0301_id){
		$this->db->delete("tb0301_subtests", array("tb0301_id" => $tb0301_id));
		$this->db->delete("tb0302_questions", array("tb0302_tb0301_id" => $tb0301_id));
		redirect($this->uri->segment(1) . "/lst"); 
	}

	public function controller()
	{
		if(!$this->uri->segment(2))
		{
			redirect($this->uri->segment(1)."/lst");
			return;
		}

		try 
		{
			$res=$this->tablecontroller->processor(
				"tb0301_subtests" // $table
				,false 		// $has_arch
				,true 		// $update_timestamp
				,[			// $columns
					"tb0301_id"=>[
						"iskey"=>1
						,"autoinc"=>1
						,"type"=>"number"
						,"template"=>"%tb0301_id%"
					]
					,"tb0301_order"=>[
						"iskey"=>0
						,"type"=>"number"
						,"template"=>"%tb0301_order%"
					]
					,"tb0301_timelimit"=>[
						"iskey"=>0
						,"type"=>"number"
						,"template"=>"%tb0301_timelimit%"
					]
					,"tb0301_name_ru"=>[
						"iskey"=>0
						,"type"=>"string"
						,"template"=>"%tb0301_name_ru%"
					]
					,"tb0301_name_kz"=>[
						"iskey"=>0
						,"type"=>"string"
						,"template"=>"%tb0301_name_kz%"
					], "tb0301_tb0202_id" => [
						"iskey" => 0, "type" => "number", "template" => "%tb0202_specialities.tb0202_name_ru%"
					], "tb0301_variant" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0301_variant%"
					]
					,"tb0301_desc_ru"=>[
						"iskey"=>0
						,"type"=>"string"
						,"template"=>""
					]
					,"tb0301_desc_kz"=>[
						"iskey"=>0
						,"type"=>"string"
						,"template"=>""
					], "tb0301_min" => [
						"iskey" => 0, "type" => "number", "template" => "%tb0301_min%"
					]
					,"tb0301_created"=>[
						"iskey"=>0
						,"type"=>"datetime"
						,"template"=>"%tb0301_created%"
					]
					,"tb0301_createdby"=>[
						"iskey"=>0
						,"type"=>"number"
						,"template"=>"%tb0101_users.tb0101_name2% %tb0101_users.tb0101_name1%"
					]
				]
				,2			// $cmdSegment
				,null 		// $onSaveSuccess
				,[			// $customWHERE
					//"tb0102_tb0101_id"=>$this->data["tb0101"]->tb0101_idn
				]
			);
			if($res) die(json_encode(array("result"=>"Операция завершена успешно.","id"=>$res)));
		}
		catch (Exception $e)
		{
			//die($e->getMessage());
			die(json_encode(array("error"=>$e->getMessage())));
		}

		$data=array();
		if($this->uri->segment(3))
		{
			$data=$this->db->get_where("tb0301_subtests",array("tb0301_id"=>urldecode($this->uri->segment(3))))->row_array();
			if(!$data||!count($data)) throw new Exception("Запись не найдена");
		}

		$this->data=array_merge($this->data,$data);
		$this->load->view($this->uri->segment(1)."/".$this->uri->segment(2),$this->data);
	}
}
