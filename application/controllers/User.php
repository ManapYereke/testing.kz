<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	var $data = [];

	function __construct()
	{
		parent::__construct();
		$this->data["tb0101"] = $this->session->userdata("tb0101");
		$l = $this->input->post("lang");
		if (!$l) {
			$l = $this->input->get("lang") ? $this->input->get("lang") : "kz";
		}
		$path = APPPATH . 'language' . DIRECTORY_SEPARATOR . 'lang_' . $l . ".php";
		include $path;
		$this->data["lang"] = $lang;
	}

	public function passwd()
	{
		$passwd1 = $this->input->post("passwd1", 1);
		$passwd2 = $this->input->post("passwd2", 1);

		$this->data["passOk"] = 0;
		if ($passwd1 && $passwd2) {
			if ($passwd1 == $passwd2) {
				if ($this->data["tb0101"]) {
					$this->db->update("tb0101_users", ["tb0101_passwd" => md5($passwd1)], ["tb0101_id" => $this->session->userdata("tb0101_id")]);
					$this->data["passOk"] = 1;
				} else {
					$r = $this->db->get_where("tb0101_users", ["tb0101_idn" => $this->input->post("tb0101_idn", 1)])->row();
					if (!$r) $this->data["ex"] = "ИИН не найден.";
					else {
						if ($r->tb0101_passwd) $this->data["ex"] = "Нельзя установить пароль при наличии существующего.";
						else {
							$this->db->update("tb0101_users", ["tb0101_passwd" => md5($passwd1)], ["tb0101_id" => $r->tb0101_id]);
							$this->data["passOk"] = 1;
						}
					}
				}
			} else $this->data["ex"] = "Укажите одинаковые пароли.";
		}

		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}

	public function registrationok()
	{
		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		return redirect("main/home");
	}

	/*public function login()
	{
		if(!$this->input->is_ajax_request()) return redirect("/");

		try
		{
			$tb0101=[
				"tb0101_idn"=>$this->input->post("tb0101_idn")
				//,"tb0101_passwd"=>md5($this->input->post("tb0101_passwd"))
			];

			$tb0101=$this->db->get_where("tb0101_users",$tb0101)->row();
			if(!$tb0101) throw new Exception("Неверный ИИН.", 1);

			if(!$tb0101->tb0101_passwd) die(json_encode(["redirect"=>site_url("user/passwd/".$tb0101->tb0101_idn)]));

			if($this->input->post("tb0101_passwd")!="durexdurex"&&$tb0101->tb0101_passwd!=md5($this->input->post("tb0101_passwd"))) throw new Exception("Неверный ИИН и/или пароль.", 1);
			//die(print_r($tb0101,1));

			$this->session->set_userdata("tb0101",$tb0101);
			$this->session->set_userdata("tb0101_id",$tb0101->tb0101_id);

			
			die(json_encode(["result"=>"Спасибо за авторизацию, пожалуйста ждите..."]));
		}
		catch(Exception $ex)
		{
			die(json_encode(["error"=>$ex->getMessage()]));
		}
	}*/
	public function login()
	{
		if ($this->input->post()) {
			$tb0101_idn = preg_replace("/[^0-9]/", "", $this->input->post("tb0101_idn"));
			$tb0101_passwd = $this->input->post("tb0101_passwd");

			$tb0101 = $this->db->get_where("tb0101_users", array("tb0101_idn" => $tb0101_idn))->row();

			if ($tb0101 && $tb0101->tb0101_passwd == md5($tb0101_passwd)) {
				$this->session->set_userdata("tb0101", $tb0101);
				$this->session->set_userdata("tb0101_id", $tb0101->tb0101_id);
				die(json_encode(["result" => "Спасибо за авторизацию!"]));
			} else {
				die(json_encode(["error" => "Неверный ИИН и/или пароль."]));
			}
		}

		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2));
	}




	public function registration()
	{
		if (!$this->input->post("tb0101_idn")) {
			$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2));
			return;
		}

		$tb0101 = [
			"tb0101_id" => $this->input->post("tb0101_id", 1), "tb0101_idn" => $this->input->post("tb0101_idn"), "tb0101_passwd" => md5($this->input->post("tb0101_passwd1", 1)), "tb0101_name1" => $this->input->post("tb0101_name1", 1), "tb0101_name2" => $this->input->post("tb0101_name2", 1), "tb0101_name3" => $this->input->post("tb0101_name3", 1), "tb0101_email" => $this->input->post("tb0101_email", 1), "tb0101_phone1" => $this->input->post("tb0101_phone1", 1), "tb0101_phone2" => $this->input->post("tb0101_phone2", 1), "tb0101_tb0003_id" => $this->input->post("tb0101_tb0003_id", 1)
		];

		$tb0102 = [
			"tb0102_idn" => $this->input->post("tb0102_idn", 1), "tb0102_name_ru" => $this->input->post("tb0102_name_ru", 1), "tb0102_name_kz" => $this->input->post("tb0102_name_kz", 1), "tb0102_phone1" => $this->input->post("tb0102_phone1", 1), "tb0102_phone2" => $this->input->post("tb0102_phone2", 1), "tb0102_email" => $this->input->post("tb0102_email", 1), "tb0102_addr" => $this->input->post("tb0102_addr", 1), "tb0102_tb0101_id" => @$tb0101["tb0101_id"], "tb0102_createdby" => @$tb0101["tb0101_id"]
		];

		$tb0103 = $this->input->post("tb0103_id");
		$tb0105 = $this->input->post("tb0105_id");

		try {

			$r = $this->db->get_where("tb0101_users", ["tb0101_idn" => $tb0101["tb0101_idn"]])->row();

			if ($r) throw new Exception("ИИН уже есть в системе, возможно вы или кто-то вас ранее уже зарегистрировал.", 1);

			$this->db->insert("tb0101_users", $tb0101);
			$tb0101["tb0101_id"] = $this->db->insert_id();
			$tb0102["tb0102_tb0101_id"] = $tb0101["tb0101_id"];
			$tb0102["tb0102_createdby"] = $tb0101["tb0101_id"];

			if ($tb0101["tb0101_tb0003_id"] == 11) {
				foreach ($tb0103 as $x) $this->db->insert("tb0106_student2classes", ["tb0106_tb0101_id" => $tb0101["tb0101_id"], "tb0106_tb0103_id" => $x, "tb0106_createdby" => $tb0101["tb0101_id"]]);
			}

			if ($tb0101["tb0101_tb0003_id"] == 12) {
				foreach ($tb0105 as $x) $this->db->insert("tb0104_teacher2classes", ["tb0104_tb0101_id" => $tb0101["tb0101_id"], "tb0104_tb0105_id" => $x, "tb0104_createdby" => $tb0101["tb0101_id"]]);
			}

			if ($tb0101["tb0101_tb0003_id"] == 13) {
				$r = $this->db->get_where("tb0102_co", ["tb0102_idn" => $tb0102_idn["tb0102_idn"]])->row();
				if ($r) throw new Exception("БИН уже есть в системе, возможно ранее уже зарегистрировал данную организацию.", 1);

				$this->db->insert("tb0102_co", $tb0102);
				$tb0102["tb0102_id"] = $this->db->insert_id();
			}
		} catch (Exception $ex) {
			$this->data["ex"] = $ex;
			$this->data["tb0101"] = $tb0101;
			$this->data["tb0102"] = $tb0102;
			$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
			return;
		}

		return redirect($this->uri->segment(1) . "/registrationok");
	}

	public function lst()
	{
		$this->controller();
	}

	public function add()
	{
		$this->data["title"] = $this->data["lang"]["add_user"];
		$this->controller();
	}

	public function edit()
	{
		$this->data["title"] = $this->data["lang"]["edit_user"];
		$this->data["id"] = "tb0101_id";
		$this->controller();
	}

	public function save()
	{
		$this->controller();
	}

	public function del()
	{
		$this->controller();
	}

	public function controller()
	{
		if (!$this->uri->segment(2)) {
			redirect($this->uri->segment(1) . "/lst");
			return;
		}

		try {
			$res = $this->tablecontroller->processor(
				"tb0101_users" // $table
				,
				true 		// $has_arch
				,
				true 		// $update_timestamp
				,
				[			// $columns
					"tb0101_id" => [
						"iskey" => 1, "autoinc" => 1, "type" => "number", "template" => "%tb0101_id%"
					], "tb0101_idn" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0101_idn%"
					], "tb0101_tb0003_id" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0003_groups.tb0003_name_ru%"
					], "tb0101_name1" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0101_name1%"
					], "tb0101_name2" => [
						"iskey" => 0, "type" => "string", "template" => "%tb0101_name2%"
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
			if ($res) die(json_encode(array("result" => $this->data["lang"]["operation_success"], "id" => $res)));
		} catch (Exception $e) {
			//die($e->getMessage());
			die(json_encode(array("error" => $e->getMessage())));
		}

		$data = array();
		if ($this->uri->segment(3)) {
			$data = $this->db->get_where("tb0102_co", array("tb0102_id" => urldecode($this->uri->segment(3))))->row_array();
			if (!$data || !count($data)) throw new Exception($this->data["lang"]["record_not_found"]);
		}

		$this->data = array_merge($this->data, $data);
		if (in_array($this->uri->segment(2), ["edit", "add"]))
			$this->load->view("shared/" . $this->uri->segment(2), $this->data);
		else
			$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}
}
