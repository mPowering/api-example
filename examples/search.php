<?php 

include_once '../mpower-api/api.php';

 if (isset($_POST['q'])){
	$api = new mPowerAPI('http://localhost:8000','demo','39b4043c69b8db27ddba761ba82479d00c8ccbb1');
	$results =  $api->search($_POST['q']);
 }
?>
<html>
<head>
</head>
<body>

<h2>Demo of searching mPowering using the API</h2>
<form action=""  method ="post">

<input type="text" name="q" id="q" value="<?php  if (isset($_POST['q'])) {echo $_POST['q'];} ?>"/>
<input type="submit" name="search" value="search" />
</form>

<div id="results">
<?php  
	if (isset($results)){
		?><ul><?php 
		foreach($results->objects as $r){
			echo "<li><a href='".$r->url. "'>".$r->title."</a></li>";
		}
		?></ul>
		<?php 	} ?>
</div>
</body>
</html>