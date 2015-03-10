<?php 

class mPowerAPI {
	
	function search($config, $query){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
		$temp_url = $config->base_url."api/v1/resource/search/";
		$temp_url .= "?format=json";
		$temp_url .= "&username=".$config->username;
		$temp_url .= "&api_key=39b4043c69b8db27ddba761ba82479d00c8ccbb1";
		$temp_url .= "&q=". $_POST['q'];
		curl_setopt($curl, CURLOPT_URL, $temp_url );
		curl_setopt($curl, CURLOPT_HTTPGET, 1 );
		$data = curl_exec($curl);
		return json_decode($data);
	}
	
	
	
}

class mPowerConfig {
	private $base_url;
	private $username;
	private $api_key;
	
	
}

?>