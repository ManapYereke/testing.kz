<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?
$tb0101=$this->session->userdata("tb0101");

$readOnly=isset($readOnly)?$readOnly:false;
$cmdSegment=2;
$fields=[
	[
		"id"=>"tb0302_id"
		,"type"=>"hidden"
		,"iskey"=>true
	]	
	,[
		"id"=>"tb0302_tb0301_id"
		,"type"=>"dropdown"
		,"title"=>"Тест"
		,"sql"=>"SELECT * FROM tb0301_subtests ORDER BY tb0301_order"
		,"class"=>"selectpicker w-100"
		,"fieldId"=>"tb0301_id"
		,"fieldText"=>"tb0301_name_ru"
		,"desc"=>""
		,"required"=>true
	]	
	// ,[
	// 	"id"=>"tb0302_order"
	// 	,"type"=>"text"
	// 	,"mask"=>"000"
	// 	,"title"=>"Порядок сортировки (0-254)"
	// 	,"class"=>"form-control"
	// 	,"desc"=>"Индекс сортировки определяющий порядок"
	// 	,"required"=>true
	// ]	
	,[
		"id"=>"tb0302_answer"
		,"type"=>"text"
		,"mask"=>"0"
		,"title"=>"Номер правильного ответа (1-5)"
		,"class"=>"form-control"
		,"desc"=>"Порядковый номер правильного ответа"
		,"required"=>true
	]	
	,[
		"id"=>"tb0302_desc_ru"
		,"type"=>"textarea"
		,"title"=>"Текст на русском"
		,"class"=>"form-control"
		,"desc"=>"Название субтеста на русском"
		,"required"=>true
	]
	,[
		"id"=>"tb0302_desc_kz"
		,"type"=>"textarea"
		,"title"=>"Текст на казахском"
		,"class"=>"form-control"
		,"desc"=>"Название субтеста на русском"
		,"required"=>true
	]
];

for($i=1;$i<=5;$i++){
	$fields[]=[
		"id"=>"tb0302_answer".$i."_ru"
		,"type"=>"textarea"
		,"title"=>"Ответ ".$i." на русском"
		,"class"=>"form-control"
		,"desc"=>"Ответ на вопрос на русском"
		,"required"=>true
	];

	$fields[]=[
		"id"=>"tb0302_answer".$i."_kz"
		,"type"=>"textarea"
		,"title"=>"Ответ ".$i." на казахском"
		,"class"=>"form-control"
		,"desc"=>"Ответ на вопрос на русском"
		,"required"=>true
	];
}
echo $this->html->formGroups($fields);
// $params=array("fields"=>$fields);
// $params=array_merge($params,$this->_ci_cached_vars);
// $this->load->view("shared/form",$params);
?>

<script type="text/javascript">
	ONLOAD.push(function(){
		$('textarea').summernote({
			//airMode: true
			callbacks: {
				onImageUpload: function(files) {
					$summernote=this;
					SummernoteImageUpload(files[0]);
				}
			}
		});

		// tinymce.init({
		// 	selector: 'textarea',
		// 	plugins: 'image advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable',
		// 	toolbar: 'image addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
		// 	toolbar_mode: 'floating',
		// 	paste_data_images: true,
		// 	file_picker_types: 'image',
		// 	/* and here's our custom image picker*/
		// 	file_picker_callback: function (cb, value, meta) {
		// 		var input = document.createElement('input');
		// 		input.setAttribute('type', 'file');
		// 		input.setAttribute('accept', 'image/*');

				
		// 		Note: In modern browsers input[type="file"] is functional without
		// 		even adding it to the DOM, but that might not be the case in some older
		// 		or quirky browsers like IE, so you might want to add it to the DOM
		// 		just in case, and visually hide it. And do not forget do remove it
		// 		once you do not need it anymore.
				

		// 		input.onchange = function () {
		// 			var file = this.files[0];

		// 			var reader = new FileReader();
		// 			reader.onload = function () {
		// 				/*
		// 				Note: Now we need to register the blob in TinyMCEs image blob
		// 				registry. In the next release this part hopefully won't be
		// 				necessary, as we are looking to handle it internally.
		// 				*/
		// 				var id = 'blobid' + (new Date()).getTime();
		// 				var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
		// 				var base64 = reader.result.split(',')[1];
		// 				var blobInfo = blobCache.create(id, file, base64);
		// 				blobCache.add(blobInfo);

		// 				/* call the callback and populate the Title field with the file name */
		// 				cb(blobInfo.blobUri(), { title: file.name });
		// 			};
		// 			reader.readAsDataURL(file);
		// 		};

		// 		input.click();
		// 	}
		// 	// tinycomments_mode: 'embedded',
		// 	// tinycomments_author: 'Author name',
		// });
	})
</script>