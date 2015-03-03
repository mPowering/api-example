<?php 
 if (isset($_POST['q'])){
 	$curl = curl_init();
 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
 	$temp_url = "http://mpowering.digital-campus.org/api/v1/resource/search/";
 	$temp_url .= "?format=json";
 	$temp_url .= "&username=demo";
 	$temp_url .= "&api_key=39b4043c69b8db27ddba761ba82479d00c8ccbb";
 	$temp_url .= "&q=". $_POST['q'];
 	curl_setopt($curl, CURLOPT_URL, $temp_url );
 	curl_setopt($curl, CURLOPT_HTTPGET, 1 );
 	$data = curl_exec($curl);
 	$results = json_decode($data);
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
