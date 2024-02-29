<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed"); 

class Html {
	function __construct()
	{
	}

	function formGroups($arr)
	{
		$html="";
		foreach($arr as $a) $html.=$this->formGroup($a)."\n";
		return $html;
	}

	function inputGroupDate($params)
	{
		$CI =& get_instance();

		$id=$params["id"];
		$title=isset($params["title"])&&$params["title"]?$CI->utils->t(@$params["title"]):"";
		$class=isset($params["class"])&&$params["class"]?$CI->utils->t(@$params["class"]):"";
		$required=(isset($params["required"])&&@$params["required"])?"required":"";
		$value=(isset($params["value"])&&@$params["value"])?@$params["value"]:@$GLOBALS["CI"]->load->get_var($id);
		$value=date("d.m.Y", strtotime($value?$value:date("d.m.Y")));

		$html = <<< END
<div class="input-group date">
	<span class="input-group-addon">
		<span class="glyphicon glyphicon-calendar"></span>
	</span>
	<input type="text" name="$id" id="$id" value="$value" class="$class" placeholder="$title" $required>
</div>
END;
		return $html;
	}

	function formGroup($params)
	{
		$CI =& get_instance();

		$id=$params["id"];
		$title=isset($params["title"])&&$params["title"]?$CI->utils->t(@$params["title"]):"";
		$class=isset($params["class"])&&$params["class"]?@$params["class"]:"";
		$mask=isset($params["mask"])&&$params["mask"]?"data-mask=\"".@$params["mask"]."\"":"";
		$type=((isset($params["type"])&&@$params["type"])?@$params["type"]:"text");
		$caption=((isset($params["caption"])&&@$params["caption"])?$CI->utils->t(@$params["caption"]):"");
		$accept=((isset($params["accept"])&&@$params["accept"])?@$params["accept"]:"");
		$multiple=(isset($params["multiple"])&&@$params["multiple"])?"multiple":"";
		$mul=$multiple?"[]":"";
		$required=(isset($params["required"])&&@$params["required"])?"required":"";
		$readonly=(isset($params["readonly"])&&@$params["readonly"])?"readonly":"";
		$read=$readonly?"true":"false";

		$value=(isset($params["value"])&&@$params["value"])?@$params["value"]:@$GLOBALS["CI"]->load->get_var($id);

		$attr=isset($params["attr"])&&$params["attr"]?@$params["attr"]:"";
		$labels=isset($params["labels"])&&$params["labels"]?@$params["labels"]:"";

		$before=isset($params["before"])&&$params["before"]?@$params["before"]:"";
		$after=isset($params["after"])&&$params["after"]?@$params["after"]:"";

		$html="";
		// $value=@$GLOBALS["CI"]->load->get_var($id);

		if($type=="date")
		{
			$html = "<div class=\"form-group\">\n"
				."	<label for=\"$id\">$title</label>"
				.$this->inputGroupDate($params)
				."</div>";
		}
		else if($type=="dropdown")
		{
			$options="";
			
			if(isset($params["sql"])){
				$params["data"]=$CI->db->query($params["sql"])->result();
			}

			foreach($params["data"] as $r)
			{
				$fieldId=$params["fieldId"];
				$fieldId=$r->$fieldId;
				// die(print_r($r));

				$fieldText=$params["fieldText"];
				$fieldText=$r->$fieldText;

				$selected= in_array($fieldId,explode(",",$value))?" selected":"";
				$options.="<option{$selected} value=\"{$fieldId}\">{$fieldText}</option>\n";
			}
			$disab=(isset($params["readonly"])&&@$params["readonly"])?"disabled":"";
			$html = <<< END
<div class="form-group">
	<label for="$id">$title</label>
	<select $multiple class="$class" id="$id" name="$id$mul" $required $attr $disab placeholder="$title" data-title="$title" data-none-results-text="...">
$options
	</select>
	<br>
END;
	if($labels){
		$html .= <<< END
	<a select-value="" href="#" onclick="$(this).parent().find('select').selectpicker('val',$(this).attr('select-value'));return false"><span class="badge">Не выбрано</span></a>
END;
		foreach($labels as $label => $key){
			$rec_id=isset($key["id"])&&$key["id"]?$key["id"]:0;
			$inp=$rec_id?'<input type="number" class="form-control" value="0" min="0" style="width:70px">':'';
			$onclick=$rec_id?'
				$(this).parent().find(\'input:first()\').val($(this).attr(\'select-value\')
				+$(this).find(\'input\').val());
				return false
			':'$(this).parent().find(\'input:first()\').val($(this).attr(\'select-value\'));return false';
			$z=$key["title"];
			$label=$rec_id?$label:str_replace("/view/","",$label);
			$html .= <<< END
	<a select-value="$label" href="#"
		onclick="$onclick"
		onchange="$onclick"
	>
		<span class="badge">$z $inp</span>
	</a>
END;
	}
		}
$html .= <<< END
</div>
END;
		}
		else if(preg_match("/^client\d*$/",$type)>0)
		{
			preg_match("/(\d+)/",$type,$m);
			$m=@$m[0];
			if($m>1)
			{
				$html .= <<< END
<label for="$id">$caption</label>
    <div id="$id">
END;
				for($i=1;$i<=$m;$i++)
				{
					$value=@$GLOBALS["CI"]->load->get_var($id.$i);
					$html .= <<< END
<div class="input-group">
	<span class="input-group-addon" id="$id$i">$i.</span>
	<input type="text" class="$class" name="$id$i" id="$id$i" value="$value" $required $attr $readonly placeholder="$title" aria-describedby="$id$i">
	<span class="input-group-btn">
		<button class="btn btn-primary" onclick="clientfinder(this)" type="button"><span class="glyphicon glyphicon-search"></span></button>
	</span>
</div>
<br>
END;
				}
				$html .= <<< END
    </div>
END;
			}
			else
			{
				$html .= <<< END
<div class="form-group">
    <label for="$id">$title</label>
	<div class="input-group">
		<input type="text" class="$class" name="$id" id="$id" value="$value" $required $attr $readonly placeholder="$title" aria-describedby="$id">
		<span class="input-group-btn">
			<button class="btn btn-primary" onclick="clientfinder(this)" type="button"><span class="glyphicon glyphicon-search"></span></button>
		</span>
	</div>
</div>
END;
			}
		}
		else if($type=="checkbox")
		{
			$checked=$value?"checked":"";
			$html = <<< END
<div class="form-group">
	<label for="$id">$title</label>
	<div class="checkbox checkbox-primary" style="padding-left:20px">
		<input id="$id" name="$id" type="checkbox" $checked $required $attr $readonly>
		<label for="$id">$title</label>
	</div>
</div>
END;
		}
		else if($type=="textarea")
		{
			$html = <<< END
<div class="form-group">
	<label for="$id">$title</label>
	<textarea class="$class" id="$id" name="$id" $required $attr $readonly placeholder="$title">$value</textarea>
</div>
END;
		}
		else if($type=="file")
		{
			$html = <<< END
<div class="form-group">
	<label for="$id">$title</label>
	<div class="input-group">
		<span class="input-group-btn">
			<button class="btn btn-default" type="button" onclick="$('#$id').filestyle('clear');return false;"><span class="glyphicon glyphicon-trash"></span></button>
		</span>
		<input type="file" $multiple class="$class filestyle" data-buttonBefore="true" id="$id" name="$id$mul" data-disabled="$read" data-buttonText="&nbsp$caption" $required $attr accept="$accept">
	</div>
</div>
END;
		}
		else if($type=="hidden")
		{
			$html = <<< END
	<input type="hidden" id="$id" name="$id" value="$value" $attr>
END;
		}
		else
		{
			$html = <<< END
<div class="form-group">
	<label for="$id">$title</label>
	<input type="$type" $multiple class="$class" id="$id" name="$id" $mask $required $attr $readonly placeholder="$title" value="$value">
END;
	if($labels){
		$html .= <<< END
	<a select-value="" href="#" onclick="$(this).parent().find('input').val($(this).attr('select-value'));return false"><span class="badge">Не выбрано</span></a>
END;
		foreach($labels as $label => $key){
			$rec_id=isset($key["id"])&&$key["id"]?$key["id"]:0;
			$inp=$rec_id?'<input type="number" class="form-control" value="0" min="0" style="width:70px">':'';
			$onclick=$rec_id?'
				$(this).parent().find(\'input:first()\').val($(this).attr(\'select-value\')
				+$(this).find(\'input\').val());
				return false
			':'$(this).parent().find(\'input:first()\').val($(this).attr(\'select-value\'));return false';
			$z=$key["title"];
			$label=$rec_id?$label:str_replace("/view/","",$label);
			$html .= <<< END
	<a select-value="$label" href="#"
		onclick="$onclick"
		onchange="$onclick"
	>
		<span class="badge">
			$z
			$inp
		</span>
	</a>
END;
		}
	}
$html .= <<< END
</div>
END;
	}

		return $before.$html.$after;
	}
}