<?php 

include_once '../orb_api/api.php';

$api = new ORBAPI('http://localhost:8000','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');

// Core resource info
$resource = new ORBResource();
$resource->title = "another test ".date('Y-m-d H:i:s');
$resource->description = "description of something or other";
$resource->image = "/home/alex/temp/image.jpg";

// Append tags
$resource->tags = ['Global Health Media Project', 'Africa', 'Laptop', 'Smartphone', 'Tablet', 'Tablet'];

// Append URLs
$url = new ORBResourceURL();
$url->url = "http://duckduckgo.com";
$url->title = "Duck Duck Go";
$url->description = "search engine";
array_push($resource->urls,$url);

// Append files
$file1 = new ORBResourceFile();
$file1->file = "/home/alex/temp/hews1.csv";
$file1->title = "HEWs";
$file1->description = "all users";
array_push($resource->files,$file1);

$file2 = new ORBResourceFile();
$file2->file = "/home/alex/temp/tutors.csv";
$file2->title = "Tutors";
$file2->description = "all tutors";
array_push($resource->files,$file2);

// Actually add the resource
$api->add_resource($resource);
		
?>