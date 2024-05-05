<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Tests extends CI_Controller
{
	var $data = [];
	var $CI;
	private $l;

	function __construct()
	{
		parent::__construct();
		$this->data["tb0101"] = $this->session->userdata("tb0101");
		$this->l = $this->input->post("lang");
		if (!$this->l) {
			$this->l = $this->input->get("lang") ? $this->input->get("lang") : "kz";
		}
		$path = APPPATH . 'language' . DIRECTORY_SEPARATOR . 'lang_' . $this->l . ".php";
		include $path;
		$this->data["lang"] = $lang;
		$this->data["l"] = $this->l;
	}

	public function index()
	{
		$this->controller();
	}

	public function lst()
	{
		$this->controller();
	}

	public function add()
	{
		$this->data["title"] = $this->data["lang"]["add_test"];
		$this->controller();
	}

	public function edit()
	{
		$this->data["title"] = $this->data["lang"]["edit_test"];
		$this->data["id"] = "tb0304_id";
		$this->controller();
	}

	public function save()
	{
		$this->controller();
	}

	public function del($tb0304_id)
	{
		$this->db->delete("tb0304_tests", array("tb0304_id" => $tb0304_id));
		redirect($this->uri->segment(1) . "/lst");
	}

	public function controller()
	{
		if (!$this->uri->segment(2)) {
			redirect($this->uri->segment(1) . "/lst");
			return;
		}

		try {
			$res = $this->tablecontroller->processor(
				"tb0304_tests" // $table
				,
				false 		// $has_arch
				,
				true 		// $update_timestamp
				,
				[			// $columns
					"tb0304_id" => [
						"iskey" => 1, "autoinc" => 1, "type" => "number", "template" => "%tb0304_id%"
					], "tb0304_name_ru" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0304_name_ru%"
					], "tb0304_name_kz" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0304_name_kz%"
					], "tb0304_0202_id" => [
						"iskey" => 0, "type" => "number", "template" => "%tb0202_specialities.tb0202_name_$this->l%"
					], "tb0304_0203_id" => [
						"iskey" => 0, "type" => "number", "template" => "%tb0203_testtypes.tb0203_name_$this->l%"
					], "tb0304_variant" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0304_variant%"
					], "tb0304_desc_ru" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0304_desc_ru%"
					], "tb0304_desc_kz" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0304_desc_kz%"
					], "tb0304_created" => [
						"iskey" => 0, "type" => "datetime", "template" => "%tb0304_created%"
					], "tb0304_createdby" => [
						"iskey" => 0, "type" => "number", "template" => "%tb0101_users.tb0101_name2% %tb0101_users.tb0101_name1%"
					]
				],
				2			// $cmdSegment
				,
				null 		// $onSaveSuccess
				,
				[			// $customWHERE
					//"tb0102_tb0101_id"=>$this->data["tb0101"]->tb0101_idn
				]
			);
			if ($res){
				$subtest_id = $this->input->post("tb0305_0301_id");
				$test_id = $this->input->post("tb0304_id");
				if(!$test_id)
					$test_id = $this->db->select_max('tb0304_id')->get('tb0304_tests')->row()->tb0304_id;
				// Удаляем предыдущие связи для этого теста
				$this->db->delete("tb0305_tests_subtests", array("tb0305_0304_id" => $test_id));

				// Сохраняем новые связи в таблице tb0305_subtests
				foreach ($subtest_id as $sid) {
					$this->db->insert("tb0305_tests_subtests", array(
						"tb0305_0301_id" => $sid,
						"tb0305_0304_id" => $test_id
					));
				}
				die(json_encode(array("result" => $this->data["lang"]["operation_success"], "id" => $res)));
			}
		} catch (Exception $e) {
			//die($e->getMessage());
			die(json_encode(array("error" => $e->getMessage())));
		}

		$data = array();
		if ($this->uri->segment(3)) {
			$data = $this->db->get_where("tb0304_tests", array("tb0304_id" => urldecode($this->uri->segment(3))))->row_array();
			if (!$data || !count($data)) throw new Exception($this->data["lang"]["record_not_found"]);
		}

		$this->data = array_merge($this->data, $data);
		if (in_array($this->uri->segment(2), ["edit", "add"]))
			$this->load->view("shared/" . $this->uri->segment(2), $this->data);
		else
			$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}
}
