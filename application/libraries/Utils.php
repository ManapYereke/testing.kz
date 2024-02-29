<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Utils {
	function __construct()
	{
	}

	function displayRuKz($l,$ru,$kz)
	{
		if(!$l) return $ru."<br>".$kz;
		if($l==2) return $kz;

		return $ru;
	}
	
	function getClientMac()
	{
		if($_SERVER["REMOTE_ADDR"]=="127.0.0.1") return "84A6C882D2E6";
		$arp=shell_exec("arp -a ".escapeshellarg($_SERVER["REMOTE_ADDR"]));
		$lines=explode("\n", $arp);
		
		foreach($lines as $line)
		{
			$cols=preg_split('/\s+/', trim($line));
			if ($cols[0]==$_SERVER["REMOTE_ADDR"]) return str_replace("-","",$cols[1]);
		}
		return "";
	}

	function strip_forbidden_tags($html, $forbidden_tags) // убираем указанные теги, но оставляем содержимое
	{
	   // $forbidden_tags = "ul|li|ol";
		return preg_replace("#<\s*\/?(".$forbidden_tags.")\s*[^>]*?>#im", '', ($html));
		// return $html;
	}

	function displayDate($v,$alt="&nbsp;",$format="d.m.Y")
	{
		$v=strtotime($v);
		if(date("Y")<=1990) return $alt;
		return date($format,$v);
	}

	function displayTime($v,$format="H:i:s")
	{
		$v=strtotime($v);
		return date($format,$v);
	}

	function l()
	{
		$CI =& get_instance();

		$l=$CI->session->userdata("lang");
		$l=!in_array($l,array("ru","kz","en"))?"en":$l;
		return $l;
	}

	function t($o)
	{
		$CI =& get_instance();
		$l=$this->l();
		if(is_array($o))
		{
			if(isset($o["ru"])) return $o[$l];
			if($l=="kz") return $o[1];
			return $o[0];
		}

		$r=$CI->db->query("SELECT * FROM tb0004_locale WHERE tb0004_id=MD5(?)",array($o))->row();
		if(!$r)
		{
			$CI->db->insert("tb0004_locale",array("tb0004_id"=>md5($o),"tb0004_text_ru"=>$o));
			return $o;
		}

		if($l=="ru"&&$r->tb0004_text_ru) return $r->tb0004_text_ru;
		elseif($l=="kz"&&$r->tb0004_text_kz) return $r->tb0004_text_kz;
		elseif($l=="en"&&$r->tb0004_text_en) return $r->tb0004_text_en;

		return $o;
	}

	function userIsAuthenticated()
	{
		$CI =& get_instance();
		return ($CI->session->userdata("tb0001_id")&&$CI->session->userdata("tb0001"));
	}
	
	function hasRole($role="")
	{
		$CI =& get_instance();
		$tb0001_id=$CI->session->userdata("tb0001_id");
		$role?$res=false:$res=true;
		$CI=&get_instance();
		
		$roles=$CI->db->query("SELECT tb0010_id FROM tb0010_role")->result();

		$userRole=$CI->db->query(
			"SELECT tb0009_tb0010_id, tb0001_disabled"
			."\nFROM tb0009_group2role"
			."\nLEFT JOIN tb0001_user ON tb0001_id=?"
			."\nWHERE tb0001_tb0002_id=tb0009_tb0002_id"
			."\nAND tb0009_tb0010_id=?"
		,array($tb0001_id,$role))->row();

		if($userRole&&!$userRole->tb0001_disabled){
			foreach($roles as $r){
				if($r->tb0010_id==$userRole->tb0009_tb0010_id)$res=true;
			}
		}
		return $res;
	}

	function auth($role="")
	{
		$CI =& get_instance();
		if($this->userIsAuthenticated())
		{
			if(!$this->hasRole($role))
			{
				// $redirectto=urlencode(site_url($CI->uri->segment(1)."/".$CI->uri->segment(2)));
				if($CI->input->is_ajax_request()) die(json_encode(array("redirect"=>site_url("user/norequiredrole"),"error"=>$this->t("Необходимо пройти авторизацию."))));
				redirect("user/norequiredrole");
				die();
			}
			return;
		}
		$redirectto=urlencode(site_url($CI->uri->segment(1)."/".$CI->uri->segment(2)));
		if($CI->input->is_ajax_request()) die(json_encode(array("redirect"=>site_url("user/login?ref=".$redirectto),"error"=>$this->t("Необходимо пройти авторизацию."))));

		redirect("user/login?ref=".$redirectto);
		die();
	}

	function arrToXML($rootName,$a)
	{
		$xml=new SimpleXMLElement("<{$rootName}/>");
		if($a&&is_array($a)) foreach($a as $i=>$j) @$xml->addChild($i,(string)$j);
		return $xml->asXML();
	}

	function registerHttpRequest()
	{
		$CI =& get_instance();
		$data=array(
			"jr0001_var_server"=>json_encode($CI->input->server(null))
			,"jr0001_var_post"=>json_encode($CI->input->post(null))
			,"jr0001_var_get"=>json_encode($CI->input->get(null))
			,"jr0001_ip"=>$CI->input->ip_address()
			,"jr0001_url"=>$CI->input->server("REQUEST_URI")
			,"jr0001_tb0001_login"=>$CI->v->getString($CI->session->userdata("tb0001_login"))
		);
		$CI->db->insert("jr0001_httprequest",$data);
	}

	function addEx($message,$sql="")
	{
		$CI =& get_instance();

		$code=$this->rndStr();
		$r=$CI->db->get_where("jr0002_exception",array("jr0002_code"=>$code))->row();
		while($r)
		{
			$code=$this->rndStr();
			$r=$CI->db->get_where("jr0002_exception",array("jr0002_code"=>$code))->row();
		}

		$data=array(
			"jr0002_code"=>$code
			,"jr0002_var_server"=>json_encode($CI->input->server(null))
			,"jr0002_var_post"=>json_encode($CI->input->post(null))
			,"jr0002_var_get"=>json_encode($CI->input->get(null))
			,"jr0002_ip"=>$CI->input->ip_address()
			,"jr0002_url"=>$CI->input->server("REQUEST_URI")
			,"jr0002_message"=>$message
			,"jr0002_sql"=>$CI->v->getString($sql)
			,"jr0002_tb0001_login"=>$CI->v->getString($CI->session->userdata("tb0001_login"))
		);
		$CI->db->insert("jr0002_exception",$data);
		return $code;
	}

	function addExOld($message,$sql="") // Old
	{
		$CI =& get_instance();
		
		$code=$this->rndStr();
		$r=$CI->db->get_where("jr0002_exception",array("tb0002_code"=>$code))->row();
		while($r)
		{
			$code=$this->rndStr();
			$r=$CI->db->get_where("jr0002_exception",array("tb0002_code"=>$code))->row();
		}

		$data=array(
			"tb0002_var_server"=>$this->arrToXML("server",$CI->input->server(null))
			,"tb0002_var_post"=>$this->arrToXML("post",$CI->input->post(null))
			,"tb0002_var_get"=>$this->arrToXML("get",$CI->input->get(null))
			,"tb0002_ip"=>$CI->input->ip_address()
			,"tb0002_url"=>$CI->input->server("REQUEST_URI")
			,"tb0002_message"=>$message
			,"tb0002_sql"=>$CI->v->getString($sql)
			,"tb0002_tb0101_id"=>$CI->v->getString($CI->session->userdata("tb0101_id"))
			,"tb0002_code"=>$code
		);
		$CI->db->insert("jr0002_exception",$data);
		return $code;
	}

	function date_parse_from_format($format, $date) {
		// reverse engineer date formats
		$keys = array(
			'Y' => array('year', '\d{4}'),
			'y' => array('year', '\d{2}'),
			'm' => array('month', '\d{2}'),
			'n' => array('month', '\d{1,2}'),
			'M' => array('month', '[A-Z][a-z]{3}'),
			'F' => array('month', '[A-Z][a-z]{2,8}'),
			'd' => array('day', '\d{2}'),
			'j' => array('day', '\d{1,2}'),
			'D' => array('day', '[A-Z][a-z]{2}'),
			'l' => array('day', '[A-Z][a-z]{6,9}'),
			'u' => array('hour', '\d{1,6}'),
			'h' => array('hour', '\d{2}'),
			'H' => array('hour', '\d{2}'),
			'g' => array('hour', '\d{1,2}'),
			'G' => array('hour', '\d{1,2}'),
			'i' => array('minute', '\d{2}'),
			's' => array('second', '\d{2}')
		);

		// convert format string to regex
		$regex = '';
		$chars = str_split($format);
		foreach ($chars AS $n => $char) {
			$lastChar = isset($chars[$n - 1]) ? $chars[$n - 1] : '';
			$skipCurrent = '\\' == $lastChar;
			if (!$skipCurrent && isset($keys[$char])) {
				$regex .= '(?P<' . $keys[$char][0] . '>' . $keys[$char][1] . ')';
			} else if ('\\' == $char) {
				$regex .= $char;
			} else {
				$regex .= preg_quote($char);
			}
		}

		$dt = array();
		$dt['error_count'] = 0;
		// now try to match it
		if (preg_match('#^' . $regex . '$#', $date, $dt)) {
			foreach ($dt AS $k => $v) {
				if (is_int($k)) {
					unset($dt[$k]);
				}
			}
			if (!checkdate($dt['month'], $dt['day'], $dt['year'])) {
				$dt['error_count'] = 1;
			}
		} else {
			$dt['error_count'] = 1;
		}
		$dt['errors'] = array();
		$dt['fraction'] = '';
		$dt['warning_count'] = 0;
		$dt['warnings'] = array();
		$dt['is_localtime'] = 0;
		$dt['zone_type'] = 0;
		$dt['zone'] = 0;
		$dt['is_dst'] = '';
		return $dt;
	}

	function getHtmlDateTime($v,$f="d.m.Y"){
		if(!$v) return "";
		$v=strtotime($v);
		if($v==strtotime("1970-01-01")) return "";
		return date($f,$v);
	}

	function getDate($v,$m=0){
		$format = '@^(?P<day>\d+).(?P<month>\d+).(?P<year>\d{4}) (?P<hour>\d+):(?P<minute>\d+):(?P<second>\d+)$@';
		$d=null;
		if(preg_match($format, $v, $dateInfo)){
			$d=mktime(
				$dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
				$dateInfo['month'], $dateInfo['day'], $dateInfo['year']
			);
		}

		$format = '@^(?P<day>\d+).(?P<month>\d+).(?P<year>\d{4}) (?P<hour>\d+):(?P<minute>\d+)$@';
		if(preg_match($format, $v, $dateInfo)){
			$d=mktime(
				$dateInfo['hour'], $dateInfo['minute'], 0,
				$dateInfo['month'], $dateInfo['day'], $dateInfo['year']
			);
		}

		$format = '@^(?P<day>\d+).(?P<month>\d+).(?P<year>\d{4})$@';
		if(preg_match($format, $v, $dateInfo)){
			$d=mktime(
				0, 0, 0,
				$dateInfo['month'], $dateInfo['day'], $dateInfo['year']
			);
		}

		$d=$m==1?date("Y-m-d H:i:s",$d):$d;

		return $d;
	}

	function num2str($num, $full=1) {
		$nul='ноль';
		$ten=array(
			array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
			array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
			array('тиын' ,'тиын' ,'тиын',	 1), //array('копейка' ,'копейки' ,'копеек',	 1),
			array('тенге'   ,'тенге'   ,'тенге'    ,0), //array('рубль'   ,'рубля'   ,'рублей'    ,0),
			array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
			array('миллион' ,'миллиона','миллионов' ,0),
			array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= $this->num2str_a($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		if($full){
			$out[] = $this->num2str_a(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
			$out[] = $kop.' '.$this->num2str_a($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		}
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}

	function num2str_a($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $f5;
		$n = $n % 10;
		if ($n>1 && $n<5) return $f2;
		if ($n==1) return $f1;
		return $f5;
	}

	function barcodeUrl($c){
		$CI =& get_instance();
		/*$xml="<contract>
	<tr0001_id>".@$c->tr0001_id."</tr0001_id>
	<tr0001_id>".@$c->tr0001_id."</tr0001_id>
	<tr0001_created>".@$c->tr0001_created."</tr0001_created>
	<tr0001_createdby>".@$c->tr0001_createdby."</tr0001_createdby>
</contract>";*/
		//$xml=$CI->objectandxml->objToXML($c);
		//$data=json_encode($c);
		$data=json_encode(array(
			"rec_id"=>@$c->tr0001_id
			,"id"=>@$c->tr0001_id
			,"printed"=>date("c")
			,"printedby"=>@$CI->session->userdata("lb0003_id")
		));

		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(BARCODE_ENCRYPTION_KEY), $data, MCRYPT_MODE_CBC, md5(md5(BARCODE_ENCRYPTION_KEY))));
		//$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(BARCODE_ENCRYPTION_KEY), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5(BARCODE_ENCRYPTION_KEY))), "\0");
		return "/barcode/get.php?s=".urlencode("Digital signature\n".$encrypted);
	}

	function str2uni($str, $encoding = 'UTF-8'){        
		$str = mb_convert_encoding($str,"UCS-4BE",$encoding);
		$ords = array();
		for($i = 0; $i < mb_strlen($str,"UCS-4BE"); $i++){        
			$s2 = mb_substr($str,$i,1,"UCS-4BE");                    
			$val = unpack("N",$s2);            
			$ords[] = $val[1];                
		}

		$newstr = '';    
		foreach($ords as $key => $val) $newstr .= "\uc0\u".$val;
		return($newstr);
	}

	function wTrim($string, $count, $ellipsis=true){
		$words = explode(' ', $string);
		if (count($words) > $count){
			array_splice($words, $count);
			$string = implode(' ', $words);
			if (is_string($ellipsis)){
				$string .= $ellipsis;
			}
			elseif ($ellipsis){
				$string .= '&hellip;';
			}
		}
		return $string;
	}

	function getTable($table,$groupKey,$where=null,$hideDisabled=true,$hideDeleted=true)
	{
		$CI =& get_instance();
		$prefix=substr($table,0,strpos($table,'_')+1);
		$sql="SELECT * FROM {$table} WHERE {$prefix}rec_id IN (SELECT MAX({$prefix}rec_id) FROM {$table} GROUP BY {$groupKey})";
		$sql.=$hideDisabled?" AND {$prefix}disabled=0":"";
		$sql.=$hideDeleted?" AND {$prefix}deleted=0":"";

		$params=null;
		if(is_array($where))
		{
			$params=array();
			foreach($where as $k=>$v) 
			{
				$sql.=" AND ({$k}=?)";
				$params[]=$v;
			}
		}
		else if($where)
		{
			$sql.=" AND {$where}";
		}

		$CI =& get_instance();
		return $CI->db->query($sql,$params);
	}

	function tableAsList($table, $fldValue, $curValue, $where="", $top=0, $htmlMask="")
	{
		$sql=strpos($table,"SELECT ") !== false?$table:"SELECT * FROM {$table}".(empty($where)?"":" WHERE {$where}");
		//$sql="SELECT * FROM {$table}".(empty($where)?"":" WHERE {$where}");
		//echo $sql;
		$CI =& get_instance();
		//$query=$CI->db->get("vw_{$table}");
		$query=$CI->db->query($sql);

		$html="";
		$rowIndex=0;
		foreach ($query->result_array() as $row){
			if($top&&$rowIndex>$top) break;
			$rowIndex++;
			$item=$htmlMask;
			if($fldValue){
				$item=str_replace("%VALUE%",$row[$fldValue],$htmlMask);
				$item=str_replace("%SELECTED%",$curValue==$row[$fldValue]?" selected":"",$item);
			} 

			foreach($row as $i=>$j) $item=str_replace("%".$i."%",$j,$item);
			$html.=$item;
		}
		return $html;
	}

	function htmlDropDown($id, $table, $fldValue, $fldText, $curValue, $attr, $allowEmpty=true, $disabled=false, $ajaxMode=false, $where="")
	{
		$CI =& get_instance();

		$l=$CI->session->userdata("lang");
		$l=!in_array($l,array(1,2))?1:$l;

		$query=$table;
		if(!is_array($table)&&!is_object($table))
		{
			if(is_array($fldText)) $fldText=$fldText[$l-1];
			$sql=strpos($table,"SELECT ") !== false?$table:"SELECT * FROM {$table}".(empty($where)?"":" WHERE {$where}");
			if($ajaxMode)
			{
				$sql.=(empty($where)?"":" AND ")."{$fldValue}='{$curValue}' LIMIT 1,1";
				$attr["ajax-data-src"]=$table;
				$attr["ajax-data-value"]=$fldValue;
				$attr["ajax-data-text"]=$fldText;
			}
			//echo $sql;
			//$query=$CI->db->get("vw_{$table}");
			$query=$CI->db->query($sql)->result_array();
		}
		else if(is_object($table)) $query=array((array)$table);
		else if(is_array($table))
		{
			$query=array();
			foreach($table as $r) $query[]=(array)$r;
		}

		if($disabled==true){$attr["disabled"]="";}
		
		if(isset($attr["data-placeholder"])){
			$options=$allowEmpty?array("0"=>$attr["data-placeholder"]):array();
		}
		else 
			$options=$allowEmpty?array("0"=>"Не указано..."):array();

		foreach ($query as $row) $options[$row[$fldValue]]=$row[$fldText];

		$tmp="";
		$attr["id"]=$id;
		foreach ($attr as $k=>$v) $tmp.=($tmp?" ":"")."{$k}=\"{$v}\"";
		$attr=$tmp;

		$html=form_dropdown($id, $options, $curValue);
		$html=str_replace("\"{$id}\"","\"{$id}\" {$attr}",$html);
		return $html;
	}

	function rndStr($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function linkJavaScripts($d,$p)
	{
		$html="";
		$files=scandir($d);
		foreach($files as $f)
		{
			if(in_array($f,array(".","..","optional"))||substr($f,strlen($f)-3)==".kz") continue;
			$html.="<script src=\"{$p}/{$f}\"></script>\n";
		}
		return $html;
	}

	function linkCss($d,$p)
	{
		$html="";
		$files=scandir($d);
		foreach($files as $f)
		{
			if(in_array($f,array(".","..","optional"))||substr($f,strlen($f)-3)==".kz") continue;
			$html.="<link rel=\"stylesheet\" href=\"{$p}/{$f}\">\n";
		}
		return $html;
	}
}