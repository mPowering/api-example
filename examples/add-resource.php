<?php 

include_once '../mpower-api/api.php';

$api = new mPowerAPI('http://localhost:8000','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');

$resource = new mPowerResource();
$resource->title = "another test ".date('Y-m-d H:i:s');
$resource->description = "description of something or other";
$resource->image = "/home/alex/temp/image.jpg";
$resource->tags = ['Global Health Media Project', 'Africa', 'Laptop', 'smartphone'];

$url = new mPowerURL();
$url->url = "http://duckduckgo.com";
$url->title = "Duck Duck Go";
$url->description = "search engine";
array_push($resource->urls,$url);

$file1 = new mPowerFile();
$file1->file = "/home/alex/temp/hews1.csv";
$file1->title = "HEWs";
$file1->description = "all users";
array_push($resource->files,$file1);

$file2 = new mPowerFile();
$file2->file = "/home/alex/temp/tutors.csv";
$file2->title = "Tutors";
$file2->description = "all tutors";
array_push($resource->files,$file2);

$api->add_resource($resource);
		
?>