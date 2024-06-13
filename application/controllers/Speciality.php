<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Speciality extends CI_Controller
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
        $this->data["title"] = $this->data["lang"]["add_speciality"];
        $this->controller();
    }

    public function edit()
    {
        $this->data["title"] = $this->data["lang"]["edit_speciality"];
        $this->data["id"] = "tb0202_id";
        $this->controller();
    }

    public function save()
    {
        $this->controller();
    }

    /*public function del()
    {
        $this->controller();
    }*/

    public function del($tb0202_id)
    {
        // Удаление специальности по tb0202_id
        $this->db->delete("tb0202_specialities", array("tb0202_id" => $tb0202_id));

        // Перенаправление на список специальностей
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
                "tb0202_specialities" // $table
                ,
                false         // $has_arch
                ,
                true         // $update_timestamp
                ,
                [            // $columns
                    "tb0202_id" => [
                        "iskey" => 1, "autoinc" => 1, "type" => "number", "template" => "%tb0202_id%"
                    ], "tb0202_name_ru" => [
                        "iskey" => 0, "type" => "string", "template" => "%tb0202_name_ru%"
                    ], "tb0202_name_kz" => [
                        "iskey" => 0, "type" => "string", "template" => "%tb0202_name_kz%"
                    ]
                ],
                2            // $cmdSegment
                ,
                null         // $onSaveSuccess
                ,
                [            // $customWHERE
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
            $data = $this->db->get_where("tb0202_specialities", array("tb0202_id" => urldecode($this->uri->segment(3))))->row_array();
            if (!$data || !count($data)) throw new Exception($this->data["lang"]["record_not_found"]);
        }

        $this->data = array_merge($this->data, $data);
        if (in_array($this->uri->segment(2), ["edit", "add"]))
            $this->load->view("shared/" . $this->uri->segment(2), $this->data);
        else
            $this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
    }
}
