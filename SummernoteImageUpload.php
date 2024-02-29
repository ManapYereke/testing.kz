<?php
if (!$_FILES['file']['name']) return;

if ($_FILES['file']['error']) die('Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error']);

$name = md5(rand(100, 200));
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$filename = $name.'.'.$ext;
$destination = dirname(__FILE__).'/upload/'.$filename; //change this directory
$location = $_FILES["file"]["tmp_name"];
move_uploaded_file($location, $destination);
echo 'https://certification.gov4c.kz/upload/'.$filename; //change this URL