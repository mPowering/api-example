<?php 

include_once '../mpower-api/api.php';

$api = new mPowerAPI('http://localhost:8000','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');

$resource = new mPowerResource();
$resource->title = "another test ".date('Y-m-d H:i:s');
$resource->description = "description of something or other";
$resource->image = "";
$resource->tags = ['Medical Aid Films'];

$api->add_resource($resource);
		
?>