<?php 

class mPowerAPI {
	
	private $base_url;
	private $username;
	private $api_key;
	
	public function __construct($base_url, $username, $api_key){
		
		if (substr($base_url, -strlen('/'))==='/'){
			$this->base_url = $base_url;
		} else {
			$this->base_url = $base_url."/";
		}
		$this->username = $username;
		$this->api_key = $api_key;
	}
	
	function search($query){
		$results = $this->exec('resource/search',['q'=>$query],'get');
		return $results;
	}
	
	
	# add resource
	# check resource with same name doesn't already exist
	# add basic info
	# add image
	# add files
	# add urls
	# organsation
	# core tags
	# other tags
	
	
	
	# edit/update resource
	
	
	
	private function exec($object, $data_array, $type='post'){
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );

		$temp_url = $this->base_url."api/v1/".$object."/";
		$temp_url .= "?format=json";
		$temp_url .= "&username=".$this->username;
		$temp_url .= "&api_key=".$this->api_key;
		
		if($type == 'post'){
			$json = json_encode($data_array);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_POST,1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($json) ));
		} else {
			curl_setopt($curl, CURLOPT_HTTPGET, 1 );
			$temp_url .= "&".http_build_query($data_array);
		}
		curl_setopt($curl, CURLOPT_URL, $temp_url );
		$data = curl_exec($curl);
		$json = json_decode($data);
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		return $json;
			
	}
	
}

?>