<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class TableController {
	function __construct()
	{
	}

	private function addWatermark($imgPath="",$watermarkPath=""){
		$OUTPUT_QUALITY = 30; // Quality is a number between 0 (best compression) and 100 (best quality)

		if($imgPath) {
			$imageSize = getimagesize($imgPath);
			$imgtype = image_type_to_mime_type($imageSize[2]);
			switch ($imgtype) {
				case 'image/jpeg':
				$image = imagecreatefromjpeg($imgPath);
				break;
				case 'image/gif':
				$image = imagecreatefromgif($imgPath);
				break;
				case 'image/png':
				$image = imagecreatefrompng($imgPath);
				break;
				default:
				die('Invalid image type.');
			}

			$width = $imageSize[0];
			$height = $imageSize[1];

			#-------------------- \/ ZOOM \/ --------------------
			// $image_zoom = imagecreatetruecolor($width * 1.5, $height * 1.5);
			// imagecopyresampled($image_zoom
			// 	,$image
			// 	,0
			// 	,0
			// 	,0
			// 	,0
			// 	,$width * 1.5
			// 	,$height * 1.5
			// 	,$width
			// 	,$height
			// );
			// $image = $image_zoom;
			#-------------------- /\ ZOOM /\ --------------------

			#-------------------- \/ CROP \/ --------------------
			$max_width = 400 * 2;
			$max_height = 300 * 2;
			$delta = $max_width / $max_height;
			if($width / $height < $delta){
				$new_image = imagecreatetruecolor($width, $width / $delta);
				imagecopy($new_image, $image, 0, 0, 0, $height / 2 - ($width / $delta) / 2, $width, $width / $delta);
				$image=$new_image;
				$height=$width / $delta;
			}elseif($width / $height >= $delta){
				$new_image = imagecreatetruecolor($height * $delta, $height);
				imagecopy($new_image, $image, 0, 0, $width / 2 - ($height * $delta) / 2, 0, $height * $delta, $height);
				$image = $new_image;
				$width = $height * $delta;
			}else{
				$new_image = imagecreatetruecolor($width, $width / $delta);
				imagecopy($new_image, $image, 0, 0, 0, $height / 2 - ($width / $delta) / 2, $width, $width / $delta);
				$image = $new_image;
				$height = $width / $delta;
			}
			// imagedestroy($new_image);
			#-------------------- /\ CROP /\ --------------------

			#-------------------- \/ RESIZE \/ --------------------
			$max_width = 400 * 2;
			$max_height = 300 * 2;
			# taller
			// if ($height > $max_height) {
			// 	$width = ($max_height / $height) * $width;
			// 	$height = $max_height;
			// }

			# wider
			// if ($width > $max_width) {
				$height = ($max_width / $width) * $height;
				$width = $max_width;
			// }

			$image_p = imagecreatetruecolor($width, $height);
			imagecopyresampled($image_p
				,$image
				,0
				,0
				,0
				,0
				,$width
				,$height
				,imagesx($image)
				,imagesy($image)
			);
			$image = $image_p;
			imagedestroy($imagep);
			#-------------------- /\ RESIZE /\ --------------------

			#-------------------- \/ LOAD RESIZED \/ --------------------
			imagejpeg($image, $imgPath, $OUTPUT_QUALITY); // SAVE RESIZED
			imagedestroy($image);

			$imageSize = getimagesize($imgPath);
			$imgtype = image_type_to_mime_type($imageSize[2]);
			switch ($imgtype) {
				case 'image/jpeg':
				$image = imagecreatefromjpeg($imgPath);
				break;
				case 'image/gif':
				$image = imagecreatefromgif($imgPath);
				break;
				case 'image/png':
				$image = imagecreatefrompng($imgPath);
				break;
				default:
				die('Invalid image type.');
			}

			$width = $imageSize[0];
			$height = $imageSize[1];
			#-------------------- /\ LOAD RESIZED /\ --------------------
		}

		#-------------------- \/ WATARMARK \/ --------------------
		if($watermarkPath) {
			$watermarkSize = getimagesize($watermarkPath);
			$imgtype = image_type_to_mime_type($watermarkSize[2]);
			$watermark = imagecreatefrompng($watermarkPath);

			$watermark_o_width = imagesx($watermark);
			$watermark_o_height = imagesy($watermark);

			$newWatermarkWidth = $width/4;
			$newWatermarkHeight = $watermark_o_height * $newWatermarkWidth / $watermark_o_width;

			$top = 20;
			$left = 20;
			$sx = imagesx($watermark);
			$sy = imagesy($watermark);

			imagecopyresized(
				$image,					// Destination image
				$watermark,				// Source image
				$top,					// Destination X
				$left,					// Destination Y
				0,						// Source X
				0,						// Source Y
				$newWatermarkWidth,		// Destination W
				$newWatermarkHeight,	// Destination H
				imagesx($watermark),	// Source W
				imagesy($watermark)		// Source H
			);
			imagedestroy($watermark);
		}
		#-------------------- /\ WATARMARK /\ --------------------

		// header('Content-type: image/png');
		// switch ($imgtype) {
		// 	case 'image/jpeg':
			imagejpeg($image, $imgPath);
		// 	break;
		// 	case 'image/gif':
		// 	imagegif($image, $imgPath);
		// 	break;
		// 	case 'image/png':
		// 	imagepng($image, $imgPath);
		// 	break;
		// 	default:
		// 	die('Invalid image type.');
		// }
		imagedestroy($image);
	}

	function deleteDir($dirPath){
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	function clearDir($dirPath){
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach($files as $file){
			if(is_file($file))
				unlink($file);
		}
	}
	
	function validateColumn($type,$v)
	{
		$CI =& get_instance();
		$v=$CI->v->getString($v);
		// $v=$type=="datetime"?$CI->v->getDateTime($v):$v;
		$v=$type=="striptags"?strip_tags($v):$v;
		$v=$type=="time"?$CI->v->getTime($v):$v;
		$v=$type=="date"?$CI->v->getDate($v):$v;
		$v=$type=="phone"?$CI->v->getPhone($v):$v;
		$v=$type=="int"?$CI->v->getInt($v):$v;
		$v=$type=="decimal"?$CI->v->getDecimal($v):$v;
		// $v=$type=="bool"?($v=="1"?1:0):$v;
		$v=$type=="bool"?($v?1:0):$v;
		return $v;
	}

	/*
		index.php/[CONTROLLER]/[ACTION]/[CMD]/[ID]
		processor(
			$table="[DB TABLE NAME]"
			,$columns=array(
				"[NAME1]"=>array(
					"iskey"=>[0|1]
					,"type"=>"[{string}|int|date|time|datetime|phone|bool|decimal]"
					,"template"=>"%FIELD NAME OR TEMPLATE%"
				)
				,"[NAME2]"=>array(
					"iskey"=>[0|1]
					,"type"=>"[{string}|int|date|time|datetime|phone|bool|decimal]"
					,"template"=>"%FIELD NAME OR TEMPLATE%"
				)
				,"[NAME...]"=>array(
					"iskey"=>[0|1]
					,"type"=>"[{string}|int|date|time|datetime|phone|bool|decimal]"
					,"template"=>"%FIELD NAME OR TEMPLATE%"
				)
			)
		);
	*/

	public function processor($table,$has_arch=true,$update_timestamp=true,$columns,$cmdSegment=2,$onSaveSuccess=null,$customWHERE=null)
	{
		$CI =& get_instance();

		preg_match('/^([^_]+)/',$table,$m);
		$tablePrefix=$m[1];

		if(!$CI->input->is_ajax_request()) return;

		$cmd=$CI->uri->segment($cmdSegment);
		if(!in_array($cmd,array("lst","save","del")))throw new Exception("Передана недопустимая команда операции {$cmd}.");

		$x=0;
		foreach($columns as $i=>$j)
		{
			if(!$j["iskey"]) continue;
			$v=$CI->uri->segment($x+$cmdSegment+1);
			$v=$this->validateColumn($j["type"],$v);

			if(!$v&&!in_array($j["type"],array("int","bool"))&&in_array($cmd,array("del"))) throw new Exception("В URL отсутствует значение ключа {$i}.");
			$columns[$i]["value"]=$v;
			$x++;
		}

		$data=array();
		foreach($columns as $i=>$j)
		{
		$v="";
		if(strpos($i,"_createdby") !== false) $v=$CI->session->userdata("tb0101_id");
		elseif(strpos($i,"_created") !== false && $update_timestamp) $v=date("Y-m-d H:i:s");
		elseif(strpos($i,"_created") !== false && !$update_timestamp) continue;
			elseif($j["type"]=="array"&&is_array($CI->input->post($i))) $v=$this->validateColumn($j["type"],implode(",",$CI->input->post($i)));
				// else $v=$this->validateColumn($j["type"],urldecode($CI->input->post($i)));
				else $v=$this->validateColumn($j["type"],$CI->input->post($i));
			$columns[$i]["value"]=$v;
			$data[$i]=$columns[$i]["value"];
		}

		$keyColumns=array();
		foreach($columns as $i=>$j) if($j["iskey"]) $keyColumns[$i]=$j;

		$keyColumnsData=array();
		foreach($keyColumns as $i=>$j) $keyColumnsData[$i]=$j["value"];

		if($customWHERE) 
		{
			foreach($customWHERE as $i=>$j)
			{
				if(!is_array($j))
				{
					$CI->db->where($i,$j);
					continue;
				}
				$CI->db->where_in($i,$j);
			}
		}
		// die($CI->db->_compile_select());
		$currentRow=$CI->db->get_where($table,$keyColumnsData)->row_array();


		if($cmd=="save")
		{
			$CI->db->trans_start();
			try {
				if(!$currentRow||!count($currentRow))
				{
					$data_ins=$data;
					if(count($keyColumns)==1)
						if(!isset($keyColumns[array_keys($keyColumns)[0]]["autoinc"]))
							unset($data_ins["{$tablePrefix}_id"]);
					$CI->db->insert($table,$data_ins);

					$query = $CI->db->query('SELECT LAST_INSERT_ID() x');
					$last_id = $query->row();
					// die(print_r([ $last_id,$last_id->x ]));

					if(count($keyColumns)==1)
					{
						$a=array_keys($keyColumns)[0];
						if(!in_array($keyColumns[array_keys($keyColumns)[0]]["type"],["number","hidden"])) $data[$a]=$columns[$a]["value"];
						else{
							// $data[$a]=$CI->db->insert_id();
							// $columns[$a]["value"]=$CI->db->insert_id();
						
							$data[$a]=$last_id->x;
							$columns[$a]["value"]=$last_id->x;
						}
						// die(print_r([ $keyColumns,$columns ])); 
					}
				}
				else
				{
					if(!$currentRow||!count($currentRow)) throw new Exception("Запись не найдена");
					$CI->db->update($table,$data,$keyColumnsData);
					// die(print_r([ $CI->db->last_query(),$data ]));
				}

				if($has_arch){
					foreach($columns as $i=>$j)
					{
						if(strpos($i,"_createdby") !== false) $data[$i]=$CI->session->userdata("tb0101_id");
						elseif(strpos($i,"_created") !== false && !$update_timestamp) $data[$i]=date("Y-m-d H:i:s");
					}
					$CI->db->insert("{$table}_arch",$data);
				}

				// die(print_r([ $CI->db->last_query(),$data ]));

				if($onSaveSuccess) $onSaveSuccess($data);
			} catch (Exception $e){
				$CI->db->trans_rollback();
				$this->utils->addEx($e->getMessage(),$CI->db->last_query());
				throw $e;
			}
			if ($CI->db->trans_status() === FALSE)
			{
				$this->utils->addEx("Ошибка сохранения.\n".$CI->db->_error_message(),$CI->db->last_query());
				$CI->db->trans_rollback();
				throw new Exception($CI->db->_error_message());
			}
			$CI->db->trans_complete();
					
			$ds = DIRECTORY_SEPARATOR; 
			$uploadsPath="uploads".$ds.$CI->uri->segment(1).$ds.$data["{$tablePrefix}_id"];

			// die(print_r($data));
			// die("uploads".$ds.$CI->uri->segment(1).$ds.$data["{$tablePrefix}_id"]);

			// $uploadsPath="uploads".$ds.$CI->uri->segment(1);
			// $archivesPath="archives";

			if(isset($_FILES["{$tablePrefix}_file"])){
				if(!is_dir($uploadsPath))mkdir($uploadsPath, 0755, true);
				
				// die(print_r($_FILES["{$tablePrefix}_file"]));

			// if(!is_dir($archivesPath))mkdir($archivesPath, 0755, true);
				for($i=0;$i<count($_FILES["{$tablePrefix}_file"]["error"]);$i++){ 
				// if(count($_FILES["{$tablePrefix}_file"]["error"])==1){
					if ($_FILES["{$tablePrefix}_file"]["error"][$i] == UPLOAD_ERR_OK) {
						// if($i==0)$this->clearDir($uploadsPath);
						$tmp_name = $_FILES["{$tablePrefix}_file"]["tmp_name"][$i];

						$name = basename($_FILES["{$tablePrefix}_file"]["name"][$i]);
						$name = iconv("utf-8", "cp1251",$uploadsPath.$ds.$name);
						move_uploaded_file($tmp_name,$name);

						if($CI->input->post("{$tablePrefix}_watermark_light"))
							$this->addWatermark($name,"images".$ds."watermark-light.png");
						if($CI->input->post("{$tablePrefix}_watermark_dark"))
							$this->addWatermark($name,"images".$ds."watermark-dark.png");
					
					// $rootPath = realpath($archivesPath);
					// $zip = new ZipArchive();
					// $zip->open($archivesPath.$ds.$data["{$tablePrefix}_id"].".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);
					// $filePath=iconv("utf-8", "cp1251",$uploadsPath.$ds.$name);
					// $relativePath = substr($filePath, strlen($rootPath) + 1);
					// $zip->addFile($filePath, iconv('UTF-8', 'cp1251',$name)); 
					}
				};

				/* FOR SOME SITUATIONS
				if(count($_FILES["{$tablePrefix}_file"]["error"])>1){
					foreach ($_FILES["{$tablePrefix}_file"]["error"] as $key => $error) {
						if ($error == UPLOAD_ERR_OK) {
							$tmp_name = $_FILES["{$tablePrefix}_file"]["tmp_name"][$key];
							$name = basename($_FILES["{$tablePrefix}_file"]["name"][$key]);
							move_uploaded_file($tmp_name,iconv("utf-8", "cp1251",$uploadsPath.$ds.$name));
						}
					};
				};
				*/
			};
			return $data["{$tablePrefix}_id"];
		}
		elseif($cmd=="del")
		{
			$CI->db->trans_start();
			try {
				if(!$currentRow||!count($currentRow)) throw new Exception("Запись не найдена");

				$currentRow["{$tablePrefix}_deleted"]=1;
				if($has_arch)$CI->db->insert("{$table}_arch",$currentRow);
				$CI->db->delete($table,$keyColumnsData); 
			} catch (Exception $e){
				$CI->db->trans_rollback();
				$CI->utils->addEx($e->getMessage(),$CI->db->last_query());
				throw $e;
			}
			if ($CI->db->trans_status() === FALSE)
			{
				$this->utils->addEx("Ошибка удаления.\n".$CI->db->_error_message(),$CI->db->last_query());
				$CI->db->trans_rollback();
				throw new Exception($CI->db->_error_message());
			}
			$CI->db->trans_complete();

			$ds = DIRECTORY_SEPARATOR; 
			$uploadsPath="uploads".$ds.$CI->uri->segment(1).$ds.$currentRow["{$tablePrefix}_id"];
			$this->deleteDir($uploadsPath);
			
			return true;
		}

		if($customWHERE) 
		{
			foreach($customWHERE as $i=>$j)
			{
				if(!is_array($j))
				{
					$CI->db->where($i,$j);
					continue;
				}
				$CI->db->where_in($i,$j);
			}
		}
		
		$t1=$CI->db->get_where($table)->result_array();
		// die($CI->db->last_query());

		$t2=array();
		foreach($t1 as $i)
		{
			$arr=array();
			foreach($columns as $x=>$y)
			{
				$tpl=$y["template"];
				if(!$tpl) continue;

				$tpl=$this->displayValue($y["type"],$x,$i[$x],$tpl,$cmdSegment);

				foreach($columns as $a=>$b) {
					$tpl=str_replace("%$a%",$i[$a],$tpl);
				}
				$arr[]=$tpl; // no colunm index name
				$arr[$x]=$tpl; // no colunm index name
				// $arr[$x]=$tpl; // colunm index name
			}
			$t2[]=$arr;
		}

		die(json_encode(array("data"=>$t2)));
	}

	/*
	$this->tablecontroller->displayValue("[TYPE]","[NAME]","[VALUE]","[TEMPLATE]")
	*/
	
	function displayValue($type,$i,$j,$tpl,$cmdSegment)
	{
		$CI =& get_instance();

		$tpl=str_replace("%controller%",$CI->uri->segment(1),$tpl);
		$tpl=str_replace("%action%",$CI->uri->segment(2),$tpl);
		$tpl=str_replace("%cmd%",$CI->uri->segment($cmdSegment),$tpl);
		$tpl=str_replace("%id%",$CI->uri->segment($cmdSegment+1),$tpl);
		$tpl=str_replace("%site_url%",site_url($CI->uri->segment(1).($cmdSegment>2?"/".$CI->uri->segment(2):"")),$tpl);

		$res=[];
		if(preg_match_all('/(tb\d{4}_[a-z\d]+\.tb\d{4}_[a-z\d_]+)\b/i',$tpl,$tpl_arr))
		{
			$tpl_arr=$tpl_arr[0];
			for ($x=0; $x < count($tpl_arr); $x++)
			{ 
				preg_match('/(tb\d{4}_[a-z\d]+)\.(tb\d{4}_[a-z\d_]+)\b/i',$tpl_arr[$x],$m);
				preg_match('/^(tb\d{4})_[a-z\d]+/i',$m[1],$table);
				if($type=="array"){
					$CI->db->where_in($table[1]."_id",explode(",",$j));
					$r=$CI->db->get($table[0])->result_array();

					$s=[];
					foreach ($r as $tmp_r){
						$s[]=$tmp_r[$m[2]]?$tmp_r[$m[2]]:$CI->utils->t("Не определено");
					}

					$res[]=[$m[1].".".$m[2],implode(", ",$s)];
				}
				else{
					$r=$CI->db->get_where($table[0],array($table[1]."_id"=>$j))->row_array();
					$s=$r?$r[$m[2]]:$CI->utils->t("Не определено");
					$res[]=[$m[1].".".$m[2],$s];
				}
				
			}
			foreach ($res as $r) 
			{
				// if($type=="bool") $tpl=str_replace("%$r[0]%",$r[1]?"<span class=\"glyphicon glyphicon-ok-circle\"></span>":"",$tpl);
				if($type=="bool") $tpl=str_replace("%$r[0]%",$r[1]?"&#10004;":"&#10008;",$tpl);
				elseif($type=="datetime") $tpl=str_replace("%$r[0]%",date("d.m.Y H:i:s",strtotime($r[1])),$tpl);
				// elseif($type=="datetime") $tpl=str_replace("%$r[0]%",$r[1],$tpl);
				elseif($type=="date") $tpl=str_replace("%$r[0]%",date("d.m.Y",strtotime($r[1])),$tpl);
				// elseif($type=="time") $tpl=str_replace("%$r[0]%",date("H:i:s",strtotime($r[1])),$tpl);
				elseif($type=="decimal") $tpl=str_replace("%$r[0]%",number_format($r[1],2,","," "),$tpl);
				elseif($type=="phone")
				{
					preg_match('/^(\d{3})(\d{4})(\d{2})(\d{2})$/',$r[1],$m);
					$tpl=str_replace("%$r[0]%","({$m[0]}) {$m[1]}-{$m[2]}-{$m[3]}",$tpl);
				}
				else $tpl=str_replace("%$r[0]%",$r[1],$tpl);
			}
			return $tpl;
		}
		else
		{
			// if($type=="bool") $tpl=str_replace("%$i%",$j?"<span class=\"glyphicon glyphicon-ok-circle\"></span>":"",$tpl);
			if($type=="bool") $tpl=str_replace("%$i%",$j?"&#10004;":"&#10008;",$tpl);
			elseif($type=="datetime") $tpl=str_replace("%$i%",date("d.m.Y H:i:s",strtotime($j)),$tpl);
			// elseif($type=="datetime") $tpl=str_replace("%$i%",$j,$tpl);
			elseif($type=="date") $tpl=str_replace("%$i%",date("d.m.Y",strtotime($j)),$tpl);
			// elseif($type=="time") $tpl=str_replace("%$i%",date("H:i:s",strtotime($j)),$tpl);
			elseif($type=="decimal") $tpl=str_replace("%$i%",number_format($j,2,","," "),$tpl);
			elseif($type=="phone")
			{
				preg_match('/^(\d{3})(\d{4})(\d{2})(\d{2})$/',$j,$m);
				$tpl=str_replace("%$i%","({$m[0]}) {$m[1]}-{$m[2]}-{$m[3]}",$tpl);
			}
			else $tpl=str_replace("%$i%",$j,$tpl);
			return $tpl;
		}
	}
}
?>