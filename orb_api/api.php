<?php 

class ORBAPI {
	
	private $base_url;
	private $username;
	private $api_key;
	private $debug;
	
	public function __construct($base_url, $username, $api_key, $debug=false){
		
		if (substr($base_url, -strlen('/'))==='/'){
			$this->base_url = $base_url;
		} else {
			$this->base_url = $base_url."/";
		}
		$this->username = $username;
		$this->api_key = $api_key;
		$this->debug = $debug;
	}
	
	function search($query){
		$status = 0;
		$data = array('q'=>$query);
		$results = $this->exec('api/v1/resource/search/',$data,'get',$status);
		return $results;
	}
	# get resource
	function get_resource($id){
		$resource_json = $this->exec('api/v1/resource/'.$id."/",null,'get',$status);
		return $resource_json;
	}
	
	# add resource
	function add_resource($resource){
		$status = 0;
		$resource_id = false;
		
		$data = array('title'=>$resource->title,
						'description'=>$resource->description);
		
		# add basic info
		$result = $this->exec('api/v1/resource/',$data,'post',$status);
		switch ($status){
			case 201:
				echo "'".$resource->title ."' created<br/>";
				$resource_id = $result->id;
				break;
			case 401:
				echo "Unauthorized";
				return;
			case 500:
				print_r($result);
				return;
			default:
				echo "Error: ".$result->error;
				return;
		}
		
		# add tags
		foreach ($resource->tags as $tag){
			$status = 0;
			$tag_resource_id = false;
			
			$data = array('name'=>$tag);
			
			$result = $this->exec('api/v1/tag/',$data,'get',$status);
			switch ($status){
				case 200:
					if($result->meta->total_count == 1){
						$tag_resource_id = $result->objects[0]->id;
					} else {
						echo "Tag '".$tag."' not found<br/>";
						continue;
					}
					break;
				default:
					continue;
					break;
			}
			
			if ($tag_resource_id){
				# add tag to resource
				$status = 0;
				$data = array('tag_id'=>$tag_resource_id,
								'resource_id'=>$resource_id);
				
				$result = $this->exec('api/v1/resourcetag/',$data,'post',$status);
				switch ($status){
					case 201:
						echo "Tag '".$tag."' added to resource<br/>";
						break;
					default:
						break;
				}
			}
		}
		
		# add image
		if (isset($resource->image)){
			echo "Uploading image... ";
			$status = 0;
			$post =  array(	'resource_id' => $resource_id,
							'image_file' => new CurlFile($resource->image, 'image/*')
							);
			$this->exec_upload('api/upload/image/',$post,$status);
			switch ($status){
				case 200:
					echo "uploaded<br/>";
					break;
				default:
					echo "ERROR uploading<br/>";
					break;
			}
		}
		
		# add files
		foreach ($resource->files as $file){
			echo "Uploading file '".$file->file."'... ";
			$status = 0;
			$post =  array(	'resource_id' => $resource_id,
					'title' => $file->title,
					'description' => $file->description,
					'resource_file' => new CurlFile($file->file, 'unknown/*')
			);
			$this->exec_upload('api/upload/file/',$post,$status);
			switch ($status){
				case 201:
					echo "uploaded<br/>";
					break;
				default:
					echo "ERROR uploading<br/>";
					break;
			}
		}
		
		# add urls
		foreach ($resource->urls as $url){
			echo "Adding url '".$url->url."'... ";
			$status = 0;
			$post =  array(	'resource_id'=>$resource_id,
							'url'=>$url->url,
							'title'=>$url->title,
							'description'=>$url->description
							);

			$result = $this->exec('api/v1/resourceurl/',$post,'post',$status);
			switch ($status){
				case 201:
					echo "added<br/>";
					break;
				default:
					echo "ERROR adding<br/>";
					break;
			}
		}
		
		echo "Finished adding resource '".$resource->title."'";
	}

	# edit/update resource
	
	
	
	private function exec($object, $data_array, $type='post', &$status){
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );

		$temp_url = $this->base_url.$object;	
		
		if($type == 'post'){
			$json = json_encode($data_array);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_POST,1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: ApiKey '.$this->username.":".$this->api_key, 
															'Content-Type: application/json', 
															'Content-Length: ' . strlen($json) ));
		} else {
			curl_setopt($curl, CURLOPT_HTTPGET, 1 );
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: ApiKey '.$this->username.":".$this->api_key));
			if ($data_array){
				$temp_url .= "?".http_build_query($data_array);
			}
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
	
	
	private function exec_upload($object, $post, &$status){
	
		$temp_url = $this->base_url.$object;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: ApiKey '.$this->username.":".$this->api_key));
		curl_setopt($curl, CURLOPT_URL, $temp_url );
		curl_setopt($curl, CURLOPT_POST,1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if($status == 401){
			$data='{"error":"unauthorized"}';
		} else if ($status == 0){
			$data='{"error":"server not found"}';
		}
		return;
	}
	
}

class ORBResource{
	public $title;
	public $description;
	public $image;
	public $tags = array();
	public $files = array();
	public $urls = array();
}

class ORBResourceFile{
	public $file;
	public $title;
	public $description;
}

class ORBResourceURL{
	public $url;
	public $title;
	public $description;
}

?>