<html>
<head>
</head>
<body>
<?php 

include_once ('../orb_api/api.php');

 if (isset($_GET['id'])){
	$api = new ORBAPI('http://health-orb.org','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');
	$resource =  $api->get_resource($_GET['id']);
	
	if ($resource){
		echo "<h2>".$resource->title."</h2>";
		
		if ($resource->image) {
			echo "<img src='" . $resource->image. "'/>";
		}
		echo $resource->description;
		
		echo "<ul>";
		foreach ($resource->tags as $t){
			echo "<li><a href='".$t->tag->url."'>".$t->tag->name."</a></li>";
		}
		echo "</ul>";
	} else {
		echo "<h2>Not found</h2>";
	}
 }
?>



</body>
</html>