<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
	var $data = [];

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
		// die("SYSTEM OFF");
		return redirect("main/home");
	}

	public function home()
	{
		// die("SYSTEM OFF");
		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
		if ($this->input->post("tb0202_id")) {
			$l = $this->input->post("lang");
			$tb0202_id = $this->input->post("tb0202_id");
			$tb0203_id = $this->input->post("tb0203_id");
			//$tests = $this->db->query("SELECT * FROM tb0304_tests WHERE tb0304_0202_id = $tb0202_id AND ")->result();
			$tests = $this->db->get_where("tb0304_tests", ["tb0304_0202_id" => $tb0202_id, "tb0304_0203_id" => $tb0203_id])->result();
			$options = [];

			foreach ($tests as $t) {
				$options[] = [
					'value' => $t->tb0304_id,
					'text' => $t->{"tb0304_name_" . $l},
				];
			}
			die(json_encode($options));
		}
	}

	public function report()
	{
		$sql = "
SELECT
	tb0101_id
	,tb0101_idn
	,tb0101_name1
	,tb0101_name2
	,tb0101_name3
	,IFNULL((
			SELECT SUM(IF(tb0303_value=tb0302_answer,1,0)) score
			FROM tb0303_answers
			LEFT JOIN tb0302_questions ON tb0302_id=tb0303_tb0302_id
			WHERE tb0303_tb0101_id=tb0101_id 
				AND tb0302_tb0301_id=2
		),0) score2
	,IFNULL((
			SELECT SUM(IF(tb0303_value=tb0302_answer,1,0)) score
			FROM tb0303_answers
			LEFT JOIN tb0302_questions ON tb0302_id=tb0303_tb0302_id
			WHERE tb0303_tb0101_id=tb0101_id 
				AND tb0302_tb0301_id=3
		),0) score3
	,IFNULL((
			SELECT SUM(IF(tb0303_value=tb0302_answer,1,0)) score
			FROM tb0303_answers
			LEFT JOIN tb0302_questions ON tb0302_id=tb0303_tb0302_id
			WHERE tb0303_tb0101_id=tb0101_id 
				AND tb0302_tb0301_id=4
		),0) score4
	,tb0101_created testStart
	,(SELECT MAX(tb0303_created) FROM tb0303_answers WHERE tb0303_tb0101_id=tb0101_id) testFinish
FROM tb0101_users
WHERE tb0101_deleted=0
	AND tb0101_id>3
ORDER BY tb0101_id
";
		$this->data["result"] = $this->db->query($sql)->result();

		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}

	public function lang()
	{
		$this->session->set_userdata("lang", $this->uri->segment(3));
		//set_cookie("lang",$this->uri->segment(3));
		redirect($_SERVER['HTTP_REFERER']);
	}
}
