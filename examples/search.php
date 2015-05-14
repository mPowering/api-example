<?php 

include_once ('../orb_api/api.php');

 if (isset($_POST['q'])){
	$api = new ORBAPI('http://health-orb.org','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');
	$results =  $api->search($_POST['q']);
 }
?>
<html>
<head>
</head>
<body>

<h2>Demo of searching ORB using the API</h2>
<form action=""  method ="post">

<input type="text" name="q" id="q" value="<?php  if (isset($_POST['q'])) {echo $_POST['q'];} ?>"/>
<input type="submit" name="search" value="search" />
</form>

<div id="results">
<?php  
	if (isset($results)){
		?><ul><?php 
		foreach($results->objects as $r){
			echo "<li><a href='resource.php?id=".$r->id. "'>".$r->title."</a></li>";
		}
		?></ul>
		<?php 	} ?>
</div>
</body>
</html>