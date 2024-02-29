<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Speciality extends CI_Controller
{
    var $data = [];
    var $CI;

    function __construct()
    {
        parent::__construct();

        $this->data["tb0101"] = $this->session->userdata("tb0101");
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
        $this->controller();
    }

    public function edit()
    {
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
            if ($res) die(json_encode(array("result" => "Операция завершена успешно.", "id" => $res)));
        } catch (Exception $e) {
            //die($e->getMessage());
            die(json_encode(array("error" => $e->getMessage())));
        }

        $data = array();
        if ($this->uri->segment(3)) {
            $data = $this->db->get_where("tb0202_specialities", array("tb0202_id" => urldecode($this->uri->segment(3))))->row_array();
            if (!$data || !count($data)) throw new Exception("Запись не найдена");
        }

        $this->data = array_merge($this->data, $data);
        $this->load->view($this->uri->segment(1) . "/" . $this->uri->segment(2), $this->data);
    }
}
