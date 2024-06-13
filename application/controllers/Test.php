<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
	var $data = [];
	private $l;

	function __construct()
	{
		parent::__construct();

		// die("SYSTEM OFF");

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
		return redirect("main/home");
	}

	public function dlDefault()
	{
		$sql = "
SELECT *
FROM tb0101_users
INNER JOIN 	tb0201_eduTypes ON tb0201_id=tb0101_tb0201_id
WHERE tb0101_tb0003_id=0 AND tb0101_deleted=0
--	AND tb0101_idn NOT IN (830120300253,830730300232,111111111111,830120300254)
";
		$tb0101 = $this->db->query($sql)->result();
		$tb0302 = $this->db->query("SELECT * FROM tb0302_questions ORDER BY tb0302_order")->result();
		$tb0303 = $this->db->get_where("tb0303_answers")->result();

		// header("Content-Description: File Transfer");
		// header("Content-Type: application/octet-stream");
		// header("Content-Disposition: attachment; filename=dlDefault-".date("Ymd-His").".txt"); 
		// header("Content-Transfer-Encoding: binary");
		// header("Connection: Keep-Alive");
		// header("Expires: 0");
		// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		// header("Pragma: public");

		$wa = ["A", "B", "C", "D", "E"];

		foreach ($tb0101 as $u) {
			$idn = str_pad($u->tb0101_idn, 12, "0", STR_PAD_LEFT);
			// $century=substr($idn,7,1);

			// echo str_pad($u->tb0101_id,5,"0",STR_PAD_LEFT);
			// echo ($century<=2?19:($century<=4?20:21)).substr($idn,0,2);
			// echo $u->tb0101_tb0002_id==2?1:2;
			// echo $century % 2 === 0 ? 2:1;

			echo $idn;
			echo $u->tb0101_tb0002_id == 2 ? 1 : 2;
			echo $u->tb0201_code;

			foreach ($tb0302 as $q) {
				$w = "";
				foreach ($tb0303 as $a) {
					if ($u->tb0101_id != $a->tb0303_tb0101_id) continue;
					if ($q->tb0302_id != $a->tb0303_tb0302_id) continue;

					$w = $a->tb0303_value;
				}
				echo $w ? $wa[$w - 1] : "-";
			}

			echo "\n\n";
		}
	}

	public function dlJSON()
	{
		$dsU = $this->db->get_where("tb0101_users", ["tb0101_tb0003_id" => 0, "tb0101_deleted" => 0])->result();
		$dsA = $this->db->get_where("tb0303_answers")->result();

		$t = [];
		foreach ($dsU as $u) {
			if (in_array($u->tb0101_idn, [830120300253, 830730300232, 111111111111, 830120300254])) continue;

			$r = (array)$u;
			unset($r["tb0101_idn"]);
			unset($r["tb0101_passwd"]);
			unset($r["tb0101_tb0003_id"]);
			unset($r["tb0101_deleted"]);
			unset($r["tb0101_createdby"]);

			$r["answers"] = [];
			foreach ($dsA as $a) {
				if ($u->tb0101_id != $a->tb0303_tb0101_id) continue;

				$a = (array)$a;
				//unset($a["tb0303_id"]);
				unset($a["tb0303_tb0101_id"]);
				unset($a["tb0303_created"]);
				$r["answers"][] = $a;
			}

			$t[] = $r;
		}


		//header("Content-Type: application/json");

		header("Content-Description: File Transfer");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=dlJSON-" . date("Ymd-His") . ".txt");
		header("Content-Transfer-Encoding: binary");
		header("Connection: Keep-Alive");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");

		die(json_encode($t));
	}

	/*public function start()
	{
		$d = [
			"tb0101_idn" => $this->input->post("tb0101_idn", 1), "tb0101_phone1" => $this->input->post("tb0101_phone1", 1), "tb0101_tb0002_id" => $this->input->post("tb0101_tb0002_id", 1), "tb0101_tb0201_id" => 0 //$this->input->post("tb0101_tb0201_id",1)
			, "tb0101_name1" => $this->input->post("tb0101_name1", 1), "tb0101_name2" => $this->input->post("tb0101_name2", 1), "tb0101_name3" => $this->input->post("tb0101_name3", 1)
		];

		try {
			$this->db->insert("tb0101_users", $d);
			$d["tb0101_id"] = $this->db->insert_id();
		} catch (Exception $ex) {
			die(json_encode(["result" => "error", "error" => $ex->getMessage()]));
		}

		die(json_encode(["redirect" => site_url($this->uri->segment(1) . "/process" . "/" . $d["tb0101_id"]), "result" => "Сохранение прошло успешно"]));
	}*/

	public function start()
	{
		$d = [
			"tb0101_idn" => $this->input->post("tb0101_idn", 1),
			"tb0101_phone1" => $this->input->post("tb0101_phone1", 1),
			"tb0101_tb0002_id" => $this->input->post("tb0101_tb0002_id", 1),
			"tb0101_name1" => $this->input->post("tb0101_name1", 1),
			"tb0101_name2" => $this->input->post("tb0101_name2", 1),
			"tb0101_name3" => $this->input->post("tb0101_name3", 1),
		];
		$tb0202_id = $this->input->post("tb0202_id", 1);
		$tb0203_id = $this->input->post("tb0203_id", 1);
		$l = "kz";
		if($d['tb0101_tb0002_id'] == 1)
			$l="ru";
		$variants = $this->db->get_where("tb0304_tests", ["tb0304_0202_id" => $tb0202_id, "tb0304_0203_id" => $tb0203_id])->result_array();
		if (!empty($variants)) {
			$randomVariant = $variants[array_rand($variants)];
			$d["tb0101_tb0304_id"] = $randomVariant["tb0304_id"];
		}
		// Check if tb0101_idn exists in the table
		$existingRecord = $this->db->get_where("tb0101_users", ["tb0101_idn" => $d["tb0101_idn"]])->row();
		if ($existingRecord) {
			// Update the existing record
			try {
				$this->db->update("tb0101_users", $d, ["tb0101_idn" => $d["tb0101_idn"]]);
				$d["tb0101_id"] = $existingRecord->tb0101_id;
			} catch (Exception $ex) {
				die(json_encode(["result" => "error", "error" => $ex->getMessage()]));
			}
		} else {
			// Insert a new record
			try {
				$this->db->insert("tb0101_users", $d);
				$d["tb0101_id"] = $this->db->insert_id();
			} catch (Exception $ex) {
				die(json_encode(["result" => "error", "error" => $ex->getMessage()]));
			}
		}

		die(json_encode(["redirect" => site_url($this->uri->segment(1) . "/process" . "/" . $d["tb0101_id"] . "?lang=$l"), "result" => "Сохранение прошло успешно"]));
	}


	public function process()
	{
		$result = $this->db->get_where("tb0101_users", ["tb0101_id" => $this->uri->segment(3)])->row_array();
		$this->data = array_merge($this->data, $result);
		$this->session->set_userdata("lang", $result["tb0101_tb0002_id"] == 2 ? "kz" : "ru");
		$subtests = $this->db->get_where("tb0305_tests_subtests", ["tb0305_0304_id" => $result["tb0101_tb0304_id"]])->result_array();
		$subtest_ids = [];
		foreach ($subtests as $subtest) {
			// Добавляем значение поля tb0305_0301_id в массив $subtest_ids
			$subtest_ids[] = $subtest['tb0305_0301_id'];
		}
		$this->db->where_in("tb0301_id", $subtest_ids);
		$this->data["tb0301"] = $this->db->get("tb0301_subtests")->result();
		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}

	public function save()
	{
		$tb0101_id = $this->input->post("tb0101_id", 1);
		$tb0101_tb0002_id=$this->db->query("SELECT tb0101_tb0002_id FROM tb0101_users WHERE tb0101_id=$tb0101_id")->row()->tb0101_tb0002_id;
		$l="kz";
		if($tb0101_tb0002_id == 1)
			$l="ru";
		// $this->db->query("TRUNCATE TABLE tb0303_answers");
		$this->db->query("DELETE FROM tb0303_answers WHERE tb0303_tb0101_id=" . $tb0101_id);

		$t = $this->db->get_where("tb0302_questions")->result();
		foreach ($t as $r) {
			$d = [
				"tb0303_tb0101_id" => $tb0101_id, "tb0303_tb0302_id" => $r->tb0302_id, "tb0303_value" => $this->input->post("tb0302_id-" . $r->tb0302_id, 1)
			];

			if (!$d["tb0303_value"]) continue;

			$this->db->insert("tb0303_answers", $d);
		}

		redirect($this->uri->segment(1) . "/thanks/" . $tb0101_id . "?lang=$l
		");
	}

	public function thanks()
	{
		$tb0101_id = $this->uri->segment(3);

		$sql = "
SELECT
	tb0301_name_ru
    ,tb0301_name_kz
	,tb0301_min
    ,SUM(IF(tb0303_value=tb0302_answer,1,0)) v
	,(SELECT COUNT(tb0302_id) 
     FROM tb0302_questions 
     WHERE tb0302_tb0301_id = tb0301_id) AS c
FROM tb0101_users
INNER JOIN tb0303_answers ON tb0303_tb0101_id=tb0101_id
INNER JOIN tb0302_questions ON tb0302_id=tb0303_tb0302_id
INNER JOIN tb0301_subtests ON tb0301_id=tb0302_tb0301_id
WHERE tb0101_id=?
GROUP BY tb0301_order, tb0301_name_ru ,tb0301_name_kz, tb0301_min
ORDER BY tb0301_order
";
		//ORDER BY tb0301_order
		$this->data["result"] = $this->db->query($sql, [$tb0101_id])->result();

		$this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
	}
}
