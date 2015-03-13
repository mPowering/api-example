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
		$status = 0;
		$results = $this->exec('api/v1/resource/search/',['q'=>$query],'get',$status);
		return $results;
	}
	
	
	# add resource
	function add_resource($resource){
		$status = 0;
		$resource_uri = false;
		# add basic info
		$result = $this->exec('api/v1/resource/',['title'=>$resource->title,'description'=>$resource->description],'post',$status);
		switch ($status){
			case 201:
				echo "success \n\n";
				$resource_uri = $result->resource_uri;
				break;
			default:
				echo "Error: ".$results->error;
				return;
		}
		
		# add tags
		foreach ($resource->tags as $tag){
			$status = 0;
			$result = $this->exec('api/v1/tag/',['name'=>$tag],'get',$status);
			print_r($result);
			echo $status;
			switch ($status){
				case 200:
					echo "success \n\n";
					$tag_resource_uri = $result->resource_uri;
					break;
				default:
					echo "Error: ".$result->error;
					break;
			}
		}
		
		# add image
		# add files
		# add urls
		
	}

	# edit/update resource
	
	
	
	private function exec($object, $data_array, $type='post', &$status){
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );

		$temp_url = $this->base_url.$object;
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
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($status == 401){
			$data='{"error":"unauthorized"}';
		} else if ($status == 0){
			$data='{"error":"server not found"}';
		}
		$json = json_decode($data);		
		return $json;
			
	}
	
}

class mPowerResource{
	public $title;
	public $description;
	public $image;
	public $tags = array();
	public $files = array();
	public $urls = array();
}

?>