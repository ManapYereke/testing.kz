<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Version 0.9, 6th April 2003 - Simon Willison ( http://simon.incutio.com/ )
   Manual: http://scripts.incutio.com/httpclient/
*/

class V {
	function __construct()
	{
	}

	// datetime
	public function getDateTime($v,$alt=0)
	{
		if(!isset($v)) return $alt;
		if(empty($v)||!$v) return $alt;
		$i=preg_match('/^(\d+)\.(\d+)\.(\d{4}) (\d+):(\d+):(\d+)$/',$v,$m1);
		$j=preg_match('/^(\d+)\.(\d+)\.(\d{4}) (\d+):(\d+)$/',$v,$m2);

		if(!$i&&!$j) return $alt;
		$m0=$i?$m1:$m2;

		$y=count($m0)>3?$m0[3]:0;
		$m=count($m0)>2?$m0[2]:0;
		$d=count($m0)>1?$m0[1]:0;
		$h=count($m0)>4?$m0[4]:0;
		$i=count($m0)>5?$m0[5]:0;
		$s=count($m0)>6?$m0[6]:0;

		return date("Y-m-d H:i:s",strtotime("{$y}-{$m}-{$d} {$h}:{$i}:{$s}"));
	}

	// date
	public function getDate($v,$alt=0)
	{
		if(!isset($v)) return $alt;
		if(empty($v)||!$v) return $alt;

		if(!preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/',$v,$m)) return $alt;
		return date("Y-m-d",strtotime($m[3]."-".$m[2]."-".$m[1]));
	}

	// time
	public function getTime($v,$alt=0)
	{
		if(!isset($v)) return $alt;
		if(empty($v)||!$v) return $alt;

		$i=preg_match('/^(\d{2}):(\d{2}):(\d{2})$/',$v,$m1);
		$j=preg_match('/^(\d{2}):(\d{2})$/',$v,$m2);
		if(!$i&&!$j) return $alt;

		$m0=$i?$m1:$m2;
		$h=str_pad($m0[1],2,"0",STR_PAD_LEFT);
		$m=str_pad($m0[2],2,"0",STR_PAD_LEFT);
		$s=isset($m0[3])?!empty($m0[3])?$m0[3]:0:0;
		$s=str_pad($s,2,"0",STR_PAD_LEFT);

		return "{$h}:{$m}:{$s}";
	}

	// decimal, float
	public function getDecimal($v,$alt=0)
	{
		$v=str_replace(" ","",$v);
		if(!isset($v)) return $alt;
		if(empty($v)) return $alt;
		if(!preg_match('/^[\d\.]+$/',$v)) return $alt;
		if(!is_numeric($v)) return $alt;
		return $v;
	}

	// number, int
	public function getIntArray($v,$alt=array(),$separator=",")
	{
		if(!isset($v)) return $alt;
		if(empty($v)) return $alt;
		if(!count($v)) return $alt;
		if(!preg_match('/^[\d'.$separator.']+$/',$v)) return $alt;
		$v=explode($separator,$v);
		return $v;
	}

	// number, int
	public function getInt($v,$alt=0)
	{
		if(!isset($v)) return $alt;
		if(empty($v)) return $alt;
		if(!preg_match('/^\d+$/',$v)) return $alt;
		return $v;
	}

	// phone
	function getPhone($v,$alt=0) {
		$v=str_replace("+","",$v);
		$v=str_replace("(","",$v);
		$v=str_replace(")","",$v);
		$v=str_replace(" ","",$v);
		$v=str_replace("-","",$v);

		if(!$v) return $alt;
		if(!preg_match("/^\d+$/i",$v)) return $alt;
		if(!preg_match("/(\d{10})$/i",$v,$m)) return $alt;
		if(!$m||!is_array($m)||count($m)<1) return $alt;
		return $m[1];
	}
	function clearPhone($sPhone = '')
	{
		$sArea=7;
		$sPhone= preg_replace('/[^0-9]/','',$sPhone);
		if(strlen($sPhone) != 11) return(False);
		if(substr($sPhone, 0,1)==='8')$sArea = 7;
		$sPhone[0] = $sArea;
		//return $sPhone;
		return $sPhone;
	}

	// string, text
	function getString($v,$alt="") {
		return $v?$v:$alt;
	}

	public function n($value,$default=0)
	{
		if(!isset($value)) return $default;
		if(empty($value)) return $default;
		if(!is_numeric($value)) return $default;
		return $value;
	}

	public function s($value,$default="")
	{
		if(!isset($value)) return $default;
		return trim($value);
	}

	public function p($value,$default=0)
	{
		if(!isset($value)) return $default;
		if(!preg_match("/^[\+]*\d+$/i",$value)) return $default;
		if(!preg_match("/(\d{10})$/i",$value,$matches)) return $default;
		if(!$matches) return $default;
		if(!is_array($matches)||count($matches)<1) return $default;
		return $matches[1];
	}

	function GetCurl($url="")
	{
		if($url=="")return;
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			CURLOPT_HTTPHEADER => array(
				"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
//				"Accept-Encoding: gzip, deflate, br",
//				"Accept-Encoding: br",
				"Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3",
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Cookie: culture=RU; ASP.NET_SessionId=kawm5srtgwzfsklow13bf3cl; .AspNet.ApplicationCookie=rm-BpxRwqBKZBD_Hh6_2PO91zYLOFZzsGcVs3WQgTHCUfWQ99Y99YvisIqMCEtPaRhjs4cXiZIuNCx-kpbD6qomL4qMmQt2zCYCt2G7J_AijW2KfZRmDlu6P3I14T8h8fNb49Gvv_fGonv5HHiyMm1Vc8ZxYTQ5YaKzDm5PWep2Fm3j_cRRG472AdcTGerHPp2ZDNHyQCgaEg_ghd6HYW_F8Xkc67E5SjDACXQ56LxH-Gs42XXfAtzckX88ra6T5A_q5ZHMQyOl6nxOK9Fp-U5H8FOjXYRXoqlyN2kygsjJNy4CJqAPimfYK-nBX9iA-lg2e-sKcoP7dLf6GYNWkRKYYxxxvBkUxxuDPvyjGPUtfGvKpL9liKTexNmbHQBLBsgGHv160kMwl12_5XjbMqLazgidIVJUNyyndSpw0d3AOlYqQVocSRWkhZfp318QF-MPA0_Bav6CraRhcw88xT0PGaPA9Fn3oUREFDjbqQKQ2zM9L9pKEu-rJqQ87Yc34Hloxftejhsa7i_VkflezI9HXjCb6ZWMIS4M38hSGa-2-y4RF_LF3MxQwTSxthNjVDna6S3CLxv7OlunWMtnTKdEkk624v5yzN2_OPSW6Tpip4YOCloDAqfPs_8ovSFbbFXYsqyQDNui94xlIeBElicXZ6LlBFV_jL4N1ZphQtFDhb3YFPyfRuHwq6z7qKWuBzhzEgk5uE1ONnHnc2zMcCoavyqgCpDl4WiAVf79rnxahd1JxNsJ7uqGDm3-uaaRHzceKpot7R8sxgNutJHkjS0zT60G8QuTka3HO-fajxsgp_ZZOMvMpP-xTpiUW99g0QT3kWsHJM1ReiChJVPx94yGUXV5VwVEeBQUnJ7UmVJZDRESc8Md2x7vC4EXRt-94ZV9UcRMliMLlgjrmEtoPYIXTXu7yb34LM-aqPxApXglkCKC88VeDm4CRF2X1YcLEvcyVYko0hUvNoalB33n3gqYQ-HCrS3StpK4-yjG6k47-PnSUGLj-JdrZI1HjFUHmD-u6xHhAmfkbI1l3WP-B9vyfOnTy0uK5_AnK-xd217QNPokggiU3sETjcuuz0YNpF-ibFOXk6Lk93lSoaphqGri_MyXuiZuEIqn0fOrhCAu8m2k4q3ENo6hydKOPn_PhdCN_GpMPVhX81o7iJ04JKfRo6pkQ12uwKRF2XzWPwfUhE_-rxGdn35LVmqcUQ_d3JSb0ewkjleLNTsE44CEUpOOSobj05Qf_V79kHD_wR1sGemBiRNOlCM_txlyK3qLoYyEVU84hT7l5nYqArnXIEsEoodnGfOuiTVRYAAwSTP3CiffHqe_hBlTqro-GinsQFkX8GV_v494je9dNG0mk6KEkVfHcnLPNm737WpEUU842jHJkmeAZVEcQmAVZ540iBE3c5Fo5EbEIiSjcI5sPdeLD9qzUMoyk-d-WvsaRRsvjw89gx5ORXhzUKW1j4XhV_ELJxHsc79_rMF0FGUI0Os-Qe62p5G3pHKq6-nw7N1nbJE5eNVV9wrta4sVPpxB5SFLfBO9nVTMklEX0XeHwypIeQ_1eY5N1-yxtCAAHIjlVZViezvg7oMMT93KpBYnUGv8fk9NSS8e4QwM9AMMZAbjMDNHa9hvgMis8KaEzMCqCGG6Y1naivUG7oot9zWK9JTPFqkdVpEX3shrZIyVP4wBOtZiggzL3XXG1jS11OITlauV2qv-CpEP-baBWZE_0QoBho3HanVJ-zCNtS2TUm05wc_pu1XJKZhQKvtFkYmVNAULoFwPnnHmi-35G9hwzkDx8RzOCnrsMpCLRdmWuEkpZsy5I_Sgggk87pWRpUmukApA3w7CroFdHJ3o0722jgNrhyZTxnQ2NEQiVdswGK4w0gOZ_iZqaaaiHc8oTgYdyGurcf2tie-bC-94ExEDCv-8SAb8le8bvctVSdfju_KV7J9iMNWQ1bDqxDwgDS21tnhwac1DBrEArbM03l7pJzGvJ3lkv2Lf1kvSvIbsxjcfGZY_98uKeRzqfOSacx-N59IDTuKC-9L7T_c3rww6IAuziwYoVTWCVScab3QP1FSNo9Cy9tdsrYuyHot7_HRM9CyJnBVbuL9MFZKMXACMjtC-tQG7OAWp0zz_7jgfp3kvjnylbu1pCuO1TaIg491iH3dL1c8LqyRF52MmRMPuNEjW6Hi8JgI_PCBAnvms-PykLow--CGXAdpkLV6YOu-kOE5SdEsCmUWnrY-6PrHG2OrC3esilwZBPJbYbm7UtHIElrNP2aCp_uZxnRhF9KLEM5dE49j4kU-27C4UqrL56LeuFsXuGnfO2fNJdQJ_N9lhaPT3_UrLi0efO0TXRkNwmM32onHw1A9xOWFI38ipkjjeuxbnRe8T17HQxcWpBoBm0L6_thYGO_0zIvt6xI_JCN4JvUt7wwvHAAOCImlC9l4ULli1jRhD1YEVUjYdzDhKTUc5AEnU6XtRdsKLNNJqXzRXAegwUNJScGMMyfrF8VaLWmVPhdK41nDGZF28TV3LVBBwbLJW3BtEUesn6HAo1JwFHX7YMAyQWZnmxUDGnvcx-w8kzRqQ3UJFEd9iPEhPaMioP857inELru5zLKzNmFVGdEL7QxHKDquuVkd--LaTcuCMI9Zbzi-XplNe8irq1Uo3wkOBuaG9X6fxTQLVGFCl93PmqFdqFVRV0Sya4VAfgoeXew_jR3PxMapV7zE5TJh_78rDJG79qS9LhwyOplGU08O6g-6kcFlb27V0tP9vLf9ya0wzQOHDcljbgMMTap_lb-KQEy06pVI7WucylMML9NdNzQZDM7tq9-kJP_LuKByGcqpIsc939nwK7E3Y0McwH-2bwdrDeacoNjVX89CpHdtLMFnqkaImnGeRpXF9wFlNgD_Luw59_mdur8MNZRQB3lNN6_DHvE2YaYmd73XiJuGbScg5HKbkkqRydoitbSFUjs8KscABdPpD2ysy9-KMcUyZ1ivXaIhGWZXBINfk_5V_ggS4re6DT434zyz5u8j3H9BYARYgjUKr072BfO91CF7xR3VAdfCnz9iouNuJn60LgE457CJl5L8GiaBdnHKC6ImXwLMxIwd7iNQhiYKfIaPgEJ-tS-38WUkzMpbrkTB-wvkIyCHXTLnmTGKARVkdPb7kAHrz66n8k7uBpg3H0tj2QyOsjcyJBw9ulNfkfKLkxec1uibkPj02b8e6PgfT-RbYeXnNXBwn5nA",
				"Postman-Token: d727855d-1403-44fe-b411-b6a9cddb4a21",
				"Pragma: no-cache",
				"Upgrade-Insecure-Requests: 1",
				"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:65.0) Gecko/20100101 Firefox/65.0",
//				"cache-control: no-cache"
				"cache-control: no-store, no-cache, must-revalidate"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		}

		return $response;
	}

	function GetParserHtml($site, $findtags, $charset=false,$pos=0,$debug=false,$raw=false){
		$arr=array();
		//создаём новый объект
		if($charset == true){header("content-type: text/html; charset=UTF-8");}
		$html = new simple_html_dom();

		$html = $this->GetCurl($site);
		$html = str_get_html($html);

//		$html = file_get_html($site);
		//$html = file_get_html($site);
//		foreach($html->find('img') as $element)
//			echo $element->src . '<br>';

//		echo "HTML2:::\n";
//		echo $html;
//		echo "\n\n\n";
//		die();

		if($html->innertext!='' && count($html->find('a'))) {
			foreach($html->find($findtags) as $a){
				$text = $a->innertext;
				//echo $text;
				$text= strip_tags($text);
				$text = preg_replace('/[^0-9.]+/iu','',$text);
				array_push($arr, $text);
			}
		}else{
			$arr[0]=$html->innertext;
		}

		if($debug==true){
			var_dump($arr[$pos]);
			print_r($arr);
		}

		//$html->clear();
		//unset($html);
		return $arr[$pos];
	}



	function getRates()
	{
		$NacKursARR = array();
		$url = "http://www.nationalbank.kz/rss/rates_all.xml";
		$dataObj = simplexml_load_file($url);
		if ($dataObj){
			foreach ($dataObj->channel->item as $item){
				// echo "title: ".$item->title."<br>";
				// echo "pubDate: ".$item->pubDate."<br>";
				// echo "description: ".$item->description."<br>";;
				// echo "quant: ".$item->quant."<br>";
				// echo "index: ".$item->index."<sbr>";
				// echo "change: ".$item->change."<br>";
				$NacKurs=strval($item->title);

				if ($NacKurs=='USD'||$NacKurs=='RUB'||$NacKurs=='EUR') {
					$NacKursARR+=[$NacKurs=>strval($item->description)];
				}
			}

		}
		//print_r($NacKursARR);
		return $NacKursARR;
	}

	function totalSumArray($arr='')
	{
		$sum=0;
		foreach ($arr as $k=>$v){
			$sum+=$arr[$k]['tr0001_amount'];
		}
		return $sum;
	}

}

?>