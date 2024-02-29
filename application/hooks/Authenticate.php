<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate {
	protected $CI;

	public function __construct() {
		$this->CI = & get_instance();
	}

	public function check_user_login() {
		$tb0101=$this->CI->session->userdata("tb0101");

		$get=$_GET;
		$post=$_POST;
		$server=$_SERVER;

		unset($server["PATH"]);
		unset($server["PP_CUSTOM_PHP_INI"]);
		unset($server["PP_CUSTOM_PHP_CGI_INDEX"]);
		unset($server["PATH_TRANSLATED"]);
		unset($server["PATH_INFO"]);
		unset($server["CONTEXT_DOCUMENT_ROOT"]);
		unset($server["CONTEXT_PREFIX"]);
		unset($server["SERVER_PROTOCOL"]);
		unset($server["GATEWAY_INTERFACE"]);
		unset($server["REMOTE_PORT"]);
		unset($server["SCRIPT_FILENAME"]);
		unset($server["SERVER_ADMIN"]);
		unset($server["REQUEST_SCHEME"]);
		unset($server["DOCUMENT_ROOT"]);
		unset($server["SERVER_PORT"]);
		unset($server["HTTP_ACCEPT_LANGUAGE"]);
		unset($server["HTTP_ACCEPT"]);
		unset($server["SERVER_ADDR"]);
		unset($server["SERVER_NAME"]);
		unset($server["SERVER_SOFTWARE"]);
		unset($server["SERVER_SIGNATURE"]);
		unset($server["HTTP_ACCEPT_ENCODING"]);
		unset($server["HTTP_CONNECTION"]);
		unset($server["HTTP_HOST"]);
		unset($server["HTTPS"]);
		unset($server["FCGI_ROLE"]);
		unset($server["REQUEST_TIME_FLOAT"]);
		unset($server["REQUEST_TIME"]);
		unset($server["HTTP_CACHE_CONTROL"]);
		unset($server["HTTP_UPGRADE_INSECURE_REQUESTS"]);
		unset($server["UNIQUE_ID"]);
		unset($server["SCRIPT_NAME"]);
		unset($server["REQUEST_URI"]);

		foreach ($server as $x=>$y) if(!$y) unset($server[$x]);

		$data=[
			"tb0000_segment1"=>$this->CI->uri->segment(1)
			,"tb0000_segment2"=>$this->CI->uri->segment(2)
			,"tb0000_segment3"=>$this->CI->uri->segment(3)
			,"tb0000_segment4"=>$this->CI->uri->segment(4)
			,"tb0000_segment5"=>$this->CI->uri->segment(5)
			,"tb0000_url"=>$_SERVER["REQUEST_URI"]
			,"tb0000_ip"=>@$_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']
			,"tb0000_tb0101_id"=>$this->CI->session->userdata("tb0101_id")
			,"tb0000_get"=>count($get)?json_encode($get):""
			,"tb0000_post"=>count($post)?json_encode($post):""
			,"tb0000_server"=>count($server)?json_encode($server):""
		];

		foreach ($data as $x=>$y) $data[$x]=$y?$y:"";
		// $data["tb0000_tb0101_id"]=$data["tb0000_tb0101_id"]?$data["tb0000_tb0101_id"]:"0";
		if($this->CI->uri->segment(1)!="schedule"&&$this->CI->uri->segment(3)!="favicon.ico") {
			$this->CI->db->insert("tb0000_http",$data);
		}

		// die(print_r($_SERVER,1));

		if(@$tb0101) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/main/report") !== false) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/main/home") !== false) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/user/login") !== false) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/user/passwd") !== false) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/user/registration") !== false) return;
		if(strpos(@$_SERVER["PHP_SELF"],"/test/") !== false) return;

		redirect('main/home');
	}
}
?>